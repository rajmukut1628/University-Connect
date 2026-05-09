<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feed_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('feedable_type');
            $table->unsignedBigInteger('feedable_id');
            $table->timestamps();

            $table->unique(['user_id', 'feedable_type', 'feedable_id'], 'unique_user_feed_like');
            $table->index(['feedable_type', 'feedable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feed_likes');
    }
};