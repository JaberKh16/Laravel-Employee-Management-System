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

            /* ---------- Identity ---------- */
            $table->string('name');
            $table->string('code')->nullable()->unique();
            $table->text('description')->nullable();

            /* ---------- Contact ---------- */
            $table->string('email')->nullable();
            $table->string('phone', 30)->nullable();

            /* ---------- Address ---------- */
            $table->string('address')->nullable();
            $table->string('zip_code', 20)->nullable();

            $table->foreignId('country_id')->nullable()
                ->constrained('countries')->nullOnDelete();
            $table->foreignId('state_id')->nullable()
                ->constrained('states')->nullOnDelete();
            $table->foreignId('city_id')->nullable()
                ->constrained('cities')->nullOnDelete();

            /* ---------- Management ---------- */
            $table->string('manager_name')->nullable();

            /* ---------- Status ---------- */
            // ---- Status ----
            // $table->enum('status', [
            //     'active',
            //     'inactive',
            //     'closed',
            //     'under_maintenance',
            // ])->default('active');
            $table->enum('status', BranchStatus::values())
                ->default(BranchStatus::Active->value)
                ->index();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch');
    }

   
}
