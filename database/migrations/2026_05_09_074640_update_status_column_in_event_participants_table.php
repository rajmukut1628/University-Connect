<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE event_participants MODIFY status ENUM('pending','approved','rejected') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE event_participants MODIFY status ENUM('registered','cancelled') DEFAULT 'registered'");
    }
};