<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('costume_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('costume_id')->constrained('sanggar_costumes')->onDelete('cascade');
            $table->integer('quantity_used')->default(1);
            $table->date('checkout_date');
            $table->date('expected_return_date');
            $table->date('actual_return_date')->nullable();
            $table->enum('status', ['checked_out', 'returned', 'damaged', 'lost'])->default('checked_out');
            $table->text('damage_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('costume_usages');
    }
};
