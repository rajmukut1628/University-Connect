<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donation_contributions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('donation_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->decimal('amount', 12, 2);
            $table->string('donor_name')->nullable();
            $table->string('donor_email')->nullable();
            $table->text('message')->nullable();

            $table->boolean('is_anonymous')->default(false);

            $table->enum('payment_method', [
                'cash',
                'bkash',
                'nagad',
                'rocket',
                'bank',
                'other'
            ])->default('cash');

            $table->enum('status', [
                'pending',
                'confirmed',
                'cancelled'
            ])->default('confirmed');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donation_contributions');
    }
};