<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-950 to-cyan-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(99,102,241,.45),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(34,211,238,.35),transparent_35%)]"></div>

            <div class="relative z-10">
                <p class="text-sm uppercase tracking-[0.35em] text-cyan-300 font-black">AI Message Center</p>
                <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">Inbox</h2>
                <p class="mt-3 text-slate-300 max-w-2xl">
                    Connect with students and alumni through a premium messaging experience.
                </p>
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

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

        <div class="xl:col-span-2 space-y-6">
            <div class="uc-card p-7">
                <h3 class="text-2xl font-black mb-6">
                    Recent Conversations
                </h3>

                <div class="space-y-4">
                    @forelse($conversations as $user)
                        <a href="{{ route('messages.show', $user) }}" class="block rounded-3xl bg-white/10 border border-white/10 p-5 hover:bg-white/20 transition">
                            <div class="flex items-center gap-4">
                                <img
                                    src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($user->email) }}"
                                    class="h-16 w-16 rounded-2xl bg-white"
                                    alt="User"
                                >

                                <div class="flex-1">
                                    <h4 class="text-lg font-black text-slate-900 dark:text-white">
                                        {{ $user->name }}
                                    </h4>

                                    <p class="text-sm text-slate-500">
                                        {{ ucfirst($user->role) }} • {{ $user->email }}
                                    </p>
                                </div>

                                <div class="h-11 w-11 rounded-2xl bg-slate-950 text-white flex items-center justify-center">
                                    <i class="fas fa-arrow-right"></i>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="rounded-3xl bg-white/10 border border-white/10 p-8 text-center">
                            <i class="fas fa-comments text-4xl text-cyan-500"></i>
                            <h4 class="mt-4 text-xl font-black">No conversations yet</h4>
                            <p class="mt-2 text-slate-500">Start a new conversation from the right panel.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="uc-card p-7">
                <h3 class="text-2xl font-black mb-6">
                    Start New Chat
                </h3>

                <div class="space-y-4 max-h-[650px] overflow-y-auto pr-2">
                    @forelse($users as $user)
                        <a href="{{ route('messages.show', $user) }}" class="block rounded-3xl bg-white/10 border border-white/10 p-4 hover:bg-white/20 transition">
                            <div class="flex items-center gap-3">
                                <img
                                    src="https://api.dicebear.com/7.x/avataaars/svg?seed={{ urlencode($user->email) }}"
                                    class="h-12 w-12 rounded-2xl bg-white"
                                    alt="User"
                                >

                                <div>
                                    <h4 class="font-black text-slate-900 dark:text-white">
                                        {{ $user->name }}
                                    </h4>

                                    <p class="text-xs text-slate-500">
                                        {{ ucfirst($user->role) }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <p class="text-slate-500">No available users found.</p>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</x-app-layout>