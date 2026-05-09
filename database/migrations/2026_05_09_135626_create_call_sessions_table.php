<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('call_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caller_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('receiver_id')->constrained('users')->cascadeOnDelete();
            $table->string('type')->default('video'); // audio, video
            $table->string('status')->default('ringing'); // ringing, accepted, rejected, ended
            $table->text('offer')->nullable();
            $table->text('answer')->nullable();
            $table->json('caller_candidates')->nullable();
            $table->json('receiver_candidates')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('call_sessions');
    }
};