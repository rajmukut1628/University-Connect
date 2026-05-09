<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Job;
use App\Models\Mentorship;
use App\Models\Message;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_jobs' => $this->getTotalJobs(),
            'total_events' => $this->getTotalEvents(),
            'mentorship_requests' => $this->getMentorshipRequests($user->id),
            'unread_messages' => $this->getUnreadMessages($user->id),
            'unread_notifications' => $this->getUnreadNotifications($user->id),
        ];

        $recommendedJobs = $this->getRecommendedJobs();
        $recommendedEvents = $this->getRecommendedEvents();

        $profileScore = $this->calculateProfileScore($user);
        $aiSuggestions = $this->generateAiSuggestions($user, $profileScore);

        return view('student.dashboard', compact(
            'user',
            'stats',
            'recommendedJobs',
            'recommendedEvents',
            'profileScore',
            'aiSuggestions'
        ));
    }

    public function aiStudyAssistant(Request $request)
    {
        $request->validate([
            'question' => ['required', 'string', 'min:3', 'max:500'],
        ]);

        return back()->with([
            'ai_question' => $request->question,
            'ai_answer' => $this->generateStudyAnswer(strtolower($request->question)),
        ]);
    }

    private function getTotalJobs(): int
    {
        if (!Schema::hasTable('jobs')) {
            return 0;
        }

        if (Schema::hasColumn('jobs', 'status')) {
            return Job::where('status', 'approved')->count();
        }

        return Job::count();
    }

    private function getRecommendedJobs()
    {
        if (!Schema::hasTable('jobs')) {
            return collect();
        }

        if (Schema::hasColumn('jobs', 'status')) {
            return Job::where('status', 'approved')->latest()->take(4)->get();
        }

        return Job::latest()->take(4)->get();
    }

    private function getTotalEvents(): int
    {
        if (!Schema::hasTable('events')) {
            return 0;
        }

        return Event::count();
    }

    private function getRecommendedEvents()
    {
        if (!Schema::hasTable('events')) {
            return collect();
        }

        return Event::latest()->take(4)->get();
    }

    private function getMentorshipRequests(int $userId): int
    {
        if (!Schema::hasTable('mentorships')) {
            return 0;
        }

        if (Schema::hasColumn('mentorships', 'student_id')) {
            return Mentorship::where('student_id', $userId)->count();
        }

        if (Schema::hasColumn('mentorships', 'user_id')) {
            return Mentorship::where('user_id', $userId)->count();
        }

        return Mentorship::count();
    }

    private function getUnreadMessages(int $userId): int
    {
        if (!Schema::hasTable('messages')) {
            return 0;
        }

        if (Schema::hasColumn('messages', 'recipient_id')) {
            return Message::where('recipient_id', $userId)
                ->when(Schema::hasColumn('messages', 'read_at'), fn ($q) => $q->whereNull('read_at'))
                ->count();
        }

        if (Schema::hasColumn('messages', 'to_user_id')) {
            return Message::where('to_user_id', $userId)
                ->when(Schema::hasColumn('messages', 'read_at'), fn ($q) => $q->whereNull('read_at'))
                ->count();
        }

        return 0;
    }

    private function getUnreadNotifications(int $userId): int
    {
        if (!Schema::hasTable('notifications')) {
            return 0;
        }

        if (Schema::hasColumn('notifications', 'user_id')) {
            return Notification::where('user_id', $userId)
                ->when(Schema::hasColumn('notifications', 'read_at'), fn ($q) => $q->whereNull('read_at'))
                ->count();
        }

        return Notification::count();
    }

    private function calculateProfileScore($user): int
    {
        $fields = ['name', 'email', 'phone', 'department', 'batch', 'skills', 'bio', 'address'];
        $completed = 0;

        foreach ($fields as $field) {
            if (!empty($user->$field)) {
                $completed++;
            }
        }

        return (int) round(($completed / count($fields)) * 100);
    }

    private function generateAiSuggestions($user, int $profileScore): array
    {
        $suggestions = [];

        if ($profileScore < 50) {
            $suggestions[] = 'Complete your profile to get better job and mentorship recommendations.';
        }

        if (empty($user->skills)) {
            $suggestions[] = 'Add your skills like Laravel, React, PHP, JavaScript, Python, AI Tools.';
        }

        if (empty($user->bio)) {
            $suggestions[] = 'Write a short bio about your career goal and academic interests.';
        }

        $suggestions[] = 'Join events related to your department to improve networking.';
        $suggestions[] = 'Apply to internships that match your current skills.';
        $suggestions[] = 'Request mentorship from alumni to get career guidance.';

        return $suggestions;
    }

    private function generateStudyAnswer(string $question): string
    {
        if (str_contains($question, 'career') || str_contains($question, 'job')) {
            return 'Focus on building a strong portfolio, improving communication skills, learning GitHub, and applying for internships regularly.';
        }

        if (str_contains($question, 'cv') || str_contains($question, 'resume')) {
            return 'Your CV should include personal information, education, skills, projects, achievements, and contact details. Keep it clean and professional.';
        }

        if (str_contains($question, 'programming') || str_contains($question, 'coding')) {
            return 'Practice coding daily. Start with problem solving, then build small projects using HTML, CSS, JavaScript, PHP, Laravel, or Python.';
        }

        if (str_contains($question, 'exam') || str_contains($question, 'study')) {
            return 'Create a daily study routine, revise class notes, practice previous questions, and take short breaks to improve focus.';
        }

        if (str_contains($question, 'internship')) {
            return 'Prepare a strong CV, update your LinkedIn/GitHub profile, learn basic interview questions, and apply to internship posts regularly.';
        }

        return 'This AI Assistant suggests: focus on your skills, complete your profile, join events, connect with alumni mentors, and apply for suitable jobs or internships.';
    }
}