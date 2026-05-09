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
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_id')->constrained('job_postings')->onDelete('cascade');
            $table->foreignId('applicant_id')->constrained('users')->onDelete('cascade');
            $table->text('cover_letter');
            $table->string('resume_path');
            $table->enum('status', ['applied', 'reviewed', 'shortlisted', 'rejected', 'hired'])->default('applied');
            $table->text('feedback')->nullable();
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamps();
            $table->unique(['job_id', 'applicant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
