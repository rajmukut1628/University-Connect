<x-app-layout>
    <x-slot name="header">
        <div class="rounded-3xl bg-gradient-to-r from-slate-950 via-blue-950 to-indigo-950 p-8 shadow-2xl border border-white/10">
            <h2 class="text-4xl font-black text-white">
                AI Resume Analyzer
            </h2>
            <p class="mt-3 text-slate-300">
                Upload your CV and get AI score, skills, missing skills and career suggestions.
            </p>
        </div>
    </x-slot>

    <div class="space-y-8">

        @if(session('success'))
            <div class="rounded-2xl bg-emerald-500/15 border border-emerald-500/30 p-4 text-emerald-600 font-bold">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Upload Form --}}
            <div class="lg:col-span-1 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-7 shadow-xl">
                <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                    Upload Resume
                </h3>

                <form method="POST" action="{{ route('resume-analyzer.store') }}" enctype="multipart/form-data" class="mt-6 space-y-5">
                    @csrf

                    <div>
                        <label class="font-bold text-slate-700 dark:text-slate-300">Resume Title</label>
                        <input type="text" name="resume_title" value="{{ old('resume_title') }}"
                               placeholder="Example: Laravel Internship CV"
                               class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                        @error('resume_title')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="font-bold text-slate-700 dark:text-slate-300">Resume File</label>
                        <input type="file" name="resume_file" required
                               class="mt-2 w-full rounded-2xl border border-slate-300 dark:border-white/10 p-3 dark:bg-slate-950 dark:text-white">
                        <p class="text-xs text-slate-500 mt-2">PDF, DOC, DOCX, TXT — Max 5MB</p>
                        @error('resume_file')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full rounded-2xl bg-gradient-to-r from-blue-600 to-cyan-500 py-3 text-white font-black shadow-xl hover:scale-105 transition">
                        Analyze Resume
                    </button>
                </form>
            </div>

            {{-- Results --}}
            <div class="lg:col-span-2 space-y-6">
                @forelse($analyses as $analysis)
                    <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-7 shadow-xl">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                                    {{ $analysis->resume_title ?? 'My Resume' }}
                                </h3>
                                <p class="text-sm text-slate-500 mt-1">
                                    {{ $analysis->original_file_name }}
                                </p>
                            </div>

                            <div class="text-center rounded-2xl bg-slate-950 px-6 py-4 text-white">
                                <p class="text-xs text-slate-300">AI Score</p>
                                <p class="text-4xl font-black">{{ $analysis->score }}%</p>
                            </div>
                        </div>

                        <div class="mt-6 h-3 w-full rounded-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-cyan-500"
                                 style="width: {{ $analysis->score }}%">
                            </div>
                        </div>

                        <p class="mt-5 text-slate-600 dark:text-slate-300 leading-relaxed">
                            {{ $analysis->summary }}
                        </p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-6">
                            <div class="rounded-2xl bg-emerald-500/10 p-5">
                                <h4 class="font-black text-emerald-600">Detected Skills</h4>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach($analysis->detected_skills ?? [] as $skill)
                                        <span class="px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-600 text-xs font-bold">
                                            {{ $skill }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="rounded-2xl bg-red-500/10 p-5">
                                <h4 class="font-black text-red-600">Missing Skills</h4>
                                <div class="mt-3 flex flex-wrap gap-2">
                                    @foreach($analysis->missing_skills ?? [] as $skill)
                                        <span class="px-3 py-1 rounded-full bg-red-500/15 text-red-600 text-xs font-bold">
                                            {{ $skill }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 rounded-2xl bg-blue-500/10 p-5">
                            <h4 class="font-black text-blue-600">Recommended Roles</h4>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach($analysis->recommended_roles ?? [] as $role)
                                    <span class="px-3 py-1 rounded-full bg-blue-500/15 text-blue-600 text-xs font-bold">
                                        {{ $role }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-6 rounded-2xl bg-purple-500/10 p-5">
                            <h4 class="font-black text-purple-600">AI Suggestions</h4>
                            <ul class="mt-3 space-y-2">
                                @foreach($analysis->suggestions ?? [] as $suggestion)
                                    <li class="text-sm text-slate-600 dark:text-slate-300">
                                        ✨ {{ $suggestion }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <form method="POST" action="{{ route('resume-analyzer.destroy', $analysis->id) }}" class="mt-6">
                            @csrf
                            @method('DELETE')

                            <button type="submit"
                                    onclick="return confirm('Delete this resume analysis?')"
                                    class="rounded-xl bg-red-500/15 px-5 py-2 text-red-600 font-bold">
                                Delete
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="rounded-3xl border border-dashed border-slate-300 dark:border-white/10 p-10 text-center">
                        <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                            No Resume Analyzed Yet
                        </h3>
                        <p class="text-slate-500 mt-2">
                            Upload your first CV to get AI analysis.
                        </p>
                    </div>
                                    {{-- AI Matched Jobs --}}
                @if(isset($matchedJobs) && $matchedJobs->count() > 0)
                    <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-7 shadow-xl">
                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                            <div>
                                <h3 class="text-2xl font-black text-slate-900 dark:text-white">
                                    AI Matched Jobs
                                </h3>
                                <p class="text-slate-500 mt-2">
                                    Jobs recommended based on your detected resume skills.
                                </p>
                            </div>

                            <div class="px-4 py-2 rounded-2xl bg-gradient-to-r from-emerald-500 to-cyan-500 text-white font-black shadow-xl">
                                {{ $matchedJobs->count() }} Matches
                            </div>
                        </div>

                        <div class="mt-6 space-y-5">
                            @foreach($matchedJobs as $job)
                                <div class="rounded-3xl border border-white/10 bg-gradient-to-r from-emerald-500/10 to-cyan-500/10 p-5">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                        <div>
                                            <h4 class="text-lg font-black text-slate-900 dark:text-white">
                                                {{ $job->title ?? 'Untitled Job' }}
                                            </h4>

                                            <p class="text-sm text-slate-500 mt-1">
                                                {{ $job->company_name ?? $job->company ?? 'Company not specified' }}
                                                @if(!empty($job->location))
                                                    • {{ $job->location }}
                                                @endif
                                            </p>

                                            <div class="mt-4 flex flex-wrap gap-2">
                                                <span class="px-3 py-1 rounded-full bg-emerald-500/15 text-emerald-600 text-xs font-bold">
                                                    {{ $job->match_score ?? 0 }}% Match
                                                </span>

                                                @foreach(($job->matched_skills ?? []) as $skill)
                                                    <span class="px-3 py-1 rounded-full bg-cyan-500/15 text-cyan-600 text-xs font-bold">
                                                        {{ $skill }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>

                                        <a href="{{ route('jobs.show', $job->id) }}"
                                           class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-cyan-500 text-white font-bold shadow-xl hover:scale-105 transition">
                                            View Job
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>