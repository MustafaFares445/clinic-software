<?php

use App\Enums\ReservationStatuses;
use App\Enums\ReservationTypes;
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
        Schema::create('reservations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->timestamp('start');
            $table->timestamp('end');
            $table->foreignUuid('patient_id')->constrained('patients');
            $table->foreignUuid('clinic_id')->constrained('clinics');
            $table->foreignUuid('doctor_id')->nullable()->constrained('users');
            $table->foreignUuid('medical_case_id')->nullable()->constrained('medical_cases')->references('id');
            $table->enum('type', array_column(ReservationTypes::cases(), 'value'))->default(ReservationTypes::APPOINTMENT->value);
            $table->enum('status', array_column(ReservationStatuses::cases(), 'value'))->default(ReservationStatuses::INCOME->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
