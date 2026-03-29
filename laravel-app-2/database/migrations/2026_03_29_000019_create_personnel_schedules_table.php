<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personnel_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('personnel_id')->constrained('personnel')->onDelete('cascade');
            $table->date('schedule_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('activity', 50)->comment('event, latihan, kerja_utama');
            $table->unsignedBigInteger('reference_id')->nullable()->comment('event_id atau rehearsal_id');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personnel_schedules');
    }
};
