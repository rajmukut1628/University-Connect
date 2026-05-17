<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'current_company')) {
                $table->string('current_company')->nullable()->after('portfolio_url');
            }

            if (!Schema::hasColumn('users', 'current_designation')) {
                $table->string('current_designation')->nullable()->after('current_company');
            }

            if (!Schema::hasColumn('users', 'current_job_type')) {
                $table->string('current_job_type')->nullable()->after('current_designation');
            }

            if (!Schema::hasColumn('users', 'work_experience_years')) {
                $table->string('work_experience_years')->nullable()->after('current_job_type');
            }

            if (!Schema::hasColumn('users', 'previous_company')) {
                $table->string('previous_company')->nullable()->after('work_experience_years');
            }

            if (!Schema::hasColumn('users', 'previous_designation')) {
                $table->string('previous_designation')->nullable()->after('previous_company');
            }

            if (!Schema::hasColumn('users', 'previous_job_details')) {
                $table->text('previous_job_details')->nullable()->after('previous_designation');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach ([
                'previous_job_details',
                'previous_designation',
                'previous_company',
                'work_experience_years',
                'current_job_type',
                'current_designation',
                'current_company',
            ] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};