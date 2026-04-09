<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // Harga negosiasi (range) - digunakan saat status pending
            $table->decimal('price_min', 15, 2)->nullable()->after('total_price');
            $table->decimal('price_max', 15, 2)->nullable()->after('price_min');
            // Waktu pelunasan penuh (100%)
            $table->timestamp('full_paid_at')->nullable()->after('dp_paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['price_min', 'price_max', 'full_paid_at']);
        });
    }
};
