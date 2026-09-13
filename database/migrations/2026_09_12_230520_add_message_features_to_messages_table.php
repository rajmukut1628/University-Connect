<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('messages', 'attachment_name')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->string('attachment_name')
                    ->nullable()
                    ->after('attachment');
            });
        }

        if (!Schema::hasColumn('messages', 'attachment_type')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->string('attachment_type')
                    ->nullable()
                    ->after('attachment_name');
            });
        }

        if (!Schema::hasColumn('messages', 'is_edited')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->boolean('is_edited')
                    ->default(false)
                    ->after('read_at');
            });
        }

        if (!Schema::hasColumn('messages', 'deleted_by_sender')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->boolean('deleted_by_sender')
                    ->default(false)
                    ->after('is_edited');
            });
        }

        if (!Schema::hasColumn('messages', 'deleted_by_receiver')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->boolean('deleted_by_receiver')
                    ->default(false)
                    ->after('deleted_by_sender');
            });
        }
    }

    public function down(): void
    {
        $columns = [
            'attachment_name',
            'attachment_type',
            'is_edited',
            'deleted_by_sender',
            'deleted_by_receiver',
        ];

        foreach ($columns as $column) {
            if (Schema::hasColumn('messages', $column)) {
                Schema::table('messages', function (Blueprint $table) use ($column) {
                    $table->dropColumn($column);
                });
            }
        }
    }
};