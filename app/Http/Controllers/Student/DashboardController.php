<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\JobPosting;
use App\Models\Mentorship;
use App\Models\Message;
use App\Services\AISuggestionService;
use App\Services\ProfileStrengthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Student Dashboard
     */
    public function index()
    {
        $user = auth()->user();

        abort_unless(
            $user && $user->role === 'student',
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Dashboard Statistics
        |--------------------------------------------------------------------------
        */

        $stats = [
            'total_jobs' => $this->getTotalJobs(),
            'total_events' => $this->getTotalEvents(),
            'mentorship_requests' => $this->getMentorshipRequests($user->id),
            'unread_messages' => $this->getUnreadMessages($user->id),
        ];

        /*
        |--------------------------------------------------------------------------
        | Recommended Content
        |--------------------------------------------------------------------------
        */

        $recommendedJobs = $this->getRecommendedJobs();

        $recommendedEvents = $this->getRecommendedEvents();

        /*
        |--------------------------------------------------------------------------
        | Profile Strength
        |--------------------------------------------------------------------------
        */

        $profileStrength = app(ProfileStrengthService::class)
            ->analyze($user);

        $profileScore = (int) ($profileStrength['score'] ?? 0);

        /*
        |--------------------------------------------------------------------------
        | AI Suggestions
        |--------------------------------------------------------------------------
        */

        $aiSuggestions = app(AISuggestionService::class)
            ->latestFor($user, 3);

        return view('student.dashboard', compact(
            'user',
            'stats',
            'recommendedJobs',
            'recommendedEvents',
            'profileStrength',
            'profileScore',
            'aiSuggestions'
        ));
    }


    /**
     * Simple Student AI Assistant
     */
    public function aiStudyAssistant(Request $request)
    {
        $validated = $request->validate([
            'question' => [
                'required',
                'string',
                'min:3',
                'max:500',
            ],
        ]);

        return back()->with([
            'ai_question' => $validated['question'],
            'ai_answer' => $this->generateStudyAnswer(
                strtolower($validated['question'])
            ),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | Approved Jobs
    |--------------------------------------------------------------------------
    */

    private function getTotalJobs(): int
    {
        if (!Schema::hasTable('job_postings')) {
            return 0;
        }

        $query = JobPosting::query();

        if (Schema::hasColumn('job_postings', 'status')) {
            $query->where('status', 'approved');
        }

        return $query->count();
    }


    private function getRecommendedJobs()
    {
        if (!Schema::hasTable('job_postings')) {
            return collect();
        }

        $query = JobPosting::query();

        if (Schema::hasColumn('job_postings', 'status')) {
            $query->where('status', 'approved');
        }

        /*
        |--------------------------------------------------------------------------
        | Do not show expired jobs
        |--------------------------------------------------------------------------
        */

        if (Schema::hasColumn('job_postings', 'deadline')) {
            $query->where(function ($q) {
                $q->whereNull('deadline')
                    ->orWhereDate('deadline', '>=', now()->toDateString());
            });
        }

        return $query
            ->latest()
            ->take(3)
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    private function getTotalEvents(): int
    {
        if (!Schema::hasTable('events')) {
            return 0;
        }

        $query = Event::query();

        if (Schema::hasColumn('events', 'status')) {
            $query->whereIn(
                'status',
                ['approved', 'active', 'published']
            );
        }

        return $query->count();
    }


    private function getRecommendedEvents()
    {
        if (!Schema::hasTable('events')) {
            return collect();
        }

        $query = Event::query();

        if (Schema::hasColumn('events', 'status')) {
            $query->whereIn(
                'status',
                ['approved', 'active', 'published']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Prefer upcoming events when event_date exists
        |--------------------------------------------------------------------------
        */

        if (Schema::hasColumn('events', 'event_date')) {
            $query->whereDate(
                'event_date',
                '>=',
                now()->toDateString()
            );

            $query->orderBy('event_date');
        } elseif (Schema::hasColumn('events', 'start_date')) {
            $query->whereDate(
                'start_date',
                '>=',
                now()->toDateString()
            );

            $query->orderBy('start_date');
        } else {
            $query->latest();
        }

        return $query
            ->take(3)
            ->get();
    }


    /*
    |--------------------------------------------------------------------------
    | Mentorship
    |--------------------------------------------------------------------------
    */

    private function getMentorshipRequests(int $userId): int
    {
        if (!Schema::hasTable('mentorships')) {
            return 0;
        }

        if (Schema::hasColumn('mentorships', 'student_id')) {
            return Mentorship::where(
                'student_id',
                $userId
            )->count();
        }

        if (Schema::hasColumn('mentorships', 'user_id')) {
            return Mentorship::where(
                'user_id',
                $userId
            )->count();
        }

        return 0;
    }


    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */

    private function getUnreadMessages(int $userId): int
    {
        if (!Schema::hasTable('messages')) {
            return 0;
        }

        /*
        |--------------------------------------------------------------------------
        | Current message schema
        |--------------------------------------------------------------------------
        */

        if (Schema::hasColumn('messages', 'recipient_id')) {
            $query = Message::where(
                'recipient_id',
                $userId
            );

            if (Schema::hasColumn('messages', 'read_at')) {
                $query->whereNull('read_at');
            } elseif (Schema::hasColumn('messages', 'is_read')) {
                $query->where('is_read', false);
            }

            return $query->count();
        }

        /*
        |--------------------------------------------------------------------------
        | Compatibility with older schema
        |--------------------------------------------------------------------------
        */

        if (Schema::hasColumn('messages', 'to_user_id')) {
            $query = Message::where(
                'to_user_id',
                $userId
            );

            if (Schema::hasColumn('messages', 'read_at')) {
                $query->whereNull('read_at');
            } elseif (Schema::hasColumn('messages', 'is_read')) {
                $query->where('is_read', false);
            }

            return $query->count();
        }

        return 0;
    }


    /*
    |--------------------------------------------------------------------------
    | Simple AI Assistant Response
    |--------------------------------------------------------------------------
    */

    private function generateStudyAnswer(string $question): string
    {
        if (
            str_contains($question, 'career') ||
            str_contains($question, 'job')
        ) {
            return 'Focus on building a strong portfolio, improving communication skills, developing practical skills, maintaining your GitHub and LinkedIn profiles, and regularly exploring suitable job and internship opportunities.';
        }

        if (
            str_contains($question, 'cv') ||
            str_contains($question, 'resume')
        ) {
            return 'Your CV should clearly present your education, technical skills, projects, achievements, experience, and contact information. Keep the layout clean, concise, and relevant to the opportunity you are applying for.';
        }

        if (
            str_contains($question, 'programming') ||
            str_contains($question, 'coding')
        ) {
            return 'Practice programming consistently. Strengthen problem-solving fundamentals and then build practical projects using technologies related to your career goals.';
        }

        if (
            str_contains($question, 'exam') ||
            str_contains($question, 'study')
        ) {
            return 'Create a realistic study routine, divide large topics into smaller tasks, revise your notes regularly, practice previous questions, and take short breaks to maintain concentration.';
        }

        if (str_contains($question, 'internship')) {
            return 'Prepare a focused CV, complete your professional profile, maintain your GitHub or portfolio, practice interview questions, and regularly check suitable internship opportunities.';
        }

        if (
            str_contains($question, 'mentor') ||
            str_contains($question, 'mentorship')
        ) {
            return 'Choose a mentor whose professional background matches your career interests. Before sending a mentorship request, clearly identify what guidance you need and keep your profile information updated.';
        }

        if (
            str_contains($question, 'skill') ||
            str_contains($question, 'skills')
        ) {
            return 'Focus on a combination of technical skills, communication, problem solving, teamwork, and practical project experience. Prioritize skills that match the career path you want to pursue.';
        }

        return 'Focus on completing your profile, developing practical skills, exploring approved opportunities, joining useful events, and connecting with suitable alumni mentors for career guidance.';
    }
}