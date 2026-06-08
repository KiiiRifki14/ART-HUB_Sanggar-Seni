<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Tambahkan validasi regex ketat untuk mencegah karakter aneh / injeksi XSS/SQL
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.]+$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9\-\+\(\)]+$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'otp_code' => ['required', 'string', 'size:6'],
            'role' => ['required', 'string', 'in:personnel,klien'],
            'specialty' => ['required_if:role,personnel', 'nullable', 'string', 'in:penari,pemusik,multi_talent'],
            'day_job_name' => ['nullable', 'string', 'max:255'],
        ]);

        $email = $request->email;
        $cacheKey = 'register_otp:' . $email;
        $attemptsKey = 'register_otp_attempts:' . $email;

        $cachedOtp = \Illuminate\Support\Facades\Cache::get($cacheKey);

        if (!$cachedOtp) {
            return back()->withInput()->withErrors(['otp_code' => 'Kode OTP tidak valid atau sudah kedaluwarsa. Silakan minta ulang.']);
        }

        if ($cachedOtp !== $request->otp_code) {
            $attempts = \Illuminate\Support\Facades\Cache::get($attemptsKey, 0) + 1;
            \Illuminate\Support\Facades\Cache::put($attemptsKey, $attempts, now()->addMinutes(15));

            if ($attempts >= 5) {
                \Illuminate\Support\Facades\Cache::forget($cacheKey);
                \Illuminate\Support\Facades\Cache::forget($attemptsKey);
                return back()->withInput()->withErrors(['otp_code' => 'Terlalu banyak percobaan salah. Kode OTP telah hangus, silakan minta kode baru.']);
            }

            return back()->withInput()->withErrors(['otp_code' => 'Kode OTP salah. Sisa percobaan: ' . (5 - $attempts)]);
        }

        // OTP Valid, clear cache
        \Illuminate\Support\Facades\Cache::forget($cacheKey);
        \Illuminate\Support\Facades\Cache::forget($attemptsKey);
        
        $role = $request->role === 'personnel' ? 'personel' : 'klien';

        // Sanitasi input menggunakan strip_tags untuk mencegah XSS
        $user = User::create([
            'name' => strip_tags($request->name),
            'email' => strip_tags($request->email),
            'phone' => strip_tags($request->phone),
            'password' => Hash::make($request->password),
            'role' => $role,
            'email_verified_at' => now(), // Verifikasi email langsung sukses
        ]);

        if ($role === 'personel') {
            $hasDayJob = !empty($request->day_job_name);
            
            \App\Models\Personnel::create([
                'user_id' => $user->id,
                'specialty' => $request->specialty, // Ganti dari hardcoded 'penari'
                'has_day_job' => $hasDayJob,
                'day_job_desc' => $hasDayJob ? strip_tags($request->day_job_name) : null,
                'day_job_start' => $hasDayJob ? $request->day_job_start : null,
                'day_job_end' => $hasDayJob ? $request->day_job_end : null,
                'is_active' => false,
                'status' => 'pending_verification',
                'is_backup' => false,
            ]);

            // Notify admins about new personnel registration
            $admins = User::where('role', 'admin')->get();
            \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\NewPersonnelRegistration($user->name));
        }

        event(new Registered($user));

        Auth::login($user);

        if ($role === 'personel') {
            return redirect()->route('personnel.pending');
        }

        return redirect()->route('dashboard');
    }
}
