<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verified_users', function (Blueprint $table) {
            $table->id();

            $table->string('student_id')->nullable()->unique();
            $table->string('alumni_id')->nullable()->unique();

            $table->string('name');
            $table->string('email')->unique();

            $table->string('department')->nullable();
            $table->string('batch')->nullable();

            $table->enum('role', ['student', 'alumni'])->default('student');
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verified_users');
    }
};