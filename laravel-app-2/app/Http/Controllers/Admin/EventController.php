<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Personnel;
use App\Models\FeeReference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Daftar Semua Event
     */
    public function index()
    {
        $events = Event::with('booking')->latest('event_date')->get();
        return view('admin.events.index', compact('events'));
    }

    /**
     * EVENT MONITORING: Dashboard operasional lapangan
     */
    public function monitoring(Request $request)
    {
        $query = Event::with(['booking', 'personnel'])
            ->orderBy('event_date', 'asc');

        // Filter by status if provided
        $filter = $request->input('filter', 'all');
        if ($filter !== 'all') {
            $query->whereHas('booking', fn($q) => $q->where('status', $filter));
        }

        $events = $query->get();

        // Summary counts
        $summary = [
            'total'     => $events->count(),
            'negotiation' => Event::whereHas('booking', fn($q) => $q->where('status', 'pending'))->count(),
            'pending_dp'  => Event::whereHas('booking', fn($q) => $q->where('status', 'confirmed'))->count(),
            'confirmed'   => Event::whereHas('booking', fn($q) => $q->whereIn('status', ['dp_paid', 'paid_full']))->count(),
            'completed'   => Event::whereHas('booking', fn($q) => $q->where('status', 'completed'))->count(),
        ];

        return view('admin.events.monitoring', compact('events', 'summary', 'filter'));
    }

    /**
     * EVENT MONITORING DETAIL: Detail operasional lapangan per event
     */
    public function monitoringDetail(Event $event)
    {
        $event->load(['booking', 'personnel.user', 'financialRecord', 'costumeRentals.vendor']);
        return view('admin.events.monitoring-detail', compact('event'));
    }

    /**
     * Detail Event + Monitoring
     */
    public function show(Event $event)
    {
        $event->load(['booking', 'personnel.user', 'financialRecord']);
        return view('admin.events.show', compact('event'));
    }

    /**
     * Update Koordinat Geolocation untuk Event
     */
    public function updateCoordinates(Request $request, Event $event)
    {
        $request->validate([
            'latitude'  => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $event->update([
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return redirect()->back()->with('success', 'Koordinat GPS untuk event berhasil di-set!');
    }

    /**
     * TAMPILKAN HALAMAN PLOTTING (Smart Plotting)
     * Akan memanggil Stored Procedure MySQL yang menggunakan CURSOR
     * untuk mengecek tabrakan jadwal dengan event lain, latihan, atau pekerjaan utama.
     */
    public function plotting(Event $event)
    {
        $event->load(['booking', 'personnel.user', 'financialRecord']);

        $date  = \Carbon\Carbon::parse($event->event_date)->format('Y-m-d');
        $start = is_string($event->event_start) ? $event->event_start : \Carbon\Carbon::parse($event->event_start)->format('H:i:s');
        $end   = is_string($event->event_end)   ? $event->event_end   : \Carbon\Carbon::parse($event->event_end)->format('H:i:s');

        // Jalankan Stored Procedure untuk deteksi konflik jadwal.
        // Dibungkus try-catch agar halaman tetap tampil walau SP belum ada di DB.
        $spData = null;
        try {
            DB::statement(
                'CALL sp_check_personnel_availability(?, ?, ?, @p_avail_count, @p_col_count, @p_col_details, @p_avail_details)',
                [$date, $start, $end]
            );
            $spResult = DB::select('SELECT
                @p_avail_count   as available_count,
                @p_col_count     as collision_count,
                @p_col_details   as collision_details,
                @p_avail_details as available_details
            ');
            $spData = $spResult[0] ?? null;
        } catch (\Exception $e) {
            // SP belum terdaftar / DB error: $spData tetap null, form tetap tampil
        }

        $personnel = Personnel::with('user')->where('is_active', true)->get();
        $fees      = FeeReference::where('is_active', true)->get();

        return view('admin.events.plotting', compact('event', 'personnel', 'fees', 'spData'));
    }

    /**
     * SIMPAN PLOTTING & TRIGGER SQL FUNCTION 'fn_estimate_total_honor'
     * Proses akan dibungkus Transaction untuk mencegah separuh personel gagal tersimpan.
     */
    public function storePlotting(Request $request, Event $event)
    {
        $request->validate([
            'personnel'                        => 'required|array|min:1',
            'personnel.*.id'                   => 'required|exists:personnel,id',
            'personnel.*.fee_reference_id'     => 'required|exists:fee_references,id',
            'personnel.*.role_in_event'        => 'required|string',
            // Kita juga bisa tambahkan field 'override_fee' dsb jika Pak Yat ingin mengganti manual
        ]);

        try {
            DB::transaction(function () use ($request, $event) {
                // 1. Validasi Bentrok Jadwal via Stored Procedure sebelum menyetujui plot
                $date = \Carbon\Carbon::parse($event->event_date)->format('Y-m-d');
                $start = is_string($event->event_start) ? $event->event_start : \Carbon\Carbon::parse($event->event_start)->format('H:i:s');
                $end = is_string($event->event_end) ? $event->event_end : \Carbon\Carbon::parse($event->event_end)->format('H:i:s');

                DB::statement('CALL sp_check_personnel_availability(?, ?, ?, @p_avail_count, @p_col_count, @p_col_details, @p_avail_details)', 
                    [$date, $start, $end]
                );
                $spResult = DB::select('SELECT @p_col_details as collision_details');
                $collisionString = $spResult[0]->collision_details ?? '';
                
                // Ekstrak ID yang bentrok dari string detail (contoh format: "ID:2 - Event Lain, ID:5 - PNS")
                // Kita akan cegah personel yang ID-nya ada di string tersebut.
                $collidingIds = [];
                if (!empty($collisionString)) {
                    preg_match_all('/ID:(\d+)/', $collisionString, $matches);
                    $collidingIds = $matches[1] ?? [];
                }

                foreach ($request->personnel as $p) {
                    if (in_array((string)$p['id'], $collidingIds)) {
                        throw new \Exception("Personel dengan ID {$p['id']} memiliki jadwal bentrok (Pekerjaan/Event) pada waktu tersebut. Plotting digagalkan oleh sistem SQL.");
                    }
                }

                // 2. Bersihkan Data Plotting Lama (jika Pak Yat sedang Re-Plotting)
                $event->personnel()->detach();

                // 3. Insert Plotting Baru dari Loop Input
                foreach ($request->personnel as $p) {
                    $feeRef = FeeReference::findOrFail($p['fee_reference_id']);
                    
                    // Fee bawaan dari FeeReference, atau ambil dari input jika ada override manual
                    $finalFee = $p['override_fee'] ?? $feeRef->base_fee;

                    $event->personnel()->attach($p['id'], [
                        'fee_reference_id' => $feeRef->id,
                        'role_in_event'    => $p['role_in_event'],
                        'fee'              => $finalFee,
                        'status'           => 'assigned'
                    ]);
                }

                // 4. GUNAKAN SQL FUNCTION "fn_estimate_total_honor"
                $query = DB::select('SELECT fn_estimate_total_honor(?) as estimated_total', [$event->id]);
                $estimatedHonor = $query[0]->estimated_total ?? 0;

                // 5. Update tabel Events
                $event->update([
                    'estimated_total_honor' => $estimatedHonor,
                    'status' => 'ready' 
                ]);

                // 6. Update bagian Keuangan (Financial Records) 
                if ($event->financialRecord) {
                    $event->financialRecord->update([
                        'total_personnel_honor' => $estimatedHonor
                    ]);
                }
            });

            return redirect()->route('admin.events.show', $event->id)
                             ->with('success', 'Smart Plotting sukses. Estimasi honor personel otomatis dikalkulasi via SQL Function!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses plotting: ' . $e->getMessage());
        }
    }
}
