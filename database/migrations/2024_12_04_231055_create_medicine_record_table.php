<?php

use App\Enums\RecordMedicinesTypes;
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
        Schema::create('medicine_record', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('medicine_id')->nullable()->constrained('medicines');
            $table->foreignUuid('record_id')->constrained('records');
            $table->enum('type', array_column(RecordMedicinesTypes::cases(), 'value'))->default(RecordMedicinesTypes::DIAGNOSED->value)->index();
            $table->text('note')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicine_record');
    }
};
