<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-950 to-cyan-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(34,211,238,.30),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(168,85,247,.25),transparent_35%)]"></div>

            <div class="relative z-10">
                <p class="text-sm uppercase tracking-[0.35em] text-cyan-300 font-black">
                    Admin Control Center
                </p>
                <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">
                    User Management
                </h2>
                <p class="mt-3 text-slate-300 max-w-2xl">
                    Search, monitor, block, unblock and manage all student, alumni and admin accounts.
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
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-5">
            @foreach([
                ['label' => 'Total Users', 'value' => $stats['total'], 'icon' => 'fa-users', 'color' => 'from-cyan-500 to-blue-600'],
                ['label' => 'Students', 'value' => $stats['students'], 'icon' => 'fa-user-graduate', 'color' => 'from-emerald-500 to-green-600'],
                ['label' => 'Alumni', 'value' => $stats['alumni'], 'icon' => 'fa-award', 'color' => 'from-amber-500 to-orange-600'],
                ['label' => 'Admins', 'value' => $stats['admins'], 'icon' => 'fa-user-shield', 'color' => 'from-purple-500 to-fuchsia-600'],
                ['label' => 'Blocked', 'value' => $stats['blocked'], 'icon' => 'fa-ban', 'color' => 'from-red-500 to-pink-600'],
                ['label' => 'Inactive', 'value' => $stats['inactive'], 'icon' => 'fa-user-clock', 'color' => 'from-slate-500 to-slate-700'],
            ] as $item)
                <div class="group rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-5 shadow-2xl hover:-translate-y-2 transition duration-300">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-slate-500 font-bold">{{ $item['label'] }}</p>
                            <p class="mt-2 text-4xl font-black text-slate-900 dark:text-white">
                                {{ $item['value'] }}
                            </p>
                        </div>

                        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br {{ $item['color'] }} flex items-center justify-center text-white shadow-xl group-hover:scale-110 transition">
                            <i class="fas {{ $item['icon'] }}"></i>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Search Filter --}}
        <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-6 shadow-2xl">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text"
                       name="search"
                       value="{{ $search }}"
                       placeholder="Search name, email, phone, department..."
                       class="rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">

                <select name="role"
                        class="rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                    <option value="">All Roles</option>
                    <option value="student" @selected($role === 'student')>Student</option>
                    <option value="alumni" @selected($role === 'alumni')>Alumni</option>
                    <option value="admin" @selected($role === 'admin')>Admin</option>
                    <option value="super_admin" @selected($role === 'super_admin')>Super Admin</option>
                </select>

                <select name="status"
                        class="rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                    <option value="">All Status</option>
                    <option value="active" @selected($status === 'active')>Active</option>
                    <option value="blocked" @selected($status === 'blocked')>Blocked</option>
                    <option value="inactive" @selected($status === 'inactive')>Inactive</option>
                </select>

                <button type="submit"
                        class="rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black shadow-xl hover:scale-105 transition">
                    <i class="fas fa-search mr-2"></i>
                    Filter
                </button>
            </form>
        </div>

        {{-- Users Table --}}
        <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 shadow-2xl overflow-hidden">
            <div class="p-6 border-b border-slate-200 dark:border-white/10 flex items-center justify-between">
                <div>
                    <p class="text-sm uppercase tracking-[0.25em] text-indigo-500 font-black">
                        User Database
                    </p>
                    <h3 class="mt-1 text-2xl font-black text-slate-900 dark:text-white">
                        All Accounts
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
                            <th class="px-6 py-4 text-left text-xs uppercase tracking-widest text-slate-500">Status</th>
                            <th class="px-6 py-4 text-left text-xs uppercase tracking-widest text-slate-500">Info</th>
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
                                            <div class="h-12 w-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-black">
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
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-5">
                                    <span class="px-4 py-2 rounded-full text-xs font-black bg-indigo-500/10 text-indigo-600">
                                        {{ ucwords(str_replace('_', ' ', $userItem->role)) }}
                                    </span>
                                </td>

                                <td class="px-6 py-5">
                                    @if($userItem->is_blocked)
                                        <span class="px-4 py-2 rounded-full text-xs font-black bg-red-500/10 text-red-600">
                                            Blocked
                                        </span>
                                    @elseif(!$userItem->is_active)
                                        <span class="px-4 py-2 rounded-full text-xs font-black bg-amber-500/10 text-amber-600">
                                            Inactive
                                        </span>
                                    @else
                                        <span class="px-4 py-2 rounded-full text-xs font-black bg-emerald-500/10 text-emerald-600">
                                            Active
                                        </span>
                                    @endif
                                </td>

                                <td class="px-6 py-5 text-sm text-slate-500">
                                    <p>{{ $userItem->phone ?? 'No phone' }}</p>
                                    <p>{{ $userItem->department ?? $userItem->address ?? 'No extra info' }}</p>
                                </td>

                                <td class="px-6 py-5">
                                    <div class="flex items-center justify-end gap-2">
                                        @if(!$userItem->is_blocked)
                                            <form method="POST" action="{{ route('admin.users.block', $userItem) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        onclick="return confirm('Block this user?')"
                                                        class="px-4 py-2 rounded-xl bg-red-500/10 text-red-600 font-black hover:bg-red-500 hover:text-white transition">
                                                    Block
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('admin.users.unblock', $userItem) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="px-4 py-2 rounded-xl bg-emerald-500/10 text-emerald-600 font-black hover:bg-emerald-500 hover:text-white transition">
                                                    Unblock
                                                </button>
                                            </form>
                                        @endif

                                        <form method="POST" action="{{ route('admin.users.destroy', $userItem) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Delete this user permanently?')"
                                                    class="px-4 py-2 rounded-xl bg-slate-500/10 text-slate-600 dark:text-slate-300 font-black hover:bg-slate-950 hover:text-white transition">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-500 font-bold">
                                    No users found.
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