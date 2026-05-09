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
        Schema::create('official_alumni', function (Blueprint $table) {
    $table->id();
    $table->string('alumni_id')->unique();
    $table->string('name');
    $table->string('email')->unique();
    $table->string('department')->nullable();
    $table->string('batch')->nullable();
    $table->string('company')->nullable();
    $table->string('designation')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('official_alumni');
    }
};
