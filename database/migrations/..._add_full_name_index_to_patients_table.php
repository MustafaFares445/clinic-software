<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddFullNameIndexToPatientsTable extends Migration
{
    public function up()
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->string('full_name', 255)
                ->generatedAs(DB::raw("CONCAT_WS(' ', firstName, lastName)"))
                ->charset('utf8mb4')
                ->collation('utf8mb4_unicode_ci')
                ->stored();

             // Add index
             $table->index('full_name', 'full_name_index');
        });
    }

    public function down()
    {
        Schema::table('patients', function  (Blueprint $table) {
            $table->dropIndex('full_name_index');
            $table->dropColumn('full_name');
        });
    }
}