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
     * Form Pendaftaran Booking Baru (Self-Service)
     */
    public function create()
    {
        $packages = [
            'jaipong' => ['name' => 'Tari Jaipong', 'base_price' => 3500000],
            'rampak_gendang' => ['name' => 'Rampak Gendang', 'base_price' => 4500000],
            'mapag_panganten' => ['name' => 'Mapag Panganten', 'base_price' => 5000000],
            'kacapi_suling' => ['name' => 'Kacapi Suling', 'base_price' => 2500000],
        ];

        return view('klien.bookings.create', compact('packages'));
    }

    /**
     * Simpan Booking Baru
     */
    public function store(Request $request)
    {
        // Daftar harga paket (sumber kebenaran di server, bukan dari JS)
        $packages = [
            'jaipong'          => ['name' => 'Tari Jaipong',     'base_price' => 3500000],
            'rampak_gendang'   => ['name' => 'Rampak Gendang',   'base_price' => 4500000],
            'mapag_panganten'  => ['name' => 'Mapag Panganten',  'base_price' => 5000000],
            'kacapi_suling'    => ['name' => 'Kacapi Suling',    'base_price' => 2500000],
        ];

        $request->validate([
            'event_type'   => 'required|in:' . implode(',', array_keys($packages)),
            'event_date'   => 'required|date|after:today',
            'event_start'  => 'required',
            'event_end'    => 'required',
            'venue'        => 'required|string',
            'client_phone' => 'required|string',
        ]);

        // Ambil harga resmi dari server — immune terhadap manipulasi JS/form tampering
        $basePrice = $packages[$request->event_type]['base_price'];
        $dpAmount  = $basePrice * 0.50;

        $booking = Booking::create([
            'client_id'     => Auth::id(),
            'client_name'   => Auth::user()->name,
            'client_phone'  => $request->client_phone,
            'event_type'    => $request->event_type,
            'event_date'    => $request->event_date,
            'event_start'   => $request->event_start,
            'event_end'     => $request->event_end,
            'venue'         => $request->venue,
            'venue_address' => $request->venue_address,
            'booking_source'=> 'web',
            'status'        => 'pending',
            'total_price'   => $basePrice,
            'dp_amount'     => $dpAmount,
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
            'payment_proof' => 'required|image|max:5120', // Maks 5MB
        ]);

        // Simpan file ke storage public (Contoh sederhana)
        $path = $request->file('payment_proof')->store('proofs', 'public');

        $booking->update([
            'payment_proof' => $path
        ]);

        return redirect()->back()->with('success', 'Bukti bayar berhasil diunggah! Menunggu konfirmasi Admin.');
    }
}
