<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Display the password reset view.
     */
    public function create(Request $request): View|RedirectResponse
    {
        if (!session()->has('reset_email')) {
            return redirect()->route('password.request');
        }
        return view('auth.reset-password-otp');
    }

    /**
     * Handle an incoming new password request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'otp_code' => ['required', 'string', 'size:6'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->otp_code !== $request->otp_code) {
            return back()->withInput($request->only('email'))->withErrors(['otp_code' => 'Kode OTP tidak valid atau email salah.']);
        }

        if (now()->greaterThan($user->otp_expires_at)) {
            return back()->withInput($request->only('email'))->withErrors(['otp_code' => 'Kode OTP sudah kedaluwarsa.']);
        }

        // OTP valid, change password
        $user->forceFill([
            'password' => Hash::make($request->password),
            'remember_token' => Str::random(60),
            'otp_code' => null,
            'otp_expires_at' => null,
        ])->save();

        event(new PasswordReset($user));
        
        session()->forget('reset_email');

        return redirect()->route('login')->with('status', 'Password telah berhasil direset. Silakan login.');
    }
}
