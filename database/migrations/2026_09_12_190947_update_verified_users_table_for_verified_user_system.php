<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('verified_users', function (Blueprint $table) {

            if (!Schema::hasColumn('verified_users', 'phone')) {
                $table->string('phone', 30)->nullable()->after('email');
            }

            if (!Schema::hasColumn('verified_users', 'unique_id')) {
                $table->string('unique_id', 100)->nullable()->after('phone');
            }

            if (!Schema::hasColumn('verified_users', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }

            if (!Schema::hasColumn('verified_users', 'created_by')) {
                $table->unsignedBigInteger('created_by')
                    ->nullable()
                    ->after('notes');

                $table->foreign('created_by')
                    ->references('id')
                    ->on('users')
                    ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('verified_users', function (Blueprint $table) {

            if (Schema::hasColumn('verified_users', 'created_by')) {
                $table->dropForeign(['created_by']);
                $table->dropColumn('created_by');
            }

            if (Schema::hasColumn('verified_users', 'notes')) {
                $table->dropColumn('notes');
            }

            if (Schema::hasColumn('verified_users', 'unique_id')) {
                $table->dropColumn('unique_id');
            }

            if (Schema::hasColumn('verified_users', 'phone')) {
                $table->dropColumn('phone');
            }
        });
    }
};