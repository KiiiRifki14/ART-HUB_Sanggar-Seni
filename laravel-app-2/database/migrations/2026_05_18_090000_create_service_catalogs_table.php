<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_catalogs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');         // deskripsi singkat (tampil di kartu)
            $table->text('detail')->nullable();   // deskripsi panjang (tampil di modal popup)
            $table->unsignedBigInteger('price');  // harga dalam rupiah
            $table->string('image')->nullable();  // path ke storage/public/catalogs/
            $table->string('badge')->nullable();  // label: Favorit, Baru, dll (opsional)
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed 3 data default agar landing page tidak kosong
        $defaults = [
            [
                'name'        => 'Pertunjukan Tari',
                'description' => 'Pilihan tari tradisional mulai dari Keraton hingga tari rakyat yang enerjik untuk acara formal maupun perayaan.',
                'detail'      => 'Pilihan tari tradisional mulai dari Keraton hingga tari rakyat yang enerjik untuk acara formal maupun perayaan. Ideal untuk opening event, resepsi, dll. (Estimasi Durasi: 15-30 Menit, Tim: 5-7 Penari)',
                'price'       => 2500000,
                'image'       => null,
                'badge'       => 'Favorit',
                'is_active'   => true,
                'sort_order'  => 1,
            ],
            [
                'name'        => 'Ensembel Musik',
                'description' => 'Harmoni magis Gamelan, Angklung, atau Kecapi Suling untuk menciptakan suasana yang tenang dan bermartabat.',
                'detail'      => 'Harmoni magis Gamelan, Angklung, atau Kecapi Suling untuk menciptakan suasana yang tenang dan bermartabat. (Estimasi Durasi: 2-3 Jam, Tim: 8-12 Pemusik)',
                'price'       => 3800000,
                'image'       => null,
                'badge'       => null,
                'is_active'   => true,
                'sort_order'  => 2,
            ],
            [
                'name'        => 'Paket Gabungan',
                'description' => 'Kolaborasi megah musik dan tari dalam satu konsep pertunjukan teatrikal yang tak terlupakan.',
                'detail'      => 'Kolaborasi megah musik dan tari dalam satu konsep pertunjukan teatrikal yang tak terlupakan. (Estimasi Durasi: Full Event, Tim: 15-20 Seniman)',
                'price'       => 5500000,
                'image'       => null,
                'badge'       => null,
                'is_active'   => true,
                'sort_order'  => 3,
            ],
        ];

        foreach ($defaults as $data) {
            \DB::table('service_catalogs')->insert(array_merge($data, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('service_catalogs');
    }
};
