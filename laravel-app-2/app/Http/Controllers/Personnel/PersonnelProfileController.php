<?php

namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PersonnelProfileController extends Controller
{
    public function edit()
    {
        $personnel = Auth::user()->personnelProfile;
        if (!$personnel) {
            return redirect()->route('personnel.dashboard')->with('error', 'Profil tidak ditemukan.');
        }
        return view('personnel.profile_edit', compact('personnel'));
    }

    public function update(Request $request)
    {
        $user      = Auth::user();
        $personnel = $user->personnelProfile;

        if (!$personnel) {
            return redirect()->route('personnel.dashboard')->with('error', 'Profil tidak ditemukan.');
        }

        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'stage_name' => 'nullable|string|max:100',
            'phone'      => 'nullable|string|max:20',
            'bio'        => 'nullable|string|max:500',
            'photo'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        // 1. Update nama & nomor HP di tabel users
        $user->name  = $validated['name'];
        $user->phone = $validated['phone'] ?? $user->phone;
        $user->save();

        // 2. Handle foto
        $photoPath = $personnel->photo;

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            try {
                // Pastikan direktori ada
                Storage::disk('public')->makeDirectory('personnel-photos');

                // Hapus foto lama jika ada
                if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                    Storage::disk('public')->delete($photoPath);
                }

                // Simpan foto baru
                $photoPath = $request->file('photo')->store('personnel-photos', 'public');

                Log::info('Photo uploaded', ['path' => $photoPath, 'personnel_id' => $personnel->id]);

            } catch (\Exception $e) {
                Log::error('Photo upload failed', ['error' => $e->getMessage()]);
                return redirect()->route('personnel.profile.edit')
                    ->withInput()
                    ->with('error', 'Gagal upload foto: ' . $e->getMessage());
            }
        }

        // 3. Update data personel
        $personnel->stage_name = $validated['stage_name'] ?? null;
        $personnel->bio        = $validated['bio'] ?? null;
        $personnel->photo      = $photoPath;
        $personnel->save();

        return redirect()->route('personnel.profile.edit')
            ->with('success', 'Profil berhasil diperbarui!');
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
        $user->password = \Illuminate\Support\Facades\Hash::make($validated['password']);
        $user->save();

        return redirect()->route('personnel.profile.edit')
            ->with('success', 'Kata sandi berhasil diperbarui!');
    }
}
