<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Cancellation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CancellationController extends Controller
{
    /**
     * Memproses Pembatalan Event oleh Klien
     * MENGGUNAKAN SQL FUNCTION: fn_calculate_cancellation_penalty
     */
    public function store(Request $request, Booking $booking)
    {
        $request->validate([
            'reason' => 'required|string',
            'digital_acknowledgement' => 'required|boolean|accepted', // Perlindungan hukum sanggar
        ]);

        if ($booking->status === 'cancelled') {
            return redirect()->back()->with('error', 'Booking sudah dibatalkan sebelumnya.');
        }

        try {
            DB::transaction(function () use ($booking, $request) {
                // Konversi tanggal ke string (untuk memastikan kompabilitas input SQL function)
                $cancelDate = Carbon::now()->format('Y-m-d');
                $eventDate = is_string($booking->event_date) ? $booking->event_date : $booking->event_date->format('Y-m-d');
                
                // 1. PANGGIL SQL FUNCTION DARI DATABASE
                // fn_calculate_cancellation_penalty(event_date, cancel_date, total_price)
                $query = DB::select('SELECT fn_calculate_cancellation_penalty(?, ?, ?) AS penalty_amount', [
                    $eventDate,
                    $cancelDate,
                    $booking->total_price
                ]);
                
                $penaltyAmount = $query[0]->penalty_amount ?? 0;
                $daysBefore = Carbon::parse($eventDate)->diffInDays(Carbon::parse($cancelDate), false);
                
                // Jika negatif, artinya event sudah lewat (tidak valid u/ pembatalan hari normal, tapi kita set 0 aja u/ safety fallback)
                $daysBefore = max(0, $daysBefore);

                // Hitung persen penalti hanya untuk visual log di Web
                $penaltyPct = ($booking->total_price > 0) ? ($penaltyAmount / $booking->total_price) * 100 : 0;
                
                // Hitung Nilai Refund
                // Uang yang harus dikembalikan = (Uang DP yang sudah masuk - Denda Pembatalan)
                // Jika Denda lebih besar dari DP, Refund = 0 (klien rugi / sanggar untung)
                $refundAmount = max(0, $booking->dp_amount - $penaltyAmount);

                // 2. Simpan Data Cancellation
                Cancellation::create([
                    'booking_id'              => $booking->id,
                    'cancellation_date'       => Carbon::now()->format('Y-m-d'),
                    'days_before_event'       => $daysBefore,
                    'penalty_percentage'      => $penaltyPct,
                    'penalty_amount'          => $penaltyAmount,
                    'refund_amount'           => $refundAmount,
                    'status'                  => 'pending',
                    'reason'                  => $request->reason,
                    'digital_acknowledgement' => $request->digital_acknowledgement,
                ]);

                // 3. Batalkan Booking dan lepaskan ikatan Event
                $booking->update(['status' => 'cancelled']);
                if ($booking->event) {
                    $booking->event->update(['status' => 'cancelled']);
                    // Lepaskan semua personel yang sudah di-plotting (Un-Plot) agar bebas di rentang tanggal tersebut
                    $booking->event->personnel()->detach(); 
                }
            });

            return redirect()->back()->with('success', 'Pembatalan Diproses Oleh SQL Function Database. Denda Pembatalan telah dihitung secara presisi sesuai H-Hari!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memproses pembatalan: ' . $e->getMessage());
        }
    }
}
