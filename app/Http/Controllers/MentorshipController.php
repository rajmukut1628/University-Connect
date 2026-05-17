<?php

namespace App\Http\Controllers;

use App\Models\Mentorship;
use App\Models\User;
use Illuminate\Http\Request;
use App\Services\GeminiMentorMatchService;

class MentorshipController extends Controller
{
    public function index(Request $request)
{
    $query = User::where('role', 'alumni')
        ->where('is_active', true)
        ->where('is_blocked', false);

    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('department', 'like', "%{$search}%")
              ->orWhere('skills', 'like', "%{$search}%")
              ->orWhere('current_company', 'like', "%{$search}%")
              ->orWhere('current_designation', 'like', "%{$search}%")
              ->orWhere('bio', 'like', "%{$search}%");
        });
    }

    if ($request->filled('department')) {
        $query->where('department', $request->department);
    }

    $mentors = $query->latest()->get();

    $myRequests = Mentorship::where('student_id', auth()->id())
        ->pluck('status', 'mentor_id');

    return view('mentors.index', compact('mentors', 'myRequests'));
}

    public function requestMentor(Request $request, User $mentor)
    {
        abort_unless(auth()->user()->isStudent(), 403, 'Only students can request mentorship.');
        abort_unless($mentor->isAlumni(), 404);

        $request->validate([
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        Mentorship::updateOrCreate(
            [
                'student_id' => auth()->id(),
                'mentor_id' => $mentor->id,
            ],
            [
                'title' => 'Career Mentorship Request',
                'description' => $request->description ?: 'I would like to request mentorship for career guidance.',
                'status' => 'pending',
                'rejection_reason' => null,
                'started_at' => null,
            ]
        );

        return back()->with('success', 'Mentorship request sent successfully.');
    }

    public function cancelRequest(User $mentor)
    {
        abort_unless(auth()->user()->isStudent(), 403, 'Only students can cancel mentorship requests.');

        $request = Mentorship::where('student_id', auth()->id())
            ->where('mentor_id', $mentor->id)
            ->where('status', 'pending')
            ->first();

        if (!$request) {
            return back()->with('error', 'No pending mentorship request found.');
        }

        $request->delete();

        return back()->with('success', 'Mentorship request cancelled successfully.');
    }

    public function myRequests()
    {
        abort_unless(auth()->user()->isAlumni(), 403);

        $requests = Mentorship::with(['student', 'mentor'])
            ->where('mentor_id', auth()->id())
            ->latest()
            ->get();

        return view('mentors.requests', compact('requests'));
    }

    public function accept(Mentorship $mentorship)
    {
        abort_unless($mentorship->mentor_id === auth()->id(), 403);

        $mentorship->update([
            'status' => 'accepted',
            'started_at' => now(),
            'rejection_reason' => null,
        ]);

        return back()->with('success', 'Mentorship request accepted.');
    }

    public function reject(Mentorship $mentorship)
    {
        abort_unless($mentorship->mentor_id === auth()->id(), 403);

        $mentorship->update([
            'status' => 'rejected',
            'rejection_reason' => 'Rejected by mentor.',
        ]);

        return back()->with('success', 'Mentorship request rejected.');
    }
    public function aiMatch(User $mentor, GeminiMentorMatchService $matcher)
{
    abort_unless(auth()->user()->isStudent(), 403, 'Only students can use AI Match.');
    abort_unless($mentor->isAlumni(), 404);

    $result = $matcher->analyze(auth()->user(), $mentor);

    return back()->with([
        'ai_match_result' => $result,
        'ai_match_mentor_id' => $mentor->id,
    ]);
}
}