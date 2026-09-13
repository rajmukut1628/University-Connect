<x-guest-layout>

    <div class="min-h-screen relative overflow-hidden bg-slate-950 text-white flex items-center justify-center px-4 py-10">

        {{-- Background Effects --}}
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(14,165,233,.35),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(99,102,241,.35),transparent_35%)]"></div>

        <div class="absolute inset-0 bg-[linear-gradient(rgba(14,165,233,.10)_1px,transparent_1px),linear-gradient(90deg,rgba(99,102,241,.10)_1px,transparent_1px)] bg-[size:70px_70px]"></div>

        <div class="absolute -top-28 -left-28 h-96 w-96 rounded-full bg-cyan-500/30 blur-3xl animate-pulse"></div>

        <div class="absolute -bottom-28 -right-28 h-96 w-96 rounded-full bg-indigo-500/30 blur-3xl animate-pulse"></div>


        <div class="relative z-10 w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">

            {{-- Left Side Hero --}}
            <div class="hidden lg:block">

                <div class="h-16 w-16 rounded-3xl bg-gradient-to-br from-cyan-500 via-blue-500 to-indigo-500 flex items-center justify-center shadow-2xl shadow-cyan-500/30">

                    <i class="fas fa-right-to-bracket text-3xl"></i>

                </div>


                <h1 class="mt-6 text-6xl font-black leading-tight">

                    Welcome Back to

                    <span class="block bg-gradient-to-r from-cyan-300 via-blue-300 to-indigo-300 bg-clip-text text-transparent">

                        University Connect

                    </span>

                </h1>


                <p class="mt-5 text-slate-300 text-lg max-w-xl">

                    Access your AI-powered campus dashboard,
                    mentorship network, job portal,
                    events and smart university ecosystem.

                </p>


                <div class="mt-8 rounded-3xl border border-white/10 bg-white/5 p-5 max-w-xl">

                    <div class="flex items-start gap-4">

                        <div class="h-11 w-11 rounded-2xl bg-cyan-500/15 text-cyan-300 flex items-center justify-center shrink-0">

                            <i class="fas fa-id-card"></i>

                        </div>


                        <div>

                            <p class="font-black text-white">
                                Student & Alumni Login
                            </p>

                            <p class="mt-1 text-sm text-slate-300 leading-6">

                                Students and Alumni can sign in using either
                                their verified email address or Official ID.

                            </p>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Login Card --}}
            <div class="rounded-[2rem] border border-white/10 bg-white/10 backdrop-blur-2xl shadow-2xl p-7 md:p-9">


                {{-- Card Header --}}
                <div class="text-center mb-8">

                    <div class="mx-auto h-16 w-16 rounded-3xl bg-gradient-to-br from-cyan-500 via-blue-500 to-indigo-500 flex items-center justify-center shadow-2xl shadow-cyan-500/30">

                        <i class="fas fa-lock text-2xl"></i>

                    </div>


                    <h2 class="mt-5 text-3xl font-black">
                        Secure Login
                    </h2>


                    <p class="mt-2 text-sm text-slate-300">

                        Enter your email or Official ID and password

                    </p>

                </div>


                {{-- Status Message --}}
                <x-auth-session-status
                    class="mb-4 text-emerald-300"
                    :status="session('status')"
                />


                {{-- Login Form --}}
                <form
                    method="POST"
                    action="{{ route('login') }}"
                    class="space-y-5"
                >

                    @csrf


                    {{-- Email / Official ID --}}
                    <div>

                        <label
                            for="login"
                            class="text-sm font-bold text-slate-200"
                        >

                            Email or Official ID

                        </label>


                        <input
                            id="login"
                            type="text"
                            name="login"
                            value="{{ old('login') }}"
                            required
                            autofocus
                            autocomplete="username"
                            class="mt-2 w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-white placeholder-slate-400 focus:border-cyan-400 focus:ring-cyan-400"
                            placeholder="Email or Official ID"
                        >


                        <x-input-error
                            :messages="$errors->get('login')"
                            class="mt-2 text-red-300"
                        />

                    </div>


                    {{-- Password --}}
                    <div>

                        <label
                            for="password"
                            class="text-sm font-bold text-slate-200"
                        >

                            Password

                        </label>


                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            class="mt-2 w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-white placeholder-slate-400 focus:border-cyan-400 focus:ring-cyan-400"
                            placeholder="Enter password"
                        >


                        <x-input-error
                            :messages="$errors->get('password')"
                            class="mt-2 text-red-300"
                        />

                    </div>


                    {{-- Remember + Forgot --}}
                    <div class="flex items-center justify-between text-sm">

                        <label class="inline-flex items-center gap-2 text-slate-300">

                            <input
                                type="checkbox"
                                name="remember"
                                class="rounded border-white/20 bg-white/10 text-cyan-500 focus:ring-cyan-400"
                            >

                            Remember me

                        </label>


                        @if(Route::has('password.request'))

                            <a
                                href="{{ route('password.request') }}"
                                class="text-cyan-300 font-bold hover:text-white"
                            >

                                Forgot Password?

                            </a>

                        @endif

                    </div>


                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="w-full rounded-2xl bg-gradient-to-r from-cyan-500 via-blue-500 to-indigo-500 py-3.5 font-black shadow-2xl shadow-cyan-500/30 hover:scale-[1.02] transition"
                    >

                        <i class="fas fa-fingerprint mr-2"></i>

                        Login Securely

                    </button>


                    {{-- Information --}}
                    <div class="rounded-2xl border border-cyan-400/15 bg-cyan-400/5 p-4">

                        <div class="flex items-start gap-3">

                            <i class="fas fa-circle-info text-cyan-300 mt-1"></i>

                            <p class="text-xs leading-5 text-slate-300">

                                Student and Alumni accounts are created from the
                                university verified database. No manual registration
                                is required.

                            </p>

                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-guest-layout>