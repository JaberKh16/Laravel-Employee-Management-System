<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisteredSocialServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registered_social_services', function (Blueprint $table) {
            $table->id();

            // Machine name used by Socialite: google, github, facebook, linkedin, twitter
            $table->string('provider', 50)->unique();

            // Human label for UI
            $table->string('label', 100);

            // OAuth credentials (stored in DB – managed by admin panel)
            $table->string('client_id');
            $table->string('client_secret');
            $table->string('redirect_uri');

            // Optional extra fields
            $table->json('scopes')->nullable();
            $table->json('extra')->nullable(); // e.g. tenant, domain hints

            // Control
            $table->boolean('is_enabled')->default(true);
            $table->boolean('is_default')->default(false);   // show first / mark recommended
            $table->unsignedSmallInteger('sort_order')->default(0);

            // Branding for button
            $table->string('icon', 100)->nullable();         // e.g. 'google', 'github'
            $table->string('brand_color', 20)->nullable();   // e.g. '#4285F4'

            // Audit
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_enabled', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registered_social_services');
    }
}
