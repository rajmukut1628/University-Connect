<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
      x-data="{ mobileMenuOpen: false, darkMode: localStorage.getItem('darkMode') === 'true' }"
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
            0%,100% {
                transform: translateY(0) scale(1);
            }

            50% {
                transform: translateY(-18px) scale(1.035);
            }
        }

        @keyframes ucShine {
            from {
                transform: translateX(-140%);
            }

            to {
                transform: translateX(140%);
            }
        }

        @keyframes ucGlow {
            0%,100% {
                box-shadow: 0 16px 45px rgba(99,102,241,.22);
            }

            50% {
                box-shadow: 0 18px 55px rgba(236,72,153,.32);
            }
        }

        @keyframes ucActivePulse {
            0%,100% {
                box-shadow: 0 14px 32px rgba(147,51,234,.35);
            }

            50% {
                box-shadow: 0 16px 45px rgba(34,211,238,.30);
            }
        }

        .uc-orb {
            animation: ucFloat 7s ease-in-out infinite;
        }

        .uc-sidebar {
    position: fixed !important;
    top: 14px !important;
    left: 14px !important;
    bottom: 14px !important;
    width: 250px !important;
    z-index: 999999 !important;
    overflow: hidden;
    background:
        radial-gradient(circle at top left, rgba(99,102,241,.38), transparent 34%),
        radial-gradient(circle at bottom right, rgba(236,72,153,.26), transparent 38%),
        linear-gradient(160deg, rgba(15,23,42,.97), rgba(30,27,75,.93), rgba(88,28,135,.70));
    border: 1px solid rgba(255,255,255,.13);
    box-shadow: 0 24px 80px rgba(2,6,23,.45);
    backdrop-filter: blur(26px);
    animation: ucGlow 5s ease-in-out infinite;
}

        .uc-sidebar::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.06), transparent);
            transform: translateX(-120%);
            animation: ucShine 6s ease-in-out infinite;
            pointer-events: none;
        }

        .uc-logo-box {
            height: 50px;
            width: 50px;
            border-radius: 20px;
            background: linear-gradient(135deg, #6366f1, #9333ea, #ec4899);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 14px 34px rgba(147,51,234,.36);
            flex-shrink: 0;
        }

        .uc-nav {
    position: relative;
    display: flex;
    align-items: center;
    gap: 10px;
    min-height: 42px;
    padding: 0 12px;
    border-radius: 14px;
    font-weight: 900;
    font-size: 12px;
    color: rgb(203 213 225);
    white-space: nowrap;
    transition: all .28s ease;
    overflow: hidden;
    width: 100%;
}
                .uc-nav::before {
            content: "";
            position: absolute;
            inset: 0;
            width: 46%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.22), transparent);
            transform: translateX(-150%);
        }

        .uc-nav:hover::before {
            animation: ucShine 1s ease;
        }

        .uc-nav:hover {
            background: rgba(255,255,255,.10);
            color: white;
            transform: translateX(5px);
        }

        .uc-active {
            background: linear-gradient(135deg, #4f46e5, #9333ea, #ec4899);
            color: white !important;
            box-shadow: 0 14px 35px rgba(147,51,234,.35);
            animation: ucActivePulse 3s infinite;
        }

        .uc-sidebar-scroll {
            max-height: calc(100vh - 250px);
            overflow-y: auto;
            padding-right: 4px;
            scrollbar-width: none;
        }

        .uc-sidebar-scroll::-webkit-scrollbar {
            display: none;
        }

        .uc-icon-btn {
            height: 42px;
            width: 42px;
            border-radius: 16px;
            background: rgba(255,255,255,.10);
            border: 1px solid rgba(255,255,255,.12);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all .28s ease;
            box-shadow: 0 10px 26px rgba(15,23,42,.24);
            flex-shrink: 0;
        }

        .uc-icon-btn:hover {
            transform: translateY(-2px) scale(1.05);
            background: rgba(255,255,255,.16);
        }

        .uc-profile-card {
            background: linear-gradient(135deg, rgba(255,255,255,.16), rgba(255,255,255,.07));
            border: 1px solid rgba(255,255,255,.13);
            box-shadow: 0 18px 45px rgba(15,23,42,.26);
            backdrop-filter: blur(18px);
        }

        .premium-glass {
            background: linear-gradient(135deg, rgba(255,255,255,.18), rgba(255,255,255,.06));
            backdrop-filter: blur(22px);
            border: 1px solid rgba(255,255,255,.16);
            box-shadow: 0 25px 80px rgba(15,23,42,.20);
        }

        .dark .premium-glass {
            background: linear-gradient(135deg, rgba(15,23,42,.90), rgba(30,41,59,.66));
            border: 1px solid rgba(255,255,255,.10);
        }

        .uc-main-area {
    margin-left: 274px;
    min-height: 100vh;
}

        @media (max-width: 1279px) {
            .uc-sidebar {
                display: none;
            }

            .uc-main-area {
                margin-left: 0;
                padding-top: 82px;
            }
        }

        .uc-mobile-topbar {
            position: fixed !important;
            top: 14px !important;
            left: 14px !important;
            right: 14px !important;
            z-index: 999999 !important;
            background:
                radial-gradient(circle at top left, rgba(99,102,241,.38), transparent 34%),
                radial-gradient(circle at bottom right, rgba(236,72,153,.26), transparent 38%),
                linear-gradient(135deg, rgba(15,23,42,.97), rgba(30,27,75,.93), rgba(88,28,135,.70));
            border: 1px solid rgba(255,255,255,.13);
            box-shadow: 0 20px 65px rgba(2,6,23,.42);
            backdrop-filter: blur(24px);
        }
    </style>
</head>

<body class="font-sans antialiased bg-slate-100 dark:bg-slate-950 text-slate-900 dark:text-white overflow-x-hidden">

    {{-- Animated Background --}}
    <div class="fixed inset-0 pointer-events-none overflow-hidden">
        <div class="uc-orb absolute -top-32 -left-32 h-96 w-96 rounded-full bg-indigo-500/25 blur-3xl"></div>
        <div class="uc-orb absolute top-40 -right-32 h-96 w-96 rounded-full bg-fuchsia-500/20 blur-3xl" style="animation-delay:2s"></div>
        <div class="uc-orb absolute -bottom-32 left-1/3 h-96 w-96 rounded-full bg-cyan-500/20 blur-3xl" style="animation-delay:4s"></div>
    </div>
        {{-- Mobile Fixed Topbar --}}
    <div class="uc-mobile-topbar xl:hidden rounded-[1.6rem] px-4 py-3">
        <div class="flex items-center justify-between gap-3">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 min-w-0">
                <div class="uc-logo-box !h-11 !w-11 !rounded-2xl">
                    <i class="fas fa-graduation-cap text-white"></i>
                </div>


                <div class="leading-tight min-w-0">
                    <h1 class="text-sm font-black text-white truncate">
                        University Connect
                    </h1>
                    <p class="text-[11px] text-slate-400 font-bold truncate">
                        AI Campus Ecosystem
                    </p>
                </div>
            </a>
        
            <button @click="mobileMenuOpen = true"
                    class="uc-icon-btn !h-11 !w-11"
                    title="Menu">
                <i class="fas fa-bars text-sm"></i>
            </button>
        </div>
    </div>

    {{-- Fixed Left Sidebar --}}
<aside class="uc-sidebar rounded-[1.7rem] p-3">
    <div class="relative z-10 h-full flex flex-col">

            {{-- Brand --}}
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 mb-6">
                <div class="uc-logo-box">
                    <i class="fas fa-graduation-cap text-lg text-white"></i>
                </div>

                <div class="leading-tight min-w-0">
                    <h1 class="text-base font-black text-white truncate">
                        University Connect
                    </h1>
                    <p class="text-xs text-slate-400 font-bold truncate">
                        AI Campus Ecosystem
                    </p>
                </div>
            </a>

            {{-- Profile Card --}}
            <a href="{{ route('profile.edit') }}"
               class="uc-profile-card rounded-[1.4rem] p-3 mb-4 flex items-center gap-3 hover:scale-[1.02] transition-all duration-300">

                @if(Auth::user()->profile_image)
                    <img src="{{ asset('storage/' . Auth::user()->profile_image) }}"
                         alt="{{ Auth::user()->name }}"
                         class="h-12 w-12 rounded-2xl object-cover border-2 border-white/30 shadow-lg">
                @else
                    <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-pink-500 flex items-center justify-center text-white font-black text-lg shadow-lg">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif

                <div class="leading-tight min-w-0">
                    <p class="font-black text-sm text-white truncate">
                        {{ Auth::user()->name }}
                    </p>
                    <p class="text-xs text-slate-400 truncate">
                        {{ ucfirst(str_replace('_', ' ', Auth::user()->role)) }}
                    </p>
                </div>
            </a>

            {{-- Quick Actions --}}
            <div class="grid grid-cols-3 gap-2 mb-4">
                <button @click="darkMode = !darkMode"
                        class="uc-icon-btn !h-11 !w-full"
                        title="Toggle Theme">
                    <i x-show="!darkMode" class="fas fa-moon text-cyan-300 text-xs"></i>
                    <i x-show="darkMode" class="fas fa-sun text-yellow-300 text-xs"></i>
                </button>

                <a href="{{ route('notifications.index') }}"
                   class="uc-icon-btn !h-11 !w-full relative"
                   title="Notifications">
                    <i class="fas fa-bell text-pink-300 text-xs"></i>

                    @php
                        $unreadCount = auth()->user()
                            ->notifications()
                            ->where('is_read', false)
                            ->count();
                    @endphp

                    @if($unreadCount > 0)
                        <span class="absolute -top-1 -right-1 min-w-[16px] h-4 px-1 rounded-full bg-red-500 border-2 border-slate-950 text-[9px] font-black text-white flex items-center justify-center">
                            {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                        </span>
                    @endif
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="uc-icon-btn !h-11 !w-full text-red-400 hover:bg-red-500 hover:text-white"
                            title="Logout">
                        <i class="fas fa-right-from-bracket text-xs"></i>
                    </button>
                </form>
            </div>
                        {{-- Sidebar Navigation --}}
            <nav class="uc-sidebar-scroll space-y-2 flex-1">

                <a href="{{ route('dashboard') }}"
                   class="uc-nav {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') || request()->routeIs('student.dashboard') || request()->routeIs('alumni.dashboard') ? 'uc-active' : '' }}">
                    <i class="fas fa-chart-pie w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('newsfeed.index') }}"
   class="uc-nav {{ request()->routeIs('newsfeed.*') ? 'uc-active' : '' }}">
    <i class="fas fa-newspaper w-5 text-center"></i>
    <span>Newsfeed</span>
</a>

                @if(auth()->user()->role === 'student')
                    <a href="{{ route('resume-analyzer.index') }}"
                       class="uc-nav {{ request()->routeIs('resume-analyzer.*') ? 'uc-active' : '' }}">
                        <i class="fas fa-file-lines w-5 text-center"></i>
                        <span>Resume AI</span>
                    </a>

                    <a href="{{ route('mentors.index') }}"
                       class="uc-nav {{ request()->routeIs('mentors.*') ? 'uc-active' : '' }}">
                        <i class="fas fa-user-tie w-5 text-center"></i>
                        <span>Mentors</span>
                    </a>
                @endif

                <a href="{{ route('jobs.index') }}"
                   class="uc-nav {{ request()->routeIs('jobs.*') ? 'uc-active' : '' }}">
                    <i class="fas fa-briefcase w-5 text-center"></i>
                    <span>Jobs</span>
                </a>

                <a href="{{ route('events.index') }}"
                   class="uc-nav {{ request()->routeIs('events.*') ? 'uc-active' : '' }}">
                    <i class="fas fa-calendar-days w-5 text-center"></i>
                    <span>Events</span>
                </a>

                <a href="{{ route('donations.index') }}"
                   class="uc-nav {{ request()->routeIs('donations.*') ? 'uc-active' : '' }}">
                    <i class="fas fa-hand-holding-heart w-5 text-center"></i>
                    <span>Donations</span>
                </a>

                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.users.index') }}"
                       class="uc-nav {{ request()->routeIs('admin.users.*') ? 'uc-active' : '' }}">
                        <i class="fas fa-users-gear w-5 text-center"></i>
                        <span>Users</span>
                    </a>

                    <a href="{{ route('admin.verified-users.index') }}"
                       class="uc-nav {{ request()->routeIs('admin.verified-users.*') ? 'uc-active' : '' }}">
                        <i class="fas fa-database w-5 text-center"></i>
                        <span>Verified</span>
                    </a>

                    <a href="{{ route('event.participants.pending') }}"
                       class="uc-nav {{ request()->routeIs('event.participants.pending') ? 'uc-active' : '' }}">
                        <i class="fas fa-clipboard-check w-5 text-center"></i>
                        <span>Requests</span>
                    </a>

                    <a href="{{ route('admin.verification.index') }}"
                       class="uc-nav {{ request()->routeIs('admin.verification.*') ? 'uc-active' : '' }}">
                        <i class="fas fa-shield-halved w-5 text-center"></i>
                        <span>Verify</span>
                    </a>
                @endif

            

                <a href="{{ route('messages.index') }}"
                   class="uc-nav {{ request()->routeIs('messages.*') ? 'uc-active' : '' }}">
                    <i class="fas fa-message w-5 text-center"></i>
                    <span>Messages</span>
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="uc-nav {{ request()->routeIs('profile.*') ? 'uc-active' : '' }}">
                    <i class="fas fa-user-pen w-5 text-center"></i>
                    <span>Profile</span>
                </a>
            </nav>
        </div>
    </aside>

    {{-- Mobile Menu --}}
    <div x-show="mobileMenuOpen"
         x-transition.opacity
         class="fixed inset-0 z-[9999999] xl:hidden">

        <div @click="mobileMenuOpen = false"
             class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm"></div>

        <aside x-transition
               class="relative h-full w-80 premium-glass p-5 rounded-r-[2rem] overflow-y-auto">

            <div class="flex items-center justify-between mb-8">
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

            <nav class="space-y-3">
                <a href="{{ route('dashboard') }}"
                   class="uc-nav {{ request()->routeIs('dashboard') || request()->routeIs('admin.dashboard') || request()->routeIs('student.dashboard') || request()->routeIs('alumni.dashboard') ? 'uc-active' : '' }}">
                    <i class="fas fa-chart-pie w-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('newsfeed.index') }}"
   class="uc-nav {{ request()->routeIs('newsfeed.*') ? 'uc-active' : '' }}">
    <i class="fas fa-newspaper w-5"></i>
    <span>Newsfeed</span>
</a>

                <a href="{{ route('jobs.index') }}"
                   class="uc-nav {{ request()->routeIs('jobs.*') ? 'uc-active' : '' }}">
                    <i class="fas fa-briefcase w-5"></i>
                    <span>Jobs</span>
                </a>

                <a href="{{ route('events.index') }}"
                   class="uc-nav {{ request()->routeIs('events.*') ? 'uc-active' : '' }}">
                    <i class="fas fa-calendar-days w-5"></i>
                    <span>Events</span>
                </a>

                <a href="{{ route('donations.index') }}"
                   class="uc-nav {{ request()->routeIs('donations.*') ? 'uc-active' : '' }}">
                    <i class="fas fa-hand-holding-heart w-5"></i>
                    <span>Donations</span>
                </a>

                <a href="{{ route('messages.index') }}"
                   class="uc-nav {{ request()->routeIs('messages.*') ? 'uc-active' : '' }}">
                    <i class="fas fa-message w-5"></i>
                    <span>Messages</span>
                </a>

                <a href="{{ route('notifications.index') }}"
                   class="uc-nav {{ request()->routeIs('notifications.*') ? 'uc-active' : '' }}">
                    <i class="fas fa-bell w-5"></i>
                    <span>Notifications</span>
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="uc-nav {{ request()->routeIs('profile.*') ? 'uc-active' : '' }}">
                    <i class="fas fa-user-pen w-5"></i>
                    <span>Profile</span>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="uc-nav text-red-400 hover:bg-red-500/15">
                        <i class="fas fa-right-from-bracket w-5"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </nav>
        </aside>
    </div>

    {{-- Main Content --}}
    <main class="uc-main-area relative z-10 px-5 py-8">
        @isset($header)
            <div class="mb-8">
                {{ $header }}
            </div>
        @endisset

        {{ $slot }}
    </main>

</body>
</html>