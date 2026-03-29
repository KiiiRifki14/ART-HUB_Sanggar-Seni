<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('costume_vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('city', 50)->comment('Subang, Bandung');
            $table->string('phone', 20)->nullable();
            $table->text('address')->nullable();
            $table->integer('return_deadline_days')->default(3)->comment('Batas hari pengembalian');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('costume_vendors');
    }
};
