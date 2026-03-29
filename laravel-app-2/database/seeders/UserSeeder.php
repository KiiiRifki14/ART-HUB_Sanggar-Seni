<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ═══ ADMIN: Pak Yat (Pimpinan Sanggar) ═══
        DB::table('users')->insert([
            'name'       => 'Pak Yat',
            'email'      => 'pakyat@arthub.local',
            'password'   => Hash::make('admin123'),
            'role'       => 'admin',
            'phone'      => '081234567890',
            'address'    => 'Jl. Sanggar Cahaya Gumilang No. 1, Tangerang',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // ═══ PERSONEL: 11 Inti + 1 Cadangan ═══
        $personnel = [
            // Penari (6 orang)
            ['name' => 'Siti Nurhaliza',   'email' => 'siti@arthub.local',    'specialty' => 'penari',   'has_day_job' => false, 'is_backup' => false],
            ['name' => 'Dewi Anggraeni',   'email' => 'dewi@arthub.local',    'specialty' => 'penari',   'has_day_job' => false, 'is_backup' => false],
            ['name' => 'Rina Marlina',     'email' => 'rina@arthub.local',    'specialty' => 'penari',   'has_day_job' => false, 'is_backup' => false],
            ['name' => 'Yuni Kartika',     'email' => 'yuni@arthub.local',    'specialty' => 'penari',   'has_day_job' => false, 'is_backup' => false],
            ['name' => 'Fitri Handayani',  'email' => 'fitri@arthub.local',   'specialty' => 'penari',   'has_day_job' => false, 'is_backup' => false],
            ['name' => 'Lina Suryani',     'email' => 'lina@arthub.local',    'specialty' => 'penari',   'has_day_job' => false, 'is_backup' => false],

            // Pemusik (5 orang — beberapa punya pekerjaan utama)
            ['name' => 'Bapak Ujang Suparman', 'email' => 'ujang@arthub.local',   'specialty' => 'pemusik', 'has_day_job' => true,  'day_job_desc' => 'PNS Kecamatan',    'day_job_start' => '08:00', 'day_job_end' => '16:00', 'is_backup' => false],
            ['name' => 'Bapak Dedi Kurniawan', 'email' => 'dedi@arthub.local',    'specialty' => 'pemusik', 'has_day_job' => true,  'day_job_desc' => 'Guru SD',          'day_job_start' => '07:00', 'day_job_end' => '14:00', 'is_backup' => false],
            ['name' => 'Bapak Asep Hidayat',   'email' => 'asep@arthub.local',    'specialty' => 'pemusik', 'has_day_job' => true,  'day_job_desc' => 'Karyawan Swasta',  'day_job_start' => '09:00', 'day_job_end' => '17:00', 'is_backup' => false],
            ['name' => 'Bapak Rahmat Soleh',   'email' => 'rahmat@arthub.local',  'specialty' => 'pemusik', 'has_day_job' => false, 'is_backup' => false],
            ['name' => 'Bapak Cecep Rustandi', 'email' => 'cecep@arthub.local',   'specialty' => 'pemusik', 'has_day_job' => false, 'is_backup' => false],

            // Cadangan Multi-Talent (1 orang)
            ['name' => 'Indra Gunawan',    'email' => 'indra@arthub.local',   'specialty' => 'multi_talent', 'has_day_job' => false, 'is_backup' => true],
        ];

        foreach ($personnel as $p) {
            $userId = DB::table('users')->insertGetId([
                'name'       => $p['name'],
                'email'      => $p['email'],
                'password'   => Hash::make('personel123'),
                'role'       => 'personel',
                'phone'      => '08' . rand(1000000000, 9999999999),
                'address'    => 'Tangerang, Banten',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('personnel')->insert([
                'user_id'       => $userId,
                'specialty'     => $p['specialty'],
                'has_day_job'   => $p['has_day_job'],
                'day_job_desc'  => $p['day_job_desc'] ?? null,
                'day_job_start' => $p['day_job_start'] ?? null,
                'day_job_end'   => $p['day_job_end'] ?? null,
                'is_active'     => true,
                'is_backup'     => $p['is_backup'],
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        // ═══ KLIEN: 2 Klien Sampel ═══
        DB::table('users')->insert([
            [
                'name'       => 'Hj. Sari Mulyani',
                'email'      => 'sari@email.com',
                'password'   => Hash::make('klien123'),
                'role'       => 'klien',
                'phone'      => '081298765432',
                'address'    => 'Jl. Melati No. 10, Karawaci, Tangerang',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name'       => 'Bapak Hendra Wijaya',
                'email'      => 'hendra@email.com',
                'password'   => Hash::make('klien123'),
                'role'       => 'klien',
                'phone'      => '081387654321',
                'address'    => 'Jl. Cendana Raya No. 5, BSD, Tangerang Selatan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
