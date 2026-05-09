<x-app-layout>
    <div class="min-h-screen text-white">
        <style>
            @keyframes jobFloat {
                0%,100% { transform: translateY(0) scale(1); }
                50% { transform: translateY(-14px) scale(1.03); }
            }

            @keyframes jobShine {
                0% { transform: translateX(-130%); }
                100% { transform: translateX(130%); }
            }

            @keyframes jobGlow {
                0%,100% { box-shadow: 0 22px 70px rgba(99,102,241,.20); }
                50% { box-shadow: 0 28px 95px rgba(236,72,153,.28); }
            }

            .job-orb {
                animation: jobFloat 7s ease-in-out infinite;
            }

            .job-card {
                animation: jobGlow 5s ease-in-out infinite;
            }

            .job-shine::before {
                content: "";
                position: absolute;
                inset: 0;
                background: linear-gradient(90deg, transparent, rgba(255,255,255,.08), transparent);
                transform: translateX(-130%);
                animation: jobShine 7s ease-in-out infinite;
                pointer-events: none;
            }

            .job-input {
                width: 100%;
                border-radius: 1.25rem;
                border: 1px solid rgba(255,255,255,.12);
                background: rgba(2,6,23,.75);
                padding: 1rem 1.1rem;
                color: white;
                font-size: .875rem;
                font-weight: 800;
                outline: none;
                transition: .25s ease;
            }

            .job-input::placeholder {
                color: rgba(148,163,184,.75);
            }

            .job-input:focus {
                border-color: rgba(34,211,238,.80);
                box-shadow: 0 0 0 5px rgba(34,211,238,.12);
                transform: translateY(-2px);
            }

            .job-label {
                display: flex;
                align-items: center;
                gap: .55rem;
                margin-bottom: .65rem;
                color: rgb(226 232 240);
                font-size: .85rem;
                font-weight: 950;
            }
        </style>

        <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-gradient-to-br from-slate-950 via-indigo-950 to-fuchsia-950 p-6 shadow-2xl md:p-8">

            <div class="job-orb absolute -top-24 -right-24 h-80 w-80 rounded-full bg-fuchsia-500/20 blur-3xl"></div>
            <div class="job-orb absolute top-56 -left-28 h-80 w-80 rounded-full bg-cyan-500/20 blur-3xl" style="animation-delay:2s"></div>
            <div class="job-orb absolute -bottom-28 right-1/3 h-80 w-80 rounded-full bg-indigo-500/20 blur-3xl" style="animation-delay:4s"></div>

            <div class="relative z-10 mb-8 flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-cyan-400/20 bg-cyan-400/10 px-4 py-2 text-xs font-black uppercase tracking-[.3em] text-cyan-300">
                        <i class="fas fa-briefcase"></i>
                        Job Portal
                    </div>

                    <h1 class="text-4xl font-black leading-tight md:text-5xl">
                        Create Job Post
                    </h1>

                    <p class="mt-3 max-w-2xl text-sm font-semibold text-slate-400">
                        Post job, internship, or career opportunity for students. Admin approval may be required before publishing.
                    </p>
                </div>

                <a href="{{ route('jobs.index') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/10 bg-white/10 px-5 py-3 text-sm font-black text-white shadow-xl transition hover:scale-105 hover:bg-white/15">
                    <i class="fas fa-arrow-left"></i>
                    Back
                </a>
            </div>

            @if ($errors->any())
                <div class="relative z-10 mb-6 rounded-2xl border border-red-500/30 bg-red-500/10 p-5 text-sm font-bold text-red-200">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                  action="{{ route('jobs.store') }}"
                  enctype="multipart/form-data"
                  class="job-card job-shine relative z-10 mx-auto max-w-6xl overflow-hidden rounded-[2rem] border border-white/10 bg-white/[.06] p-5 shadow-2xl backdrop-blur-2xl md:p-7">
                @csrf

                <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">

                    <div class="xl:col-span-8">
                        <label class="job-label">
                            <i class="fas fa-heading text-cyan-300"></i>
                            Job Title
                        </label>
                        <input type="text"
                               name="title"
                               value="{{ old('title') }}"
                               required
                               placeholder="Example: Developer Intern"
                               class="job-input">
                    </div>

                    <div class="xl:col-span-4">
                        <label class="job-label">
                            <i class="fas fa-building text-purple-300"></i>
                            Company Name
                        </label>
                        <input type="text"
                               name="company"
                               value="{{ old('company') }}"
                               required
                               placeholder="Example: TechNova Ltd."
                               class="job-input">
                    </div>

                    <div class="xl:col-span-4">
                        <label class="job-label">
                            <i class="fas fa-location-dot text-red-300"></i>
                            Location
                        </label>
                        <input type="text"
                               name="location"
                               value="{{ old('location') }}"
                               required
                               placeholder="Dhaka / Remote / Hybrid"
                               class="job-input">
                    </div>

                    <div class="xl:col-span-4">
                        <label class="job-label">
                            <i class="fas fa-layer-group text-emerald-300"></i>
                            Job Type
                        </label>
                        <select name="type" required class="job-input">
                            <option value="">Select Type</option>
                            <option value="Full Time" {{ old('type') === 'Full Time' ? 'selected' : '' }}>Full Time</option>
                            <option value="Part Time" {{ old('type') === 'Part Time' ? 'selected' : '' }}>Part Time</option>
                            <option value="Internship" {{ old('type') === 'Internship' ? 'selected' : '' }}>Internship</option>
                            <option value="Remote" {{ old('type') === 'Remote' ? 'selected' : '' }}>Remote</option>
                            <option value="Contract" {{ old('type') === 'Contract' ? 'selected' : '' }}>Contract</option>
                        </select>
                    </div>

                    <div class="xl:col-span-4">
                        <label class="job-label">
                            <i class="fas fa-bangladeshi-taka-sign text-yellow-300"></i>
                            Salary Range
                        </label>
                        <input type="text"
                               name="salary_range"
                               value="{{ old('salary_range') }}"
                               placeholder="Example: 15,000 - 30,000 BDT"
                               class="job-input">
                    </div>

                    <div class="xl:col-span-6">
                        <label class="job-label">
                            <i class="fas fa-users text-blue-300"></i>
                            Positions Available
                        </label>
                        <input type="number"
                               name="positions_available"
                               value="{{ old('positions_available') }}"
                               min="1"
                               placeholder="Example: 3"
                               class="job-input">
                    </div>

                    <div class="xl:col-span-6">
                        <label class="job-label">
                            <i class="fas fa-calendar-check text-pink-300"></i>
                            Application Deadline
                        </label>
                        <input type="date"
                               name="deadline"
                               value="{{ old('deadline') }}"
                               class="job-input">
                    </div>

                    <div class="xl:col-span-12">
                        <label class="job-label">
                            <i class="fas fa-image text-indigo-300"></i>
                            Job Banner / Company Image
                        </label>

                        <label class="group flex cursor-pointer flex-col items-center justify-center rounded-[1.6rem] border border-dashed border-white/20 bg-slate-950/60 px-6 py-8 text-center transition hover:border-cyan-400/60 hover:bg-cyan-400/5">
                            <div class="mb-3 flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-cyan-500 via-indigo-600 to-pink-500 text-2xl shadow-xl transition group-hover:scale-110">
                                <i class="fas fa-cloud-arrow-up"></i>
                            </div>

                            <p class="text-base font-black text-white">
                                Upload Job Banner Image
                            </p>

                            <p class="mt-1 text-xs font-semibold text-slate-400">
                                JPG, PNG, WEBP allowed. Recommended size 1200×700.
                            </p>

                            <input type="file"
                                   name="image"
                                   accept="image/*"
                                   class="hidden">
                        </label>
                    </div>

                    <div class="xl:col-span-12">
                        <label class="job-label">
                            <i class="fas fa-align-left text-emerald-300"></i>
                            Job Description
                        </label>
                        <textarea name="description"
                                  rows="6"
                                  required
                                  placeholder="Write job responsibilities, work environment, role details..."
                                  class="job-input min-h-[160px]">{{ old('description') }}</textarea>
                    </div>

                    <div class="xl:col-span-6">
                        <label class="job-label">
                            <i class="fas fa-list-check text-cyan-300"></i>
                            Requirements
                        </label>
                        <textarea name="requirements"
                                  rows="5"
                                  placeholder="Skills, education, experience, tools..."
                                  class="job-input min-h-[140px]">{{ old('requirements') }}</textarea>
                    </div>

                    <div class="xl:col-span-6">
                        <label class="job-label">
                            <i class="fas fa-gift text-pink-300"></i>
                            Benefits
                        </label>
                        <textarea name="benefits"
                                  rows="5"
                                  placeholder="Training, certificate, remote work, allowance..."
                                  class="job-input min-h-[140px]">{{ old('benefits') }}</textarea>
                    </div>

                    <div class="xl:col-span-12">
                        <div class="rounded-[1.6rem] border border-cyan-400/20 bg-cyan-500/10 p-5">
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-cyan-400/15 text-cyan-300">
                                    <i class="fas fa-shield-halved"></i>
                                </div>

                                <div>
                                    <h3 class="text-lg font-black text-cyan-300">
                                        Job Approval Flow
                                    </h3>
                                    <p class="mt-1 text-sm font-semibold text-slate-400">
                                        Alumni submitted jobs will be saved as pending. Admin approval may be required before students can apply.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end">
                    <a href="{{ route('jobs.index') }}"
                       class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/10 px-6 py-3 text-sm font-black text-white transition hover:bg-white/15">
                        Cancel
                    </a>

                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-cyan-500 via-indigo-600 to-pink-500 px-8 py-3 text-sm font-black text-white shadow-xl shadow-cyan-500/25 transition hover:scale-105">
                        <i class="fas fa-paper-plane"></i>
                        Submit Job Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>