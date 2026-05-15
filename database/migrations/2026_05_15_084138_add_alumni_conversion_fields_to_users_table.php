<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'alumni_id')) {
                $table->string('alumni_id')->nullable()->unique()->after('student_id');
            }

            if (!Schema::hasColumn('users', 'alumni_since')) {
                $table->timestamp('alumni_since')->nullable()->after('alumni_id');
            }

            if (!Schema::hasColumn('users', 'converted_from_student_at')) {
                $table->timestamp('converted_from_student_at')->nullable()->after('alumni_since');
            }

            if (!Schema::hasColumn('users', 'converted_by')) {
                $table->foreignId('converted_by')->nullable()->after('converted_from_student_at')->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['alumni_id', 'alumni_since', 'converted_from_student_at', 'converted_by']);
        });
    }
};