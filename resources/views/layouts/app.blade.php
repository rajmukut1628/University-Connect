<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{
        mobileMenuOpen: false,
        profileMenuOpen: false,
        darkMode: localStorage.getItem('darkMode') === 'true'
      }"
      x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
      :class="{ 'dark': darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'University Connect') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

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

        .uc-app-shell {
            min-height: 100vh;
            background:
                radial-gradient(circle at 12% 10%, rgba(99, 102, 241, .26), transparent 28%),
                radial-gradient(circle at 85% 16%, rgba(236, 72, 153, .18), transparent 30%),
                radial-gradient(circle at 50% 85%, rgba(34, 211, 238, .14), transparent 32%),
                linear-gradient(135deg, #f8fafc, #eef2ff 42%, #fdf2f8);
        }

        .dark .uc-app-shell {
            background:
                radial-gradient(circle at 12% 10%, rgba(99, 102, 241, .22), transparent 28%),
                radial-gradient(circle at 85% 16%, rgba(236, 72, 153, .16), transparent 30%),
                radial-gradient(circle at 50% 85%, rgba(34, 211, 238, .12), transparent 32%),
                linear-gradient(135deg, #020617, #0f172a 45%, #1e1b4b);
        }

        .uc-topbar-wrap {
    position: sticky !important;
    top: 16px !important;
    left: 16px !important;
    right: 16px !important;
    z-index: 999999 !important;
    padding: 16px 16px 0 16px;
}

        .uc-topbar {
            position: relative;
            overflow: visible;
            min-height: 78px;
            border-radius: 28px;
            background:
                radial-gradient(circle at top left, rgba(99, 102, 241, .32), transparent 34%),
                radial-gradient(circle at bottom right, rgba(236, 72, 153, .22), transparent 34%),
                linear-gradient(135deg, rgba(15, 23, 42, .92), rgba(30, 27, 75, .88), rgba(88, 28, 135, .72));
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
            border-radius: 28px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .08), transparent);
            transform: translateX(-130%);
            animation: ucShine 7s ease-in-out infinite;
            pointer-events: none;
            overflow: hidden;
        }

        .uc-logo-box {
            height: 50px;
            width: 50px;
            border-radius: 20px;
            background: linear-gradient(135deg, #6366f1, #9333ea, #ec4899);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow:
                0 14px 34px rgba(147, 51, 234, .36),
                inset 0 1px 0 rgba(255, 255, 255, .24);
            flex-shrink: 0;
        }

        .uc-main-area {
            padding-top: 20px;
            min-height: 100vh;
        }

        .uc-nav-row {
            position: relative;
            z-index: 5;
            display: flex;
            align-items: center;
            gap: 10px;
            overflow-x: auto;
            overflow-y: hidden;
            padding: 10px;
            scrollbar-width: none;
        }

        .uc-nav-row::-webkit-scrollbar {
            display: none;
        }

        .uc-nav {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            min-height: 30px;
            padding: 0 10px;
            border-radius: 18px;
            font-weight: 900;
            font-size: 12px;
            color: rgb(203 213 225);
            white-space: nowrap;
            transition: all .28s ease;
            overflow: hidden;
            flex-shrink: 0;
            background: rgba(255, 255, 255, .075);
            border: 1px solid rgba(255, 255, 255, .10);
            box-shadow: 0 12px 26px rgba(15, 23, 42, .16);
            text-decoration: none;
        }

        .uc-nav::before {
            content: "";
            position: absolute;
            inset: 0;
            width: 48%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .22), transparent);
            transform: translateX(-150%);
        }

        .uc-nav:hover::before {
            animation: ucShine 1s ease;
        }

        .uc-nav:hover {
            background: rgba(255, 255, 255, .14);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 16px 38px rgba(15, 23, 42, .25);
        }

        .uc-active {
            background: linear-gradient(135deg, #4f46e5, #9333ea, #ec4899) !important;
            color: white !important;
            border-color: rgba(255, 255, 255, .20) !important;
            box-shadow: 0 18px 45px rgba(147, 51, 234, .36) !important;
            animation: ucActivePulse 3s infinite;
        }

        .uc-icon-btn {
            height: 46px;
            width: 46px;
            border-radius: 18px;
            background: rgba(255, 255, 255, .10);
            border: 1px solid rgba(255, 255, 255, .12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all .28s ease;
            box-shadow: 0 12px 28px rgba(15, 23, 42, .22);
            flex-shrink: 0;
        }

        .uc-icon-btn:hover {
            transform: translateY(-3px) scale(1.04);
            background: rgba(255, 255, 255, .16);
            box-shadow: 0 18px 42px rgba(15, 23, 42, .28);
        }

        .uc-profile-pill {
            position: relative;
            min-width: 235px;
            max-width: 280px;
            border-radius: 22px;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, .16), rgba(255, 255, 255, .07));
            border: 1px solid rgba(255, 255, 255, .13);
            box-shadow: 0 18px 45px rgba(15, 23, 42, .22);
            backdrop-filter: blur(18px);
            transition: all .28s ease;
        }

        .uc-profile-pill:hover {
            transform: translateY(-2px);
            background:
                linear-gradient(135deg, rgba(255, 255, 255, .20), rgba(255, 255, 255, .09));
        }

        .uc-role-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            height: 24px;
            padding: 0 10px;
            border-radius: 999px;
            background: rgba(34, 211, 238, .13);
            border: 1px solid rgba(34, 211, 238, .22);
            color: rgb(165 243 252);
            font-size: 10px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .uc-dropdown {
            position: absolute;
            top: calc(100% + 12px);
            right: 0;
            width: 270px;
            border-radius: 24px;
            background:
                radial-gradient(circle at top left, rgba(99, 102, 241, .26), transparent 38%),
                linear-gradient(145deg, rgba(15, 23, 42, .96), rgba(30, 27, 75, .94));
            border: 1px solid rgba(255, 255, 255, .13);
            box-shadow: 0 24px 80px rgba(2, 6, 23, .42);
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
            background: rgba(255, 255, 255, .11);
            color: white;
            transform: translateX(4px);
        }

        .premium-glass {
            background: linear-gradient(135deg, rgba(255, 255, 255, .18), rgba(255, 255, 255, .06));
            backdrop-filter: blur(22px);
            border: 1px solid rgba(255, 255, 255, .16);
            box-shadow: 0 25px 80px rgba(15, 23, 42, .20);
        }

        .dark .premium-glass {
            background: linear-gradient(135deg, rgba(15, 23, 42, .90), rgba(30, 41, 59, .66));
            border: 1px solid rgba(255, 255, 255, .10);
        }

        @media (max-width: 1023px) {
            .uc-topbar {
                min-height: auto;
                border-radius: 24px;
            }

            .uc-desktop-nav {
                display: none !important;
            }

            .uc-profile-pill {
                min-width: auto;
                max-width: none;
            }
        }

        @media (min-width: 1024px) {
            .uc-mobile-menu-btn {
                display: none !important;
            }
        }
    </style>
