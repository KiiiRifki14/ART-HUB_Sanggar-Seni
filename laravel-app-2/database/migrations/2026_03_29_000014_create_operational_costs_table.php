<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('operational_costs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('financial_record_id')->constrained('financial_records')->onDelete('cascade');
            $table->string('category', 50)->comment('bensin, konsumsi, parkir, honor, denda_insiden, sewa_kostum, lainnya');
            $table->string('description')->nullable();
            $table->decimal('estimated_amount', 15, 2)->default(0);
            $table->decimal('actual_amount', 15, 2)->default(0);
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('operational_costs');
    }
};
