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
        Schema::create('filling_materials', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('color');
            $table->foreignUuid('laboratory_id')->constrained('laboratories')->references('id');
            $table->foreignUuid('clinic_id')->constrained('clinics')->references('id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filling_materials');
    }
};
