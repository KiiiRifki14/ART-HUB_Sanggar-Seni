<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Seed default CMS keys jika belum ada
        $defaults = [
            'sanggar_name'         => 'Cahaya Gumilang',
            'hero_tagline'         => 'Melestarikan Warisan Melalui Seni.',
            'hero_description'     => 'Experience the timeless beauty of Indonesian traditional arts, curated through generations of excellence and passion.',
            'hero_image'           => null,                  // path di storage
            'footer_address'       => 'Jakarta, Indonesia',
            'footer_email'         => 'halo@cahayagumilang.id',
            'footer_tagline'       => 'Pusat pelestarian dan pengembangan seni budaya tradisional Indonesia.',
            'footer_copyright'     => '© 2024 Cahaya Gumilang. All Rights Reserved.',
            'founder_photo'        => null,                  // path di storage
            'founder_photo_active' => '1',                   // 1 = tampil, 0 = disembunyikan
        ];

        foreach ($defaults as $key => $value) {
            \App\Models\SiteContent::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }

    public function down(): void
    {
        // tidak menghapus data
    }
};
