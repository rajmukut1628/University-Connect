<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'start_date')) {
                $table->date('start_date')->nullable()->after('location');
            }

            if (!Schema::hasColumn('events', 'start_time')) {
                $table->time('start_time')->nullable()->after('start_date');
            }

            if (!Schema::hasColumn('events', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_time');
            }

            if (!Schema::hasColumn('events', 'end_time')) {
                $table->time('end_time')->nullable()->after('end_date');
            }

            if (!Schema::hasColumn('events', 'cover_image')) {
                $table->string('cover_image')->nullable()->after('capacity');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'start_date')) {
                $table->dropColumn('start_date');
            }

            if (Schema::hasColumn('events', 'start_time')) {
                $table->dropColumn('start_time');
            }

            if (Schema::hasColumn('events', 'end_date')) {
                $table->dropColumn('end_date');
            }

            if (Schema::hasColumn('events', 'end_time')) {
                $table->dropColumn('end_time');
            }

            if (Schema::hasColumn('events', 'cover_image')) {
                $table->dropColumn('cover_image');
            }
        });
    }
};