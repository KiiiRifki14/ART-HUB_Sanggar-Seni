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
            'name'          => 'required|string|max:255',
            'stage_name'    => 'nullable|string|max:100',
            'phone'         => 'nullable|string|max:20',
            'bio'           => 'nullable|string|max:500',
            'photo'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            // Pekerjaan / Sekolah / Kegiatan Utama
            'has_day_job'   => 'nullable|boolean',
            'day_job_name'  => 'nullable|string|max:100',
            'day_job_start' => 'nullable|date_format:H:i',
            'day_job_end'   => 'nullable|date_format:H:i|after:day_job_start',
        ], [
            'day_job_end.after'           => 'Jam selesai kegiatan harus setelah jam mulai.',
            'day_job_start.date_format'   => 'Format jam mulai tidak valid.',
            'day_job_end.date_format'     => 'Format jam selesai tidak valid.',
        ]);

        // 1. Update nama & nomor HP di tabel users
        $user->name  = $validated['name'];
        $user->phone = $validated['phone'] ?? $user->phone;
        $user->save();

        // 2. Handle foto
        $photoPath = $personnel->photo;

        if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
            try {
                Storage::disk('public')->makeDirectory('personnel-photos');
                if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                    Storage::disk('public')->delete($photoPath);
                }
                $photoPath = $request->file('photo')->store('personnel-photos', 'public');
                Log::info('Photo uploaded', ['path' => $photoPath, 'personnel_id' => $personnel->id]);
            } catch (\Exception $e) {
                Log::error('Photo upload failed', ['error' => $e->getMessage()]);
                return redirect()->route('personnel.profile.edit')
                    ->withInput()
                    ->with('error', 'Gagal upload foto: ' . $e->getMessage());
            }
        }

        // 3. Handle pekerjaan/sekolah
        $hasDayJob   = !empty($validated['has_day_job']);
        $dayJobName  = $hasDayJob ? ($validated['day_job_name'] ?? null) : null;
        $dayJobStart = $hasDayJob ? ($validated['day_job_start'] ?? null) : null;
        $dayJobEnd   = $hasDayJob ? ($validated['day_job_end'] ?? null) : null;

        // 4. Update data personel (day_job_desc di-sync sama day_job_name agar SP bentrok bisa baca)
        $personnel->stage_name   = $validated['stage_name'] ?? null;
        $personnel->bio          = $validated['bio'] ?? null;
        $personnel->photo        = $photoPath;
        $personnel->has_day_job  = $hasDayJob;
        $personnel->day_job_name = $dayJobName;
        $personnel->day_job_desc = $dayJobName; // sync ke day_job_desc yang dipakai SP
        $personnel->day_job_start = $dayJobStart;
        $personnel->day_job_end   = $dayJobEnd;
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
