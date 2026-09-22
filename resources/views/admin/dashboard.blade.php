<x-app-layout>

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <x-slot name="header">

        <div class="relative overflow-hidden rounded-3xl
                    bg-gradient-to-r from-slate-950 via-indigo-950 to-purple-950
                    p-8 shadow-2xl border border-white/10">

            <div class="absolute inset-0
                        bg-[radial-gradient(circle_at_top_left,rgba(99,102,241,.45),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(236,72,153,.35),transparent_35%)]">
            </div>

            <div class="absolute -top-24 -right-24 h-72 w-72
                        rounded-full bg-fuchsia-500/20 blur-3xl">
            </div>

            <div class="absolute -bottom-24 -left-24 h-72 w-72
                        rounded-full bg-cyan-500/20 blur-3xl">
            </div>


            <div class="relative z-10 flex flex-col lg:flex-row
                        lg:items-center lg:justify-between gap-6">

                <div>

                    <p class="text-sm uppercase tracking-[0.35em]
                              text-cyan-300 font-bold">

                        {{ auth()->user()->role === 'super_admin'
                            ? 'Super Admin Control'
                            : 'Administration'
                        }}

                    </p>


                    <h2 class="mt-3 text-4xl lg:text-5xl
                               font-black text-white">

                        {{ auth()->user()->role === 'super_admin'
                            ? 'Super Admin Command Center'
                            : 'Administrative Command Center'
                        }}

                    </h2>


                    <p class="mt-3 text-slate-300 max-w-2xl">

                        Manage users, jobs, events, verification
                        and university activities from one place.

                    </p>

                </div>


                <div class="flex items-center gap-4">

                    <div class="rounded-2xl border border-white/10
                                bg-white/10 backdrop-blur-xl
                                px-5 py-4">

                        <p class="text-xs text-slate-300">
                            Logged in as
                        </p>

                        <p class="font-bold text-white">
                            {{ Auth::user()->name }}
                        </p>

                    </div>


                    <div class="h-16 w-16 rounded-2xl
                                bg-gradient-to-br from-cyan-400 to-fuchsia-500
                                flex items-center justify-center
                                shadow-2xl shadow-fuchsia-500/30">

                        @if(auth()->user()->role === 'super_admin')

                            <i class="fas fa-crown text-2xl text-white"></i>

                        @else

                            <i class="fas fa-user-shield text-2xl text-white"></i>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </x-slot>


    {{-- ========================================================= --}}
    {{-- CUSTOM STYLE --}}
    {{-- ========================================================= --}}

    <style>

        @keyframes ucFloat {

            0%, 100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-8px);
            }

        }


        .uc-card {

            position: relative;

            overflow: hidden;

            border-radius: 1.5rem;

            border: 1px solid rgba(255,255,255,.16);

            background:
                linear-gradient(
                    135deg,
                    rgba(255,255,255,.16),
                    rgba(255,255,255,.06)
                );

            backdrop-filter: blur(22px);

            box-shadow:
                0 24px 70px rgba(15,23,42,.18);

            transition:
                transform .25s ease,
                border-color .25s ease,
                box-shadow .25s ease;

        }


        .uc-card:hover {

            transform: translateY(-4px);

            border-color: rgba(99,102,241,.35);

            box-shadow:
                0 28px 80px rgba(15,23,42,.25);

        }


        .uc-float {

            animation:
                ucFloat 5s ease-in-out infinite;

        }


        .uc-action {

            position: relative;

            overflow: hidden;

            min-height: 145px;

            border-radius: 1.4rem;

            border: 1px solid rgba(255,255,255,.10);

            transition:
                transform .25s ease,
                border-color .25s ease,
                box-shadow .25s ease;

        }


        .uc-action:hover {

            transform: translateY(-5px);

            border-color: rgba(255,255,255,.25);

            box-shadow:
                0 20px 45px rgba(15,23,42,.25);

        }

    </style>


    {{-- ========================================================= --}}
    {{-- ROLE / ROUTES --}}
    {{-- ========================================================= --}}

    @php

        $isSuperAdmin =
            auth()->user()->role === 'super_admin';


        $verificationRoute = $isSuperAdmin
            ? route('superadmin.verification.index')
            : route('admin.verification.index');


        $usersRoute = $isSuperAdmin
            ? route('superadmin.users.index')
            : route('admin.users.index');


        /*
        |--------------------------------------------------------------------------
        | Dashboard Statistic Cards
        |--------------------------------------------------------------------------
        */

        $cards = [

            [
                'title' => 'Total Users',
                'value' => $stats['total_users'] ?? 0,
                'icon' => 'fa-users',
                'from' => 'from-cyan-400',
                'to' => 'to-blue-600',
            ],

            [
                'title' => 'Students',
                'value' => $stats['students'] ?? 0,
                'icon' => 'fa-user-graduate',
                'from' => 'from-emerald-400',
                'to' => 'to-green-600',
            ],

            [
                'title' => 'Alumni',
                'value' => $stats['alumni'] ?? 0,
                'icon' => 'fa-award',
                'from' => 'from-amber-400',
                'to' => 'to-orange-600',
            ],

            [
                'title' => 'Jobs',
                'value' => $stats['job_postings'] ?? 0,
                'icon' => 'fa-briefcase',
                'from' => 'from-pink-400',
                'to' => 'to-rose-600',
            ],

            [
                'title' => 'Events',
                'value' => $stats['events'] ?? 0,
                'icon' => 'fa-calendar-check',
                'from' => 'from-violet-400',
                'to' => 'to-purple-700',
            ],

            [
                'title' => 'Mentorships',
                'value' => $stats['mentorships'] ?? 0,
                'icon' => 'fa-handshake-angle',
                'from' => 'from-indigo-400',
                'to' => 'to-purple-600',
            ],

        ];

    @endphp



    <div class="space-y-8">


        {{-- ========================================================= --}}
        {{-- STATISTIC CARDS --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1
                    sm:grid-cols-2
                    lg:grid-cols-3
                    xl:grid-cols-6
                    gap-5">


            @foreach($cards as $index => $card)

                <div class="uc-card p-5">

                    <div class="relative z-10">


                        <div class="flex items-center justify-between gap-4">

                            <div>

                                <p class="text-sm font-bold
                                          text-slate-500
                                          dark:text-slate-300">

                                    {{ $card['title'] }}

                                </p>


                                <h3 class="mt-3 text-3xl font-black
                                           bg-gradient-to-r
                                           {{ $card['from'] }}
                                           {{ $card['to'] }}
                                           bg-clip-text
                                           text-transparent">

                                    {{ $card['value'] }}

                                </h3>

                            </div>


                            <div class="h-14 w-14 shrink-0
                                        rounded-2xl
                                        bg-gradient-to-br
                                        {{ $card['from'] }}
                                        {{ $card['to'] }}
                                        flex items-center
                                        justify-center
                                        shadow-xl
                                        uc-float">

                                <i class="fas {{ $card['icon'] }}
                                          text-xl text-white">
                                </i>

                            </div>

                        </div>


                        <div class="mt-5 h-1.5 rounded-full
                                    bg-white/20 overflow-hidden">

                            <div
                                class="h-full rounded-full
                                       bg-gradient-to-r
                                       {{ $card['from'] }}
                                       {{ $card['to'] }}"
                                style="width:
                                    {{ min(100, 35 + ($index * 10)) }}%">
                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>



        {{-- ========================================================= --}}
        {{-- QUICK ACTIONS --}}
        {{-- ========================================================= --}}

        <div class="uc-card p-7 lg:p-8">


            <div class="relative z-10">


                <div class="flex flex-col
                            lg:flex-row
                            lg:items-end
                            lg:justify-between
                            gap-4
                            mb-7">


                    <div>

                        <p class="text-sm uppercase
                                  tracking-[0.25em]
                                  text-fuchsia-500
                                  font-black">

                            Quick Actions

                        </p>


                        <h3 class="mt-2 text-3xl
                                   font-black
                                   text-slate-900
                                   dark:text-white">

                            {{ $isSuperAdmin
                                ? 'Super Admin Tools'
                                : 'Admin Tools'
                            }}

                        </h3>


                        <p class="mt-2 text-sm
                                  text-slate-500
                                  dark:text-slate-400">

                            Access the most important management tools
                            directly from your dashboard.

                        </p>

                    </div>


                    <div class="hidden lg:flex
                                h-14 w-14
                                rounded-2xl
                                bg-gradient-to-br
                                from-indigo-500
                                to-fuchsia-500
                                items-center
                                justify-center
                                text-white
                                shadow-xl">

                        <i class="fas fa-bolt text-xl"></i>

                    </div>

                </div>



                <div class="grid grid-cols-1
                            md:grid-cols-2
                            xl:grid-cols-3
                            gap-5">


                    {{-- Verification --}}

                    <a
                        href="{{ $verificationRoute }}"
                        class="uc-action
                               bg-gradient-to-br
                               from-emerald-500/20
                               to-cyan-500/10
                               p-5 group"
                    >

                        <div class="flex items-start
                                    justify-between gap-4">

                            <div class="h-12 w-12
                                        rounded-2xl
                                        bg-emerald-500/20
                                        text-emerald-500
                                        flex items-center
                                        justify-center">

                                <i class="fas fa-user-check text-xl"></i>

                            </div>


                            <i class="fas fa-arrow-right
                                      text-slate-400
                                      group-hover:translate-x-1
                                      transition-transform">
                            </i>

                        </div>


                        <h4 class="mt-5 text-lg font-black
                                   text-slate-900
                                   dark:text-white">

                            Verification

                        </h4>


                        <p class="mt-2 text-sm
                                  text-slate-500
                                  dark:text-slate-400">

                            Review and manage user verification.

                        </p>

                    </a>



                    {{-- User Management --}}

                    <a
                        href="{{ $usersRoute }}"
                        class="uc-action
                               bg-gradient-to-br
                               from-violet-500/20
                               to-fuchsia-500/10
                               p-5 group"
                    >

                        <div class="flex items-start
                                    justify-between gap-4">

                            <div class="h-12 w-12
                                        rounded-2xl
                                        bg-violet-500/20
                                        text-violet-500
                                        flex items-center
                                        justify-center">

                                <i class="fas fa-users-cog text-xl"></i>

                            </div>


                            <i class="fas fa-arrow-right
                                      text-slate-400
                                      group-hover:translate-x-1
                                      transition-transform">
                            </i>

                        </div>


                        <h4 class="mt-5 text-lg font-black
                                   text-slate-900
                                   dark:text-white">

                            User Management

                        </h4>


                        <p class="mt-2 text-sm
                                  text-slate-500
                                  dark:text-slate-400">

                            Manage students, alumni and user accounts.

                        </p>

                    </a>



                    {{-- Alumni Conversion --}}

                    <a
                        href="{{ route('alumni-conversion.index') }}"
                        class="uc-action
                               bg-gradient-to-br
                               from-cyan-500/20
                               to-emerald-500/10
                               p-5 group"
                    >

                        <div class="flex items-start
                                    justify-between gap-4">

                            <div class="h-12 w-12
                                        rounded-2xl
                                        bg-cyan-500/20
                                        text-cyan-500
                                        flex items-center
                                        justify-center">

                                <i class="fas fa-user-graduate text-xl"></i>

                            </div>


                            <i class="fas fa-arrow-right
                                      text-slate-400
                                      group-hover:translate-x-1
                                      transition-transform">
                            </i>

                        </div>


                        <h4 class="mt-5 text-lg font-black
                                   text-slate-900
                                   dark:text-white">

                            Alumni Conversion

                        </h4>


                        <p class="mt-2 text-sm
                                  text-slate-500
                                  dark:text-slate-400">

                            Review student to alumni conversion requests.

                        </p>

                    </a>



                    {{-- Manage Jobs --}}

                    <a
                        href="{{ route('jobs.index') }}"
                        class="uc-action
                               bg-gradient-to-br
                               from-pink-500/20
                               to-rose-500/10
                               p-5 group"
                    >

                        <div class="flex items-start
                                    justify-between gap-4">

                            <div class="h-12 w-12
                                        rounded-2xl
                                        bg-pink-500/20
                                        text-pink-500
                                        flex items-center
                                        justify-center">

                                <i class="fas fa-briefcase text-xl"></i>

                            </div>


                            <i class="fas fa-arrow-right
                                      text-slate-400
                                      group-hover:translate-x-1
                                      transition-transform">
                            </i>

                        </div>


                        <h4 class="mt-5 text-lg font-black
                                   text-slate-900
                                   dark:text-white">

                            Manage Jobs

                        </h4>


                        <p class="mt-2 text-sm
                                  text-slate-500
                                  dark:text-slate-400">

                            Review, approve and manage job posts.

                        </p>

                    </a>



                    {{-- Manage Events --}}

                    <a
                        href="{{ route('events.index') }}"
                        class="uc-action
                               bg-gradient-to-br
                               from-amber-500/20
                               to-orange-500/10
                               p-5 group"
                    >

                        <div class="flex items-start
                                    justify-between gap-4">

                            <div class="h-12 w-12
                                        rounded-2xl
                                        bg-amber-500/20
                                        text-amber-500
                                        flex items-center
                                        justify-center">

                                <i class="fas fa-calendar-days text-xl"></i>

                            </div>


                            <i class="fas fa-arrow-right
                                      text-slate-400
                                      group-hover:translate-x-1
                                      transition-transform">
                            </i>

                        </div>


                        <h4 class="mt-5 text-lg font-black
                                   text-slate-900
                                   dark:text-white">

                            Manage Events

                        </h4>


                        <p class="mt-2 text-sm
                                  text-slate-500
                                  dark:text-slate-400">

                            Create and manage university events.

                        </p>

                    </a>



                    {{-- ================================================= --}}
                    {{-- SUPER ADMIN ONLY --}}
                    {{-- ================================================= --}}

                    @if($isSuperAdmin)


                        {{-- Verified Users DB --}}

                        <a
                            href="{{ route('superadmin.verified-users.index') }}"
                            class="uc-action
                                   bg-gradient-to-br
                                   from-blue-500/20
                                   to-cyan-500/10
                                   p-5 group"
                        >

                            <div class="flex items-start
                                        justify-between gap-4">

                                <div class="h-12 w-12
                                            rounded-2xl
                                            bg-blue-500/20
                                            text-blue-500
                                            flex items-center
                                            justify-center">

                                    <i class="fas fa-database text-xl"></i>

                                </div>


                                <i class="fas fa-arrow-right
                                          text-slate-400
                                          group-hover:translate-x-1
                                          transition-transform">
                                </i>

                            </div>


                            <h4 class="mt-5 text-lg font-black
                                       text-slate-900
                                       dark:text-white">

                                Verified Users DB

                            </h4>


                            <p class="mt-2 text-sm
                                      text-slate-500
                                      dark:text-slate-400">

                                Manage the official verified users database.

                            </p>

                        </a>



                        {{-- Create Admin --}}

                        <a
                            href="{{ route('superadmin.admins.create') }}"
                            class="uc-action
                                   bg-gradient-to-br
                                   from-orange-500/20
                                   to-amber-500/10
                                   p-5 group"
                        >

                            <div class="flex items-start
                                        justify-between gap-4">

                                <div class="h-12 w-12
                                            rounded-2xl
                                            bg-orange-500/20
                                            text-orange-500
                                            flex items-center
                                            justify-center">

                                    <i class="fas fa-user-shield text-xl"></i>

                                </div>


                                <i class="fas fa-arrow-right
                                          text-slate-400
                                          group-hover:translate-x-1
                                          transition-transform">
                                </i>

                            </div>


                            <h4 class="mt-5 text-lg font-black
                                       text-slate-900
                                       dark:text-white">

                                Create Admin

                            </h4>


                            <p class="mt-2 text-sm
                                      text-slate-500
                                      dark:text-slate-400">

                                Create and manage administrative accounts.

                            </p>

                        </a>


                    @endif


                </div>

            </div>

        </div>



        {{-- ========================================================= --}}
        {{-- MAIN DASHBOARD CONTENT --}}
        {{-- ========================================================= --}}

        <div class="grid grid-cols-1
                    xl:grid-cols-2
                    gap-8">


            {{-- ===================================================== --}}
            {{-- RECENT USERS --}}
            {{-- ===================================================== --}}

            <div class="uc-card p-7">


                <div class="relative z-10">


                    <div class="flex flex-col
                                sm:flex-row
                                sm:items-center
                                sm:justify-between
                                gap-4
                                mb-6">


                        <div>

                            <p class="text-sm uppercase
                                      tracking-[0.25em]
                                      text-purple-500
                                      font-black">

                                User Control

                            </p>


                            <h3 class="mt-1 text-2xl
                                       font-black
                                       text-slate-900
                                       dark:text-white">

                                Recent Platform Members

                            </h3>

                        </div>


                        <a
                            href="{{ $usersRoute }}"
                            class="px-4 py-2 rounded-xl
                                   bg-purple-500/15
                                   text-purple-600
                                   dark:text-purple-300
                                   font-bold
                                   hover:bg-purple-500/25
                                   transition"
                        >

                            Manage Users

                        </a>

                    </div>



                    <div class="overflow-x-auto">


                        <table class="w-full">


                            <thead>

                                <tr class="text-left
                                           text-xs uppercase
                                           tracking-widest
                                           text-slate-500
                                           border-b
                                           border-white/10">

                                    <th class="py-4 pr-4">
                                        User
                                    </th>

                                    <th class="py-4 pr-4">
                                        Role
                                    </th>

                                    <th class="py-4">
                                        Status
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="divide-y divide-white/10">


                                @forelse($recentUsers as $user)

                                    <tr class="hover:bg-white/5 transition">


                                        <td class="py-5 pr-4">


                                            <div class="flex items-center gap-3">


                                                <div class="h-11 w-11
                                                            shrink-0
                                                            rounded-2xl
                                                            bg-gradient-to-br
                                                            from-indigo-500
                                                            to-fuchsia-500
                                                            flex items-center
                                                            justify-center
                                                            text-white
                                                            font-black">

                                                    {{ strtoupper(substr($user->name, 0, 1)) }}

                                                </div>


                                                <div class="min-w-0">

                                                    <p class="font-black
                                                              text-slate-900
                                                              dark:text-white
                                                              truncate">

                                                        {{ $user->name }}

                                                    </p>


                                                    <p class="text-sm
                                                              text-slate-500
                                                              truncate">

                                                        {{ $user->email }}

                                                    </p>

                                                </div>


                                            </div>


                                        </td>



                                        <td class="py-5 pr-4">

                                            <span class="px-3 py-1
                                                         rounded-full
                                                         text-xs font-black

                                                @if($user->role === 'student')
                                                    bg-cyan-500/15 text-cyan-600 dark:text-cyan-300

                                                @elseif($user->role === 'alumni')
                                                    bg-amber-500/15 text-amber-600 dark:text-amber-300

                                                @elseif($user->role === 'super_admin')
                                                    bg-fuchsia-500/15 text-fuchsia-600 dark:text-fuchsia-300

                                                @else
                                                    bg-purple-500/15 text-purple-600 dark:text-purple-300
                                                @endif
                                            ">

                                                {{ ucwords(str_replace('_', ' ', $user->role)) }}

                                            </span>

                                        </td>



                                        <td class="py-5">

                                            @if($user->is_blocked)

                                                <span class="px-3 py-1
                                                             rounded-full
                                                             bg-red-500/15
                                                             text-red-600
                                                             dark:text-red-300
                                                             text-xs
                                                             font-black">

                                                    Blocked

                                                </span>


                                            @elseif($user->is_active)

                                                <span class="px-3 py-1
                                                             rounded-full
                                                             bg-emerald-500/15
                                                             text-emerald-600
                                                             dark:text-emerald-300
                                                             text-xs
                                                             font-black">

                                                    Active

                                                </span>


                                            @else

                                                <span class="px-3 py-1
                                                             rounded-full
                                                             bg-amber-500/15
                                                             text-amber-600
                                                             dark:text-amber-300
                                                             text-xs
                                                             font-black">

                                                    Inactive

                                                </span>

                                            @endif

                                        </td>


                                    </tr>

                                @empty


                                    <tr>

                                        <td
                                            colspan="3"
                                            class="py-10
                                                   text-center
                                                   text-slate-500
                                                   font-bold"
                                        >

                                            No users found.

                                        </td>

                                    </tr>


                                @endforelse


                            </tbody>


                        </table>


                    </div>


                </div>

            </div>



            {{-- ===================================================== --}}
            {{-- RECENT JOBS --}}
            {{-- ===================================================== --}}

            <div class="uc-card p-7">


                <div class="relative z-10">


                    <div class="flex flex-col
                                sm:flex-row
                                sm:items-center
                                sm:justify-between
                                gap-4
                                mb-6">


                        <div>

                            <p class="text-sm uppercase
                                      tracking-[0.25em]
                                      text-pink-500
                                      font-black">

                                Career Flow

                            </p>


                            <h3 class="mt-1 text-2xl
                                       font-black
                                       text-slate-900
                                       dark:text-white">

                                Recent Job Posts

                            </h3>

                        </div>


                        <a
                            href="{{ route('jobs.index') }}"
                            class="px-4 py-2
                                   rounded-xl
                                   bg-pink-500/15
                                   text-pink-600
                                   dark:text-pink-300
                                   font-bold
                                   hover:bg-pink-500/25
                                   transition"
                        >

                            View Jobs

                        </a>

                    </div>



                    <div class="space-y-4">


                        @forelse($recentJobs as $job)


                            <div class="rounded-3xl
                                        border border-white/10
                                        bg-white/5
                                        p-5
                                        flex flex-col
                                        md:flex-row
                                        md:items-center
                                        md:justify-between
                                        gap-4">


                                <div class="min-w-0">


                                    <h4 class="font-black
                                               text-slate-900
                                               dark:text-white">

                                        {{ $job->title ?? 'Untitled Job' }}

                                    </h4>


                                    <p class="text-sm
                                              text-slate-500
                                              mt-1">

                                        {{ $job->company_name
                                            ?? $job->company
                                            ?? 'Unknown Company'
                                        }}


                                        @if(!empty($job->location))

                                            <span class="mx-1">
                                                •
                                            </span>

                                            {{ $job->location }}

                                        @endif

                                    </p>


                                    @if(isset($job->created_at))

                                        <p class="mt-2
                                                  text-xs
                                                  text-slate-400
                                                  font-bold">

                                            {{ \Carbon\Carbon::parse($job->created_at)->diffForHumans() }}

                                        </p>

                                    @endif


                                </div>



                                <div class="shrink-0">


                                    <span class="px-3 py-1.5
                                                 rounded-full
                                                 text-xs
                                                 font-black

                                        @if(($job->status ?? '') === 'approved')
                                            bg-emerald-500/15
                                            text-emerald-600
                                            dark:text-emerald-300

                                        @elseif(($job->status ?? '') === 'pending')
                                            bg-amber-500/15
                                            text-amber-600
                                            dark:text-amber-300

                                        @elseif(($job->status ?? '') === 'rejected')
                                            bg-red-500/15
                                            text-red-600
                                            dark:text-red-300

                                        @else
                                            bg-slate-500/15
                                            text-slate-600
                                            dark:text-slate-300
                                        @endif
                                    ">

                                        {{ strtoupper($job->status ?? 'UNKNOWN') }}

                                    </span>


                                </div>


                            </div>


                        @empty


                            <div class="rounded-3xl
                                        border border-white/10
                                        bg-white/5
                                        p-10
                                        text-center">


                                <div class="mx-auto
                                            h-14 w-14
                                            rounded-2xl
                                            bg-pink-500/15
                                            text-pink-500
                                            flex items-center
                                            justify-center">

                                    <i class="fas fa-briefcase text-xl"></i>

                                </div>


                                <p class="mt-4
                                          text-slate-500
                                          font-bold">

                                    No job posts found.

                                </p>


                            </div>


                        @endforelse


                    </div>


                </div>

            </div>


        </div>


    </div>


</x-app-layout>