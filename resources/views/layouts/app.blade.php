<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{
          mobileMenuOpen: false,
          profileMenuOpen: false,
          moreMenuOpen: false,
          darkMode: localStorage.getItem('darkMode') === 'true'
      }"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'University Connect') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap"
          rel="stylesheet">

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        * {
            font-family: 'Figtree', sans-serif;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            min-height: 100vh;
        }

        [x-cloak] {
            display: none !important;
        }

        /* =========================================================
           ANIMATIONS
        ========================================================= */

        @keyframes ucFloat {
            0%, 100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-18px) scale(1.035);
            }
        }

        @keyframes ucShine {
            from {
                transform: translateX(-150%);
            }

            to {
                transform: translateX(150%);
            }
        }

        @keyframes ucGlow {
            0%, 100% {
                box-shadow:
                    0 22px 70px rgba(79, 70, 229, .22),
                    inset 0 1px 0 rgba(255, 255, 255, .16);
            }

            50% {
                box-shadow:
                    0 26px 90px rgba(236, 72, 153, .25),
                    inset 0 1px 0 rgba(255, 255, 255, .20);
            }
        }

        @keyframes ucActivePulse {
            0%, 100% {
                box-shadow: 0 16px 42px rgba(99, 102, 241, .36);
            }

            50% {
                box-shadow: 0 18px 55px rgba(34, 211, 238, .30);
            }
        }

        .uc-orb {
            animation: ucFloat 7s ease-in-out infinite;
        }

        /* =========================================================
           APP BACKGROUND
        ========================================================= */

        .uc-app-shell {
            min-height: 100vh;

            background:
                radial-gradient(
                    circle at 12% 10%,
                    rgba(99, 102, 241, .26),
                    transparent 28%
                ),
                radial-gradient(
                    circle at 85% 16%,
                    rgba(236, 72, 153, .18),
                    transparent 30%
                ),
                radial-gradient(
                    circle at 50% 85%,
                    rgba(34, 211, 238, .14),
                    transparent 32%
                ),
                linear-gradient(
                    135deg,
                    #f8fafc,
                    #eef2ff 42%,
                    #fdf2f8
                );
        }

        .dark .uc-app-shell {
            background:
                radial-gradient(
                    circle at 12% 10%,
                    rgba(99, 102, 241, .22),
                    transparent 28%
                ),
                radial-gradient(
                    circle at 85% 16%,
                    rgba(236, 72, 153, .16),
                    transparent 30%
                ),
                radial-gradient(
                    circle at 50% 85%,
                    rgba(34, 211, 238, .12),
                    transparent 32%
                ),
                linear-gradient(
                    135deg,
                    #020617,
                    #0f172a 45%,
                    #1e1b4b
                );
        }

        /* =========================================================
           TOP NAVBAR
        ========================================================= */

        .uc-topbar-wrap {
            position: sticky !important;
            top: 12px !important;
            left: 0;
            right: 0;

            z-index: 999999 !important;

            padding:
                12px
                clamp(10px, 1.4vw, 20px)
                0;
        }

        .uc-topbar {
            position: relative;

            overflow: visible;

            min-height: 74px;

            border-radius: 26px;

            background:
                radial-gradient(
                    circle at top left,
                    rgba(99, 102, 241, .32),
                    transparent 34%
                ),
                radial-gradient(
                    circle at bottom right,
                    rgba(236, 72, 153, .22),
                    transparent 34%
                ),
                linear-gradient(
                    135deg,
                    rgba(15, 23, 42, .94),
                    rgba(30, 27, 75, .90),
                    rgba(88, 28, 135, .76)
                );

            border: 1px solid rgba(255, 255, 255, .14);

            box-shadow:
                0 24px 80px rgba(2, 6, 23, .32),
                inset 0 1px 0 rgba(255, 255, 255, .12);

            backdrop-filter: blur(28px);

            animation: ucGlow 5s ease-in-out infinite;
        }

        .uc-topbar::before {
            content: "";

            position: absolute;
            inset: 0;

            border-radius: 26px;

            background:
                linear-gradient(
                    90deg,
                    transparent,
                    rgba(255, 255, 255, .08),
                    transparent
                );

            transform: translateX(-130%);

            animation: ucShine 7s ease-in-out infinite;

            pointer-events: none;

            overflow: hidden;
        }

        .uc-topbar-inner {
            position: relative;

            z-index: 10;

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 12px;

            min-width: 0;
        }

        /* =========================================================
           BRAND
        ========================================================= */

        .uc-brand {
            display: flex;

            align-items: center;

            gap: 10px;

            min-width: 0;

            flex-shrink: 0;

            text-decoration: none;
        }

        .uc-logo-box {
            height: 46px;

            width: 46px;

            border-radius: 17px;

            background:
                linear-gradient(
                    135deg,
                    #6366f1,
                    #9333ea,
                    #ec4899
                );

            display: flex;

            align-items: center;

            justify-content: center;

            box-shadow:
                0 14px 34px rgba(147, 51, 234, .36),
                inset 0 1px 0 rgba(255, 255, 255, .24);

            flex-shrink: 0;
        }

        .uc-brand-copy {
            line-height: 1.15;
            min-width: 0;
        }

        .uc-brand-title {
            color: white;

            font-weight: 900;

            font-size: 14px;

            white-space: nowrap;
        }

        .uc-brand-subtitle {
            margin-top: 3px;

            color: rgb(148 163 184);

            font-size: 10px;

            font-weight: 800;

            white-space: nowrap;
        }

        /* =========================================================
           DESKTOP NAVIGATION
        ========================================================= */

        .uc-desktop-nav {
            flex: 1;

            min-width: 0;

            display: flex;

            align-items: center;

            justify-content: center;
        }

        .uc-nav-row {
            position: relative;

            z-index: 5;

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 6px;

            min-width: 0;

            width: 100%;

            padding: 6px;

            overflow: visible;
        }

        .uc-nav {
            position: relative;

            display: inline-flex;

            align-items: center;

            justify-content: center;

            gap: 6px;

            min-height: 38px;

            padding: 0 11px;

            border-radius: 15px;

            font-weight: 900;

            font-size: 11px;

            color: rgb(203 213 225);

            white-space: nowrap;

            transition: all .28s ease;

            overflow: hidden;

            flex-shrink: 0;

            background: rgba(255, 255, 255, .075);

            border: 1px solid rgba(255, 255, 255, .10);

            box-shadow:
                0 10px 24px rgba(15, 23, 42, .15);

            text-decoration: none;

            cursor: pointer;
        }

        .uc-nav::before {
            content: "";

            position: absolute;

            inset: 0;

            width: 48%;

            background:
                linear-gradient(
                    90deg,
                    transparent,
                    rgba(255, 255, 255, .22),
                    transparent
                );

            transform: translateX(-150%);

            pointer-events: none;
        }

        .uc-nav:hover::before {
            animation: ucShine 1s ease;
        }

        .uc-nav:hover {
            background: rgba(255, 255, 255, .14);

            color: white;

            transform: translateY(-2px);

            box-shadow:
                0 16px 38px rgba(15, 23, 42, .25);
        }

        .uc-active {
            background:
                linear-gradient(
                    135deg,
                    #4f46e5,
                    #9333ea,
                    #ec4899
                ) !important;

            color: white !important;

            border-color:
                rgba(255, 255, 255, .20) !important;

            box-shadow:
                0 18px 45px rgba(147, 51, 234, .36) !important;

            animation:
                ucActivePulse 3s infinite;
        }

        /* =========================================================
           MORE MENU
        ========================================================= */

        .uc-more-wrapper {
            position: relative;

            flex-shrink: 0;
        }

        .uc-more-dropdown {
            position: absolute;

            top: calc(100% + 12px);

            left: 50%;

            transform: translateX(-50%);

            width: 220px;

            border-radius: 20px;

            background:
                radial-gradient(
                    circle at top left,
                    rgba(99, 102, 241, .26),
                    transparent 38%
                ),
                linear-gradient(
                    145deg,
                    rgba(15, 23, 42, .98),
                    rgba(30, 27, 75, .97)
                );

            border:
                1px solid rgba(255, 255, 255, .13);

            box-shadow:
                0 24px 80px rgba(2, 6, 23, .45);

            backdrop-filter: blur(26px);

            padding: 9px;

            z-index: 9999999;
        }

        .uc-more-link {
            display: flex;

            align-items: center;

            gap: 10px;

            min-height: 42px;

            padding: 0 12px;

            border-radius: 14px;

            color: rgb(203 213 225);

            font-size: 11px;

            font-weight: 900;

            text-decoration: none;

            transition: all .22s ease;
        }

        .uc-more-link:hover {
            background:
                rgba(255, 255, 255, .11);

            color: white;

            transform: translateX(3px);
        }

        .uc-more-link-active {
            color: white;

            background:
                linear-gradient(
                    135deg,
                    rgba(79, 70, 229, .8),
                    rgba(147, 51, 234, .8)
                );
        }

        /* =========================================================
           RIGHT SIDE
        ========================================================= */

        .uc-right-actions {
            display: flex;

            align-items: center;

            gap: 7px;

            flex-shrink: 0;
        }

        .uc-icon-btn {
            height: 44px;

            width: 44px;

            border-radius: 16px;

            background:
                rgba(255, 255, 255, .10);

            border:
                1px solid rgba(255, 255, 255, .12);

            display: inline-flex;

            align-items: center;

            justify-content: center;

            color: white;

            transition: all .28s ease;

            box-shadow:
                0 12px 28px rgba(15, 23, 42, .22);

            flex-shrink: 0;
        }

        .uc-icon-btn:hover {
            transform:
                translateY(-2px)
                scale(1.03);

            background:
                rgba(255, 255, 255, .16);

            box-shadow:
                0 18px 42px rgba(15, 23, 42, .28);
        }

        /* =========================================================
           PROFILE
        ========================================================= */

        .uc-profile-pill {
            position: relative;

            min-width: 175px;

            max-width: 215px;

            border-radius: 18px;

            background:
                linear-gradient(
                    135deg,
                    rgba(255, 255, 255, .16),
                    rgba(255, 255, 255, .07)
                );

            border:
                1px solid rgba(255, 255, 255, .13);

            box-shadow:
                0 18px 45px rgba(15, 23, 42, .22);

            backdrop-filter: blur(18px);

            transition: all .28s ease;
        }

        .uc-profile-pill:hover {
            transform: translateY(-2px);

            background:
                linear-gradient(
                    135deg,
                    rgba(255, 255, 255, .20),
                    rgba(255, 255, 255, .09)
                );
        }

        .uc-profile-avatar {
            height: 40px;

            width: 40px;

            border-radius: 14px;

            flex-shrink: 0;
        }

        .uc-role-badge {
            display: inline-flex;

            align-items: center;

            gap: 5px;

            height: 21px;

            padding: 0 8px;

            border-radius: 999px;

            background:
                rgba(34, 211, 238, .13);

            border:
                1px solid rgba(34, 211, 238, .22);

            color: rgb(165 243 252);

            font-size: 8px;

            font-weight: 900;

            text-transform: uppercase;

            letter-spacing: .06em;

            max-width: 100%;

            white-space: nowrap;
        }

        /* =========================================================
           PROFILE DROPDOWN
        ========================================================= */

        .uc-dropdown {
            position: absolute;

            top: calc(100% + 12px);

            right: 0;

            width: 270px;

            border-radius: 24px;

            background:
                radial-gradient(
                    circle at top left,
                    rgba(99, 102, 241, .26),
                    transparent 38%
                ),
                linear-gradient(
                    145deg,
                    rgba(15, 23, 42, .98),
                    rgba(30, 27, 75, .96)
                );

            border:
                1px solid rgba(255, 255, 255, .13);

            box-shadow:
                0 24px 80px rgba(2, 6, 23, .42);

            backdrop-filter: blur(26px);

            padding: 10px;

            overflow: hidden;

            z-index: 9999999;
        }

        .uc-dropdown-link {
            display: flex;

            align-items: center;

            gap: 10px;

            min-height: 44px;

            padding: 0 12px;

            border-radius: 16px;

            font-size: 12px;

            font-weight: 900;

            color: rgb(203 213 225);

            transition: all .24s ease;

            text-decoration: none;
        }

        .uc-dropdown-link:hover {
            background:
                rgba(255, 255, 255, .11);

            color: white;

            transform: translateX(4px);
        }

        /* =========================================================
           MAIN AREA
        ========================================================= */

        .uc-main-area {
            padding-top: 20px;

            min-height: 100vh;
        }

        .premium-glass {
            background:
                linear-gradient(
                    135deg,
                    rgba(255, 255, 255, .18),
                    rgba(255, 255, 255, .06)
                );

            backdrop-filter: blur(22px);

            border:
                1px solid rgba(255, 255, 255, .16);

            box-shadow:
                0 25px 80px rgba(15, 23, 42, .20);
        }

        .dark .premium-glass {
            background:
                linear-gradient(
                    135deg,
                    rgba(15, 23, 42, .94),
                    rgba(30, 41, 59, .84)
                );

            border:
                1px solid rgba(255, 255, 255, .10);
        }

        /* =========================================================
           LARGE DESKTOP COMPACTION
        ========================================================= */

        @media (max-width: 1500px) {

            .uc-brand-title {
                font-size: 13px;
            }

            .uc-brand-subtitle {
                font-size: 9px;
            }

            .uc-logo-box {
                height: 43px;
                width: 43px;
            }

            .uc-nav-row {
                gap: 4px;
            }

            .uc-nav {
                padding: 0 9px;

                font-size: 10px;

                gap: 5px;
            }

            .uc-profile-pill {
                min-width: 155px;

                max-width: 185px;
            }
        }

        /* =========================================================
           ZOOM / SMALL LAPTOP BREAKPOINT
           Desktop navbar disappears before items become compressed.
        ========================================================= */

        @media (max-width: 1279px) {

            .uc-desktop-nav {
                display: none !important;
            }

            .uc-mobile-menu-btn {
                display: inline-flex !important;
            }

            .uc-profile-pill {
                min-width: 165px;

                max-width: 200px;
            }

            .uc-topbar-inner {
                gap: 10px;
            }
        }

        /* =========================================================
           DESKTOP ONLY
        ========================================================= */

        @media (min-width: 1280px) {

            .uc-mobile-menu-btn {
                display: none !important;
            }
        }

        /* =========================================================
           TABLET
        ========================================================= */

        @media (max-width: 900px) {

            .uc-topbar-wrap {
                top: 8px !important;

                padding:
                    8px
                    10px
                    0;
            }

            .uc-topbar {
                border-radius: 22px;

                min-height: auto;
            }

            .uc-brand-subtitle {
                display: none;
            }

            .uc-profile-pill {
                min-width: auto;

                width: auto;

                max-width: none;

                padding: 6px !important;
            }

            .uc-profile-copy {
                display: none !important;
            }

            .uc-profile-chevron {
                display: none;
            }
        }

        /* =========================================================
           MOBILE
        ========================================================= */

        @media (max-width: 640px) {

            .uc-brand-copy {
                display: none;
            }

            .uc-topbar {
                border-radius: 20px;
            }

            .uc-topbar-inner {
                gap: 8px;
            }

            .uc-logo-box {
                height: 42px;

                width: 42px;

                border-radius: 15px;
            }

            .uc-profile-avatar {
                height: 38px;

                width: 38px;

                border-radius: 13px;
            }

            .uc-icon-btn {
                height: 42px;

                width: 42px;

                border-radius: 15px;
            }

            .uc-dropdown {
                position: fixed;

                top: 76px;

                left: 12px;

                right: 12px;

                width: auto;
            }
        }
    </style>
