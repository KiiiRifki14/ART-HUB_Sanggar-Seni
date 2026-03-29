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
     * TAMPILKAN HALAMAN PLOTTING (Smart Plotting)
     * Akan memanggil Stored Procedure MySQL yang menggunakan CURSOR
     * untuk mengecek tabrakan jadwal dengan event lain, latihan, atau pekerjaan utama.
     */
    public function plotting(Event $event)
    {
        // Format tanggal dan waktu ke SQL format
        $date = $event->event_date->format('Y-m-d');
        // Jika cast ditarik sebagai string (biasanya H:i:s), kita langsung ambil substring jika perlu
        $start = is_string($event->event_start) ? $event->event_start : $event->event_start->format('H:i:s');
        $end = is_string($event->event_end) ? $event->event_end : $event->event_end->format('H:i:s');

        // EKSEKUSI STORED PROCEDURE (Basis Data 2)
        // sp_check_personnel_availability menggunakan Cursor mengiterasi 12 personel untuk mendeteksi *Collision*
        DB::statement('CALL sp_check_personnel_availability(?, ?, ?, @p_avail_count, @p_col_count, @p_col_details, @p_avail_details)', 
            [$date, $start, $end]
        );
        
        // Tarik variabel OUT dari MySQL
        $spResult = DB::select('SELECT 
            @p_avail_count as available_count, 
            @p_col_count as collision_count, 
            @p_col_details as collision_details, 
            @p_avail_details as available_details
        ');
        
        $spData = $spResult[0];

        // Ambil data dari tabel untuk ditampilkan di Dropdown mapping
        $personnel = Personnel::with('user')->where('is_active', true)->get();
        $fees = FeeReference::where('is_active', true)->get();
        
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
                // 1. Bersihkan Data Plotting Lama (jika Pak Yat sedang Re-Plotting)
                $event->personnel()->detach();

                // 2. Insert Plotting Baru dari Loop Input
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

                // 3. GUNAKAN SQL FUNCTION (Basis Data 2) "fn_estimate_total_honor"
                // Sengaja mendelegasikan beban SUM/Kalkulasi ke server MySQL
                $query = DB::select('SELECT fn_estimate_total_honor(?) as estimated_total', [$event->id]);
                $estimatedHonor = $query[0]->estimated_total ?? 0;

                // 4. Update tabel Events
                $event->update([
                    'estimated_total_honor' => $estimatedHonor,
                    'status' => 'ready' // Berubah dari 'planning' -> 'ready'
                ]);

                // 5. Update bagian Keuangan (Financial Records) 
                // Agar Budget Operasional & Evaluasi Net Profit bisa disesuaikan
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
