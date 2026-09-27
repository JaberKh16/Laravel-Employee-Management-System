<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('clients_infos', function (Blueprint $table) {
            $table->id();

            // ---- Basic info ----
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('code')->nullable()->unique();     // client code / short key
            $table->string('company_name')->nullable();
            $table->text('description')->nullable();

            // ---- Contact ----
            $table->string('contact_person')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('alternate_phone')->nullable();
            $table->string('website')->nullable();

            // ---- Address ----
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('postal_code')->nullable();

            // ---- Business / tax ----
            $table->string('tax_id')->nullable();            // VAT / GST / TIN
            $table->string('registration_number')->nullable();
            $table->string('currency', 8)->default('USD');

            // ---- Status ----
            $table->enum('status', [
                'active',
                'inactive',
                'prospect',
                'archived',
            ])->default('active');

            // ---- Relations ----
            $table->foreignId('branch_id')->nullable()
                ->constrained('branch')->nullOnDelete();
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
            $table->index('email');
            $table->index('company_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients_infos');
    }
}
