<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('client_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->tinyInteger('rating')->comment('1 sampai 5 bintang');
            $table->text('testimony')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('client_feedbacks');
    }
};
