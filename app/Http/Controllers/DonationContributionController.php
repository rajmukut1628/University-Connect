<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationContribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonationContributionController extends Controller
{
    public function store(Request $request, Donation $donation)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'message' => ['nullable', 'string', 'max:1000'],
            'payment_method' => ['required', 'string', 'in:cash,bkash,nagad,rocket,bank,other'],
            'is_anonymous' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($validated, $donation) {
            $user = auth()->user();

            DonationContribution::create([
                'donation_id' => $donation->id,
                'user_id' => $user->id,
                'amount' => $validated['amount'],
                'donor_name' => $user->name,
                'donor_email' => $user->email,
                'message' => $validated['message'] ?? null,
                'payment_method' => $validated['payment_method'],
                'is_anonymous' => request()->boolean('is_anonymous'),
                'status' => 'confirmed',
            ]);

            $donation->increment('collected_amount', $validated['amount']);
        });

        return redirect()
            ->route('donations.show', $donation)
            ->with('success', 'Thank you! Your contribution has been added successfully.');
    }
}