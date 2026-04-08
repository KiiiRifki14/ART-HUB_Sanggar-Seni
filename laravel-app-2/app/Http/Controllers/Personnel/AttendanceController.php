<?php

namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * GHOSTING GUARD: Personel Tap 'Saya Sudah Sampai' On-Site
     */
    public function checkIn(Request $request, Event $event)
    {
        $user = Auth::user();
        $personnel = collect($user->personnelProfile)->first(); // Asumsi setup relation HasOne/BelongsTo

        if (!$personnel) {
            return redirect()->back()->with('error', 'Akses ditolak. Anda bukan roster.');
        }

        // Cari record plotting personel untuk event ini
        $pivot = DB::table('event_personnel')
            ->where('event_id', $event->id)
            ->where('personnel_id', $personnel->id)
            ->first();

        if (!$pivot) {
            return redirect()->back()->with('error', 'Anda tidak didaftarkan pada event ini.');
        }

        if ($pivot->checked_in_at) {
            return redirect()->back()->with('info', 'Anda sudah melakukan Check-in sebelumnya.');
        }

        // Logic Radius Haversine
        $lat = $request->input('latitude');
        $lon = $request->input('longitude');

        if ($event->latitude && $event->longitude && $lat && $lon) {
            $eLat = $event->latitude;
            $eLon = $event->longitude;
            $earthRadius = 6371000; // Radius bumi dalam meter
            
            $latDelta = deg2rad($lat - $eLat);
            $lonDelta = deg2rad($lon - $eLon);
            
            $a = sin($latDelta / 2) * sin($latDelta / 2) +
                 cos(deg2rad($eLat)) * cos(deg2rad($lat)) *
                 sin($lonDelta / 2) * sin($lonDelta / 2);
            $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
            
            $distance = $earthRadius * $c;
            
            if ($distance > 200) {
                return redirect()->back()->with('error', "Gagal Check-In: Jarak Anda " . round($distance) . "m dari area pementasan (Maks: 200m).");
            }
        } elseif ($event->latitude && $event->longitude) {
            return redirect()->back()->with('error', 'Sistem tidak mendeteksi lokasi (GPS) perangkat Anda.');
        }

        $now = Carbon::now();
        // Target kehadiran adalah 30 menit sebelum acara dimulai (Call Time)
        $callTime = Carbon::parse(Carbon::parse($event->event_date)->format('Y-m-d') . ' ' . Carbon::parse($event->event_start)->format('H:i:s'))->subMinutes(30);
        
        $status = 'on_time';
        $lateMinutes = 0;
        $penaltyAmount = 0;

        // Toleransi keterlambatan 15 menit dari Call Time
        if ($now->greaterThan($callTime->copy()->addMinutes(15))) {
            $status = 'late';
            // Hitung keterlambatan riil terhadap Call Time
            $lateMinutes = $now->diffInMinutes($callTime);
            
            // Logika Denda: Rp 15.000 untuk setiap 10 menit terlambat.
            // (Memukul telak "Ghosting" dan ketidakdisiplinan!)
            $penaltyAmount = floor($lateMinutes / 10) * 15000;
        }

        try {
            DB::transaction(function () use ($event, $personnel, $status, $lateMinutes, $penaltyAmount, $now, $user) {
                // 1. UPDATE TABEL PIVOT: Catat jam dan status Check-in
                DB::table('event_personnel')
                    ->where('event_id', $event->id)
                    ->where('personnel_id', $personnel->id)
                    ->update([
                        'checked_in_at' => $now,
                        'attendance_status' => $status,
                        'late_minutes' => $lateMinutes,
                        'updated_at' => $now,
                    ]);

                // 2. TRIGGER MySQL ACTION (Integrasi Basis Data 2)
                // Jika 'late' dan ada denda, kita inject EventLog type: 'keterlambatan'
                if ($status === 'late' && $penaltyAmount > 0) {
                    
                    // Injecting into EventLog.
                    // PERHATIAN: Trigger 'trg_incident_to_cost' di MySQL akan bereaksi otomatis terhadap INSERT ini!
                    EventLog::create([
                        'event_id'         => $event->id,
                        'log_type'         => 'keterlambatan',
                        'title'            => 'Keterlambatan: ' . $user->name,
                        'description'      => "Personel terlambat {$lateMinutes} menit dari Call Time. Sistem menerapkan denda otomatis.",
                        'financial_impact' => $penaltyAmount, 
                        'logged_by'        => $user->id,
                        'logged_at'        => $now,
                    ]);

                    // Selain Trigger yang bereaksi menambah dana darurat ke operasional,
                    // Honor si personel di tabel event_personnel dipotong secara real!
                    DB::table('event_personnel')
                        ->where('event_id', $event->id)
                        ->where('personnel_id', $personnel->id)
                        ->decrement('fee', $penaltyAmount);
                }
            });

            // Return feedback ke Personel UX
            if ($status === 'on_time') {
                return redirect()->back()->with('success', 'Check-In Sukses: Tepat Waktu. Selamat bekerja!');
            } else {
                return redirect()->back()->with('warning', "Check-In Telat: Anda terlambat {$lateMinutes} menit. Sistem Merekam Denda Sebesar Rp " . number_format($penaltyAmount, 0, ',', '.') . " pada fee Anda.");
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Sistem Gagal Mencatat Check-In: ' . $e->getMessage());
        }
    }
}
