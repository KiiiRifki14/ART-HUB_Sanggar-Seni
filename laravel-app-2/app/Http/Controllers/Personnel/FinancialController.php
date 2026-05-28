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

        // Ambil semua event untuk rekap statistik secara penuh
        $allEvents = $personnel->events()->get();

        $totalEarned  = 0;
        $totalPending = 0;
        $totalPenalty = 0;

        foreach ($allEvents as $ev) {
            $pivot = $ev->pivot;
            $penalty = $pivot->late_minutes
                ? floor($pivot->late_minutes / 10) * 15000
                : 0;
            
            if ($ev->status === 'completed') {
                $totalEarned += $pivot->fee ?? 0;
            } else {
                $totalPending += $pivot->fee ?? 0;
            }
            $totalPenalty += $penalty;
        }

        // Paginate events untuk tampilan list (2 per halaman)
        $paginator = $personnel->events()
            ->orderBy('event_date', 'desc')
            ->paginate(3);

        $eventFinancials = collect($paginator->items())->map(function ($event) {
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

        // Simpan koleksi ter-map kembali ke paginator agar pemanggilan links() berfungsi
        $paginator->setCollection($eventFinancials);
        $eventFinancials = $paginator;

        return view('personnel.keuangan', compact(
            'personnel', 'eventFinancials',
            'totalEarned', 'totalPending', 'totalPenalty'
        ));
    }
}
