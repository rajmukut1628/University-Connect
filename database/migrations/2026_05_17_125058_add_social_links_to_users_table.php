<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'github_url')) {
                $table->string('github_url')->nullable()->after('profile_image');
            }

            if (!Schema::hasColumn('users', 'linkedin_url')) {
                $table->string('linkedin_url')->nullable()->after('github_url');
            }

            if (!Schema::hasColumn('users', 'portfolio_url')) {
                $table->string('portfolio_url')->nullable()->after('linkedin_url');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'portfolio_url')) {
                $table->dropColumn('portfolio_url');
            }

            if (Schema::hasColumn('users', 'linkedin_url')) {
                $table->dropColumn('linkedin_url');
            }

            if (Schema::hasColumn('users', 'github_url')) {
                $table->dropColumn('github_url');
            }
        });
    }
};