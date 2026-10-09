<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDailyLogsSummaryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_logs_summary', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('metric', 64);
            $table->date('bucket');           // one row per day

            $table->unsignedInteger('samples')->default(0);
            $table->double('total')->default(0);
            $table->double('average')->default(0);
            $table->double('min_value')->nullable();
            $table->double('max_value')->nullable();

            $table->timestamps();

            // one row per user/metric/day — makes upserts cheap
            $table->unique(['user_id', 'metric', 'bucket']);
            $table->index(['user_id', 'bucket']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('daily_logs_summary');
    }
}
