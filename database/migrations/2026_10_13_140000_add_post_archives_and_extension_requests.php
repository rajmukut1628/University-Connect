<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        foreach (['job_postings', 'events', 'donations'] as $name) {
            if (!Schema::hasColumn($name, 'archived_at')) {
                Schema::table($name, fn (Blueprint $table) => $table->timestamp('archived_at')->nullable()->index());
            }
        }
        if (!Schema::hasTable('post_extension_requests')) Schema::create('post_extension_requests', function (Blueprint $table) {
            $table->id();
            $table->string('post_type', 20);
            $table->unsignedBigInteger('post_id');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('dates');
            $table->text('reason');
            $table->string('status', 20)->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('review_note')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
            $table->index(['post_type', 'post_id', 'status']);
        });
        if (!Schema::hasTable('donation_card_payments')) Schema::create('donation_card_payments', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->unique();
            $table->foreignId('donation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('donation_card_payments');
        Schema::dropIfExists('post_extension_requests');
        foreach (['job_postings', 'events', 'donations'] as $name) {
            Schema::table($name, fn (Blueprint $table) => $table->dropColumn('archived_at'));
        }
    }
};
