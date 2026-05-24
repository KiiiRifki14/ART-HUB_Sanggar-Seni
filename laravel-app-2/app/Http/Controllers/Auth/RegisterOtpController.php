<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\OtpMail;

class RegisterOtpController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
        ]);

        $email = $request->email;
        $otp = sprintf("%06d", mt_rand(1, 999999));
        $cacheKey = 'register_otp:' . $email;

        // Store OTP in Cache for 15 minutes
        Cache::put($cacheKey, $otp, now()->addMinutes(15));

        // Send Email
        Mail::to($email)->send(new OtpMail($otp));

        return response()->json([
            'message' => 'Kode OTP berhasil dikirim ke ' . $email,
        ]);
    }
}
