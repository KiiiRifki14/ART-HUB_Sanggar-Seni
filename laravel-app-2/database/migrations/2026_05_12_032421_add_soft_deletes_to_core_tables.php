<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (!Schema::hasColumn('bookings', 'deleted_at')) { $table->softDeletes(); }
        });
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'deleted_at')) { $table->softDeletes(); }
        });
        Schema::table('personnel', function (Blueprint $table) {
            if (!Schema::hasColumn('personnel', 'deleted_at')) { $table->softDeletes(); }
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('events', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
        Schema::table('personnel', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
