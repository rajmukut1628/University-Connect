<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('title');
            $table->string('category')->nullable();
            $table->decimal('target_amount', 12, 2)->default(0);
            $table->decimal('collected_amount', 12, 2)->default(0);

            $table->text('description')->nullable();
            $table->string('image')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->date('deadline')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};