</head>

<body class="font-sans antialiased text-slate-900 dark:text-white overflow-x-hidden">

@php

    use Illuminate\Support\Str;

    $user = Auth::user();

    $role = $user?->role;

    /*
    |--------------------------------------------------------------------------
    | Notifications
    |--------------------------------------------------------------------------
    */

    $unreadCount = $user
        ? $user->notifications()
            ->where('is_read', false)
            ->count()
        : 0;

    /*
    |--------------------------------------------------------------------------
    | Avatar
    |--------------------------------------------------------------------------
    */

    $avatarUrl = null;

    if ($user && $user->profile_image) {

        $avatarUrl = Str::startsWith(
            $user->profile_image,
            ['http://', 'https://']
        )
            ? $user->profile_image
            : asset('storage/' . $user->profile_image);
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard Route
    |--------------------------------------------------------------------------
    */

    $dashboardRoute = match ($role) {

        'super_admin' =>
            Route::has('superadmin.dashboard')
                ? route('superadmin.dashboard')
                : route('dashboard'),

        'admin' =>
            Route::has('admin.dashboard')
                ? route('admin.dashboard')
                : route('dashboard'),

        'student' =>
            Route::has('student.dashboard')
                ? route('student.dashboard')
                : route('dashboard'),

        'alumni' =>
            Route::has('alumni.dashboard')
                ? route('alumni.dashboard')
                : route('dashboard'),

        default =>
            route('dashboard'),
    };

    /*
    |--------------------------------------------------------------------------
    | Navigation Helpers
    |--------------------------------------------------------------------------
    */

    $makeNavItem = function (
        $label,
        $icon,
        $route,
        $active
    ) {
        return [
            'label'  => $label,
            'icon'   => $icon,
            'route'  => $route,
            'active' => $active,
        ];
    };

    $primaryNavItems = [];

    $moreNavItems = [];

    $mobileNavItems = [];

    /*
    |--------------------------------------------------------------------------
    | Common Routes
    |--------------------------------------------------------------------------
    */

    $newsfeedRoute =
        Route::has('newsfeed.index')
            ? route('newsfeed.index')
            : '#';

    $askAiRoute =
        Route::has('ask-ai.index')
            ? route('ask-ai.index')
            : '#';

    $jobsRoute =
        Route::has('jobs.index')
            ? route('jobs.index')
            : '#';

    $eventsRoute =
        Route::has('events.index')
            ? route('events.index')
            : '#';

    $donationsRoute =
        Route::has('donations.index')
            ? route('donations.index')
            : '#';

    $messagesRoute =
        Route::has('messages.index')
            ? route('messages.index')
            : '#';

    /*
    |--------------------------------------------------------------------------
    | Student Navigation
    |--------------------------------------------------------------------------
    |
    | Desktop:
    | Dashboard | Newsfeed | Ask AI | Mentors | Jobs | More
    |
    | More:
    | Events | Donations | Messages
    |
    */

    if ($role === 'student') {

        $mentorRoute =
            Route::has('mentors.index')
                ? route('mentors.index')
                : '#';

        $primaryNavItems = [

            $makeNavItem(
                'Dashboard',
                'fa-chart-pie',
                $dashboardRoute,
                request()->routeIs(
                    'dashboard',
                    'student.dashboard'
                )
            ),

            $makeNavItem(
                'Newsfeed',
                'fa-newspaper',
                $newsfeedRoute,
                request()->routeIs('newsfeed.*')
            ),

            $makeNavItem(
                'Ask AI',
                'fa-robot',
                $askAiRoute,
                request()->routeIs('ask-ai.*')
            ),

            $makeNavItem(
                'Mentors',
                'fa-user-tie',
                $mentorRoute,
                request()->routeIs('mentors.*')
            ),

            $makeNavItem(
                'Jobs',
                'fa-briefcase',
                $jobsRoute,
                request()->routeIs('jobs.*')
            ),
        ];

        $moreNavItems = [

            $makeNavItem(
                'Events',
                'fa-calendar-days',
                $eventsRoute,
                request()->routeIs('events.*')
            ),

            $makeNavItem(
                'Donations',
                'fa-hand-holding-heart',
                $donationsRoute,
                request()->routeIs('donations.*')
            ),

            $makeNavItem(
                'Messages',
                'fa-message',
                $messagesRoute,
                request()->routeIs('messages.*')
            ),
        ];

        $mobileNavItems = array_merge(
            $primaryNavItems,
            $moreNavItems
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Alumni Navigation
    |--------------------------------------------------------------------------
    |
    | Desktop:
    | Dashboard | Newsfeed | Mentors | Jobs | Messages | More
    |
    | More:
    | Ask AI | Events | Donations
    |
    */

    elseif ($role === 'alumni') {

        $mentorRoute =
            Route::has('mentors.requests')
                ? route('mentors.requests')
                : '#';

        $primaryNavItems = [

            $makeNavItem(
                'Dashboard',
                'fa-chart-pie',
                $dashboardRoute,
                request()->routeIs(
                    'dashboard',
                    'alumni.dashboard'
                )
            ),

            $makeNavItem(
                'Newsfeed',
                'fa-newspaper',
                $newsfeedRoute,
                request()->routeIs('newsfeed.*')
            ),

            $makeNavItem(
                'Mentors',
                'fa-user-tie',
                $mentorRoute,
                request()->routeIs('mentors.*')
            ),

            $makeNavItem(
                'Jobs',
                'fa-briefcase',
                $jobsRoute,
                request()->routeIs('jobs.*')
            ),

            $makeNavItem(
                'Messages',
                'fa-message',
                $messagesRoute,
                request()->routeIs('messages.*')
            ),
        ];

        $moreNavItems = [

            $makeNavItem(
                'Ask AI',
                'fa-robot',
                $askAiRoute,
                request()->routeIs('ask-ai.*')
            ),

            $makeNavItem(
                'Events',
                'fa-calendar-days',
                $eventsRoute,
                request()->routeIs('events.*')
            ),

            $makeNavItem(
                'Donations',
                'fa-hand-holding-heart',
                $donationsRoute,
                request()->routeIs('donations.*')
            ),
        ];

        $mobileNavItems = array_merge(
            $primaryNavItems,
            $moreNavItems
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Admin / Super Admin Navigation
    |--------------------------------------------------------------------------
    */

    elseif (in_array($role, ['admin', 'super_admin'], true)) {

        /*
        |--------------------------------------------------------------------------
        | IMPORTANT:
        | Routes are now selected according to CURRENT ROLE.
        |--------------------------------------------------------------------------
        */

        if ($role === 'super_admin') {

            $verificationRoute =
                Route::has('superadmin.verification.index')
                    ? route('superadmin.verification.index')
                    : '#';

            $usersRoute =
                Route::has('superadmin.users.index')
                    ? route('superadmin.users.index')
                    : '#';

            $verifiedDbRoute =
                Route::has('superadmin.verified-users.index')
                    ? route('superadmin.verified-users.index')
                    : '#';

        } else {

            $verificationRoute =
                Route::has('admin.verification.index')
                    ? route('admin.verification.index')
                    : '#';

            $usersRoute =
                Route::has('admin.users.index')
                    ? route('admin.users.index')
                    : '#';

            /*
            |--------------------------------------------------------------------------
            | Admin Verified DB
            |--------------------------------------------------------------------------
            |
            | First try admin.verified-users.index.
            | If that route does not exist, keep it unavailable instead of
            | incorrectly sending Admin to Super Admin route.
            |
            */

            $verifiedDbRoute =
                Route::has('admin.verified-users.index')
                    ? route('admin.verified-users.index')
                    : '#';
        }

        /*
        |--------------------------------------------------------------------------
        | Admin / Super Admin Primary Navigation
        |--------------------------------------------------------------------------
        */

        $primaryNavItems = [

            $makeNavItem(
                'Dashboard',
                'fa-chart-pie',
                $dashboardRoute,
                request()->routeIs(
                    'dashboard',
                    'admin.dashboard',
                    'superadmin.dashboard'
                )
            ),

            $makeNavItem(
                'Newsfeed',
                'fa-newspaper',
                $newsfeedRoute,
                request()->routeIs('newsfeed.*')
            ),

            $makeNavItem(
                'Verification',
                'fa-user-check',
                $verificationRoute,
                request()->routeIs(
                    'admin.verification.*',
                    'superadmin.verification.*'
                )
            ),

            $makeNavItem(
                'Users',
                'fa-users-cog',
                $usersRoute,
                request()->routeIs(
                    'admin.users.*',
                    'superadmin.users.*'
                )
            ),

            $makeNavItem(
                'Verified DB',
                'fa-database',
                $verifiedDbRoute,
                request()->routeIs(
                    'admin.verified-users.*',
                    'superadmin.verified-users.*'
                )
            ),
        ];

        /*
        |--------------------------------------------------------------------------
        | Admin More Menu
        |--------------------------------------------------------------------------
        */

        $moreNavItems = [

            $makeNavItem(
                'Jobs',
                'fa-briefcase',
                $jobsRoute,
                request()->routeIs('jobs.*')
            ),

            $makeNavItem(
                'Events',
                'fa-calendar-days',
                $eventsRoute,
                request()->routeIs('events.*')
            ),

            $makeNavItem(
                'Donations',
                'fa-hand-holding-heart',
                $donationsRoute,
                request()->routeIs('donations.*')
            ),
        ];

        /*
        |--------------------------------------------------------------------------
        | ONLY Super Admin Can Create Admin
        |--------------------------------------------------------------------------
        */

        if ($role === 'super_admin') {

            $createAdminRoute =
                Route::has('superadmin.admins.create')
                    ? route('superadmin.admins.create')
                    : '#';

            /*
            | Create Admin stays visible as an important management option.
            */

            $primaryNavItems[] = $makeNavItem(
                'Create Admin',
                'fa-user-shield',
                $createAdminRoute,
                request()->routeIs(
                    'superadmin.admins.*'
                )
            );
        }

        $mobileNavItems = array_merge(
            $primaryNavItems,
            $moreNavItems
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Fallback Navigation
    |--------------------------------------------------------------------------
    */

    else {

        $primaryNavItems = [

            $makeNavItem(
                'Dashboard',
                'fa-chart-pie',
                $dashboardRoute,
                request()->routeIs('dashboard')
            ),
        ];

        $mobileNavItems = $primaryNavItems;
    }

    /*
    |--------------------------------------------------------------------------
    | More Menu Active State
    |--------------------------------------------------------------------------
    */

    $moreMenuActive = collect($moreNavItems)
        ->contains(function ($item) {
            return $item['active'];
        });

@endphp

<div class="uc-app-shell relative overflow-hidden">

    {{-- ============================================================
         ANIMATED BACKGROUND
    ============================================================ --}}

    <div class="fixed inset-0 pointer-events-none overflow-hidden">

        <div
            class="
                uc-orb
                absolute
                -top-32
                -left-32
                h-96
                w-96
                rounded-full
                bg-indigo-500/25
                blur-3xl
            ">
        </div>

        <div
            class="
                uc-orb
                absolute
                top-40
                -right-32
                h-96
                w-96
                rounded-full
                bg-fuchsia-500/20
                blur-3xl
            "
            style="animation-delay:2s">
        </div>

        <div
            class="
                uc-orb
                absolute
                -bottom-32
                left-1/3
                h-96
                w-96
                rounded-full
                bg-cyan-500/20
                blur-3xl
            "
            style="animation-delay:4s">
        </div>

    </div>

    {{-- ============================================================
         TOP NAVBAR
    ============================================================ --}}

    <header class="uc-topbar-wrap">

        <div class="uc-topbar px-3 xl:px-4 py-3">

            <div class="uc-topbar-inner">

                {{-- =================================================
                     BRAND
                ================================================= --}}

                <a
                    href="{{ $dashboardRoute }}"
                    class="uc-brand"
                >

                    <div class="uc-logo-box">

                        <i
                            class="
                                fas
                                fa-graduation-cap
                                text-base
                                text-white
                            ">
                        </i>

                    </div>

                    <div class="uc-brand-copy">

                        <h1 class="uc-brand-title">
                            University Connect
                        </h1>

                        <p class="uc-brand-subtitle">
                            AI Campus Ecosystem
                        </p>

                    </div>

                </a>

                {{-- =================================================
                     DESKTOP ROLE-BASED NAVIGATION
                ================================================= --}}

                <nav class="uc-desktop-nav">

                    <div class="uc-nav-row">

                        @foreach($primaryNavItems as $item)

                            <a
                                href="{{ $item['route'] }}"
                                class="
                                    uc-nav
                                    {{ $item['active'] ? 'uc-active' : '' }}
                                "
                            >

                                <i
                                    class="
                                        fas
                                        {{ $item['icon'] }}
                                        text-[10px]
                                    ">
                                </i>

                                <span>
                                    {{ $item['label'] }}
                                </span>

                            </a>

                        @endforeach

                        {{-- =========================================
                             MORE DROPDOWN
                        ========================================= --}}

                        @if(count($moreNavItems) > 0)

                            <div
                                class="uc-more-wrapper"
                                @click.outside="moreMenuOpen = false"
                            >

                                <button
                                    type="button"
                                    @click="
                                        moreMenuOpen = !moreMenuOpen;
                                        profileMenuOpen = false;
                                    "
                                    class="
                                        uc-nav
                                        {{ $moreMenuActive ? 'uc-active' : '' }}
                                    "
                                >

                                    <i
                                        class="
                                            fas
                                            fa-ellipsis
                                            text-[10px]
                                        ">
                                    </i>

                                    <span>
                                        More
                                    </span>

                                    <i
                                        class="
                                            fas
                                            fa-chevron-down
                                            text-[8px]
                                            ml-1
                                        "
                                        :class="{
                                            'rotate-180': moreMenuOpen
                                        }"
                                    >
                                    </i>

                                </button>

                                <div
                                    x-cloak
                                    x-show="moreMenuOpen"
                                    x-transition.opacity.scale.origin.top
                                    class="uc-more-dropdown"
                                >

                                    @foreach($moreNavItems as $item)

                                        <a
                                            href="{{ $item['route'] }}"
                                            class="
                                                uc-more-link
                                                {{
                                                    $item['active']
                                                        ? 'uc-more-link-active'
                                                        : ''
                                                }}
                                            "
                                        >

                                            <i
                                                class="
                                                    fas
                                                    {{ $item['icon'] }}
                                                    w-5
                                                    text-center
                                                    text-cyan-300
                                                ">
                                            </i>

                                            <span>
                                                {{ $item['label'] }}
                                            </span>

                                            @if($item['active'])

                                                <span
                                                    class="
                                                        ml-auto
                                                        h-2
                                                        w-2
                                                        rounded-full
                                                        bg-cyan-300
                                                    ">
                                                </span>

                                            @endif

                                        </a>

                                    @endforeach

                                </div>

                            </div>

                        @endif

                    </div>

                </nav>

                {{-- =================================================
                     RIGHT ACTIONS
                ================================================= --}}

                <div class="uc-right-actions">

                    {{-- =============================================
                         PROFILE DROPDOWN
                    ============================================= --}}

                    <div
                        class="relative"
                        @click.outside="profileMenuOpen = false"
                    >

                        <button
                            type="button"
                            @click="
                                profileMenuOpen = !profileMenuOpen;
                                moreMenuOpen = false;
                            "
                            class="
                                uc-profile-pill
                                p-2
                                flex
                                items-center
                                gap-2
                                text-left
                            "
                        >

                            {{-- Avatar --}}

                            @if($avatarUrl)

                                <img
                                    src="{{ $avatarUrl }}"
                                    alt="{{ $user->name }}"
                                    class="
                                        uc-profile-avatar
                                        object-cover
                                        border-2
                                        border-white/30
                                        shadow-lg
                                    "
                                >

                            @else

                                <div
                                    class="
                                        uc-profile-avatar
                                        bg-gradient-to-br
                                        from-indigo-500
                                        to-pink-500
                                        flex
                                        items-center
                                        justify-center
                                        text-white
                                        font-black
                                        text-base
                                        shadow-lg
                                    "
                                >

                                    {{ strtoupper(substr($user->name, 0, 1)) }}

                                </div>

                            @endif

                            {{-- Profile Information --}}

                            <div
                                class="
                                    uc-profile-copy
                                    leading-tight
                                    min-w-0
                                    flex-1
                                "
                            >

                                <p
                                    class="
                                        font-black
                                        text-xs
                                        text-white
                                        truncate
                                    "
                                >
                                    {{ $user->name }}
                                </p>

                                <span class="uc-role-badge mt-1">

                                    <i
                                        class="
                                            fas
                                            fa-circle
                                            text-[5px]
                                        ">
                                    </i>

                                    {{
                                        strtoupper(
                                            str_replace('_', ' ', $role)
                                        )
                                    }}

                                </span>

                            </div>

                            <i
                                class="
                                    uc-profile-chevron
                                    fas
                                    fa-chevron-down
                                    text-[9px]
                                    text-slate-300
                                "
                                :class="{
                                    'rotate-180': profileMenuOpen
                                }"
                            >
                            </i>

                        </button>

                        {{-- =========================================
                             PROFILE DROPDOWN CONTENT
                        ========================================= --}}

                        <div
                            x-cloak
                            x-show="profileMenuOpen"
                            x-transition.opacity.scale.origin.top.right
                            class="uc-dropdown"
                        >

                            {{-- User Mini Header --}}

                            <div
                                class="
                                    px-3
                                    py-3
                                    mb-2
                                    rounded-2xl
                                    bg-white/5
                                    border
                                    border-white/10
                                "
                            >

                                <p
                                    class="
                                        text-sm
                                        font-black
                                        text-white
                                        truncate
                                    "
                                >
                                    {{ $user->name }}
                                </p>

                                <p
                                    class="
                                        text-[10px]
                                        text-slate-400
                                        font-bold
                                        truncate
                                        mt-1
                                    "
                                >
                                    {{ $user->email }}
                                </p>

                            </div>

                            {{-- Notifications --}}

                            <a
                                href="{{
                                    Route::has('notifications.index')
                                        ? route('notifications.index')
                                        : '#'
                                }}"
                                class="
                                    uc-dropdown-link
                                    relative
                                "
                            >

                                <i
                                    class="
                                        fas
                                        fa-bell
                                        w-5
                                        text-center
                                        text-pink-300
                                    ">
                                </i>

                                <span>
                                    Notifications
                                </span>

                                @if($unreadCount > 0)

                                    <span
                                        class="
                                            ml-auto
                                            min-w-[20px]
                                            h-5
                                            px-2
                                            rounded-full
                                            bg-red-500
                                            text-[10px]
                                            font-black
                                            text-white
                                            flex
                                            items-center
                                            justify-center
                                        "
                                    >

                                        {{
                                            $unreadCount > 99
                                                ? '99+'
                                                : $unreadCount
                                        }}

                                    </span>

                                @endif

                            </a>

                            {{-- Dark / Light Mode --}}

                            <button
                                type="button"
                                @click="darkMode = !darkMode"
                                class="
                                    uc-dropdown-link
                                    w-full
                                    text-left
                                "
                            >

                                <i
                                    x-show="!darkMode"
                                    class="
                                        fas
                                        fa-moon
                                        w-5
                                        text-center
                                        text-cyan-300
                                    ">
                                </i>

                                <i
                                    x-show="darkMode"
                                    class="
                                        fas
                                        fa-sun
                                        w-5
                                        text-center
                                        text-yellow-300
                                    ">
                                </i>

                                <span x-show="!darkMode">
                                    Dark Mode
                                </span>

                                <span x-show="darkMode">
                                    Light Mode
                                </span>

                            </button>

                            {{-- Profile Settings --}}

                            <a
                                href="{{
                                    Route::has('profile.edit')
                                        ? route('profile.edit')
                                        : '#'
                                }}"
                                class="uc-dropdown-link"
                            >

                                <i
                                    class="
                                        fas
                                        fa-user-pen
                                        w-5
                                        text-center
                                        text-cyan-300
                                    ">
                                </i>

                                <span>
                                    Profile Settings
                                </span>

                            </a>

                            {{-- Dashboard --}}

                            <a
                                href="{{ $dashboardRoute }}"
                                class="uc-dropdown-link"
                            >

                                <i
                                    class="
                                        fas
                                        fa-gauge-high
                                        w-5
                                        text-center
                                        text-indigo-300
                                    ">
                                </i>

                                <span>
                                    My Dashboard
                                </span>

                            </a>

                            <div
                                class="
                                    my-2
                                    border-t
                                    border-white/10
                                ">
                            </div>

                            {{-- =====================================
                                 STUDENT ALUMNI CONVERSION
                            ===================================== --}}

                            @if(
                                auth()->check()
                                &&
                                auth()->user()->role === 'student'
                            )

                                <a
                                    href="{{
                                        Route::has('alumni-conversion.create')
                                            ? route('alumni-conversion.create')
                                            : '#'
                                    }}"
                                    class="uc-dropdown-link"
                                >

                                    <i
                                        class="
                                            fas
                                            fa-user-graduate
                                            w-5
                                            text-center
                                            text-cyan-300
                                        ">
                                    </i>

                                    <span>
                                        Apply for Alumni Status
                                    </span>

                                </a>

                            @endif

                            {{-- =====================================
                                 ADMIN ALUMNI CONVERSION MANAGEMENT
                            ===================================== --}}

                            @if(
                                auth()->check()
                                &&
                                in_array(
                                    auth()->user()->role,
                                    ['admin', 'super_admin']
                                )
                            )

                                <a
                                    href="{{
                                        Route::has('alumni-conversion.index')
                                            ? route('alumni-conversion.index')
                                            : '#'
                                    }}"
                                    class="uc-dropdown-link"
                                >

                                    <i
                                        class="
                                            fas
                                            fa-users-gear
                                            w-5
                                            text-center
                                            text-emerald-300
                                        ">
                                    </i>

                                    <span>
                                        Alumni Conversion Requests
                                    </span>

                                </a>

                            @endif

                            <div
                                class="
                                    my-2
                                    border-t
                                    border-white/10
                                ">
                            </div>

                            {{-- Logout --}}

                            <form
                                method="POST"
                                action="{{ route('logout') }}"
                            >

                                @csrf

                                <button
                                    type="submit"
                                    class="
                                        uc-dropdown-link
                                        w-full
                                        text-left
                                        text-red-300
                                        hover:text-white
                                        hover:bg-red-500/15
                                    "
                                >

                                    <i
                                        class="
                                            fas
                                            fa-right-from-bracket
                                            w-5
                                            text-center
                                        ">
                                    </i>

                                    <span>
                                        Logout
                                    </span>

                                </button>

                            </form>

                        </div>

                    </div>

                    {{-- =============================================
                         MOBILE / COMPACT MENU BUTTON
                    ============================================= --}}

                    <button
                        type="button"
                        @click="
                            mobileMenuOpen = true;
                            profileMenuOpen = false;
                            moreMenuOpen = false;
                        "
                        class="
                            uc-icon-btn
                            uc-mobile-menu-btn
                        "
                        title="Menu"
                    >

                        <i
                            class="
                                fas
                                fa-bars
                                text-sm
                            ">
                        </i>

                    </button>

                </div>

            </div>

        </div>

    </header>

    {{-- ============================================================
         MOBILE / COMPACT SIDEBAR
    ============================================================ --}}

    <div
        x-cloak
        x-show="mobileMenuOpen"
        x-transition.opacity
        class="
            fixed
            inset-0
            z-[9999999]
            xl:hidden
        "
    >

        {{-- Backdrop --}}

        <div
            @click="mobileMenuOpen = false"
            class="
                absolute
                inset-0
                bg-slate-950/80
                backdrop-blur-sm
            "
        >
        </div>

        {{-- Sidebar --}}

        <aside
            x-transition:enter="
                transition
                ease-out
                duration-300
            "
            x-transition:enter-start="
                -translate-x-full
                opacity-0
            "
            x-transition:enter-end="
                translate-x-0
                opacity-100
            "
            x-transition:leave="
                transition
                ease-in
                duration-200
            "
            x-transition:leave-start="
                translate-x-0
                opacity-100
            "
            x-transition:leave-end="
                -translate-x-full
                opacity-0
            "
            class="
                relative
                h-full
                w-[88%]
                max-w-sm
                premium-glass
                p-5
                rounded-r-[2rem]
                overflow-y-auto
            "
        >

            {{-- =====================================================
                 SIDEBAR HEADER
            ===================================================== --}}

            <div
                class="
                    flex
                    items-center
                    justify-between
                    mb-5
                "
            >

                <div
                    class="
                        flex
                        items-center
                        gap-3
                        min-w-0
                    "
                >

                    <div class="uc-logo-box">

                        <i
                            class="
                                fas
                                fa-graduation-cap
                                text-white
                            ">
                        </i>

                    </div>

                    <div class="min-w-0">

                        <h1
                            class="
                                font-black
                                text-slate-900
                                dark:text-white
                                truncate
                            "
                        >
                            University Connect
                        </h1>

                        <p
                            class="
                                text-xs
                                text-slate-500
                                dark:text-slate-400
                                font-bold
                            "
                        >
                            AI Campus Ecosystem
                        </p>

                    </div>

                </div>

                <button
                    type="button"
                    @click="mobileMenuOpen = false"
                    class="
                        h-10
                        w-10
                        rounded-xl
                        bg-red-500/15
                        text-red-500
                        flex
                        items-center
                        justify-center
                        shrink-0
                    "
                >

                    <i class="fas fa-xmark"></i>

                </button>

            </div>

            {{-- =====================================================
                 MOBILE USER INFORMATION
            ===================================================== --}}

            <div
                class="
                    mb-5
                    p-3
                    rounded-2xl
                    bg-white/10
                    dark:bg-white/5
                    border
                    border-white/10
                    flex
                    items-center
                    gap-3
                "
            >

                @if($avatarUrl)

                    <img
                        src="{{ $avatarUrl }}"
                        alt="{{ $user->name }}"
                        class="
                            h-12
                            w-12
                            rounded-2xl
                            object-cover
                            border-2
                            border-white/30
                            shrink-0
                        "
                    >

                @else

                    <div
                        class="
                            h-12
                            w-12
                            rounded-2xl
                            bg-gradient-to-br
                            from-indigo-500
                            to-pink-500
                            flex
                            items-center
                            justify-center
                            text-white
                            font-black
                            text-lg
                            shrink-0
                        "
                    >

                        {{ strtoupper(substr($user->name, 0, 1)) }}

                    </div>

                @endif

                <div class="min-w-0">

                    <p
                        class="
                            font-black
                            text-sm
                            text-slate-900
                            dark:text-white
                            truncate
                        "
                    >
                        {{ $user->name }}
                    </p>

                    <span class="uc-role-badge mt-1">

                        <i
                            class="
                                fas
                                fa-circle
                                text-[5px]
                            ">
                        </i>

                        {{
                            strtoupper(
                                str_replace('_', ' ', $role)
                            )
                        }}

                    </span>

                </div>

            </div>

            {{-- =====================================================
                 ROLE-BASED MOBILE NAVIGATION
            ===================================================== --}}

            <nav class="space-y-2">

                @foreach($mobileNavItems as $item)

                    <a
                        href="{{ $item['route'] }}"
                        @click="mobileMenuOpen = false"
                        class="
                            uc-nav
                            w-full
                            justify-start
                            {{
                                $item['active']
                                    ? 'uc-active'
                                    : ''
                            }}
                        "
                    >

                        <i
                            class="
                                fas
                                {{ $item['icon'] }}
                                w-5
                                text-center
                            ">
                        </i>

                        <span>
                            {{ $item['label'] }}
                        </span>

                    </a>

                @endforeach

                {{-- =================================================
                     MOBILE EXTRA OPTIONS
                ================================================= --}}

                <div
                    class="
                        pt-3
                        mt-3
                        border-t
                        border-slate-300/20
                        dark:border-white/10
                    "
                >

                    {{-- Notifications --}}

                    <a
                        href="{{
                            Route::has('notifications.index')
                                ? route('notifications.index')
                                : '#'
                        }}"
                        class="
                            uc-nav
                            w-full
                            justify-start
                            mb-2
                        "
                    >

                        <i
                            class="
                                fas
                                fa-bell
                                w-5
                                text-center
                            ">
                        </i>

                        <span>
                            Notifications
                        </span>

                        @if($unreadCount > 0)

                            <span
                                class="
                                    ml-auto
                                    min-w-[20px]
                                    h-5
                                    px-2
                                    rounded-full
                                    bg-red-500
                                    text-[10px]
                                    font-black
                                    text-white
                                    flex
                                    items-center
                                    justify-center
                                "
                            >

                                {{
                                    $unreadCount > 99
                                        ? '99+'
                                        : $unreadCount
                                }}

                            </span>

                        @endif

                    </a>

                    {{-- Profile --}}

                    <a
                        href="{{
                            Route::has('profile.edit')
                                ? route('profile.edit')
                                : '#'
                        }}"
                        class="
                            uc-nav
                            w-full
                            justify-start
                            mb-2
                            {{
                                request()->routeIs('profile.*')
                                    ? 'uc-active'
                                    : ''
                            }}
                        "
                    >

                        <i
                            class="
                                fas
                                fa-user-pen
                                w-5
                                text-center
                            ">
                        </i>

                        <span>
                            Profile
                        </span>

                    </a>

                    {{-- Dark / Light Mode --}}

                    <button
                        type="button"
                        @click="darkMode = !darkMode"
                        class="
                            uc-nav
                            w-full
                            justify-start
                            mb-2
                        "
                    >

                        <i
                            x-show="!darkMode"
                            class="
                                fas
                                fa-moon
                                w-5
                                text-center
                            ">
                        </i>

                        <i
                            x-show="darkMode"
                            class="
                                fas
                                fa-sun
                                w-5
                                text-center
                            ">
                        </i>

                        <span x-show="!darkMode">
                            Dark Mode
                        </span>

                        <span x-show="darkMode">
                            Light Mode
                        </span>

                    </button>

                    {{-- =============================================
                         MOBILE ALUMNI CONVERSION
                    ============================================= --}}

                    @if(
                        auth()->check()
                        &&
                        auth()->user()->role === 'student'
                    )

                        <a
                            href="{{
                                Route::has('alumni-conversion.create')
                                    ? route('alumni-conversion.create')
                                    : '#'
                            }}"
                            class="
                                uc-nav
                                w-full
                                justify-start
                                mb-2
                            "
                        >

                            <i
                                class="
                                    fas
                                    fa-user-graduate
                                    w-5
                                    text-center
                                ">
                            </i>

                            <span>
                                Alumni Application
                            </span>

                        </a>

                    @endif

                    @if(
                        auth()->check()
                        &&
                        in_array(
                            auth()->user()->role,
                            ['admin', 'super_admin']
                        )
                    )

                        <a
                            href="{{
                                Route::has('alumni-conversion.index')
                                    ? route('alumni-conversion.index')
                                    : '#'
                            }}"
                            class="
                                uc-nav
                                w-full
                                justify-start
                                mb-2
                            "
                        >

                            <i
                                class="
                                    fas
                                    fa-users-gear
                                    w-5
                                    text-center
                                ">
                            </i>

                            <span>
                                Alumni Requests
                            </span>

                        </a>

                    @endif

                    {{-- Logout --}}

                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="
                                uc-nav
                                w-full
                                justify-start
                                text-red-300
                                hover:bg-red-500/15
                            "
                        >

                            <i
                                class="
                                    fas
                                    fa-right-from-bracket
                                    w-5
                                    text-center
                                ">
                            </i>

                            <span>
                                Logout
                            </span>

                        </button>

                    </form>

                </div>

            </nav>

        </aside>

    </div>

    {{-- ============================================================
         MAIN PAGE CONTENT
    ============================================================ --}}

    <main
        class="
            uc-main-area
            relative
            z-10
            px-4
            lg:px-8
            pb-10
        "
    >

        <div class="max-w-[1500px] mx-auto">

            @isset($header)

                <div class="mb-8">

                    {{ $header }}

                </div>

            @endisset

            {{ $slot }}

        </div>

    </main>

</div>

</body>
</html>
