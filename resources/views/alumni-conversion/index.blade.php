<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-purple-950 to-indigo-950 p-8 shadow-2xl border border-white/10">
            <div class="relative z-10">
                <p class="text-sm uppercase tracking-[0.35em] text-cyan-300 font-black">
                    Alumni Conversion Admin Panel
                </p>
                <h2 class="mt-3 text-4xl font-black text-white">
                    Conversion Requests
                </h2>
                <p class="mt-3 text-slate-300">
                    Review student requests and convert eligible students to alumni accounts.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-2xl bg-emerald-500/15 border border-emerald-500/30 p-4 text-emerald-600 font-bold">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-2xl bg-red-500/15 border border-red-500/30 p-4 text-red-600 font-bold">
                {{ session('error') }}
            </div>
        @endif

        <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 shadow-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-100 dark:bg-slate-950">
                        <tr>
                            <th class="px-5 py-4 text-left text-xs uppercase tracking-widest text-slate-500">Student</th>
                            <th class="px-5 py-4 text-left text-xs uppercase tracking-widest text-slate-500">Graduation</th>
                            <th class="px-5 py-4 text-left text-xs uppercase tracking-widest text-slate-500">Career</th>
                            <th class="px-5 py-4 text-left text-xs uppercase tracking-widest text-slate-500">Status</th>
                            <th class="px-5 py-4 text-right text-xs uppercase tracking-widest text-slate-500">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                        @forelse($requests as $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition">
                                <td class="px-5 py-5">
                                    <p class="font-black text-slate-900 dark:text-white">
                                        {{ $item->user->name ?? 'Unknown User' }}
                                    </p>
                                    <p class="text-sm text-slate-500">
                                        {{ $item->user->email ?? 'No email' }}
                                    </p>
                                    <p class="text-xs text-cyan-600 font-bold mt-1">
                                        Student ID: {{ $item->student_id ?? 'N/A' }}
                                    </p>
                                </td>

                                <td class="px-5 py-5">
                                    <p class="font-black text-slate-900 dark:text-white">
                                        {{ $item->graduation_year }}
                                    </p>
                                    <p class="text-xs text-slate-500">
                                        Requested {{ $item->created_at->diffForHumans() }}
                                    </p>
                                </td>

                                <td class="px-5 py-5 text-sm text-slate-500">
                                    <p>{{ $item->current_company ?: 'No company' }}</p>
                                    <p>{{ $item->designation ?: 'No designation' }}</p>

                                    @if($item->supporting_document)
                                        <a href="{{ asset('storage/' . $item->supporting_document) }}"
                                           target="_blank"
                                           class="inline-block mt-2 text-cyan-600 font-black hover:underline">
                                            View Document
                                        </a>
                                    @endif
                                </td>

                                <td class="px-5 py-5">
                                    @if($item->status === 'pending')
                                        <span class="px-4 py-2 rounded-full text-xs font-black bg-amber-500/10 text-amber-600">
                                            Pending
                                        </span>
                                    @elseif($item->status === 'approved')
                                        <span class="px-4 py-2 rounded-full text-xs font-black bg-emerald-500/10 text-emerald-600">
                                            Approved
                                        </span>
                                    @else
                                        <span class="px-4 py-2 rounded-full text-xs font-black bg-red-500/10 text-red-600">
                                            Rejected
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-5">
                                    @if($item->status === 'pending')
                                        <div class="flex flex-col gap-3 items-end">
                                            <form method="POST"
                                                  action="{{ route('alumni-conversion.approve', $item) }}"
                                                  class="w-full max-w-xs">
                                                @csrf
                                                @method('PATCH')

                                                <textarea name="admin_notes"
                                                          rows="2"
                                                          placeholder="Admin note optional..."
                                                          class="w-full rounded-xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white text-sm"></textarea>

                                                <button type="submit"
                                                        onclick="return confirm('Convert this student to alumni?')"
                                                        class="mt-2 w-full px-4 py-2 rounded-xl bg-emerald-500 text-white font-black hover:bg-emerald-600 transition">
                                                    Approve & Convert
                                                </button>
                                            </form>

                                            <form method="POST"
                                                  action="{{ route('alumni-conversion.reject', $item) }}"
                                                  class="w-full max-w-xs">
                                                @csrf
                                                @method('PATCH')

                                                <textarea name="admin_notes"
                                                          rows="2"
                                                          required
                                                          placeholder="Rejection reason required..."
                                                          class="w-full rounded-xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white text-sm"></textarea>

                                                <button type="submit"
                                                        onclick="return confirm('Reject this request?')"
                                                        class="mt-2 w-full px-4 py-2 rounded-xl bg-red-500 text-white font-black hover:bg-red-600 transition">
                                                    Reject
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="text-right text-sm">
                                            <p class="font-black text-slate-700 dark:text-slate-200">
                                                Processed
                                            </p>
                                            <p class="text-slate-500">
                                                {{ $item->approved_at?->format('d M Y h:i A') }}
                                            </p>
                                            <p class="text-slate-500">
                                                By: {{ $item->approvedBy->name ?? 'N/A' }}
                                            </p>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500 font-bold">
                                    No conversion requests found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</x-app-layout>