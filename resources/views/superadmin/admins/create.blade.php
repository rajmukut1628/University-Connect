<x-app-layout>
    <x-slot name="header">
        <div class="rounded-3xl bg-gradient-to-r from-slate-950 via-emerald-950 to-cyan-950 p-8 shadow-2xl border border-white/10">
            <p class="text-sm uppercase tracking-[0.35em] text-emerald-300 font-bold">
                Super Admin Control
            </p>

            <h2 class="mt-3 text-4xl font-black text-white">
                Add Another Admin
            </h2>

            <p class="mt-3 text-slate-300 max-w-2xl">
                Create a new administrator account for managing users, verification, posts, donations and platform activities.
            </p>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl">

            <form method="POST" action="{{ route('superadmin.admins.store') }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="font-bold text-slate-700 dark:text-slate-300">Full Name</label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               required
                               class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="font-bold text-slate-700 dark:text-slate-300">Email</label>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="font-bold text-slate-700 dark:text-slate-300">Phone</label>
                        <input type="text"
                               name="phone"
                               value="{{ old('phone') }}"
                               placeholder="+8801XXXXXXXXX"
                               class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                        @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="font-bold text-slate-700 dark:text-slate-300">Address</label>
                        <input type="text"
                               name="address"
                               value="{{ old('address') }}"
                               placeholder="Dhaka, Bangladesh"
                               class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                        @error('address')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="font-bold text-slate-700 dark:text-slate-300">Password</label>
                        <input type="password"
                               name="password"
                               required
                               class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="font-bold text-slate-700 dark:text-slate-300">Confirm Password</label>
                        <input type="password"
                               name="password_confirmation"
                               required
                               class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                    </div>
                </div>

                <div class="rounded-3xl bg-emerald-500/10 border border-emerald-500/20 p-6">
                    <h3 class="text-xl font-black text-emerald-600">
                        Admin Permission
                    </h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                        This account will be created with <b>Admin</b> role. Only Super Admin can create another admin.
                    </p>
                </div>

                <div class="flex flex-col md:flex-row gap-4 pt-4">
                    <button type="submit"
                            class="px-8 py-4 rounded-2xl bg-gradient-to-r from-emerald-500 to-cyan-600 text-white font-black shadow-xl hover:scale-105 transition">
                        <i class="fas fa-user-shield mr-2"></i>
                        Create Admin
                    </button>

                    <a href="{{ route('admin.dashboard') }}"
                       class="px-8 py-4 rounded-2xl bg-slate-950 text-white font-black shadow-xl hover:scale-105 transition text-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Dashboard
                    </a>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>