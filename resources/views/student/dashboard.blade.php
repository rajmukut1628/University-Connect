<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-blue-950 to-indigo-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(59,130,246,.45),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(168,85,247,.35),transparent_35%)]"></div>
            <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-cyan-500/20 blur-3xl animate-pulse"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-cyan-300 font-bold">AI Career Hub</p>
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
                            <p class="text-sm font-bold text-slate-500 dark:text-slate-300">{{ $card['title'] }}</p>
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
                <div class="uc-card p-7">
                    <div class="relative z-10">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-7">
                            <div>
                                <p class="text-sm uppercase tracking-[0.25em] text-cyan-500 font-black">AI Assistant</p>
                                <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                                    Study & Career Assistant
                                </h3>
                                <p class="text-sm text-slate-500 mt-2">
                                    Ask about study plan, CV, internship, programming, career or job preparation.
                                </p>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('student.ai.study.assistant') }}" class="space-y-4">
                            @csrf

                            <textarea
                                name="question"
                                rows="4"
                                required
                                placeholder="Example: How can I prepare for internship?"
                                class="w-full rounded-3xl border border-slate-200 dark:border-white/10 bg-white/80 dark:bg-slate-950/70 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-400 focus:border-cyan-400"
                            >{{ old('question') }}</textarea>

                            @error('question')
                                <p class="text-red-500 text-sm font-semibold">{{ $message }}</p>
                            @enderror

                            <button type="submit"
                                class="px-6 py-3 rounded-2xl bg-gradient-to-r from-cyan-500 to-blue-600 text-white font-black shadow-xl hover:scale-105 transition">
                                <i class="fas fa-wand-magic-sparkles mr-2"></i>
                                Ask AI Assistant
                            </button>
                        </form>

                        @if(session('ai_answer'))
                            <div class="mt-6 rounded-3xl bg-slate-950 p-6 text-white border border-white/10">
                                <p class="text-cyan-300 text-sm font-black">Your Question</p>
                                <p class="mt-2 text-slate-200">{{ session('ai_question') }}</p>

                                <p class="text-emerald-300 text-sm font-black mt-5">AI Answer</p>
                                <p class="mt-2 text-slate-300 leading-relaxed">
                                    {{ session('ai_answer') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Recommended Opportunities --}}
                <div class="uc-card p-7">
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-7">
                        <div>
                            <p class="text-sm uppercase tracking-[0.25em] text-blue-500 font-black">AI Matchmaking</p>
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
                                <p class="text-slate-500 font-semibold">No approved jobs found yet.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                                {{-- Suggested Alumni Mentors --}}
                <div class="uc-card p-7">
                    <div class="relative z-10 flex items-center justify-between mb-6">
                        <div>
                            <p class="text-sm uppercase tracking-[0.25em] text-purple-500 font-black">Mentorship AI</p>
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
                                    <h4 class="font-black text-slate-900 dark:text-white">Best Alumni Mentor</h4>
                                    <p class="text-sm text-slate-500">Career Guidance • Interview Preparation</p>
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
                                    <h4 class="font-black text-slate-900 dark:text-white">Career Preparation</h4>
                                    <p class="text-sm text-slate-500">Portfolio • CV • LinkedIn • GitHub</p>
                                </div>
                            </div>

                            <p class="mt-4 text-sm text-slate-500">
                                Build your CV, portfolio and professional profiles to increase job opportunities.
                            </p>

                            <a href="{{ route('profile.edit') }}"
                               class="mt-5 block text-center w-full rounded-2xl bg-gradient-to-r from-blue-600 to-cyan-500 py-3 text-white font-bold">
                                Update Profile
                            </a>
                            {{-- AI Resume Analyzer Button --}}
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

                {{-- Career Score --}}
                <div class="uc-card p-7">
                    <div class="relative z-10">
                        <p class="text-sm uppercase tracking-[0.25em] text-emerald-500 font-black">Career Score</p>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mt-1">
                            Profile Strength
                        </h3>

                        <div class="mt-6 flex items-center justify-center">
                            <div class="relative h-44 w-44 rounded-full bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 p-2 uc-float">
                                <div class="h-full w-full rounded-full bg-slate-950 flex flex-col items-center justify-center text-white">
                                    <p class="text-5xl font-black">{{ $profileScore ?? 0 }}%</p>
                                    <p class="text-xs text-slate-300">
                                        {{ ($profileScore ?? 0) >= 80 ? 'Ready' : 'Improve' }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 h-3 w-full rounded-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-cyan-500"
                                 style="width: {{ $profileScore ?? 0 }}%">
                            </div>
                        </div>

                        <button class="mt-6 w-full rounded-2xl bg-gradient-to-r from-emerald-500 to-cyan-500 py-3 text-white font-black">
                            Improve Profile
                        </button>
                    </div>
                </div>

                {{-- Smart Events --}}
                <div class="uc-card p-7">
                    <div class="relative z-10">
                        <p class="text-sm uppercase tracking-[0.25em] text-pink-500 font-black">Smart Events</p>
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

                {{-- AI Suggestions --}}
                <div class="uc-card p-7">
                    <div class="relative z-10">
                        <p class="text-sm uppercase tracking-[0.25em] text-cyan-500 font-black">AI Suggestions</p>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mt-1 mb-5">
                            Career Tips
                        </h3>

                        <div class="space-y-3">
                            @foreach($aiSuggestions ?? [] as $suggestion)
                                <div class="rounded-2xl bg-slate-950 p-4 text-white border border-white/10">
                                    <p class="text-sm text-slate-300">
                                        <i class="fas fa-wand-magic-sparkles text-cyan-300 mr-2"></i>
                                        {{ $suggestion }}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>