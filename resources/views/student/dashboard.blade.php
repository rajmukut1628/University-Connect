<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-blue-950 to-indigo-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(59,130,246,.45),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(168,85,247,.35),transparent_35%)]"></div>
            <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-cyan-500/20 blur-3xl animate-pulse"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-cyan-300 font-bold">
                        AI Career Hub
                    </p>

                    <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">
                        Welcome, {{ Auth::user()->name }}
                    </h2>

                    <p class="mt-3 text-slate-300 max-w-2xl">
                        Your smart student dashboard for jobs, mentorship, alumni networking and career growth.
                    </p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/10 backdrop-blur-xl px-6 py-4">
                    <p class="text-xs text-slate-300">Profile Completion</p>

                    <p class="font-bold text-emerald-300 text-2xl">
                        {{ $profileScore ?? 0 }}%
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <style>
        @keyframes ucFloat {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-14px); }
        }

        @keyframes ucScan {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        @keyframes aiGlow {
            0%, 100% {
                box-shadow:
                    0 0 25px rgba(34, 211, 238, .20),
                    0 0 60px rgba(99, 102, 241, .18),
                    0 24px 70px rgba(15, 23, 42, .25);
            }

            50% {
                box-shadow:
                    0 0 35px rgba(34, 211, 238, .35),
                    0 0 90px rgba(168, 85, 247, .25),
                    0 30px 90px rgba(15, 23, 42, .32);
            }
        }

        @keyframes aiSlideUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes aiTypingPulse {
            0%, 100% {
                opacity: .45;
                transform: scale(.92);
            }

            50% {
                opacity: 1;
                transform: scale(1);
            }
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
            transform: translateY(-8px) scale(1.01);
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

        .ai-premium-panel {
            position: relative;
            overflow: hidden;
            border-radius: 2rem;
            border: 1px solid rgba(34, 211, 238, .25);
            background:
                radial-gradient(circle at top left, rgba(34, 211, 238, .18), transparent 35%),
                radial-gradient(circle at bottom right, rgba(168, 85, 247, .20), transparent 35%),
                linear-gradient(135deg, rgba(15, 23, 42, .98), rgba(2, 6, 23, .96));
            animation: aiGlow 4s ease-in-out infinite;
        }

        .ai-premium-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(34,211,238,.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(34,211,238,.08) 1px, transparent 1px);
            background-size: 34px 34px;
            mask-image: linear-gradient(to bottom, black, transparent);
            pointer-events: none;
        }

        .ai-answer-card {
            animation: aiSlideUp .45s ease both;
        }

        .ai-answer-body {
            white-space: pre-line;
            line-height: 1.9;
            font-size: .96rem;
        }

        .ai-dot {
            height: .55rem;
            width: .55rem;
            border-radius: 9999px;
            background: rgb(34, 211, 238);
            display: inline-block;
            animation: aiTypingPulse 1.2s ease-in-out infinite;
        }

        .ai-dot:nth-child(2) {
            animation-delay: .15s;
        }

        .ai-dot:nth-child(3) {
            animation-delay: .30s;
        }
    </style>

    <div class="space-y-8">

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-6">
            @php
                $cards = [
                    [
                        'title' => 'Approved Jobs',
                        'value' => $stats['total_jobs'] ?? 0,
                        'icon' => 'fa-briefcase',
                        'from' => 'from-blue-400',
                        'to' => 'to-cyan-600'
                    ],
                    [
                        'title' => 'Events',
                        'value' => $stats['total_events'] ?? 0,
                        'icon' => 'fa-calendar-days',
                        'from' => 'from-pink-400',
                        'to' => 'to-rose-600'
                    ],
                    [
                        'title' => 'Mentorships',
                        'value' => $stats['mentorship_requests'] ?? 0,
                        'icon' => 'fa-handshake-angle',
                        'from' => 'from-violet-400',
                        'to' => 'to-purple-700'
                    ],
                    [
                        'title' => 'Messages',
                        'value' => $stats['unread_messages'] ?? 0,
                        'icon' => 'fa-message',
                        'from' => 'from-emerald-400',
                        'to' => 'to-green-600'
                    ],
                    [
                        'title' => 'Notifications',
                        'value' => $stats['unread_notifications'] ?? 0,
                        'icon' => 'fa-bell',
                        'from' => 'from-yellow-400',
                        'to' => 'to-orange-600'
                    ],
                ];
            @endphp

            @foreach ($cards as $card)
                <div class="uc-card p-6">
                    <div class="relative z-10 flex items-center justify-between">
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
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <div class="xl:col-span-2 space-y-8">
                                {{-- AI Study Assistant --}}
                <div class="ai-premium-panel p-7">
                    <div class="relative z-10">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5 mb-7">
                            <div>
                                <div class="inline-flex items-center gap-2 rounded-full border border-cyan-400/25 bg-cyan-400/10 px-4 py-2 text-cyan-200 text-xs font-black uppercase tracking-[0.25em]">
                                    <span class="relative flex h-2.5 w-2.5">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-300 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-cyan-300"></span>
                                    </span>
                                    Real AI Assistant
                                </div>

                                <h3 class="mt-4 text-3xl font-black text-white">
                                    Study, Career & Life Guidance
                                </h3>

                                <p class="mt-3 text-sm text-slate-300 max-w-2xl">
                                    Ask anything about study, CV, internship, programming, career, communication,
                                    mentorship, projects, leadership or personal growth.
                                </p>
                            </div>

                            <div class="hidden md:flex h-16 w-16 rounded-3xl bg-gradient-to-br from-cyan-400 via-blue-500 to-purple-600 items-center justify-center shadow-2xl shadow-cyan-500/30 uc-float">
                                <i class="fas fa-robot text-2xl text-white"></i>
                            </div>
                        </div>

                        <form id="aiAssistantForm" method="POST" action="{{ route('student.ai.study.assistant') }}" class="space-y-5">
                            @csrf

                            <div class="relative">
                                <textarea
                                id="aiQuestionInput"
                                    name="question"
                                    rows="5"
                                    required
                                    placeholder="Ask anything... Example: How can I improve my CV for internship?"
                                    class="w-full rounded-[1.7rem] border border-cyan-400/30 bg-white/10 text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-400 focus:border-cyan-400 p-5 pr-14 shadow-inner backdrop-blur-xl"
                                >{{ old('question') }}</textarea>

                                <div class="absolute right-4 top-4 h-10 w-10 rounded-2xl bg-cyan-400/10 border border-cyan-400/20 flex items-center justify-center text-cyan-300">
                                    <i class="fas fa-message"></i>
                                </div>
                            </div>

                            @error('question')
                                <p class="text-red-300 text-sm font-semibold">
                                    {{ $message }}
                                </p>
                            @enderror

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                                <div class="flex flex-wrap gap-2">
                                    <span class="px-3 py-1 rounded-full bg-white/10 border border-white/10 text-xs text-slate-300">
                                        CV
                                    </span>
                                    <span class="px-3 py-1 rounded-full bg-white/10 border border-white/10 text-xs text-slate-300">
                                        Internship
                                    </span>
                                    <span class="px-3 py-1 rounded-full bg-white/10 border border-white/10 text-xs text-slate-300">
                                        Study Plan
                                    </span>
                                    <span class="px-3 py-1 rounded-full bg-white/10 border border-white/10 text-xs text-slate-300">
                                        Career
                                    </span>
                                </div>

                                <button type="submit"
                                    class="group inline-flex items-center justify-center px-7 py-3.5 rounded-2xl bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-600 text-white font-black shadow-2xl shadow-cyan-500/25 hover:scale-105 transition">
                                    <i class="fas fa-wand-magic-sparkles mr-2 group-hover:rotate-12 transition"></i>
                                    Ask AI Assistant
                                </button>
                            </div>
                        </form>

                        @if(session('ai_answer'))
                            <div class="ai-answer-card mt-7 rounded-[1.8rem] border border-white/10 bg-white/10 backdrop-blur-2xl p-5 md:p-6 shadow-2xl">
                                <div class="flex items-start gap-4">
                                    <div class="h-12 w-12 shrink-0 rounded-2xl bg-gradient-to-br from-cyan-400 via-blue-500 to-purple-600 flex items-center justify-center shadow-xl">
                                        <i class="fas fa-user text-white"></i>
                                    </div>

                                    <div class="flex-1">
                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                            <p class="text-xs uppercase tracking-[0.25em] text-cyan-200 font-black">
                                                Your Question
                                            </p>

                                            <span class="text-[11px] text-slate-400">
                                                {{ now()->format('d M Y, h:i A') }}
                                            </span>
                                        </div>

                                        <div class="mt-3 rounded-2xl bg-slate-950/70 border border-white/10 p-4">
                                            <p class="text-slate-200 leading-relaxed">
                                                {{ session('ai_question') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 flex items-start gap-4">
                                    <div class="h-12 w-12 shrink-0 rounded-2xl bg-gradient-to-br from-emerald-400 via-cyan-400 to-blue-500 flex items-center justify-center shadow-xl shadow-cyan-500/20">
                                        <i class="fas fa-robot text-white"></i>
                                    </div>

                                    <div class="flex-1">
                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                                            <p class="text-xs uppercase tracking-[0.25em] text-emerald-200 font-black">
                                                AI Assistant Answer
                                            </p>

                                            <div class="flex items-center gap-1">
                                                <span class="ai-dot"></span>
                                                <span class="ai-dot"></span>
                                                <span class="ai-dot"></span>
                                            </div>
                                        </div>

                                        <div class="mt-3 rounded-2xl bg-slate-950/80 border border-emerald-400/20 p-5 max-h-[420px] overflow-y-auto">
    <p id="aiAnswerText" class="ai-answer-body text-slate-200">
    {{ session('ai_answer') }}
</p>
</div>

                                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-3">
                                            <div class="rounded-2xl bg-cyan-400/10 border border-cyan-400/20 p-3">
                                                <p class="text-xs text-cyan-200 font-black">
                                                    Smart Guidance
                                                </p>
                                                <p class="text-[11px] text-slate-400 mt-1">
                                                    Personalized response
                                                </p>
                                            </div>

                                            <div class="rounded-2xl bg-purple-400/10 border border-purple-400/20 p-3">
                                                <p class="text-xs text-purple-200 font-black">
                                                    University Focused
                                                </p>
                                                <p class="text-[11px] text-slate-400 mt-1">
                                                    Career & study support
                                                </p>
                                            </div>

                                            <div class="rounded-2xl bg-emerald-400/10 border border-emerald-400/20 p-3">
                                                <p class="text-xs text-emerald-200 font-black">
                                                    Action Based
                                                </p>
                                                <p class="text-[11px] text-slate-400 mt-1">
                                                    Practical next steps
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Recommended Opportunities --}}
                <div class="uc-card p-7">
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-7">
                        <div>
                            <p class="text-sm uppercase tracking-[0.25em] text-blue-500 font-black">
                                AI Matchmaking
                            </p>

                            <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                                Recommended Opportunities
                            </h3>

                            <p class="text-sm text-slate-500 mt-2">
                                Latest approved jobs selected for students.
                            </p>
                        </div>

                        <a href="{{ route('jobs.index') }}"
                           class="px-5 py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-cyan-500 text-white font-bold shadow-xl hover:scale-105 transition">
                            <i class="fas fa-briefcase mr-2"></i>
                            Browse Jobs
                        </a>
                    </div>

                    <div class="space-y-5">
                        @forelse($recommendedJobs as $job)
                            <div class="rounded-3xl border border-white/10 bg-gradient-to-r from-blue-500/10 to-cyan-500/10 p-5 hover:scale-[1.01] transition">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div>
                                        <div class="flex items-center gap-3">
                                            <span class="h-12 w-12 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white">
                                                <i class="fas fa-code"></i>
                                            </span>

                                            <div>
                                                <h4 class="text-lg font-black text-slate-900 dark:text-white">
                                                    {{ $job->title ?? 'Untitled Job' }}
                                                </h4>

                                                <p class="text-sm text-slate-500">
                                                    {{ $job->company_name ?? $job->company ?? 'Company not specified' }}
                                                    @if(!empty($job->location))
                                                        • {{ $job->location }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        <div class="mt-4 flex flex-wrap gap-2">
                                            @if(!empty($job->type))
                                                <span class="px-3 py-1 rounded-full bg-blue-500/15 text-blue-600 text-xs font-bold">
                                                    {{ $job->type }}
                                                </span>
                                            @endif

                                            @if(!empty($job->category))
                                                <span class="px-3 py-1 rounded-full bg-cyan-500/15 text-cyan-600 text-xs font-bold">
                                                    {{ $job->category }}
                                                </span>
                                            @endif

                                            @if(!empty($job->salary))
                                                <span class="px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-600 text-xs font-bold">
                                                    {{ $job->salary }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                                                        <div class="text-right">
                                        <p class="text-3xl font-black text-emerald-500">AI</p>
                                        <p class="text-xs text-slate-500 mb-3">Recommended</p>

                                        <a href="{{ route('jobs.show', $job->id) }}"
                                           class="inline-block px-5 py-2 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-500 text-white font-bold">
                                            Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-3xl border border-dashed border-slate-300 dark:border-white/10 p-8 text-center">
                                <p class="text-slate-500 font-semibold">
                                    No approved jobs found yet.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Suggested Alumni Mentors --}}
                <div class="uc-card p-7">
                    <div class="relative z-10 flex items-center justify-between mb-6">
                        <div>
                            <p class="text-sm uppercase tracking-[0.25em] text-purple-500 font-black">
                                Mentorship AI
                            </p>

                            <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                                Suggested Alumni Mentors
                            </h3>
                        </div>

                        <a href="{{ route('mentors.index') }}"
                           class="px-4 py-2 rounded-xl bg-purple-500/15 text-purple-600 font-bold">
                            Browse All
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                            <div class="flex items-center gap-4">
                                <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white font-black text-xl">
                                    AI
                                </div>

                                <div>
                                    <h4 class="font-black text-slate-900 dark:text-white">
                                        Best Alumni Mentor
                                    </h4>

                                    <p class="text-sm text-slate-500">
                                        Career Guidance • Interview Preparation
                                    </p>
                                </div>
                            </div>

                            <p class="mt-4 text-sm text-slate-500">
                                Connect with alumni mentors to improve your career roadmap and job preparation.
                            </p>

                            <a href="{{ route('mentors.index') }}"
                               class="mt-5 block text-center w-full rounded-2xl bg-gradient-to-r from-purple-600 to-pink-500 py-3 text-white font-bold">
                                Find Mentor
                            </a>
                        </div>

                        <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                            <div class="flex items-center gap-4">
                                <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-white font-black text-xl">
                                    CV
                                </div>

                                <div>
                                    <h4 class="font-black text-slate-900 dark:text-white">
                                        Career Preparation
                                    </h4>

                                    <p class="text-sm text-slate-500">
                                        Portfolio • CV • LinkedIn • GitHub
                                    </p>
                                </div>
                            </div>

                            <p class="mt-4 text-sm text-slate-500">
                                Build your CV, portfolio and professional profiles to increase job opportunities.
                            </p>

                            <a href="{{ route('profile.edit') }}"
                               class="mt-5 block text-center w-full rounded-2xl bg-gradient-to-r from-blue-600 to-cyan-500 py-3 text-white font-bold">
                                Update Profile
                            </a>

                            <a href="{{ route('resume-analyzer.index') }}"
                               class="mt-3 block text-center w-full rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-500 py-3 text-white font-bold shadow-xl hover:scale-105 transition">
                                <i class="fas fa-file-lines mr-2"></i>
                                AI Resume Analyzer
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right Side --}}
            <div class="space-y-8">
                {{-- Real AI Profile Strength --}}
