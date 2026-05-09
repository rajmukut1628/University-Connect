<x-app-layout>
    <div class="min-h-screen text-white">
        <style>
            @keyframes feedFloat {
                0%,100% { transform: translateY(0) scale(1); }
                50% { transform: translateY(-12px) scale(1.03); }
            }

            @keyframes feedShine {
                0% { transform: translateX(-130%); }
                100% { transform: translateX(130%); }
            }

            @keyframes feedIn {
                from { opacity: 0; transform: translateY(18px) scale(.97); }
                to { opacity: 1; transform: translateY(0) scale(1); }
            }

            .feed-orb {
                animation: feedFloat 7s ease-in-out infinite;
            }

            .feed-card {
                animation: feedIn .45s ease both;
            }

            .feed-card::before {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,.08), transparent);
                transform: translateX(-130%);
                transition: .4s ease;
                pointer-events: none;
            }

            .feed-card:hover::before {
                animation: feedShine 1.1s ease;
            }
        </style>

        <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-gradient-to-br from-slate-950 via-indigo-950 to-fuchsia-950 p-5 shadow-2xl md:p-8">

            <div class="feed-orb absolute -top-24 -right-24 h-80 w-80 rounded-full bg-cyan-500/20 blur-3xl"></div>
            <div class="feed-orb absolute top-72 -left-28 h-80 w-80 rounded-full bg-fuchsia-500/20 blur-3xl" style="animation-delay:2s"></div>
            <div class="feed-orb absolute -bottom-28 right-1/3 h-80 w-80 rounded-full bg-emerald-500/15 blur-3xl" style="animation-delay:4s"></div>

            <div class="relative z-10 mb-8">
                <p class="text-xs font-black uppercase tracking-[.35em] text-cyan-300">
                    Social Campus Feed
                </p>

                <h1 class="mt-3 text-4xl font-black md:text-5xl">
                    University Newsfeed
                </h1>

                <p class="mt-3 max-w-2xl text-sm font-semibold text-slate-400">
                    Jobs, events, donations and campus updates appear here like a social media feed.
                </p>
            </div>

            <div class="relative z-10 mx-auto max-w-3xl space-y-6">
                @forelse($feedItems as $item)
                    <article class="feed-card relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/[.07] shadow-2xl backdrop-blur-2xl transition hover:-translate-y-1">

                        <div class="flex items-start gap-4 p-5">
                            <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-br {{ $item['color'] }} text-white shadow-xl">
                                <i class="fas {{ $item['icon'] }} text-xl"></i>
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h3 class="text-lg font-black text-white">
                                        University Connect
                                    </h3>

                                    <span class="rounded-full bg-white/10 px-3 py-1 text-[10px] font-black uppercase tracking-wide text-slate-300">
                                        {{ $item['badge'] }}
                                    </span>
                                </div>

                                <p class="mt-1 text-xs font-semibold text-slate-400">
                                    {{ optional($item['date'])->diffForHumans() ?? 'Recently' }}
                                </p>
                            </div>
                        </div>

                        @if(!empty($item['image']))
                            <a href="{{ $item['url'] }}" class="block">
                                <img src="{{ asset('storage/' . $item['image']) }}"
                                     alt="{{ $item['title'] }}"
                                     class="h-72 w-full object-cover">
                            </a>
                        @endif

                        <div class="p-5">
                            <h2 class="text-2xl font-black text-white">
                                {{ $item['title'] }}
                            </h2>

                            <p class="mt-3 text-sm font-semibold leading-relaxed text-slate-300">
                                {{ \Illuminate\Support\Str::limit(strip_tags($item['description']), 220) }}
                            </p>

                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach($item['meta'] as $meta)
                                    @if($meta)
                                        <span class="rounded-full border border-white/10 bg-white/10 px-3 py-1 text-xs font-black text-slate-300">
                                            {{ $meta }}
                                        </span>
                                    @endif
                                @endforeach
                            </div>

                            <div class="mt-5 border-t border-white/10 pt-4">

    <div class="mb-4 flex items-center justify-between text-xs font-black text-slate-400">
        <span>
            <i class="fas fa-heart text-pink-400"></i>
            {{ $item['likes_count'] ?? 0 }} Likes
        </span>

        <span>
            <i class="fas fa-comment text-cyan-400"></i>
            {{ $item['comments_count'] ?? 0 }} Comments
        </span>

        <span>
            <i class="fas fa-share text-emerald-400"></i>
            {{ $item['shares_count'] ?? 0 }} Shares
        </span>
    </div>

    <div class="grid grid-cols-3 gap-3">
        <form method="POST" action="{{ route('newsfeed.like') }}">
            @csrf
            <input type="hidden" name="feedable_type" value="{{ $item['feedable_type'] }}">
            <input type="hidden" name="feedable_id" value="{{ $item['feedable_id'] }}">

            <button type="submit"
                    class="w-full rounded-2xl border border-white/10 px-4 py-3 text-sm font-black transition hover:scale-105
                    {{ ($item['liked_by_me'] ?? false)
                        ? 'bg-pink-500/20 text-pink-300'
                        : 'bg-white/10 text-slate-300 hover:bg-white/15' }}">
                <i class="fas fa-heart mr-2"></i>
                Like
            </button>
        </form>

        <button type="button"
                onclick="document.getElementById('comment-box-{{ $item['type'] }}-{{ $item['feedable_id'] }}').classList.toggle('hidden')"
                class="w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm font-black text-slate-300 transition hover:scale-105 hover:bg-white/15">
            <i class="fas fa-comment mr-2 text-cyan-300"></i>
            Comment
        </button>

        <form method="POST" action="{{ route('newsfeed.share') }}">
            @csrf
            <input type="hidden" name="feedable_type" value="{{ $item['feedable_type'] }}">
            <input type="hidden" name="feedable_id" value="{{ $item['feedable_id'] }}">

            <button type="submit"
                    class="w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm font-black text-slate-300 transition hover:scale-105 hover:bg-white/15">
                <i class="fas fa-share mr-2 text-emerald-300"></i>
                Share
            </button>
        </form>
    </div>

    <div id="comment-box-{{ $item['type'] }}-{{ $item['feedable_id'] }}"
         class="hidden mt-4 rounded-2xl border border-white/10 bg-slate-950/50 p-4">
        <form method="POST" action="{{ route('newsfeed.comment') }}" class="flex flex-col gap-3">
            @csrf
            <input type="hidden" name="feedable_type" value="{{ $item['feedable_type'] }}">
            <input type="hidden" name="feedable_id" value="{{ $item['feedable_id'] }}">

            <textarea name="comment"
                      rows="3"
                      required
                      placeholder="Write a comment..."
                      class="w-full rounded-2xl border border-white/10 bg-slate-950/80 px-4 py-3 text-sm font-bold text-white placeholder:text-slate-500 outline-none focus:border-cyan-400"></textarea>

            <button type="submit"
                    class="self-end rounded-2xl bg-gradient-to-r from-cyan-500 to-fuchsia-500 px-5 py-2.5 text-sm font-black text-white">
                Post Comment
            </button>
        </form>
    </div>

    @if(!empty($item['recent_comments']) && count($item['recent_comments']))
        <div class="mt-4 space-y-3">
            @foreach($item['recent_comments'] as $comment)
                <div class="rounded-2xl border border-white/10 bg-white/10 p-3">
                    <p class="text-xs font-black text-white">
                        {{ $comment->user->name ?? 'User' }}
                    </p>
                    <p class="mt-1 text-sm font-semibold text-slate-300">
                        {{ $comment->comment }}
                    </p>
                </div>
            @endforeach
        </div>
    @endif

    <div class="mt-5 flex justify-end">
        <a href="{{ $item['url'] }}"
           class="rounded-2xl bg-gradient-to-r {{ $item['color'] }} px-5 py-2.5 text-sm font-black text-white shadow-xl transition hover:scale-105">
            View Details
        </a>
    </div>
</div>
                                <div class="flex items-center gap-3 text-xs font-black text-slate-400">
                                    <span><i class="fas fa-heart text-pink-400"></i> Like</span>
                                    <span><i class="fas fa-comment text-cyan-400"></i> Comment</span>
                                    <span><i class="fas fa-share text-emerald-400"></i> Share</span>
                                </div>

                                <a href="{{ $item['url'] }}"
                                   class="rounded-2xl bg-gradient-to-r {{ $item['color'] }} px-5 py-2.5 text-sm font-black text-white shadow-xl transition hover:scale-105">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-[2rem] border border-white/10 bg-white/[.07] p-10 text-center shadow-2xl backdrop-blur-2xl">
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
    </div>
</x-app-layout>