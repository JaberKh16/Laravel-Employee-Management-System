<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_accounts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('provider', 50);              // google, github…
            $table->string('provider_user_id', 191);     // OAuth sub / id

            $table->string('provider_email')->nullable();
            $table->string('nickname')->nullable();
            $table->string('name')->nullable();
            $table->text('avatar')->nullable();

            // Tokens
            $table->text('access_token')->nullable();
            $table->text('refresh_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->json('raw')->nullable();             // full payload from provider

            $table->timestamps();

            // Prevent linking the same provider account twice globally
            $table->unique(['provider', 'provider_user_id']);
            // One row per (user, provider) if you prefer strict single-link:
            $table->unique(['user_id', 'provider']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_accounts');
    }
}
