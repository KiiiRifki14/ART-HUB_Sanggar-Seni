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
        ]);
        
        $role = $request->role === 'personnel' ? 'personel' : 'klien';

        // Sanitasi input menggunakan strip_tags untuk mencegah XSS
        $user = User::create([
            'name' => strip_tags($request->name),
            'email' => strip_tags($request->email),
            'phone' => strip_tags($request->phone),
            'password' => Hash::make($request->password),
            'role' => $role,
        ]);

        if ($role === 'personel') {
            $hasDayJob = !empty($request->day_job_name);
            
            \App\Models\Personnel::create([
                'user_id' => $user->id,
                'specialty' => 'penari', // Defaulting as discussed
                'has_day_job' => $hasDayJob,
                'day_job_desc' => $hasDayJob ? $request->day_job_name : null,
                'day_job_start' => $hasDayJob ? $request->day_job_start : null,
                'day_job_end' => $hasDayJob ? $request->day_job_end : null,
                'is_active' => false,
                'is_backup' => false,
            ]);
        }

        event(new Registered($user));

        if ($role === 'personel') {
            Auth::login($user);
            return redirect()->route('personnel.pending');
        }

        return redirect()->route('login')->with('status', 'Account created successfully! Please login.');
    }
}
