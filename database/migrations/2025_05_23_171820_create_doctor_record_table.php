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
        Schema::create('doctor_record', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('record_id')->constrained('records')->references('id');
            $table->foreignUuid('doctor_id')->constrained('users')->references('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_medical_session');
    }
};
