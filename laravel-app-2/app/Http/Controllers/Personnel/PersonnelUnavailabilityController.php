<?php

namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonnelUnavailabilityController extends Controller
{
    public function store(Request $request)
    {
        $personnel = Auth::user()->personnelProfile;
        
        if (!$personnel) {
            return redirect()->route('personnel.dashboard')->with('error', 'Profil tidak ditemukan.');
        }

        $validated = $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'reason'     => 'required|string|max:255',
        ]);

        // Jika end_date kosong, set sama dengan start_date
        $endDate = $validated['end_date'] ?? $validated['start_date'];

        $personnel->unavailabilities()->create([
            'start_date' => $validated['start_date'],
            'end_date'   => $endDate,
            'reason'     => $validated['reason'],
        ]);

        return redirect()->route('personnel.dashboard')->with('success', 'Berhasil menandai tanggal berhalangan.');
    }
}
