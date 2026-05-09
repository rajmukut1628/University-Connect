<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@university.com'],
            [
                'name' => 'Administrative Admin',
                'password' => bcrypt('12345678'),
                'role' => 'admin',
                'email_verified' => true,
                'is_active' => true,
                'is_blocked' => false,
            ]
        );
    }
}