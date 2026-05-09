<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationManualPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DonationManualPaymentController extends Controller
{
    public function store(Request $request, Donation $donation)
    {
        $request->validate([
            'payment_method' => ['required', 'string', 'max:100'],
            'account_number' => ['required', 'string', 'max:100'],
            'transaction_id' => ['required', 'string', 'max:150', 'unique:donation_manual_payments,transaction_id'],
            'amount' => ['required', 'numeric', 'min:1'],
            'note' => ['nullable', 'string', 'max:1000'],
            'screenshot' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        $screenshotPath = null;

        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')->store('donation-payments', 'public');
        }

        DB::transaction(function () use ($request, $donation, $screenshotPath) {
            $payment = DonationManualPayment::create([
                'donation_id' => $donation->id,
                'user_id' => Auth::id(),
                'payment_method' => $request->payment_method,
                'account_number' => $request->account_number,
                'transaction_id' => $request->transaction_id,
                'amount' => $request->amount,
                'note' => $request->note,
                'screenshot' => $screenshotPath,
                'status' => 'approved',
            ]);

            $donation->increment('collected_amount', $payment->amount);
        });

        return back()->with('success', 'Thank you! Your donation has been recorded successfully.');
    }
}