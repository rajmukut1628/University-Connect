<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-cyan-950 to-indigo-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(34,211,238,.45),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(99,102,241,.35),transparent_35%)]"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-cyan-300 font-black">Job Details</p>
                    <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">{{ $job->title }}</h2>
                    <p class="mt-3 text-slate-300 max-w-2xl">
                        {{ $job->company_name }} • {{ $job->location ?? 'Location not specified' }}
                    </p>
                </div>

                <a href="{{ route('jobs.index') }}" class="px-6 py-4 rounded-2xl bg-white/10 text-white font-black border border-white/10 hover:bg-white/20 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>
        </div>
    </x-slot>

    <style>
        .uc-card {
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem;
            border: 1px solid rgba(255,255,255,.16);
            background: linear-gradient(135deg, rgba(255,255,255,.16), rgba(255,255,255,.06));
            backdrop-filter: blur(22px);
            box-shadow: 0 24px 70px rgba(15,23,42,.18);
        }

        .uc-input {
            width: 100%;
            border-radius: 1rem;
            border: 1px solid rgba(255,255,255,.12);
            background: rgba(255,255,255,.10);
            padding: 0.9rem 1rem;
            color: inherit;
        }

        .uc-input:focus {
            border-color: rgb(34 211 238);
            box-shadow: 0 0 0 3px rgba(34,211,238,.25);
            outline: none;
        }
    </style>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        <div class="xl:col-span-2 space-y-6">

            @if(session('success'))
                <div class="uc-card p-5 text-emerald-500 font-black">
                    <i class="fas fa-circle-check mr-2"></i>{{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="uc-card p-5 text-red-400 font-black">
                    @foreach($errors->all() as $error)
                        <p><i class="fas fa-triangle-exclamation mr-2"></i>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="uc-card p-8">
                <div class="relative z-10">
                    <div class="flex flex-wrap gap-2 mb-6">
                        <span class="px-4 py-2 rounded-full bg-cyan-500/15 text-cyan-600 text-sm font-black">
                            {{ str_replace('_', ' ', ucfirst($job->type)) }}
                        </span>

                        <span class="px-4 py-2 rounded-full
                            @if($job->status === 'approved') bg-emerald-500/15 text-emerald-600
                            @elseif($job->status === 'pending') bg-amber-500/15 text-amber-600
                            @else bg-red-500/15 text-red-600 @endif
                            text-sm font-black">
                            {{ strtoupper($job->status) }}
                        </span>

                        @if($job->salary_range)
                            <span class="px-4 py-2 rounded-full bg-purple-500/15 text-purple-600 text-sm font-black">
                                {{ $job->salary_range }}
                            </span>
                        @endif
                    </div>

                    <h3 class="text-3xl font-black text-slate-900 dark:text-white">About this opportunity</h3>

                    <p class="mt-5 text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line">
                        {{ $job->description }}
                    </p>
                </div>
            </div>

            <div class="uc-card p-8">
                <div class="relative z-10">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                        <i class="fas fa-list-check mr-2 text-cyan-500"></i>Requirements
                    </h3>

                    <p class="mt-5 text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line">
                        {{ $job->requirements ?: 'No specific requirements added.' }}
                    </p>
                </div>
            </div>

            <div class="uc-card p-8">
                <div class="relative z-10">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                        <i class="fas fa-gift mr-2 text-fuchsia-500"></i>Benefits
                    </h3>

                    <p class="mt-5 text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line">
                        {{ $job->benefits ?: 'No benefits added.' }}
                    </p>
                </div>
            </div>

            @if(auth()->user()->isStudent() && $job->status === 'approved')
                <div class="uc-card p-8">
                    <div class="relative z-10">
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-5">
                            <i class="fas fa-paper-plane mr-2 text-emerald-500"></i>Apply for this job
                        </h3>

                        @if($alreadyApplied)
                            <div class="rounded-2xl bg-emerald-500/15 border border-emerald-500/20 p-5 text-emerald-500 font-black">
                                <i class="fas fa-circle-check mr-2"></i>You already applied for this job.
                            </div>
                        @else
                            <form method="POST" action="{{ route('jobs.apply', $job) }}" class="space-y-4">
                                @csrf

                                <textarea name="cover_letter" rows="5" class="uc-input" placeholder="Write a short cover letter..."></textarea>

                                <button class="w-full rounded-2xl bg-gradient-to-r from-cyan-500 to-emerald-500 py-4 text-white font-black shadow-xl hover:scale-[1.01] transition">
                                    Submit Application
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

        </div>

        <div class="space-y-6">

            <div class="uc-card p-7">
                <div class="relative z-10">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">Job Snapshot</h3>

                    <div class="mt-6 space-y-4">
                        <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
                            <p class="text-xs text-slate-500 font-bold">Company</p>
                            <p class="font-black">{{ $job->company_name }}</p>
                        </div>

                        <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
                            <p class="text-xs text-slate-500 font-bold">Location</p>
                            <p class="font-black">{{ $job->location ?? 'Not specified' }}</p>
                        </div>

                        <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
                            <p class="text-xs text-slate-500 font-bold">Experience</p>
                            <p class="font-black">{{ $job->experience_level ?? 'Not specified' }}</p>
                        </div>

                        <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
                            <p class="text-xs text-slate-500 font-bold">Positions</p>
                            <p class="font-black">{{ $job->positions_available }}</p>
                        </div>

                        <div class="rounded-2xl bg-white/10 border border-white/10 p-4">
                            <p class="text-xs text-slate-500 font-bold">Deadline</p>
                            <p class="font-black">
                                {{ $job->deadline ? $job->deadline->format('d M Y') : 'Open' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="uc-card p-7 bg-slate-950 text-white">
                <div class="relative z-10">
                    <p class="text-sm uppercase tracking-[0.25em] text-cyan-300 font-black">AI Match Insight</p>
                    <h3 class="mt-3 text-4xl font-black text-emerald-300">92%</h3>
                    <p class="mt-3 text-slate-300 text-sm">
                        This opportunity is highly suitable for students with Laravel, PHP, MySQL and communication skills.
                    </p>
                </div>
            </div>

            @if(auth()->user()->isAdmin() && $job->status === 'pending')
                <div class="uc-card p-7">
                    <div class="relative z-10">
                        <h3 class="text-2xl font-black mb-5">Admin Approval</h3>

                        <div class="flex gap-3">
                            <form method="POST" action="{{ route('jobs.approve', $job) }}" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button class="w-full rounded-2xl bg-emerald-500 py-3 text-white font-black">
                                    Approve
                                </button>
                            </form>

                            <form method="POST" action="{{ route('jobs.reject', $job) }}" class="flex-1">
                                @csrf
                                @method('PATCH')
                                <button class="w-full rounded-2xl bg-red-500 py-3 text-white font-black">
                                    Reject
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif

        </div>

    </div>
</x-app-layout>