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
        Schema::create('records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->longText('description')->nullable();
            $table->enum('type' , ['in' , 'out']);

            $table->foreignUuid('patient_id')->constrained('patients')->references('id');
            $table->foreignUuid('tooth_id')->constrained('teeth')->references('id');
            $table->foreignUuid('clinic_id')->constrained('clinics')->references('id');
            $table->foreignUuid('treatment_id')->constrained('treatments')->references('id');
            $table->foreignUuid('filling_material_id')->nullable()->constrained('filling_materials')->references('id');
            $table->foreignUuid('medical_session_id')->constrained('medical_sessions')->references('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_sessions');
    }
};
