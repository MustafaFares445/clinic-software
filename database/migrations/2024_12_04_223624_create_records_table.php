<?php

use App\Enums\RecordTypes;
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
            $table->foreignUuid('patient_id')->constrained('patients');
            $table->foreignUuid('clinic_id')->constrained('clinics');
            $table->foreignUuid('reservation_id')->nullable()->constrained('reservations');
            $table->longText('description');
            $table->enum('type', array_column(RecordTypes::cases(), 'value'))->default(RecordTypes::APPOINTMENT->value)->index();
            $table->dateTime('dateTime');
            $table->longText('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
