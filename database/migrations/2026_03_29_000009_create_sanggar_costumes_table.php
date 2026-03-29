<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sanggar_costumes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->comment('Kostum Jaipong Set A');
            $table->string('category', 50)->comment('jaipong, rampak, degung, topeng');
            $table->integer('quantity')->default(1);
            $table->enum('condition', ['good', 'damaged', 'maintenance'])->default('good');
            $table->string('storage_location')->nullable()->comment('Lemari A, Rak 2');
            $table->text('description')->nullable();
            $table->timestamp('last_cleaned_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sanggar_costumes');
    }
};
