<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class PaymentController extends Controller
{
    public function index()
    {
        $stats = [
            'total'   => Booking::count(),
            'unpaid'  => Booking::whereIn('status', ['completed', 'paid_full'])->whereNull('full_paid_at')->count(),
            'piutang' => Booking::whereNull('full_paid_at')->get()->sum(function($b) {
                return $b->total_price - $b->dp_amount;
            }),
            'lunas'   => Booking::whereNotNull('full_paid_at')->count(),
        ];

        $bookings = Booking::with(['client', 'event'])->latest()->paginate(10)->withQueryString();

        return view('admin.payments.index', compact('bookings', 'stats'));
    }
}
