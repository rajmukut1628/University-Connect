<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use App\Services\ProfileStrengthService;

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

        auth()->logout();

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

        if (empty($user->bio)) {
            $suggestions[] = 'Write a short bio about your academic or professional goal.';
        }

        if (empty($suggestions)) {
            $suggestions[] = 'Your profile looks strong. Keep it updated regularly.';
        }

        return $suggestions;
    }
}