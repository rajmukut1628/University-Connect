<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // If the table already exists, skip this migration
        if (Schema::hasTable('notifications')) {
            return;
        }

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Notification type (general, job, event, message, verification, etc.)
            $table->string('type')->default('general');

            // Notification title
            $table->string('title');

            // Full notification message
            $table->text('message');

            // Polymorphic relation (optional)
            // Examples:
            // - Job model
            // - Event model
            // - Message model
            // - User model
            $table->nullableMorphs('notifiable');

            // Read status
            $table->boolean('is_read')->default(false);

            // When user read the notification
            $table->timestamp('read_at')->nullable();

            // Priority: low, normal, high, urgent
            $table->string('priority')->default('normal');

            // Laravel timestamps
            $table->timestamps();

            // Indexes for faster searching
            $table->index(['user_id', 'is_read']);
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};