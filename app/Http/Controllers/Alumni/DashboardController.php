<?php

namespace App\Http\Controllers\Alumni;

use App\Http\Controllers\Controller;
use App\Models\Mentorship;
use App\Models\Message;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

            'approved_jobs' => $jobOwnerColumn
                ? DB::table('jobs')
                    ->where($jobOwnerColumn, $user->id)
                    ->where('status', 'approved')
                    ->count()
                : 0,

            'pending_jobs' => $jobOwnerColumn
                ? DB::table('jobs')
                    ->where($jobOwnerColumn, $user->id)
                    ->where('status', 'pending')
                    ->count()
                : 0,

            'mentorship_requests' => Schema::hasTable('mentorships') && Schema::hasColumn('mentorships', 'mentor_id')
                ? Mentorship::where('mentor_id', $user->id)->count()
                : 0,

            'unread_messages' => Schema::hasTable('messages') && Schema::hasColumn('messages', 'recipient_id')
                ? Message::where('recipient_id', $user->id)->whereNull('read_at')->count()
                : 0,

            'unread_notifications' => Schema::hasTable('notifications') && Schema::hasColumn('notifications', 'user_id')
                ? Notification::where('user_id', $user->id)->count()
                : 0,
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
        $aiSuggestions = $this->generateAiSuggestions($stats, $contributionScore);

        return view('alumni.dashboard', compact(
            'user',
            'stats',
            'myJobs',
            'mentorshipRequests',
            'recommendedStudents',
            'contributionScore',
            'aiSuggestions'
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

    private function calculateContributionScore(array $stats): int
    {
        $score = 20;

        $score += min(($stats['my_jobs'] ?? 0) * 10, 30);
        $score += min(($stats['approved_jobs'] ?? 0) * 10, 25);
        $score += min(($stats['mentorship_requests'] ?? 0) * 5, 15);
        $score += min(($stats['unread_messages'] ?? 0) * 2, 10);

        return min($score, 100);
    }

    private function generateAiSuggestions(array $stats, int $contributionScore): array
    {
        $suggestions = [];

        if (($stats['my_jobs'] ?? 0) < 1) {
            $suggestions[] = 'Post at least one job or internship opportunity for students.';
        }

        if (($stats['pending_jobs'] ?? 0) > 0) {
            $suggestions[] = 'Some of your job posts are pending admin approval.';
        }

        if (($stats['mentorship_requests'] ?? 0) > 0) {
            $suggestions[] = 'You have mentorship requests waiting for your response.';
        }

        if ($contributionScore < 60) {
            $suggestions[] = 'Increase your contribution by posting jobs and mentoring students.';
        }

        $suggestions[] = 'Share your industry experience with current students.';
        $suggestions[] = 'Help students improve their CV, GitHub and interview skills.';

        return $suggestions;
    }
}