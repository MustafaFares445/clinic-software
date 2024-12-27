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
            $table->id();
            $table->dateTime('start');
            $table->dateTime('end')->nullable();
            $table->foreignId('patient_id')->constrained('patients');
            $table->foreignId('clinic_id')->constrained('clinics');
            $table->foreignId('doctor_id')->nullable()->constrained('users');
            $table->enum('type' , ReservationTypes::values())->default('appointment');
            $table->enum('status' , ReservationStatuses::values())->default('income');
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
