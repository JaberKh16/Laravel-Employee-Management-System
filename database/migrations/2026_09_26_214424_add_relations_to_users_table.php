<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()
                ->constrained('roles')->nullOnDelete();
            $table->foreignId('profile_id')->nullable()->after('role_id')
                ->constrained('profiles')->nullOnDelete();
            $table->foreignId('dept_id')->nullable()->after('profile_id')
                ->constrained('departments')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->after('dept_id')
                ->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->after('created_by')
                ->constrained('users')->nullOnDelete();

            // Status
            $table->boolean('status')->default(1);   // 1 = active, 0 = inactive
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id', 'profile_id', 'dept_id', 'created_by', 'updated_by']);
            $table->dropColumn(['role_id', 'profile_id', 'dept_id', 'created_by', 'updated_by']);
        });
    }
    
}
