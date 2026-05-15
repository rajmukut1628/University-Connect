<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total_users' => User::count(),
            'students' => User::where('role', 'student')->count(),
            'alumni' => User::where('role', 'alumni')->count(),
            'admins' => User::where('role', 'admin')->count(),
            'super_admins' => User::where('role', 'super_admin')->count(),
            'active_users' => User::where('is_active', true)->where('is_blocked', false)->count(),
            'blocked_users' => User::where('is_blocked', true)->count(),

            'job_postings' => Schema::hasTable('job_postings') ? DB::table('job_postings')->count() : 0,
            'approved_jobs' => Schema::hasTable('job_postings') ? DB::table('job_postings')->where('status', 'approved')->count() : 0,
            'pending_jobs' => Schema::hasTable('job_postings') ? DB::table('job_postings')->where('status', 'pending')->count() : 0,

            'events' => Schema::hasTable('events') ? DB::table('events')->count() : 0,
            'event_registrations' => Schema::hasTable('event_participants') ? DB::table('event_participants')->count() : 0,

            'mentorships' => Schema::hasTable('mentorships') ? DB::table('mentorships')->count() : 0,
            'accepted_mentorships' => Schema::hasTable('mentorships') ? DB::table('mentorships')->where('status', 'accepted')->count() : 0,

            'messages' => Schema::hasTable('messages') ? DB::table('messages')->count() : 0,
            'notifications' => Schema::hasTable('notifications') ? DB::table('notifications')->count() : 0,
        ];

        $recentUsers = User::latest()->take(6)->get();

        $recentJobs = Schema::hasTable('job_postings')
            ? DB::table('job_postings')->latest()->take(5)->get()
            : collect();

        $recentMentorships = Schema::hasTable('mentorships')
            ? DB::table('mentorships')
                ->join('users as students', 'mentorships.student_id', '=', 'students.id')
                ->join('users as mentors', 'mentorships.mentor_id', '=', 'mentors.id')
                ->select(
                    'mentorships.id',
                    'mentorships.status',
                    'mentorships.created_at',
                    'students.name as student_name',
                    'mentors.name as mentor_name'
                )
                ->latest('mentorships.created_at')
                ->take(5)
                ->get()
            : collect();

        $chart = [
            'users' => [
                'students' => $stats['students'],
                'alumni' => $stats['alumni'],
                'admins' => $stats['admins'],
                'super_admins' => $stats['super_admins'],
            ],
            'platform' => [
                'jobs' => $stats['job_postings'],
                'events' => $stats['events'],
                'mentorships' => $stats['mentorships'],
                'messages' => $stats['messages'],
            ],
        ];

        return view('admin.dashboard', compact(
            'stats',
            'recentUsers',
            'recentJobs',
            'recentMentorships',
            'chart'
        ));
    }

    public function generateAiReport(Request $request)
    {
        $apiKey = config('services.gemini.api_key');
        $model = config('services.gemini.model', 'gemini-2.5-flash');
        $baseUrl = rtrim(config('services.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta'), '/');

        if (blank($apiKey)) {
            return back()->with('ai_report', 'Gemini API key is missing. Please add GEMINI_API_KEY in your .env file.');
        }

        $stats = [
            'total_users' => User::count(),
            'students' => User::where('role', 'student')->count(),
            'alumni' => User::where('role', 'alumni')->count(),
            'admins' => User::whereIn('role', ['admin', 'super_admin'])->count(),
            'active_users' => User::where('is_active', true)->where('is_blocked', false)->count(),
            'blocked_users' => User::where('is_blocked', true)->count(),

            'job_postings' => Schema::hasTable('job_postings') ? DB::table('job_postings')->count() : 0,
            'approved_jobs' => Schema::hasTable('job_postings') ? DB::table('job_postings')->where('status', 'approved')->count() : 0,
            'pending_jobs' => Schema::hasTable('job_postings') ? DB::table('job_postings')->where('status', 'pending')->count() : 0,

            'events' => Schema::hasTable('events') ? DB::table('events')->count() : 0,
            'event_registrations' => Schema::hasTable('event_participants') ? DB::table('event_participants')->count() : 0,

            'mentorships' => Schema::hasTable('mentorships') ? DB::table('mentorships')->count() : 0,
            'accepted_mentorships' => Schema::hasTable('mentorships') ? DB::table('mentorships')->where('status', 'accepted')->count() : 0,

            'messages' => Schema::hasTable('messages') ? DB::table('messages')->count() : 0,
            'notifications' => Schema::hasTable('notifications') ? DB::table('notifications')->count() : 0,
        ];

        $prompt = "
You are an expert AI admin analyst for a university networking platform named University Connect.

Generate a professional, concise admin report based on the live platform data below.
Do not use markdown symbols like **, ###, or bullet stars. Write clean plain text with clear section titles.
Give complete report, not short summary.

Platform Data:
- Total Users: {$stats['total_users']}
- Students: {$stats['students']}
- Alumni: {$stats['alumni']}
- Admins/Super Admins: {$stats['admins']}
- Active Users: {$stats['active_users']}
- Blocked Users: {$stats['blocked_users']}
- Job Posts: {$stats['job_postings']}
- Approved Jobs: {$stats['approved_jobs']}
- Pending Jobs: {$stats['pending_jobs']}
- Events: {$stats['events']}
- Event Registrations: {$stats['event_registrations']}
- Mentorships: {$stats['mentorships']}
- Accepted Mentorships: {$stats['accepted_mentorships']}
- Messages: {$stats['messages']}
- Notifications: {$stats['notifications']}

Write the report with these sections:
1. Executive Summary
2. User Activity Analysis
3. Jobs, Events and Mentorship Insights
4. Security and Account Health
5. Admin Recommendations

Keep it polished, practical, and easy for a university administrator to understand.
";

        try {
            $url = "{$baseUrl}/models/{$model}:generateContent?key={$apiKey}";

            $response = Http::timeout(90)
                ->retry(2, 1000)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->post($url, [
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                   'generationConfig' => [
                    'temperature' => 0.6,
                    'topK' => 40,
                    'topP' => 0.95,
                    'maxOutputTokens' => 3000,
                    ],
                ]);

            if ($response->failed()) {
                Log::error('Gemini AI Report Failed', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return back()->with(
                    'ai_report',
                    'Gemini API Error: ' . $response->status() . ' - ' . $response->body()
                );
            }

            $data = $response->json();

            $report = data_get($data, 'candidates.0.content.parts.0.text');

            if (blank($report)) {
                Log::warning('Gemini AI Report Empty Response', [
                    'response' => $data,
                ]);

                return back()->with(
                    'ai_report',
                    'AI report generated, but response text was empty. Raw response: ' . json_encode($data)
                );
            }

            return back()->with('ai_report', $report);

        } catch (\Throwable $e) {
            Log::error('Gemini AI Report Exception', [
                'message' => $e->getMessage(),
            ]);

            return back()->with(
                'ai_report',
                'Gemini Exception: ' . $e->getMessage()
            );
        }
    }
}