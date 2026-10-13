<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            // ── Identity ─────────────────────────────────────
            // Who should see this notification
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained('users')
                  ->cascadeOnDelete();

            // For system-wide / broadcast notifications (user_id = null)
            // type is one of: 'user' (targeted), 'role' (role-based), 'system' (everyone)
            $table->enum('audience', ['user', 'role', 'system', 'branch'])
                  ->default('user');

            // When audience = 'role', which role
            $table->string('audience_value')->nullable(); // role name, branch id, etc.

            // ── Content ──────────────────────────────────────
            $table->string('type', 100);            // e.g. 'employee.created', 'payroll.processed'
            $table->string('category', 50)          // UI grouping / color coding
                  ->default('general');             // general | success | warning | error | info | action

            $table->string('title', 255);
            $table->text('message')->nullable();

            // Optional icon slug (e.g. 'bell', 'user-plus', 'check-circle')
            $table->string('icon', 50)->nullable();

            // Action link (where to go when clicked)
            $table->string('action_url', 500)->nullable();
            $table->string('action_label', 100)->nullable();

            // ── Related entity (polymorphic-lite) ───────────
            $table->string('related_type', 100)->nullable(); // e.g. 'App\Models\Employee'
            $table->unsignedBigInteger('related_id')->nullable();

            // ── State tracking ───────────────────────────────
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            $table->boolean('is_dismissed')->default(false);
            $table->timestamp('dismissed_at')->nullable();

            // Soft-delete for audit trail (never hard delete)
            $table->softDeletes();

            // ── Priority & grouping ──────────────────────────
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])
                  ->default('normal');

            // Group similar notifications (e.g. "5 new employees added")
            $table->string('group_key', 100)->nullable();
            $table->unsignedInteger('group_count')->default(1);

            // ── Extra payload (JSON for flexibility) ─────────
            $table->json('data')->nullable();

            // ── Expiry (auto-hide after N days) ──────────────
            $table->timestamp('expires_at')->nullable();

            $table->timestamps();

            // ── Indexes for fast queries ─────────────────────
            $table->index(['user_id', 'is_read', 'created_at'], 'idx_user_unread');
            $table->index(['user_id', 'is_dismissed', 'created_at'], 'idx_user_active');
            $table->index(['audience', 'audience_value'], 'idx_audience');
            $table->index(['type', 'created_at'], 'idx_type');
            $table->index(['group_key'], 'idx_group');
            $table->index(['expires_at'], 'idx_expiry');
            $table->index(['related_type', 'related_id'], 'idx_related');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
