<?php

namespace App\Http\Controllers\Klien;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ClientFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientFeedbackController extends Controller
{
    /**
     * Menyimpan rating dan ulasan dari klien.
     */
    public function store(Request $request, Booking $booking)
    {
        // Pastikan Klien yang login adalah pemilik booking ini
        if ($booking->client_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        // Pastikan status booking sudah completed
        if ($booking->status !== 'completed') {
            return redirect()->back()->with('error', 'Hanya pesanan yang sudah selesai yang dapat diberikan ulasan.');
        }

        // Pastikan belum ada ulasan sebelumnya
        if ($booking->feedback()->exists()) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk pesanan ini.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'testimony' => 'nullable|string|max:1000',
        ]);

        $booking->feedback()->create([
            'rating' => $validated['rating'],
            'testimony' => $validated['testimony'],
            'submitted_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Terima kasih! Ulasan Anda telah berhasil disimpan.');
    }
}
