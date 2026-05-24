<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FIX B-05: Tambahkan kolom audit digital acknowledgement pada tabel cancellations.
 * Kolom ini menyimpan IP address, user agent, dan timestamp sebagai bukti hukum digital
 * bahwa klien benar-benar menyetujui syarat pembatalan.
 * 
 * FIX A-09: Tidak ada perubahan schema untuk specialty karena kolom sudah ada.
 * Perbaikan A-09 dilakukan di level controller.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cancellations', function (Blueprint $table) {
            // Kolom audit digital acknowledgement (B-05)
            if (!Schema::hasColumn('cancellations', 'acknowledged_ip')) {
                $table->string('acknowledged_ip', 45)->nullable()->after('digital_acknowledgement')
                      ->comment('IP address klien saat menyetujui digital acknowledgement');
            }
            if (!Schema::hasColumn('cancellations', 'acknowledged_at')) {
                $table->timestamp('acknowledged_at')->nullable()->after('acknowledged_ip')
                      ->comment('Timestamp saat klien menyetujui digital acknowledgement');
            }
            if (!Schema::hasColumn('cancellations', 'acknowledged_ua')) {
                $table->string('acknowledged_ua', 255)->nullable()->after('acknowledged_at')
                      ->comment('User agent browser klien saat menyetujui digital acknowledgement');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cancellations', function (Blueprint $table) {
            $table->dropColumn(['acknowledged_ip', 'acknowledged_at', 'acknowledged_ua']);
        });
    }
};
