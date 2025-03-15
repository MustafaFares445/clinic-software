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
        Schema::create('plans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->longText('description');
            $table->boolean('clinicsUsers')->default(false);
            $table->unsignedBigInteger('fixed_value')->nullable(); // dollar price
            $table->unsignedBigInteger('percent_value')->nullable(); // percent price
            $table->unsignedInteger('users_count')->nullable(); // null mean unlimited
            $table->time('duration')->nullable(); // null mean unlimited
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
