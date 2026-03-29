<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personnel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('specialty', ['penari', 'pemusik', 'multi_talent'])->default('penari');
            $table->boolean('has_day_job')->default(false);
            $table->string('day_job_desc')->nullable();
            $table->time('day_job_start')->nullable();
            $table->time('day_job_end')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_backup')->default(false)->comment('Cadangan multi-talent');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personnel');
    }
};
