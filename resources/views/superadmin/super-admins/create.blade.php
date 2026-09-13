<x-app-layout>

    <x-slot name="header">

        <div class="rounded-3xl bg-gradient-to-r from-slate-950 via-fuchsia-950 to-purple-950 p-8 shadow-2xl border border-white/10">

            <p class="text-sm uppercase tracking-[0.35em] text-fuchsia-300 font-bold">
                Owner Super Admin
            </p>

            <h2 class="mt-3 text-4xl font-black text-white">
                Create Super Admin
            </h2>

            <p class="mt-3 text-slate-300 max-w-2xl">
                Create another Super Admin account for advanced platform administration.
            </p>

        </div>

    </x-slot>


    <div class="max-w-4xl mx-auto space-y-6">


        {{-- Validation Errors --}}
        @if($errors->any())

            <div class="rounded-2xl bg-red-500/10
                        border border-red-500/20
                        px-5 py-4">

                <p class="font-black text-red-600 dark:text-red-400">

                    <i class="fas fa-triangle-exclamation mr-2"></i>

                    Please fix the following errors:

                </p>


                <ul class="mt-3 list-disc list-inside
                           text-sm text-red-500 space-y-1">

                    @foreach($errors->all() as $error)

                        <li>
                            {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <div class="rounded-3xl
                    bg-white dark:bg-slate-900
                    border border-slate-200 dark:border-white/10
                    p-8 shadow-2xl">


            <div class="mb-8">

                <p class="text-xs uppercase tracking-[0.25em]
                          text-fuchsia-500 font-black">

                    Super Admin Account

                </p>

                <h3 class="mt-2 text-3xl font-black
                           text-slate-900 dark:text-white">

                    Account Information

                </h3>

                <p class="mt-2 text-sm
                          text-slate-500 dark:text-slate-400">

                    The new account will receive Super Admin permission,
                    but it will not become the Owner automatically.

                </p>

            </div>


            <form
                method="POST"
                action="{{ route('superadmin.super-admins.store') }}"
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

                            <span class="text-red-500">*</span>

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
                                   focus:border-fuchsia-500
                                   focus:ring-fuchsia-500"
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

                            <span class="text-red-500">*</span>

                        </label>


                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            placeholder="superadmin@example.com"
                            class="mt-2 w-full rounded-2xl
                                   border-slate-300
                                   dark:border-white/10
                                   dark:bg-slate-950
                                   dark:text-white
                                   focus:border-fuchsia-500
                                   focus:ring-fuchsia-500"
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
                                   focus:border-fuchsia-500
                                   focus:ring-fuchsia-500"
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
                                   focus:border-fuchsia-500
                                   focus:ring-fuchsia-500"
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

                            <span class="text-red-500">*</span>

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
                                   focus:border-fuchsia-500
                                   focus:ring-fuchsia-500"
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

                            <span class="text-red-500">*</span>

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
                                   focus:border-fuchsia-500
                                   focus:ring-fuchsia-500"
                        >

                    </div>

                </div>


                {{-- Permission --}}
                <div class="rounded-3xl
                            bg-fuchsia-500/10
                            border border-fuchsia-500/20
                            p-6">

                    <div class="flex items-start gap-4">

                        <div class="h-12 w-12 rounded-2xl
                                    bg-fuchsia-500/15
                                    text-fuchsia-500
                                    flex items-center justify-center
                                    shrink-0">

                            <i class="fas fa-user-shield text-xl"></i>

                        </div>


                        <div>

                            <h3 class="text-xl font-black text-fuchsia-600">
                                Super Admin Permission
                            </h3>

                            <p class="mt-2 text-sm
                                      text-slate-600 dark:text-slate-400">

                                This account will be created with
                                <strong>Super Admin</strong> role.

                                It will not become the Owner automatically.

                                Ownership can only be transferred by the
                                current Owner.

                            </p>

                        </div>

                    </div>

                </div>


                {{-- Buttons --}}
                <div class="flex flex-col md:flex-row gap-4 pt-4">

                    <button
                        type="submit"
                        class="px-8 py-4 rounded-2xl
                               bg-gradient-to-r from-fuchsia-500 to-purple-700
                               text-white font-black
                               shadow-xl
                               hover:scale-105
                               transition"
                    >

                        <i class="fas fa-user-plus mr-2"></i>

                        Create Super Admin

                    </button>


                    <a
                        href="{{ route('superadmin.super-admins.index') }}"
                        class="px-8 py-4 rounded-2xl
                               bg-slate-950
                               text-white font-black
                               shadow-xl
                               hover:scale-105
                               transition
                               text-center"
                    >

                        <i class="fas fa-arrow-left mr-2"></i>

                        Back to Super Admins

                    </a>

                </div>

            </form>

        </div>

    </div>

</x-app-layout>