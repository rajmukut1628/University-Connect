<x-app-layout>
    <style>
        @keyframes aiGlow {
            0%, 100% {
                box-shadow:
                    0 0 25px rgba(34, 211, 238, .20),
                    0 0 70px rgba(168, 85, 247, .16),
                    0 24px 70px rgba(15, 23, 42, .24);
            }

            50% {
                box-shadow:
                    0 0 38px rgba(34, 211, 238, .38),
                    0 0 95px rgba(168, 85, 247, .28),
                    0 32px 90px rgba(15, 23, 42, .34);
            }
        }

        @keyframes aiSlideUp {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes aiPulse {
            0%, 100% {
                opacity: .45;
                transform: scale(.92);
            }

            50% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .ask-ai-dark-panel {
            position: relative;
            overflow: hidden;
            border-radius: 2rem;
            border: 1px solid rgba(34, 211, 238, .25);
            background:
                radial-gradient(circle at top left, rgba(34, 211, 238, .18), transparent 35%),
                radial-gradient(circle at bottom right, rgba(168, 85, 247, .20), transparent 35%),
                linear-gradient(135deg, rgba(15, 23, 42, .98), rgba(2, 6, 23, .96));
            animation: aiGlow 4s ease-in-out infinite;
        }

        .ask-ai-dark-panel::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(34,211,238,.08) 1px, transparent 1px),
                linear-gradient(90deg, rgba(34,211,238,.08) 1px, transparent 1px);
            background-size: 34px 34px;
            mask-image: linear-gradient(to bottom, black, transparent);
            pointer-events: none;
        }

        .ai-response-panel {
            position: sticky;
            top: 125px;
            overflow: hidden;
            border-radius: 2rem;
            border: 1px solid rgba(16, 185, 129, .25);
            background:
                radial-gradient(circle at top right, rgba(16, 185, 129, .18), transparent 38%),
                radial-gradient(circle at bottom left, rgba(6, 182, 212, .15), transparent 38%),
                linear-gradient(135deg, rgba(15, 23, 42, .98), rgba(2, 6, 23, .96));
            box-shadow:
                0 0 35px rgba(16, 185, 129, .12),
                0 24px 80px rgba(15, 23, 42, .30);
        }

        .ai-answer-body {
            white-space: pre-line;
            line-height: 1.9;
            font-size: .96rem;
        }

        .ai-answer-card {
            animation: aiSlideUp .45s ease both;
        }

        .ai-dot {
            height: .55rem;
            width: .55rem;
            border-radius: 9999px;
            background: rgb(34, 211, 238);
            display: inline-block;
            animation: aiPulse 1.2s ease-in-out infinite;
        }

        .ai-dot:nth-child(2) {
            animation-delay: .15s;
        }

        .ai-dot:nth-child(3) {
            animation-delay: .30s;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 9999px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(to bottom, #10b981, #06b6d4);
            border-radius: 9999px;
            border: 2px solid rgba(15, 23, 42, 0.5);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(to bottom, #34d399, #22d3ee);
        }

        @media (max-width: 1279px) {
            .ai-response-panel {
                position: relative;
                top: auto;
            }
        }
    </style>

    <div class="space-y-8">

        @if(session('success'))
            <div class="rounded-2xl bg-emerald-500/15 border border-emerald-500/30 p-4 text-emerald-300 font-bold">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">

            {{-- Left Side: Ask AI Form --}}
            <div class="xl:col-span-5">
                <div class="ask-ai-dark-panel p-6 lg:p-7">
                    <div class="relative z-10">

                        <div class="flex items-start justify-between gap-5 mb-7">
                            <div>
                                <div class="inline-flex items-center gap-2 rounded-full border border-cyan-400/25 bg-cyan-400/10 px-4 py-2 text-cyan-200 text-xs font-black uppercase tracking-[0.25em]">
                                    <span class="relative flex h-2.5 w-2.5">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-300 opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-cyan-300"></span>
                                    </span>
                                    Live AI Chat
                                </div>

                                <h3 class="mt-4 text-3xl font-black text-white">
                                    Ask Anything
                                </h3>

                                <p class="mt-3 text-sm text-slate-300 max-w-xl">
                                    Ask about study, CV, internship, programming, career, communication,
                                    mentorship, final defense, projects or personal growth.
                                </p>
                            </div>

                            <div class="hidden md:flex h-16 w-16 rounded-3xl bg-gradient-to-br from-cyan-400 via-blue-500 to-purple-600 items-center justify-center shadow-2xl shadow-cyan-500/30">
                                <i class="fas fa-robot text-2xl text-white"></i>
                            </div>
                        </div>

                        <form id="askAiForm" method="POST" action="{{ route('ask-ai.ask') }}" class="space-y-5">
                            @csrf

                            <div class="relative">
                                <textarea
                                    id="askAIQuestion"
                                    name="question"
                                    rows="7"
                                    required
                                    placeholder="Ask anything..."
                                    class="w-full rounded-[1.7rem] border border-cyan-400/30 bg-white/10 text-white placeholder-slate-400 focus:ring-2 focus:ring-cyan-400 focus:border-cyan-400 p-5 pr-14 shadow-inner backdrop-blur-xl resize-none">{{ old('question') }}</textarea>

                                <div class="absolute right-4 top-4 h-10 w-10 rounded-2xl bg-cyan-400/10 border border-cyan-400/20 flex items-center justify-center text-cyan-300">
                                    <i class="fas fa-message"></i>
                                </div>
                            </div>

                            @error('question')
                                <p class="text-red-300 text-sm font-semibold">
                                    {{ $message }}
                                </p>
                            @enderror

                            <div class="flex flex-wrap gap-2">
                                <span class="px-3 py-1 rounded-full bg-white/10 border border-white/10 text-xs text-slate-300">CV</span>
                                <span class="px-3 py-1 rounded-full bg-white/10 border border-white/10 text-xs text-slate-300">Internship</span>
                                <span class="px-3 py-1 rounded-full bg-white/10 border border-white/10 text-xs text-slate-300">Defense</span>
                                <span class="px-3 py-1 rounded-full bg-white/10 border border-white/10 text-xs text-slate-300">Career</span>
                            </div>

                            <button id="submitBtn" type="submit"
                                    class="group w-full inline-flex items-center justify-center px-7 py-4 rounded-2xl bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-600 text-white font-black shadow-2xl shadow-cyan-500/25 hover:scale-[1.02] transition">
                                <i class="fas fa-wand-magic-sparkles mr-2 group-hover:rotate-12 transition"></i>
                                Ask AI
                            </button>
                        </form>
                    </div>
                </div>
            </div>
                        {{-- Right Side: AI Response Panel --}}
            <div class="xl:col-span-7">
                <div id="aiResponseWrapper"
                     class="{{ session('ai_answer') ? '' : 'hidden' }} ai-answer-card">

                    <div class="ai-response-panel">

                        {{-- Header --}}
                        <div class="relative px-8 py-6 border-b border-white/10 bg-white/[0.03] backdrop-blur-xl">
                            <div class="flex items-center justify-between gap-4 flex-wrap">
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-400 via-cyan-500 to-blue-600 flex items-center justify-center shadow-xl shadow-emerald-500/30">
                                            <i class="fas fa-robot text-2xl text-white"></i>
                                        </div>

                                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-emerald-400 rounded-full border-2 border-slate-900 animate-ping"></span>
                                        <span class="absolute -top-1 -right-1 w-4 h-4 bg-emerald-400 rounded-full border-2 border-slate-900"></span>
                                    </div>

                                    <div>
                                        <h3 class="text-2xl font-black text-white tracking-tight">
                                            Ask AI Response
                                        </h3>
                                        <p class="text-sm text-slate-400 mt-1">
                                            Real-time intelligent analysis powered by AI
                                        </p>
                                    </div>
                                </div>

                                <button type="button"
                                        id="copyAnswerBtn"
                                        class="group inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/10 text-slate-300 hover:text-white transition-all duration-300">
                                    <i class="fas fa-copy group-hover:scale-110 transition-transform"></i>
                                    <span class="font-semibold">Copy Answer</span>
                                </button>
                            </div>
                        </div>

                        {{-- Thinking Animation --}}
                        <div id="thinkingBox" class="relative p-8 hidden">
                            <div class="flex items-start gap-5">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-400 via-orange-500 to-red-500 flex items-center justify-center shadow-xl shadow-orange-500/25">
                                    <i class="fas fa-bolt text-2xl text-white animate-pulse"></i>
                                </div>

                                <div class="flex-1">
                                    <h4 class="text-lg font-bold text-white mb-2 flex items-center gap-2">
                                        AI is Thinking
                                        <span class="flex gap-1">
                                            <span class="w-2 h-2 bg-amber-400 rounded-full animate-bounce"></span>
                                            <span class="w-2 h-2 bg-orange-400 rounded-full animate-bounce delay-100"></span>
                                            <span class="w-2 h-2 bg-red-400 rounded-full animate-bounce delay-200"></span>
                                        </span>
                                    </h4>

                                    <p class="text-slate-400 mb-4">
                                        Analyzing your question and preparing a professional answer...
                                    </p>

                                    <div class="w-full h-2 bg-slate-700/50 rounded-full overflow-hidden">
                                        <div class="h-full w-full bg-gradient-to-r from-amber-400 via-orange-500 to-red-500 animate-pulse"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Answer Content --}}
                        <div id="answerBox"
                             class="{{ session('ai_answer') ? '' : 'hidden' }} max-h-[720px] overflow-y-auto custom-scrollbar">

                            {{-- Question --}}
                            <div class="px-8 pt-8">
                                <p class="text-xs uppercase tracking-[0.25em] text-cyan-200 font-black">
                                    Your Question
                                </p>

                                <div class="mt-3 rounded-2xl bg-slate-950/70 border border-white/10 p-5">
                                    <p id="questionPreview"
                                       class="text-slate-200 leading-relaxed">
                                        {{ session('ai_question') }}
                                    </p>
                                </div>
                            </div>

                            {{-- AI Answer --}}
                            <div class="px-8 py-8">
                                <div class="flex items-center justify-between gap-3 mb-3">
                                    <p class="text-xs uppercase tracking-[0.25em] text-emerald-200 font-black">
                                        AI Answer
                                    </p>

                                    <div class="flex items-center gap-1">
                                        <span class="ai-dot"></span>
                                        <span class="ai-dot"></span>
                                        <span class="ai-dot"></span>
                                    </div>
                                </div>

                                <div class="rounded-2xl bg-slate-950/80 border border-emerald-400/20 p-6">
                                    <div id="answerContent"
                                         class="ai-answer-body text-slate-200 prose prose-invert max-w-none">
                                        @if(session('ai_answer'))
                                            {!! nl2br(e(session('ai_answer'))) !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Markdown Parser --}}
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('askAiForm');
            const questionInput = document.getElementById('askAIQuestion');
            const submitBtn = document.getElementById('submitBtn');

            const aiResponseWrapper = document.getElementById('aiResponseWrapper');
            const thinkingBox = document.getElementById('thinkingBox');
            const answerBox = document.getElementById('answerBox');
            const answerContent = document.getElementById('answerContent');
            const questionPreview = document.getElementById('questionPreview');
            const copyAnswerBtn = document.getElementById('copyAnswerBtn');

            if (!form) return;

            form.addEventListener('submit', async function (e) {
                e.preventDefault();

                const question = questionInput.value.trim();
                if (!question) {
                    questionInput.focus();
                    return;
                }

                // Show response panel
                aiResponseWrapper.classList.remove('hidden');
                thinkingBox.classList.remove('hidden');
                answerBox.classList.add('hidden');

                // Show question preview
                if (questionPreview) {
                    questionPreview.textContent = question;
                }

                // Scroll to response section
                aiResponseWrapper.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });

                // Disable button
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-70', 'cursor-not-allowed');

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            question: question,
                            prompt: question
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error(
                            data.error ||
                            data.message ||
                            'Something went wrong.'
                        );
                    }

                    const answer =
                        data.answer ||
                        data.response ||
                        data.message ||
                        'No response received.';

                    thinkingBox.classList.add('hidden');
                    answerBox.classList.remove('hidden');

                    if (window.marked) {
                        answerContent.innerHTML = marked.parse(answer);
                    } else {
                        answerContent.textContent = answer;
                    }

                } catch (error) {
                    thinkingBox.classList.add('hidden');
                    answerBox.classList.remove('hidden');

                    answerContent.innerHTML = `
                        <div class="rounded-2xl border border-red-500/20 bg-red-500/10 p-6">
                            <h4 class="text-red-300 font-bold text-lg mb-2">Error</h4>
                            <p class="text-red-200">${error.message}</p>
                        </div>
                    `;
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-70', 'cursor-not-allowed');
                }
            });

            if (copyAnswerBtn) {
                copyAnswerBtn.addEventListener('click', async function () {
                    const text = answerContent.innerText.trim();
                    if (!text) return;

                    try {
                        await navigator.clipboard.writeText(text);

                        const original = copyAnswerBtn.innerHTML;
                        copyAnswerBtn.innerHTML =
                            '<i class="fas fa-check"></i><span class="font-semibold">Copied!</span>';

                        setTimeout(() => {
                            copyAnswerBtn.innerHTML = original;
                        }, 2000);
                    } catch (e) {
                        alert('Copy failed.');
                    }
                });
            }
        });
    </script>
</x-app-layout>