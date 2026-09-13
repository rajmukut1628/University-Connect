<x-guest-layout>

    <div class="w-full max-w-md mx-auto">

        {{-- Header --}}
        <div class="text-center mb-8">

            <div class="mx-auto h-16 w-16 rounded-2xl bg-gradient-to-br from-cyan-500 via-indigo-500 to-purple-600 flex items-center justify-center shadow-2xl shadow-indigo-500/30">

                <i class="fas fa-lock text-2xl text-white"></i>

            </div>

            <h1 class="mt-5 text-3xl font-black text-slate-900 dark:text-white">
                Create New Password
            </h1>

            <p class="mt-3 text-sm leading-6 text-slate-500 dark:text-slate-400">

                Create a secure new password for your University Connect account.

            </p>

        </div>


        {{-- Validation Errors --}}
        @if($errors->any())

            <div class="mb-5 rounded-2xl border border-red-500/20 bg-red-500/10 px-4 py-4">

                @foreach($errors->all() as $error)

                    <p class="text-sm font-bold text-red-500">

                        <i class="fas fa-circle-exclamation mr-2"></i>

                        {{ $error }}

                    </p>

                @endforeach

            </div>

        @endif


        <form
            method="POST"
            action="{{ route('password.store') }}"
            class="space-y-5"
        >

            @csrf


            {{-- Reset Token --}}
            <input
                type="hidden"
                name="token"
                value="{{ $request->route('token') }}"
            >


            {{-- Email --}}
            <div>

                <label
                    for="email"
                    class="block mb-2 text-sm font-black text-slate-700 dark:text-slate-200"
                >
                    Email Address
                </label>


                <div class="relative">

                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">

                        <i class="fas fa-envelope"></i>

                    </div>


                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email', $request->email) }}"
                        required
                        autofocus
                        autocomplete="username"
                        class="w-full rounded-2xl border border-slate-300 dark:border-white/10 bg-white dark:bg-white/5 pl-11 pr-4 py-3.5 text-slate-900 dark:text-white focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20"
                    >

                </div>

            </div>


            {{-- New Password --}}
            <div>

                <label
                    for="password"
                    class="block mb-2 text-sm font-black text-slate-700 dark:text-slate-200"
                >
                    New Password
                </label>


                <div class="relative">

                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">

                        <i class="fas fa-lock"></i>

                    </div>


                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="Enter new password"
                        class="w-full rounded-2xl border border-slate-300 dark:border-white/10 bg-white dark:bg-white/5 pl-11 pr-12 py-3.5 text-slate-900 dark:text-white placeholder-slate-400 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20"
                    >


                    <button
                        type="button"
                        onclick="togglePassword('password', 'passwordIcon')"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-purple-500"
                    >

                        <i
                            id="passwordIcon"
                            class="fas fa-eye"
                        ></i>

                    </button>

                </div>

            </div>


            {{-- Confirm Password --}}
            <div>

                <label
                    for="password_confirmation"
                    class="block mb-2 text-sm font-black text-slate-700 dark:text-slate-200"
                >
                    Confirm New Password
                </label>


                <div class="relative">

                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">

                        <i class="fas fa-shield-halved"></i>

                    </div>


                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="Confirm new password"
                        class="w-full rounded-2xl border border-slate-300 dark:border-white/10 bg-white dark:bg-white/5 pl-11 pr-12 py-3.5 text-slate-900 dark:text-white placeholder-slate-400 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20"
                    >


                    <button
                        type="button"
                        onclick="togglePassword('password_confirmation', 'confirmationIcon')"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-purple-500"
                    >

                        <i
                            id="confirmationIcon"
                            class="fas fa-eye"
                        ></i>

                    </button>

                </div>

            </div>


            {{-- Password Tips --}}
            <div class="rounded-2xl bg-cyan-500/5 border border-cyan-500/10 p-4">

                <p class="text-xs font-black text-cyan-500 mb-2">
                    Password Tips
                </p>

                <div class="space-y-1 text-xs text-slate-500 dark:text-slate-400">

                    <p>
                        <i class="fas fa-check mr-2 text-emerald-500"></i>
                        Use at least 8 characters.
                    </p>

                    <p>
                        <i class="fas fa-check mr-2 text-emerald-500"></i>
                        Use a password different from your Official ID.
                    </p>

                    <p>
                        <i class="fas fa-check mr-2 text-emerald-500"></i>
                        Use a combination that is difficult to guess.
                    </p>

                </div>

            </div>


            {{-- Submit --}}
            <button
                type="submit"
                class="w-full rounded-2xl bg-gradient-to-r from-cyan-500 via-indigo-600 to-purple-600 px-5 py-3.5 text-white font-black shadow-xl shadow-indigo-500/20 hover:scale-[1.02] transition duration-300"
            >

                <i class="fas fa-key mr-2"></i>

                Reset Password

            </button>


            <div class="text-center">

                <a
                    href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 text-sm font-black text-purple-500 hover:text-pink-500 transition"
                >

                    <i class="fas fa-arrow-left"></i>

                    Back to Login

                </a>

            </div>

        </form>

    </div>


    <script>

        function togglePassword(inputId, iconId) {

            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (!input || !icon) {
                return;
            }

            if (input.type === 'password') {

                input.type = 'text';

                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');

            } else {

                input.type = 'password';

                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');

            }

        }

    </script>

</x-guest-layout>