<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\User;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\MentorshipController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ResumeAnalysisController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DonationContributionController;
use App\Http\Controllers\DonationManualPaymentController;
use App\Http\Controllers\NewsfeedController;
use App\Http\Controllers\FeedActionController;
use App\Http\Controllers\CallController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\AskAIController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\VerificationController;

use App\Http\Controllers\SuperAdmin\AdminController;
use App\Http\Controllers\SuperAdmin\VerifiedUserController as SuperAdminVerifiedUserController;

use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Alumni\DashboardController as AlumniDashboardController;
use App\Http\Controllers\AlumniConversionController;

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    $homeStats = [
        'students' => User::where('role', 'student')->count(),
        'alumni' => User::where('role', 'alumni')->count(),

        'jobs' => Schema::hasTable('job_postings')
            ? DB::table('job_postings')->count()
            : (Schema::hasTable('jobs') ? DB::table('jobs')->count() : 0),

        'events' => Schema::hasTable('events')
            ? DB::table('events')->count()
            : 0,

        'admins' => User::whereIn('role', ['admin', 'super_admin'])->count(),

        'verified_users' => User::where('is_active', true)
            ->where('is_blocked', false)
            ->count(),
    ];

    return view('welcome', compact('homeStats'));
})->name('home');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Main Dashboard Redirect
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->is_blocked) {
            auth()->logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account has been blocked.',
            ]);
        }

        if (!$user->is_active) {
            auth()->logout();

            return redirect()->route('login')->withErrors([
                'email' => 'Your account is not active yet.',
            ]);
        }

        return match ($user->role) {
            'super_admin' => redirect()->route('superadmin.dashboard'),
            'admin'       => redirect()->route('admin.dashboard'),
            'student'     => redirect()->route('student.dashboard'),
            'alumni'      => redirect()->route('alumni.dashboard'),
            default       => redirect()->route('home'),
        };
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Alumni Conversion System
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:student'])->group(function () {
        Route::get('/alumni-conversion/apply', [AlumniConversionController::class, 'create'])
            ->name('alumni-conversion.create');

        Route::post('/alumni-conversion/apply', [AlumniConversionController::class, 'store'])
            ->name('alumni-conversion.store');
    });

    Route::middleware(['role:admin,super_admin'])->group(function () {
        Route::get('/alumni-conversion/requests', [AlumniConversionController::class, 'index'])
            ->name('alumni-conversion.index');

        Route::patch('/alumni-conversion/{conversionRequest}/approve', [AlumniConversionController::class, 'approve'])
            ->name('alumni-conversion.approve');

        Route::patch('/alumni-conversion/{conversionRequest}/reject', [AlumniConversionController::class, 'reject'])
            ->name('alumni-conversion.reject');
    });

    /*
    |--------------------------------------------------------------------------
    | Super Admin Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:super_admin'])
        ->prefix('superadmin')
        ->name('superadmin.')
        ->group(function () {

            Route::get('/dashboard', [AdminDashboardController::class, 'index'])
                ->name('dashboard');

            Route::post('/generate-ai-report', [AdminDashboardController::class, 'generateAiReport'])
                ->name('generate-ai-report');

            Route::get('/verified-users', [SuperAdminVerifiedUserController::class, 'index'])
                ->name('verified-users.index');

            Route::post('/verified-users', [SuperAdminVerifiedUserController::class, 'store'])
                ->name('verified-users.store');

            Route::post('/verified-users/bulk-store', [SuperAdminVerifiedUserController::class, 'bulkStore'])
                ->name('verified-users.bulk-store');

            Route::post('/verified-users/bulk-preview', [SuperAdminVerifiedUserController::class, 'bulkPreview'])
                ->name('verified-users.bulk-preview');

            Route::post('/verified-users/bulk-confirm', [SuperAdminVerifiedUserController::class, 'bulkConfirm'])
                ->name('verified-users.bulk-confirm');

            Route::delete('/verified-users/{verifiedUser}', [SuperAdminVerifiedUserController::class, 'destroy'])
                ->name('verified-users.destroy');

            Route::get('/admins/create', [AdminController::class, 'create'])
                ->name('admins.create');

            Route::post('/admins', [AdminController::class, 'store'])
                ->name('admins.store');

            Route::get('/users', [UserManagementController::class, 'index'])
                ->name('users.index');

            Route::patch('/users/{user}/block', [UserManagementController::class, 'block'])
                ->name('users.block');

            Route::patch('/users/{user}/unblock', [UserManagementController::class, 'unblock'])
                ->name('users.unblock');

            Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])
                ->name('users.destroy');

            Route::get('/verification', [VerificationController::class, 'index'])
                ->name('verification.index');

            Route::patch('/verification/{user}/approve', [VerificationController::class, 'approve'])
                ->name('verification.approve');

            Route::patch('/verification/{user}/reject', [VerificationController::class, 'reject'])
                ->name('verification.reject');
        });
            /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/dashboard', [AdminDashboardController::class, 'index'])
                ->name('dashboard');

            Route::post('/generate-ai-report', [AdminDashboardController::class, 'generateAiReport'])
                ->name('generate-ai-report');

            Route::get('/users', [UserManagementController::class, 'index'])
                ->name('users.index');

            Route::patch('/users/{user}/block', [UserManagementController::class, 'block'])
                ->name('users.block');

            Route::patch('/users/{user}/unblock', [UserManagementController::class, 'unblock'])
                ->name('users.unblock');

            Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])
                ->name('users.destroy');

            Route::get('/verification', [VerificationController::class, 'index'])
                ->name('verification.index');

            Route::patch('/verification/{user}/approve', [VerificationController::class, 'approve'])
                ->name('verification.approve');

            Route::patch('/verification/{user}/reject', [VerificationController::class, 'reject'])
                ->name('verification.reject');
        });

    /*
    |--------------------------------------------------------------------------
    | Student Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:student'])
        ->prefix('student')
        ->name('student.')
        ->group(function () {

            Route::get('/dashboard', [StudentDashboardController::class, 'index'])
                ->name('dashboard');

            Route::post('/ai-study-assistant', [AIController::class, 'ask'])
                ->name('ai.study.assistant');
        });

    /*
    |--------------------------------------------------------------------------
    | Alumni Routes
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:alumni'])
        ->prefix('alumni')
        ->name('alumni.')
        ->group(function () {

            Route::get('/dashboard', [AlumniDashboardController::class, 'index'])
                ->name('dashboard');
        });

    /*
    |--------------------------------------------------------------------------
    | Ask AI
    |--------------------------------------------------------------------------
    */

    Route::get('/ask-ai', [AskAIController::class, 'index'])
        ->name('ask-ai.index');

    Route::post('/ask-ai/ask', [AskAIController::class, 'ask'])
        ->name('ask-ai.ask');

    /*
    |--------------------------------------------------------------------------
    | Newsfeed
    |--------------------------------------------------------------------------
    */

    Route::get('/newsfeed', [NewsfeedController::class, 'index'])
        ->name('newsfeed.index');

    Route::post('/newsfeed/like', [FeedActionController::class, 'like'])
        ->name('newsfeed.like');

    Route::post('/newsfeed/comment', [FeedActionController::class, 'comment'])
        ->name('newsfeed.comment');

    Route::post('/newsfeed/share', [FeedActionController::class, 'share'])
        ->name('newsfeed.share');

    /*
    |--------------------------------------------------------------------------
    | Messages
    |--------------------------------------------------------------------------
    */

    Route::get('/messages', [MessageController::class, 'index'])
        ->name('messages.index');

    Route::get('/messages/{user}', [MessageController::class, 'show'])
        ->name('messages.show');

    Route::post('/messages/{user}', [MessageController::class, 'store'])
        ->name('messages.store');

    Route::patch('/messages/message/{message}', [MessageController::class, 'update'])
        ->name('messages.update');

    Route::delete('/messages/message/{message}', [MessageController::class, 'destroy'])
        ->name('messages.destroy');

    /*
    |--------------------------------------------------------------------------
    | Calls
    |--------------------------------------------------------------------------
    */

    Route::post('/calls/start/{user}', [CallController::class, 'start'])
        ->name('calls.start');

    Route::get('/calls/{call}', [CallController::class, 'show'])
        ->name('calls.show');

    Route::post('/calls/{call}/offer', [CallController::class, 'storeOffer'])
        ->name('calls.offer');

    Route::post('/calls/{call}/answer', [CallController::class, 'storeAnswer'])
        ->name('calls.answer');

    Route::post('/calls/{call}/candidate', [CallController::class, 'storeCandidate'])
        ->name('calls.candidate');

    Route::get('/calls/{call}/poll', [CallController::class, 'poll'])
        ->name('calls.poll');

    Route::post('/calls/{call}/accept', [CallController::class, 'accept'])
        ->name('calls.accept');

    Route::post('/calls/{call}/reject', [CallController::class, 'reject'])
        ->name('calls.reject');

    Route::post('/calls/{call}/end', [CallController::class, 'end'])
        ->name('calls.end');

    /*
    |--------------------------------------------------------------------------
    | Donations
    |--------------------------------------------------------------------------
    */

    Route::get('/donations', [DonationController::class, 'index'])
        ->name('donations.index');

    Route::get('/donations/create', [DonationController::class, 'create'])
        ->name('donations.create');

    Route::post('/donations', [DonationController::class, 'store'])
        ->name('donations.store');

    Route::get('/donations/{donation}', [DonationController::class, 'show'])
        ->name('donations.show');

    Route::patch('/donations/{donation}/approve', [DonationController::class, 'approve'])
        ->name('donations.approve');

    Route::patch('/donations/{donation}/reject', [DonationController::class, 'reject'])
        ->name('donations.reject');

    Route::delete('/donations/{donation}', [DonationController::class, 'destroy'])
        ->name('donations.destroy');

    Route::post('/donations/{donation}/contribute', [DonationContributionController::class, 'store'])
        ->name('donations.contribute');

    Route::post('/donations/{donation}/manual-payment', [DonationManualPaymentController::class, 'store'])
        ->name('donations.manual-payment');

    /*
    |--------------------------------------------------------------------------
    | Resume Analyzer
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:student,alumni'])->group(function () {

        Route::get('/resume-analyzer', [ResumeAnalysisController::class, 'index'])
            ->name('resume-analyzer.index');

        Route::post('/resume-analyzer', [ResumeAnalysisController::class, 'store'])
            ->name('resume-analyzer.store');

        Route::delete('/resume-analyzer/{resumeAnalysis}', [ResumeAnalysisController::class, 'destroy'])
            ->name('resume-analyzer.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | Jobs
    |--------------------------------------------------------------------------
    */

    Route::get('/jobs', [JobController::class, 'index'])
        ->name('jobs.index');

    Route::middleware(['role:alumni'])->group(function () {

        Route::get('/jobs/create', [JobController::class, 'create'])
            ->name('jobs.create');

        Route::post('/jobs', [JobController::class, 'store'])
            ->name('jobs.store');

        Route::get('/jobs/my-posts', [JobController::class, 'myJobs'])
            ->name('jobs.my');
    });

    Route::middleware(['role:student'])->group(function () {

        Route::post('/jobs/{job}/apply', [JobController::class, 'apply'])
            ->name('jobs.apply');
    });

    Route::middleware(['role:admin,super_admin'])->group(function () {

        Route::patch('/jobs/{job}/approve', [JobController::class, 'approve'])
            ->name('jobs.approve');

        Route::patch('/jobs/{job}/reject', [JobController::class, 'reject'])
            ->name('jobs.reject');
    });

    Route::get('/jobs/{job}', [JobController::class, 'show'])
        ->name('jobs.show');

    /*
    |--------------------------------------------------------------------------
    | Mentorship
    |--------------------------------------------------------------------------
    */

    Route::middleware(['role:student'])->group(function () {

        Route::get('/alumni-mentors', [MentorshipController::class, 'index'])
            ->name('mentors.index');

        Route::post('/alumni-mentors/{mentor}/request', [MentorshipController::class, 'requestMentor'])
            ->name('mentors.request');
    });

    Route::middleware(['role:alumni'])->group(function () {

        Route::get('/mentorship-requests', [MentorshipController::class, 'myRequests'])
            ->name('mentors.requests');

        Route::patch('/mentorships/{mentorship}/accept', [MentorshipController::class, 'accept'])
            ->name('mentors.accept');

        Route::patch('/mentorships/{mentorship}/reject', [MentorshipController::class, 'reject'])
            ->name('mentors.reject');
    });

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */

    Route::get('/events', [EventController::class, 'index'])
        ->name('events.index');

    Route::middleware(['role:alumni,admin,super_admin'])->group(function () {

        Route::get('/events/create', [EventController::class, 'create'])
            ->name('events.create');

        Route::post('/events', [EventController::class, 'store'])
            ->name('events.store');
    });

    Route::middleware(['role:student,alumni'])->group(function () {

        Route::post('/events/{event}/register', [EventController::class, 'register'])
            ->name('events.register');
    });

    Route::middleware(['role:admin,super_admin'])->group(function () {

        Route::get('/admin/event-participants/pending', [EventController::class, 'pendingParticipants'])
            ->name('event.participants.pending');

        Route::patch('/event-participants/{participant}/approve', [EventController::class, 'approveParticipant'])
            ->name('event.participants.approve');

        Route::patch('/event-participants/{participant}/reject', [EventController::class, 'rejectParticipant'])
            ->name('event.participants.reject');
    });

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.read');

    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])
        ->name('notifications.readAll');

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

if (file_exists(__DIR__ . '/auth.php')) {
    require __DIR__ . '/auth.php';
}