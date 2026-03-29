<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->onDelete('cascade');

            $table->decimal('total_revenue', 15, 2)->default(0)->comment('Total harga event');

            // Fixed Profit (Laba Pak Yat)
            $table->decimal('fixed_profit_pct', 5, 2)->default(30.00)->comment('Persentase default 30%');
            $table->boolean('is_profit_overridden')->default(false)->comment('Apakah di-override manual');
            $table->decimal('fixed_profit', 15, 2)->default(0)->comment('Nominal laba tetap pimpinan');

            $table->decimal('dp_received', 15, 2)->default(0);
            $table->decimal('total_personnel_honor', 15, 2)->default(0)->comment('Total honor semua personel');

            // Budget Operasional
            $table->decimal('operational_budget', 15, 2)->default(0)->comment('Anggaran dari sisa DP');
            $table->decimal('actual_operational_cost', 15, 2)->default(0)->comment('Realisasi biaya');
            $table->decimal('net_profit', 15, 2)->default(0)->comment('Laba bersih akhir');

            // Safety Buffer
            $table->decimal('safety_buffer_pct', 5, 2)->default(10.00)->comment('10% buffer dari anggaran');
            $table->decimal('safety_buffer_amt', 15, 2)->default(0);
            $table->boolean('budget_warning')->default(false);
            $table->text('warning_message')->nullable();

            $table->boolean('profit_locked')->default(false)->comment('DP masuk = profit terkunci');
            $table->enum('status', ['draft', 'locked', 'finalized', 'audited'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_records');
    }
};
