<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Enums\BranchStatus;

class CreateBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->default(null);
            $table->text('description')->nullable()->default(null);

            // ---- Status ----
            // $table->enum('status', [
            //     'active',
            //     'inactive',
            //     'closed',
            //     'under_maintenance',
            // ])->default('active');

            $table->enum('status', BranchStatus::values())->default(BranchStatus::Active->value);

            $table->timestamps();
            $table->softDeletes();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('branch');
    }
}
