<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Display Admin / Super Admin dashboard.
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Dashboard Statistics
        |--------------------------------------------------------------------------
        */

        $stats = [
            'total_users' => User::count(),

            'students' => User::where('role', 'student')->count(),

            'alumni' => User::where('role', 'alumni')->count(),

            'job_postings' => Schema::hasTable('job_postings')
                ? DB::table('job_postings')->count()
                : 0,

            'events' => Schema::hasTable('events')
                ? DB::table('events')->count()
                : 0,

            'mentorships' => Schema::hasTable('mentorships')
                ? DB::table('mentorships')->count()
                : 0,
        ];


        /*
        |--------------------------------------------------------------------------
        | Recent Users
        |--------------------------------------------------------------------------
        */

        $recentUsers = User::query()
            ->latest()
            ->take(6)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | Recent Job Posts
        |--------------------------------------------------------------------------
        */

        $recentJobs = Schema::hasTable('job_postings')
            ? DB::table('job_postings')
                ->latest()
                ->take(5)
                ->get()
            : collect();


        /*
        |--------------------------------------------------------------------------
        | Dashboard View
        |--------------------------------------------------------------------------
        */

        return view('admin.dashboard', compact(
            'stats',
            'recentUsers',
            'recentJobs'
        ));
    }
}