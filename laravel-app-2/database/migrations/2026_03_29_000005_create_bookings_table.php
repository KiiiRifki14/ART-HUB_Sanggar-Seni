<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            // Nullable: Quick Entry admin tanpa registrasi klien
            $table->foreignId('client_id')->nullable()->constrained('users')->onDelete('set null');
            // Field manual untuk Quick Entry
            $table->string('client_name')->nullable()->comment('Nama klien (Quick Entry)');
            $table->string('client_phone', 20)->nullable()->comment('Telp klien (Quick Entry)');
            $table->string('client_email')->nullable()->comment('Email klien (Quick Entry)');

            $table->string('event_type', 50)->comment('jaipong, degung, rampak, dll');
            $table->date('event_date');
            $table->time('event_start');
            $table->time('event_end');
            $table->string('venue');
            $table->text('venue_address')->nullable();

            $table->decimal('total_price', 15, 2)->default(0);
            $table->decimal('dp_amount', 15, 2)->default(0)->comment('DP 50%');
            $table->string('payment_receipt')->nullable()->comment('Path file bukti transfer');

            $table->enum('status', ['pending', 'dp_paid', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->enum('booking_source', ['web', 'admin_manual'])->default('web');

            $table->text('client_notes')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamp('dp_paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
