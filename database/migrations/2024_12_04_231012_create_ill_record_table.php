<?php

use App\Enums\RecordIllsTypes;
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
        Schema::create('ill_record', function (Blueprint $table) {
            $table->foreignUuid('ill_id')->constrained('ills');
            $table->foreignUuid('record_id')->constrained('records');
            $table->enum('type' , array_column(RecordIllsTypes::cases(), 'value'))->default(RecordIllsTypes::DIAGNOSED->value)->index();
            $table->unique(['ill_id' , 'record_id' , 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ill_record');
    }
};
