<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-emerald-950 to-cyan-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(16,185,129,.35),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(34,211,238,.30),transparent_35%)]"></div>

            <div class="relative z-10">
                <p class="text-sm uppercase tracking-[0.35em] text-emerald-300 font-black">
                    Event Approval Center
                </p>
                <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">
                    Pending Event Requests
                </h2>
                <p class="mt-3 text-slate-300 max-w-2xl">
                    Review student and alumni event registration requests before approval.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">

        @if(session('success'))
            <div class="rounded-2xl bg-emerald-500/15 border border-emerald-500/30 p-4 text-emerald-600 font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-2xl bg-red-500/15 border border-red-500/30 p-4 text-red-600 font-bold">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 shadow-2xl overflow-hidden">
            <div class="p-6 border-b border-slate-200 dark:border-white/10 flex items-center justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.25em] text-emerald-500 font-black">
                        Approval Queue
                    </p>
                    <h3 class="mt-1 text-2xl font-black text-slate-900 dark:text-white">
                        Waiting Requests
                    </h3>
                </div>

                <a href="{{ route('events.index') }}"
                   class="px-5 py-3 rounded-2xl bg-slate-950 text-white font-black hover:scale-105 transition">
                    Events
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-100 dark:bg-slate-950">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs uppercase tracking-widest text-slate-500">Applicant</th>
                            <th class="px-6 py-4 text-left text-xs uppercase tracking-widest text-slate-500">Event</th>
                            <th class="px-6 py-4 text-left text-xs uppercase tracking-widest text-slate-500">Event Info</th>
                            <th class="px-6 py-4 text-left text-xs uppercase tracking-widest text-slate-500">Status</th>
                            <th class="px-6 py-4 text-right text-xs uppercase tracking-widest text-slate-500">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                        @forelse($participants as $participant)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        @if($participant->user?->profile_image)
                                            <img src="{{ asset('storage/' . $participant->user->profile_image) }}"
                                                 class="h-12 w-12 rounded-2xl object-cover">
                                        @else
                                            <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-cyan-600 flex items-center justify-center text-white font-black">
                                                {{ strtoupper(substr($participant->user?->name ?? 'U', 0, 1)) }}
                                            </div>
                                        @endif

                                        <div>
                                            <p class="font-black text-slate-900 dark:text-white">
                                                {{ $participant->user?->name ?? 'Unknown User' }}
                                            </p>
                                            <p class="text-sm text-slate-500">
                                                {{ $participant->user?->email ?? 'No email' }}
                                            </p>
                                            <p class="text-xs text-slate-400 mt-1">
                                                {{ ucwords(str_replace('_', ' ', $participant->user?->role ?? 'user')) }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    <p class="font-black text-slate-900 dark:text-white">
                                        {{ $participant->event?->title ?? 'Unknown Event' }}
                                    </p>
                                    <p class="text-sm text-slate-500">
                                        {{ ucfirst($participant->event?->type ?? 'Event') }}
                                    </p>
                                </td>

                                <td class="px-6 py-5 text-sm text-slate-500">
                                    <p>
                                        <i class="fas fa-calendar text-emerald-500 mr-1"></i>
                                        {{ $participant->event?->event_date ? $participant->event->event_date->format('d M Y, h:i A') : 'Date not set' }}
                                    </p>
                                    <p>
                                        <i class="fas fa-location-dot text-pink-500 mr-1"></i>
                                        {{ $participant->event?->location ?? 'Location not set' }}
                                    </p>
                                    <p>
                                        <i class="fas fa-users text-cyan-500 mr-1"></i>
                                        Capacity: {{ $participant->event?->capacity ?? 'Unlimited' }}
                                    </p>
                                </td>

                                <td class="px-6 py-5">
                                    <span class="px-4 py-2 rounded-full text-xs font-black bg-amber-500/10 text-amber-600">
                                        Pending
                                    </span>
                                </td>

                                <td class="px-6 py-5">
                                    <div class="flex items-center justify-end gap-2">
                                        <form method="POST" action="{{ route('event.participants.approve', $participant) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    onclick="return confirm('Approve this event registration?')"
                                                    class="px-4 py-2 rounded-xl bg-emerald-500/10 text-emerald-600 font-black hover:bg-emerald-500 hover:text-white transition">
                                                Approve
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('event.participants.reject', $participant) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    onclick="return confirm('Reject this event registration?')"
                                                    class="px-4 py-2 rounded-xl bg-red-500/10 text-red-600 font-black hover:bg-red-500 hover:text-white transition">
                                                Reject
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-14 text-center">
                                    <div class="mx-auto h-20 w-20 rounded-3xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 text-3xl">
                                        <i class="fas fa-circle-check"></i>
                                    </div>

                                    <h3 class="mt-4 text-2xl font-black text-slate-900 dark:text-white">
                                        No Pending Requests
                                    </h3>

                                    <p class="mt-2 text-slate-500">
                                        All event registration requests are already reviewed.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6">
                {{ $participants->links() }}
            </div>
        </div>
    </div>
</x-app-layout>