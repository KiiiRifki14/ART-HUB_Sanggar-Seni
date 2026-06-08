<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_personnel', function (Blueprint $table) {
            $table->string('status', 50)->default('assigned')->change();
        });
    }

    public function down(): void
    {
        Schema::table('event_personnel', function (Blueprint $table) {
            $table->enum('status', ['assigned', 'confirmed', 'unavailable', 'replaced'])->default('assigned')->change();
        });
    }
};
