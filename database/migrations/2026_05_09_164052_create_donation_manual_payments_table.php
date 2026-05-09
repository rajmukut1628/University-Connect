<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationManualPaymentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('donation_manual_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('payment_method')->nullable();
            $table->string('account_number')->nullable();
            $table->string('transaction_id')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('screenshot')->nullable();
            $table->text('note')->nullable();

            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donation_manual_payments');
    }
}