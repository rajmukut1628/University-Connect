<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            return;
        }

        $events = [
            [
                'title' => 'AI Career Bootcamp',
                'description' => 'A premium workshop focused on AI tools, career preparation, portfolio building and future job readiness.',
                'event_date' => now()->addDays(7)->setTime(14, 0),
                'location' => 'Auditorium Hall',
                'created_by' => $admin->id,
                'capacity' => 120,
                'type' => 'workshop',
                'status' => 'published',
                'requirements' => 'Bring laptop and student ID.',
            ],
            [
                'title' => 'Alumni Networking Night',
                'description' => 'Meet successful alumni, build professional connections and explore mentorship opportunities.',
                'event_date' => now()->addDays(14)->setTime(18, 0),
                'location' => 'Main Campus',
                'created_by' => $admin->id,
                'capacity' => 200,
                'type' => 'networking',
                'status' => 'published',
                'requirements' => 'Open for verified students and alumni.',
            ],
            [
                'title' => 'Laravel Project Showcase',
                'description' => 'Students will present Laravel projects and receive feedback from alumni software engineers.',
                'event_date' => now()->addDays(20)->setTime(11, 0),
                'location' => 'CSE Lab 301',
                'created_by' => $admin->id,
                'capacity' => 80,
                'type' => 'showcase',
                'status' => 'published',
                'requirements' => 'Project demo required for presenters.',
            ],
        ];

        foreach ($events as $event) {
            Event::updateOrCreate(
                ['title' => $event['title']],
                $event
            );
        }
    }
}