<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationManualPayment;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DonationManualPaymentController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Submit bKash / Nagad Payment
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request,
        Donation $donation
    ) {
        abort_unless(
            $donation->status === 'approved',
            403
        );

        $validated = $request->validate([
            'payment_method' => [
                'required',
                Rule::in([
                    'bKash',
                    'Nagad',
                ]),
            ],

            'account_number' => [
                'required',
                'string',
                'max:30',
            ],

            'transaction_id' => [
                'required',
                'string',
                'max:150',
                'unique:donation_manual_payments,transaction_id',
            ],

            'amount' => [
                'required',
                'numeric',
                'min:1',
            ],

            'note' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'screenshot' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:4096',
            ],
        ]);


        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | Screenshot
        |--------------------------------------------------------------------------
        */

        $screenshotPath = null;

        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request
                ->file('screenshot')
                ->store(
                    'donation-payments',
                    'public'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Pending Payment
        |--------------------------------------------------------------------------
        */

        $payment = DonationManualPayment::create([
            'donation_id' => $donation->id,

            'user_id' => $user->id,

            'payment_method' =>
                $validated['payment_method'],

            'account_number' =>
                $validated['account_number'],

            'transaction_id' =>
                $validated['transaction_id'],

            'amount' =>
                $validated['amount'],

            'note' =>
                $validated['note'] ?? null,

            'screenshot' =>
                $screenshotPath,

            'status' =>
                'pending',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Donor Notification
        |--------------------------------------------------------------------------
        */

        NotificationService::personal(
            $user,
            'donation_payment_submitted',
            'Donation Payment Submitted',
            'Your ' .
                $payment->payment_method .
                ' payment of ৳' .
                number_format(
                    (float) $payment->amount,
                    2
                ) .
                ' has been submitted and is waiting for verification.',
            route(
                'donations.show',
                $donation
            ),
            'medium',
            $payment,
            $user
        );


        /*
        |--------------------------------------------------------------------------
        | Management Notification
        |--------------------------------------------------------------------------
        */

        NotificationService::management(
            'donation_payment_pending',
            'Donation Payment Verification',
            $user->name .
                ' submitted a ' .
                $payment->payment_method .
                ' donation payment of ৳' .
                number_format(
                    (float) $payment->amount,
                    2
                ) .
                '. Transaction ID: ' .
                $payment->transaction_id,
            route(
                'donation-payments.pending'
            ),
            'high',
            $payment,
            $user
        );


        return back()->with(
            'success',
            'Payment submitted successfully. It will be added to the campaign after Admin verification.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Pending Payments
    |--------------------------------------------------------------------------
    */

    public function pending()
    {
        $this->ensureManagement();

        $payments = DonationManualPayment::with([
                'user',
                'donation',
            ])
            ->where('status', 'pending')
            ->oldest()
            ->paginate(20);

        return view(
            'donations.pending-payments',
            compact('payments')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Approve
    |--------------------------------------------------------------------------
    */

    public function approve(
        DonationManualPayment $payment
    ) {
        $this->ensureManagement();

        if ($payment->status !== 'pending') {
            return back()->with(
                'error',
                'This payment has already been processed.'
            );
        }


        DB::transaction(function () use ($payment) {

            /*
            | Lock payment so double-click / simultaneous requests
            | cannot add the amount twice.
            */

            $lockedPayment =
                DonationManualPayment::query()
                    ->whereKey($payment->id)
                    ->lockForUpdate()
                    ->firstOrFail();


            if ($lockedPayment->status !== 'pending') {
                return;
            }


            $donation =
                Donation::query()
                    ->whereKey($lockedPayment->donation_id)
                    ->lockForUpdate()
                    ->firstOrFail();


            $lockedPayment->update([
                'status' => 'approved',

                'reviewed_by' =>
                    Auth::id(),

                'reviewed_at' =>
                    now(),

                'rejection_reason' =>
                    null,
            ]);


            $donation->increment(
                'collected_amount',
                (float) $lockedPayment->amount
            );
        });


        $payment->refresh();


        if ($payment->user) {
            NotificationService::personal(
                $payment->user,
                'donation_payment_confirmed',
                'Donation Payment Confirmed',
                'Your ' .
                    $payment->payment_method .
                    ' donation payment of ৳' .
                    number_format(
                        (float) $payment->amount,
                        2
                    ) .
                    ' has been verified successfully.',
                route(
                    'donations.show',
                    $payment->donation_id
                ),
                'medium',
                $payment,
                Auth::user()
            );
        }


        return back()->with(
            'success',
            'Payment approved and donation amount added successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Reject
    |--------------------------------------------------------------------------
    */

    public function reject(
        Request $request,
        DonationManualPayment $payment
    ) {
        $this->ensureManagement();


        if ($payment->status !== 'pending') {
            return back()->with(
                'error',
                'This payment has already been processed.'
            );
        }


        $validated = $request->validate([
            'rejection_reason' => [
                'required',
                'string',
                'max:1000',
            ],
        ]);


        $payment->update([
            'status' =>
                'rejected',

            'reviewed_by' =>
                Auth::id(),

            'reviewed_at' =>
                now(),

            'rejection_reason' =>
                $validated['rejection_reason'],
        ]);


        if ($payment->user) {
            NotificationService::personal(
                $payment->user,
                'donation_payment_rejected',
                'Donation Payment Rejected',
                'Your donation payment could not be verified. Reason: ' .
                    $validated['rejection_reason'],
                route(
                    'donations.show',
                    $payment->donation_id
                ),
                'high',
                $payment,
                Auth::user()
            );
        }


        return back()->with(
            'success',
            'Payment rejected successfully.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Admin / Super Admin Authorization
    |--------------------------------------------------------------------------
    */

    private function ensureManagement(): void
    {
        abort_unless(
            Auth::check() &&
            in_array(
                Auth::user()->role,
                [
                    'admin',
                    'super_admin',
                ],
                true
            ),
            403
        );
    }
}