<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Rehearsal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RehearsalController extends Controller
{
    public function index()
    {
        $total = Rehearsal::count();
        $upcoming = Rehearsal::where('rehearsal_date', '>=', now()->toDateString())->count();
        $past = $total - $upcoming;

        $rehearsals = Rehearsal::with('event.booking')->orderBy('rehearsal_date', 'asc')->paginate(10);
        
        // Ambil data event yang aktif (bukan completed/cancelled) untuk pilihan di modal dropdown
        $events = \App\Models\Event::with('booking')
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->latest()
            ->get();

        return view('admin.rehearsals.index', compact('rehearsals', 'events', 'total', 'upcoming', 'past'));
    }

    /**
     * Menampilkan halaman form tambah jadwal latihan (dedicated page, bukan modal)
     */
    public function create()
    {
        $events = \App\Models\Event::with('booking')
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->latest()
            ->get();

        return view('admin.rehearsals.create', compact('events'));
    }

    /**
     * Menjadwalkan Latihan (3-Stage Logic with fault-tolerant error handling)
     *
     * ARSITEKTUR:
     * Stage 1 → Validasi input form
     * Stage 2 → Panggil SP pengecekan bentrok jadwal personel
     * Stage 3 → Simpan rehearsal jika tidak ada bentrok (atau admin paksa simpan)
     */
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'type'           => 'required|in:musik,tari,gabungan',
            'rehearsal_date' => 'required|date',
            'start_time'     => 'required',
            'end_time'       => 'required',
            'location'       => 'required|string',
            'latitude'       => 'nullable|numeric|between:-90,90',
            'longitude'      => 'nullable|numeric|between:-180,180',
        ]);

        // ── STAGE 2: CEK KONFLIK JADWAL via Stored Procedure ─────────────────
        $collisionCount   = 0;
        $collisionDetails = '';

        try {
            DB::statement(
                'CALL sp_check_personnel_availability(?, ?, ?, @p_avail, @p_col, @p_col_det, @p_avail_det)',
                [
                    $request->rehearsal_date,
                    $request->start_time,
                    $request->end_time,
                ]
            );

            $spResult = DB::select('SELECT @p_col as collision_count, @p_col_det as collision_details');

            $collisionCount   = (int) ($spResult[0]->collision_count ?? 0);
            $collisionDetails = $spResult[0]->collision_details ?? '';

        } catch (\Exception $e) {
            // SP gagal (tidak ada, timeout, lock, dll) → log dan lanjut simpan tanpa cek konflik
            \Illuminate\Support\Facades\Log::error('[RehearsalController] SP sp_check_personnel_availability GAGAL: ' . $e->getMessage(), [
                'date'  => $request->rehearsal_date,
                'start' => $request->start_time,
                'end'   => $request->end_time,
            ]);

            // Lanjut menyimpan tanpa info konflik, beri warning ke admin
            return $this->saveRehearsal($request, $event, 0, '')
                ->with('warning', '⚠️ Jadwal disimpan, tapi pengecekan konflik personel gagal dijalankan. Periksa log server. Error: ' . $e->getMessage());
        }

        // ── STAGE 3A: Jika ada bentrok dan admin belum konfirmasi ─────────────
        if ($collisionCount > 0 && !$request->has('force_save')) {
            return redirect()->back()
                ->withInput()
                ->with('conflict_warning',
                    '⚠️ Bentrok Jadwal! Latihan ini bentrok dengan ' . $collisionCount .
                    ' personel: ' . $collisionDetails .
                    ' Centang "Tetap Simpan" jika Anda ingin memaksakan jadwal ini.'
                );
        }

        // ── STAGE 3B: Simpan rehearsal ────────────────────────────────────────
        return $this->saveRehearsal($request, $event, $collisionCount, $collisionDetails);
    }

    /**
     * Menampilkan form edit jadwal latihan
     */
    public function edit(Rehearsal $rehearsal)
    {
        // Guard: latihan yang sudah selesai tidak bisa diedit
        $dateOnly    = \Carbon\Carbon::parse($rehearsal->rehearsal_date)->toDateString();
        $endTimeOnly = \Carbon\Carbon::parse($rehearsal->end_time ?? '23:59:00')->format('H:i:s');
        $endDateTime = \Carbon\Carbon::parse($dateOnly . ' ' . $endTimeOnly);
        if ($endDateTime->isPast()) {
            return redirect()->route('admin.rehearsals.index')
                ->with('error', '🔒 Latihan "' . \Carbon\Carbon::parse($rehearsal->rehearsal_date)->translatedFormat('d F Y') . '" sudah selesai dan tidak bisa diedit.');
        }

        $events = \App\Models\Event::with('booking')
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->latest()
            ->get();
        return view('admin.rehearsals.edit', compact('rehearsal', 'events'));
    }

    /**
     * Update jadwal latihan (tanggal, jam, lokasi, tipe, catatan)
     */
    public function update(Request $request, Rehearsal $rehearsal)
    {
        // Guard: double protection – blokir update via POST langsung jika latihan sudah selesai
        $dateOnly    = \Carbon\Carbon::parse($rehearsal->rehearsal_date)->toDateString();
        $endTimeOnly = \Carbon\Carbon::parse($rehearsal->end_time ?? '23:59:00')->format('H:i:s');
        $endDateTime = \Carbon\Carbon::parse($dateOnly . ' ' . $endTimeOnly);
        if ($endDateTime->isPast()) {
            return redirect()->route('admin.rehearsals.index')
                ->with('error', '🔒 Latihan pada ' . \Carbon\Carbon::parse($rehearsal->rehearsal_date)->translatedFormat('d F Y') . ' sudah selesai, tidak bisa diubah.');
        }

        $request->validate([
            'type'           => 'required|in:gabungan,tari,musik',
            'rehearsal_date' => 'required|date',
            'start_time'     => 'required',
            'end_time'       => 'required|after:start_time',
            'location'       => 'required|string',
            'latitude'       => 'nullable|numeric|between:-90,90',
            'longitude'      => 'nullable|numeric|between:-180,180',
        ], [
            'end_time.after' => 'Jam selesai harus setelah jam mulai.',
        ]);

        $rehearsal->update([
            'type'           => $request->type,
            'rehearsal_date' => $request->rehearsal_date,
            'start_time'     => $request->start_time,
            'end_time'       => $request->end_time,
            'location'       => $request->location,
            'latitude'       => $request->latitude,
            'longitude'      => $request->longitude,
            'notes'          => $request->notes,
        ]);

        return redirect()->route('admin.rehearsals.index')
            ->with('success', 'Jadwal latihan berhasil diperbarui!');
    }

    /**
     * Helper: simpan record Rehearsal dan return redirect dengan flash message.
     * Dibungkus try-catch tersendiri agar error DB di level insert pun tertangkap.
     */
    private function saveRehearsal(Request $request, Event $event, int $collisionCount, string $collisionDetails)
    {
        try {
            Rehearsal::create([
                'event_id'       => $event->id,
                'type'           => $request->type,
                'rehearsal_date' => $request->rehearsal_date,
                'start_time'     => $request->start_time,
                'end_time'       => $request->end_time,
                'location'       => $request->location,
                'latitude'       => $request->latitude,
                'longitude'      => $request->longitude,
                'notes'          => $request->notes,
            ]);

            // Update parent event status to 'rehearsal' (LATIHAN) if currently in planning or ready status
            if (in_array($event->status, ['planning', 'ready'])) {
                $event->update(['status' => 'rehearsal']);
            }

            $msg = 'Jadwal latihan (' . strtoupper($request->type) . ') berhasil dibuat!';

            if ($collisionCount > 0) {
                $msg .= ' (Disimpan dengan konflik jadwal yang dipaksakan)';
                return redirect()->route('admin.rehearsals.index')->with('warning', $msg);
            }

            return redirect()->route('admin.rehearsals.index')->with('success', $msg);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('[RehearsalController] Gagal menyimpan rehearsal: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', '❌ Gagal menyimpan jadwal latihan: ' . $e->getMessage());
        }
    }
}

