<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medical_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('record_id')->nullable()->constrained('records');
            $table->unsignedInteger('quantity')->default(1);
            $table->enum('type' , ['in' , 'out'])->index();
            $table->foreignUuid('doctor_id')->constrained('users')->references('id');
            $table->foreignUuid('clinic_id')->constrained('clinics');
            $table->foreignUuid('medicine_id')->constrained('medicines');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_transactions');
    }
};
