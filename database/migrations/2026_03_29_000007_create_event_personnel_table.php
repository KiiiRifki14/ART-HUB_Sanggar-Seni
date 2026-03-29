<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_personnel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('personnel_id')->constrained('personnel')->onDelete('cascade');
            $table->foreignId('fee_reference_id')->nullable()->constrained('fee_references')->onDelete('set null');
            $table->enum('role_in_event', ['penari_utama', 'penari_latar', 'pemusik', 'cadangan'])->default('penari_latar');
            $table->enum('status', ['assigned', 'confirmed', 'unavailable', 'replaced'])->default('assigned');
            $table->decimal('fee', 15, 2)->default(0)->comment('Otomatis dari fee_references atau override');

            // Absensi / Check-in
            $table->timestamp('checked_in_at')->nullable()->comment('Waktu check-in di lokasi');
            $table->enum('attendance_status', ['not_arrived', 'on_time', 'late', 'absent'])->default('not_arrived');
            $table->integer('late_minutes')->default(0);

            $table->timestamps();

            // Satu personel tidak boleh di-assign 2x ke event yang sama
            $table->unique(['event_id', 'personnel_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_personnel');
    }
};
