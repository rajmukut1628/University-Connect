<x-app-layout>

    @php

        $isSuperAdmin =
            auth()->user()->role === 'super_admin';


        $indexRoute = $isSuperAdmin
            ? route('superadmin.users.index')
            : route('admin.users.index');


        $updateRoute = $isSuperAdmin
            ? route(
                'superadmin.users.update',
                $user
            )
            : route(
                'admin.users.update',
                $user
            );

    @endphp


    <x-slot name="header">

        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-950 to-purple-950 p-8 shadow-2xl border border-white/10">

            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(99,102,241,.35),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(168,85,247,.30),transparent_35%)]"></div>


            <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-5">


                <div>

                    <p class="text-sm uppercase tracking-[0.30em] text-indigo-300 font-black">

                        User Management

                    </p>


                    <h2 class="mt-3 text-4xl font-black text-white">

                        Edit User

                    </h2>


                    <p class="mt-2 text-slate-300">

                        Update user account information.

                    </p>

                </div>


                <a
                    href="{{ $indexRoute }}"
                    class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white/10 border border-white/10 px-5 py-3 text-white font-black hover:bg-white/20 transition"
                >

                    <i class="fas fa-arrow-left"></i>

                    Back to Users

                </a>


            </div>

        </div>

    </x-slot>


    <div class="max-w-5xl mx-auto">


        {{-- ========================================================= --}}
        {{-- ERRORS --}}
        {{-- ========================================================= --}}

        @if($errors->any())

            <div class="mb-6 rounded-2xl bg-red-500/10 border border-red-500/20 p-5">


                <p class="font-black text-red-500 mb-3">

                    <i class="fas fa-circle-exclamation mr-2"></i>

                    Please fix the following:

                </p>


                <ul class="space-y-1 text-sm text-red-500">

                    @foreach($errors->all() as $error)

                        <li>

                            • {{ $error }}

                        </li>

                    @endforeach

                </ul>


            </div>

        @endif


        <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 shadow-2xl overflow-hidden">


            {{-- ========================================================= --}}
            {{-- USER SUMMARY --}}
            {{-- ========================================================= --}}

            <div class="p-6 border-b border-slate-200 dark:border-white/10">


                <div class="flex items-center gap-4">


                    @if(!empty($user->profile_image))

                        <img
                            src="{{ $user->getProfileImageUrl() }}"
                            alt="{{ $user->name }}"
                            class="h-16 w-16 rounded-2xl object-cover"
                        >

                    @else

                        <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-xl font-black">

                            {{ strtoupper(substr($user->name, 0, 1)) }}

                        </div>

                    @endif


                    <div>


                        <h3 class="text-xl font-black text-slate-900 dark:text-white">

                            {{ $user->name }}

                        </h3>


                        <p class="mt-1 text-sm text-slate-500">

                            {{ $user->email }}

                        </p>


                        <p class="mt-2">

                            <span class="inline-flex rounded-full bg-indigo-500/10 px-3 py-1 text-xs font-black text-indigo-500">

                                {{ ucwords(
                                    str_replace(
                                        '_',
                                        ' ',
                                        $user->role
                                    )
                                ) }}

                            </span>

                        </p>


                    </div>


                </div>


            </div>


            {{-- ========================================================= --}}
            {{-- EDIT FORM --}}
            {{-- ========================================================= --}}

            <form
                method="POST"
                action="{{ $updateRoute }}"
                class="p-6"
            >

                @csrf

                @method('PATCH')


                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                    {{-- FULL NAME --}}

                    <div>

                        <label
                            for="name"
                            class="block mb-2 text-sm font-black text-slate-700 dark:text-slate-200"
                        >

                            Full Name

                        </label>


                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                        >

                    </div>


                    {{-- EMAIL --}}

                    <div>

                        <label
                            for="email"
                            class="block mb-2 text-sm font-black text-slate-700 dark:text-slate-200"
                        >

                            Email Address

                        </label>


                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            class="w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                        >

                    </div>


                    {{-- OFFICIAL ID --}}

                    <div>

                        <label
                            for="official_id"
                            class="block mb-2 text-sm font-black text-slate-700 dark:text-slate-200"
                        >

                            Official ID

                        </label>


                        <input
                            id="official_id"
                            type="text"
                            name="official_id"
                            value="{{ old('official_id', $user->official_id) }}"
                            class="w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                        >

                    </div>


                    {{-- PHONE --}}

                    <div>

                        <label
                            for="phone"
                            class="block mb-2 text-sm font-black text-slate-700 dark:text-slate-200"
                        >

                            Phone

                        </label>


                        <input
                            id="phone"
                            type="text"
                            name="phone"
                            value="{{ old('phone', $user->phone) }}"
                            class="w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                        >

                    </div>


                    {{-- DEPARTMENT --}}

                    <div>

                        <label
                            for="department"
                            class="block mb-2 text-sm font-black text-slate-700 dark:text-slate-200"
                        >

                            Department

                        </label>


                        <input
                            id="department"
                            type="text"
                            name="department"
                            value="{{ old('department', $user->department) }}"
                            class="w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                        >

                    </div>


                    {{-- BATCH --}}

                    <div>

                        <label
                            for="batch"
                            class="block mb-2 text-sm font-black text-slate-700 dark:text-slate-200"
                        >

                            Batch

                        </label>


                        <input
                            id="batch"
                            type="text"
                            name="batch"
                            value="{{ old('batch', $user->batch) }}"
                            class="w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                        >

                    </div>


                    {{-- ROLE --}}

                    <div>

                        <label
                            for="role"
                            class="block mb-2 text-sm font-black text-slate-700 dark:text-slate-200"
                        >

                            Role

                        </label>


                        <select
                            id="role"
                            name="role"
                            required
                            class="w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                        >


                            <option
                                value="student"
                                @selected(
                                    old(
                                        'role',
                                        $user->role
                                    ) === 'student'
                                )
                            >

                                Student

                            </option>


                            <option
                                value="alumni"
                                @selected(
                                    old(
                                        'role',
                                        $user->role
                                    ) === 'alumni'
                                )
                            >

                                Alumni

                            </option>


                            @if($isSuperAdmin)

                                <option
                                    value="admin"
                                    @selected(
                                        old(
                                            'role',
                                            $user->role
                                        ) === 'admin'
                                    )
                                >

                                    Admin

                                </option>

                            @endif


                        </select>


                    </div>


                    {{-- ACCOUNT STATUS --}}

                    <div>

                        <label
                            for="is_active"
                            class="block mb-2 text-sm font-black text-slate-700 dark:text-slate-200"
                        >

                            Account Status

                        </label>


                        <select
                            id="is_active"
                            name="is_active"
                            required
                            class="w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                        >


                            <option
                                value="1"
                                @selected(
                                    (string) old(
                                        'is_active',
                                        $user->is_active
                                            ? '1'
                                            : '0'
                                    ) === '1'
                                )
                            >

                                Active

                            </option>


                            <option
                                value="0"
                                @selected(
                                    (string) old(
                                        'is_active',
                                        $user->is_active
                                            ? '1'
                                            : '0'
                                    ) === '0'
                                )
                            >

                                Inactive

                            </option>


                        </select>


                    </div>


                    {{-- ADDRESS --}}

                    <div class="md:col-span-2">

                        <label
                            for="address"
                            class="block mb-2 text-sm font-black text-slate-700 dark:text-slate-200"
                        >

                            Address

                        </label>


                        <textarea
                            id="address"
                            name="address"
                            rows="3"
                            class="w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                        >{{ old('address', $user->address) }}</textarea>


                    </div>


                </div>


                {{-- ========================================================= --}}
                {{-- BUTTONS --}}
                {{-- ========================================================= --}}

                <div class="mt-8 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">


                    <a
                        href="{{ $indexRoute }}"
                        class="inline-flex items-center justify-center rounded-2xl bg-slate-500/10 px-6 py-3 text-slate-600 dark:text-slate-300 font-black hover:bg-slate-500/20 transition"
                    >

                        Cancel

                    </a>


                    <button
                        type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 px-7 py-3 text-white font-black shadow-xl hover:scale-[1.02] transition"
                    >

                        <i class="fas fa-floppy-disk"></i>

                        Save Changes

                    </button>


                </div>


            </form>


        </div>


    </div>

</x-app-layout>