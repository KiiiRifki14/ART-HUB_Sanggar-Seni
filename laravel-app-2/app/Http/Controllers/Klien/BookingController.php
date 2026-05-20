<?php

namespace App\Http\Controllers\Klien;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\FeeReference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Dashboard / Riwayat Booking Klien
     */
    public function index()
    {
        $bookings = Booking::where('client_id', Auth::id())->latest()->get();
        return view('klien.dashboard', compact('bookings'));
    }

    /**
     * Form Pendaftaran Booking Baru (Self-Service) – Dynamic dari ServiceCatalog
     */
    public function create()
    {
        $catalogs = \App\Models\ServiceCatalog::where('is_active', true)
                        ->orderBy('sort_order')->orderBy('id')->get();
        return view('klien.bookings.create', compact('catalogs'));
    }

    /**
     * Simpan Booking Baru
     */
    public function store(Request $request)
    {
        // Validasi catalog ID dari DB (aman dari manipulasi)
        $catalog = \App\Models\ServiceCatalog::where('is_active', true)->find($request->service_catalog_id);
        if (!$catalog) {
            return back()->withErrors(['service_catalog_id' => 'Paket yang dipilih tidak valid.'])->withInput();
        }

        $request->validate([
            'service_catalog_id' => 'required|exists:service_catalogs,id',
            'event_date'   => [
                'required',
                'date',
                'after:today',
                function ($attribute, $value, $fail) {
                    $exists = Booking::where('event_date', $value)
                        ->whereIn('status', ['dp_paid', 'confirmed', 'paid_full', 'completed'])
                        ->exists();
                    if ($exists) {
                        $fail('Tanggal ' . \Carbon\Carbon::parse($value)->format('d M Y') . ' sudah penuh/di-booking. Silakan pilih tanggal lain.');
                    }
                },
            ],
            'event_start'  => 'required',
            'event_end'    => 'required',
            'venue'        => 'required|string',
            'client_phone' => 'required|string',
        ]);

        // Harga dari catalog di server — immune dari manipulasi
        $basePrice = $catalog->price;
        $dpAmount  = $basePrice * 0.50;

        $booking = Booking::create([
            'client_id'          => Auth::id(),
            'client_name'        => Auth::user()->name,
            'client_phone'       => $request->client_phone,
            'event_type'         => $catalog->name,
            'service_catalog_id' => $catalog->id,
            'event_date'         => $request->event_date,
            'event_start'        => $request->event_start,
            'event_end'          => $request->event_end,
            'venue'              => $request->venue,
            'venue_address'      => $request->venue_address,
            'booking_source'     => 'web',
            'status'             => 'pending',
            'total_price'        => $basePrice,
            'dp_amount'          => $dpAmount,
        ]);

        return redirect()->route('klien.bookings.show', $booking->id)
            ->with('success', 'Booking berhasil diajukan! Tim kami akan meninjau pesanan Anda.');
    }

    /**
     * Negotiation Hub (Status Booking & Harga)
     */
    public function show($id)
    {
        $booking = Booking::where('id', $id)->where('client_id', Auth::id())->firstOrFail();
        return view('klien.bookings.show', compact('booking'));
    }

    /**
     * Upload Bukti Transportasi Pembayaran
     */
    public function uploadProof(Request $request, $id)
    {
        $booking = Booking::where('id', $id)->where('client_id', Auth::id())->firstOrFail();

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:5120', // Maks 5MB (MIME check)
        ]);

        // Simpan file ke storage public (Contoh sederhana)
        $path = $request->file('payment_proof')->store('proofs', 'public');

        $booking->update([
            'payment_proof' => $path
        ]);

        return redirect()->back()->with('success', 'Bukti bayar berhasil diunggah! Menunggu konfirmasi Admin.');
    }

    /**
     * Upload Bukti Pelunasan
     */
    public function uploadFullProof(Request $request, $id)
    {
        $booking = Booking::where('id', $id)->where('client_id', Auth::id())->firstOrFail();

        if (!in_array($booking->status, ['dp_paid', 'confirmed'])) {
            return redirect()->back()->with('error', 'Status pesanan belum valid untuk pelunasan.');
        }

        $request->validate([
            'full_payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $path = $request->file('full_payment_proof')->store('proofs', 'public');

        $booking->update([
            'full_payment_proof' => $path
        ]);

        return redirect()->back()->with('success', 'Bukti pelunasan berhasil diunggah! Menunggu verifikasi dari Admin.');
    }
}
