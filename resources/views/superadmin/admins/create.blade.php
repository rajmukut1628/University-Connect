<x-app-layout>

    <x-slot name="header">

        <div class="rounded-3xl bg-gradient-to-r from-slate-950 via-emerald-950 to-cyan-950 p-8 shadow-2xl border border-white/10">

            <p class="text-sm uppercase tracking-[0.35em] text-emerald-300 font-bold">
                Super Admin Control
            </p>

            <h2 class="mt-3 text-4xl font-black text-white">
                Admin Management
            </h2>

            <p class="mt-3 text-slate-300 max-w-2xl">
                Create and manage administrator accounts for the University Connect platform.
            </p>

        </div>

    </x-slot>


    <div class="max-w-5xl mx-auto space-y-8">


        {{-- ========================================================= --}}
        {{-- OWNER SUPER ADMIN OPTIONS --}}
        {{-- ========================================================= --}}

        @if(auth()->user()->isOwner())

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                {{-- Create Super Admin --}}
                <a
                    href="{{ route('superadmin.super-admins.create') }}"
                    class="group rounded-3xl border border-fuchsia-500/20
                           bg-gradient-to-br from-fuchsia-500/10 to-purple-600/10
                           p-7 shadow-xl hover:shadow-2xl
                           hover:scale-[1.02] transition"
                >

                    <div class="flex items-center gap-5">

                        <div class="h-16 w-16 rounded-2xl
                                    bg-gradient-to-br from-fuchsia-500 to-purple-700
                                    flex items-center justify-center
                                    text-white shadow-xl">

                            <i class="fas fa-user-crown text-2xl"></i>

                        </div>

                        <div>

                            <p class="text-xs uppercase tracking-[0.25em]
                                      text-fuchsia-500 font-black">

                                Owner Only

                            </p>

                            <h3 class="mt-1 text-2xl font-black
                                       text-slate-900 dark:text-white">

                                Create Super Admin

                            </h3>

                            <p class="mt-2 text-sm
                                      text-slate-500 dark:text-slate-400">

                                Add another Super Admin account to the platform.

                            </p>

                        </div>

                    </div>

                </a>


                {{-- Manage Super Admin --}}
                <a
                    href="{{ route('superadmin.super-admins.index') }}"
                    class="group rounded-3xl border border-indigo-500/20
                           bg-gradient-to-br from-indigo-500/10 to-cyan-500/10
                           p-7 shadow-xl hover:shadow-2xl
                           hover:scale-[1.02] transition"
                >

                    <div class="flex items-center gap-5">

                        <div class="h-16 w-16 rounded-2xl
                                    bg-gradient-to-br from-indigo-500 to-cyan-600
                                    flex items-center justify-center
                                    text-white shadow-xl">

                            <i class="fas fa-users-gear text-2xl"></i>

                        </div>

                        <div>

                            <p class="text-xs uppercase tracking-[0.25em]
                                      text-cyan-500 font-black">

                                Owner Only

                            </p>

                            <h3 class="mt-1 text-2xl font-black
                                       text-slate-900 dark:text-white">

                                Manage Super Admins

                            </h3>

                            <p class="mt-2 text-sm
                                      text-slate-500 dark:text-slate-400">

                                View Super Admins, transfer ownership and remove accounts.

                            </p>

                        </div>

                    </div>

                </a>

            </div>

        @endif


        {{-- ========================================================= --}}
        {{-- CREATE GENERAL ADMIN --}}
        {{-- ========================================================= --}}

        <div class="rounded-3xl bg-white dark:bg-slate-900
                    border border-slate-200 dark:border-white/10
                    p-8 shadow-2xl">


            <div class="mb-8">

                <p class="text-xs uppercase tracking-[0.25em]
                          text-emerald-500 font-black">

                    General Admin

                </p>

                <h3 class="mt-2 text-3xl font-black
                           text-slate-900 dark:text-white">

                    Create Admin

                </h3>

                <p class="mt-2 text-sm
                          text-slate-500 dark:text-slate-400">

                    Create a General Admin account for managing users,
                    verification, jobs, events and other platform activities.

                </p>

            </div>


            {{-- Success Message --}}
            @if(session('success'))

                <div class="mb-6 rounded-2xl
                            bg-emerald-500/10
                            border border-emerald-500/20
                            px-5 py-4
                            text-emerald-600 dark:text-emerald-400
                            font-bold">

                    <i class="fas fa-circle-check mr-2"></i>

                    {{ session('success') }}

                </div>

            @endif


            {{-- Validation Errors --}}
            @if($errors->any())

                <div class="mb-6 rounded-2xl
                            bg-red-500/10
                            border border-red-500/20
                            px-5 py-4">

                    <p class="font-black text-red-600 dark:text-red-400">

                        <i class="fas fa-triangle-exclamation mr-2"></i>

                        Please fix the following errors:

                    </p>

                    <ul class="mt-3 list-disc list-inside
                               text-sm text-red-500 space-y-1">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <form
                method="POST"
                action="{{ route('superadmin.admins.store') }}"
                class="space-y-6"
            >

                @csrf


                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                    {{-- Full Name --}}
                    <div>

                        <label
                            for="name"
                            class="font-bold text-slate-700 dark:text-slate-300"
                        >
                            Full Name
                        </label>

                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            placeholder="Enter full name"
                            class="mt-2 w-full rounded-2xl
                                   border-slate-300
                                   dark:border-white/10
                                   dark:bg-slate-950
                                   dark:text-white
                                   focus:border-emerald-500
                                   focus:ring-emerald-500"
                        >

                        @error('name')

                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- Email --}}
                    <div>

                        <label
                            for="email"
                            class="font-bold text-slate-700 dark:text-slate-300"
                        >
                            Email
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            placeholder="admin@example.com"
                            class="mt-2 w-full rounded-2xl
                                   border-slate-300
                                   dark:border-white/10
                                   dark:bg-slate-950
                                   dark:text-white
                                   focus:border-emerald-500
                                   focus:ring-emerald-500"
                        >

                        @error('email')

                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- Phone --}}
                    <div>

                        <label
                            for="phone"
                            class="font-bold text-slate-700 dark:text-slate-300"
                        >
                            Phone
                        </label>

                        <input
                            id="phone"
                            type="text"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="+8801XXXXXXXXX"
                            class="mt-2 w-full rounded-2xl
                                   border-slate-300
                                   dark:border-white/10
                                   dark:bg-slate-950
                                   dark:text-white
                                   focus:border-emerald-500
                                   focus:ring-emerald-500"
                        >

                        @error('phone')

                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- Address --}}
                    <div>

                        <label
                            for="address"
                            class="font-bold text-slate-700 dark:text-slate-300"
                        >
                            Address
                        </label>

                        <input
                            id="address"
                            type="text"
                            name="address"
                            value="{{ old('address') }}"
                            placeholder="Dhaka, Bangladesh"
                            class="mt-2 w-full rounded-2xl
                                   border-slate-300
                                   dark:border-white/10
                                   dark:bg-slate-950
                                   dark:text-white
                                   focus:border-emerald-500
                                   focus:ring-emerald-500"
                        >

                        @error('address')

                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- Password --}}
                    <div>

                        <label
                            for="password"
                            class="font-bold text-slate-700 dark:text-slate-300"
                        >
                            Password
                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            placeholder="Enter password"
                            class="mt-2 w-full rounded-2xl
                                   border-slate-300
                                   dark:border-white/10
                                   dark:bg-slate-950
                                   dark:text-white
                                   focus:border-emerald-500
                                   focus:ring-emerald-500"
                        >

                        @error('password')

                            <p class="text-red-500 text-sm mt-1">
                                {{ $message }}
                            </p>

                        @enderror

                    </div>


                    {{-- Confirm Password --}}
                    <div>

                        <label
                            for="password_confirmation"
                            class="font-bold text-slate-700 dark:text-slate-300"
                        >
                            Confirm Password
                        </label>

                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            placeholder="Confirm password"
                            class="mt-2 w-full rounded-2xl
                                   border-slate-300
                                   dark:border-white/10
                                   dark:bg-slate-950
                                   dark:text-white
                                   focus:border-emerald-500
                                   focus:ring-emerald-500"
                        >

                    </div>

                </div>


                {{-- Permission Information --}}
                <div class="rounded-3xl
                            bg-emerald-500/10
                            border border-emerald-500/20
                            p-6">

                    <div class="flex items-start gap-4">

                        <div class="h-12 w-12 shrink-0
                                    rounded-2xl
                                    bg-emerald-500/15
                                    text-emerald-500
                                    flex items-center justify-center">

                            <i class="fas fa-user-shield text-xl"></i>

                        </div>

                        <div>

                            <h3 class="text-xl font-black text-emerald-600">

                                Admin Permission

                            </h3>

                            <p class="mt-2 text-sm
                                      text-slate-600 dark:text-slate-400">

                                This account will be created with
                                <strong>Admin</strong> role.

                                It will not receive Super Admin or Owner permission.

                            </p>

                        </div>

                    </div>

                </div>


                {{-- Buttons --}}
                <div class="flex flex-col md:flex-row gap-4 pt-4">

                    <button
                        type="submit"
                        class="px-8 py-4 rounded-2xl
                               bg-gradient-to-r from-emerald-500 to-cyan-600
                               text-white font-black
                               shadow-xl
                               hover:scale-105
                               transition"
                    >

                        <i class="fas fa-user-shield mr-2"></i>

                        Create Admin

                    </button>


                    <a
                        href="{{ route('superadmin.dashboard') }}"
                        class="px-8 py-4 rounded-2xl
                               bg-slate-950
                               text-white font-black
                               shadow-xl
                               hover:scale-105
                               transition
                               text-center"
                    >

                        <i class="fas fa-arrow-left mr-2"></i>

                        Back to Dashboard

                    </a>

                </div>

            </form>

        </div>


        {{-- ========================================================= --}}
        {{-- OWNER INFORMATION --}}
        {{-- ========================================================= --}}

        @if(auth()->user()->isOwner())

            <div class="rounded-3xl
                        border border-purple-500/20
                        bg-purple-500/10
                        p-6">

                <div class="flex items-start gap-4">

                    <div class="h-12 w-12 shrink-0
                                rounded-2xl
                                bg-purple-500/15
                                text-purple-500
                                flex items-center justify-center">

                        <i class="fas fa-crown text-xl"></i>

                    </div>

                    <div>

                        <h3 class="font-black text-purple-600 text-lg">
                            Owner Super Admin Access
                        </h3>

                        <p class="mt-2 text-sm
                                  text-slate-600 dark:text-slate-400">

                            You are currently the Owner Super Admin.
                            You can create another Super Admin,
                            manage Super Admin accounts and transfer ownership.

                        </p>

                    </div>

                </div>

            </div>

        @endif

    </div>

</x-app-layout>