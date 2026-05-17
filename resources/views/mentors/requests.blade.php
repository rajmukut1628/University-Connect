<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-violet-950 to-fuchsia-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(139,92,246,.45),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(236,72,153,.35),transparent_35%)]"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-fuchsia-300 font-black">Mentorship Control</p>
                    <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">Student Requests</h2>
                    <p class="mt-3 text-slate-300 max-w-2xl">
                        Review, accept, or reject mentorship requests from verified students.
                    </p>
                </div>

                <a href="{{ route('mentors.index') }}" class="px-6 py-4 rounded-2xl bg-white/10 text-white font-black border border-white/10 hover:bg-white/20 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Mentors
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

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="uc-card p-6">
                <p class="text-sm font-bold text-slate-500 dark:text-slate-300">Total Requests</p>
                <h3 class="mt-3 text-4xl font-black text-purple-500">{{ $requests->count() }}</h3>
            </div>

            <div class="uc-card p-6">
                <p class="text-sm font-bold text-slate-500 dark:text-slate-300">Pending</p>
                <h3 class="mt-3 text-4xl font-black text-amber-500">{{ $requests->where('status', 'pending')->count() }}</h3>
            </div>

            <div class="uc-card p-6">
                <p class="text-sm font-bold text-slate-500 dark:text-slate-300">Accepted</p>
                <h3 class="mt-3 text-4xl font-black text-emerald-500">{{ $requests->where('status', 'accepted')->count() }}</h3>
            </div>

            <div class="uc-card p-6">
                <p class="text-sm font-bold text-slate-500 dark:text-slate-300">Rejected</p>
                <h3 class="mt-3 text-4xl font-black text-red-500">{{ $requests->where('status', 'rejected')->count() }}</h3>
            </div>
        </div>

        <div class="space-y-5">
            @forelse($requests as $requestItem)
                <div class="uc-card p-6">
                    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                        <div class="flex items-start gap-4">
                            <img
                                src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($requestItem->student?->email ?? 'student') }}"
                                class="h-20 w-20 rounded-3xl bg-white shadow-xl"
                                alt="Student"
                            >

                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                                        {{ $requestItem->student?->name ?? 'Unknown Student' }}
                                    </h3>

                                    <span class="px-3 py-1 rounded-full text-xs font-black
                                        @if($requestItem->status === 'accepted') bg-emerald-500/15 text-emerald-600
                                        @elseif($requestItem->status === 'rejected') bg-red-500/15 text-red-600
                                        @else bg-amber-500/15 text-amber-600 @endif">
                                        {{ strtoupper($requestItem->status) }}
                                    </span>
                                </div>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $requestItem->student?->email ?? 'No email found' }}
                                </p>
                                <a href="{{ route('profiles.student.show', $requestItem->student_id) }}"
   class="inline-flex items-center gap-2 mt-3 px-4 py-2 rounded-xl
          bg-gradient-to-r from-blue-500 to-cyan-500
          text-white text-sm font-black shadow-lg
          hover:scale-105 transition duration-300">
    <i class="fas fa-user-graduate"></i>
    View Student Profile
</a>

                                <p class="mt-4 font-black text-purple-500">
                                    {{ $requestItem->title ?? 'Career Mentorship Request' }}
                                </p>

                                <p class="mt-2 text-sm text-slate-500 max-w-2xl">
                                    {{ $requestItem->description ?? 'No request description provided.' }}
                                </p>

                                <p class="mt-3 text-xs text-slate-400 font-bold">
                                    Requested: {{ $requestItem->created_at?->format('d M Y, h:i A') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row lg:flex-col gap-3 min-w-[180px]">
                            @if($requestItem->status === 'pending')
                                <form method="POST" action="{{ route('mentors.accept', $requestItem) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="w-full rounded-2xl bg-gradient-to-r from-emerald-500 to-green-600 px-5 py-3 text-white font-black shadow-xl hover:scale-105 transition">
                                        Accept
                                    </button>
                                </form>

                                <form method="POST" action="{{ route('mentors.reject', $requestItem) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button class="w-full rounded-2xl bg-red-500/15 text-red-600 px-5 py-3 font-black hover:bg-red-500 hover:text-white transition">
                                        Reject
                                    </button>
                                </form>
                            @else
                                <div class="rounded-2xl bg-slate-950 text-white px-5 py-3 text-center font-black">
                                    {{ ucfirst($requestItem->status) }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="uc-card p-10 text-center">
                    <div class="relative z-10">
                        <div class="mx-auto h-20 w-20 rounded-3xl bg-gradient-to-br from-violet-500 to-fuchsia-500 flex items-center justify-center">
                            <i class="fas fa-inbox text-3xl text-white"></i>
                        </div>

                        <h3 class="mt-6 text-2xl font-black">No mentorship requests yet</h3>
                        <p class="mt-2 text-slate-500">When students request mentorship, they will appear here.</p>
                    </div>
                </div>
            @endforelse
        </div>

    </div>
</x-app-layout>