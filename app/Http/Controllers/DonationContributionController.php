<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationContribution;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DonationContributionController extends Controller
{
    public function store(
        Request $request,
        Donation $donation
    ) {
        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:1',
            ],

            'message' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'payment_method' => [
                'required',
                'string',
                'in:cash,bkash,nagad,rocket,bank,other',
            ],

            'is_anonymous' => [
                'nullable',
                'boolean',
            ],
        ]);


        $user = auth()->user();


        /*
        |--------------------------------------------------------------------------
        | Create Contribution
        |--------------------------------------------------------------------------
        */

        $contribution = DB::transaction(
            function () use (
                $validated,
                $donation,
                $user,
                $request
            ) {
                $contribution =
                    DonationContribution::create([
                        'donation_id' =>
                            $donation->id,

                        'user_id' =>
                            $user->id,

                        'amount' =>
                            $validated['amount'],

                        'donor_name' =>
                            $user->name,

                        'donor_email' =>
                            $user->email,

                        'message' =>
                            $validated['message']
                            ?? null,

                        'payment_method' =>
                            $validated[
                                'payment_method'
                            ],

                        'is_anonymous' =>
                            $request->boolean(
                                'is_anonymous'
                            ),

                        'status' =>
                            'confirmed',
                    ]);


                $donation->increment(
                    'collected_amount',
                    $validated['amount']
                );


                return $contribution;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | Donor Personal Notification
        |--------------------------------------------------------------------------
        */

        NotificationService::personal(
            $user,
            'donation_contribution_confirmed',
            'Donation Confirmed',
            'Your contribution of ' .
                number_format(
                    (float) $validated['amount'],
                    2
                ) .
                ' has been added successfully.',
            route(
                'donations.show',
                $donation
            ),
            'medium',
            $contribution,
            $user
        );


        /*
        |--------------------------------------------------------------------------
        | Admin + Super Admin Notification
        |--------------------------------------------------------------------------
        */

        NotificationService::management(
            'donation_contribution',
            'New Donation Contribution',
            $user->name .
                ' contributed ' .
                number_format(
                    (float) $validated['amount'],
                    2
                ) .
                ' using ' .
                strtoupper(
                    $validated[
                        'payment_method'
                    ]
                ) .
                '.',
            route(
                'donations.show',
                $donation
            ),
            'medium',
            $contribution,
            $user
        );


        return redirect()
            ->route(
                'donations.show',
                $donation
            )
            ->with(
                'success',
                'Thank you! Your contribution has been added successfully.'
            );
    }
}