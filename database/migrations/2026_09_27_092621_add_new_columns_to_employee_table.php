<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('emp_code')->nullable()->after('id');
            $table->string('designation')->nullable()->after('emp_code');
            $table->decimal('salary', 10, 2)->nullable()->after('designation');
            $table->date('hired_date')->nullable()->after('salary');
            $table->date('confirmation_date')->nullable()->after('hired_date');
            $table->date('termination_date')->nullable()->after('confirmation_date');
            $table->enum('status', [
                'active',
                'inactive',
                'on_leave',
                'suspended',
                'resigned',
                'terminated',
            ])->default('active')->after('termination_date');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'emp_code',
                'designation',
                'salary',
                'hired_date',
                'confirmation_date',
                'termination_date',
                'status',
            ]);
        });
    }
}
