<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Enums\ProjectStatus;
use App\Http\Enums\PriorityStatus;

class CreateProjectInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_infos', function (Blueprint $table) {
            $table->id();

            // ---- Basic info ----
            $table->string('name')->nullable();
            $table->string('code')->nullable()->unique();
            $table->text('description')->nullable();

            // ---- Client ----
            // $table->foreignId('client_id')->nullable()
            //     ->constrained('clients')->nullOnDelete();
            $table->unsignedBigInteger('client_id')->nullable();

            // ---- Assignment ----
            $table->string('assignee')->nullable();
            $table->foreignId('manager_id')->nullable()
                ->constrained('users')->nullOnDelete();

            // ---- Dates ----
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('project_date')->nullable();
            $table->date('deadline')->nullable();

            // ---- Status / progress ----
            // $table->enum('status', [
            //     'planning',
            //     'in_progress',
            //     'on_hold',
            //     'completed',
            //     'cancelled',
            // ])->default('planning');

            $table->enum('status', ProjectStatus::values())
                ->default(ProjectStatus::Active->value);

            // $table->enum('priority', [
            //     'low',
            //     'medium',
            //     'high',
            //     'urgent',
            // ])->default('medium');

            $table->enum('priority', PriorityStatus::values())->default(PriorityStatus::Medium->value);

            $table->unsignedTinyInteger('progress')->default(0);

            // ---- Budget ----
            $table->decimal('budget', 14, 2)->nullable();
            $table->decimal('actual_cost', 14, 2)->nullable();
            $table->string('currency', 8)->default('USD');

            // ---- Relations ----
            $table->foreignId('branch_id')->nullable()
                ->constrained('branch')->nullOnDelete();
            $table->foreignId('department_id')->nullable()
                ->constrained('departments')->nullOnDelete();
            $table->foreignId('created_by')->nullable()
                ->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()
                ->constrained('users')->nullOnDelete();

            // ---- Extra ----
            $table->json('meta')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('status');
            $table->index('priority');
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_infos');
    }
}
