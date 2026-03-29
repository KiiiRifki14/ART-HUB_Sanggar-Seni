<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_references', function (Blueprint $table) {
            $table->id();
            $table->string('role_name', 50)->comment('Penari Utama, Pemusik, Penari Latar, Cadangan');
            $table->decimal('base_fee', 15, 2)->comment('Tarif dasar per event (Rupiah)');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_references');
    }
};
