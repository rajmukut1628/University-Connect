<x-guest-layout>
    <div class="min-h-screen relative overflow-hidden bg-slate-950 text-white flex items-center justify-center px-4 py-10">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(59,130,246,.35),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(236,72,153,.35),transparent_35%)]"></div>
        <div class="absolute inset-0 bg-[linear-gradient(rgba(99,102,241,.10)_1px,transparent_1px),linear-gradient(90deg,rgba(236,72,153,.10)_1px,transparent_1px)] bg-[size:70px_70px]"></div>

        <div class="absolute -top-28 -left-28 h-96 w-96 rounded-full bg-indigo-500/30 blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-28 -right-28 h-96 w-96 rounded-full bg-fuchsia-500/30 blur-3xl animate-pulse"></div>

        <div class="relative z-10 w-full max-w-6xl grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">

            <div class="hidden lg:block">
                <div class="mb-8">
                    <div class="h-16 w-16 rounded-3xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center shadow-2xl shadow-fuchsia-500/30">
                        <i class="fas fa-graduation-cap text-3xl"></i>
                    </div>

                    <h1 class="mt-6 text-6xl font-black leading-tight">
                        Join the
                        <span class="block bg-gradient-to-r from-cyan-300 via-indigo-300 to-fuchsia-300 bg-clip-text text-transparent">
                            Verified Campus
                        </span>
                    </h1>

                    <p class="mt-5 text-slate-300 text-lg max-w-xl">
                        Only official university students and alumni can create an account through secure database matching.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4 max-w-xl">
                    <div class="rounded-3xl border border-white/10 bg-white/10 backdrop-blur-2xl p-5">
                        <i class="fas fa-shield-halved text-cyan-300 text-2xl"></i>
                        <h3 class="mt-4 font-black text-xl">Verified Access</h3>
                        <p class="text-sm text-slate-400 mt-2">Student ID / Alumni ID required.</p>
                    </div>

                    <div class="rounded-3xl border border-white/10 bg-white/10 backdrop-blur-2xl p-5">
                        <i class="fas fa-handshake-angle text-fuchsia-300 text-2xl"></i>
                        <h3 class="mt-4 font-black text-xl">Smart Network</h3>
                        <p class="text-sm text-slate-400 mt-2">Connect with alumni and students.</p>
                    </div>
                </div>
            </div>

            <div class="rounded-[2rem] border border-white/10 bg-white/10 backdrop-blur-2xl shadow-2xl p-7 md:p-9">
                <div class="text-center mb-8">
                    <div class="mx-auto h-16 w-16 rounded-3xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center shadow-2xl shadow-fuchsia-500/30">
                        <i class="fas fa-user-plus text-2xl"></i>
                    </div>

                    <h2 class="mt-5 text-3xl font-black">Create Verified Account</h2>
                    <p class="mt-2 text-sm text-slate-300">Official university database matching enabled</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <div>
                        <label class="text-sm font-bold text-slate-200">Full Name</label>
                        <input name="name" value="{{ old('name') }}" required autofocus
                               class="mt-2 w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-white placeholder-slate-400 focus:border-fuchsia-400 focus:ring-fuchsia-400"
                               placeholder="Enter your full name">
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-red-300" />
                    </div>

                    <div>
                        <label class="text-sm font-bold text-slate-200">Official Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                               class="mt-2 w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-white placeholder-slate-400 focus:border-fuchsia-400 focus:ring-fuchsia-400"
                               placeholder="student@university.com">
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300" />
                    </div>

                    <div>
                        <label class="text-sm font-bold text-slate-200">Account Type</label>
                        <select name="role" required
                                class="mt-2 w-full rounded-2xl border border-white/10 bg-slate-900 px-4 py-3 text-white focus:border-fuchsia-400 focus:ring-fuchsia-400">
                            <option value="student" @selected(old('role') === 'student')>Student</option>
                            <option value="alumni" @selected(old('role') === 'alumni')>Alumni</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2 text-red-300" />
                    </div>

                    <div>
                        <label class="text-sm font-bold text-slate-200">Student ID / Alumni ID</label>
                        <input name="official_id" value="{{ old('official_id') }}" required
                               class="mt-2 w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-white placeholder-slate-400 focus:border-fuchsia-400 focus:ring-fuchsia-400"
                               placeholder="Enter your official ID">
                        <x-input-error :messages="$errors->get('official_id')" class="mt-2 text-red-300" />
                    </div>

                    <div>
                        <label class="text-sm font-bold text-slate-200">Password</label>
                        <input type="password" name="password" required
                               class="mt-2 w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-white placeholder-slate-400 focus:border-fuchsia-400 focus:ring-fuchsia-400"
                               placeholder="Create strong password">
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300" />
                    </div>

                    <div>
                        <label class="text-sm font-bold text-slate-200">Confirm Password</label>
                        <input type="password" name="password_confirmation" required
                               class="mt-2 w-full rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-white placeholder-slate-400 focus:border-fuchsia-400 focus:ring-fuchsia-400"
                               placeholder="Confirm password">
                    </div>

                    <button class="w-full rounded-2xl bg-gradient-to-r from-indigo-500 via-purple-500 to-fuchsia-500 py-3.5 font-black shadow-2xl shadow-fuchsia-500/30 hover:scale-[1.02] transition">
                        <i class="fas fa-shield-check mr-2"></i>
                        Create Verified Account
                    </button>

                    <p class="text-center text-sm text-slate-300">
                        Already registered?
                        <a href="{{ route('login') }}" class="text-fuchsia-300 font-black hover:text-white">Login</a>
                    </p>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>