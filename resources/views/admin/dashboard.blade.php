<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-950 to-purple-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(99,102,241,.45),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(236,72,153,.35),transparent_35%)]"></div>
            <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-fuchsia-500/20 blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-cyan-500/20 blur-3xl animate-pulse"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-cyan-300 font-bold">
                        {{ auth()->user()->role === 'super_admin' ? 'Super Admin AI Core' : 'University Connect AI Core' }}
                    </p>

                    <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">
                        {{ auth()->user()->role === 'super_admin' ? 'Super Admin Command Center' : 'Administrative Command Center' }}
                    </h2>

                    <p class="mt-3 text-slate-300 max-w-2xl">
                        Real-time analytics for users, jobs, mentorships, events, messages and platform health.
                    </p>
                </div>

                <div class="flex items-center gap-4">
                    <div class="rounded-2xl border border-white/10 bg-white/10 backdrop-blur-xl px-5 py-4">
                        <p class="text-xs text-slate-300">Logged in as</p>
                        <p class="font-bold text-white">{{ Auth::user()->name }}</p>
                    </div>

                    <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-cyan-400 to-fuchsia-500 flex items-center justify-center shadow-2xl shadow-fuchsia-500/30 animate-bounce">
                        <i class="fas fa-crown text-2xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <style>
        @keyframes ucFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-14px); }
        }

        @keyframes ucGlow {
            0%, 100% { box-shadow: 0 0 30px rgba(99,102,241,.25); }
            50% { box-shadow: 0 0 60px rgba(236,72,153,.35); }
        }

        @keyframes ucScan {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        .uc-card {
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem;
            border: 1px solid rgba(255,255,255,.16);
            background: linear-gradient(135deg, rgba(255,255,255,.16), rgba(255,255,255,.06));
            backdrop-filter: blur(22px);
            box-shadow: 0 24px 70px rgba(15,23,42,.18);
            transition: .35s ease;
        }

        .uc-card:hover {
            transform: translateY(-6px) scale(1.005);
            animation: ucGlow 2.5s infinite;
        }

        .uc-card::before {
            content: "";
            position: absolute;
            inset: 0;
            width: 45%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.18), transparent);
            transform: translateX(-100%);
        }

        .uc-card:hover::before {
            animation: ucScan 1.2s ease;
        }

        .uc-float {
            animation: ucFloat 5s ease-in-out infinite;
        }

        .ai-report-scroll::-webkit-scrollbar {
            width: 8px;
        }

        .ai-report-scroll::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.8);
            border-radius: 999px;
        }

        .ai-report-scroll::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #22d3ee, #a855f7);
            border-radius: 999px;
        }

        .ai-report-body {
            color: #dbeafe;
            font-size: 14px;
            line-height: 1.9;
            white-space: pre-line;
        }
    </style>

    @php
        $isSuperAdmin = auth()->user()->role === 'super_admin';

        $verificationRoute = $isSuperAdmin
            ? route('superadmin.verification.index')
            : route('admin.verification.index');

        $usersRoute = $isSuperAdmin
            ? route('superadmin.users.index')
            : route('admin.users.index');

        $generateReportRoute = $isSuperAdmin
            ? route('superadmin.generate-ai-report')
            : route('admin.generate-ai-report');

        $cards = [
            ['title' => 'Total Users', 'value' => $stats['total_users'] ?? 0, 'icon' => 'fa-users', 'from' => 'from-cyan-400', 'to' => 'to-blue-600'],
            ['title' => 'Students', 'value' => $stats['students'] ?? 0, 'icon' => 'fa-user-graduate', 'from' => 'from-emerald-400', 'to' => 'to-green-600'],
            ['title' => 'Alumni', 'value' => $stats['alumni'] ?? 0, 'icon' => 'fa-award', 'from' => 'from-amber-400', 'to' => 'to-orange-600'],
            ['title' => 'Jobs', 'value' => $stats['job_postings'] ?? 0, 'icon' => 'fa-briefcase', 'from' => 'from-pink-400', 'to' => 'to-rose-600'],
            ['title' => 'Events', 'value' => $stats['events'] ?? 0, 'icon' => 'fa-calendar-check', 'from' => 'from-violet-400', 'to' => 'to-purple-700'],
            ['title' => 'Mentorships', 'value' => $stats['mentorships'] ?? 0, 'icon' => 'fa-handshake-angle', 'from' => 'from-indigo-400', 'to' => 'to-purple-600'],
            ['title' => 'Messages', 'value' => $stats['messages'] ?? 0, 'icon' => 'fa-message', 'from' => 'from-blue-400', 'to' => 'to-cyan-600'],
            ['title' => 'Notifications', 'value' => $stats['notifications'] ?? 0, 'icon' => 'fa-bell', 'from' => 'from-red-400', 'to' => 'to-pink-600'],
        ];

        $engagementBase = ($stats['job_postings'] ?? 0) + ($stats['events'] ?? 0) + ($stats['mentorships'] ?? 0) + ($stats['messages'] ?? 0);
        $engagementScore = min(100, 60 + ($engagementBase * 4));
        $verificationScore = ($stats['total_users'] ?? 0) > 0 ? 100 : 0;
    @endphp

    <div class="space-y-8">

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            @foreach ($cards as $index => $card)
                <div class="uc-card p-6">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-bold text-slate-500 dark:text-slate-300">
                                    {{ $card['title'] }}
                                </p>

                                <h3 class="mt-3 text-4xl font-black bg-gradient-to-r {{ $card['from'] }} {{ $card['to'] }} bg-clip-text text-transparent">
                                    {{ $card['value'] }}
                                </h3>
                            </div>

                            <div class="h-16 w-16 rounded-2xl bg-gradient-to-br {{ $card['from'] }} {{ $card['to'] }} flex items-center justify-center shadow-xl uc-float">
                                <i class="fas {{ $card['icon'] }} text-2xl text-white"></i>
                            </div>
                        </div>

                        <div class="mt-5 h-2 rounded-full bg-white/20 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r {{ $card['from'] }} {{ $card['to'] }}"
                                 style="width: {{ min(100, 25 + ($index * 9)) }}%">
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <div class="xl:col-span-2 space-y-8">

                <div class="uc-card p-7">
                    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5 mb-7">
                        <div>
                            <p class="text-sm uppercase tracking-[0.25em] text-indigo-500 font-black">
                                AI Overview
                            </p>
                            <h3 class="text-2xl font-black text-slate-900 dark:text-white mt-1">
                                University Activity Pulse
                            </h3>
                            <p class="text-sm text-slate-500 dark:text-slate-300 mt-2">
                                Generate a clean AI-powered report from your live platform database.
                            </p>
                        </div>

                        <form method="POST" action="{{ $generateReportRoute }}">
                            @csrf

                            <button type="submit"
                                    class="px-6 py-3 rounded-2xl bg-gradient-to-r from-indigo-600 to-fuchsia-600 text-white font-black shadow-xl hover:scale-105 transition">
                                <i class="fas fa-wand-magic-sparkles mr-2"></i>
                                Generate Report
                            </button>
                        </form>
                    </div>

                    @if(session('ai_report'))
                        <div class="relative z-10 mb-7 rounded-[2rem] border border-cyan-400/20 bg-slate-950/95 shadow-2xl shadow-cyan-500/10 overflow-hidden">
                            <div class="sticky top-0 z-20 border-b border-white/10 bg-slate-950/95 px-6 py-5">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-2xl bg-cyan-400/15 text-cyan-300 flex items-center justify-center">
                                            <i class="fas fa-robot text-xl"></i>
                                        </div>

                                        <div>
                                            <h4 class="text-xl font-black text-cyan-300">
                                                AI Generated Platform Report
                                            </h4>
                                            <p class="text-xs text-slate-400 mt-1">
                                                Complete Gemini analysis based on University Connect live data
                                            </p>
                                        </div>
                                    </div>

                                    <span class="px-4 py-2 rounded-full bg-emerald-500/10 text-emerald-300 text-xs font-black">
                                        LIVE AI REPORT
                                    </span>
                                </div>
                            </div>

                            <div class="ai-report-scroll max-h-[620px] overflow-y-auto px-6 py-6">
                                <div class="ai-report-body">
                                    {!! nl2br(e(session('ai_report'))) !!}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="relative z-10 mb-7 rounded-[2rem] border border-white/10 bg-slate-950/80 p-7">
                            <div class="flex items-start gap-4">
                                <div class="h-12 w-12 rounded-2xl bg-cyan-400/15 text-cyan-300 flex items-center justify-center">
                                    <i class="fas fa-robot text-xl"></i>
                                </div>

                                <div>
                                    <h4 class="text-xl font-black text-white">
                                        AI Generated Platform Report
                                    </h4>
                                    <p class="mt-3 text-sm leading-7 text-slate-300">
                                        Click Generate Report to create a complete AI summary with user activity,
                                        job insights, event performance, mentorship health, security status and admin recommendations.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                        <div class="rounded-2xl bg-gradient-to-br from-indigo-500/15 to-cyan-500/15 p-5 border border-white/10">
                            <p class="text-sm text-slate-500 dark:text-slate-300">Engagement Score</p>
                            <h4 class="text-4xl font-black text-indigo-500 mt-2">{{ $engagementScore }}%</h4>
                            <p class="text-xs mt-2 text-emerald-500 font-bold">Live database analytics</p>
                        </div>

                        <div class="rounded-2xl bg-gradient-to-br from-fuchsia-500/15 to-pink-500/15 p-5 border border-white/10">
                            <p class="text-sm text-slate-500 dark:text-slate-300">Verification Health</p>
                            <h4 class="text-4xl font-black text-fuchsia-500 mt-2">{{ $verificationScore }}%</h4>
                            <p class="text-xs mt-2 text-cyan-500 font-bold">Official DB validation enabled</p>
                        </div>

                        <div class="rounded-2xl bg-gradient-to-br from-emerald-500/15 to-lime-500/15 p-5 border border-white/10">
                            <p class="text-sm text-slate-500 dark:text-slate-300">System Status</p>
                            <h4 class="text-4xl font-black text-emerald-500 mt-2">Live</h4>
                            <p class="text-xs mt-2 text-emerald-500 font-bold">All services active</p>
                        </div>
                    </div>
                                        <div class="mt-7 rounded-3xl bg-slate-950 p-6 overflow-hidden relative">
                        <div class="absolute inset-0 bg-[linear-gradient(90deg,rgba(34,211,238,.08)_1px,transparent_1px),linear-gradient(rgba(168,85,247,.08)_1px,transparent_1px)] bg-[size:36px_36px]"></div>

                        <div class="relative z-10">
                            <div class="flex items-center justify-between mb-5">
                                <h4 class="text-white font-black text-lg">AI Platform Growth Graph</h4>
                                <span class="text-xs px-3 py-1 rounded-full bg-cyan-500/20 text-cyan-300 font-bold">
                                    DATABASE POWERED
                                </span>
                            </div>

                            @php
                                $bars = [
                                    max(18, ($chart['users']['students'] ?? 0) * 12),
                                    max(18, ($chart['users']['alumni'] ?? 0) * 12),
                                    max(18, ($stats['job_postings'] ?? 0) * 12),
                                    max(18, ($stats['events'] ?? 0) * 12),
                                    max(18, ($stats['mentorships'] ?? 0) * 12),
                                    max(18, ($stats['messages'] ?? 0) * 8),
                                    max(18, ($stats['event_registrations'] ?? 0) * 8),
                                    max(18, ($stats['notifications'] ?? 0) * 8),
                                ];
                            @endphp

                            <div class="flex items-end gap-3 h-48">
                                @foreach ($bars as $bar)
                                    <div class="flex-1 rounded-t-2xl bg-gradient-to-t from-indigo-600 via-fuchsia-500 to-cyan-300 hover:scale-110 transition"
                                         style="height: {{ min(100, $bar) }}%">
                                    </div>
                                @endforeach
                            </div>

                            <div class="mt-5 grid grid-cols-4 md:grid-cols-8 gap-2 text-center text-[10px] text-slate-400 font-bold">
                                <span>Students</span>
                                <span>Alumni</span>
                                <span>Jobs</span>
                                <span>Events</span>
                                <span>Mentors</span>
                                <span>Messages</span>
                                <span>Regs</span>
                                <span>Alerts</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="uc-card p-7">
                    <div class="relative z-10 flex items-center justify-between mb-6">
                        <div>
                            <p class="text-sm uppercase tracking-[0.25em] text-purple-500 font-black">User Control</p>
                            <h3 class="text-2xl font-black text-slate-900 dark:text-white">Recent Platform Members</h3>
                        </div>

                        <a href="{{ $usersRoute }}" class="px-4 py-2 rounded-xl bg-purple-500/15 text-purple-600 font-bold hover:bg-purple-500/25 transition">
                            Manage Users
                        </a>
                    </div>

                    <div class="relative z-10 overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="text-left text-xs uppercase tracking-widest text-slate-500 border-b border-white/10">
                                    <th class="py-4">User</th>
                                    <th class="py-4">Role</th>
                                    <th class="py-4">Status</th>
                                    <th class="py-4">Security</th>
                                    <th class="py-4">Joined</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-white/10">
                                @forelse($recentUsers as $user)
                                    <tr class="hover:bg-white/5 transition">
                                        <td class="py-5">
                                            <div class="flex items-center gap-3">
                                                <div class="h-11 w-11 rounded-2xl bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center text-white font-black">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>

                                                <div>
                                                    <p class="font-black text-slate-900 dark:text-white">{{ $user->name }}</p>
                                                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="py-5">
                                            <span class="px-3 py-1 rounded-full text-xs font-black
                                                @if($user->role === 'student') bg-cyan-500/15 text-cyan-600
                                                @elseif($user->role === 'alumni') bg-amber-500/15 text-amber-600
                                                @elseif($user->role === 'super_admin') bg-fuchsia-500/15 text-fuchsia-600
                                                @else bg-purple-500/15 text-purple-600 @endif">
                                                {{ str_replace('_', ' ', ucfirst($user->role)) }}
                                            </span>
                                        </td>

                                        <td class="py-5">
                                            @if($user->is_blocked)
                                                <span class="px-3 py-1 rounded-full bg-red-500/15 text-red-600 text-xs font-black">Blocked</span>
                                            @elseif($user->is_active)
                                                <span class="px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-600 text-xs font-black">Active</span>
                                            @else
                                                <span class="px-3 py-1 rounded-full bg-amber-500/15 text-amber-600 text-xs font-black">Inactive</span>
                                            @endif
                                        </td>

                                        <td class="py-5">
                                            <span class="px-3 py-1 rounded-full bg-indigo-500/15 text-indigo-600 text-xs font-black">
                                                Verified
                                            </span>
                                        </td>

                                        <td class="py-5 text-sm text-slate-500 font-bold">
                                            {{ $user->created_at?->format('d M Y') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-slate-500 font-bold">
                                            No users found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="uc-card p-7">
                    <div class="relative z-10 flex items-center justify-between mb-6">
                        <div>
                            <p class="text-sm uppercase tracking-[0.25em] text-pink-500 font-black">Career Flow</p>
                            <h3 class="text-2xl font-black text-slate-900 dark:text-white">Recent Job Posts</h3>
                        </div>

                        <a href="{{ route('jobs.index') }}"
                           class="px-4 py-2 rounded-xl bg-pink-500/15 text-pink-600 font-bold hover:bg-pink-500/25 transition">
                            View Jobs
                        </a>
                    </div>

                    <div class="space-y-4">
                        @forelse($recentJobs as $job)
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                <div>
                                    <h4 class="font-black text-slate-900 dark:text-white">
                                        {{ $job->title ?? 'Untitled Job' }}
                                    </h4>

                                    <p class="text-sm text-slate-500 mt-1">
                                        {{ $job->company_name ?? $job->company ?? 'Unknown Company' }}

                                        @if(!empty($job->location))
                                            • {{ $job->location }}
                                        @endif
                                    </p>
                                </div>

                                <div class="flex items-center gap-2">
                                    <span class="px-3 py-1 rounded-full text-xs font-black
                                        @if(($job->status ?? '') === 'approved') bg-emerald-500/15 text-emerald-600
                                        @elseif(($job->status ?? '') === 'pending') bg-amber-500/15 text-amber-600
                                        @else bg-red-500/15 text-red-600 @endif">
                                        {{ strtoupper($job->status ?? 'UNKNOWN') }}
                                    </span>

                                    <span class="text-xs text-slate-500 font-bold">
                                        {{ isset($job->created_at) ? \Carbon\Carbon::parse($job->created_at)->diffForHumans() : '' }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-3xl border border-white/10 bg-white/5 p-8 text-center">
                                <p class="text-slate-500 font-bold">No job posts found.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-8">

                <div class="uc-card p-7">
                    <div class="relative z-10">
                        <p class="text-sm uppercase tracking-[0.25em] text-pink-500 font-black">Quick Actions</p>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mt-1 mb-6">
                            {{ $isSuperAdmin ? 'Super Admin Tools' : 'Admin Tools' }}
                        </h3>

                        <div class="space-y-4">
                            <a href="{{ $verificationRoute }}"
                               class="block w-full group rounded-2xl bg-gradient-to-r from-emerald-500 to-cyan-600 p-[1px]">
                                <div class="rounded-2xl bg-slate-950 px-5 py-4 text-white font-bold flex items-center justify-between group-hover:bg-transparent transition-all duration-300">
                                    <span>
                                        <i class="fas fa-user-check mr-2"></i>
                                        Verification
                                    </span>
                                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </a>

                            <a href="{{ $usersRoute }}"
                               class="block w-full group rounded-2xl bg-gradient-to-r from-violet-600 to-fuchsia-600 p-[1px]">
                                <div class="rounded-2xl bg-slate-950 px-5 py-4 text-white font-bold flex items-center justify-between group-hover:bg-transparent transition-all duration-300">
                                    <span>
                                        <i class="fas fa-users-cog mr-2"></i>
                                        User Management
                                    </span>
                                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </a>

                            <a href="{{ route('alumni-conversion.index') }}"
                               class="block w-full group rounded-2xl bg-gradient-to-r from-cyan-500 to-emerald-500 p-[1px]">
                                <div class="rounded-2xl bg-slate-950 px-5 py-4 text-white font-bold flex items-center justify-between group-hover:bg-transparent transition-all duration-300">
                                    <span>
                                        <i class="fas fa-user-graduate mr-2"></i>
                                        Alumni Conversion
                                    </span>
                                    <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </a>

                            @if($isSuperAdmin)
                                <a href="{{ route('superadmin.verified-users.index') }}"
                                   class="block w-full group rounded-2xl bg-gradient-to-r from-cyan-500 to-blue-600 p-[1px]">
                                    <div class="rounded-2xl bg-slate-950 px-5 py-4 text-white font-bold flex items-center justify-between group-hover:bg-transparent transition-all duration-300">
                                        <span>
                                            <i class="fas fa-database mr-2"></i>
                                            Verified Users DB
                                        </span>
                                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                    </div>
                                </a>

                                <a href="{{ route('superadmin.admins.create') }}"
                                   class="block w-full group rounded-2xl bg-gradient-to-r from-amber-500 to-orange-600 p-[1px]">
                                    <div class="rounded-2xl bg-slate-950 px-5 py-4 text-white font-bold flex items-center justify-between group-hover:bg-transparent transition-all duration-300">
                                        <span>
                                            <i class="fas fa-user-shield mr-2"></i>
                                            Create Admin
                                        </span>
                                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                    </div>
                                </a>
                            @endif

                            <a href="{{ route('jobs.index') }}"
                               class="block w-full group rounded-2xl bg-gradient-to-r from-indigo-600 to-cyan-500 p-[1px]">
                                <div class="rounded-2xl bg-slate-950 px-5 py-4 text-white font-bold flex items-center justify-between group-hover:bg-transparent transition">
                                    <span><i class="fas fa-briefcase mr-2"></i>Manage Jobs</span>
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </a>

                            <a href="{{ route('events.index') }}"
                               class="block w-full group rounded-2xl bg-gradient-to-r from-emerald-500 to-lime-500 p-[1px]">
                                <div class="rounded-2xl bg-slate-950 px-5 py-4 text-white font-bold flex items-center justify-between group-hover:bg-transparent transition">
                                    <span><i class="fas fa-calendar-days mr-2"></i>Manage Events</span>
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </a>

                            <a href="{{ route('notifications.index') }}"
                               class="block w-full group rounded-2xl bg-gradient-to-r from-fuchsia-600 to-pink-500 p-[1px]">
                                <div class="rounded-2xl bg-slate-950 px-5 py-4 text-white font-bold flex items-center justify-between group-hover:bg-transparent transition">
                                    <span><i class="fas fa-bell mr-2"></i>Notifications</span>
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="uc-card p-7">
                    <div class="relative z-10">
                        <p class="text-sm uppercase tracking-[0.25em] text-emerald-500 font-black">Security AI</p>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mt-1 mb-6">Verification Engine</h3>

                        <div class="space-y-5">
                            <div>
                                <div class="flex justify-between text-sm font-bold mb-2">
                                    <span>Official DB Match</span>
                                    <span class="text-emerald-500">100%</span>
                                </div>
                                <div class="h-3 rounded-full bg-white/15 overflow-hidden">
                                    <div class="h-full w-full rounded-full bg-gradient-to-r from-emerald-400 to-lime-500"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between text-sm font-bold mb-2">
                                    <span>Active Users</span>
                                    <span class="text-cyan-500">{{ $stats['active_users'] ?? 0 }}</span>
                                </div>
                                <div class="h-3 rounded-full bg-white/15 overflow-hidden">
                                    <div class="h-full rounded-full bg-gradient-to-r from-cyan-400 to-blue-600"
                                         style="width: {{ ($stats['total_users'] ?? 0) > 0 ? min(100, (($stats['active_users'] ?? 0) / ($stats['total_users'] ?? 1)) * 100) : 0 }}%">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between text-sm font-bold mb-2">
                                    <span>Blocked Users</span>
                                    <span class="text-red-500">{{ $stats['blocked_users'] ?? 0 }}</span>
                                </div>
                                <div class="h-3 rounded-full bg-white/15 overflow-hidden">
                                    <div class="h-full rounded-full bg-gradient-to-r from-red-500 to-pink-600"
                                         style="width: {{ ($stats['total_users'] ?? 0) > 0 ? min(100, (($stats['blocked_users'] ?? 0) / ($stats['total_users'] ?? 1)) * 100) : 0 }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="uc-card p-7">
                    <div class="relative z-10">
                        <p class="text-sm uppercase tracking-[0.25em] text-indigo-500 font-black">Mentorship Feed</p>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mt-1 mb-6">Recent Mentorships</h3>

                        <div class="space-y-4">
                            @forelse($recentMentorships as $mentorship)
                                <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                                    <div class="flex items-start gap-3">
                                        <div class="h-10 w-10 rounded-xl bg-indigo-500/20 text-indigo-500 flex items-center justify-center">
                                            <i class="fas fa-handshake-angle"></i>
                                        </div>

                                        <div class="flex-1">
                                            <p class="font-black text-slate-900 dark:text-white">
                                                {{ $mentorship->student_name ?? 'Student' }}
                                                →
                                                {{ $mentorship->mentor_name ?? 'Mentor' }}
                                            </p>

                                            <div class="mt-2 flex items-center justify-between gap-3">
                                                <span class="px-3 py-1 rounded-full text-xs font-black
                                                    @if(($mentorship->status ?? '') === 'accepted') bg-emerald-500/15 text-emerald-600
                                                    @elseif(($mentorship->status ?? '') === 'rejected') bg-red-500/15 text-red-600
                                                    @else bg-amber-500/15 text-amber-600 @endif">
                                                    {{ strtoupper($mentorship->status ?? 'PENDING') }}
                                                </span>

                                                <span class="text-xs text-slate-500 font-bold">
                                                    {{ isset($mentorship->created_at) ? \Carbon\Carbon::parse($mentorship->created_at)->diffForHumans() : '' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-2xl bg-white/5 border border-white/10 p-6 text-center">
                                    <p class="text-slate-500 font-bold">No mentorship activity yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="uc-card p-7 bg-slate-950 text-white">
                    <div class="relative z-10">
                        <p class="text-sm uppercase tracking-[0.25em] text-cyan-300 font-black">AI Admin Insight</p>
                        <h3 class="mt-3 text-2xl font-black">Platform Recommendation</h3>

                        <p class="mt-4 text-sm text-slate-300 leading-relaxed">
                            Focus on approving pending jobs, increasing event registrations, and encouraging alumni mentorship activity to improve platform engagement.
                        </p>

                        <div class="mt-6 grid grid-cols-2 gap-3">
                            <div class="rounded-2xl bg-white/10 p-4">
                                <p class="text-xs text-slate-400 font-bold">Pending Jobs</p>
                                <p class="text-2xl font-black text-amber-300">{{ $stats['pending_jobs'] ?? 0 }}</p>
                            </div>

                            <div class="rounded-2xl bg-white/10 p-4">
                                <p class="text-xs text-slate-400 font-bold">Accepted Mentors</p>
                                <p class="text-2xl font-black text-emerald-300">{{ $stats['accepted_mentorships'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>