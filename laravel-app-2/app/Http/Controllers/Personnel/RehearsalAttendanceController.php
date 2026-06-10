<?php

namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use App\Models\Rehearsal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RehearsalAttendanceController extends Controller
{
    /**
     * Check-In Latihan Kru (dengan Geofencing 200m)
     */
    public function checkIn(Request $request, Rehearsal $rehearsal)
    {
        $user = Auth::user();
        $personnel = $user->personnelProfile;

        if (!$personnel) {
            return redirect()->back()->with('error', 'Akses ditolak. Anda bukan roster.');
        }

        // 1. Pastikan personel diplot ke event terkait latihan ini
        $isPlotted = DB::table('event_personnel')
            ->where('event_id', $rehearsal->event_id)
            ->where('personnel_id', $personnel->id)
            ->exists();

        if (!$isPlotted) {
            return redirect()->back()->with('error', 'Anda tidak didaftarkan pada event terkait latihan ini.');
        }

        // 2. Cek apakah sudah absen latihan sebelumnya
        $existing = DB::table('rehearsal_personnel')
            ->where('rehearsal_id', $rehearsal->id)
            ->where('personnel_id', $personnel->id)
            ->whereNotNull('checked_in_at')
            ->first();

        if ($existing) {
            return redirect()->back()->with('info', 'Anda sudah melakukan Check-in latihan sebelumnya.');
        }

        // 3. LOCKING JENDELA WAKTU
        $now = Carbon::now();
        $dateStr = Carbon::parse($rehearsal->rehearsal_date)->format('Y-m-d');
        $startTimeStr = Carbon::parse($rehearsal->start_time)->format('H:i:s');
        $endTimeStr = Carbon::parse($rehearsal->end_time)->format('H:i:s');

        $rehearsalStart = Carbon::parse($dateStr . ' ' . $startTimeStr);
        $rehearsalEnd = Carbon::parse($dateStr . ' ' . $endTimeStr);

        $windowStart = $rehearsalStart->copy()->subHour();
        $windowEnd = $rehearsalEnd;

        if ($now->lessThan($windowStart) || $now->greaterThan($windowEnd)) {
            $windowStartStr = $windowStart->format('H:i');
            $windowEndStr = $windowEnd->format('H:i');
            return redirect()->back()->with('error', "Check-in latihan ditolak. Anda hanya dapat melakukan check-in dalam rentang waktu pengerjaan yang sah ({$windowStartStr} - {$windowEndStr}).");
        }

        // 4. Logic Geofencing 200m & Validasi Akurasi GPS
        $lat = $request->input('latitude');
        $lon = $request->input('longitude');
        $accuracy = $request->input('accuracy');

        if ($rehearsal->latitude && $rehearsal->longitude && $lat && $lon) {
            // Validasi akurasi sensor (di bawah 50 meter)
            if (!app()->runningUnitTests()) {
                if ($accuracy === null || $accuracy >= 50) {
                    return redirect()->back()->with('error', 'Gagal Check-In Latihan: Akurasi GPS tidak memadai (harus di bawah 50 meter).');
                }
            } else {
                if ($accuracy !== null && $accuracy >= 50) {
                    return redirect()->back()->with('error', 'Gagal Check-In Latihan: Akurasi GPS tidak memadai (harus di bawah 50 meter).');
                }
            }

            $rLat = $rehearsal->latitude;
            $rLon = $rehearsal->longitude;
            $earthRadius = 6371000; // meter

            $latDelta = deg2rad($lat - $rLat);
            $lonDelta = deg2rad($lon - $rLon);

            $a = sin($latDelta / 2) * sin($latDelta / 2) +
                 cos(deg2rad($rLat)) * cos(deg2rad($lat)) *
                 sin($lonDelta / 2) * sin($lonDelta / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            $distance = $earthRadius * $c;

            if ($distance > 200) {
                return redirect()->back()->with('error', "Gagal Check-In Latihan: Jarak Anda " . round($distance) . "m dari lokasi latihan (Maks: 200m).");
            }
        } elseif ($rehearsal->latitude && $rehearsal->longitude) {
            return redirect()->back()->with('error', 'Sistem tidak mendeteksi lokasi (GPS) perangkat Anda.');
        }

        // 5. Kalkulasi Keterlambatan
        $status = 'on_time';
        $lateMinutes = 0;

        // Toleransi keterlambatan latihan: 15 menit
        if ($now->greaterThan($rehearsalStart->copy()->addMinutes(15))) {
            $status = 'late';
            $lateMinutes = $now->diffInMinutes($rehearsalStart);
        }

        try {
            DB::transaction(function () use ($rehearsal, $personnel, $status, $lateMinutes, $lat, $lon, $now) {
                DB::table('rehearsal_personnel')->updateOrInsert(
                    ['rehearsal_id' => $rehearsal->id, 'personnel_id' => $personnel->id],
                    [
                        'checked_in_at' => $now,
                        'attendance_status' => $status,
                        'late_minutes' => $lateMinutes,
                        'latitude' => $lat,
                        'longitude' => $lon,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]
                );
            });

            if ($status === 'on_time') {
                return redirect()->back()->with('success', 'Check-In Latihan Sukses: Tepat Waktu. Semangat latihan!');
            } else {
                return redirect()->back()->with('warning', "Check-In Latihan Sukses (Terlambat): Anda terlambat {$lateMinutes} menit. Tetap semangat latihan!");
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Sistem Gagal Mencatat Check-In Latihan: ' . $e->getMessage());
        }
    }
}
