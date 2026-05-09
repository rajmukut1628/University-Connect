<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resume_analyses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('resume_title')->nullable();
            $table->string('original_file_name')->nullable();
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();

            $table->unsignedTinyInteger('score')->default(0);

            $table->json('detected_skills')->nullable();
            $table->json('missing_skills')->nullable();
            $table->json('suggestions')->nullable();
            $table->json('recommended_roles')->nullable();

            $table->text('summary')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resume_analyses');
    }
};