<div class="uc-card p-7">
    <div class="relative z-10">
        <p class="text-sm uppercase tracking-[0.25em] text-emerald-400 font-black">
            Career Score
        </p>

        <h3 class="text-2xl font-black text-white mt-1">
            Profile Strength
        </h3>

        @php
            $realProfileScore = $profileStrength['score'] ?? ($profileScore ?? 0);
            $profileLevel = $profileStrength['level'] ?? 'Improve';
            $profileMessage = $profileStrength['message'] ?? 'Complete your profile to improve AI recommendations.';
            $missingItems = $profileStrength['missing'] ?? [];
        @endphp

        <div class="mt-6 flex items-center justify-center">
            <div class="relative h-44 w-44 rounded-full bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 p-2">
                <div class="h-full w-full rounded-full bg-slate-950 flex flex-col items-center justify-center text-white">
                    <p class="text-5xl font-black">
                        {{ $realProfileScore }}%
                    </p>
                    <p class="text-xs text-slate-300">
                        {{ $profileLevel }}
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-6 h-3 w-full rounded-full bg-slate-800 overflow-hidden">
            <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 via-cyan-400 to-purple-500"
                 style="width: {{ $realProfileScore }}%">
            </div>
        </div>

        <p class="mt-5 text-sm text-slate-300 leading-relaxed">
            {{ $profileMessage }}
        </p>

        @if(!empty($missingItems))
            <div class="mt-5 rounded-2xl bg-slate-950/60 border border-white/10 p-4">
                <p class="text-xs uppercase tracking-[0.2em] text-amber-300 font-black mb-3">
                    AI Detected Missing
                </p>

                <div class="flex flex-wrap gap-2">
                    @foreach(array_slice($missingItems, 0, 5) as $item)
                        <span class="px-3 py-1 rounded-full bg-amber-500/15 text-amber-300 text-xs font-bold">
                            {{ $item }}
                        </span>
                    @endforeach
                </div>
            </div>
        @endif

        <a href="{{ route('profile.edit') }}"
           class="mt-6 w-full inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-emerald-500 to-cyan-500 py-3 text-white font-black shadow-xl hover:scale-105 transition">
            <i class="fas fa-user-check mr-2"></i>
            Improve Profile
        </a>
    </div>
