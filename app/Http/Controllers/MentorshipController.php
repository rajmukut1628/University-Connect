<?php

namespace App\Http\Controllers;

use App\Models\Mentorship;
use App\Models\User;
use Illuminate\Http\Request;

class MentorshipController extends Controller
{
    public function index()
    {
        $mentors = User::where('role', 'alumni')
            ->where('is_active', true)
            ->where('is_blocked', false)
            ->latest()
            ->get();

        $myRequests = Mentorship::where('student_id', auth()->id())
            ->pluck('status', 'mentor_id');

        return view('mentors.index', compact('mentors', 'myRequests'));
    }

    public function requestMentor(Request $request, User $mentor)
    {
        if (!auth()->user()->isStudent()) {
            abort(403, 'Only students can request mentorship.');
        }

        if (!$mentor->isAlumni()) {
            abort(404);
        }

        Mentorship::firstOrCreate(
            [
                'student_id' => auth()->id(),
                'mentor_id' => $mentor->id,
            ],
            [
                'title' => 'Career Mentorship Request',
                'description' => $request->description ?? 'I would like to request mentorship for career guidance.',
                'status' => 'pending',
            ]
        );

        return back()->with('success', 'Mentorship request sent successfully.');
    }

    public function myRequests()
    {
        if (!auth()->user()->isAlumni()) {
            abort(403);
        }

        $requests = Mentorship::with(['student', 'mentor'])
            ->where('mentor_id', auth()->id())
            ->latest()
            ->get();

        return view('mentors.requests', compact('requests'));
    }

    public function accept(Mentorship $mentorship)
    {
        if ($mentorship->mentor_id !== auth()->id()) {
            abort(403);
        }

        $mentorship->update([
            'status' => 'accepted',
            'started_at' => now(),
        ]);

        return back()->with('success', 'Mentorship request accepted.');
    }

    public function reject(Mentorship $mentorship)
    {
        if ($mentorship->mentor_id !== auth()->id()) {
            abort(403);
        }

        $mentorship->update([
            'status' => 'rejected',
            'rejection_reason' => 'Rejected by mentor.',
        ]);

        return back()->with('success', 'Mentorship request rejected.');
    }
}