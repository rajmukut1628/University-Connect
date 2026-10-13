<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageInformationController extends Controller
{
    public const TOPICS = [
        'registration' => 'registration', 'register' => 'registration', 'signup' => 'registration', 'sign-up' => 'registration',
        'admin' => 'admin', 'superadmin' => 'superadmin', 'super-admin' => 'superadmin', 'super_admin' => 'superadmin',
        'admin/login' => 'admin', 'superadmin/login' => 'superadmin', 'super-admin/login' => 'superadmin',
        'student' => 'student', 'students' => 'student', 'alumni' => 'alumni',
        'student/login' => 'student', 'alumni/login' => 'alumni',
        'signin' => 'login', 'sign-in' => 'login', 'log-in' => 'login',
        'job' => 'jobs', 'event' => 'events', 'donation' => 'donations',
        'mentor' => 'mentorship', 'mentors' => 'mentorship', 'mentorship' => 'mentorship', 'mentorships' => 'mentorship',
        'message' => 'messages', 'notification' => 'notifications', 'account' => 'profile',
        'archive' => 'archive', 'help' => 'help', 'support' => 'help', 'contact' => 'help',
    ];

    public function __invoke(Request $request)
    {
        $topic = self::TOPICS[$request->path()] ?? abort(404);
        [$title, $message] = match ($topic) {
            'registration' => ['Self-registration is unavailable', 'Student and Alumni accounts are created from university-verified records. If you do not have an account, contact your university administrator.'],
            'admin' => ['University Admin Access', 'Administrators use the same login page as everyone else. Sign in with your assigned Admin account to open your dashboard. Admin accounts are created by a Super Admin.'],
            'superadmin' => ['Super Admin Access', 'Sign in with your assigned Super Admin account using the login page. Your dashboard provides the management tools available to your account.'],
            'student' => ['Student Access', 'Use your university-provided account to sign in. Your dashboard connects you to mentors, university events and career opportunities. Contact your university administrator if your account is unavailable.'],
            'alumni' => ['Alumni Access', 'Sign in with your university-provided Alumni account to manage your profile, review mentorship requests and share opportunities. Contact your university administrator for account assistance.'],
            'login' => ['Sign in to University Connect', 'Use your existing university account on the login page. If you have forgotten your password, you can reset it there.'],
            'jobs' => ['University Jobs', 'Browse approved career opportunities from the Jobs page after signing in.'],
            'events' => ['University Events', 'Visit the Events page after signing in to explore university events and registration details.'],
            'donations' => ['Donation Campaigns', 'Visit the Donations page after signing in to view campaign information and payment options.'],
            'mentorship' => ['University Mentorship', 'Students can find Alumni mentors and request guidance. Alumni can review and accept their mentorship requests. Sign in to open the section available to your account.'],
            'messages' => ['Mentorship Messages', 'Students and Alumni can message each other after their mentorship request is accepted. Sign in to open your conversations.'],
            'notifications' => ['Your Notifications', 'Sign in to view updates and notifications for your account.'],
            'profile' => ['Your University Profile', 'Sign in to view and update your own profile information.'],
            'archive' => ['Post Archive & Extensions', 'Sign in to view your archived posts and date extension requests. Admins can review extension requests by section.'],
            default => ['Account Help', 'Use the login page to access your existing university account or reset your password. For account creation or access assistance, contact your university administrator.'],
        };
        $destination = $request->user() ? match ($topic) {
            'jobs' => ['Jobs', 'jobs.index'], 'events' => ['Events', 'events.index'],
            'donations' => ['Donations', 'donations.index'], 'messages' => ['Messages', 'messages.index'],
            'notifications' => ['Notifications', 'notifications.index'], 'profile' => ['My Profile', 'profile.edit'],
            'archive' => ['Archive / Requests', 'posts.archive'],
            'mentorship' => match ($request->user()->role) {
                'student' => ['Find a Mentor', 'mentors.index'], 'alumni' => ['Mentorship Requests', 'mentors.requests'],
                default => null,
            },
            default => null,
        } : null;
        return response()->view('information.show', compact('title', 'message', 'destination'))
            ->header('Cache-Control', 'private, no-store');
    }
}