</div>

                {{-- Smart Events --}}
                <div class="uc-card p-7">
                    <div class="relative z-10">
                        <p class="text-sm uppercase tracking-[0.25em] text-pink-500 font-black">
                            Smart Events
                        </p>

                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mt-1 mb-5">
                            Upcoming Events
                        </h3>

                        <div class="space-y-4">
                            @forelse($recommendedEvents as $event)
                                <div class="rounded-2xl bg-white/5 border border-white/10 p-4">
                                    <p class="font-black text-slate-900 dark:text-white">
                                        {{ $event->title ?? 'Untitled Event' }}
                                    </p>

                                    <p class="text-sm text-slate-500 mt-1">
                                        {{ $event->event_date ?? $event->date ?? 'Date not specified' }}
                                    </p>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-dashed border-slate-300 dark:border-white/10 p-5">
                                    <p class="text-sm text-slate-500 font-semibold">
                                        No upcoming events found.
                                    </p>
                                </div>
                            @endforelse
                        </div>

                        <a href="{{ route('events.index') }}"
                           class="mt-5 block text-center rounded-2xl bg-gradient-to-r from-pink-500 to-rose-600 py-3 text-white font-bold">
                            View Events
                        </a>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-white/15 bg-white/10 backdrop-blur-2xl p-6 shadow-2xl">
    <div class="flex items-center justify-between gap-4 mb-6">
        <div>
            <p class="text-xs uppercase tracking-[0.35em] text-cyan-300 font-black">
                AI Suggestions
            </p>
            <h3 class="mt-2 text-2xl font-black text-white">
                Career Tips
            </h3>
        </div>

        <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-cyan-400 to-purple-500 flex items-center justify-center shadow-xl">
            <i class="fas fa-wand-magic-sparkles text-white"></i>
        </div>
    </div>

    <div class="space-y-3">
        @forelse($aiSuggestions ?? [] as $suggestion)
            @php
                $title = is_array($suggestion)
                    ? ($suggestion['title'] ?? 'AI Suggestion')
                    : ($suggestion->title ?? 'AI Suggestion');

                $description = is_array($suggestion)
                    ? ($suggestion['description'] ?? '')
                    : ($suggestion->description ?? '');

                $category = is_array($suggestion)
                    ? ($suggestion['category'] ?? 'career')
                    : ($suggestion->category ?? 'career');

                $icon = is_array($suggestion)
                    ? ($suggestion['icon'] ?? 'fa-wand-magic-sparkles')
                    : ($suggestion->icon ?? 'fa-wand-magic-sparkles');

                $score = is_array($suggestion)
                    ? (int) ($suggestion['score'] ?? 70)
                    : (int) ($suggestion->score ?? 70);

                $score = min($score, 100);
            @endphp

            <div class="group rounded-2xl bg-slate-950/75 border border-white/10 p-4 hover:border-cyan-400/50 hover:bg-slate-900/90 transition-all duration-300">
                <div class="flex items-start gap-3">
                    <div class="h-10 w-10 rounded-xl bg-cyan-400/15 flex items-center justify-center shrink-0">
                        <i class="fas {{ $icon }} text-cyan-300"></i>
                    </div>

                    <div class="flex-1">
                        <div class="flex items-center justify-between gap-3">
                            <h4 class="text-sm font-black text-white">
                                {{ $title }}
                            </h4>

                            <span class="text-[10px] px-2 py-1 rounded-full bg-purple-500/15 text-purple-300 font-black uppercase">
                                {{ $category }}
                            </span>
                        </div>

                        <p class="mt-2 text-xs leading-relaxed text-slate-300">
                            {{ $description }}
                        </p>

                        <div class="mt-3 flex items-center gap-2">
                            <div class="h-1.5 flex-1 rounded-full bg-white/10 overflow-hidden">
                                <div class="h-full rounded-full bg-gradient-to-r from-cyan-400 to-purple-500"
                                     style="width: {{ $score }}%">
                                </div>
                            </div>

                            <span class="text-[10px] text-slate-400 font-bold">
                                {{ $score }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-2xl bg-slate-950/75 border border-white/10 p-5 text-center">
                <div class="mx-auto h-14 w-14 rounded-2xl bg-cyan-400/15 flex items-center justify-center">
                    <i class="fas fa-robot text-cyan-300 text-xl"></i>
                </div>

                <h4 class="mt-4 text-white font-black">
                    No AI suggestions yet
                </h4>

                <p class="mt-2 text-sm text-slate-400">
                    Complete your profile, add skills, and upload your resume to get better AI suggestions.
                </p>
            </div>
        @endforelse
    </div>
</div>

            </div>
        </div>

    </div>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('aiAssistantForm');
    const questionInput = document.getElementById('aiQuestionInput');
    const answerText = document.getElementById('aiAnswerText');

    if (!form || !questionInput || !answerText) return;

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const question = questionInput.value.trim();
        if (!question) return;

        answerText.textContent = 'AI is thinking...';

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                answerText.textContent = data.answer;
            } else {
                answerText.textContent = data.message || 'AI Assistant failed to respond.';
            }
        } catch (error) {
            answerText.textContent = 'Network error. Please try again.';
        }
    });
});
</script>
</x-app-layout>