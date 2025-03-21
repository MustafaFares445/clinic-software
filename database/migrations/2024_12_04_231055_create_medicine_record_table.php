<?php

use Illuminate\Support\Facades\DB;
use App\Enums\RecordMedicinesTypes;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medicine_record', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('(UUID())'));
            $table->foreignUuid('medicine_id')->nullable()->constrained('medicines');
            $table->foreignUuid('record_id')->constrained('records');
            $table->enum('type', array_column(RecordMedicinesTypes::cases(), 'value'))->default(RecordMedicinesTypes::DIAGNOSED->value)->index();
            $table->text('notes')->nullable();
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
