<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationManualPayment;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DonationManualPaymentController extends Controller
{
    public function store(
        Request $request,
        Donation $donation
    ) {
        $validated = $request->validate([
            'payment_method' => [
                'required',
                'string',
                'max:100',
            ],

            'account_number' => [
                'required',
                'string',
                'max:100',
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
        | Store Screenshot
        |--------------------------------------------------------------------------
        */

        $screenshotPath = null;


        if (
            $request->hasFile(
                'screenshot'
            )
        ) {
            $screenshotPath =
                $request
                    ->file('screenshot')
                    ->store(
                        'donation-payments',
                        'public'
                    );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Manual Payment
        |--------------------------------------------------------------------------
        */

        $payment = DB::transaction(
            function () use (
                $validated,
                $donation,
                $screenshotPath,
                $user
            ) {
                $payment =
                    DonationManualPayment::create([
                        'donation_id' =>
                            $donation->id,

                        'user_id' =>
                            $user->id,

                        'payment_method' =>
                            $validated[
                                'payment_method'
                            ],

                        'account_number' =>
                            $validated[
                                'account_number'
                            ],

                        'transaction_id' =>
                            $validated[
                                'transaction_id'
                            ],

                        'amount' =>
                            $validated[
                                'amount'
                            ],

                        'note' =>
                            $validated[
                                'note'
                            ] ?? null,

                        'screenshot' =>
                            $screenshotPath,

                        'status' =>
                            'approved',
                    ]);


                $donation->increment(
                    'collected_amount',
                    $payment->amount
                );


                return $payment;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Donor Personal Notification
        |--------------------------------------------------------------------------
        */

        NotificationService::personal(
            $user,
            'donation_payment_confirmed',
            'Donation Payment Confirmed',
            'Your donation payment of ' .
                number_format(
                    (float) $payment->amount,
                    2
                ) .
                ' has been recorded successfully.',
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
        | Admin + Super Admin Notification
        |--------------------------------------------------------------------------
        */

        NotificationService::management(
            'donation_payment',
            'New Donation Payment',
            $user->name .
                ' made a donation payment of ' .
                number_format(
                    (float) $payment->amount,
                    2
                ) .
                ' using ' .
                strtoupper(
                    $payment->payment_method
                ) .
                '.',
            route(
                'donations.show',
                $donation
            ),
            'medium',
            $payment,
            $user
        );


        return back()->with(
            'success',
            'Thank you! Your donation has been recorded successfully.'
        );
    }
}