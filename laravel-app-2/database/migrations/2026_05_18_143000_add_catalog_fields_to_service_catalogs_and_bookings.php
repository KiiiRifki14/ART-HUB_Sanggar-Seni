<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom ke service_catalogs
        Schema::table('service_catalogs', function (Blueprint $table) {
            $table->unsignedSmallInteger('max_personnel')->default(0)->after('sort_order')
                  ->comment('0 = tidak ada batas. > 0 = maks personel yang bisa di-plot');
            $table->enum('specialty_type', ['penari', 'pemusik', 'gabungan'])
                  ->default('gabungan')->after('max_personnel')
                  ->comment('Jenis personel yang dibutuhkan katalog ini');
        });

        // 2. Tambah service_catalog_id ke bookings
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('service_catalog_id')
                  ->nullable()
                  ->after('client_notes')
                  ->constrained('service_catalogs')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['service_catalog_id']);
            $table->dropColumn('service_catalog_id');
        });

        Schema::table('service_catalogs', function (Blueprint $table) {
            $table->dropColumn(['max_personnel', 'specialty_type']);
        });
    }
};
