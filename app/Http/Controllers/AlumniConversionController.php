<?php

namespace App\Http\Controllers;

use App\Models\AlumniConversionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AlumniConversionController extends Controller
{
    public function create()
    {
        $user = auth()->user();

        if ($user->role !== 'student') {
            abort(403, 'Only students can apply for alumni conversion.');
        }

        $existingRequest = AlumniConversionRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        return view('alumni-conversion.create', compact('existingRequest'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'student') {
            abort(403, 'Only students can apply for alumni conversion.');
        }

        $alreadyPending = AlumniConversionRequest::where('user_id', $user->id)
            ->where('status', 'pending')
            ->exists();

        if ($alreadyPending) {
            return back()->with('error', 'You already have a pending alumni conversion request.');
        }

        $validated = $request->validate([
            'graduation_year' => ['required', 'digits:4'],
            'current_company' => ['nullable', 'string', 'max:255'],
            'designation' => ['nullable', 'string', 'max:255'],
            'student_note' => ['nullable', 'string', 'max:2000'],
            'supporting_document' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png,doc,docx', 'max:5120'],
        ]);

        $documentPath = null;

        if ($request->hasFile('supporting_document')) {
            $documentPath = $request->file('supporting_document')
                ->store('alumni-conversion-documents', 'public');
        }

        AlumniConversionRequest::create([
            'user_id' => $user->id,
            'student_id' => $user->student_id,
            'graduation_year' => $validated['graduation_year'],
            'current_company' => $validated['current_company'] ?? null,
            'designation' => $validated['designation'] ?? null,
            'student_note' => $validated['student_note'] ?? null,
            'supporting_document' => $documentPath,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Your alumni conversion request has been submitted successfully.');
    }

    public function index()
    {
        if (!in_array(auth()->user()->role, ['admin', 'super_admin'])) {
            abort(403);
        }

        $requests = AlumniConversionRequest::with(['user', 'approvedBy'])
            ->latest()
            ->paginate(15);

        return view('alumni-conversion.index', compact('requests'));
    }

    public function approve(Request $request, AlumniConversionRequest $conversionRequest)
    {
        if (!in_array(auth()->user()->role, ['admin', 'super_admin'])) {
            abort(403);
        }

        if ($conversionRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $user = $conversionRequest->user;

        if (!$user || $user->role !== 'student') {
            return back()->with('error', 'Only active student accounts can be converted to alumni.');
        }

        $alumniId = $this->generateAlumniId();

        $user->update([
            'role' => 'alumni',
            'alumni_id' => $alumniId,
            'alumni_since' => now(),
            'converted_from_student_at' => now(),
            'converted_by' => auth()->id(),
        ]);

        $conversionRequest->update([
            'status' => 'approved',
            'admin_notes' => $request->admin_notes,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Student account converted to alumni successfully.');
    }

    public function reject(Request $request, AlumniConversionRequest $conversionRequest)
    {
        if (!in_array(auth()->user()->role, ['admin', 'super_admin'])) {
            abort(403);
        }

        if ($conversionRequest->status !== 'pending') {
            return back()->with('error', 'This request has already been processed.');
        }

        $request->validate([
            'admin_notes' => ['required', 'string', 'max:2000'],
        ]);

        $conversionRequest->update([
            'status' => 'rejected',
            'admin_notes' => $request->admin_notes,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'Alumni conversion request rejected.');
    }

    private function generateAlumniId(): string
    {
        $year = now()->format('Y');

        $count = AlumniConversionRequest::where('status', 'approved')
            ->whereYear('approved_at', $year)
            ->count() + 1;

        return 'ALU' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}