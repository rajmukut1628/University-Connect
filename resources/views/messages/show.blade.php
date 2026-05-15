<x-app-layout>
    <div class="min-h-screen text-white">
        <style>
            @keyframes chatFloat {
                0%, 100% { transform: translateY(0) scale(1); }
                50% { transform: translateY(-12px) scale(1.03); }
            }

            @keyframes chatShine {
                0% { transform: translateX(-130%); }
                100% { transform: translateX(130%); }
            }

            @keyframes chatGlow {
                0%, 100% {
                    box-shadow: 0 24px 80px rgba(34, 211, 238, .18);
                }
                50% {
                    box-shadow: 0 30px 100px rgba(236, 72, 153, .25);
                }
            }

            @keyframes bubbleIn {
                from {
                    opacity: 0;
                    transform: translateY(14px) scale(.96);
                }
                to {
                    opacity: 1;
                    transform: translateY(0) scale(1);
                }
            }

            .chat-orb {
                animation: chatFloat 7s ease-in-out infinite;
            }

            .chat-shell {
                animation: chatGlow 5s ease-in-out infinite;
            }

            .chat-shine::before {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,.08), transparent);
                transform: translateX(-130%);
                animation: chatShine 7s ease-in-out infinite;
                pointer-events: none;
            }

            .chat-input {
                width: 100%;
                border-radius: 1.25rem;
                border: 1px solid rgba(255,255,255,.12);
                background: rgba(2,6,23,.78);
                padding: 1rem 1.1rem;
                color: white;
                font-size: .875rem;
                font-weight: 800;
                outline: none;
                transition: .25s ease;
            }

            .chat-input::placeholder {
                color: rgba(148,163,184,.75);
            }

            .chat-input:focus {
                border-color: rgba(34,211,238,.75);
                box-shadow: 0 0 0 5px rgba(34,211,238,.10);
                transform: translateY(-1px);
            }

            .chat-scroll::-webkit-scrollbar {
                width: 6px;
            }

            .chat-scroll::-webkit-scrollbar-thumb {
                background: rgba(255,255,255,.18);
                border-radius: 999px;
            }

            .chat-scroll::-webkit-scrollbar-track {
                background: transparent;
            }

            .message-animate {
                animation: bubbleIn .35s ease both;
            }

            .wa-composer-box {
                border-radius: 2rem;
                border: 1px solid rgba(255,255,255,.12);
                background:
                    radial-gradient(circle at top left, rgba(34,211,238,.10), transparent 35%),
                    linear-gradient(135deg, rgba(2,6,23,.95), rgba(15,23,42,.92));
                box-shadow: 0 18px 55px rgba(2,6,23,.45);
            }

            .wa-input {
                min-height: 48px;
                max-height: 160px;
                resize: none;
                border: 0 !important;
                outline: none !important;
                box-shadow: none !important;
                background: transparent !important;
                color: white;
                font-size: .9rem;
                font-weight: 800;
                line-height: 1.6;
            }

            .wa-input:focus {
                border: 0 !important;
                outline: none !important;
                box-shadow: none !important;
                ring: 0 !important;
            }
        </style>

        <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-gradient-to-br from-slate-950 via-indigo-950 to-fuchsia-950 p-5 shadow-2xl md:p-7">

            <div class="chat-orb absolute -top-24 -right-24 h-80 w-80 rounded-full bg-cyan-500/20 blur-3xl"></div>
            <div class="chat-orb absolute top-64 -left-28 h-80 w-80 rounded-full bg-fuchsia-500/20 blur-3xl" style="animation-delay:2s"></div>
            <div class="chat-orb absolute -bottom-28 right-1/3 h-80 w-80 rounded-full bg-emerald-500/15 blur-3xl" style="animation-delay:4s"></div>

            {{-- Header --}}
            <div class="relative z-10 mb-6 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('messages.index') }}"
                       class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/10 bg-white/10 text-white transition hover:scale-105 hover:bg-white/15">
                        <i class="fas fa-arrow-left"></i>
                    </a>

                    <div class="relative">
                        @if($user->profile_image ?? false)
                            <img src="{{ asset('storage/' . $user->profile_image) }}"
                                 alt="{{ $user->name }}"
                                 class="h-16 w-16 rounded-3xl object-cover border-2 border-white/20 shadow-xl">
                        @else
                            <div class="flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-cyan-500 via-purple-600 to-pink-500 text-2xl font-black shadow-xl">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif

                        <span class="absolute -bottom-1 -right-1 h-5 w-5 rounded-full border-4 border-slate-950 bg-emerald-400"></span>
                    </div>

                    <div>
                        <p class="text-xs font-black uppercase tracking-[.3em] text-cyan-300">
                            Premium Chat
                        </p>

                        <h1 class="mt-1 text-2xl font-black md:text-3xl">
                            {{ $user->name }}
                        </h1>

                        <p class="text-sm font-semibold text-slate-400">
                            {{ ucfirst($user->role) }} • Active now
                        </p>
                    </div>
                </div>

                {{-- Real Call Buttons --}}
                <div class="flex flex-wrap items-center gap-3">
                    <form method="POST" action="{{ route('calls.start', $user) }}">
                        @csrf
                        <input type="hidden" name="type" value="audio">

                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-5 py-3 text-sm font-black text-emerald-300 shadow-xl transition hover:scale-105 hover:bg-emerald-500/15">
                            <i class="fas fa-phone"></i>
                            Audio Call
                        </button>
                    </form>

                    <form method="POST" action="{{ route('calls.start', $user) }}">
                        @csrf
                        <input type="hidden" name="type" value="video">

                        <button type="submit"
                                class="inline-flex items-center gap-2 rounded-2xl border border-cyan-400/20 bg-cyan-500/10 px-5 py-3 text-sm font-black text-cyan-300 shadow-xl transition hover:scale-105 hover:bg-cyan-500/15">
                            <i class="fas fa-video"></i>
                            Video Call
                        </button>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="relative z-10 mb-5 rounded-2xl border border-emerald-400/25 bg-emerald-500/10 p-4 text-sm font-black text-emerald-300">
                    <i class="fas fa-circle-check mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="relative z-10 mb-5 rounded-2xl border border-red-400/25 bg-red-500/10 p-4 text-sm font-black text-red-300">
                    @foreach($errors->all() as $error)
                        <div>
                            <i class="fas fa-triangle-exclamation mr-2"></i>
                            {{ $error }}
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Chat Shell --}}
            <div class="chat-shell chat-shine relative z-10 overflow-hidden rounded-[2rem] border border-white/10 bg-white/[.06] shadow-2xl backdrop-blur-2xl">

                {{-- Chat Top Bar --}}
                <div class="flex items-center justify-between border-b border-white/10 bg-slate-950/40 px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-2xl bg-gradient-to-br from-cyan-500 to-fuchsia-500">
                            <i class="fas fa-lock text-white"></i>
                        </div>

                        <div>
                            <p class="text-sm font-black text-white">
                                Secure Conversation
                            </p>
                            <p class="text-xs font-semibold text-slate-400">
                                Attachments, edit, delete and premium message actions enabled
                            </p>
                        </div>
                    </div>

                    <div class="hidden items-center gap-2 md:flex">
                        <span class="rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs font-black text-slate-300">
                            <i class="fas fa-shield-halved mr-1 text-emerald-300"></i>
                            Protected
                        </span>
                    </div>
                </div>

                {{-- Messages --}}
                <div class="chat-scroll max-h-[560px] min-h-[430px] overflow-y-auto px-5 py-6 space-y-5 flex flex-col">
                    @forelse($messages as $message)
                        @php
                            $isMine = $message->sender_id === auth()->id();
                            $isImage = $message->attachment_type && str_contains($message->attachment_type, 'image');
                            $isVideo = $message->attachment_type && str_contains($message->attachment_type, 'video');
                            $isAudio = $message->attachment_type && str_contains($message->attachment_type, 'audio');
                            $messageText = $message->body ?? $message->message ?? $message->content ?? '';
                        @endphp

                        <div class="w-full flex message-animate {{ $isMine ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-[92%] md:max-w-[72%] flex items-end gap-3 {{ $isMine ? 'flex-row-reverse' : '' }}">

                                {{-- Avatar --}}
                                <div class="hidden h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br from-indigo-500 to-pink-500 text-sm font-black shadow-lg md:flex">
                                    {{ $isMine ? strtoupper(substr(auth()->user()->name, 0, 1)) : strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                                                {{-- Bubble + Side Actions --}}
                                <div class="group flex items-center gap-3 {{ $isMine ? 'flex-row-reverse' : '' }}">

                                    <div class="relative inline-block max-w-[220px] md:max-w-[260px] overflow-hidden rounded-[1.25rem] px-3 py-2.5 shadow-xl transition-all duration-300 group-hover:scale-[1.015]
                                        {{ $isMine
                                            ? 'rounded-br-md bg-gradient-to-br from-cyan-500 via-indigo-600 to-fuchsia-600 text-white'
                                            : 'rounded-bl-md border border-white/10 bg-slate-900/90 text-slate-100' }}">

                                        <div class="absolute inset-0 pointer-events-none bg-gradient-to-r from-white/10 via-transparent to-transparent opacity-30"></div>

                                        @if($messageText)
                                            <p class="relative whitespace-pre-wrap break-words text-sm font-extrabold leading-snug tracking-tight drop-shadow-sm">
                                                {{ $messageText }}
                                            </p>
                                        @endif

                                        @if($message->attachment)
                                            <div class="relative mt-3 overflow-hidden rounded-2xl border border-white/10 bg-slate-950/35">
                                                @if($isImage)
                                                    <a href="{{ asset('storage/' . $message->attachment) }}" target="_blank">
                                                        <img src="{{ asset('storage/' . $message->attachment) }}"
                                                             alt="{{ $message->attachment_name }}"
                                                             class="max-h-72 w-full object-cover">
                                                    </a>
                                                @elseif($isVideo)
                                                    <video controls class="max-h-72 w-full">
                                                        <source src="{{ asset('storage/' . $message->attachment) }}" type="{{ $message->attachment_type }}">
                                                    </video>
                                                @elseif($isAudio)
                                                    <audio controls class="w-full">
                                                        <source src="{{ asset('storage/' . $message->attachment) }}" type="{{ $message->attachment_type }}">
                                                    </audio>
                                                @else
                                                    <a href="{{ asset('storage/' . $message->attachment) }}"
                                                       target="_blank"
                                                       class="flex items-center gap-3 p-4 text-sm font-black text-white hover:bg-white/10">
                                                        <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-white/10">
                                                            <i class="fas fa-paperclip"></i>
                                                        </span>
                                                        <span class="truncate">
                                                            {{ $message->attachment_name ?? 'Attachment' }}
                                                        </span>
                                                    </a>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="relative mt-3 flex items-center justify-end gap-2 text-[10px] font-black text-white/80">
                                            <span>{{ $message->created_at?->format('h:i A') }}</span>

                                            @if($message->is_edited)
                                                <span>• Edited</span>
                                            @endif

                                            @if($isMine)
                                                <span>• {{ $message->read_at ? 'Seen' : 'Sent' }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    @if($isMine)
                                        <div class="flex flex-col gap-2 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-all duration-300">
                                            <button type="button"
                                                    onclick="document.getElementById('edit-box-{{ $message->id }}').classList.toggle('hidden')"
                                                    class="flex h-9 w-9 items-center justify-center rounded-xl border border-cyan-400/20 bg-cyan-500/10 text-cyan-300 shadow-lg transition hover:scale-110 hover:bg-cyan-500/20"
                                                    title="Edit">
                                                <i class="fas fa-pen text-xs"></i>
                                            </button>

                                            <form method="POST"
                                                  action="{{ route('messages.destroy', $message) }}"
                                                  onsubmit="return confirm('Delete this message?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="flex h-9 w-9 items-center justify-center rounded-xl border border-red-400/20 bg-red-500/10 text-red-300 shadow-lg transition hover:scale-110 hover:bg-red-500/20"
                                                        title="Delete">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($isMine)
                            <form id="edit-box-{{ $message->id }}"
                                  method="POST"
                                  action="{{ route('messages.update', $message) }}"
                                  class="hidden ml-auto w-full max-w-[92%] md:max-w-[72%] rounded-2xl border border-cyan-400/20 bg-cyan-500/10 p-3">
                                @csrf
                                @method('PATCH')

                                <textarea name="body"
                                          rows="3"
                                          required
                                          class="chat-input min-h-[90px]">{{ $messageText }}</textarea>

                                <div class="mt-3 flex justify-end gap-2">
                                    <button type="button"
                                            onclick="document.getElementById('edit-box-{{ $message->id }}').classList.add('hidden')"
                                            class="rounded-xl border border-white/10 bg-white/10 px-4 py-2 text-xs font-black text-white">
                                        Cancel
                                    </button>

                                    <button type="submit"
                                            class="rounded-xl bg-cyan-500 px-4 py-2 text-xs font-black text-white">
                                        Save
                                    </button>
                                </div>
                            </form>
                        @endif
                    @empty
                        <div class="flex min-h-[360px] flex-col items-center justify-center text-center">
                            <div class="flex h-24 w-24 items-center justify-center rounded-[2rem] bg-gradient-to-br from-cyan-500 via-purple-600 to-pink-500 text-4xl shadow-2xl">
                                <i class="fas fa-comments"></i>
                            </div>

                            <h3 class="mt-6 text-2xl font-black">
                                No messages yet
                            </h3>

                            <p class="mt-2 max-w-md text-sm font-semibold text-slate-400">
                                Start the conversation with a message, image, document, audio or video attachment.
                            </p>
                        </div>
                    @endforelse
                </div>

                {{-- WhatsApp Style Message Composer --}}
                <div class="border-t border-white/10 bg-slate-950/55 p-4 md:p-5">
                    <form method="POST"
                          action="{{ route('messages.store', $user) }}"
                          enctype="multipart/form-data"
                          class="space-y-3">
                        @csrf

                        <div class="wa-composer-box flex items-end gap-3 p-3">
                            {{-- Attachment Button --}}
                            <label class="group flex h-12 w-12 shrink-0 cursor-pointer items-center justify-center rounded-2xl border border-white/10 bg-white/10 text-cyan-300 shadow-lg transition hover:scale-110 hover:bg-cyan-500/20">
                                <i class="fas fa-paperclip text-sm group-hover:rotate-12 transition"></i>

                                <input type="file"
                                       name="attachment"
                                       id="messageAttachment"
                                       class="hidden"
                                       accept=".jpg,.jpeg,.png,.webp,.pdf,.doc,.docx,.zip,.rar,.txt,.mp3,.mp4">
                            </label>

                            {{-- Message Input --}}
                            <textarea name="body"
                                      id="messageBody"
                                      rows="1"
                                      placeholder="Type a message..."
                                      class="wa-input flex-1 px-2 py-3 placeholder:text-slate-400"></textarea>

                            {{-- Send Button --}}
                            <button type="submit"
                                    class="group flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-r from-cyan-500 via-indigo-600 to-fuchsia-600 text-white shadow-xl shadow-cyan-500/20 transition hover:scale-110">
                                <i class="fas fa-paper-plane text-sm group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition"></i>
                            </button>
                        </div>

                        <div id="selectedFileName"
                             class="hidden rounded-2xl border border-cyan-400/20 bg-cyan-500/10 px-4 py-3 text-xs font-bold text-cyan-200">
                        </div>

                        <p class="px-2 text-xs font-semibold text-slate-500">
                            Supported: Image, PDF, DOC, DOCX, ZIP, TXT, MP3, MP4
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const chatBox = document.querySelector('.chat-scroll');

        if (chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        document.addEventListener('DOMContentLoaded', function () {
            const textarea = document.getElementById('messageBody');
            const fileInput = document.getElementById('messageAttachment');
            const fileNameBox = document.getElementById('selectedFileName');

            if (textarea) {
                textarea.addEventListener('input', function () {
                    this.style.height = '48px';
                    this.style.height = Math.min(this.scrollHeight, 160) + 'px';
                });
            }

            if (fileInput && fileNameBox) {
                fileInput.addEventListener('change', function () {
                    if (this.files.length > 0) {
                        fileNameBox.textContent = '📎 Selected File: ' + this.files[0].name;
                        fileNameBox.classList.remove('hidden');
                    } else {
                        fileNameBox.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</x-app-layout>