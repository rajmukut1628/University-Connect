<x-app-layout>
    <div class="min-h-screen text-white">
        <style>
            @keyframes ucFeedFloat {
                0%, 100% { transform: translateY(0) scale(1); }
                50% { transform: translateY(-12px) scale(1.03); }
            }

            @keyframes ucFeedIn {
                from { opacity: 0; transform: translateY(18px) scale(.98); }
                to { opacity: 1; transform: translateY(0) scale(1); }
            }

            @keyframes ucFeedShine {
                0% { transform: translateX(-130%) skewX(-12deg); }
                100% { transform: translateX(130%) skewX(-12deg); }
            }

            .uc-feed-page {
                position: relative;
                overflow: hidden;
                border-radius: 2rem;
                border: 1px solid rgba(255,255,255,.10);
                background:
                    radial-gradient(circle at top left, rgba(34,211,238,.14), transparent 28%),
                    radial-gradient(circle at top right, rgba(236,72,153,.16), transparent 34%),
                    linear-gradient(135deg, #020617 0%, #11194a 48%, #3b0764 100%);
                box-shadow: 0 30px 90px rgba(0,0,0,.42);
            }

            .uc-feed-orb {
                position: absolute;
                border-radius: 9999px;
                filter: blur(75px);
                animation: ucFeedFloat 8s ease-in-out infinite;
                pointer-events: none;
            }

            .uc-feed-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(290px, 1fr));
                gap: 1.25rem;
                align-items: start;
            }

            .uc-feed-card {
                position: relative;
                overflow: hidden;
                border-radius: 1.5rem;
                border: 1px solid rgba(255,255,255,.12);
                background: linear-gradient(145deg, rgba(255,255,255,.09), rgba(255,255,255,.045));
                box-shadow:
                    0 18px 48px rgba(0,0,0,.30),
                    inset 0 1px 0 rgba(255,255,255,.08);
                backdrop-filter: blur(20px);
                animation: ucFeedIn .45s ease both;
                transition: .28s ease;
            }

            .uc-feed-card:hover {
                transform: translateY(-5px);
                border-color: rgba(34,211,238,.28);
                box-shadow:
                    0 24px 70px rgba(0,0,0,.42),
                    0 0 32px rgba(168,85,247,.14);
            }

            .uc-feed-card::before {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,.08), transparent);
                transform: translateX(-130%) skewX(-12deg);
                pointer-events: none;
            }

            .uc-feed-card:hover::before {
                animation: ucFeedShine 1s ease;
            }

            .uc-feed-icon {
                width: 42px;
                height: 42px;
                display: grid;
                place-items: center;
                border-radius: 1rem;
                box-shadow: 0 12px 26px rgba(0,0,0,.25);
            }

            .uc-feed-image {
                height: 160px;
                width: 100%;
                object-fit: cover;
                transition: .35s ease;
            }

            .uc-feed-card:hover .uc-feed-image {
                transform: scale(1.035);
            }

            .uc-glass-pill {
                border: 1px solid rgba(255,255,255,.12);
                background: rgba(255,255,255,.10);
                box-shadow: inset 0 1px 0 rgba(255,255,255,.07);
            }

            .uc-feed-action {
                border: 1px solid rgba(255,255,255,.12);
                background: rgba(255,255,255,.08);
                transition: .22s ease;
            }

            .uc-feed-action:hover {
                transform: translateY(-2px);
                background: rgba(255,255,255,.15);
            }

            @media (min-width: 1280px) {
                .uc-feed-grid {
                    grid-template-columns: repeat(3, minmax(0, 1fr));
                }
            }

            @media (max-width: 768px) {
                .uc-feed-image {
                    height: 145px;
                }
            }
        </style>

        <section class="uc-feed-page p-4 md:p-7">
            <div class="uc-feed-orb -right-24 -top-24 h-80 w-80 bg-cyan-400/20"></div>
            <div class="uc-feed-orb -left-28 top-72 h-80 w-80 bg-fuchsia-500/20" style="animation-delay: 2s;"></div>
            <div class="uc-feed-orb bottom-0 right-1/3 h-72 w-72 bg-emerald-400/14" style="animation-delay: 4s;"></div>

            <div class="relative z-10 mx-auto max-w-7xl">
                <div class="mb-7 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div>
                        <div class="inline-flex items-center gap-2 rounded-full border border-cyan-300/20 bg-cyan-300/10 px-4 py-2 text-xs font-black uppercase tracking-[.25em] text-cyan-200">
                            <i class="fas fa-bolt"></i>
                            Social Campus Feed
                        </div>

                        <h1 class="mt-4 text-4xl font-black tracking-tight md:text-5xl">
                            University Newsfeed
                        </h1>

                        <p class="mt-3 max-w-2xl text-sm font-semibold leading-relaxed text-slate-300">
                            Compact premium card layout. More posts will show beautifully without filling the whole screen.
                        </p>
                    </div>

                    <a href="{{ route('newsfeed.index') }}?refresh={{ now()->timestamp }}"
   class="group rounded-3xl border border-white/10 bg-white/[.07] px-5 py-4 text-sm font-bold text-slate-300 shadow-2xl backdrop-blur-xl transition duration-300 hover:-translate-y-1 hover:bg-white/[.12] hover:shadow-fuchsia-500/20">

    <i class="fas fa-rotate mr-2 text-fuchsia-300 transition duration-500 group-hover:rotate-180"></i>
    Refresh shows different posts
