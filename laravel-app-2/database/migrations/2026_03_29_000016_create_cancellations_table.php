<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cancellations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->date('cancellation_date');
            $table->integer('days_before_event')->default(0);
            $table->decimal('penalty_percentage', 5, 2)->default(0);
            $table->decimal('penalty_amount', 15, 2)->default(0);
            $table->decimal('refund_amount', 15, 2)->default(0);
            $table->enum('status', ['pending', 'processed', 'refunded'])->default('pending');
            $table->text('reason')->nullable();
            $table->boolean('digital_acknowledgement')->default(false)->comment('Tanda tangan digital kebijakan');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cancellations');
    }
};
