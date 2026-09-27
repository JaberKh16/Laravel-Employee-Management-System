<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveAnColumnFromEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        if (Schema::hasColumn('employees', 'date_hired')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('date_hired');
                
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('employees', 'date_hired')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropColumn('date_hired');
            });
        }
    }
}
