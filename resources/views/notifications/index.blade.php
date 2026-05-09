<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-pink-950 to-purple-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(236,72,153,.45),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(168,85,247,.35),transparent_35%)]"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-pink-300 font-black">AI Notification Center</p>
                    <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">Notifications</h2>
                    <p class="mt-3 text-slate-300 max-w-2xl">
                        Track university updates, jobs, mentorship requests, events and system alerts.
                    </p>
                </div>

                <form method="POST" action="{{ route('notifications.readAll') }}">
                    @csrf
                    @method('PATCH')
                    <button class="px-6 py-4 rounded-2xl bg-white/10 text-white font-black border border-white/10 hover:bg-white/20 transition">
                        <i class="fas fa-check-double mr-2"></i> Mark All Read
                    </button>
                </form>
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
                <p class="text-sm font-bold text-slate-500 dark:text-slate-300">Total</p>
                <h3 class="mt-3 text-4xl font-black text-pink-500">{{ $stats['total'] ?? 0 }}</h3>
            </div>

            <div class="uc-card p-6">
                <p class="text-sm font-bold text-slate-500 dark:text-slate-300">Unread</p>
                <h3 class="mt-3 text-4xl font-black text-amber-500">{{ $stats['unread'] ?? 0 }}</h3>
            </div>

            <div class="uc-card p-6">
                <p class="text-sm font-bold text-slate-500 dark:text-slate-300">High Priority</p>
                <h3 class="mt-3 text-4xl font-black text-red-500">{{ $stats['high'] ?? 0 }}</h3>
            </div>

            <div class="uc-card p-6">
                <p class="text-sm font-bold text-slate-500 dark:text-slate-300">Read</p>
                <h3 class="mt-3 text-4xl font-black text-emerald-500">{{ $stats['read'] ?? 0 }}</h3>
            </div>
        </div>

        <div class="space-y-5">
            @forelse($notifications as $notification)
                <div class="uc-card p-6 {{ !$notification->is_read ? 'ring-2 ring-pink-500/30' : '' }}">
                    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                        <div class="flex items-start gap-4">
                            <div class="h-16 w-16 rounded-2xl flex items-center justify-center shadow-xl
                                @if($notification->priority === 'high') bg-gradient-to-br from-red-500 to-pink-600
                                @elseif($notification->priority === 'low') bg-gradient-to-br from-slate-500 to-slate-700
                                @else bg-gradient-to-br from-pink-500 to-purple-600 @endif">
                                <i class="fas
                                    @if($notification->type === 'job') fa-briefcase
                                    @elseif($notification->type === 'event') fa-calendar-days
                                    @elseif($notification->type === 'mentorship') fa-handshake-angle
                                    @elseif($notification->type === 'message') fa-message
                                    @else fa-bell @endif
                                    text-2xl text-white"></i>
                            </div>

                            <div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-xl font-black text-slate-900 dark:text-white">
                                        {{ $notification->title }}
                                    </h3>

                                    @if(!$notification->is_read)
                                        <span class="px-3 py-1 rounded-full bg-pink-500/15 text-pink-600 text-xs font-black">
                                            NEW
                                        </span>
                                    @endif

                                    <span class="px-3 py-1 rounded-full
                                        @if($notification->priority === 'high') bg-red-500/15 text-red-600
                                        @elseif($notification->priority === 'low') bg-slate-500/15 text-slate-600
                                        @else bg-purple-500/15 text-purple-600 @endif
                                        text-xs font-black">
                                        {{ strtoupper($notification->priority) }}
                                    </span>
                                </div>

                                <p class="mt-2 text-sm text-slate-500 max-w-3xl">
                                    {{ $notification->message }}
                                </p>

                                <p class="mt-3 text-xs text-slate-400 font-bold">
                                    {{ $notification->created_at?->diffForHumans() }}
                                </p>
                            </div>
                        </div>

                        @if(!$notification->is_read)
                            <form method="POST" action="{{ route('notifications.read', $notification) }}">
                                @csrf
                                @method('PATCH')

                                <button class="px-5 py-3 rounded-2xl bg-gradient-to-r from-pink-500 to-purple-600 text-white font-black shadow-xl hover:scale-105 transition">
                                    Mark Read
                                </button>
                            </form>
                        @else
                            <div class="px-5 py-3 rounded-2xl bg-emerald-500/15 text-emerald-600 font-black">
                                Read
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="uc-card p-10 text-center">
                    <div class="relative z-10">
                        <div class="mx-auto h-20 w-20 rounded-3xl bg-gradient-to-br from-pink-500 to-purple-600 flex items-center justify-center">
                            <i class="fas fa-bell-slash text-3xl text-white"></i>
                        </div>

                        <h3 class="mt-6 text-2xl font-black">No notifications yet</h3>
                        <p class="mt-2 text-slate-500">Your platform updates will appear here.</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div>
            {{ $notifications->links() }}
        </div>
    </div>
</x-app-layout>