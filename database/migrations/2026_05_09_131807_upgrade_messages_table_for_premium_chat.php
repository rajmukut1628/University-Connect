<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            if (!Schema::hasColumn('messages', 'attachment')) {
                $table->string('attachment')->nullable()->after('body');
            }

            if (!Schema::hasColumn('messages', 'attachment_name')) {
                $table->string('attachment_name')->nullable()->after('attachment');
            }

            if (!Schema::hasColumn('messages', 'attachment_type')) {
                $table->string('attachment_type')->nullable()->after('attachment_name');
            }

            if (!Schema::hasColumn('messages', 'is_edited')) {
                $table->boolean('is_edited')->default(false)->after('read_at');
            }

            if (!Schema::hasColumn('messages', 'deleted_by_sender')) {
                $table->boolean('deleted_by_sender')->default(false)->after('is_edited');
            }

            if (!Schema::hasColumn('messages', 'deleted_by_receiver')) {
                $table->boolean('deleted_by_receiver')->default(false)->after('deleted_by_sender');
            }
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            foreach ([
                'attachment',
                'attachment_name',
                'attachment_type',
                'is_edited',
                'deleted_by_sender',
                'deleted_by_receiver',
            ] as $column) {
                if (Schema::hasColumn('messages', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};