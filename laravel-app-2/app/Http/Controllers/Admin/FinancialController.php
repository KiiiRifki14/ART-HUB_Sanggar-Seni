<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinancialRecord;
use App\Models\OperationalCost;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FinancialController extends Controller
{
    /**
     * Mengupdate / Merubah Biaya Operasional.
     * Memicu TRIGGER: "trg_operational_cost_audit" (Pencegahan Biaya Siluman)
     */
    public function updateOperationalCost(Request $request, OperationalCost $cost)
    {
        $request->validate([
            'actual_amount' => 'required|numeric|min:0',
        ]);

        $oldAmount = $cost->actual_amount;
        $newAmount = $request->actual_amount;

        $cost->update([
            'actual_amount' => $newAmount,
            'updated_by'    => Auth::id() // Trigger akan mengambil User ID ini untuk Audit Trail
        ]);

        // Karena trigger trg_operational_cost_audit bekerja setelah aksi UPDATE,
        // jika nilai (actual_amount) berubah, trigger MySQL secara ghaib akan memasukkan
        // Log ke dalam tabel 'financial_audits' tanpa perlu satu baris pun kode Eloquent di sini!
        
        $msg = 'Realisasi biaya berhasil diperbarui.';
        if ($oldAmount !== $newAmount) {
            $msg .= ' [SISTEM KEAMANAN] Perubahan nilai mendadak telah berhasil di-Audit (disimpan) oleh Database untuk mencegah kebocoran RAB.';
        }

        return redirect()->back()->with('success', $msg);
    }
}
