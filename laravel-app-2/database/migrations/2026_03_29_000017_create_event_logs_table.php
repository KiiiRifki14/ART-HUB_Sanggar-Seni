<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->enum('log_type', ['insiden', 'catatan', 'denda_klien', 'keterlambatan', 'kerusakan']);
            $table->string('title')->comment('Judul singkat kejadian');
            $table->text('description')->nullable()->comment('Detail kejadian');
            $table->decimal('financial_impact', 15, 2)->nullable()->comment('Dampak keuangan jika ada');
            $table->foreignId('logged_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('logged_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_logs');
    }
};
