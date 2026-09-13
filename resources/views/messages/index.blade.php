<x-app-layout>

    <x-slot name="header">
        <div class="rounded-3xl border border-white/10 bg-gradient-to-r from-slate-950 via-indigo-950 to-purple-950 px-6 py-6 shadow-xl">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <p class="text-xs font-black uppercase tracking-[0.25em] text-cyan-300">
                        Message Center
                    </p>

                    <h2 class="mt-1 text-2xl font-black text-white md:text-3xl">
                        Messages
                    </h2>

                    <p class="mt-1 text-sm text-slate-400">
                        Chat with students and alumni.
                    </p>
                </div>

                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-cyan-500/15 text-cyan-300">
                    <i class="fas fa-comments text-xl"></i>
                </div>

            </div>
        </div>
    </x-slot>


    <style>
        .message-panel {
            border: 1px solid rgba(255,255,255,.10);
            background: rgba(15,23,42,.55);
            backdrop-filter: blur(18px);
            box-shadow: 0 18px 55px rgba(2,6,23,.18);
        }

        .message-user {
            transition: .2s ease;
        }

        .message-user:hover {
            background: rgba(255,255,255,.07);
            transform: translateY(-1px);
        }

        .message-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .message-scroll::-webkit-scrollbar-thumb {
            background: rgba(148,163,184,.35);
            border-radius: 999px;
        }
    </style>


    <div class="mx-auto max-w-6xl">

        <div class="grid grid-cols-1 gap-5 lg:grid-cols-12">


            {{-- ===================================================== --}}
            {{-- CONVERSATIONS --}}
            {{-- ===================================================== --}}

            <section class="message-panel overflow-hidden rounded-3xl lg:col-span-7">

                <div class="border-b border-white/10 px-5 py-4">

                    <div class="flex items-center justify-between">

                        <div>
                            <h3 class="text-lg font-black text-slate-900 dark:text-white">
                                Recent Conversations
                            </h3>

                            <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                                Your latest messages
                            </p>
                        </div>

                        <span class="rounded-full bg-cyan-500/10 px-3 py-1 text-xs font-bold text-cyan-500">
                            {{ $conversations->count() }}
                        </span>

                    </div>

                </div>


                <div class="message-scroll max-h-[650px] overflow-y-auto">

                    @forelse($conversations as $conversationUser)

                        @php
                            $lastMessage = $conversationUser->last_message;
                            $isMine = $lastMessage
                                ? (int) $lastMessage->sender_id === (int) auth()->id()
                                : false;

                            $preview = $lastMessage?->content;

                            if (!$preview && $lastMessage?->attachment) {
                                $preview = 'Attachment';
                            }
                        @endphp


                        <a
                            href="{{ route('messages.show', $conversationUser) }}"
                            class="message-user block border-b border-white/5 px-5 py-4"
                        >

                            <div class="flex items-center gap-3">

                                {{-- Avatar --}}

                                @if($conversationUser->profile_image)

                                    <img
                                        src="{{ $conversationUser->getProfileImageUrl() }}"
                                        alt="{{ $conversationUser->name }}"
                                        class="h-12 w-12 shrink-0 rounded-2xl object-cover"
                                    >

                                @else

                                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-cyan-500 to-purple-600 font-black text-white">
                                        {{ strtoupper(substr($conversationUser->name, 0, 1)) }}
                                    </div>

                                @endif


                                <div class="min-w-0 flex-1">

                                    <div class="flex items-center justify-between gap-3">

                                        <h4 class="truncate font-black text-slate-900 dark:text-white">
                                            {{ $conversationUser->name }}
                                        </h4>


                                        @if($lastMessage)

                                            <span class="shrink-0 text-[11px] font-semibold text-slate-400">
                                                {{ $lastMessage->created_at?->diffForHumans(null, true) }}
                                            </span>

                                        @endif

                                    </div>


                                    <div class="mt-1 flex items-center justify-between gap-3">

                                        <p class="truncate text-sm
                                            {{ $conversationUser->unread_count > 0
                                                ? 'font-black text-slate-900 dark:text-white'
                                                : 'text-slate-500 dark:text-slate-400' }}">

                                            @if($isMine)
                                                You:
                                            @endif

                                            {{ \Illuminate\Support\Str::limit($preview ?? 'Start a conversation', 55) }}

                                        </p>


                                        @if($conversationUser->unread_count > 0)

                                            <span class="flex h-6 min-w-6 shrink-0 items-center justify-center rounded-full bg-cyan-500 px-2 text-[11px] font-black text-white">
                                                {{ $conversationUser->unread_count > 99
                                                    ? '99+'
                                                    : $conversationUser->unread_count }}
                                            </span>

                                        @endif

                                    </div>


                                    <p class="mt-1 text-[11px] font-bold uppercase tracking-wide text-slate-400">
                                        {{ ucfirst($conversationUser->role) }}
                                    </p>

                                </div>

                            </div>

                        </a>


                    @empty

                        <div class="flex min-h-[300px] flex-col items-center justify-center px-6 py-10 text-center">

                            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-cyan-500/10 text-cyan-500">
                                <i class="fas fa-comment-dots text-2xl"></i>
                            </div>

                            <h4 class="mt-4 font-black text-slate-900 dark:text-white">
                                No conversations yet
                            </h4>

                            <p class="mt-1 text-sm text-slate-500">
                                Choose someone and send your first message.
                            </p>

                        </div>

                    @endforelse

                </div>

            </section>


            {{-- ===================================================== --}}
            {{-- NEW CHAT --}}
            {{-- ===================================================== --}}

            <section class="message-panel overflow-hidden rounded-3xl lg:col-span-5">

                <div class="border-b border-white/10 px-5 py-4">

                    <h3 class="text-lg font-black text-slate-900 dark:text-white">
                        New Chat
                    </h3>

                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                        Students and alumni
                    </p>

                </div>


                <div class="border-b border-white/10 p-4">

                    <div class="relative">

                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                        <input
                            type="text"
                            id="userSearch"
                            placeholder="Search people..."
                            class="w-full rounded-2xl border border-white/10 bg-slate-950/40 py-3 pl-11 pr-4 text-sm text-slate-900 outline-none focus:border-cyan-500 dark:text-white"
                        >

                    </div>

                </div>


                <div
                    id="userList"
                    class="message-scroll max-h-[565px] overflow-y-auto"
                >

                    @forelse($users as $chatUser)

                        <a
                            href="{{ route('messages.show', $chatUser) }}"
                            data-user-name="{{ strtolower($chatUser->name) }}"
                            data-user-email="{{ strtolower($chatUser->email) }}"
                            class="message-user chat-user block border-b border-white/5 px-5 py-3"
                        >

                            <div class="flex items-center gap-3">

                                @if($chatUser->profile_image)

                                    <img
                                        src="{{ $chatUser->getProfileImageUrl() }}"
                                        alt="{{ $chatUser->name }}"
                                        class="h-10 w-10 rounded-xl object-cover"
                                    >

                                @else

                                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 text-sm font-black text-white">
                                        {{ strtoupper(substr($chatUser->name, 0, 1)) }}
                                    </div>

                                @endif


                                <div class="min-w-0 flex-1">

                                    <p class="truncate text-sm font-black text-slate-900 dark:text-white">
                                        {{ $chatUser->name }}
                                    </p>

                                    <p class="truncate text-xs text-slate-500">
                                        {{ ucfirst($chatUser->role) }}

                                        @if($chatUser->department)
                                            • {{ $chatUser->department }}
                                        @endif
                                    </p>

                                </div>


                                <i class="fas fa-chevron-right text-xs text-slate-400"></i>

                            </div>

                        </a>

                    @empty

                        <div class="p-8 text-center text-sm text-slate-500">
                            No available users.
                        </div>

                    @endforelse

                </div>

            </section>


        </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const search = document.getElementById('userSearch');
            const users = document.querySelectorAll('.chat-user');

            if (!search) {
                return;
            }

            search.addEventListener('input', function () {

                const value = this.value
                    .toLowerCase()
                    .trim();

                users.forEach(function (user) {

                    const name =
                        user.dataset.userName || '';

                    const email =
                        user.dataset.userEmail || '';

                    const matches =
                        name.includes(value) ||
                        email.includes(value);

                    user.classList.toggle(
                        'hidden',
                        !matches
                    );

                });

            });

        });
    </script>

</x-app-layout>