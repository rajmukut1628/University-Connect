<?php

namespace Database\Seeders;

use App\Models\OfficialAlumni;
use App\Models\OfficialStudent;
use Illuminate\Database\Seeder;

class OfficialMemberSeeder extends Seeder
{
    public function run(): void
    {
        OfficialStudent::updateOrCreate(
            ['email' => 'student@university.com'],
            [
                'student_id' => 'STU-1001',
                'name' => 'Demo Student',
                'department' => 'Computer Science',
                'batch' => '2026',
            ]
        );

        OfficialAlumni::updateOrCreate(
            ['email' => 'alumni@university.com'],
            [
                'alumni_id' => 'ALU-2001',
                'name' => 'Demo Alumni',
                'department' => 'Computer Science',
                'batch' => '2020',
                'company' => 'Tech Corp',
                'designation' => 'Software Engineer',
            ]
        );
    }
}