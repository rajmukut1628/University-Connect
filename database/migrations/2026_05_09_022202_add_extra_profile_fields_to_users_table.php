<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'department')) {
                $table->string('department')->nullable()->after('phone');
            }

            if (!Schema::hasColumn('users', 'batch')) {
                $table->string('batch')->nullable()->after('department');
            }

            if (!Schema::hasColumn('users', 'skills')) {
                $table->text('skills')->nullable()->after('batch');
            }

            if (!Schema::hasColumn('users', 'address')) {
                $table->string('address')->nullable()->after('bio');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'address')) {
                $table->dropColumn('address');
            }

            if (Schema::hasColumn('users', 'skills')) {
                $table->dropColumn('skills');
            }

            if (Schema::hasColumn('users', 'batch')) {
                $table->dropColumn('batch');
            }

            if (Schema::hasColumn('users', 'department')) {
                $table->dropColumn('department');
            }
        });
    }
};