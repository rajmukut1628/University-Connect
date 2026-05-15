<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Mentorship;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use App\Services\AISuggestionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Services\ProfileStrengthService;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $jobOwnerColumn = $this->getJobOwnerColumn();

        $stats = [
            'my_jobs' => $jobOwnerColumn
                ? DB::table('jobs')->where($jobOwnerColumn, $user->id)->count()
                : 0,

            'approved_jobs' => $jobOwnerColumn && Schema::hasColumn('jobs', 'status')
                ? DB::table('jobs')
                    ->where($jobOwnerColumn, $user->id)
                    ->whereIn('status', ['approved', 'active', 'published'])
                    ->count()
                : 0,

            'pending_jobs' => $jobOwnerColumn && Schema::hasColumn('jobs', 'status')
                ? DB::table('jobs')
                    ->where($jobOwnerColumn, $user->id)
                    ->where('status', 'pending')
                    ->count()
                : 0,

            'mentorship_requests' => Schema::hasTable('mentorships') && Schema::hasColumn('mentorships', 'mentor_id')
                ? Mentorship::where('mentor_id', $user->id)->count()
                : 0,

            'unread_messages' => $this->getUnreadMessages($user->id),

            'unread_notifications' => $this->getUnreadNotifications($user->id),
        ];

        $myJobs = $jobOwnerColumn
            ? DB::table('jobs')
                ->where($jobOwnerColumn, $user->id)
                ->orderByDesc(Schema::hasColumn('jobs', 'created_at') ? 'created_at' : 'id')
                ->take(5)
                ->get()
            : collect();

        $mentorshipRequests = Schema::hasTable('mentorships') && Schema::hasColumn('mentorships', 'mentor_id')
            ? Mentorship::where('mentor_id', $user->id)
                ->latest()
                ->take(5)
                ->get()
            : collect();

        $recommendedStudents = Schema::hasTable('users')
            ? User::where('role', 'student')
                ->latest()
                ->take(6)
                ->get()
            : collect();

        $contributionScore = $this->calculateContributionScore($stats);

        // Real Dynamic AI Suggestions
        $aiSuggestions = app(AISuggestionService::class)->latestFor($user, 6);

        $profileStrength = app(ProfileStrengthService::class)->analyze($user);
        $profileScore = $profileStrength['score'];

        return view('alumni.dashboard', compact(
            'user',
            'stats',
            'myJobs',
            'mentorshipRequests',
            'recommendedStudents',
            'contributionScore',
            'aiSuggestions',
            'profileStrength',
            'profileScore'
        ));
    }

    private function getJobOwnerColumn(): ?string
    {
        if (!Schema::hasTable('jobs')) {
            return null;
        }

        foreach (['user_id', 'alumni_id', 'created_by', 'posted_by'] as $column) {
            if (Schema::hasColumn('jobs', $column)) {
                return $column;
            }
        }

        return null;
    }

    private function getUnreadMessages(int $userId): int
    {
        if (!Schema::hasTable('messages')) {
            return 0;
        }

        if (Schema::hasColumn('messages', 'recipient_id')) {
            return Message::where('recipient_id', $userId)
                ->when(
                    Schema::hasColumn('messages', 'read_at'),
                    fn ($q) => $q->whereNull('read_at')
                )
                ->count();
        }

        if (Schema::hasColumn('messages', 'to_user_id')) {
            return Message::where('to_user_id', $userId)
                ->when(
                    Schema::hasColumn('messages', 'read_at'),
                    fn ($q) => $q->whereNull('read_at')
                )
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
                ->when(
                    Schema::hasColumn('notifications', 'read_at'),
                    fn ($q) => $q->whereNull('read_at')
                )
                ->count();
        }

        return 0;
    }

    private function calculateContributionScore(array $stats): int
    {
        $score = 20;

        $score += min(($stats['my_jobs'] ?? 0) * 10, 30);
        $score += min(($stats['approved_jobs'] ?? 0) * 10, 25);
        $score += min(($stats['mentorship_requests'] ?? 0) * 5, 15);
        $score += min(($stats['unread_messages'] ?? 0) * 2, 10);

        return min($score, 100);
    }
}