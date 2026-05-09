<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-emerald-950 to-purple-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(16,185,129,.32),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(168,85,247,.28),transparent_35%)]"></div>

            <div class="relative z-10">
                <p class="text-sm uppercase tracking-[0.35em] text-emerald-300 font-black">
                    Verification Control
                </p>

                <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">
                    User Verification
                </h2>

                <p class="mt-3 text-slate-300 max-w-2xl">
                    Review pending student and alumni accounts. Approve real users or reject suspicious accounts.
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

        {{-- Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-6 shadow-2xl hover:-translate-y-2 transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500 font-bold">Pending Users</p>
                        <p class="mt-2 text-4xl font-black text-slate-900 dark:text-white">
                            {{ $stats['pending'] }}
                        </p>
                    </div>
                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center text-white shadow-xl">
                        <i class="fas fa-user-clock"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-6 shadow-2xl hover:-translate-y-2 transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500 font-bold">Pending Students</p>
                        <p class="mt-2 text-4xl font-black text-slate-900 dark:text-white">
                            {{ $stats['students'] }}
                        </p>
                    </div>
                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center text-white shadow-xl">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-6 shadow-2xl hover:-translate-y-2 transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500 font-bold">Pending Alumni</p>
                        <p class="mt-2 text-4xl font-black text-slate-900 dark:text-white">
                            {{ $stats['alumni'] }}
                        </p>
                    </div>
                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-purple-500 to-fuchsia-600 flex items-center justify-center text-white shadow-xl">
                        <i class="fas fa-award"></i>
                    </div>
                </div>
            </div>

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-6 shadow-2xl hover:-translate-y-2 transition duration-300">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-500 font-bold">Blocked Users</p>
                        <p class="mt-2 text-4xl font-black text-red-500">
                            {{ $stats['blocked'] }}
                        </p>
                    </div>
                    <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-red-500 to-pink-600 flex items-center justify-center text-white shadow-xl">
                        <i class="fas fa-ban"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-6 shadow-2xl">
            <form method="GET" action="{{ route('admin.verification.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Search name, email, phone, department..."
                       class="rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">

                <select name="role"
                        class="rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                    <option value="">All Pending Roles</option>
                    <option value="student" @selected($role === 'student')>Student</option>
                    <option value="alumni" @selected($role === 'alumni')>Alumni</option>
                </select>

                <button type="submit"
                        class="rounded-2xl bg-gradient-to-r from-emerald-500 to-cyan-600 text-white font-black shadow-xl hover:scale-105 transition">
                    <i class="fas fa-search mr-2"></i>
                    Search Pending
                </button>
            </form>
        </div>

        {{-- Pending Users --}}
        <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 shadow-2xl overflow-hidden">
            <div class="p-6 border-b border-slate-200 dark:border-white/10 flex items-center justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.25em] text-emerald-500 font-black">
                        Pending Verification
                    </p>
                    <h3 class="mt-1 text-2xl font-black text-slate-900 dark:text-white">
                        Waiting Accounts
                    </h3>
                </div>

                <a href="{{ route('admin.dashboard') }}"
                   class="hidden md:inline-flex px-5 py-3 rounded-2xl bg-slate-950 text-white font-black hover:scale-105 transition">
                    Dashboard
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-100 dark:bg-slate-950">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs uppercase tracking-widest text-slate-500">User</th>
                            <th class="px-6 py-4 text-left text-xs uppercase tracking-widest text-slate-500">Role</th>
                            <th class="px-6 py-4 text-left text-xs uppercase tracking-widest text-slate-500">Academic Info</th>
                            <th class="px-6 py-4 text-left text-xs uppercase tracking-widest text-slate-500">Status</th>
                            <th class="px-6 py-4 text-right text-xs uppercase tracking-widest text-slate-500">Action</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200 dark:divide-white/10">
                        @forelse($users as $userItem)
                            <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition">
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        @if($userItem->profile_image)
                                            <img src="{{ asset('storage/' . $userItem->profile_image) }}"
                                                 class="h-12 w-12 rounded-2xl object-cover">
                                        @else
                                            <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-emerald-500 to-cyan-600 flex items-center justify-center text-white font-black">
                                                {{ strtoupper(substr($userItem->name, 0, 1)) }}
                                            </div>
                                        @endif

                                        <div>
                                            <p class="font-black text-slate-900 dark:text-white">
                                                {{ $userItem->name }}
                                            </p>
                                            <p class="text-sm text-slate-500">
                                                {{ $userItem->email }}
                                            </p>
                                            <p class="text-xs text-slate-400 mt-1">
                                                {{ $userItem->phone ?? 'No phone number' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    <span class="px-4 py-2 rounded-full text-xs font-black bg-purple-500/10 text-purple-600">
                                        {{ ucwords(str_replace('_', ' ', $userItem->role)) }}
                                    </span>
                                </td>

                                <td class="px-6 py-5 text-sm text-slate-500">
                                    <p>
                                        <span class="font-bold">Department:</span>
                                        {{ $userItem->department ?? 'N/A' }}
                                    </p>
                                    <p>
                                        <span class="font-bold">Batch:</span>
                                        {{ $userItem->batch ?? 'N/A' }}
                                    </p>
                                    <p>
                                        <span class="font-bold">Address:</span>
                                        {{ $userItem->address ?? 'N/A' }}
                                    </p>
                                </td>

                                <td class="px-6 py-5">
                                    <span class="px-4 py-2 rounded-full text-xs font-black bg-amber-500/10 text-amber-600">
                                        Pending
                                    </span>
                                </td>

                                <td class="px-6 py-5">
                                    <div class="flex items-center justify-end gap-2">
                                        <form method="POST" action="{{ route('admin.verification.approve', $userItem) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    onclick="return confirm('Approve this user?')"
                                                    class="px-4 py-2 rounded-xl bg-emerald-500/10 text-emerald-600 font-black hover:bg-emerald-500 hover:text-white transition">
                                                Approve
                                            </button>
                                        </form>

                                        <form method="POST" action="{{ route('admin.verification.reject', $userItem) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    onclick="return confirm('Reject and block this user?')"
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
                                        No Pending Users
                                    </h3>

                                    <p class="mt-2 text-slate-500">
                                        All student and alumni accounts are already reviewed.
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>