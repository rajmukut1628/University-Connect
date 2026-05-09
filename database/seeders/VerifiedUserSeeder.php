<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\VerifiedUser;

class VerifiedUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'student_id' => '221234567',
                'alumni_id' => null,
                'name' => 'Raj Mukut',
                'email' => 'raj@example.com',
                'department' => 'CSE',
                'batch' => '56th',
                'role' => 'student',
                'status' => 'active',
            ],

            [
                'student_id' => '201812345',
                'alumni_id' => 'ALM-001',
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'department' => 'CSE',
                'batch' => '2018',
                'role' => 'alumni',
                'status' => 'active',
            ],

            // 👉 আপনার ready student/alumni data এখানেই add করবেন
        ];

        foreach ($users as $user) {
            VerifiedUser::updateOrCreate(
                ['email' => $user['email']],
                $user
            );
        }
    }
}