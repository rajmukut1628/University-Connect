<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-[2.25rem] bg-gradient-to-r from-slate-950 via-indigo-950 to-cyan-950 p-8 lg:p-10 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(99,102,241,.45),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(34,211,238,.35),transparent_35%)]"></div>
            <div class="absolute -top-20 -right-20 h-72 w-72 rounded-full bg-cyan-500/20 blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-20 -left-20 h-72 w-72 rounded-full bg-fuchsia-500/15 blur-3xl animate-pulse"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-cyan-300 font-black">
                        AI Message Center
                    </p>

                    <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white tracking-tight">
                        Inbox
                    </h2>

                    <p class="mt-3 text-slate-300 max-w-2xl leading-relaxed">
                        Connect with students and alumni through a premium real-time messaging experience.
                    </p>
                </div>

                <div class="hidden lg:flex items-center gap-4">
                    <div class="h-20 w-20 rounded-[2rem] bg-gradient-to-br from-cyan-400 via-blue-500 to-purple-600 flex items-center justify-center shadow-2xl shadow-cyan-500/30">
                        <i class="fas fa-comments text-3xl text-white"></i>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <style>
        @keyframes msgFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        @keyframes msgShine {
            0% { transform: translateX(-120%); }
            100% { transform: translateX(120%); }
        }

        .msg-card {
            position: relative;
            overflow: hidden;
            border-radius: 2rem;
            border: 1px solid rgba(255,255,255,.14);
            background: linear-gradient(135deg, rgba(255,255,255,.14), rgba(255,255,255,.05));
            backdrop-filter: blur(24px);
            box-shadow:
                0 24px 80px rgba(15,23,42,.22),
                inset 0 1px 0 rgba(255,255,255,.08);
            transition: all .35s ease;
        }

        .msg-card:hover {
            transform: translateY(-6px);
            box-shadow:
                0 32px 100px rgba(15,23,42,.28),
                inset 0 1px 0 rgba(255,255,255,.10);
        }

        .msg-card::before {
            content: "";
            position: absolute;
            inset: 0;
            width: 42%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.14), transparent);
            transform: translateX(-120%);
            pointer-events: none;
        }

        .msg-card:hover::before {
            animation: msgShine 1s ease;
        }

        .msg-avatar {
            animation: msgFloat 5s ease-in-out infinite;
        }

        .msg-scroll::-webkit-scrollbar {
            width: 8px;
        }

        .msg-scroll::-webkit-scrollbar-track {
            background: rgba(15,23,42,.25);
            border-radius: 9999px;
        }

        .msg-scroll::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #06b6d4, #a855f7);
            border-radius: 9999px;
        }

        .msg-user-item {
            position: relative;
            overflow: hidden;
            border-radius: 1.75rem;
            border: 1px solid rgba(255,255,255,.10);
            background: rgba(255,255,255,.08);
            transition: all .3s ease;
        }

        .msg-user-item:hover {
            transform: translateY(-4px) scale(1.01);
            background: rgba(255,255,255,.12);
            border-color: rgba(34,211,238,.25);
            box-shadow: 0 20px 50px rgba(15,23,42,.18);
        }

        .msg-online-dot {
            position: absolute;
            bottom: -2px;
            right: -2px;
            height: 14px;
            width: 14px;
            border-radius: 9999px;
            background: #10b981;
            border: 2px solid white;
        }

        .dark .msg-online-dot {
            border-color: rgb(15 23 42);
        }
    </style>

    <div class="grid grid-cols-1 2xl:grid-cols-12 gap-8 items-start">

        {{-- Left Side --}}
        <div class="2xl:col-span-8 space-y-6">
            <div class="msg-card p-7 lg:p-8">
                <div class="relative z-10 flex items-center justify-between gap-4 mb-8">
                    <div>
                        <h3 class="text-3xl font-black text-slate-900 dark:text-white">
                            Recent Conversations
                        </h3>
                        <p class="mt-2 text-slate-500 dark:text-slate-400">
                            Continue your latest discussions.
                        </p>
                    </div>

                    <div class="hidden md:flex h-14 w-14 rounded-3xl bg-gradient-to-br from-cyan-400 to-purple-600 items-center justify-center shadow-xl">
                        <i class="fas fa-comments text-white text-xl"></i>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($conversations as $user)
                        <a href="{{ route('messages.show', $user) }}"
                           class="msg-user-item block p-5">
                            <div class="flex items-center gap-4">
                                <div class="relative msg-avatar">
                                    <img
                                        src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($user->email) }}"
                                        class="h-16 w-16 rounded-[1.25rem] bg-white shadow-xl"
                                        alt="User"
                                    >
                                    <span class="msg-online-dot"></span>
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h4 class="text-lg font-black text-slate-900 dark:text-white truncate">
                                        {{ $user->name }}
                                    </h4>

                                    <p class="text-sm text-slate-500 dark:text-slate-400 truncate">
                                        {{ ucfirst($user->role) }} • {{ $user->email }}
                                    </p>
                                </div>

                                <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-cyan-500 to-fuchsia-500 text-white flex items-center justify-center shadow-lg">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="rounded-[2rem] bg-white/8 border border-white/10 p-12 text-center">
                            <div class="mx-auto h-20 w-20 rounded-[2rem] bg-gradient-to-br from-cyan-500 to-purple-600 flex items-center justify-center shadow-2xl">
                                <i class="fas fa-comments text-3xl text-white"></i>
                            </div>

                            <h4 class="mt-6 text-2xl font-black text-slate-900 dark:text-white">
                                No Conversations Yet
                            </h4>

                            <p class="mt-3 text-slate-500 dark:text-slate-400 max-w-md mx-auto">
                                Start a new conversation from the right panel and connect instantly.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
                {{-- Right Side --}}
        <div class="2xl:col-span-4 space-y-6">
            <div class="msg-card p-7 lg:p-8">
                <div class="relative z-10 flex items-center justify-between gap-4 mb-8">
                    <div>
                        <h3 class="text-3xl font-black text-slate-900 dark:text-white">
                            Start New Chat
                        </h3>
                        <p class="mt-2 text-slate-500 dark:text-slate-400">
                            Select a user and begin messaging.
                        </p>
                    </div>

                    <div class="hidden md:flex h-14 w-14 rounded-3xl bg-gradient-to-br from-emerald-400 to-cyan-500 items-center justify-center shadow-xl">
                        <i class="fas fa-paper-plane text-white text-xl"></i>
                    </div>
                </div>

                <div class="msg-scroll space-y-4 max-h-[720px] overflow-y-auto pr-2">
                    @forelse($users as $user)
                        <a href="{{ route('messages.show', $user) }}"
                           class="msg-user-item block p-4">
                            <div class="flex items-center gap-3">
                                <div class="relative msg-avatar">
                                    <img
                                        src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($user->email) }}"
                                        class="h-12 w-12 rounded-2xl bg-white shadow-lg"
                                        alt="User"
                                    >
                                    <span class="msg-online-dot"></span>
                                </div>

                                <div class="min-w-0">
                                    <h4 class="font-black text-slate-900 dark:text-white truncate">
                                        {{ $user->name }}
                                    </h4>

                                    <p class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ ucfirst($user->role) }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="rounded-3xl bg-white/8 border border-white/10 p-6 text-center">
                            <p class="text-slate-500 dark:text-slate-400">
                                No available users found.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</x-app-layout>