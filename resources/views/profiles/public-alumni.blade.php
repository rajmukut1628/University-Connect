<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-purple-950 to-indigo-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(168,85,247,.30),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(59,130,246,.25),transparent_35%)]"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-purple-300 font-black">
                        Alumni Public Profile
                    </p>

                    <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">
                        {{ $profileUser->name }}
                    </h2>

                    <p class="mt-3 text-slate-300 max-w-3xl">
                        Professional alumni profile for mentorship, networking and career guidance.
                    </p>
                </div>

                <div class="rounded-3xl bg-white/10 backdrop-blur-xl border border-white/10 px-6 py-5 text-center">
                    <p class="text-xs text-slate-300">Role</p>
                    <p class="text-2xl font-black text-emerald-300">Alumni</p>
                    <p class="text-xs text-slate-400 mt-1">Mentor Profile</p>
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
                            <div class="mx-auto h-32 w-32 rounded-3xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white text-5xl font-black shadow-2xl">
                                {{ strtoupper(substr($profileUser->name, 0, 1)) }}
                            </div>
                        @endif

                        <h3 class="mt-5 text-3xl font-black text-slate-900 dark:text-white">
                            {{ $profileUser->name }}
                        </h3>

                        <p class="text-purple-500 font-bold mt-1">
                            {{ $profileUser->current_designation ?: 'Alumni Mentor' }}
                        </p>

                        <p class="text-slate-500 mt-2">
                            {{ $profileUser->current_company ?: 'Professional Alumni' }}
                        </p>
                    </div>
                </div>

                {{-- Social Links --}}
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
                            <p class="text-sm text-slate-500">Batch / Passing Year</p>
                            <p class="mt-2 font-black text-slate-900 dark:text-white">
                                {{ $profileUser->batch ?: 'Not specified' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Professional Experience --}}
                <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-6">
                        Professional Experience
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="rounded-2xl bg-slate-100 dark:bg-slate-950 p-5">
                            <p class="text-sm text-slate-500">Current Company</p>
                            <p class="mt-2 font-black text-slate-900 dark:text-white">
                                {{ $profileUser->current_company ?: 'Not specified' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-100 dark:bg-slate-950 p-5">
                            <p class="text-sm text-slate-500">Current Designation</p>
                            <p class="mt-2 font-black text-slate-900 dark:text-white">
                                {{ $profileUser->current_designation ?: 'Not specified' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-100 dark:bg-slate-950 p-5">
                            <p class="text-sm text-slate-500">Job Type</p>
                            <p class="mt-2 font-black text-slate-900 dark:text-white">
                                {{ $profileUser->current_job_type ?: 'Not specified' }}
                            </p>
                        </div>

                        <div class="rounded-2xl bg-slate-100 dark:bg-slate-950 p-5">
                            <p class="text-sm text-slate-500">Total Experience</p>
                            <p class="mt-2 font-black text-slate-900 dark:text-white">
                                {{ $profileUser->work_experience_years ?: 'Not specified' }}
                            </p>
                        </div>
                    </div>

                    @if($profileUser->previous_company || $profileUser->previous_designation || $profileUser->previous_job_details)
                        <div class="mt-6 rounded-2xl bg-slate-100 dark:bg-slate-950 p-6">
                            <h4 class="text-xl font-black text-slate-900 dark:text-white mb-4">
                                Previous Experience
                            </h4>

                            <p class="font-bold text-slate-900 dark:text-white">
                                {{ $profileUser->previous_designation ?: 'Previous Role' }}
                                @if($profileUser->previous_company)
                                    at {{ $profileUser->previous_company }}
                                @endif
                            </p>

                            @if($profileUser->previous_job_details)
                                <p class="mt-3 text-slate-600 dark:text-slate-300 leading-relaxed">
                                    {{ $profileUser->previous_job_details }}
                                </p>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Bio --}}
                <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl">
                    <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-6">
                        Professional Bio
                    </h3>

                    <p class="text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line">
                        {{ $profileUser->bio ?: 'No biography provided yet.' }}
                    </p>
                </div>

                {{-- Skills --}}
                @if($profileUser->skills)
                    <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl">
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white mb-6">
                            Skills & Expertise
                        </h3>

                        <div class="flex flex-wrap gap-3">
                            @foreach(explode(',', $profileUser->skills) as $skill)
                                <span class="px-4 py-2 rounded-full bg-purple-500/10 text-purple-600 dark:text-purple-300 font-bold">
                                    {{ trim($skill) }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Actions --}}
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('mentors.index') }}"
                       class="px-8 py-4 rounded-2xl bg-slate-950 text-white font-black shadow-xl hover:scale-105 transition">
                        Back to Mentors
                    </a>

                    <a href="{{ route('messages.index', ['user' => $profileUser->id]) }}"
                       class="px-8 py-4 rounded-2xl bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-black shadow-xl hover:scale-105 transition">
                        Send Message
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>