<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CostumeVendorSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('costume_vendors')->insert([
            [
                'name'                 => 'Sanggar Pusaka Subang',
                'city'                 => 'Subang',
                'phone'                => '082112345678',
                'address'              => 'Jl. Otista No. 45, Subang, Jawa Barat',
                'return_deadline_days' => 3,
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
            [
                'name'                 => 'Rumah Kostum Bandung',
                'city'                 => 'Bandung',
                'phone'                => '081398765432',
                'address'              => 'Jl. Braga No. 78, Bandung, Jawa Barat',
                'return_deadline_days' => 5,
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
            [
                'name'                 => 'Toko Seni Merdeka',
                'city'                 => 'Bandung',
                'phone'                => '085211223344',
                'address'              => 'Jl. Merdeka No. 12, Bandung, Jawa Barat',
                'return_deadline_days' => 4,
                'created_at'           => now(),
                'updated_at'           => now(),
            ],
        ]);
    }
}
