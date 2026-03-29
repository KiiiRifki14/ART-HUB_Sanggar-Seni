<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CostumeUsage;
use App\Models\CostumeRental;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CostumeController extends Controller
{
    /**
     * MENGEMBALIKAN KOSTUM ASET SANGGAR
     * Controller ini akan memicu DUA TRIGGERS SECARA BERANTAI di MySQL:
     * 1. trg_sanggar_costume_return : Menentukan apakah 'returned' / 'damaged' berdasarkan tanggal telat.
     * 2. trg_sync_costume_condition : Men-sinkronisasikan kondisi ke tabel sanggar_costumes (Inventaris) 
     *    misal jika rusak, inventaris mark as 'damaged' / 'maintenance'.
     */
    public function returnSanggarCostume(Request $request, CostumeUsage $usage)
    {
        $request->validate([
            'status'       => 'required|in:checked_out,returned,damaged,lost',
            'damage_notes' => 'required_if:status,damaged,lost|nullable|string'
        ]);

        $usage->update([
            'actual_return_date' => Carbon::now()->format('Y-m-d'),
            // Kita inject inputan status form, TAPI trigger MySQL yang memegang logic mutlaknya.
            'status'             => $request->status,
            'damage_notes'       => $request->damage_notes,
        ]);

        // Karena trigger MySQL mengubah value di belakang layar, kita perlu melakukan refresh
        // pada object Model ini agar sesuai dengan DB State terbaru.
        $usage->refresh();

        $msg = 'Kostum Sanggar dikembalikan.';
        if ($usage->status === 'damaged') {
            $msg .= ' PERINGATAN BIAYA: Terdeteksi Kerusakan. MySQL telah menurunkan status inventaris ke Condition: Damaged.';
        } elseif ($usage->status === 'lost') {
            $msg .= ' BAHAYA: Kostum Hilang. Inventaris di-lock ke Maintenance.';
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * MENGEMBALIKAN KOSTUM SEWA VENDOR LUAR
     * Memanggil TRIGGER: trg_costume_rental_overdue
     */
    public function returnVendorRental(CostumeRental $rental)
    {
        // Peringatan: Kita hanya mengisi 'returned_date'
        // Seluruh kalkulasi status OVERDUE, hari OVERDUE_DAYS, dan OVERDUE_FINE (Rp 50.000 / hari)
        // Dihandle 100% oleh TRIGGER MySQL (trg_costume_rental_overdue).
        
        $rental->update([
            'returned_date' => Carbon::now()->format('Y-m-d'),
        ]);

        // Refresh untuk menarik hasil tembakan Trigger MySQL (denda dll)
        $rental->refresh();

        $msg = 'Pengembalian kostum vendor eksekutif selesai dicatat.';
        
        if ($rental->status === 'overdue') {
            $msg .= sprintf(
                ' [SYSTEM WARNING] Terdeteksi Overdue sebanyak %d hari! Total Denda Otomatis via Trigger MySQL: Rp %s.',
                $rental->overdue_days,
                number_format($rental->overdue_fine, 0, ',', '.')
            );
            return redirect()->back()->with('warning', $msg);
        }

        return redirect()->back()->with('success', $msg);
    }
}
