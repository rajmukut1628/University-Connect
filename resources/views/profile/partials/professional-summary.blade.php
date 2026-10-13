<div class="space-y-5">
    <h3 class="text-2xl font-black text-slate-900 dark:text-white">Professional Summary</h3>
    <p class="text-sm text-slate-500">Enter your own information or leave fields empty. These details appear on your public profile. Changes to your work history below also update this summary.</p>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        @foreach(['current_company'=>'Current Organization','current_designation'=>'Current Position','current_job_type'=>'Employment Type','work_experience_years'=>'Total Experience','previous_company'=>'Previous Organization','previous_designation'=>'Previous Position'] as $field=>$label)
            <div>
                <label for="{{ $field }}" class="font-bold text-slate-900 dark:text-white">{{ $label }}</label>
                <input id="{{ $field }}" name="{{ $field }}" type="text" maxlength="{{ $field === 'work_experience_years' ? 100 : 255 }}"
                    value="{{ old($field, $user->$field) }}" class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">
                @error($field)<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
            </div>
        @endforeach
    </div>
    <div>
        <label for="previous_job_details" class="font-bold text-slate-900 dark:text-white">Previous Job Details</label>
        <textarea id="previous_job_details" name="previous_job_details" rows="4" maxlength="3000" class="mt-2 w-full rounded-2xl border-slate-300 dark:border-white/10 dark:bg-slate-950 dark:text-white">{{ old('previous_job_details', $user->previous_job_details) }}</textarea>
        @error('previous_job_details')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
    </div>
</div>
