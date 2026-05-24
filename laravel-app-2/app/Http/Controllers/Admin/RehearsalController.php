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
        $rehearsals = Rehearsal::with('event.booking')->orderBy('rehearsal_date', 'asc')->get();
        return view('admin.rehearsals.index', compact('rehearsals'));
    }

    /**
     * Menjadwalkan Latihan (3-Stage Logic)
     */
    public function store(Request $request, Event $event)
    {
        $request->validate([
            'type' => 'required|in:musik,tari,gabungan',
            'rehearsal_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'location' => 'required|string',
        ]);

        // CEK KONFLIK JADWAL VIA STORED PROCEDURE
        // Sama dengan Smart Plotting, pastikan Latihan tidak menabrak Day-Job personel
        // atau jadwal event sanggar yang lain.
        DB::statement('CALL sp_check_personnel_availability(?, ?, ?, @p_avail, @p_col, @p_col_det, @p_avail_det)', [
            $request->rehearsal_date,
            $request->start_time,
            $request->end_time
        ]);

        // FIX F-04: Betulkan penamaan variabel SP hasil kembalian (@p_col dan @p_col_det)
        $spResult = DB::select('SELECT @p_col as collision_count, @p_col_det as collision_details');
        
        $collisionCount = $spResult[0]->collision_count ?? 0;
        $collisionDetails = $spResult[0]->collision_details;

        // FIX F-04: Jika terjadi bentrok, berikan conflict_warning terlebih dahulu
        // kecuali jika admin secara sadar mencentang force_save / mengonfirmasi tetap simpan.
        if ($collisionCount > 0 && !$request->has('force_save')) {
            return redirect()->back()
                ->withInput()
                ->with('conflict_warning', '⚠️ Bentrok Jadwal! Latihan ini bentrok dengan ' . $collisionCount . ' personel: ' . $collisionDetails . '. Centang "Tetap Simpan" jika Anda ingin memaksakan jadwal ini.');
        }

        $rehearsal = Rehearsal::create([
            'event_id' => $event->id,
            'type' => $request->type,
            'rehearsal_date' => $request->rehearsal_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'location' => $request->location,
            'notes' => $request->notes,
        ]);

        $msg = 'Jadwal latihan (' . strtoupper($request->type) . ') berhasil dibuat!';
        if ($collisionCount > 0) {
            $msg .= ' (Disimpan dengan paksaan konflik jadwal)';
            return redirect()->back()->with('warning', $msg);
        }

        return redirect()->back()->with('success', $msg);
    }
}
