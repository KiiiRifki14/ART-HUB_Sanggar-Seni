<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('costume_rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');
            $table->foreignId('vendor_id')->constrained('costume_vendors')->onDelete('cascade');
            $table->string('costume_type', 100);
            $table->integer('quantity')->default(1);
            $table->date('rental_date');
            $table->date('due_date');
            $table->date('returned_date')->nullable();
            $table->enum('status', ['rented', 'returned', 'overdue'])->default('rented');
            $table->decimal('rental_cost', 15, 2)->default(0);
            $table->decimal('overdue_fine', 15, 2)->default(0)->comment('Denda kumulatif');
            $table->integer('overdue_days')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('costume_rentals');
    }
};
