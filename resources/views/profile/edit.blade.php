<x-app-layout>
    <x-slot name="header">
        @php
            $role = auth()->user()->role;
            $isAdmin = in_array($role, ['admin', 'super_admin']);
            $isStudentOrAlumni = in_array($role, ['student', 'alumni']);
        @endphp

        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-950 to-purple-950 p-8 shadow-2xl border border-white/10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(99,102,241,.35),transparent_35%),radial-gradient(circle_at_bottom_right,rgba(168,85,247,.30),transparent_35%)]"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                <div>
                    <p class="text-sm uppercase tracking-[0.35em] text-indigo-300 font-bold">
                        Ultra Premium Profile
                    </p>

                    <h2 class="mt-3 text-4xl lg:text-5xl font-black text-white">
                        {{ $user->name }}
                    </h2>

                    <p class="mt-3 text-slate-300 max-w-2xl">
                        @if($isAdmin)
                            Manage your administrator profile and platform access information.
                        @elseif($role === 'alumni')
                            Build your professional alumni profile for mentoring, networking and opportunity sharing.
                        @else
                            Build a complete academic and career profile to unlock better job, mentorship and networking opportunities.
                        @endif
                    </p>
                </div>

                <div class="rounded-3xl bg-white/10 backdrop-blur-xl border border-white/10 px-6 py-5 text-center">
                    <p class="text-xs text-slate-300">Profile Status</p>

                    <p class="text-4xl font-black text-emerald-300">
                        {{ $profileScore ?? 0 }}%
                    </p>

                    <p class="text-xs text-slate-400 mt-1">
                        @if($isAdmin)
                            Administrator Profile
                        @elseif($role === 'alumni')
                            Professional Impact Profile
                        @else
                            Student Career Profile
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">

        @if (session('status') === 'profile-updated')
            <div class="rounded-2xl bg-emerald-500/15 border border-emerald-500/30 p-4 text-emerald-600 font-bold">
                Profile updated successfully.
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">

            {{-- Left Sidebar --}}
            <div class="space-y-8">

                {{-- Profile Card --}}
                <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-7 shadow-2xl">
                    <div class="text-center">
                        @if($user->profile_image)
                            <img src="{{ asset('storage/' . $user->profile_image) }}"
                                 class="mx-auto h-28 w-28 rounded-3xl object-cover border-4 border-white shadow-2xl">
                        @else
                            <div class="mx-auto h-28 w-28 rounded-3xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-4xl font-black shadow-2xl">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif

                        <h3 class="mt-5 text-2xl font-black text-slate-900 dark:text-white">
                            {{ $user->name }}
                        </h3>

                        <p class="text-slate-500 mt-1">
                            {{ ucwords(str_replace('_', ' ', $user->role)) }}
                        </p>

                        <div class="mt-5 h-3 w-full rounded-full bg-slate-200 dark:bg-slate-800 overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-cyan-500"
                                 style="width: {{ $profileScore ?? 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Suggestions --}}
                @if(!$isAdmin)
                    <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-7 shadow-2xl">
                        <p class="text-sm uppercase tracking-[0.25em] text-cyan-500 font-black">
                            AI Suggestions
                        </p>

                        <h3 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">
                            Profile Improvement
                        </h3>

                        <div class="mt-5 space-y-3">
                            @forelse($profileSuggestions ?? [] as $suggestion)
                                <div class="rounded-2xl bg-cyan-500/10 border border-cyan-500/20 p-4">
                                    <p class="text-sm text-slate-600 dark:text-slate-300">
                                        <i class="fas fa-wand-magic-sparkles text-cyan-500 mr-2"></i>
                                        {{ $suggestion }}
                                    </p>
                                </div>
                            @empty
                                <p class="text-sm text-slate-500">
                                    No suggestions available.
                                </p>
                            @endforelse
                        </div>
                    </div>
                @endif

                {{-- Role Card --}}
                <div class="rounded-3xl bg-gradient-to-br from-slate-950 via-indigo-950 to-purple-950 p-7 shadow-2xl text-white">
                    <p class="text-sm uppercase tracking-[0.25em] text-purple-300 font-black">
                        Role Profile
                    </p>

                    <h3 class="mt-2 text-2xl font-black">
                        @if($role === 'super_admin')
                            Super Administrator
                        @elseif($role === 'admin')
                            Administrator
                        @elseif($role === 'alumni')
                            Alumni Professional
                        @else
                            Student Career Builder
                        @endif
                    </h3>

                    <p class="mt-3 text-slate-300 text-sm leading-relaxed">
                        @if($role === 'super_admin')
                            Create admins, manage users, verify accounts, moderate posts and control the full platform.
                        @elseif($role === 'admin')
                            Manage users, verify accounts, moderate content and support the university community.
                        @elseif($role === 'alumni')
                            Share your professional experience, post opportunities and guide students through mentorship.
                        @else
                            Complete your academic profile, skills and career goal to get better job and mentorship recommendations.
                        @endif
                    </p>
                </div>

            </div>

            {{-- Main Profile Form --}}
            <div class="xl:col-span-2 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl">
                <div class="mb-8">
                    <p class="text-sm uppercase tracking-[0.25em] text-indigo-500 font-black">
                        Edit Information
                    </p>

                    <h3 class="mt-2 text-3xl font-black text-slate-900 dark:text-white">
                        @if($isAdmin)
                            Administrator Details
                        @else
                            Personal & Career Details
                        @endif
                    </h3>

                    <p class="mt-2 text-slate-500">
                        @if($isAdmin)
                            Update your admin profile information.
                        @else
                            Update your profile information for better networking and AI recommendations.
                        @endif
                    </p>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    {{-- Common Fields --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="font-bold text-slate-700 dark:text-slate-300">Full Name</label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name', $user->name) }}"
                                   required
                                   class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="font-bold text-slate-700 dark:text-slate-300">Email</label>
                            <input type="email"
                                   value="{{ $user->email }}"
                                   disabled
                                   class="mt-2 w-full rounded-2xl border-slate-300 bg-slate-100 dark:border-white/10 dark:bg-slate-800 dark:text-slate-400">
                        </div>

                        <div>
                            <label class="font-bold text-slate-700 dark:text-slate-300">Phone</label>
                            <input type="text"
                                   name="phone"
                                   value="{{ old('phone', $user->phone) }}"
                                   placeholder="Example: +8801XXXXXXXXX"
                                   class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="font-bold text-slate-700 dark:text-slate-300">Address</label>
                            <input type="text"
                                   name="address"
                                   value="{{ old('address', $user->address) }}"
                                   placeholder="Example: Dhaka, Bangladesh"
                                   class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                            @error('address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Images --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="font-bold text-slate-700 dark:text-slate-300">Profile Image</label>
                            <input type="file"
                                   name="profile_image"
                                   accept="image/*"
                                   class="mt-2 w-full rounded-2xl border border-slate-300 dark:border-white/10 p-3 dark:bg-slate-950 dark:text-white">
                            @error('profile_image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="font-bold text-slate-700 dark:text-slate-300">Cover Image</label>
                            <input type="file"
                                   name="cover_image"
                                   accept="image/*"
                                   class="mt-2 w-full rounded-2xl border border-slate-300 dark:border-white/10 p-3 dark:bg-slate-950 dark:text-white">
                            @error('cover_image')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Student / Alumni Fields --}}
                    @if($isStudentOrAlumni)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">Department</label>
                                <input type="text"
                                       name="department"
                                       value="{{ old('department', $user->department) }}"
                                       placeholder="Example: CSE"
                                       class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                                @error('department')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="font-bold text-slate-700 dark:text-slate-300">
                                    {{ $role === 'alumni' ? 'Passing Year / Batch' : 'Batch' }}
                                </label>
                                <input type="text"
                                       name="batch"
                                       value="{{ old('batch', $user->batch) }}"
                                       placeholder="Example: 2025 / 56th Batch"
                                       class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                                @error('batch')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="font-bold text-slate-700 dark:text-slate-300">Skills</label>
                            <input type="text"
                                   name="skills"
                                   value="{{ old('skills', $user->skills) }}"
                                   placeholder="Example: Laravel, PHP, JavaScript, MySQL, Communication"
                                   class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                            <p class="text-xs text-slate-500 mt-2">Separate skills with comma.</p>
                            @error('skills')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="font-bold text-slate-700 dark:text-slate-300">
                                {{ $role === 'alumni' ? 'Professional Bio' : 'Career Goal / Bio' }}
                            </label>
                            <textarea name="bio"
                                      rows="5"
                                      placeholder="{{ $role === 'alumni' ? 'Write about your professional experience and mentorship interest...' : 'Write about your academic goal and career plan...' }}"
                                      class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">{{ old('bio', $user->bio) }}</textarea>
                            @error('bio')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($role === 'alumni')
                            <div class="rounded-3xl bg-purple-500/10 border border-purple-500/20 p-6">
                                <h4 class="text-xl font-black text-purple-600">
                                    Alumni Professional Profile
                                </h4>
                                <p class="text-sm text-slate-500 mt-2">
                                    Add professional experience, company name and expertise to help students find better mentors.
                                </p>
                            </div>
                        @else
                            <div class="rounded-3xl bg-blue-500/10 border border-blue-500/20 p-6">
                                <h4 class="text-xl font-black text-blue-600">
                                    Student Career Profile
                                </h4>
                                <p class="text-sm text-slate-500 mt-2">
                                    Add skills, department, batch and career goal to get better AI job and mentorship recommendations.
                                </p>
                            </div>
                        @endif
                    @endif

                    {{-- Admin / Super Admin Fields --}}
                    @if($isAdmin)
                        @if($role === 'super_admin')
                            <div class="rounded-3xl bg-gradient-to-r from-emerald-500/10 to-cyan-500/10 border border-emerald-500/20 p-6 shadow-xl">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div>
                                        <p class="text-sm uppercase tracking-[0.25em] text-emerald-500 font-black">
                                            Super Admin Control
                                        </p>

                                        <h4 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">
                                            Add Another Admin
                                        </h4>

                                        <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 leading-relaxed">
                                            Create and manage additional administrator accounts.
                                        </p>
                                    </div>

                                    <a href="{{ route('superadmin.admins.create') }}"
                                       class="inline-flex items-center justify-center px-6 py-3 rounded-2xl bg-gradient-to-r from-emerald-500 to-cyan-600 text-white font-black shadow-xl hover:scale-105 transition duration-300">
                                        <i class="fas fa-user-shield mr-2"></i>
                                        Add Another Admin
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="rounded-3xl bg-gradient-to-r from-amber-500/10 to-orange-500/10 border border-amber-500/20 p-6 shadow-xl">
                            <p class="text-sm uppercase tracking-[0.25em] text-amber-500 font-black">
                                Administrator Profile
                            </p>

                            <h4 class="mt-2 text-2xl font-black text-slate-900 dark:text-white">
                                University Management Console
                            </h4>

                            <p class="mt-3 text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                                Manage users, verify accounts, moderate posts, publish announcements,
                                monitor donations, oversee alumni mentoring and control the university ecosystem.
                            </p>
                        </div>
                    @endif

                    {{-- Buttons --}}
                    <div class="flex flex-col md:flex-row gap-4 pt-4">
                        <button type="submit"
                                class="px-8 py-4 rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-black shadow-xl hover:scale-105 transition">
                            <i class="fas fa-save mr-2"></i>
                            Save Profile
                        </button>

                        <a href="{{ route('dashboard') }}"
                           class="px-8 py-4 rounded-2xl bg-slate-950 text-white font-black shadow-xl hover:scale-105 transition text-center">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>