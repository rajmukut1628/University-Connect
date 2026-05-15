<x-app-layout>
    <x-slot name="header">
        @php
            $user = auth()->user();
            $isAdminPanel = $user && in_array($user->role, ['admin', 'super_admin']);
            $isUserPanel = $user && in_array($user->role, ['student', 'alumni']);
        @endphp

        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-emerald-950 to-cyan-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(16,185,129,.45),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(34,211,238,.35),transparent_35%)]"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-emerald-300 font-black">
                        {{ $isAdminPanel ? 'Event Management Center' : 'AI Event Hub' }}
                    </p>

                    <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">
                        {{ $isAdminPanel ? 'Event Requests & Management' : 'University Events' }}
                    </h2>

                    <p class="mt-3 text-slate-300 max-w-2xl">
                        @if($isAdminPanel)
                            Create real university events, monitor registration requests, and approve student or alumni participation.
                        @else
                            Explore real university events and submit registration requests for admin approval.
                        @endif
                    </p>

                    {{-- Admin Only Add Event --}}
                    @if($isAdminPanel)
                        <div class="mt-6">
                            <a href="{{ route('events.create') }}"
                               class="inline-flex items-center gap-2 rounded-xl bg-white/10 border border-white/15 px-4 py-2.5 text-sm font-black text-white hover:bg-white/20 hover:scale-105 transition-all duration-300">
                                <i class="fas fa-calendar-plus text-emerald-300"></i>
                                <span>Add Event</span>
                            </a>
                        </div>
                    @endif
                </div>

                @if($isAdminPanel)
                    <div class="rounded-3xl bg-white/10 backdrop-blur-xl border border-white/10 px-6 py-5">
                        <p class="text-xs text-slate-300">Approval Workflow</p>
                        <p class="text-2xl font-black text-amber-300">
                            {{ $stats['pending_requests'] ?? 0 }} Pending
                        </p>
                        <p class="text-xs text-slate-400 mt-1">
                            Event registration requests
                        </p>
                    </div>
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
            background: linear-gradient(135deg, rgba(255,255,255,.16), rgba(255,255,255,.06));
            backdrop-filter: blur(22px);
            box-shadow: 0 24px 70px rgba(15,23,42,.18);
            transition: .35s ease;
        }

        .uc-card:hover {
            transform: translateY(-8px) scale(1.01);
        }

        .event-action-card {
            border-radius: 1.5rem;
            border: 1px solid rgba(255,255,255,.16);
            background: linear-gradient(135deg, rgba(15,23,42,.92), rgba(30,41,59,.70));
            box-shadow: 0 24px 70px rgba(15,23,42,.25);
        }
    </style>

    <div class="space-y-8">

        {{-- Admin / Super Admin Event Action Center --}}
        @if($isAdminPanel)
            <div class="event-action-card p-6">
                <div class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-6">
                    <div>
                        <p class="text-sm uppercase tracking-[0.25em] text-amber-300 font-black">
                            Admin Event Control
                        </p>

                        <h3 class="mt-2 text-2xl font-black text-white">
                            Event Request Approval System
                        </h3>

                        <p class="mt-2 text-sm text-slate-400 max-w-2xl">
                            Students and alumni can request event registration. Admin or Super Admin must approve the request before it becomes an official participant.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('event.participants.pending') }}"
                           class="inline-flex items-center justify-center gap-2 px-6 py-4 rounded-2xl bg-gradient-to-r from-amber-500 to-orange-600 text-white font-black shadow-xl hover:scale-105 transition">
                            <i class="fas fa-clipboard-check"></i>
                            Event Requests
                        </a>

                        <a href="{{ route('events.create') }}"
                           class="inline-flex items-center justify-center gap-2 px-6 py-4 rounded-2xl bg-gradient-to-r from-emerald-500 to-cyan-600 text-white font-black shadow-xl hover:scale-105 transition">
                            <i class="fas fa-calendar-plus"></i>
                            Add Event
                        </a>
                    </div>
                </div>
            </div>
        @endif

        {{-- Success Message --}}
        @if(session('success'))
            <div class="uc-card p-5 text-emerald-500 font-black">
                <i class="fas fa-circle-check mr-2"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- Error Messages --}}
        @if($errors->any())
            <div class="uc-card p-5 text-red-500 font-black">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Statistics --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="uc-card p-6">
                <p class="text-sm font-bold text-slate-500">
                    {{ $isAdminPanel ? 'Total Published Events' : 'Total Events' }}
                </p>
                <h3 class="mt-3 text-4xl font-black text-emerald-500">
                    {{ $stats['total_events'] ?? 0 }}
                </h3>
            </div>

            <div class="uc-card p-6">
                <p class="text-sm font-bold text-slate-500">
                    Upcoming Events
                </p>
                <h3 class="mt-3 text-4xl font-black text-cyan-500">
                    {{ $stats['upcoming_events'] ?? 0 }}
                </h3>
            </div>

            @if($isAdminPanel)
                <div class="uc-card p-6">
                    <p class="text-sm font-bold text-slate-500">
                        Pending Requests
                    </p>
                    <h3 class="mt-3 text-4xl font-black text-amber-500">
                        {{ $stats['pending_requests'] ?? 0 }}
                    </h3>
                </div>

                <div class="uc-card p-6">
                    <p class="text-sm font-bold text-slate-500">
                        Approved Participants
                    </p>
                    <h3 class="mt-3 text-4xl font-black text-pink-500">
                        {{ $stats['approved_participants'] ?? 0 }}
                    </h3>
                </div>
            @else
                <div class="uc-card p-6">
                    <p class="text-sm font-bold text-slate-500">
                        My Requests
                    </p>
                    <h3 class="mt-3 text-4xl font-black text-purple-500">
                        {{ $stats['my_registrations'] ?? 0 }}
                    </h3>
                </div>

                <div class="uc-card p-6">
                    <p class="text-sm font-bold text-slate-500">
                        Approved Participants
                    </p>
                    <h3 class="mt-3 text-4xl font-black text-pink-500">
                        {{ $stats['approved_participants'] ?? 0 }}
                    </h3>
                </div>
            @endif
        </div>

        {{-- Event Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($events as $event)
                @php
                    $isRegistered = isset($registeredEvents[$event->id]);
                    $participants = $event->participants->count();
                    $isFull = $event->capacity && $participants >= $event->capacity;
                    $isOpen = in_array($event->status, ['active', 'published']);
                @endphp

                <div class="uc-card p-6">
                    <div class="relative z-10">

                        <div class="h-20 w-20 rounded-3xl bg-gradient-to-br from-emerald-500 to-cyan-500 flex items-center justify-center shadow-xl">
                            <i class="fas fa-calendar-days text-3xl text-white"></i>
                        </div>

                        <div class="mt-6 flex items-center justify-between">
                            <span class="px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-600 text-xs font-black">
                                {{ ucfirst($event->type ?? 'Event') }}
                            </span>

                            <span class="px-3 py-1 rounded-full {{ $isOpen ? 'bg-cyan-500/15 text-cyan-600' : 'bg-red-500/15 text-red-600' }} text-xs font-black">
                                {{ strtoupper($event->status ?? 'PUBLISHED') }}
                            </span>
                        </div>

                        <h3 class="mt-5 text-2xl font-black text-slate-900 dark:text-white">
                            {{ $event->title }}
                        </h3>

                        <p class="mt-3 text-sm text-slate-500 leading-relaxed">
                            {{ \Illuminate\Support\Str::limit($event->description, 120) }}
                        </p>

                        <div class="mt-5 space-y-3 text-sm text-slate-500">
                            <p>
                                <i class="fas fa-calendar mr-2 text-emerald-500"></i>
                                {{ $event->event_date ? $event->event_date->format('d M Y, h:i A') : 'Date not set' }}
                            </p>

                            <p>
                                <i class="fas fa-location-dot mr-2 text-pink-500"></i>
                                {{ $event->location ?? 'Location not specified' }}
                            </p>

                            <p>
                                <i class="fas fa-users mr-2 text-cyan-500"></i>
                                {{ $participants }} / {{ $event->capacity ?? 'Unlimited' }} approved
                            </p>

                            <p>
                                <i class="fas fa-user-shield mr-2 text-purple-500"></i>
                                {{ $event->creator?->name ?? 'University Admin' }}
                            </p>
                        </div>

                        <div class="mt-6">
                            @if($isAdminPanel)
                                <div class="w-full rounded-2xl bg-slate-500/15 text-slate-500 py-3 text-center font-black">
                                    <i class="fas fa-shield-halved mr-2"></i>
                                    Admin Management View
                                </div>

                            @elseif($isRegistered)
                                @php
                                    $myStatus = $registeredEvents[$event->id];
                                @endphp

                                @if($myStatus === 'pending')
                                    <div class="w-full rounded-2xl bg-amber-500/15 text-amber-600 py-3 text-center font-black">
                                        <i class="fas fa-hourglass-half mr-2"></i>
                                        Pending Approval
                                    </div>

                                @elseif($myStatus === 'approved')
                                    <div class="w-full rounded-2xl bg-emerald-500/15 text-emerald-600 py-3 text-center font-black">
                                        <i class="fas fa-circle-check mr-2"></i>
                                        Approved
                                    </div>

                                @elseif($myStatus === 'rejected')
                                    <div class="w-full rounded-2xl bg-red-500/15 text-red-600 py-3 text-center font-black">
                                        <i class="fas fa-circle-xmark mr-2"></i>
                                        Rejected
                                    </div>

                                @else
                                    <div class="w-full rounded-2xl bg-slate-500/15 text-slate-600 py-3 text-center font-black">
                                        Registered
                                    </div>
                                @endif

                            @elseif(!$isOpen)
                                <div class="w-full rounded-2xl bg-red-500/15 text-red-600 py-3 text-center font-black">
                                    Registration Closed
                                </div>

                            @elseif($isFull)
                                <div class="w-full rounded-2xl bg-red-500/15 text-red-600 py-3 text-center font-black">
                                    Full
                                </div>

                            @else
                                <form method="POST" action="{{ route('events.register', $event) }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full rounded-2xl bg-gradient-to-r from-emerald-500 to-cyan-500 py-3 text-white font-black shadow-xl hover:scale-105 transition">
                                        Request Registration
                                    </button>
                                </form>
                            @endif
                        </div>

                    </div>
                </div>
            @empty
                <div class="xl:col-span-3 uc-card p-10 text-center">
                    @if($isAdminPanel)
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                            No Events Created Yet
                        </h3>
                        <p class="mt-2 text-slate-500">
                            Add a real university event. After students or alumni register, requests will appear in Event Requests.
                        </p>
                    @else
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                            No Events Available
                        </h3>
                        <p class="mt-2 text-slate-500">
                            No published university events are available right now.
                        </p>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>