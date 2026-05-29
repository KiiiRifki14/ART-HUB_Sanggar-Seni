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
     * Costume Rental Overview
     */
    public function index()
    {
        $sanggarCostumes = \App\Models\SanggarCostume::all();
        $vendorRentals = \App\Models\CostumeRental::with(['event', 'vendor'])->latest()->get();
        return view('admin.costumes.index', compact('sanggarCostumes', 'vendorRentals'));
    }
    // ==========================================
    // BAGIAN 1: ASET KOSTUM SANGGAR
    // ==========================================

    public function createAsset()
    {
        // Menampilkan form tambah aset sanggar
        return view('admin.costumes.create-asset');
    }

    public function storeAsset(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'name'      => 'required|string|max:255',
            'category'  => 'required|string|max:100',
            'quantity'  => 'required|integer|min:1',
            'condition' => 'required|in:good,damaged,maintenance',
        ]);

        // Simpan ke database
        \App\Models\SanggarCostume::create([
            'name'      => $request->name,
            'category'  => $request->category,
            'quantity'  => $request->quantity,
            'condition' => $request->condition,
        ]);

        return redirect()->route('admin.costumes.index')
            ->with('success', 'Aset Sanggar baru berhasil ditambahkan!');
    }

    public function editAsset(\App\Models\SanggarCostume $costume)
    {
        return view('admin.costumes.edit-asset', compact('costume'));
    }

    public function updateAsset(Request $request, \App\Models\SanggarCostume $costume)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'category'  => 'required|string|max:100',
            'quantity'  => 'required|integer|min:1',
            'condition' => 'required|in:good,damaged,maintenance',
        ]);

        $costume->update([
            'name'      => $request->name,
            'category'  => $request->category,
            'quantity'  => $request->quantity,
            'condition' => $request->condition,
        ]);

        return redirect()->route('admin.costumes.index')
            ->with('success', 'Aset Sanggar berhasil diperbarui!');
    }

    public function destroyAsset(\App\Models\SanggarCostume $costume)
    {
        $costume->delete();

        return redirect()->route('admin.costumes.index')
            ->with('success', 'Aset Sanggar berhasil dihapus!');
    }

    // ==========================================
    // BAGIAN 2: SEWA VENDOR EKSTERNAL
    // ==========================================

    public function createRental()
    {
        // Ambil data event yang belum selesai dan belum lewat tanggal pelaksanaan (upcoming / active)
        $events = \App\Models\Event::with('booking')
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->where('event_date', '>=', Carbon::today()->toDateString())
            ->orderBy('event_date', 'asc')
            ->get();
        $vendors = \App\Models\CostumeVendor::all();

        // Menampilkan form tambah sewaan
        return view('admin.costumes.create-rental', compact('events', 'vendors'));
    }

    public function storeRental(Request $request)
    {
        // Validasi input
        $request->validate([
            'event_id'          => [
                'required',
                'exists:events,id',
                function ($attribute, $value, $fail) {
                    $event = \App\Models\Event::find($value);
                    if ($event) {
                        if (in_array($event->status, ['completed', 'cancelled'])) {
                            $fail('Event ini sudah selesai atau dibatalkan.');
                        } elseif (Carbon::parse($event->event_date)->lt(Carbon::today())) {
                            $fail('Event ini sudah lewat tanggal pelaksanaannya.');
                        }
                    }
                }
            ],
            'costume_vendor_id' => 'required|exists:costume_vendors,id',
            'costume_type'      => 'required|string|max:255',
            'quantity'          => 'required|integer|min:1',
            'rental_cost'       => 'required|numeric|min:0',
            'due_date'          => 'required|date',
        ]);

        // Simpan transaksi sewa ke database
        $rental = \App\Models\CostumeRental::create([
            'event_id'          => $request->event_id,
            'vendor_id'         => $request->costume_vendor_id,
            'costume_type'      => $request->costume_type,
            'quantity'          => $request->quantity,
            'rental_cost'       => $request->rental_cost,
            'due_date'          => $request->due_date,
            'status'            => 'rented', // Default status saat baru menyewa
        ]);

        // Auto-create Operational Cost untuk sewa vendor ini
        $financialRecord = \App\Models\FinancialRecord::where('event_id', $request->event_id)->first();
        if ($financialRecord && $request->rental_cost > 0) {
            \App\Models\OperationalCost::create([
                'financial_record_id' => $financialRecord->id,
                'category'            => 'sewa_kostum',
                'description'         => 'Sewa Vendor: ' . $request->costume_type . ' (' . $request->quantity . ' pcs)',
                'estimated_amount'    => $request->rental_cost,
                'actual_amount'       => $request->rental_cost,
                'updated_by'          => \Illuminate\Support\Facades\Auth::id(),
            ]);

            // Update total pengeluaran aktual di FinancialRecord
            $totalActual = \App\Models\OperationalCost::where('financial_record_id', $financialRecord->id)->sum('actual_amount');
            $financialRecord->update(['actual_operational_cost' => $totalActual]);
        }

        return redirect()->route('admin.costumes.index')
            ->with('success', 'Data penyewaan kostum vendor berhasil dicatat dan biaya operasional ditambahkan!');
    }
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
    // ==========================================
    // BAGIAN 3: API UNTUK AJAX CALL
    // ==========================================

    public function storeVendorApi(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Simpan vendor baru ke database dengan default city 'Subang' agar tidak melanggar constraint non-null DB
        $vendor = \App\Models\CostumeVendor::create([
            'name' => $request->name,
            'city' => 'Subang',
        ]);

        // Kembalikan respons dalam bentuk JSON (agar bisa dibaca oleh JavaScript)
        return response()->json([
            'success' => true,
            'vendor'  => $vendor
        ]);
    }
}
