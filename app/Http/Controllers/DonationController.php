<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class DonationController extends Controller
{
    public function index()
    {
        $donations = Schema::hasTable('donations')
            ? Donation::with(['user'])
                ->latest()
                ->get()
            : collect();

        return view('donations.index', compact('donations'));
    }

    public function create()
    {
        return view('donations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['nullable', 'string', 'max:100'],
            'target_amount' => ['required', 'numeric', 'min:1'],
            'description' => ['nullable', 'string', 'max:3000'],
            'deadline' => ['nullable', 'date'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('donation-images', 'public');
        }

        $validated['user_id'] = auth()->id();
        $validated['collected_amount'] = 0;
        $validated['status'] = auth()->user()->isAdmin() ? 'approved' : 'pending';

        Donation::create($validated);

        return redirect()
            ->route('donations.index')
            ->with('success', 'Donation campaign submitted successfully.');
    }

    public function show(Donation $donation)
    {
        $donation->load([
            'user',
            'contributions.user',
        ]);

        return view('donations.show', compact('donation'));
    }

    public function approve(Donation $donation)
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $donation->update(['status' => 'approved']);

        return back()->with('success', 'Donation campaign approved successfully.');
    }

    public function reject(Donation $donation)
    {
        abort_unless(auth()->user()->isAdmin(), 403);

        $donation->update(['status' => 'rejected']);

        return back()->with('success', 'Donation campaign rejected successfully.');
    }

    public function destroy(Donation $donation)
    {
        abort_unless(
            auth()->check() &&
            (auth()->user()->isAdmin() || $donation->user_id === auth()->id()),
            403
        );

        if ($donation->image && Storage::disk('public')->exists($donation->image)) {
            Storage::disk('public')->delete($donation->image);
        }

        $donation->delete();

        return redirect()
            ->route('donations.index')
            ->with('success', 'Donation campaign deleted successfully.');
    }
}