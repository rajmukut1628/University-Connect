<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-purple-950 to-pink-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(168,85,247,.45),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(236,72,153,.35),transparent_35%)]"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-pink-300 font-black">
                        AI Mentor Network
                    </p>

                    <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">
                        Alumni Mentors
                    </h2>

                    <p class="mt-3 text-slate-300 max-w-2xl">
                        Connect with verified alumni mentors for career guidance, interview preparation, portfolio review and industry roadmap.
                    </p>
                </div>

                @if(auth()->user()->isAlumni())
                    <a href="{{ route('mentors.requests') }}"
                       class="px-6 py-4 rounded-2xl bg-white/10 text-white font-black border border-white/10 hover:bg-white/20 transition">
                        <i class="fas fa-inbox mr-2"></i>
                        Requests
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <style>
        @keyframes ucFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-14px); }
        }

        @keyframes ucScan {
            0% { transform: translateX(-120%); }
            100% { transform: translateX(120%); }
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
            transform: translateX(-120%);
        }

        .uc-card:hover::before {
            animation: ucScan 1.1s ease;
        }

        .uc-float {
            animation: ucFloat 5s ease-in-out infinite;
        }

        select option {
            background: #0f172a;
            color: #ffffff;
        }
    </style>

    <div class="space-y-8">

        @if(session('success'))
            <div class="uc-card p-5 text-emerald-500 font-black">
                <i class="fas fa-circle-check mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="uc-card p-5 text-red-500 font-black">
                <i class="fas fa-circle-exclamation mr-2"></i>
                {{ session('error') }}
            </div>
        @endif

        {{-- Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="uc-card p-6">
                <div class="relative z-10">
                    <p class="text-sm font-bold text-slate-500 dark:text-slate-300">Total Mentors</p>
                    <h3 class="mt-3 text-4xl font-black text-purple-500">
                        {{ $mentors->count() }}
                    </h3>
                </div>
            </div>

            <div class="uc-card p-6">
                <div class="relative z-10">
                    <p class="text-sm font-bold text-slate-500 dark:text-slate-300">Available</p>
                    <h3 class="mt-3 text-4xl font-black text-emerald-500">
                        {{ $mentors->count() }}
                    </h3>
                </div>
            </div>

            <div class="uc-card p-6">
                <div class="relative z-10">
                    <p class="text-sm font-bold text-slate-500 dark:text-slate-300">My Requests</p>
                    <h3 class="mt-3 text-4xl font-black text-cyan-500">
                        {{ $myRequests->count() }}
                    </h3>
                </div>
            </div>

            <div class="uc-card p-6">
                <div class="relative z-10">
                    <p class="text-sm font-bold text-slate-500 dark:text-slate-300">AI Match</p>
                    <h3 class="mt-3 text-4xl font-black text-pink-500">
                        Real AI
                    </h3>
                </div>
            </div>
        </div>

        {{-- Search / Filter UI --}}
        <div class="uc-card p-6">
            <form method="GET" action="{{ route('mentors.index') }}"
      class="relative z-10 grid grid-cols-1 lg:grid-cols-4 gap-4">

    <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Search mentor, company, skill..."
        class="lg:col-span-2 rounded-2xl border border-white/10 bg-white/10 backdrop-blur-xl px-5 py-4 text-slate-900 dark:text-white placeholder-slate-400 focus:border-pink-400 focus:ring-2 focus:ring-pink-400/30"
    >

    <div class="relative">
        <select name="department"
                class="w-full rounded-2xl border border-pink-400/30 bg-slate-900/95 text-white backdrop-blur-xl px-5 py-4 pr-12 font-semibold shadow-xl appearance-none focus:border-pink-400 focus:ring-2 focus:ring-pink-400/30">
            <option value="">All Departments</option>
            @foreach(['Computer Science','Pharmacy','BBA','EEE','English','Textile Engineering','Bangla','LAW'] as $dept)
                <option value="{{ $dept }}" {{ request('department') === $dept ? 'selected' : '' }}>
                    {{ $dept }}
                </option>
            @endforeach
        </select>

        <i class="fas fa-chevron-down absolute right-5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
    </div>

    <button type="submit"
            class="rounded-2xl bg-gradient-to-r from-purple-600 to-pink-500 px-6 py-4 text-white font-black shadow-xl hover:scale-105 transition duration-300">
        <i class="fas fa-search mr-2"></i>
        Search
    </button>
</form>
        </div>

        {{-- Mentor Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($mentors as $mentor)
                @php
                    $status = $myRequests[$mentor->id] ?? null;

                    $skills = $mentor->skills
                        ? collect(explode(',', $mentor->skills))->map(fn($skill) => trim($skill))->filter()->take(4)
                        : collect(['Career', 'Interview', 'Portfolio', 'Networking']);

                    $score = 88 + ($mentor->id % 10);
                @endphp

                <div class="uc-card p-6">
                    <div class="relative z-10">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex items-center gap-4">
                                @if($mentor->profile_image)
                                    <img
                                        src="{{ $mentor->getProfileImageUrl() }}"
                                        class="h-20 w-20 rounded-3xl object-cover bg-white shadow-xl uc-float"
                                        alt="{{ $mentor->name }}"
                                    >
                                @else
                                    <img
                                        src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($mentor->email) }}"
                                        class="h-20 w-20 rounded-3xl bg-white shadow-xl uc-float"
                                        alt="Mentor"
                                    >
                                @endif

                                <div>
                                    <h3 class="text-xl font-black text-slate-900 dark:text-white">
                                        {{ $mentor->name }}
                                    </h3>

                                    <p class="text-sm text-slate-500">
                                        {{ $mentor->current_designation ?: 'Verified Alumni Mentor' }}
                                    </p>

                                    <p class="text-sm font-bold text-purple-500">
                                        {{ $mentor->current_company ?: $mentor->email }}
                                    </p>
                                </div>
                            </div>

                            <span class="px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-600 text-xs font-black">
                                {{ $score }}%
                            </span>
                        </div>

                        <p class="mt-5 text-sm text-slate-500 leading-relaxed">
                            {{ $mentor->bio ?: 'Available for career guidance, project roadmap, interview preparation and university-to-industry transition support.' }}
                        </p>

                        <div class="mt-5 flex flex-wrap gap-2">
                            @foreach($skills as $skill)
                                <span class="px-3 py-1 rounded-full bg-pink-500/15 text-pink-600 text-xs font-bold">
                                    {{ $skill }}
                                </span>
                            @endforeach
                        </div>
                                                {{-- Actions --}}
                        <div class="mt-6 flex flex-col gap-3">

                            {{-- View Alumni Public Profile --}}
                            <a href="{{ route('profiles.alumni.show', $mentor->id) }}"
                               class="w-full rounded-2xl bg-gradient-to-r from-cyan-500 to-blue-600 py-3 text-white font-black shadow-xl hover:scale-105 transition text-center">
                                <i class="fas fa-user mr-2"></i>
                                View Profile
                            </a>

                            {{-- Real AI Match --}}
                            @if(auth()->user()->isStudent())
                                <form method="POST" action="{{ route('mentors.ai-match', $mentor) }}">
                                    @csrf

                                    <button type="submit"
                                            class="w-full rounded-2xl bg-gradient-to-r from-purple-600 via-indigo-600 to-pink-500 py-3 text-white font-black shadow-xl hover:scale-105 transition">
                                        <i class="fas fa-wand-magic-sparkles mr-2"></i>
                                        Real AI Match
                                    </button>
                                </form>
                            @endif

                            {{-- Request / Cancel Logic --}}
                            @if(auth()->user()->isStudent())
                                @if($status === 'pending')
                                    <div class="w-full rounded-2xl py-3 text-center font-black bg-amber-500/15 text-amber-600">
                                        Request Pending
                                    </div>

                                    <form method="POST"
                                          action="{{ route('mentors.cancel', $mentor) }}"
                                          onsubmit="return confirm('Cancel this mentorship request?');">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="w-full rounded-2xl bg-red-500 py-3 text-white font-black shadow-xl hover:bg-red-600 hover:scale-105 transition">
                                            Cancel Request
                                        </button>
                                    </form>

                                @elseif($status === 'accepted')
                                    <div class="w-full rounded-2xl py-3 text-center font-black bg-emerald-500/15 text-emerald-600">
                                        Request Accepted
                                    </div>

                                @elseif($status === 'rejected')
                                    <div class="w-full rounded-2xl py-3 text-center font-black bg-red-500/15 text-red-600">
                                        Request Rejected
                                    </div>

                                    <form method="POST" action="{{ route('mentors.request', $mentor) }}">
                                        @csrf
                                        <input type="hidden"
                                               name="description"
                                               value="I would like to request mentorship again for career guidance.">

                                        <button type="submit"
                                                class="w-full rounded-2xl bg-gradient-to-r from-purple-600 to-pink-500 py-3 text-white font-black shadow-xl hover:scale-105 transition">
                                            Request Again
                                        </button>
                                    </form>

                                @else
                                    <form method="POST" action="{{ route('mentors.request', $mentor) }}">
                                        @csrf
                                        <input type="hidden"
                                               name="description"
                                               value="I would like to request mentorship for career guidance.">

                                        <button type="submit"
                                                class="w-full rounded-2xl bg-gradient-to-r from-purple-600 to-pink-500 py-3 text-white font-black shadow-xl hover:scale-105 transition">
                                            Request Mentorship
                                        </button>
                                    </form>
                                @endif
                            @else
                                <div class="rounded-2xl bg-slate-950 text-white py-3 text-center font-black">
                                    Alumni Mentor
                                </div>
                            @endif

                            {{-- AI Match Result --}}
                            @if(session('ai_match_result') && session('ai_match_mentor_id') == $mentor->id)
                                @php
                                    $match = session('ai_match_result');
                                @endphp

                                <div class="mt-4 rounded-3xl border border-purple-500/20 bg-gradient-to-br from-purple-500/10 via-indigo-500/10 to-pink-500/10 p-5 shadow-xl">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="text-xs uppercase tracking-[0.25em] text-purple-500 font-black">
                                                Gemini AI Match
                                            </p>

                                            <h4 class="mt-2 text-3xl font-black text-slate-900 dark:text-white">
                                                {{ $match['score'] ?? 0 }}%
                                            </h4>

                                            <p class="text-sm font-bold text-pink-500">
                                                {{ $match['level'] ?? 'AI Match' }}
                                            </p>
                                        </div>

                                        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-purple-600 to-pink-500 flex items-center justify-center text-white shadow-xl">
                                            <i class="fas fa-robot text-xl"></i>
                                        </div>
                                    </div>

                                    <p class="mt-4 text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                                        {{ $match['summary'] ?? 'AI analyzed this mentor profile.' }}
                                    </p>

                                    @if(!empty($match['reasons']))
                                        <div class="mt-4">
                                            <p class="text-sm font-black text-slate-900 dark:text-white mb-2">
                                                Why this match?
                                            </p>

                                            <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-300">
                                                @foreach($match['reasons'] as $reason)
                                                    <li>• {{ $reason }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if(!empty($match['recommendations']))
                                        <div class="mt-4">
                                            <p class="text-sm font-black text-slate-900 dark:text-white mb-2">
                                                AI Recommendations
                                            </p>

                                            <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-300">
                                                @foreach($match['recommendations'] as $recommendation)
                                                    <li>• {{ $recommendation }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    @if(($match['source'] ?? null) === 'fallback')
                                        <p class="mt-4 text-xs text-amber-500 font-bold">
                                            Gemini unavailable, fallback matching was used.
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="xl:col-span-3 uc-card p-10 text-center">
                    <div class="relative z-10">
                        <div class="mx-auto h-20 w-20 rounded-3xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                            <i class="fas fa-user-tie text-3xl text-white"></i>
                        </div>

                        <h3 class="mt-6 text-2xl font-black">
                            No alumni mentors found
                        </h3>

                        <p class="mt-2 text-slate-500">
                            Register an alumni account first to appear here.
                        </p>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>