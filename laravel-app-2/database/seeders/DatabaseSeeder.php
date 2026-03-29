<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Urutan penting! Seeder dijalankan sesuai urutan dependensi tabel.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,          // 1. Users + Personnel (admin, 12 personel, 2 klien)
            FeeReferenceSeeder::class,  // 2. Tarif honor per peran
            SanggarCostumeSeeder::class, // 3. Inventaris kostum sanggar
            CostumeVendorSeeder::class,  // 4. Vendor kostum (Subang & Bandung)
            SampleBookingSeeder::class,  // 5. Booking sampel (termasuk Quick Entry)
        ]);
    }
}
