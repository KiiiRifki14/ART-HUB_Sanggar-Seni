<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FeeReferenceSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('fee_references')->insert([
            [
                'role_name'   => 'Penari Utama',
                'base_fee'    => 500000,
                'description' => 'Penari utama yang memimpin formasi tarian',
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'role_name'   => 'Penari Latar',
                'base_fee'    => 350000,
                'description' => 'Penari pendukung dalam formasi',
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'role_name'   => 'Pemusik',
                'base_fee'    => 400000,
                'description' => 'Pemain alat musik tradisional (kendang, saron, dll)',
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
            [
                'role_name'   => 'Cadangan',
                'base_fee'    => 300000,
                'description' => 'Personel multi-talent cadangan (bisa menari dan bermain musik)',
                'is_active'   => true,
                'created_at'  => now(),
                'updated_at'  => now(),
            ],
        ]);
    }
}
