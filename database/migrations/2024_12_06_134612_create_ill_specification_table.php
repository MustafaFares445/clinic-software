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
        Schema::create('ill_specification', function (Blueprint $table) {
            $table->foreignUuid('specification_id')->constrained('specifications');
            $table->foreignUuid('ill_id')->constrained('ills');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_ill');
    }
};
