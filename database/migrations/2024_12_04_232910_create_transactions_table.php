<?php

use App\Enums\TransactionFromTypes;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users');
            $table->foreignUuid('clinic_id')->constrained('clinics');
            $table->uuidMorphs('relateable');
            $table->enum('type' , ['in' , 'out'])->index();
            $table->unsignedBigInteger('amount');
            $table->enum('from' , array_column(TransactionFromTypes::cases(), 'value'))->index();
            $table->boolean('finance')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
