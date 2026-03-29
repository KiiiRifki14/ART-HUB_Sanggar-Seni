<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_audits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('financial_record_id')->constrained('financial_records')->onDelete('cascade');
            $table->string('field_changed');
            $table->decimal('old_value', 15, 2)->default(0);
            $table->decimal('new_value', 15, 2)->default(0);
            $table->foreignId('changed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('change_reason')->nullable();
            $table->timestamp('changed_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_audits');
    }
};
