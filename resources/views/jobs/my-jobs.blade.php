<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-purple-950 to-amber-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(168,85,247,.45),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(245,158,11,.35),transparent_35%)]"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-yellow-300 font-black">Alumni Career Control</p>
                    <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">My Posted Jobs</h2>
                    <p class="mt-3 text-slate-300 max-w-2xl">
                        Track your posted opportunities, approval status, student applications and career impact.
                    </p>
                </div>

                <a href="{{ route('jobs.create') }}" class="px-6 py-4 rounded-2xl bg-gradient-to-r from-purple-500 to-pink-500 text-white font-black shadow-2xl hover:scale-105 transition">
                    <i class="fas fa-plus mr-2"></i> Post New Job
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
            transition: .35s ease;
        }

        .uc-card:hover {
            transform: translateY(-6px);
        }
    </style>

    <div class="space-y-8">

        @if(session('success'))
            <div class="uc-card p-5 text-emerald-500 font-black">
                <i class="fas fa-circle-check mr-2"></i>{{ session('success') }}
            </div>
        @endif

        <div class="uc-card p-7">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <p class="text-sm uppercase tracking-[0.25em] text-purple-500 font-black">Post Analytics</p>
                    <h3 class="text-2xl font-black mt-1">Your recruitment activity</h3>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div class="rounded-2xl bg-purple-500/15 p-4 text-center">
                        <p class="text-2xl font-black text-purple-500">{{ $jobs->total() }}</p>
                        <p class="text-xs font-bold text-slate-500">Total</p>
                    </div>
                    <div class="rounded-2xl bg-emerald-500/15 p-4 text-center">
                        <p class="text-2xl font-black text-emerald-500">{{ $jobs->where('status', 'approved')->count() }}</p>
                        <p class="text-xs font-bold text-slate-500">Approved</p>
                    </div>
                    <div class="rounded-2xl bg-amber-500/15 p-4 text-center">
                        <p class="text-2xl font-black text-amber-500">{{ $jobs->where('status', 'pending')->count() }}</p>
                        <p class="text-xs font-bold text-slate-500">Pending</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-5">
            @forelse($jobs as $job)
                <div class="uc-card p-6">
                    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                        <div class="flex items-start gap-4">
                            <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-xl">
                                <i class="fas fa-briefcase text-2xl text-white"></i>
                            </div>

                            <div>
                                <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                                    {{ $job->title }}
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $job->company_name }} • {{ $job->location ?? 'No location' }}
                                </p>

                                <div class="mt-4 flex flex-wrap gap-2">
                                    <span class="px-3 py-1 rounded-full bg-blue-500/15 text-blue-600 text-xs font-black">
                                        {{ str_replace('_', ' ', ucfirst($job->type)) }}
                                    </span>

                                    <span class="px-3 py-1 rounded-full text-xs font-black
                                        @if($job->status === 'approved') bg-emerald-500/15 text-emerald-600
                                        @elseif($job->status === 'pending') bg-amber-500/15 text-amber-600
                                        @else bg-red-500/15 text-red-600 @endif">
                                        {{ strtoupper($job->status) }}
                                    </span>

                                    <span class="px-3 py-1 rounded-full bg-purple-500/15 text-purple-600 text-xs font-black">
                                        {{ $job->applications_count ?? $job->applications()->count() }} Applications
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <a href="{{ route('jobs.show', $job) }}" class="px-5 py-3 rounded-2xl bg-slate-950 text-white font-black hover:bg-purple-600 transition">
                                View
                            </a>

                            @if($job->status === 'pending')
                                <span class="px-5 py-3 rounded-2xl bg-amber-500/15 text-amber-600 font-black">
                                    Waiting Approval
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="uc-card p-10 text-center">
                    <div class="relative z-10">
                        <div class="mx-auto h-20 w-20 rounded-3xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                            <i class="fas fa-briefcase text-3xl text-white"></i>
                        </div>

                        <h3 class="mt-6 text-2xl font-black">No job posted yet</h3>
                        <p class="mt-2 text-slate-500">Create your first opportunity for verified university students.</p>

                        <a href="{{ route('jobs.create') }}" class="inline-flex mt-6 px-6 py-3 rounded-2xl bg-gradient-to-r from-purple-500 to-pink-500 text-white font-black">
                            Post Job
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <div>
            {{ $jobs->links() }}
        </div>
    </div>
</x-app-layout>