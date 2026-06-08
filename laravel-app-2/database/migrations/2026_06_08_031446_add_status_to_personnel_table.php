<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('personnel', function (Blueprint $table) {
            $table->string('status')->default('active')->after('is_active');
        });

        // Backfill existing data
        DB::table('personnel')->where('is_active', true)->update(['status' => 'active']);
        DB::table('personnel')->where('is_active', false)->update(['status' => 'pending_verification']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personnel', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
