<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddClientIdFkToProjectsInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('project_infos', function (Blueprint $table) {
            $table->foreign('client_id')
                ->references('id')
                ->on('clients_infos')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('project_infos', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
        });
    }
}
