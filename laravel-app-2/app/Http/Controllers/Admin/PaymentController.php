<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class PaymentController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('client')->latest()->get();
        return view('admin.payments.index', compact('bookings'));
    }
}
