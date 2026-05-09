<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'total_users' => User::count(),
            'students' => User::where('role', 'student')->count(),
            'alumni' => User::where('role', 'alumni')->count(),
            'admins' => User::where('role', 'admin')->count(),

            'active_users' => User::where('is_active', true)->count(),
            'blocked_users' => User::where('is_blocked', true)->count(),

            'job_postings' => Schema::hasTable('job_postings')
                ? DB::table('job_postings')->count()
                : 0,

            'approved_jobs' => Schema::hasTable('job_postings')
                ? DB::table('job_postings')->where('status', 'approved')->count()
                : 0,

            'pending_jobs' => Schema::hasTable('job_postings')
                ? DB::table('job_postings')->where('status', 'pending')->count()
                : 0,

            'events' => Schema::hasTable('events')
                ? DB::table('events')->count()
                : 0,

            'event_registrations' => Schema::hasTable('event_participants')
                ? DB::table('event_participants')->count()
                : 0,

            'mentorships' => Schema::hasTable('mentorships')
                ? DB::table('mentorships')->count()
                : 0,

            'accepted_mentorships' => Schema::hasTable('mentorships')
                ? DB::table('mentorships')->where('status', 'accepted')->count()
                : 0,

            'messages' => Schema::hasTable('messages')
                ? DB::table('messages')->count()
                : 0,

            'notifications' => Schema::hasTable('notifications')
                ? DB::table('notifications')->count()
                : 0,
        ];

        $recentUsers = User::latest()
            ->take(6)
            ->get();

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
}