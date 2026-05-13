<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Personnel;
use App\Models\FeeReference;
use App\Models\SanggarCostume;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ═══════════════════════════════════════════════════
        // 1. USERS (Admin & Personel Saja)
        // ═══════════════════════════════════════════════════
        $admin = User::create([
            'name' => 'Pak Yatno (Admin)',
            'email' => 'pakyat@arthub.local',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 12 Personel Users
        $personnelData = [
            ['name' => 'Sinta Nurhaliza',   'specialty' => 'penari',       'has_day_job' => false, 'day_job_desc' => null, 'is_backup' => false],
            ['name' => 'Dewi Anggraeni',    'specialty' => 'penari',       'has_day_job' => false, 'day_job_desc' => null, 'is_backup' => false],
            ['name' => 'Rina Kartika',      'specialty' => 'penari',       'has_day_job' => true,  'day_job_desc' => 'PNS Dinas Pendidikan', 'is_backup' => false],
            ['name' => 'Neng Kokom',        'specialty' => 'penari',       'has_day_job' => false, 'day_job_desc' => null, 'is_backup' => false],
            ['name' => 'Yuli Rahayu',       'specialty' => 'penari',       'has_day_job' => true,  'day_job_desc' => 'Guru SD Negeri', 'is_backup' => false],
            ['name' => 'Asep Sunandar',     'specialty' => 'pemusik',      'has_day_job' => false, 'day_job_desc' => null, 'is_backup' => false],
            ['name' => 'Dadan Supardan',    'specialty' => 'pemusik',      'has_day_job' => true,  'day_job_desc' => 'Pegawai Kecamatan', 'is_backup' => false],
            ['name' => 'Ujang Kurniawan',   'specialty' => 'pemusik',      'has_day_job' => true,  'day_job_desc' => 'Satpam Bank BRI', 'is_backup' => false],
            ['name' => 'Iis Sugianti',      'specialty' => 'penari',       'has_day_job' => false, 'day_job_desc' => null, 'is_backup' => false],
            ['name' => 'Lilis Suryani',     'specialty' => 'penari',       'has_day_job' => false, 'day_job_desc' => null, 'is_backup' => false],
            ['name' => 'Bambang Pamungkas', 'specialty' => 'multi_talent', 'has_day_job' => false, 'day_job_desc' => null, 'is_backup' => false],
            ['name' => 'Cecep Hidayat',     'specialty' => 'multi_talent', 'has_day_job' => false, 'day_job_desc' => null, 'is_backup' => true],
        ];

        foreach ($personnelData as $i => $p) {
            $user = User::create([
                'name' => $p['name'],
                'email' => strtolower(str_replace([' ', '.'], ['_', ''], $p['name'])) . '@arthub.local',
                'password' => Hash::make('password'),
                'role' => 'personel',
            ]);
            Personnel::create([
                'user_id' => $user->id,
                'specialty' => $p['specialty'],
                'has_day_job' => $p['has_day_job'],
                'day_job_desc' => $p['day_job_desc'],
                'day_job_start' => $p['has_day_job'] ? '08:00:00' : null,
                'day_job_end' => $p['has_day_job'] ? '16:00:00' : null,
                'is_active' => true,
                'is_backup' => $p['is_backup'],
            ]);
        }

        // ═══════════════════════════════════════════════════
        // 2. FEE REFERENCES (Tarif per Role)
        // ═══════════════════════════════════════════════════
        FeeReference::create(['role_name' => 'Penari Utama', 'base_fee' => 350000, 'description' => 'Peran utama koreografi']);
        FeeReference::create(['role_name' => 'Penari Latar',  'base_fee' => 250000, 'description' => 'Pendukung formasi tari']);
        FeeReference::create(['role_name' => 'Pemusik',       'base_fee' => 300000, 'description' => 'Pengiring musik gamelan/degung']);
        FeeReference::create(['role_name' => 'Cadangan',      'base_fee' => 200000, 'description' => 'Standby multi-talent']);

        // ═══════════════════════════════════════════════════
        // 3. SANGGAR COSTUMES (Aset Milik Sanggar)
        // ═══════════════════════════════════════════════════
        SanggarCostume::create(['name' => 'Kebaya Jaipong Merah', 'category' => 'atasan', 'condition' => 'good', 'quantity' => 5]);
        SanggarCostume::create(['name' => 'Sampur Emas', 'category' => 'aksesoris', 'condition' => 'good', 'quantity' => 12]);
        SanggarCostume::create(['name' => 'Sinjang Batik', 'category' => 'bawahan', 'condition' => 'good', 'quantity' => 8]);
        SanggarCostume::create(['name' => 'Mahkota Siger', 'category' => 'aksesoris', 'condition' => 'damaged', 'quantity' => 2]);
        SanggarCostume::create(['name' => 'Gondang (Gendang Besar)', 'category' => 'alat_musik', 'condition' => 'good', 'quantity' => 4]);
    }
}
