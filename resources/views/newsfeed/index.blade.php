<x-app-layout>

    <div class="min-h-screen">

        <style>

            .uc-feed-shell {
                width: 100%;
                max-width: 720px;
                margin: 0 auto;
            }

            .uc-post {
                overflow: hidden;
                border-radius: 1.5rem;
                border: 1px solid rgba(255, 255, 255, .10);
                background: linear-gradient(
                    145deg,
                    rgba(30, 41, 59, .96),
                    rgba(30, 27, 75, .96)
                );
                box-shadow: 0 18px 55px rgba(0, 0, 0, .25);
                transition: .25s ease;
            }

            .uc-post:hover {
                border-color: rgba(168, 85, 247, .25);
            }

            .uc-avatar {
                width: 46px;
                height: 46px;
                border-radius: 9999px;
                display: flex;
                align-items: center;
                justify-content: center;
                flex-shrink: 0;
                color: white;
                box-shadow: 0 8px 24px rgba(0,0,0,.25);
            }

       .uc-post-image {
    display: block;
    width: 100%;
    height: 280px;
    object-fit: contain;
    object-position: center center;
    background: #0f172a;
}

            .uc-action-btn {
                width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: .5rem;
                padding: .7rem;
                border-radius: .8rem;
                font-weight: 800;
                font-size: .875rem;
                color: #cbd5e1;
                transition: .2s ease;
            }

            .uc-action-btn:hover {
                background: rgba(255, 255, 255, .07);
                color: white;
            }

            .uc-comment-box {
                background: rgba(255, 255, 255, .055);
                border: 1px solid rgba(255, 255, 255, .08);
            }

            .uc-meta-pill {
                border: 1px solid rgba(255, 255, 255, .08);
                background: rgba(255, 255, 255, .06);
            }

            @media (max-width: 768px) {

    .uc-post {
        border-radius: 1rem;
    }

    .uc-feed-shell {
        max-width: 100%;
    }

    .uc-post-image {
        height: 230px;
        object-fit: contain;
        object-position: center center;
    }
}

        </style>


        <div class="mx-auto max-w-7xl px-3 sm:px-4 lg:px-6 py-6">


            {{-- ========================================================= --}}
            {{-- PAGE HEADER --}}
            {{-- ========================================================= --}}

            <div class="uc-feed-shell mb-6">

                <div class="flex items-center justify-between gap-4">

                    <div>

                        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">
                            Newsfeed
                        </h1>

                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                            Jobs, events and campus updates from University Connect.
                        </p>

                    </div>


                    <a
                        href="{{ route('newsfeed.index') }}"
                        class="h-11 w-11 shrink-0 rounded-full bg-white/10 border border-white/10 flex items-center justify-center text-slate-300 hover:text-white hover:bg-white/15 transition"
                        title="Refresh Feed"
                    >

                        <i class="fas fa-rotate"></i>

                    </a>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- SUCCESS MESSAGE --}}
            {{-- ========================================================= --}}

            @if(session('success'))

                <div class="uc-feed-shell mb-5">

                    <div class="rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-sm font-bold text-emerald-400">

                        <i class="fas fa-circle-check mr-2"></i>

                        {{ session('success') }}

                    </div>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- ERRORS --}}
            {{-- ========================================================= --}}

            @if($errors->any())

                <div class="uc-feed-shell mb-5">

                    <div class="rounded-2xl border border-red-500/20 bg-red-500/10 px-4 py-3">

                        @foreach($errors->all() as $error)

                            <p class="text-sm font-bold text-red-400">

                                <i class="fas fa-circle-exclamation mr-2"></i>

                                {{ $error }}

                            </p>

                        @endforeach

                    </div>

                </div>

            @endif


            {{-- ========================================================= --}}
            {{-- FEED --}}
            {{-- ========================================================= --}}

            <div class="uc-feed-shell space-y-6">


                @forelse($feedItems as $item)


                    <article class="uc-post">


                        {{-- ================================================= --}}
                        {{-- POST HEADER --}}
                        {{-- ================================================= --}}

                        <div class="flex items-center justify-between gap-4 px-4 sm:px-5 py-4">


                            <div class="flex items-center gap-3 min-w-0">


                                <div class="uc-avatar bg-gradient-to-br {{ $item['color'] }}">

                                    <i class="fas {{ $item['icon'] }}"></i>

                                </div>


                                <div class="min-w-0">


                                    <div class="flex items-center gap-2 flex-wrap">

                                        <p class="font-black text-white">
                                            University Connect
                                        </p>


                                        <span class="text-blue-400 text-xs">

                                            <i class="fas fa-circle-check"></i>

                                        </span>

                                    </div>


                                    <div class="mt-0.5 flex items-center gap-2 text-xs text-slate-400">

                                        <span>
                                            {{ optional($item['date'])->diffForHumans() ?? 'Recently' }}
                                        </span>

                                        <span>
                                            ·
                                        </span>

                                        <span>

                                            <i class="fas fa-earth-americas"></i>

                                        </span>

                                    </div>


                                </div>

                            </div>


                            <span class="shrink-0 rounded-full bg-white/[.07] border border-white/10 px-3 py-1.5 text-[10px] sm:text-xs font-black text-slate-300">

                                {{ $item['badge'] }}

                            </span>


                        </div>


                        {{-- ================================================= --}}
                        {{-- POST TEXT --}}
                        {{-- ================================================= --}}

                        <div class="px-4 sm:px-5 pb-4">


                            <h2 class="text-lg sm:text-xl font-black text-white leading-snug">

                                {{ $item['title'] }}

                            </h2>


                            @if(!empty($item['description']))

                                <p class="mt-2 text-sm leading-6 text-slate-300">

                                    {{ \Illuminate\Support\Str::limit(
                                        strip_tags($item['description']),
                                        350
                                    ) }}

                                </p>

                            @endif


                            {{-- META --}}

                            @if(!empty($item['meta']))

                                <div class="mt-4 flex flex-wrap gap-2">

                                    @foreach($item['meta'] as $meta)

                                        @if($meta)

                                            <span class="uc-meta-pill rounded-full px-3 py-1.5 text-xs font-bold text-slate-300">

                                                {{ $meta }}

                                            </span>

                                        @endif

                                    @endforeach

                                </div>

                            @endif


                        </div>


                        {{-- ================================================= --}}
                        {{-- IMAGE --}}
                        {{-- ================================================= --}}

                        @if(!empty($item['image']))

                            <a
                                href="{{ $item['url'] }}"
                                class="block bg-slate-950"
                            >

                                <img
                                    src="{{ asset('storage/' . $item['image']) }}"
                                    alt="{{ $item['title'] }}"
                                    class="uc-post-image"
                                >

                            </a>

                        @endif


                        {{-- ================================================= --}}
                        {{-- ENGAGEMENT COUNTS --}}
                        {{-- ================================================= --}}

                        <div class="flex items-center justify-between gap-4 px-4 sm:px-5 pt-4 pb-3 text-sm text-slate-400">


                            <div class="flex items-center gap-2">

                                <div class="h-6 w-6 rounded-full bg-gradient-to-br from-pink-500 to-red-500 text-white flex items-center justify-center text-[10px]">

                                    <i class="fas fa-heart"></i>

                                </div>

                                <span>

                                    {{ $item['likes_count'] ?? 0 }}

                                </span>

                            </div>


                            <div class="flex items-center gap-4">

                                <button
                                    type="button"
                                    onclick="document.getElementById('comment-box-{{ $item['type'] }}-{{ $item['feedable_id'] }}').classList.toggle('hidden')"
                                    class="hover:text-white transition"
                                >

                                    {{ $item['comments_count'] ?? 0 }}
                                    comments

                                </button>


                                <span>

                                    {{ $item['shares_count'] ?? 0 }}
                                    shares

                                </span>

                            </div>


                        </div>


                        {{-- ================================================= --}}
                        {{-- ACTION BUTTONS --}}
                        {{-- ================================================= --}}

                        <div class="mx-4 sm:mx-5 border-t border-b border-white/10">

                            <div class="grid grid-cols-3 gap-1 py-1">


                                {{-- LIKE --}}

                                <form
                                    method="POST"
                                    action="{{ route('newsfeed.like') }}"
                                >

                                    @csrf


                                    <input
                                        type="hidden"
                                        name="feedable_type"
                                        value="{{ $item['feedable_type'] }}"
                                    >


                                    <input
                                        type="hidden"
                                        name="feedable_id"
                                        value="{{ $item['feedable_id'] }}"
                                    >


                                    <button
                                        type="submit"
                                        class="uc-action-btn {{ ($item['liked_by_me'] ?? false) ? '!text-pink-400' : '' }}"
                                    >

                                        @if($item['liked_by_me'] ?? false)

                                            <i class="fas fa-heart text-pink-400"></i>

                                        @else

                                            <i class="far fa-heart"></i>

                                        @endif


                                        <span>
                                            Like
                                        </span>

                                    </button>

                                </form>


                                {{-- COMMENT --}}

                                <button
                                    type="button"
                                    onclick="document.getElementById('comment-box-{{ $item['type'] }}-{{ $item['feedable_id'] }}').classList.toggle('hidden')"
                                    class="uc-action-btn"
                                >

                                    <i class="far fa-comment"></i>

                                    <span>
                                        Comment
                                    </span>

                                </button>


                                {{-- SHARE --}}

                                <form
                                    method="POST"
                                    action="{{ route('newsfeed.share') }}"
                                >

                                    @csrf


                                    <input
                                        type="hidden"
                                        name="feedable_type"
                                        value="{{ $item['feedable_type'] }}"
                                    >


                                    <input
                                        type="hidden"
                                        name="feedable_id"
                                        value="{{ $item['feedable_id'] }}"
                                    >


                                    <button
                                        type="submit"
                                        class="uc-action-btn"
                                    >

                                        <i class="fas fa-share"></i>

                                        <span>
                                            Share
                                        </span>

                                    </button>

                                </form>


                            </div>

                        </div>


                        {{-- ================================================= --}}
                        {{-- COMMENT FORM --}}
                        {{-- ================================================= --}}

                        <div
                            id="comment-box-{{ $item['type'] }}-{{ $item['feedable_id'] }}"
                            class="hidden px-4 sm:px-5 pt-4"
                        >

                            <form
                                method="POST"
                                action="{{ route('newsfeed.comment') }}"
                                class="flex items-start gap-3"
                            >

                                @csrf


                                <input
                                    type="hidden"
                                    name="feedable_type"
                                    value="{{ $item['feedable_type'] }}"
                                >


                                <input
                                    type="hidden"
                                    name="feedable_id"
                                    value="{{ $item['feedable_id'] }}"
                                >


                                {{-- CURRENT USER AVATAR --}}

                                @if(auth()->user()->profile_image)

                                    <img
                                        src="{{ auth()->user()->getProfileImageUrl() }}"
                                        alt="{{ auth()->user()->name }}"
                                        class="h-9 w-9 shrink-0 rounded-full object-cover bg-white"
                                    >

                                @else

                                    <div class="h-9 w-9 shrink-0 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 text-white flex items-center justify-center text-xs font-black">

                                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                                    </div>

                                @endif


                                <div class="flex-1">

                                    <div class="flex items-end gap-2 rounded-2xl bg-white/[.07] border border-white/10 px-4 py-2">

                                        <textarea
                                            name="comment"
                                            rows="1"
                                            required
                                            maxlength="1000"
                                            placeholder="Write a comment..."
                                            class="flex-1 resize-none bg-transparent border-0 p-0 text-sm text-white placeholder-slate-500 focus:ring-0"
                                        ></textarea>


                                        <button
                                            type="submit"
                                            class="h-8 w-8 shrink-0 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 text-white flex items-center justify-center"
                                            title="Post Comment"
                                        >

                                            <i class="fas fa-paper-plane text-xs"></i>

                                        </button>

                                    </div>

                                </div>

                            </form>

                        </div>


                        {{-- ================================================= --}}
                        {{-- RECENT COMMENTS --}}
                        {{-- ================================================= --}}

                        @if(!empty($item['recent_comments']) && count($item['recent_comments']))


                            <div class="px-4 sm:px-5 pt-4 space-y-3">


                                @foreach($item['recent_comments'] as $comment)


                                    <div class="flex items-start gap-3">


                                        {{-- COMMENT USER AVATAR --}}

                                        @if($comment->user?->profile_image)

                                            <img
                                                src="{{ $comment->user->getProfileImageUrl() }}"
                                                alt="{{ $comment->user->name ?? 'User' }}"
                                                class="h-9 w-9 shrink-0 rounded-full object-cover bg-white"
                                            >

                                        @else

                                            <div class="h-9 w-9 shrink-0 rounded-full bg-gradient-to-br from-cyan-500 to-purple-600 text-white flex items-center justify-center text-xs font-black">

                                                {{ strtoupper(substr($comment->user->name ?? 'U', 0, 1)) }}

                                            </div>

                                        @endif


                                        <div class="min-w-0">


                                            <div class="uc-comment-box rounded-2xl px-4 py-2.5">


                                                <p class="text-xs font-black text-white">

                                                    {{ $comment->user->name ?? 'User' }}

                                                </p>


                                                <p class="mt-1 text-sm leading-5 text-slate-300">

                                                    {{ $comment->comment }}

                                                </p>


                                            </div>


                                            <p class="mt-1 ml-2 text-[11px] text-slate-500">

                                                {{ optional($comment->created_at)->diffForHumans() }}

                                            </p>


                                        </div>


                                    </div>


                                @endforeach


                            </div>


                        @endif


                        {{-- ================================================= --}}
                        {{-- DETAILS --}}
                        {{-- ================================================= --}}

                        <div class="px-4 sm:px-5 py-4">


                            <a
                                href="{{ $item['url'] }}"
                                class="flex items-center justify-center gap-2 w-full rounded-xl bg-white/[.06] border border-white/10 py-2.5 text-sm font-black text-slate-300 hover:bg-white/[.10] hover:text-white transition"
                            >

                                View Details

                                <i class="fas fa-arrow-right text-xs"></i>

                            </a>


                        </div>


                    </article>


                @empty


                    {{-- ================================================= --}}
                    {{-- EMPTY --}}
                    {{-- ================================================= --}}

                    <div class="rounded-3xl border border-white/10 bg-white/[.05] p-12 text-center">


                        <div class="mx-auto h-16 w-16 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-xl">

                            <i class="fas fa-newspaper"></i>

                        </div>


                        <h3 class="mt-5 text-xl font-black text-white">
                            No posts yet
                        </h3>


                        <p class="mt-2 text-sm text-slate-400">

                            Approved jobs, events and donation campaigns will appear here.

                        </p>


                    </div>


                @endforelse


            </div>


        </div>

    </div>

</x-app-layout>