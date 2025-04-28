<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            DB::statement("
                ALTER TABLE patients
                ADD COLUMN full_name VARCHAR(255)
                GENERATED ALWAYS AS (CONCAT_WS(' ', firstName, lastName)) STORED
            ");

            // Add index
            $table->index('full_name', 'full_name_index');
        });
    }

    public function down()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropIndex('full_name_index');
            DB::statement("ALTER TABLE patients DROP COLUMN full_name");
        });
    }
};