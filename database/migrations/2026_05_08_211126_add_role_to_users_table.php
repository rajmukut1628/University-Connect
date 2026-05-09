<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'student', 'alumni'])->default('student')->after('email');
            $table->string('phone')->nullable()->after('role');
            $table->text('bio')->nullable()->after('phone');
            $table->string('profile_image')->nullable()->after('bio');
            $table->string('cover_image')->nullable()->after('profile_image');
            $table->boolean('email_verified')->default(false)->after('cover_image');
            $table->boolean('is_active')->default(true)->after('email_verified');
            $table->boolean('is_blocked')->default(false)->after('is_active');
            $table->text('blocked_reason')->nullable()->after('is_blocked');
            $table->unsignedBigInteger('alumni_id')->nullable()->after('blocked_reason');
            $table->unsignedBigInteger('student_id')->nullable()->after('alumni_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'phone',
                'bio',
                'profile_image',
                'cover_image',
                'email_verified',
                'is_active',
                'is_blocked',
                'blocked_reason',
                'alumni_id',
                'student_id',
            ]);
        });
    }
};
