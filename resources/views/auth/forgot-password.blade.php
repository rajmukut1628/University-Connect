<x-guest-layout>

    <div class="w-full max-w-md mx-auto">

        {{-- Header --}}
        <div class="text-center mb-8">

            <div class="mx-auto h-16 w-16 rounded-2xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center shadow-2xl shadow-purple-500/30">

                <i class="fas fa-key text-2xl text-white"></i>

            </div>

            <h1 class="mt-5 text-3xl font-black text-slate-900 dark:text-white">
                Forgot Password?
            </h1>

            <p class="mt-3 text-sm leading-6 text-slate-500 dark:text-slate-400">

                Enter the email address connected to your University Connect account.
                We will send you a secure password reset link.

            </p>

        </div>


        {{-- Session Status --}}
        @if(session('status'))

            <div class="mb-5 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-4">

                <div class="flex items-start gap-3">

                    <div class="h-9 w-9 shrink-0 rounded-xl bg-emerald-500/15 text-emerald-500 flex items-center justify-center">

                        <i class="fas fa-circle-check"></i>

                    </div>

                    <div>

                        <p class="font-black text-emerald-500">
                            Reset Link Sent
                        </p>

                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                            {{ session('status') }}
                        </p>

                    </div>

                </div>

            </div>

        @endif


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


        {{-- Form --}}
        <form
            method="POST"
            action="{{ route('password.email') }}"
            class="space-y-5"
        >

            @csrf


            {{-- Email --}}
            <div>

                <label
                    for="email"
                    class="block mb-2 text-sm font-black text-slate-700 dark:text-slate-200"
                >
                    Registered Email Address
                </label>


                <div class="relative">

                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">

                        <i class="fas fa-envelope"></i>

                    </div>


                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="Enter your registered email"
                        class="w-full rounded-2xl border border-slate-300 dark:border-white/10 bg-white dark:bg-white/5 pl-11 pr-4 py-3.5 text-slate-900 dark:text-white placeholder-slate-400 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20"
                    >

                </div>

            </div>


            {{-- Submit --}}
            <button
                type="submit"
                class="w-full rounded-2xl bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 px-5 py-3.5 text-white font-black shadow-xl shadow-purple-500/20 hover:scale-[1.02] transition duration-300"
            >

                <i class="fas fa-paper-plane mr-2"></i>

                Send Password Reset Link

            </button>


            {{-- Back To Login --}}
            <div class="pt-2 text-center">

                <a
                    href="{{ route('login') }}"
                    class="inline-flex items-center gap-2 text-sm font-black text-purple-500 hover:text-pink-500 transition"
                >

                    <i class="fas fa-arrow-left"></i>

                    Back to Login

                </a>

            </div>


            {{-- Information --}}
            <div class="rounded-2xl bg-slate-500/5 border border-slate-500/10 p-4">

                <p class="text-xs leading-5 text-slate-500 dark:text-slate-400">

                    <i class="fas fa-circle-info mr-1 text-cyan-500"></i>

                    Password reset is available through your registered email address.
                    Your Official ID cannot be used for password recovery.

                </p>

            </div>

        </form>

    </div>

</x-guest-layout>