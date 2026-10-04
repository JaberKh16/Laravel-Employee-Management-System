<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();

            // ISO 3166-1 alpha-2 codes are exactly 2 chars (US, BD, IN).
            // char(2) is fine but string(3) is more forgiving if you ever
            // need to store alpha-3 codes without another migration.
            $table->char('country_code', 2)->unique();

            $table->string('name')->unique();

            // TEXT columns can't have a DEFAULT in MySQL (portable DBs reject it).
            // Make it nullable instead — same effect, works everywhere.
            $table->text('description')->nullable();

            $table->timestamps();

            $table->index('name'); // speeds up search/orderBy on the index page
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
