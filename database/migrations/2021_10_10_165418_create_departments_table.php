<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Enums\ActiveStatus;
class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->string('floor')->nullable();
            $table->tinyInteger('status')->default(ActiveStatus::Active->value);
            $table->foreignId('manager_id')
                ->nullable()
                ->unique()                     // remove if manager can run multiple depts
                ->constrained('users')
                ->nullOnDelete();              // delete manager → dept stays, manager_id = NULL

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departments');
    }
}
