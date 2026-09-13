<?php

namespace App\Http\Controllers;

use App\Models\AlumniWorkExperience;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlumniWorkExperienceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Store Experience
    |--------------------------------------------------------------------------
    */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        abort_unless(
            $user->isAlumni(),
            403
        );

        $validated = $request->validate([
            'company_name' => [
                'required',
                'string',
                'max:255',
            ],

            'designation' => [
                'required',
                'string',
                'max:255',
            ],

            'employment_type' => [
                'nullable',
                'string',
                'in:Full-time,Part-time,Contract,Internship,Freelance,Self-employed,Volunteer,Other',
            ],

            'work_mode' => [
                'nullable',
                'string',
                'in:On-site,Hybrid,Remote',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'is_current' => [
                'nullable',
                'boolean',
            ],

            'description' => [
                'nullable',
                'string',
                'max:3000',
            ],
        ]);

        $isCurrent = $request->boolean(
            'is_current'
        );

        /*
        |--------------------------------------------------------------------------
        | Current job does not need end date
        |--------------------------------------------------------------------------
        */
        if ($isCurrent) {
            $validated['end_date'] = null;
        }

        $validated['is_current'] = $isCurrent;

        DB::transaction(function () use (
            $user,
            $validated,
            $isCurrent
        ) {
            /*
            |--------------------------------------------------------------------------
            | Only one current primary experience
            |--------------------------------------------------------------------------
            */
            if ($isCurrent) {
                AlumniWorkExperience::where(
                    'user_id',
                    $user->id
                )
                    ->where(
                        'is_current',
                        true
                    )
                    ->update([
                        'is_current' => false,
                    ]);
            }

            $user
                ->workExperiences()
                ->create($validated);

            $this->syncLegacyJobFields(
                $user
            );
        });

        return redirect()
            ->route('profile.edit')
            ->with(
                'status',
                'experience-added'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Update Experience
    |--------------------------------------------------------------------------
    */
    public function update(
        Request $request,
        AlumniWorkExperience $experience
    ): RedirectResponse {
        $user = $request->user();

        abort_unless(
            $user->isAlumni(),
            403
        );

        /*
        |--------------------------------------------------------------------------
        | Ownership Protection
        |--------------------------------------------------------------------------
        */
        abort_unless(
            $experience->user_id === $user->id,
            403
        );

        $validated = $request->validate([
            'company_name' => [
                'required',
                'string',
                'max:255',
            ],

            'designation' => [
                'required',
                'string',
                'max:255',
            ],

            'employment_type' => [
                'nullable',
                'string',
                'in:Full-time,Part-time,Contract,Internship,Freelance,Self-employed,Volunteer,Other',
            ],

            'work_mode' => [
                'nullable',
                'string',
                'in:On-site,Hybrid,Remote',
            ],

            'location' => [
                'nullable',
                'string',
                'max:255',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'is_current' => [
                'nullable',
                'boolean',
            ],

            'description' => [
                'nullable',
                'string',
                'max:3000',
            ],
        ]);

        $isCurrent = $request->boolean(
            'is_current'
        );

        if ($isCurrent) {
            $validated['end_date'] = null;
        }

        $validated['is_current'] = $isCurrent;

        DB::transaction(function () use (
            $user,
            $experience,
            $validated,
            $isCurrent
        ) {
            /*
            |--------------------------------------------------------------------------
            | Make all other experiences previous if this becomes current
            |--------------------------------------------------------------------------
            */
            if ($isCurrent) {
                AlumniWorkExperience::where(
                    'user_id',
                    $user->id
                )
                    ->where(
                        'id',
                        '!=',
                        $experience->id
                    )
                    ->where(
                        'is_current',
                        true
                    )
                    ->update([
                        'is_current' => false,
                    ]);
            }

            $experience->update(
                $validated
            );

            $this->syncLegacyJobFields(
                $user
            );
        });

        return redirect()
            ->route('profile.edit')
            ->with(
                'status',
                'experience-updated'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Experience
    |--------------------------------------------------------------------------
    */
    public function destroy(
        Request $request,
        AlumniWorkExperience $experience
    ): RedirectResponse {
        $user = $request->user();

        abort_unless(
            $user->isAlumni(),
            403
        );

        abort_unless(
            $experience->user_id === $user->id,
            403
        );

        DB::transaction(function () use (
            $user,
            $experience
        ) {
            $experience->delete();

            $this->syncLegacyJobFields(
                $user
            );
        });

        return redirect()
            ->route('profile.edit')
            ->with(
                'status',
                'experience-deleted'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Sync Existing User Job Fields
    |--------------------------------------------------------------------------
    |
    | Your project already uses:
    |
    | current_company
    | current_designation
    | current_job_type
    | work_experience_years
    | previous_company
    | previous_designation
    | previous_job_details
    |
    | We keep them synced so old pages/features do not break.
    |
    */
    private function syncLegacyJobFields(
        User $user
    ): void {
        $experiences = AlumniWorkExperience::where(
            'user_id',
            $user->id
        )
            ->orderByDesc('is_current')
            ->orderByDesc('start_date')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Current Experience
        |--------------------------------------------------------------------------
        */
        $currentExperience = $experiences
            ->firstWhere(
                'is_current',
                true
            );

        if ($currentExperience) {
            $user->current_company =
                $currentExperience->company_name;

            $user->current_designation =
                $currentExperience->designation;

            $user->current_job_type =
                $currentExperience->employment_type;
        } else {
            $user->current_company = null;
            $user->current_designation = null;
            $user->current_job_type = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Most Recent Previous Experience
        |--------------------------------------------------------------------------
        */
        $previousExperience = $experiences
            ->where(
                'is_current',
                false
            )
            ->sortByDesc(
                'end_date'
            )
            ->first();

        if ($previousExperience) {
            $user->previous_company =
                $previousExperience->company_name;

            $user->previous_designation =
                $previousExperience->designation;

            $user->previous_job_details =
                $previousExperience->description;
        } else {
            $user->previous_company = null;
            $user->previous_designation = null;
            $user->previous_job_details = null;
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Total Experience
        |--------------------------------------------------------------------------
        */
        $totalMonths = 0;

        foreach ($experiences as $experience) {
            if (!$experience->start_date) {
                continue;
            }

            $start = Carbon::parse(
                $experience->start_date
            )->startOfMonth();

            $end = $experience->is_current
                ? now()->startOfMonth()
                : (
                    $experience->end_date
                        ? Carbon::parse(
                            $experience->end_date
                        )->startOfMonth()
                        : now()->startOfMonth()
                );

            if ($end->lt($start)) {
                continue;
            }

            $totalMonths +=
                $start->diffInMonths($end) + 1;
        }

        $user->work_experience_years =
            $this->formatExperienceDuration(
                $totalMonths
            );

        $user->save();
    }

    /*
    |--------------------------------------------------------------------------
    | Format Experience
    |--------------------------------------------------------------------------
    */
    private function formatExperienceDuration(
        int $totalMonths
    ): ?string {
        if ($totalMonths <= 0) {
            return null;
        }

        $years = intdiv(
            $totalMonths,
            12
        );

        $months = $totalMonths % 12;

        $parts = [];

        if ($years > 0) {
            $parts[] =
                $years
                . ' '
                . ($years === 1
                    ? 'year'
                    : 'years');
        }

        if ($months > 0) {
            $parts[] =
                $months
                . ' '
                . ($months === 1
                    ? 'month'
                    : 'months');
        }

        return implode(
            ' ',
            $parts
        );
    }
}