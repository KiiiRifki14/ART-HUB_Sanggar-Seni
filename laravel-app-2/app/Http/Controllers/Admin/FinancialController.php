<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FinancialRecord;
use App\Models\OperationalCost;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class FinancialController extends Controller
{
    /**
     * Financial Report Overview with optional date range & keyword filter
     */
    public function index(Request $request)
    {
        $query = FinancialRecord::with('event.booking')->latest();

        // Filter pencarian (event code / jenis acara)
        if ($search = $request->input('search')) {
            $query->whereHas('event', function ($q) use ($search) {
                $q->where('event_code', 'like', "%{$search}%")
                  ->orWhereHas('booking', function ($q2) use ($search) {
                      $q2->where('event_type', 'like', "%{$search}%");
                  });
            });
        }

        // Filter rentang tanggal berdasarkan event_date
        if ($dateFrom = $request->input('date_from')) {
            $query->whereHas('event', fn($q) => $q->where('event_date', '>=', $dateFrom));
        }
        if ($dateTo = $request->input('date_to')) {
            $query->whereHas('event', fn($q) => $q->where('event_date', '<=', $dateTo));
        }

        $records = $query->paginate(15)->withQueryString();

        return view('admin.financials.index', compact('records'));
    }

    /**
     * Export Financial Report to PDF
     */
    public function exportPdf()
    {
        $records = FinancialRecord::with('event.booking')->get();
        $pdf = Pdf::loadView('admin.financials.pdf', compact('records'))
                  ->setPaper('a4', 'landscape');
        return $pdf->download('Laporan_Keuangan_ARTHUB_'.now()->format('Ymd').'.pdf');
    }

    /**
     * POST-EVENT LIST: Daftar event yang perlu input biaya lapangan
     */
    public function postEventList()
    {
        // Otomatis tandai event yang sudah lewat tanggalnya sebagai Selesai
        try {
            \Illuminate\Support\Facades\Artisan::call('events:auto-complete');
        } catch (\Exception $e) {
            // Abaikan jika gagal
        }

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
            'updated_by'    => Auth::id()
        ]);

        // Update total realisasi lapangan pada FinancialRecord
        if ($cost->financialRecord) {
            $cost->financialRecord->update([
                'actual_operational_cost' => $cost->financialRecord->operationalCosts()->sum('actual_amount')
            ]);
        }

        $msg = 'Realisasi biaya berhasil diperbarui.';
        if ($oldAmount !== $newAmount) {
            $msg .= ' [SISTEM KEAMANAN] Perubahan nilai mendadak telah berhasil di-Audit (disimpan) oleh Database untuk mencegah kebocoran RAB.';
        }

        return redirect()->back()->with('success', $msg);
    }

    /**
     * TAMBAH BIAYA OPERASIONAL BARU (Fix Bug #4 - Tumpang Tindih Post-Event)
     * Admin bisa menambah item pengeluaran baru (Honor Kru Ekstra, dll)
     */
    public function storeOperationalCost(Request $request, \App\Models\Event $event)
    {
        $request->validate([
            'category'         => 'required|string|max:100',
            'description'      => 'required|string|max:255',
            'estimated_amount' => 'required|numeric|min:0',
            'actual_amount'    => 'required|numeric|min:0',
        ]);

        if (!$event->financialRecord) {
            return redirect()->back()->with('error', 'Data keuangan event belum terbentuk. Pastikan DP sudah dikonfirmasi.');
        }

        $event->financialRecord->operationalCosts()->create([
            'category'         => $request->category,
            'description'      => $request->description,
            'estimated_amount' => $request->estimated_amount,
            'actual_amount'    => $request->actual_amount,
            'updated_by'       => Auth::id(),
        ]);

        // Update total realisasi lapangan pada FinancialRecord
        $event->financialRecord->update([
            'actual_operational_cost' => $event->financialRecord->operationalCosts()->sum('actual_amount')
        ]);

        return redirect()->back()->with('success', 'Biaya operasional baru berhasil ditambahkan.');
    }
}
