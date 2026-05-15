<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-purple-950 to-amber-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(168,85,247,.45),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(245,158,11,.35),transparent_35%)]"></div>
            <div class="absolute -top-24 -right-24 h-72 w-72 rounded-full bg-yellow-500/20 blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-fuchsia-500/20 blur-3xl animate-pulse"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-yellow-300 font-bold">Alumni Impact Hub</p>
                    <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">
                        Welcome Back, {{ Auth::user()->name }}
                    </h2>
                    <p class="mt-3 text-slate-300 max-w-2xl">
                        Guide students, post opportunities, grow your alumni network and create measurable career impact.
                    </p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/10 backdrop-blur-xl px-6 py-4">
                    <p class="text-xs text-slate-300">Contribution Score</p>
                    <p class="font-bold text-yellow-300 text-2xl">
                        <i class="fas fa-star mr-2"></i>{{ $contributionScore ?? 0 }}%
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

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-6">
            @php
                $cards = [
                    [
                        'title' => 'My Jobs',
                        'value' => $stats['my_jobs'] ?? 0,
                        'icon' => 'fa-briefcase',
                        'from' => 'from-purple-400',
                        'to' => 'to-fuchsia-600'
                    ],
                    [
                        'title' => 'Approved',
                        'value' => $stats['approved_jobs'] ?? 0,
                        'icon' => 'fa-circle-check',
                        'from' => 'from-emerald-400',
                        'to' => 'to-green-600'
                    ],
                    [
                        'title' => 'Pending',
                        'value' => $stats['pending_jobs'] ?? 0,
                        'icon' => 'fa-clock',
                        'from' => 'from-amber-400',
                        'to' => 'to-orange-600'
                    ],
                    [
                        'title' => 'Mentorships',
                        'value' => $stats['mentorship_requests'] ?? 0,
                        'icon' => 'fa-users',
                        'from' => 'from-blue-400',
                        'to' => 'to-cyan-600'
                    ],
                    [
                        'title' => 'Messages',
                        'value' => $stats['unread_messages'] ?? 0,
                        'icon' => 'fa-envelope-open-text',
                        'from' => 'from-pink-400',
                        'to' => 'to-rose-600'
                    ],
                    [
                        'title' => 'Alerts',
                        'value' => $stats['unread_notifications'] ?? 0,
                        'icon' => 'fa-bell',
                        'from' => 'from-indigo-400',
                        'to' => 'to-blue-700'
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

                {{-- Alumni Profile Overview --}}
                <div class="uc-card p-7">
                    <div class="relative z-10">
                        <div class="relative overflow-hidden rounded-3xl h-56 bg-slate-950">
                            <img
                                src="https://images.unsplash.com/photo-1497366754035-f200968a6e72?w=1200"
                                class="h-full w-full object-cover opacity-70"
                                alt="Alumni cover"
                            >

                            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent"></div>

                            <div class="absolute bottom-5 left-5 right-5 flex items-end justify-between gap-4">
                                <div class="flex items-end gap-4">
                                    <img
                                        src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode(Auth::user()->email) }}"
                                        class="h-24 w-24 rounded-3xl bg-white border-4 border-white shadow-2xl"
                                        alt="Profile"
                                    >

                                    <div>
                                        <h3 class="text-3xl font-black text-white">
                                            {{ Auth::user()->name }}
                                        </h3>
                                        <p class="text-slate-300">
                                            {{ Auth::user()->department ?? 'Alumni Member' }}
                                            @if(!empty(Auth::user()->batch))
                                                • Batch {{ Auth::user()->batch }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <span class="hidden md:inline-flex px-4 py-2 rounded-full bg-yellow-400/20 text-yellow-200 font-black border border-yellow-300/20">
                                    <i class="fas fa-star mr-2"></i>AI Mentor
                                </span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                            <div class="rounded-2xl bg-purple-500/10 border border-white/10 p-4">
                                <p class="text-sm text-slate-500 dark:text-slate-300">Department</p>
                                <p class="font-black text-slate-900 dark:text-white">
                                    {{ Auth::user()->department ?? 'Not Added' }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-amber-500/10 border border-white/10 p-4">
                                <p class="text-sm text-slate-500 dark:text-slate-300">Batch</p>
                                <p class="font-black text-slate-900 dark:text-white">
                                    {{ Auth::user()->batch ?? 'Not Added' }}
                                </p>
                            </div>

                            <div class="rounded-2xl bg-emerald-500/10 border border-white/10 p-4">
                                <p class="text-sm text-slate-500 dark:text-slate-300">Impact Score</p>
                                <p class="font-black text-emerald-500">
                                    {{ $contributionScore ?? 0 }}%
                                </p>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <a href="{{ route('profile.edit') }}"
                               class="px-5 py-3 rounded-2xl bg-gradient-to-r from-purple-600 to-fuchsia-600 text-white font-bold shadow-xl hover:scale-105 transition">
                                <i class="fas fa-user-edit mr-2"></i>Edit Profile
                            </a>

                            <a href="{{ route('jobs.create') }}"
                               class="px-5 py-3 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold shadow-xl hover:scale-105 transition">
                                <i class="fas fa-plus mr-2"></i>Post New Job
                            </a>

                            <a href="{{ route('mentors.requests') }}"
                               class="px-5 py-3 rounded-2xl bg-slate-950 text-white font-bold shadow-xl hover:scale-105 transition">
                                <i class="fas fa-handshake-angle mr-2"></i>Mentorship Requests
                            </a>
                        </div>
                    </div>
                </div>

                {{-- My Opportunity Posts --}}
                <div class="uc-card p-7">
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-7">
                        <div>
                            <p class="text-sm uppercase tracking-[0.25em] text-purple-500 font-black">Recruitment Engine</p>
                            <h3 class="text-2xl font-black text-slate-900 dark:text-white">My Opportunity Posts</h3>
                            <p class="text-sm text-slate-500 mt-2">
                                Your recently posted jobs and internship opportunities.
                            </p>
                        </div>

                        <a href="{{ route('jobs.create') }}"
                           class="px-5 py-3 rounded-2xl bg-gradient-to-r from-purple-600 to-pink-500 text-white font-bold shadow-xl hover:scale-105 transition">
                            <i class="fas fa-plus mr-2"></i>Post New Job
                        </a>
                    </div>

                    <div class="space-y-5">
                        @forelse($myJobs as $job)
                            <div class="rounded-3xl border border-white/10 bg-gradient-to-r from-purple-500/10 to-pink-500/10 p-5">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div>
                                        <h4 class="text-lg font-black text-slate-900 dark:text-white">
                                            {{ $job->title ?? 'Untitled Job' }}
                                        </h4>

                                        <p class="text-sm text-slate-500">
                                            {{ $job->location ?? 'Location not specified' }}
                                            @if(!empty($job->type))
                                                • {{ $job->type }}
                                            @endif
                                        </p>

                                        <div class="mt-4 flex flex-wrap gap-2">
                                            <span class="px-3 py-1 rounded-full
                                                {{ ($job->status ?? 'pending') === 'approved'
                                                    ? 'bg-emerald-500/15 text-emerald-600'
                                                    : (($job->status ?? 'pending') === 'rejected'
                                                        ? 'bg-red-500/15 text-red-600'
                                                        : 'bg-yellow-500/15 text-yellow-600') }}
                                                text-xs font-bold">
                                                {{ ucfirst($job->status ?? 'Pending') }}
                                            </span>

                                            @if(!empty($job->category))
                                                <span class="px-3 py-1 rounded-full bg-blue-500/15 text-blue-600 text-xs font-bold">
                                                    {{ $job->category }}
                                                </span>
                                            @endif

                                            @if(!empty($job->salary))
                                                <span class="px-3 py-1 rounded-full bg-purple-500/15 text-purple-600 text-xs font-bold">
                                                    {{ $job->salary }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex gap-2">
                                        <a href="{{ route('jobs.show', $job->id) }}"
                                           class="h-11 w-11 rounded-xl bg-slate-950 text-white hover:bg-purple-600 transition flex items-center justify-center">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-3xl border border-dashed border-slate-300 dark:border-white/10 p-8 text-center">
                                <p class="text-slate-500 font-semibold">You have not posted any job yet.</p>

                                <a href="{{ route('jobs.create') }}"
                                   class="mt-5 inline-block px-5 py-3 rounded-2xl bg-gradient-to-r from-purple-600 to-pink-500 text-white font-bold">
                                    Post First Job
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
                                {{-- Mentorship Requests --}}
                <div class="uc-card p-7">
                    <div class="relative z-10">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                            <div>
                                <p class="text-sm uppercase tracking-[0.25em] text-amber-500 font-black">Mentorship Board</p>
                                <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                                    Student Requests
                                </h3>
                            </div>

                            <a href="{{ route('mentors.requests') }}"
                               class="px-4 py-2 rounded-xl bg-amber-500/15 text-amber-600 font-bold">
                                View All
                            </a>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            @forelse($mentorshipRequests as $request)
                                <div class="rounded-3xl border border-white/10 bg-white/5 p-5">
                                    <div class="flex items-center gap-4">
                                        <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white font-black">
                                            ST
                                        </div>

                                        <div>
                                            <h4 class="font-black text-slate-900 dark:text-white">
                                                Student Request
                                            </h4>
                                            <p class="text-sm text-slate-500">
                                                Status: {{ ucfirst($request->status ?? 'pending') }}
                                            </p>
                                        </div>
                                    </div>

                                    <p class="mt-4 text-sm text-slate-500">
                                        A student requested mentorship support from you.
                                    </p>

                                    <div class="mt-5 flex gap-2">
                                        <a href="{{ route('mentors.requests') }}"
                                           class="flex-1 text-center rounded-2xl bg-gradient-to-r from-emerald-500 to-green-600 py-3 text-white font-bold">
                                            Manage
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="md:col-span-2 rounded-3xl border border-dashed border-slate-300 dark:border-white/10 p-8 text-center">
                                    <p class="text-slate-500 font-semibold">
                                        No mentorship requests found yet.
                                    </p>
                                </div>
                            @endforelse
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

                {{-- AI Suggestions --}}
<div class="uc-card p-7">
    <div class="relative z-10">
        <div class="flex items-center justify-between gap-4 mb-6">
            <div>
                <p class="text-sm uppercase tracking-[0.25em] text-cyan-500 font-black">
                    AI Suggestions
                </p>
                <h3 class="text-2xl font-black text-slate-900 dark:text-white mt-1">
                    What to do next
                </h3>
                <p class="text-sm text-slate-500 mt-2">
                    Personalized recommendations generated from your profile,
                    contribution activity, mentorship, and job posting history.
                </p>
            </div>

            <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-cyan-400 to-purple-600 flex items-center justify-center shadow-xl uc-float">
                <i class="fas fa-wand-magic-sparkles text-white text-xl"></i>
            </div>
        </div>

        <div class="space-y-4">
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

                    $score = min(max($score, 0), 100);
                @endphp

                <div class="group rounded-3xl bg-white/5 border border-white/10 p-5 hover:border-cyan-400/40 hover:bg-white/10 transition-all duration-300">
                    <div class="flex items-start gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-cyan-500/15 flex items-center justify-center shrink-0">
                            <i class="fas {{ $icon }} text-cyan-400"></i>
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-3">
                                <h4 class="font-black text-slate-900 dark:text-white leading-snug">
                                    {{ $title }}
                                </h4>

                                <span class="shrink-0 px-2.5 py-1 rounded-full bg-purple-500/15 text-purple-500 text-[10px] font-black uppercase">
                                    {{ $category }}
                                </span>
                            </div>

                            <p class="text-sm text-slate-500 mt-2 leading-relaxed">
                                {{ $description }}
                            </p>

                            <div class="mt-4 flex items-center gap-3">
                                <div class="flex-1 h-2 rounded-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
                                    <div
                                        class="h-full rounded-full bg-gradient-to-r from-cyan-400 to-purple-600"
                                        style="width: {{ $score }}%">
                                    </div>
                                </div>

                                <span class="text-xs font-bold text-slate-500">
                                    {{ $score }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="rounded-3xl border border-dashed border-slate-300 dark:border-white/10 p-8 text-center">
                    <div class="mx-auto h-16 w-16 rounded-3xl bg-cyan-500/10 flex items-center justify-center">
                        <i class="fas fa-robot text-cyan-500 text-2xl"></i>
                    </div>

                    <h4 class="mt-4 font-black text-slate-900 dark:text-white">
                        No AI Suggestions Yet
                    </h4>

                    <p class="mt-2 text-sm text-slate-500">
                        Complete your profile, post jobs, and engage in mentorship
                        to receive smarter AI recommendations.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>

                {{-- Recommended Students --}}
                <div class="uc-card p-7">
                    <div class="relative z-10">
                        <p class="text-sm uppercase tracking-[0.25em] text-emerald-500 font-black">AI Candidate Match</p>
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mt-1 mb-5">
                            Recommended Students
                        </h3>

                        <div class="space-y-4">
                            @forelse($recommendedStudents as $student)
                                <div class="rounded-2xl bg-slate-950 p-4 text-white">
                                    <div class="flex items-center gap-3">
                                        <div class="h-11 w-11 rounded-xl bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center font-black">
                                            {{ strtoupper(substr($student->name ?? 'S', 0, 1)) }}
                                        </div>

                                        <div>
                                            <p class="font-black">{{ $student->name ?? 'Student' }}</p>
                                            <p class="text-xs text-slate-400">
                                                {{ $student->department ?? 'Department not added' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="rounded-2xl border border-dashed border-slate-300 dark:border-white/10 p-5">
                                    <p class="text-sm text-slate-500 font-semibold">
                                        No student found.
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>