</a>
                </div>

                <div class="uc-feed-grid">
                    @forelse($feedItems as $item)
                                            <article class="uc-feed-card">
                            <div class="flex items-start gap-3 p-4">
                                <div class="uc-feed-icon shrink-0 bg-gradient-to-br {{ $item['color'] }} text-white">
                                    <i class="fas {{ $item['icon'] }} text-base"></i>
                                </div>

                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="truncate text-sm font-black text-white">
                                            University Connect
                                        </h3>

                                        <span class="uc-glass-pill rounded-full px-2.5 py-1 text-[9px] font-black uppercase tracking-wide text-slate-200">
                                            {{ $item['badge'] }}
                                        </span>
                                    </div>

                                    <p class="mt-1 text-[11px] font-bold text-slate-400">
                                        <i class="far fa-clock mr-1"></i>
                                        {{ optional($item['date'])->diffForHumans() ?? 'Recently' }}
                                    </p>
                                </div>
                            </div>

                            @if(!empty($item['image']))
                                <a href="{{ $item['url'] }}" class="relative block overflow-hidden">
                                    <img src="{{ asset('storage/' . $item['image']) }}"
                                         alt="{{ $item['title'] }}"
                                         class="uc-feed-image">

                                    <div class="absolute inset-x-0 bottom-0 h-14 bg-gradient-to-t from-slate-950/70 to-transparent"></div>
                                </a>
                            @endif

                            <div class="p-4">
                                <h2 class="line-clamp-1 text-lg font-black leading-tight text-white">
                                    {{ $item['title'] }}
                                </h2>

                                <p class="mt-2 line-clamp-2 text-xs font-semibold leading-relaxed text-slate-300">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($item['description']), 95) }}
                                </p>

                                @if(!empty($item['meta']) && count($item['meta']))
                                    <div class="mt-3 flex flex-wrap gap-1.5">
                                        @foreach($item['meta'] as $meta)
                                            @if($meta)
                                                <span class="uc-glass-pill rounded-full px-2.5 py-1 text-[10px] font-black text-slate-200">
                                                    {{ \Illuminate\Support\Str::limit($meta, 22) }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                <div class="mt-4 grid grid-cols-3 gap-2 rounded-2xl border border-white/10 bg-slate-950/30 p-2">
                                    <div class="text-center text-[11px] font-black text-slate-300">
                                        <i class="fas fa-heart text-pink-400"></i>
                                        <span>{{ $item['likes_count'] ?? 0 }}</span>
                                    </div>

                                    <div class="text-center text-[11px] font-black text-slate-300">
                                        <i class="fas fa-comment text-cyan-400"></i>
                                        <span>{{ $item['comments_count'] ?? 0 }}</span>
                                    </div>

                                    <div class="text-center text-[11px] font-black text-slate-300">
                                        <i class="fas fa-share text-emerald-400"></i>
                                        <span>{{ $item['shares_count'] ?? 0 }}</span>
                                    </div>
                                </div>

                                <div class="mt-3 grid grid-cols-3 gap-2">
                                    <form method="POST" action="{{ route('newsfeed.like') }}">
                                        @csrf
                                        <input type="hidden" name="feedable_type" value="{{ $item['feedable_type'] }}">
                                        <input type="hidden" name="feedable_id" value="{{ $item['feedable_id'] }}">

                                        <button type="submit"
                                                class="uc-feed-action w-full rounded-xl px-2 py-2 text-[11px] font-black
                                                {{ ($item['liked_by_me'] ?? false)
                                                    ? 'text-pink-300 ring-1 ring-pink-400/20'
                                                    : 'text-slate-300' }}">
                                            <i class="fas fa-heart mr-1 text-pink-400"></i>
                                            Like
                                        </button>
                                    </form>

                                    <button type="button"
                                            onclick="document.getElementById('comment-box-{{ $item['type'] }}-{{ $item['feedable_id'] }}').classList.toggle('hidden')"
                                            class="uc-feed-action w-full rounded-xl px-2 py-2 text-[11px] font-black text-slate-300">
                                        <i class="fas fa-comment mr-1 text-cyan-300"></i>
                                        Comment
                                    </button>

                                    <form method="POST" action="{{ route('newsfeed.share') }}">
                                        @csrf
                                        <input type="hidden" name="feedable_type" value="{{ $item['feedable_type'] }}">
                                        <input type="hidden" name="feedable_id" value="{{ $item['feedable_id'] }}">

                                        <button type="submit"
                                                class="uc-feed-action w-full rounded-xl px-2 py-2 text-[11px] font-black text-slate-300">
                                            <i class="fas fa-share mr-1 text-emerald-300"></i>
                                            Share
                                        </button>
                                    </form>
                                </div>

                                <div id="comment-box-{{ $item['type'] }}-{{ $item['feedable_id'] }}"
                                     class="hidden mt-3 rounded-2xl border border-white/10 bg-slate-950/45 p-3">
                                    <form method="POST" action="{{ route('newsfeed.comment') }}" class="space-y-2">
                                        @csrf
                                        <input type="hidden" name="feedable_type" value="{{ $item['feedable_type'] }}">
                                        <input type="hidden" name="feedable_id" value="{{ $item['feedable_id'] }}">

                                        <textarea name="comment"
                                                  rows="2"
                                                  required
                                                  placeholder="Write a comment..."
                                                  class="w-full resize-none rounded-xl border border-white/10 bg-slate-950/80 px-3 py-2 text-xs font-bold text-white placeholder:text-slate-500 outline-none transition focus:border-cyan-400"></textarea>

                                        <button type="submit"
                                                class="w-full rounded-xl bg-gradient-to-r from-cyan-500 to-fuchsia-500 px-4 py-2 text-xs font-black text-white">
                                            Post Comment
                                        </button>
                                    </form>
                                </div>

                                @if(!empty($item['recent_comments']) && count($item['recent_comments']))
                                    <div class="mt-3 space-y-2">
                                        @foreach($item['recent_comments'] as $comment)
                                            <div class="rounded-xl border border-white/10 bg-white/[.075] p-2.5">
                                                <p class="text-[11px] font-black text-white">
                                                    <i class="fas fa-user-circle mr-1 text-cyan-300"></i>
                                                    {{ $comment->user->name ?? 'User' }}
                                                </p>

                                                <p class="mt-1 line-clamp-2 text-xs font-semibold leading-relaxed text-slate-300">
                                                    {{ $comment->comment }}
                                                </p>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="mt-4 flex items-center justify-between gap-3 border-t border-white/10 pt-4">
                                    <span class="text-[10px] font-bold text-slate-400">
                                        <i class="fas fa-sparkles mr-1 text-yellow-300"></i>
                                        Campus post
                                    </span>

                                    <a href="{{ $item['url'] }}"
                                       class="rounded-xl bg-gradient-to-r {{ $item['color'] }} px-4 py-2 text-xs font-black text-white shadow-xl transition hover:scale-105">
                                        Details
                                        <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full rounded-[2rem] border border-white/10 bg-white/[.07] p-10 text-center shadow-2xl backdrop-blur-2xl">
                            <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-[2rem] bg-gradient-to-br from-cyan-500 to-fuchsia-500 text-3xl shadow-xl">
                                <i class="fas fa-newspaper"></i>
                            </div>

                            <h3 class="mt-6 text-2xl font-black">
                                No posts yet
                            </h3>

                            <p class="mt-2 text-sm font-semibold text-slate-400">
                                Approved jobs, events and donation campaigns will appear here.
                            </p>
                        </div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</x-app-layout>