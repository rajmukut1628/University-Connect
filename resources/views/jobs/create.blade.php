<x-app-layout>

    <x-slot name="header">

        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-blue-950 to-purple-950 p-8 shadow-2xl border border-white/10">

            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(14,165,233,.40),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(236,72,153,.30),transparent_35%)]"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                <div>

                    <p class="text-sm uppercase tracking-[0.35em] text-cyan-300 font-black">
                        Job Management
                    </p>

                    <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">

                        @if(in_array(auth()->user()->role, ['admin', 'super_admin'], true))
                            Add New Job
                        @else
                            Post a Job
                        @endif

                    </h2>

                    <p class="mt-3 text-slate-300 max-w-2xl">

                        @if(in_array(auth()->user()->role, ['admin', 'super_admin'], true))
                            Create and publish a verified job opportunity.
                        @else
                            Share a job opportunity with the university community.
                        @endif

                    </p>

                </div>


                <a
                    href="{{ route('jobs.index') }}"
                    class="px-6 py-4 rounded-2xl bg-white/10 border border-white/10 text-white font-black hover:bg-white/20 transition"
                >
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Jobs
                </a>

            </div>

        </div>

    </x-slot>


    <div class="max-w-5xl mx-auto space-y-8">


        @if($errors->any())

            <div class="rounded-3xl bg-red-500/10 border border-red-500/30 p-6">

                <h3 class="font-black text-red-500">
                    Please correct the following errors:
                </h3>

                <ul class="mt-3 space-y-1 text-sm text-red-500">

                    @foreach($errors->all() as $error)

                        <li>
                            • {{ $error }}
                        </li>

                    @endforeach

                </ul>

            </div>

        @endif


        <form
            method="POST"
            action="{{ route('jobs.store') }}"
            class="space-y-8"
        >

            @csrf


            {{-- Basic Information --}}

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl">

                <p class="text-sm uppercase tracking-[0.25em] text-cyan-500 font-black">
                    Basic Information
                </p>

                <h3 class="mt-2 text-3xl font-black text-slate-900 dark:text-white">
                    Job Details
                </h3>


                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">


                    <div class="md:col-span-2">

                        <label class="font-bold text-slate-700 dark:text-slate-300">
                            Job Title *
                        </label>

                        <input
                            type="text"
                            name="title"
                            value="{{ old('title') }}"
                            placeholder="Example: Junior Software Engineer"
                            required
                            class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                        >

                    </div>


                    <div>

                        <label class="font-bold text-slate-700 dark:text-slate-300">
                            Company Name *
                        </label>

                        <input
                            type="text"
                            name="company_name"
                            value="{{ old('company_name') }}"
                            placeholder="Example: Brain Station 23"
                            required
                            class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                        >

                    </div>


                    <div>

                        <label class="font-bold text-slate-700 dark:text-slate-300">
                            Job Type *
                        </label>

                        <select
                            name="type"
                            required
                            class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                        >

                            <option value="">
                                Select Job Type
                            </option>

                            @foreach([
                                'full_time' => 'Full Time',
                                'part_time' => 'Part Time',
                                'internship' => 'Internship',
                                'contract' => 'Contract',
                                'temporary' => 'Temporary',
                                'remote' => 'Remote',
                                'hybrid' => 'Hybrid',
                            ] as $value => $label)

                                <option
                                    value="{{ $value }}"
                                    @selected(old('type') === $value)
                                >
                                    {{ $label }}
                                </option>

                            @endforeach

                        </select>

                    </div>


                    <div>

                        <label class="font-bold text-slate-700 dark:text-slate-300">
                            Location *
                        </label>

                        <input
                            type="text"
                            name="location"
                            value="{{ old('location') }}"
                            placeholder="Example: Dhaka, Bangladesh"
                            required
                            class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                        >

                    </div>


                    <div>

                        <label class="font-bold text-slate-700 dark:text-slate-300">
                            Salary Range
                        </label>

                        <input
                            type="text"
                            name="salary_range"
                            value="{{ old('salary_range') }}"
                            placeholder="Example: BDT 30,000 - 45,000"
                            class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                        >

                    </div>


                    <div>

                        <label class="font-bold text-slate-700 dark:text-slate-300">
                            Vacancy *
                        </label>

                        <input
                            type="number"
                            name="positions_available"
                            value="{{ old('positions_available', 1) }}"
                            min="1"
                            required
                            placeholder="Example: 3"
                            class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                        >

                        <p class="mt-2 text-xs text-slate-500">
                            Number of available positions.
                        </p>

                    </div>


                    <div>

                        <label class="font-bold text-slate-700 dark:text-slate-300">
                            Application Deadline
                        </label>

                        <input
                            type="date"
                            name="deadline"
                            value="{{ old('deadline') }}"
                            min="{{ now()->format('Y-m-d') }}"
                            class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                        >

                    </div>

                </div>

            </div>


            {{-- Contact Information --}}

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl">

                <p class="text-sm uppercase tracking-[0.25em] text-emerald-500 font-black">
                    Application Contact
                </p>

                <h3 class="mt-2 text-3xl font-black text-slate-900 dark:text-white">
                    Contact Information
                </h3>

                <p class="mt-3 text-sm text-slate-500">
                    Provide at least an email address or phone number so interested candidates can contact the employer.
                </p>


                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">


                    <div>

                        <label class="font-bold text-slate-700 dark:text-slate-300">
                            Contact Email
                        </label>

                        <input
                            type="email"
                            name="contact_email"
                            value="{{ old('contact_email') }}"
                            placeholder="Example: hr@company.com"
                            class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                        >

                        @error('contact_email')
                            <p class="mt-2 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <div>

                        <label class="font-bold text-slate-700 dark:text-slate-300">
                            Contact Phone
                        </label>

                        <input
                            type="text"
                            name="contact_phone"
                            value="{{ old('contact_phone') }}"
                            placeholder="+8801XXXXXXXXX"
                            class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                        >

                        @error('contact_phone')
                            <p class="mt-2 text-sm text-red-500">
                                {{ $message }}
                            </p>
                        @enderror

                    </div>


                    <div class="md:col-span-2">

                        <label class="font-bold text-slate-700 dark:text-slate-300">
                            External Application Link
                        </label>

                        <input
                            type="url"
                            name="application_url"
                            value="{{ old('application_url') }}"
                            placeholder="https://company.com/careers/job"
                            class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                        >

                        <p class="mt-2 text-xs text-slate-500">
                            Optional. Use this if candidates should apply through another website.
                        </p>

                    </div>

                </div>

            </div>


            {{-- Description --}}

            <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl">

                <p class="text-sm uppercase tracking-[0.25em] text-purple-500 font-black">
                    Job Information
                </p>

                <h3 class="mt-2 text-3xl font-black text-slate-900 dark:text-white">
                    Description & Requirements
                </h3>


                <div class="mt-8 space-y-6">


                    <div>

                        <label class="font-bold text-slate-700 dark:text-slate-300">
                            Job Description *
                        </label>

                        <textarea
                            name="description"
                            rows="8"
                            required
                            placeholder="Describe the role, responsibilities and opportunity..."
                            class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                        >{{ old('description') }}</textarea>

                    </div>


                    <div>

                        <label class="font-bold text-slate-700 dark:text-slate-300">
                            Requirements *
                        </label>

                        <textarea
                            name="requirements"
                            rows="6"
                            required
                            placeholder="Example: PHP, Laravel, MySQL, communication skills..."
                            class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white"
                        >{{ old('requirements') }}</textarea>

                    </div>

                </div>

            </div>


            {{-- Workflow Message --}}

            @if(in_array(auth()->user()->role, ['admin', 'super_admin'], true))

                <div class="rounded-3xl bg-emerald-500/10 border border-emerald-500/20 p-6">

                    <h4 class="font-black text-emerald-600 dark:text-emerald-300">
                        Admin Publication
                    </h4>

                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                        This job will be published immediately.
                    </p>

                </div>

            @else

                <div class="rounded-3xl bg-amber-500/10 border border-amber-500/20 p-6">

                    <h4 class="font-black text-amber-600 dark:text-amber-300">
                        Approval Required
                    </h4>

                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-300">
                        Your job will remain pending until an Admin or Super Admin approves it.
                    </p>

                </div>

            @endif


            <div class="flex flex-col sm:flex-row gap-4">

                <button
                    type="submit"
                    class="px-8 py-4 rounded-2xl bg-gradient-to-r from-cyan-500 to-fuchsia-500 text-white font-black shadow-2xl hover:scale-[1.02] transition"
                >

                    <i class="fas fa-plus-circle mr-2"></i>

                    @if(in_array(auth()->user()->role, ['admin', 'super_admin'], true))

                        Add & Publish Job

                    @else

                        Submit Job for Approval

                    @endif

                </button>


                <a
                    href="{{ route('jobs.index') }}"
                    class="px-8 py-4 rounded-2xl bg-slate-950 text-white font-black text-center shadow-xl"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</x-app-layout>