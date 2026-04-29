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
            'day_job_start'=> 'nullable|date_format:H:i',
            'day_job_end'  => 'nullable|date_format:H:i',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Buat akun user untuk personel
                $user = User::create([
                    'name'     => $request->name,
                    'email'    => $request->email,
                    'phone'    => $request->phone,
                    'password' => Hash::make($request->input('password', 'sanggar123')),
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
            'day_job_start'=> 'nullable|date_format:H:i',
            'day_job_end'  => 'nullable|date_format:H:i',
            'is_active'    => 'nullable|boolean',
            'is_backup'    => 'nullable|boolean',
        ]);

        try {
            DB::transaction(function () use ($request, $personnel) {
                // Update User for name and phone
                $personnel->user->update([
                    'name' => $request->name,
                    'phone' => $request->phone
                ]);
                
                // Update Personnel specific fields
                $personnel->update([
                    'specialty'     => $request->specialty,
                    'has_day_job'   => $request->boolean('has_day_job'),
                    'day_job_desc'  => $request->has_day_job ? $request->day_job_name : null,
                    'day_job_start' => $request->has_day_job ? $request->day_job_start : null,
                    'day_job_end'   => $request->has_day_job ? $request->day_job_end : null,
                    'is_active'     => $request->boolean('is_active', true),
                    'is_backup'     => $request->boolean('is_backup'),
                ]);
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
}
