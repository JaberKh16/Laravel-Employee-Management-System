<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('job_profiles', function (Blueprint $table) {
            $table->id();

            $table->enum('employment_type', [
                'full_time',
                'part_time',
                'contract',
                'intern',
                'temporary',
                'freelance',
            ])->nullable();

            // ---- Employment details ----
            $table->string('designation')->nullable();

            // If departments table exists, use FK instead of string:
            $table->foreignId('department_id')->nullable()
                ->constrained('departments')->nullOnDelete();

            // ---- Tenure / promotion ----
            $table->integer('calculated_years')->nullable();
            $table->boolean('is_promoted')->default(false);
            $table->date('last_promotion_date')->nullable();

            // ---- Compensation ----
            $table->decimal('basic_salary', 12, 2)->nullable();
            $table->decimal('allowance', 12, 2)->nullable();
            $table->string('currency', 8)->default('USD');
            $table->string('bank_account')->nullable();

            // ---- Personal info ----
            $table->string('national_id')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('nationality')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('phone')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->text('address')->nullable();
            $table->string('postal_code')->nullable();

            // ---- Relations ----
            $table->foreignId('user_id')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()
                ->constrained('branch')->nullOnDelete();


            // ---- Extra ----
            $table->text('notes')->nullable();
            $table->json('meta')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('designation');
            $table->index('department_id');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_profiles');
    }
}
