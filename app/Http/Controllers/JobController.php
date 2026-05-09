<?php

namespace App\Http\Controllers;

use App\Models\JobApplication;
use App\Models\JobPosting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $query = JobPosting::with('postedBy')
            ->when($request->user()->role === 'student', function ($q) {
                $q->where('status', 'approved');
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;

                $q->where(function ($inner) use ($search) {
                    $inner->where('title', 'like', "%{$search}%")
                        ->orWhere('company_name', 'like', "%{$search}%")
                        ->orWhere('location', 'like', "%{$search}%")
                        ->orWhere('type', 'like', "%{$search}%")
                        ->orWhere('requirements', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('type'), function ($q) use ($request) {
                $q->where('type', $request->type);
            })
            ->latest();

        $jobs = $query->paginate(9)->withQueryString();

        $stats = [
            'total_jobs' => JobPosting::count(),
            'approved_jobs' => JobPosting::where('status', 'approved')->count(),
            'pending_jobs' => JobPosting::where('status', 'pending')->count(),
            'applications' => Schema::hasTable('job_applications')
                ? DB::table('job_applications')->count()
                : 0,
        ];

        return view('jobs.index', compact('jobs', 'stats'));
    }

    public function create()
    {
        if (!auth()->user()->isAlumni() && !auth()->user()->isAdmin()) {
            abort(403, 'Only alumni or admin can post jobs.');
        }

        return view('jobs.create');
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isAlumni() && !auth()->user()->isAdmin()) {
            abort(403, 'Only alumni or admin can post jobs.');
        }

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'location' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:full_time,part_time,internship,remote,hybrid'],
            'experience_level' => ['nullable', 'string', 'max:255'],
            'salary_range' => ['nullable', 'string', 'max:255'],
            'positions_available' => ['required', 'integer', 'min:1'],
            'deadline' => ['nullable', 'date'],
            'description' => ['required', 'string'],
            'requirements' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
        ]);

        $validated['posted_by'] = auth()->id();
        $validated['status'] = auth()->user()->isAdmin() ? 'approved' : 'pending';

        JobPosting::create($validated);

        return redirect()
            ->route('jobs.index')
            ->with('success', 'Job posted successfully. Waiting for admin approval.');
    }

    public function show(JobPosting $job)
    {
        if (auth()->user()->isStudent() && $job->status !== 'approved') {
            abort(403, 'This job is not approved yet.');
        }

        $job->load(['postedBy', 'applications']);

        $alreadyApplied = false;

        if (Schema::hasTable('job_applications')) {
            $alreadyApplied = JobApplication::where('job_id', $job->id)
                ->where('applicant_id', auth()->id())
                ->exists();
        }

        return view('jobs.show', compact('job', 'alreadyApplied'));
    }

    public function apply(Request $request, JobPosting $job)
    {
        if (!auth()->user()->isStudent()) {
            abort(403, 'Only students can apply for jobs.');
        }

        if ($job->status !== 'approved') {
            return back()->withErrors([
                'job' => 'This job is not approved yet.',
            ]);
        }

        if (!Schema::hasTable('job_applications')) {
            return back()->withErrors([
                'job' => 'Job applications table is missing.',
            ]);
        }

        $request->validate([
            'cover_letter' => ['nullable', 'string', 'max:3000'],
        ]);

        JobApplication::firstOrCreate(
            [
                'job_id' => $job->id,
                'applicant_id' => auth()->id(),
            ],
            [
                'cover_letter' => $request->cover_letter,
                'status' => 'pending',
            ]
        );

        return back()->with('success', 'Application submitted successfully.');
    }

    public function approve(JobPosting $job)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only admin can approve jobs.');
        }

        $job->update([
            'status' => 'approved',
        ]);

        return back()->with('success', 'Job approved successfully.');
    }

    public function reject(JobPosting $job)
    {
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Only admin can reject jobs.');
        }

        $job->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Job rejected successfully.');
    }

    public function myJobs()
    {
        if (!auth()->user()->isAlumni() && !auth()->user()->isAdmin()) {
            abort(403, 'Only alumni or admin can access posted jobs.');
        }

        $jobs = JobPosting::where('posted_by', auth()->id())
            ->latest()
            ->paginate(10);

        return view('jobs.my-jobs', compact('jobs'));
    }
}