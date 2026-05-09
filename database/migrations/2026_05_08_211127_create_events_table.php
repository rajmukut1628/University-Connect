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
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            // Basic Event Information
            $table->string('title');
            $table->longText('description');

            // Date & Time
            $table->dateTime('event_date');

            // Location
            $table->string('location')->nullable();

            // Creator (Admin or Alumni)
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            // Capacity
            $table->unsignedInteger('capacity')->nullable();

            // Optional Event Image
            $table->string('event_image')->nullable();

            // Event Type
            $table->string('type')->default('workshop');
            // Examples: workshop, seminar, networking, webinar, showcase

            // Status
            $table->string('status')->default('active');
            // Examples: active, published, draft, cancelled, completed

            // Additional Requirements
            $table->text('requirements')->nullable();

            // Premium Analytics Fields
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('registration_count')->default(0);

            // SEO / Featured Flags
            $table->boolean('is_featured')->default(false);

            // Timestamps
            $table->timestamps();

            // Indexes for performance
            $table->index('event_date');
            $table->index('status');
            $table->index('type');
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};