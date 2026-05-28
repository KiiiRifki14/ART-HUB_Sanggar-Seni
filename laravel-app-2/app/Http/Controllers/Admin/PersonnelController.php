<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Personnel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PersonnelController extends Controller
{
    public function index()
    {
        $personnel = Personnel::with('user')->orderBy('id')->get();
        return view('admin.personnel.index', compact('personnel'));
    }

    public function create()
    {
        return view('admin.personnel.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'specialty'    => 'required|string|max:100',
            'phone'        => 'nullable|string|max:20',
            'has_day_job'  => 'nullable|boolean',
            'day_job_name' => 'nullable|string|max:255',
            'day_job_start'=> ['nullable', 'regex:/^([01]\d|2[0-3]):[0-5]\d$/'],
            'day_job_end'  => ['nullable', 'regex:/^([01]\d|2[0-3]):[0-5]\d$/'],
        ]);

        $tempPassword = $request->filled('password')
            ? $request->input('password')
            : \Illuminate\Support\Str::random(8);

        try {
            DB::transaction(function () use ($request, $tempPassword) {
                // Buat akun user untuk personel
                $user = User::create([
                    'name'     => $request->name,
                    'email'    => $request->email,
                    'phone'    => $request->phone,
                    'password' => Hash::make($tempPassword),
                    'role'     => 'personel',
                ]);

                // Buat data personel
                Personnel::create([
                    'user_id'       => $user->id,
                    'specialty'     => $request->specialty,
                    'has_day_job'   => $request->boolean('has_day_job'),
                    'day_job_desc'  => $request->has_day_job ? $request->day_job_name : null,
                    'day_job_start' => $request->has_day_job ? $request->day_job_start : null,
                    'day_job_end'   => $request->has_day_job ? $request->day_job_end : null,
                    'is_active'     => true,
                    'is_backup'     => $request->boolean('is_backup'),
                ]);
            });

            if (!$request->filled('password')) {
                return redirect()->route('admin.personnel.index')
                    ->with('success', "Personel {$request->name} berhasil ditambahkan ke sanggar!")
                    ->with('temp_password', $tempPassword)
                    ->with('temp_password_name', $request->name);
            }

            return redirect()->route('admin.personnel.index')
                ->with('success', "Personel {$request->name} berhasil ditambahkan ke sanggar!");

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal menambahkan personel: ' . $e->getMessage());
        }
    }

    public function edit(Personnel $personnel)
    {
        $personnel->load('user');
        return view('admin.personnel.edit', compact('personnel'));
    }

    public function update(Request $request, Personnel $personnel)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'specialty'    => 'required|string|max:100',
            'phone'        => 'nullable|string|max:20',
            'has_day_job'  => 'nullable|boolean',
            'day_job_name' => 'nullable|string|max:255',
            'day_job_start'=> ['nullable', 'regex:/^([01]\d|2[0-3]):[0-5]\d$/'],
            'day_job_end'  => ['nullable', 'regex:/^([01]\d|2[0-3]):[0-5]\d$/'],
            'is_active'    => 'nullable|boolean',
            'is_backup'    => 'nullable|boolean',
        ]);

        try {
            DB::transaction(function () use ($request, $personnel) {
                // 1. Update data User (Nama & HP)
                $personnel->user->name = $request->name;
                $personnel->user->phone = $request->phone;
                $personnel->user->save();

                // 2. Update data Personnel (Gunakan explicit assignment)
                $personnel->specialty = $request->specialty;

                // Method boolean() otomatis menerjemahkan nilai checkbox menjadi true/false
                $personnel->has_day_job = $request->boolean('has_day_job');
                $personnel->day_job_desc = $request->boolean('has_day_job') ? $request->day_job_name : null;
                $personnel->day_job_start = $request->boolean('has_day_job') ? $request->day_job_start : null;
                $personnel->day_job_end = $request->boolean('has_day_job') ? $request->day_job_end : null;

                $personnel->is_active = $request->boolean('is_active');
                $personnel->is_backup = $request->boolean('is_backup');

                // Perintah save() memaksa data tersimpan meskipun tidak ada di $fillable model
                $personnel->save();
            });

            return redirect()->route('admin.personnel.index')
                ->with('success', "Data personel berhasil diperbarui!");

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Gagal memperbarui: ' . $e->getMessage());
        }
    }

    public function destroy(Personnel $personnel)
    {
        try {
            $name = $personnel->user->name;
            $personnel->user->delete(); // cascades ke personnel
            return redirect()->route('admin.personnel.index')
                ->with('success', "Personel {$name} berhasil dihapus dari sanggar.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }

    /**
     * Setujui pendaftaran personel baru (ubah is_active = true)
     */
    public function approve(Personnel $personnel)
    {
        $personnel->update(['is_active' => true]);
        return redirect()->route('admin.personnel.index')
            ->with('success', "✅ {$personnel->user->name} telah disetujui dan sekarang bisa mengakses Portal Kru!");
    }

    /**
     * Tolak pendaftaran personel baru (hapus akun)
     */
    public function reject(Personnel $personnel)
    {
        $name = $personnel->user->name ?? 'Personel';
        $personnel->user->delete(); // cascades ke personnel via FK
        return redirect()->route('admin.personnel.index')
            ->with('warning', "❌ Pendaftaran {$name} telah DITOLAK dan akun dihapus.");
    }
}
