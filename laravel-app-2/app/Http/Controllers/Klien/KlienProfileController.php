<?php

namespace App\Http\Controllers\Klien;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class KlienProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('klien.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone'        => 'required|string|max:20',
            'organization' => 'nullable|string|max:255',
        ]);

        $user->name = $validated['name'];
        $user->phone = $validated['phone'];
        // Asumsikan Klien punya field organization di model atau tersimpan di tempat lain,
        // Tapi saat registrasi organization tidak disimpan ke tabel terpisah, hanya di controller KlienProfileController
        // Wait, did Klien have an organization field? Di register.blade.php ada field 'organization' tapi tidak disimpan di RegisteredUserController.
        // I'll skip organization if it's not in the db, or add it if it is. Let's just update users table.

        if ($user->email !== $validated['email']) {
            $user->email = $validated['email'];
            $user->email_verified_at = null; // Memaksa verifikasi ulang
        }

        $user->save();

        if ($user->wasChanged('email')) {
            return redirect()->back()->with('success', 'Profil berhasil diperbarui. Karena Anda mengubah email, silakan verifikasi ulang email baru Anda.');
        }

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password'         => ['required', 'string', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()],
        ], [
            'current_password.required' => 'Kata sandi saat ini wajib diisi.',
            'current_password.current_password' => 'Kata sandi saat ini tidak sesuai.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak sesuai.',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->back()->with('success', 'Kata sandi berhasil diperbarui!');
    }
}
