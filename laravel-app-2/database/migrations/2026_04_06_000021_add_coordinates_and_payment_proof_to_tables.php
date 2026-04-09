<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('events', 'latitude')) {
            Schema::table('events', function (Blueprint $table) {
                $table->decimal('latitude', 10, 8)->nullable()->after('venue');
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            });
        }

        if (!Schema::hasColumn('bookings', 'payment_proof')) {
            Schema::table('bookings', function (Blueprint $table) {
                $table->string('payment_proof')->nullable()->after('dp_paid_at');
            });
        }
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('payment_proof');
        });
    }
};
