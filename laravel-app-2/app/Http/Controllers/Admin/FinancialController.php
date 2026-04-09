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
     * Financial Report Overview
     */
    public function index()
    {
        $records = FinancialRecord::with('event.booking')->get();
        return view('admin.financials.index', compact('records'));
    }

    /**
     * POST-EVENT LIST: Daftar event yang perlu input biaya lapangan
     */
    public function postEventList()
    {
        // Event yang sudah lewat tanggalnya (selesai) atau status completed
        $events = \App\Models\Event::with(['booking', 'financialRecord.operationalCosts'])
            ->where(function ($q) {
                $q->where('event_date', '<', now()->toDateString())
                  ->orWhere('status', 'completed');
            })
            ->orderBy('event_date', 'desc')
            ->get();

        return view('admin.financials.post-event-list', compact('events'));
    }

    /**
     * Post-Event Update (detail biaya operasional per event)
     */
    public function postEvent(\App\Models\Event $event)
    {
        $event->load('financialRecord.operationalCosts');
        return view('admin.financials.post-event', compact('event'));
    }

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
