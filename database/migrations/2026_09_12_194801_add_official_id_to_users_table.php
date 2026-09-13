<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'official_id')) {
                $table->string('official_id')
                    ->nullable()
                    ->unique()
                    ->after('email');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'official_id')) {
                $table->dropUnique(['official_id']);
                $table->dropColumn('official_id');
            }
        });
    }
};