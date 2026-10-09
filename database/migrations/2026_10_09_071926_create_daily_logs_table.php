<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateDailyLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('daily_logs', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });
        // NOTE: the partition key (logged_at) must be part of the PK.
        DB::statement("
            CREATE TABLE daily_logs (
                id          BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                user_id     BIGINT UNSIGNED NOT NULL,
                logged_at   DATETIME NOT NULL,
                metric      VARCHAR(64) NOT NULL,
                value       DOUBLE NOT NULL,
                meta        JSON NULL,
                created_at  TIMESTAMP NULL,
                updated_at  TIMESTAMP NULL,
                PRIMARY KEY (id, logged_at),
                KEY idx_user_metric_time (user_id, metric, logged_at)
            )
            ENGINE=InnoDB
            DEFAULT CHARSET=utf8mb4
            COLLATE=utf8mb4_unicode_ci
            PARTITION BY RANGE (TO_DAYS(logged_at)) (
                PARTITION p2025_01 VALUES LESS THAN (TO_DAYS('2025-02-01')),
                PARTITION p2025_02 VALUES LESS THAN (TO_DAYS('2025-03-01')),
                PARTITION p2025_03 VALUES LESS THAN (TO_DAYS('2025-04-01')),
                PARTITION pmax     VALUES LESS THAN MAXVALUE
            );
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('daily_logs');
        DB::statement('DROP TABLE IF EXISTS daily_logs');
    }
}
