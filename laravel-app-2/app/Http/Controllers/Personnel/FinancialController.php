<?php

namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FinancialController extends Controller
{
    public function index()
    {
        $user      = Auth::user();
        $personnel = $user->personnelProfile;

        if (!$personnel) {
            return redirect()->route('personnel.dashboard')->with('error', 'Profil tidak ditemukan.');
        }

        // Ambil semua event beserta data pivot (fee, penalty, dll)
        $eventFinancials = $personnel->events()
            ->orderBy('event_date', 'desc')
            ->get()
            ->map(function ($event) {
                $pivot = $event->pivot;
                return [
                    'event'          => $event,
                    'fee'            => $pivot->fee ?? 0,
                    'role'           => $pivot->role_in_event ?? '-',
                    'checked_in_at'  => $pivot->checked_in_at,
                    'status'         => $pivot->attendance_status ?? 'pending',
                    'late_minutes'   => $pivot->late_minutes ?? 0,
                    'penalty'        => $pivot->late_minutes
                        ? floor($pivot->late_minutes / 10) * 15000
                        : 0,
                    'event_status'   => $event->status,
                ];
            });

        $totalEarned  = $eventFinancials->where('event_status', 'completed')->sum('fee');
        $totalPending = $eventFinancials->where('event_status', '!=', 'completed')->sum('fee');
        $totalPenalty = $eventFinancials->sum('penalty');

        return view('personnel.keuangan', compact(
            'personnel', 'eventFinancials',
            'totalEarned', 'totalPending', 'totalPenalty'
        ));
    }
}
