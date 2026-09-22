<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donation_manual_payments', function (Blueprint $table) {

            if (!Schema::hasColumn('donation_manual_payments', 'status')) {
                $table->string('status', 30)
                    ->default('pending')
                    ->after('screenshot');
            }

            if (!Schema::hasColumn('donation_manual_payments', 'reviewed_by')) {
                $table->foreignId('reviewed_by')
                    ->nullable()
                    ->after('status')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('donation_manual_payments', 'reviewed_at')) {
                $table->timestamp('reviewed_at')
                    ->nullable()
                    ->after('reviewed_by');
            }

            if (!Schema::hasColumn('donation_manual_payments', 'rejection_reason')) {
                $table->text('rejection_reason')
                    ->nullable()
                    ->after('reviewed_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('donation_manual_payments', function (Blueprint $table) {

            if (Schema::hasColumn('donation_manual_payments', 'reviewed_by')) {
                $table->dropForeign(['reviewed_by']);
            }

            $columns = [];

            foreach ([
                'reviewed_by',
                'reviewed_at',
                'rejection_reason',
            ] as $column) {
                if (Schema::hasColumn('donation_manual_payments', $column)) {
                    $columns[] = $column;
                }
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};