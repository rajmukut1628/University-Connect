<x-app-layout>
    <x-slot name="header">

        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-950 to-purple-950 p-8 shadow-2xl border border-white/10">

            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(99,102,241,.30),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(168,85,247,.25),transparent_35%)]"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-purple-300 font-black">
                        Administration Profile
                    </p>

                    <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">
                        {{ $user->name }}
                    </h2>

                    <p class="mt-3 text-slate-300">
                        Manage your administrator profile and account information.
                    </p>
                </div>

                <div class="rounded-3xl bg-white/10 border border-white/10 px-7 py-5 text-center">

                    <p class="text-xs text-slate-300">
                        Role
                    </p>

                    <p class="mt-1 text-xl font-black text-emerald-300">
                        {{ ucwords(str_replace('_', ' ', $user->role)) }}
                    </p>

                    @if($user->isOwner())
                        <p class="mt-1 text-xs text-amber-300 font-bold">
                            Owner Super Admin
                        </p>
                    @endif

                </div>
            </div>
        </div>

    </x-slot>

    <div class="max-w-5xl mx-auto space-y-8">

        @if(session('status') === 'profile-updated')
            <div class="rounded-2xl bg-emerald-500/10 border border-emerald-500/30 p-4 text-emerald-600 dark:text-emerald-300 font-bold">
                Profile updated successfully.
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-2xl bg-red-500/10 border border-red-500/30 p-5">
                <ul class="list-disc list-inside text-red-500 text-sm space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Card --}}
            <div class="space-y-6">

                <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-7 shadow-2xl text-center">

                    @if($user->profile_image)
                        <img
                            src="{{ $user->getProfileImageUrl() }}"
                            alt="{{ $user->name }}"
                            class="mx-auto h-32 w-32 rounded-3xl object-cover border-4 border-white shadow-xl"
                        >
                    @else
                        <div class="mx-auto h-32 w-32 rounded-3xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-5xl font-black">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif

                    <h3 class="mt-5 text-2xl font-black text-slate-900 dark:text-white">
                        {{ $user->name }}
                    </h3>

                    <p class="mt-1 font-bold text-purple-500">
                        {{ ucwords(str_replace('_', ' ', $user->role)) }}
                    </p>

                    <p class="mt-3 text-sm text-slate-500 break-all">
                        {{ $user->email }}
                    </p>
                </div>

                @if($user->isSuperAdmin())
                    <div class="rounded-3xl bg-gradient-to-br from-emerald-500/10 to-cyan-500/10 border border-emerald-500/20 p-6">

                        <p class="text-xs uppercase tracking-[0.25em] text-emerald-500 font-black">
                            Super Admin
                        </p>

                        <h3 class="mt-2 text-xl font-black text-slate-900 dark:text-white">
                            Administration
                        </h3>

                        <p class="mt-3 text-sm text-slate-500">
                            Create and manage administrator accounts from the Super Admin panel.
                        </p>

                        <a
                            href="{{ route('superadmin.admins.create') }}"
                            class="mt-5 inline-flex px-5 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-cyan-600 text-white font-black"
                        >
                            Add Another Admin
                        </a>
                    </div>
                @endif
            </div>

            {{-- Form --}}
            <div class="lg:col-span-2">

                <form
                    method="POST"
                    action="{{ route('profile.update') }}"
                    enctype="multipart/form-data"
                    class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl"
                >
                    @csrf
                    @method('PATCH')

                    <p class="text-sm uppercase tracking-[0.25em] text-indigo-500 font-black">
                        Account Settings
                    </p>

                    <h3 class="mt-2 text-3xl font-black text-slate-900 dark:text-white">
                        Administrator Details
                    </h3>

                    <div class="mt-8 space-y-6">

                        <div>
                            <label class="font-bold text-slate-700 dark:text-slate-300">
                                Full Name
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                required
                                class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                            >
                        </div>

                        <div>
                            <label class="font-bold text-slate-700 dark:text-slate-300">
                                Email
                            </label>

                            <input
                                type="email"
                                value="{{ $user->email }}"
                                disabled
                                class="mt-2 w-full rounded-2xl border-slate-300 bg-slate-100 dark:border-white/10 dark:bg-slate-800 dark:text-slate-400"
                            >
                        </div>

                        <div>
                            <label class="font-bold text-slate-700 dark:text-slate-300">
                                Phone
                            </label>

                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone', $user->phone) }}"
                                placeholder="+8801XXXXXXXXX"
                                class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                            >
                        </div>

                        <div>
                            <label class="font-bold text-slate-700 dark:text-slate-300">
                                Address
                            </label>

                            <input
                                type="text"
                                name="address"
                                value="{{ old('address', $user->address) }}"
                                placeholder="Dhaka, Bangladesh"
                                class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                            >
                        </div>

                        <div>
                            <label class="font-bold text-slate-700 dark:text-slate-300">
                                Profile Image
                            </label>

                            <input
                                type="file"
                                name="profile_image"
                                accept=".jpg,.jpeg,.png,.webp"
                                class="mt-2 w-full rounded-2xl border border-slate-300 dark:border-white/10 p-3 dark:bg-slate-950 dark:text-white"
                            >

                            <p class="mt-2 text-xs text-slate-500">
                                JPG, JPEG, PNG or WEBP. Maximum 2MB.
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-100 dark:bg-slate-950 p-5">

                            <p class="text-sm text-slate-500">
                                Account Role
                            </p>

                            <p class="mt-1 text-lg font-black text-slate-900 dark:text-white">
                                {{ ucwords(str_replace('_', ' ', $user->role)) }}
                            </p>

                            <p class="mt-2 text-xs text-slate-500">
                                Role and account permissions cannot be changed from the profile page.
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 pt-2">

                            <button
                                type="submit"
                                class="px-8 py-4 rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black shadow-xl"
                            >
                                Save Profile
                            </button>

                            <a
                                href="{{ route('dashboard') }}"
                                class="px-8 py-4 rounded-2xl bg-slate-950 text-white font-black text-center"
                            >
                                Back to Dashboard
                            </a>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>