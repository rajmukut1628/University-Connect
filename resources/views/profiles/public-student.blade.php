<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-blue-950 to-cyan-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(59,130,246,.30),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(34,211,238,.25),transparent_35%)]"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-cyan-300 font-black">
                        Student Public Profile
                    </p>

                    <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">
                        {{ $profileUser->name }}
                    </h2>

                    <p class="mt-3 text-slate-300 max-w-3xl">
                        Academic and career profile shared for mentorship and networking.
                    </p>
                </div>

                <div class="rounded-3xl bg-white/10 backdrop-blur-xl border border-white/10 px-6 py-5 text-center">
                    <p class="text-xs text-slate-300">Role</p>
                    <p class="text-2xl font-black text-emerald-300">Student</p>
                    <p class="text-xs text-slate-400 mt-1">Mentee Profile</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto space-y-8">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

            {{-- Left Profile Card --}}
            <div class="space-y-8">
                <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-7 shadow-2xl">
                    <div class="text-center">
                        @if($profileUser->profile_image)
                            <img src="{{ $profileUser->getProfileImageUrl() }}"
                                 alt="{{ $profileUser->name }}"
                                 class="mx-auto h-32 w-32 rounded-3xl object-cover border-4 border-white shadow-2xl">
                        @else
                            <div class="mx-auto h-32 w-32 rounded-3xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center text-white text-5xl font-black shadow-2xl">
                                {{ strtoupper(substr($profileUser->name, 0, 1)) }}
                            </div>
                        @endif

                        <h3 class="mt-5 text-3xl font-black text-slate-900 dark:text-white">
                            {{ $profileUser->name }}
                        </h3>

                        <p class="text-cyan-500 font-bold mt-1">
                            Student
                        </p>

                        <p class="text-slate-500 mt-2">
                            {{ $profileUser->department ?: 'Department not specified' }}
                        </p>
                    </div>
                </div>

                {{-- Social Links --}}
                @if($profileUser->github_url || $profileUser->linkedin_url || $profileUser->portfolio_url)
                    <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-7 shadow-2xl">
                        <h4 class="text-xl font-black text-slate-900 dark:text-white mb-4">
                            Social Links
                        </h4>

                        <div class="space-y-3">
                            @if($profileUser->github_url)
                                <a href="{{ $profileUser->github_url }}" target="_blank"
                                   class="block px-4 py-3 rounded-2xl bg-slate-100 dark:bg-slate-950 hover:bg-slate-200 dark:hover:bg-slate-800 transition">
                                    GitHub Profile
                                </a>
                            @endif

                            @if($profileUser->linkedin_url)
                                <a href="{{ $profileUser->linkedin_url }}" target="_blank"
                                   class="block px-4 py-3 rounded-2xl bg-slate-100 dark:bg-slate-950 hover:bg-slate-200 dark:hover:bg-slate-800 transition">
                                    LinkedIn Profile
                                </a>
                            @endif

                            @if($profileUser->portfolio_url)
                                <a href="{{ $profileUser->portfolio_url }}" target="_blank"
                                   class="block px-4 py-3 rounded-2xl bg-slate-100 dark:bg-slate-950 hover:bg-slate-200 dark:hover:bg-slate-800 transition">
                                    Portfolio Website
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- Main Content --}}
            <div class="xl:col-span-2 space-y-8">

                {{-- Academic Information --}}
                <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-6">
                        Academic Information
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="rounded-2xl bg-slate-100 dark:bg-slate-950 p-5">
                            <p class="text-sm text-slate-500">Department</p>
                            <p class="mt-2 font-black text-slate-900 dark:text-white">
                                {{ $profileUser->department ?: 'Not specified' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-100 dark:bg-slate-950 p-5">
                            <p class="text-sm text-slate-500">Batch</p>
                            <p class="mt-2 font-black text-slate-900 dark:text-white">
                                {{ $profileUser->batch ?: 'Not specified' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Skills --}}
                @if($profileUser->skills)
                    <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl">
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-6">
                            Skills
                        </h3>

                        <div class="flex flex-wrap gap-3">
                            @foreach(explode(',', $profileUser->skills) as $skill)
                                <span class="px-4 py-2 rounded-full bg-cyan-500/10 text-cyan-600 dark:text-cyan-300 font-bold">
                                    {{ trim($skill) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Bio --}}
                <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-6">
                        Career Goal / Bio
                    </h3>

                    <p class="text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line">
                        {{ $profileUser->bio ?: 'No biography provided yet.' }}
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex flex-wrap gap-4">
                    <a href="{{ url()->previous() }}"
                       class="px-8 py-4 rounded-2xl bg-slate-950 text-white font-black shadow-xl hover:scale-105 transition">
                        Back
                    </a>

                    <a href="{{ route('messages.index', ['user' => $profileUser->id]) }}"
                       class="px-8 py-4 rounded-2xl bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-black shadow-xl hover:scale-105 transition">
                        Send Message
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>