<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('event_code', 20)->unique()->comment('EVT-2026-001');
            $table->enum('status', ['planning', 'rehearsal', 'ready', 'ongoing', 'completed', 'cancelled'])->default('planning');
            $table->date('event_date');
            $table->time('event_start');
            $table->time('event_end');
            $table->string('venue');
            $table->integer('personnel_count')->default(12)->comment('11 inti + 1 cadangan');
            $table->decimal('estimated_total_honor', 15, 2)->default(0)->comment('Auto dari fee_references');
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
