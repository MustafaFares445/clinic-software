<?php

use App\Enums\ClinicTypes;
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
        Schema::create('clinics', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('address')->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->longText('description')->nullable();
            $table->boolean('is_banned')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('number_of_doctors')->default(1);
            $table->unsignedSmallInteger('number_of_secretariat')->default(1);
            $table->enum('type', array_column(ClinicTypes::cases(), 'value'))->default(ClinicTypes::CLINIC->value);
            $table->foreignUuid('clinic_id')->constrained('clinics');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinics');
    }
};
