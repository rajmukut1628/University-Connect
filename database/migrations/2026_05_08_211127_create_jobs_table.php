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
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('company_name');
            $table->string('location');
            $table->enum('type', ['full-time', 'part-time', 'internship', 'contract', 'temporary'])->default('full-time');
            $table->enum('experience_level', ['entry', 'mid', 'senior'])->default('entry');
            $table->string('salary_range')->nullable();
            $table->foreignId('posted_by')->constrained('users')->onDelete('cascade');
            $table->text('requirements');
            $table->text('benefits')->nullable();
            $table->integer('positions_available')->default(1);
            $table->enum('status', ['open', 'closed', 'filled'])->default('open');
            $table->timestamp('deadline')->nullable();
            $table->string('job_image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
    }
};