</head>

<body class="font-sans antialiased text-slate-900 dark:text-white overflow-x-hidden">

@php
    use Illuminate\Support\Str;

    $user = Auth::user();
    $role = $user?->role;

    $unreadCount = $user
        ? $user->notifications()->where('is_read', false)->count()
        : 0;

    $avatarUrl = null;

    if ($user && $user->profile_image) {
        $avatarUrl = Str::startsWith($user->profile_image, ['http://', 'https://'])
            ? $user->profile_image
            : asset('storage/' . $user->profile_image);
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard Route
    |--------------------------------------------------------------------------
    */
    $dashboardRoute = match ($role) {
        'super_admin' => Route::has('superadmin.dashboard')
            ? route('superadmin.dashboard')
            : route('dashboard'),

        'admin' => Route::has('admin.dashboard')
            ? route('admin.dashboard')
            : route('dashboard'),

        'student' => Route::has('student.dashboard')
            ? route('student.dashboard')
            : route('dashboard'),

        'alumni' => Route::has('alumni.dashboard')
            ? route('alumni.dashboard')
            : route('dashboard'),

        default => route('dashboard'),
    };

    /*
    |--------------------------------------------------------------------------
    | Shared Role Groups
    |--------------------------------------------------------------------------
    */
    $adminRoles = ['admin', 'super_admin'];
    $allUserRoles = ['super_admin', 'admin', 'student', 'alumni'];

    /*
    |--------------------------------------------------------------------------
    | Navigation Items
    |--------------------------------------------------------------------------
    */
    $navItems = [];

    $navItems[] = [
        'label'  => 'Dashboard',
        'icon'   => 'fa-chart-pie',
        'route'  => $dashboardRoute,
        'active' => request()->routeIs(
            'dashboard',
            'superadmin.dashboard',
            'admin.dashboard',
            'student.dashboard',
            'alumni.dashboard'
        ),
        'roles'  => $allUserRoles,
    ];

    $navItems[] = [
        'label'  => 'Newsfeed',
        'icon'   => 'fa-newspaper',
        'route'  => Route::has('newsfeed.index') ? route('newsfeed.index') : '#',
        'active' => request()->routeIs('newsfeed.*'),
        'roles'  => $allUserRoles,
    ];

    $navItems[] = [
        'label'  => 'Ask AI',
        'icon'   => 'fa-robot',
        'route'  => Route::has('ask-ai.index') ? route('ask-ai.index') : '#',
        'active' => request()->routeIs('ask-ai.*'),
        'roles'  => ['student', 'alumni'],
    ];

    $navItems[] = [
        'label'  => 'Mentors',
        'icon'   => 'fa-user-tie',
        'route'  => Route::has('mentors.index') ? route('mentors.index') : '#',
        'active' => request()->routeIs('mentors.*'),
        'roles'  => ['student', 'alumni'],
    ];

    $navItems[] = [
        'label'  => 'Jobs',
        'icon'   => 'fa-briefcase',
        'route'  => Route::has('jobs.index') ? route('jobs.index') : '#',
        'active' => request()->routeIs('jobs.*'),
        'roles'  => $allUserRoles,
    ];

    $navItems[] = [
        'label'  => 'Events',
        'icon'   => 'fa-calendar-days',
        'route'  => Route::has('events.index') ? route('events.index') : '#',
        'active' => request()->routeIs('events.*'),
        'roles'  => $allUserRoles,
    ];

    $navItems[] = [
        'label'  => 'Donations',
        'icon'   => 'fa-hand-holding-heart',
        'route'  => Route::has('donations.index') ? route('donations.index') : '#',
        'active' => request()->routeIs('donations.*'),
        'roles'  => $allUserRoles,
    ];

    $navItems[] = [
        'label'  => 'Messages',
        'icon'   => 'fa-message',
        'route'  => Route::has('messages.index') ? route('messages.index') : '#',
        'active' => request()->routeIs('messages.*'),
        'roles'  => $allUserRoles,
    ];

    /*
    |--------------------------------------------------------------------------
    | Super Admin Exclusive Navigation
    |--------------------------------------------------------------------------
    */
    if ($role === 'super_admin') {
        $navItems[] = [
            'label'  => 'Verification',
            'icon'   => 'fa-user-check',
            'route'  => Route::has('superadmin.verification.index')
                ? route('superadmin.verification.index')
                : '#',
            'active' => request()->routeIs('superadmin.verification.*'),
            'roles'  => ['super_admin'],
        ];

        $navItems[] = [
            'label'  => 'Users',
            'icon'   => 'fa-users-cog',
            'route'  => Route::has('superadmin.users.index')
                ? route('superadmin.users.index')
                : '#',
            'active' => request()->routeIs('superadmin.users.*'),
            'roles'  => ['super_admin'],
        ];

        $navItems[] = [
            'label'  => 'Verified DB',
            'icon'   => 'fa-database',
            'route'  => Route::has('superadmin.verified-users.index')
                ? route('superadmin.verified-users.index')
                : '#',
            'active' => request()->routeIs('superadmin.verified-users.*'),
            'roles'  => ['super_admin'],
        ];

        $navItems[] = [
            'label'  => 'Create Admin',
            'icon'   => 'fa-user-shield',
            'route'  => Route::has('superadmin.admins.create')
                ? route('superadmin.admins.create')
                : '#',
            'active' => request()->routeIs('superadmin.admins.*'),
            'roles'  => ['super_admin'],
        ];
    }
@endphp

<div class="uc-app-shell relative overflow-hidden">

    {{-- Animated Background --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="uc-orb absolute -top-32 -left-32 h-96 w-96 rounded-full bg-indigo-500/25 blur-3xl"></div>
        <div class="uc-orb absolute top-40 -right-32 h-96 w-96 rounded-full bg-fuchsia-500/20 blur-3xl" style="animation-delay:2s"></div>
        <div class="uc-orb absolute -bottom-32 left-1/3 h-96 w-96 rounded-full bg-cyan-500/20 blur-3xl" style="animation-delay:4s"></div>
    </div>

    {{-- Ultra Premium Horizontal Top Navbar --}}
    <header class="uc-topbar-wrap">
        <div class="uc-topbar px-4 lg:px-5 py-3">
            <div class="relative z-10 flex items-center justify-between gap-4">

                {{-- Brand --}}
                <a href="{{ $dashboardRoute }}" class="flex items-center gap-3 min-w-0 shrink-0">
                    <div class="uc-logo-box">
                        <i class="fas fa-graduation-cap text-lg text-white"></i>
                    </div>

                    <div class="leading-tight min-w-0 hidden sm:block">
                        <h1 class="text-base font-black text-white truncate">
                            University Connect
                        </h1>
                        <p class="text-xs text-slate-400 font-bold truncate">
                            AI Campus Ecosystem
                        </p>
                    </div>
                </a>

                {{-- Desktop Horizontal Role Wise Navigation --}}
                <nav class="uc-desktop-nav uc-nav-row flex-1">
                    @foreach($navItems as $item)
                        @if(in_array($role, $item['roles']))
                            <a href="{{ $item['route'] }}"
                               class="uc-nav {{ $item['active'] ? 'uc-active' : '' }}">
                                <i class="fas {{ $item['icon'] }} text-xs"></i>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endif
                    @endforeach
                </nav>

                {{-- Right Actions --}}
                <div class="flex items-center gap-2 shrink-0">

                    {{-- Profile Dropdown --}}
                    <div class="relative"
                         @click.outside="profileMenuOpen = false">

                        <button @click="profileMenuOpen = !profileMenuOpen"
                                class="uc-profile-pill p-2 flex items-center gap-3 text-left">

                            @if($avatarUrl)
                                <img src="{{ $avatarUrl }}"
                                     alt="{{ $user->name }}"
                                     class="h-11 w-11 rounded-2xl object-cover border-2 border-white/30 shadow-lg">
                            @else
                                <div class="h-11 w-11 rounded-2xl bg-gradient-to-br from-indigo-500 to-pink-500 flex items-center justify-center text-white font-black text-lg shadow-lg">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif

                            <div class="leading-tight min-w-0 flex-1 hidden sm:block">
                                <p class="font-black text-sm text-white truncate">
                                    {{ $user->name }}
                                </p>

                                <span class="uc-role-badge mt-1">
                                    <i class="fas fa-circle text-[6px]"></i>
                                    {{ strtoupper(str_replace('_', ' ', $role)) }}
                                </span>
                            </div>

                            <i class="fas fa-chevron-down text-[10px] text-slate-300"></i>
                        </button>

                        <div x-show="profileMenuOpen"
                             x-transition.opacity.scale.origin.top.right
                             class="uc-dropdown"
                             style="display: none;">

                            {{-- Notifications --}}
                            <a href="{{ Route::has('notifications.index') ? route('notifications.index') : '#' }}"
                               class="uc-dropdown-link relative">
                                <i class="fas fa-bell w-5 text-center text-pink-300"></i>
                                <span>Notifications</span>

                                @if($unreadCount > 0)
                                    <span class="ml-auto min-w-[20px] h-5 px-2 rounded-full bg-red-500 text-[10px] font-black text-white flex items-center justify-center">
                                        {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                                    </span>
                                @endif
                            </a>

                            {{-- Dark / Light Mode --}}
                            <button @click="darkMode = !darkMode"
                                    type="button"
                                    class="uc-dropdown-link w-full text-left">
                                <i x-show="!darkMode"
                                   class="fas fa-moon w-5 text-center text-cyan-300"></i>
                                <i x-show="darkMode"
                                   class="fas fa-sun w-5 text-center text-yellow-300"></i>
                                <span x-show="!darkMode">Dark Mode</span>
                                <span x-show="darkMode">Light Mode</span>
                            </button>

                            {{-- Profile --}}
                            <a href="{{ Route::has('profile.edit') ? route('profile.edit') : '#' }}"
                               class="uc-dropdown-link">
                                <i class="fas fa-user-pen w-5 text-center text-cyan-300"></i>
                                <span>Profile Settings</span>
                            </a>

                            {{-- Dashboard --}}
                            <a href="{{ $dashboardRoute }}"
                               class="uc-dropdown-link">
                                <i class="fas fa-gauge-high w-5 text-center text-indigo-300"></i>
                                <span>My Dashboard</span>
                            </a>

                            <div class="my-2 border-t border-white/10"></div>

                            {{-- Alumni Conversion Menu --}}
@if(auth()->check() && auth()->user()->role === 'student')
    <a href="{{ route('alumni-conversion.create') }}"
       class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl">
        <i class="fas fa-user-graduate mr-2 text-cyan-500"></i>
        Apply for Alumni Status
    </a>
@endif

@if(auth()->check() && in_array(auth()->user()->role, ['admin', 'super_admin']))
    <a href="{{ route('alumni-conversion.index') }}"
       class="block px-4 py-2 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl">
        <i class="fas fa-users-gear mr-2 text-emerald-500"></i>
        Alumni Conversion Requests
    </a>
@endif

                            {{-- Logout --}}
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                        class="uc-dropdown-link w-full text-left text-red-300 hover:text-white hover:bg-red-500/15">
                                    <i class="fas fa-right-from-bracket w-5 text-center"></i>
                                    <span>Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Mobile Menu Button --}}
                    <button @click="mobileMenuOpen = true"
                            class="uc-icon-btn uc-mobile-menu-btn"
                            title="Menu">
                        <i class="fas fa-bars text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    {{-- Mobile Menu --}}
    <div x-show="mobileMenuOpen"
         x-transition.opacity
         class="fixed inset-0 z-[9999999] lg:hidden">

        <div @click="mobileMenuOpen = false"
             class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm"></div>

        <aside x-transition
               class="relative h-full w-[88%] max-w-sm premium-glass p-5 rounded-r-[2rem] overflow-y-auto">

            <div class="flex items-center justify-between mb-7">
                <div class="flex items-center gap-3">
                    <div class="uc-logo-box">
                        <i class="fas fa-graduation-cap text-white"></i>
                    </div>

                    <div>
                        <h1 class="font-black text-slate-900 dark:text-white">
                            University Connect
                        </h1>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-bold">
                            AI Campus Ecosystem
                        </p>
                    </div>
                </div>

                <button @click="mobileMenuOpen = false"
                        class="h-10 w-10 rounded-xl bg-red-500/15 text-red-500 flex items-center justify-center">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>

            {{-- Mobile Navigation --}}
            <nav class="space-y-2">
                @foreach($navItems as $item)
                    @if(in_array($role, $item['roles']))
                        <a href="{{ $item['route'] }}"
                           class="uc-nav w-full justify-start {{ $item['active'] ? 'uc-active' : '' }}">
                            <i class="fas {{ $item['icon'] }} w-5 text-center"></i>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endif
                @endforeach

                <a href="{{ Route::has('profile.edit') ? route('profile.edit') : '#' }}"
                   class="uc-nav w-full justify-start {{ request()->routeIs('profile.*') ? 'uc-active' : '' }}">
                    <i class="fas fa-user-pen w-5 text-center"></i>
                    <span>Profile</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="uc-nav w-full justify-start text-red-300 hover:bg-red-500/15">
                        <i class="fas fa-right-from-bracket w-5 text-center"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </aside>
    </div>

    {{-- Main Content --}}
    <main class="uc-main-area relative z-10 px-4 lg:px-8 pb-10">
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