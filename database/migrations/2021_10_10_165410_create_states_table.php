<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('states', function (Blueprint $table) {
        //     $table->id();
        //     $table->foreignId('country_id')->constrained();
        //     $table->string('name');
        //     $table->text('description')->default(null);
        //     $table->string('state_code')->default(null);
        //     $table->timestamps();
        // });

        Schema::create('states', function (Blueprint $table) {
            $table->id();

            // cascadeOnDelete: deleting a country removes its states.
            // If you'd rather block deletion, use ->restrictOnDelete().
            $table->foreignId('country_id')
                ->constrained('countries')
                ->cascadeOnDelete();

            $table->string('name');

            // TEXT can't have DEFAULT in portable MySQL — use nullable().
            $table->text('description')->nullable();

            // Real ISO 3166-2 codes are up to 6 chars (e.g. "GB-ENG").
            // 10 gives headroom for future subdivisions without a migration.
            $table->string('state_code', 10)->nullable();

            $table->timestamps();

            // A state name only needs to be unique *within* its country,
            // not globally. This composite index enforces that and doubles
            // as the lookup index for the country → states relationship.
            $table->unique(['country_id', 'name']);

            // Speeds up the filter dropdown which lists states by code.
            $table->index('state_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('states');
    }
}
