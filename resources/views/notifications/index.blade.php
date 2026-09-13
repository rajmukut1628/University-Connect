<x-app-layout>

    <x-slot name="header">

        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-pink-950 to-purple-950 p-8 shadow-2xl border border-white/10">

            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(236,72,153,.45),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(168,85,247,.35),transparent_35%)]"></div>


            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">


                <div>

                    <p class="text-sm uppercase tracking-[0.35em] text-pink-300 font-black">

                        Activity Center

                    </p>


                    <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">

                        Notifications

                    </h2>


                    <p class="mt-3 text-slate-300 max-w-2xl">

                        Track your mentorships, jobs, messages,
                        events, account updates and other University Connect activities.

                    </p>

                </div>


                @if(($stats['unread'] ?? 0) > 0)

                    <form
                        method="POST"
                        action="{{ route('notifications.readAll') }}"
                    >

                        @csrf

                        @method('PATCH')


                        <button
                            type="submit"
                            class="px-6 py-4 rounded-2xl bg-white/10 text-white font-black border border-white/10 hover:bg-white/20 transition"
                        >

                            <i class="fas fa-check-double mr-2"></i>

                            Mark All Read

                        </button>

                    </form>

                @endif


            </div>

        </div>

    </x-slot>


    <style>

        .uc-card {
            position: relative;
            overflow: hidden;
            border-radius: 1.5rem;
            border: 1px solid rgba(255,255,255,.16);
            background: linear-gradient(
                135deg,
                rgba(255,255,255,.16),
                rgba(255,255,255,.06)
            );
            backdrop-filter: blur(22px);
            box-shadow: 0 24px 70px rgba(15,23,42,.18);
            transition: .3s ease;
        }

        .uc-card:hover {
            transform: translateY(-3px);
        }

    </style>


    <div class="space-y-8">


        {{-- ========================================================= --}}
        {{-- SUCCESS --}}
        {{-- ========================================================= --}}

        @if(session('success'))

            <div class="uc-card p-5 text-emerald-500 font-black">

                <i class="fas fa-circle-check mr-2"></i>

                {{ session('success') }}

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- STATS --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">


            <a
                href="{{ route('notifications.index') }}"
                class="uc-card p-6"
            >

                <p class="text-sm font-bold text-slate-500 dark:text-slate-300">
                    Total
                </p>

                <h3 class="mt-3 text-4xl font-black text-pink-500">
                    {{ $stats['total'] ?? 0 }}
                </h3>

            </a>


            <a
                href="{{ route('notifications.index', ['status' => 'unread']) }}"
                class="uc-card p-6"
            >

                <p class="text-sm font-bold text-slate-500 dark:text-slate-300">
                    Unread
                </p>

                <h3 class="mt-3 text-4xl font-black text-amber-500">
                    {{ $stats['unread'] ?? 0 }}
                </h3>

            </a>


            <a
                href="{{ route('notifications.index', ['priority' => 'high']) }}"
                class="uc-card p-6"
            >

                <p class="text-sm font-bold text-slate-500 dark:text-slate-300">
                    High Priority
                </p>

                <h3 class="mt-3 text-4xl font-black text-red-500">
                    {{ $stats['high'] ?? 0 }}
                </h3>

            </a>


            <a
                href="{{ route('notifications.index', ['status' => 'read']) }}"
                class="uc-card p-6"
            >

                <p class="text-sm font-bold text-slate-500 dark:text-slate-300">
                    Read
                </p>

                <h3 class="mt-3 text-4xl font-black text-emerald-500">
                    {{ $stats['read'] ?? 0 }}
                </h3>

            </a>


        </div>


        {{-- ========================================================= --}}
        {{-- FILTERS --}}
        {{-- ========================================================= --}}

        <div class="uc-card p-5">


            <form
                method="GET"
                action="{{ route('notifications.index') }}"
                class="relative z-10 grid grid-cols-1 md:grid-cols-4 gap-4"
            >


                <select
                    name="status"
                    class="rounded-2xl border-white/10 dark:bg-slate-950 dark:text-white"
                >

                    <option value="">
                        All Status
                    </option>

                    <option
                        value="unread"
                        @selected(request('status') === 'unread')
                    >
                        Unread
                    </option>

                    <option
                        value="read"
                        @selected(request('status') === 'read')
                    >
                        Read
                    </option>

                </select>


                <select
                    name="type"
                    class="rounded-2xl border-white/10 dark:bg-slate-950 dark:text-white"
                >

                    <option value="">
                        All Activities
                    </option>


                    @foreach($types as $type)

                        <option
                            value="{{ $type }}"
                            @selected(request('type') === $type)
                        >

                            {{ ucwords(
                                str_replace(
                                    '_',
                                    ' ',
                                    $type
                                )
                            ) }}

                        </option>

                    @endforeach

                </select>


                <select
                    name="priority"
                    class="rounded-2xl border-white/10 dark:bg-slate-950 dark:text-white"
                >

                    <option value="">
                        All Priority
                    </option>

                    <option
                        value="high"
                        @selected(request('priority') === 'high')
                    >
                        High
                    </option>

                    <option
                        value="medium"
                        @selected(request('priority') === 'medium')
                    >
                        Medium
                    </option>

                    <option
                        value="low"
                        @selected(request('priority') === 'low')
                    >
                        Low
                    </option>

                </select>


                <button
                    type="submit"
                    class="rounded-2xl bg-gradient-to-r from-pink-500 to-purple-600 px-5 py-3 text-white font-black"
                >

                    <i class="fas fa-filter mr-2"></i>

                    Filter

                </button>


            </form>


        </div>


        {{-- ========================================================= --}}
        {{-- NOTIFICATION LIST --}}
        {{-- ========================================================= --}}

        <div class="space-y-4">


            @forelse($notifications as $notification)


                @php

                    $icon = match($notification->type) {

                        'job',
                        'job_application',
                        'job_approved',
                        'job_rejected'
                            => 'fa-briefcase',

                        'event',
                        'event_registration',
                        'event_approved',
                        'event_rejected'
                            => 'fa-calendar-days',

                        'mentorship',
                        'mentorship_request',
                        'mentorship_accepted',
                        'mentorship_rejected',
                        'mentorship_completed'
                            => 'fa-handshake',

                        'message'
                            => 'fa-message',

                        'donation'
                            => 'fa-hand-holding-heart',

                        'account',
                        'account_blocked',
                        'account_unblocked',
                        'account_updated'
                            => 'fa-user-shield',

                        'alumni_conversion'
                            => 'fa-user-graduate',

                        default
                            => 'fa-bell',
                    };

                @endphp


                <div
                    class="uc-card p-5
                    {{ !$notification->is_read
                        ? 'ring-2 ring-pink-500/30'
                        : '' }}"
                >


                    <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">


                        <div class="flex items-start gap-4 flex-1">


                            {{-- ICON / ACTOR --}}

                            @if($notification->actor?->profile_image)

                                <img
                                    src="{{ $notification->actor->getProfileImageUrl() }}"
                                    alt="{{ $notification->actor->name }}"
                                    class="h-14 w-14 shrink-0 rounded-2xl object-cover"
                                >

                            @else

                                <div
                                    class="h-14 w-14 shrink-0 rounded-2xl flex items-center justify-center shadow-xl

                                    @if($notification->priority === 'high')
                                        bg-gradient-to-br from-red-500 to-pink-600

                                    @elseif($notification->priority === 'low')
                                        bg-gradient-to-br from-slate-500 to-slate-700

                                    @else
                                        bg-gradient-to-br from-pink-500 to-purple-600
                                    @endif"
                                >

                                    <i class="fas {{ $icon }} text-xl text-white"></i>

                                </div>

                            @endif


                            <div class="min-w-0 flex-1">


                                <div class="flex flex-wrap items-center gap-2">


                                    <h3 class="text-lg font-black text-slate-900 dark:text-white">

                                        {{ $notification->title }}

                                    </h3>


                                    @if(!$notification->is_read)

                                        <span class="px-2.5 py-1 rounded-full bg-pink-500/15 text-pink-600 text-[10px] font-black">

                                            NEW

                                        </span>

                                    @endif


                                    @if($notification->priority === 'high')

                                        <span class="px-2.5 py-1 rounded-full bg-red-500/15 text-red-600 text-[10px] font-black">

                                            HIGH

                                        </span>

                                    @endif


                                </div>


                                @if($notification->actor)

                                    <p class="mt-1 text-xs font-bold text-purple-500">

                                        {{ $notification->actor->name }}

                                    </p>

                                @endif


                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-300 leading-6">

                                    {{ $notification->message }}

                                </p>


                                <div class="mt-3 flex flex-wrap items-center gap-4 text-xs text-slate-400 font-bold">


                                    <span>

                                        <i class="fas fa-clock mr-1"></i>

                                        {{ $notification->created_at?->diffForHumans() }}

                                    </span>


                                    <span>

                                        <i class="fas {{ $icon }} mr-1"></i>

                                        {{ ucwords(
                                            str_replace(
                                                '_',
                                                ' ',
                                                $notification->type
                                            )
                                        ) }}

                                    </span>


                                </div>


                            </div>


                        </div>


                        {{-- ACTIONS --}}

                        <div class="flex flex-wrap items-center gap-2">


                            @if($notification->action_url)


                                @if(!$notification->is_read)

                                    <form
                                        method="POST"
                                        action="{{ route(
                                            'notifications.read',
                                            $notification
                                        ) }}?open=1"
                                    >

                                        @csrf
                                        @method('PATCH')


                                        <button
                                            type="submit"
                                            class="px-5 py-3 rounded-2xl bg-gradient-to-r from-pink-500 to-purple-600 text-white font-black shadow-xl hover:scale-105 transition"
                                        >

                                            View Activity

                                            <i class="fas fa-arrow-right ml-2"></i>

                                        </button>

                                    </form>


                                @else

                                    <a
                                        href="{{ $notification->action_url }}"
                                        class="px-5 py-3 rounded-2xl bg-gradient-to-r from-pink-500 to-purple-600 text-white font-black shadow-xl hover:scale-105 transition"
                                    >

                                        View Activity

                                        <i class="fas fa-arrow-right ml-2"></i>

                                    </a>

                                @endif


                            @elseif(!$notification->is_read)


                                <form
                                    method="POST"
                                    action="{{ route(
                                        'notifications.read',
                                        $notification
                                    ) }}"
                                >

                                    @csrf
                                    @method('PATCH')


                                    <button
                                        type="submit"
                                        class="px-5 py-3 rounded-2xl bg-pink-500/10 text-pink-600 font-black"
                                    >

                                        <i class="fas fa-check mr-2"></i>

                                        Mark Read

                                    </button>

                                </form>


                            @else


                                <span class="px-5 py-3 rounded-2xl bg-emerald-500/15 text-emerald-600 font-black">

                                    <i class="fas fa-check-double mr-2"></i>

                                    Read

                                </span>


                            @endif


                        </div>


                    </div>


                </div>


            @empty


                <div class="uc-card p-12 text-center">


                    <div class="relative z-10">


                        <div class="mx-auto h-20 w-20 rounded-3xl bg-gradient-to-br from-pink-500 to-purple-600 flex items-center justify-center">

                            <i class="fas fa-bell-slash text-3xl text-white"></i>

                        </div>


                        <h3 class="mt-6 text-2xl font-black text-slate-900 dark:text-white">

                            No Notifications

                        </h3>


                        <p class="mt-2 text-slate-500">

                            Your University Connect activities will appear here.

                        </p>


                    </div>


                </div>


            @endforelse


        </div>


        <div>
            {{ $notifications->links() }}
        </div>


    </div>

</x-app-layout>