<x-app-layout>
    <x-slot name="header">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-950 via-indigo-950 to-cyan-950 p-8 shadow-2xl border border-white/10">
            <div class="relative z-10">
                <p class="text-sm uppercase tracking-[0.35em] text-cyan-300 font-black">
                    Student Lifecycle
                </p>
                <h2 class="mt-3 text-4xl font-black text-white">
                    Apply for Alumni Status
                </h2>
                <p class="mt-3 text-slate-300">
                    Submit your graduation information. Admin will review and convert your account to alumni.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        @if(session('error'))
            <div class="mb-6 rounded-2xl bg-red-500/15 border border-red-500/30 p-4 text-red-600 font-bold">
                {{ session('error') }}
            </div>
        @endif

        @if($existingRequest)
            <div class="rounded-3xl bg-amber-500/10 border border-amber-500/30 p-8 shadow-2xl">
                <h3 class="text-2xl font-black text-amber-500">
                    Request Already Pending
                </h3>
                <p class="mt-3 text-slate-600 dark:text-slate-300">
                    You already submitted an alumni conversion request. Please wait for admin approval.
                </p>
            </div>
        @else
            <form method="POST"
                  action="{{ route('alumni-conversion.store') }}"
                  enctype="multipart/form-data"
                  class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-8 shadow-2xl space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-black text-slate-700 dark:text-slate-200 mb-2">
                        Graduation Year
                    </label>
                    <input type="text"
                           name="graduation_year"
                           value="{{ old('graduation_year') }}"
                           placeholder="2026"
                           class="w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                    <x-input-error :messages="$errors->get('graduation_year')" class="mt-2 text-red-500" />
                </div>

                <div>
                    <label class="block text-sm font-black text-slate-700 dark:text-slate-200 mb-2">
                        Current Company <span class="text-slate-400">(Optional)</span>
                    </label>
                    <input type="text"
                           name="current_company"
                           value="{{ old('current_company') }}"
                           placeholder="Company name"
                           class="w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                    <x-input-error :messages="$errors->get('current_company')" class="mt-2 text-red-500" />
                </div>

                <div>
                    <label class="block text-sm font-black text-slate-700 dark:text-slate-200 mb-2">
                        Designation <span class="text-slate-400">(Optional)</span>
                    </label>
                    <input type="text"
                           name="designation"
                           value="{{ old('designation') }}"
                           placeholder="Software Engineer"
                           class="w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                    <x-input-error :messages="$errors->get('designation')" class="mt-2 text-red-500" />
                </div>

                <div>
                    <label class="block text-sm font-black text-slate-700 dark:text-slate-200 mb-2">
                        Supporting Document <span class="text-slate-400">(Optional)</span>
                    </label>
                    <input type="file"
                           name="supporting_document"
                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                           class="w-full rounded-2xl border border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white p-3">
                    <x-input-error :messages="$errors->get('supporting_document')" class="mt-2 text-red-500" />
                </div>

                <div>
                    <label class="block text-sm font-black text-slate-700 dark:text-slate-200 mb-2">
                        Student Note <span class="text-slate-400">(Optional)</span>
                    </label>
                    <textarea name="student_note"
                              rows="5"
                              placeholder="Write a short note for admin..."
                              class="w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">{{ old('student_note') }}</textarea>
                    <x-input-error :messages="$errors->get('student_note')" class="mt-2 text-red-500" />
                </div>

                <button type="submit"
                        class="w-full rounded-2xl bg-gradient-to-r from-cyan-600 to-indigo-600 py-4 text-white font-black shadow-xl hover:scale-[1.02] transition">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Submit Alumni Conversion Request
                </button>
            </form>
        @endif
    </div>
</x-app-layout>