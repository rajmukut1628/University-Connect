<?php

namespace App\Http\Controllers;

use App\Services\ProfileStrengthService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();

        $profileScore = $this->calculateProfileScore($user);
        $profileSuggestions = $this->generateProfileSuggestions($user, $profileScore);
        $profileStrength = app(ProfileStrengthService::class)->analyze($user);

        return view('profile.edit', [
            'user' => $user,
            'profileScore' => $profileScore,
            'profileSuggestions' => $profileSuggestions,
            'profileStrength' => $profileStrength,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'department' => ['nullable', 'string', 'max:255'],
            'batch' => ['nullable', 'string', 'max:100'],
            'skills' => ['nullable', 'string', 'max:1000'],
            'bio' => ['nullable', 'string', 'max:2000'],
            'address' => ['nullable', 'string', 'max:500'],
            'profile_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],

            // Social / Professional Links
            'github_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'portfolio_url' => ['nullable', 'url', 'max:255'],
            'current_company' => ['nullable', 'string', 'max:255'],
            'current_designation' => ['nullable', 'string', 'max:255'],
            'current_job_type' => ['nullable', 'string', 'max:100'],
            'work_experience_years' => ['nullable', 'string', 'max:100'],
            'previous_company' => ['nullable', 'string', 'max:255'],
            'previous_designation' => ['nullable', 'string', 'max:255'],
            'previous_job_details' => ['nullable', 'string', 'max:2000'],
        ]);

        unset($validated['profile_image']);

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $validated['profile_image'] = $request->file('profile_image')
                ->store('profile-images', 'public');
        }

        $user->fill($validated);
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
            Storage::disk('public')->delete($user->profile_image);
        }

        auth()->guard()->logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    private function calculateProfileScore($user): int
    {
        $fields = [
            'name',
            'email',
            'phone',
            'department',
            'batch',
            'skills',
            'bio',
            'address',
            'profile_image',
            'github_url',
            'linkedin_url',
            'portfolio_url',
            'current_company',
            'current_designation',
            'work_experience_years',
            'previous_company',
            'previous_job_details',
        ];

        $completed = 0;

        foreach ($fields as $field) {
            if (!empty($user->$field)) {
                $completed++;
            }
        }

        return (int) round(($completed / count($fields)) * 100);
    }

    private function generateProfileSuggestions($user, int $profileScore): array
    {
        $suggestions = [];

        if ($profileScore < 60) {
            $suggestions[] = 'Complete your profile information to improve visibility.';
        }

        if (empty($user->profile_image)) {
            $suggestions[] = 'Upload a professional profile photo.';
        }

        if (empty($user->phone)) {
            $suggestions[] = 'Add your phone number for better communication.';
        }

        if (empty($user->department)) {
            $suggestions[] = 'Add your department information.';
        }

        if (empty($user->batch)) {
            $suggestions[] = 'Add your batch or passing year.';
        }

        if (empty($user->skills)) {
            $suggestions[] = 'Add your skills like Laravel, PHP, JavaScript, Python, React, Communication.';
        }

        if (empty($user->github_url)) {
            $suggestions[] = 'Add your GitHub profile to showcase projects and code.';
        }

        if (empty($user->linkedin_url)) {
            $suggestions[] = 'Add your LinkedIn profile for professional networking.';
        }

        if (empty($user->portfolio_url)) {
            $suggestions[] = 'Add your portfolio or website link to improve your professional profile.';
        }

        if (empty($user->bio)) {
            $suggestions[] = 'Write a short bio about your academic or professional goal.';
        }

        if (empty($suggestions)) {
            $suggestions[] = 'Your profile looks strong. Keep it updated regularly.';
        }
        if (empty($user->current_company)) {
    $suggestions[] = 'Add your current company or workplace.';
}

if (empty($user->current_designation)) {
    $suggestions[] = 'Add your current designation or job role.';
}

if (empty($user->work_experience_years)) {
    $suggestions[] = 'Add your total work experience.';
}

if (empty($user->previous_job_details)) {
    $suggestions[] = 'Add previous job or experience details.';
}

        return $suggestions;
    }
}