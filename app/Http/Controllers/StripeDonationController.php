<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeDonationController extends Controller
{
    public function checkout(Request $request, Donation $donation)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
        ]);

        Stripe::setApiKey(config('services.stripe.secret'));

        $amount = round($request->amount, 2);
        $currency = config('services.stripe.currency', 'usd');

        $session = Session::create([
            'payment_method_types' => ['card'],
            'mode' => 'payment',

            'line_items' => [[
                'price_data' => [
                    'currency' => $currency,
                    'product_data' => [
                        'name' => $donation->title,
                        'description' => 'Donation for University Connect campaign',
                    ],
                    'unit_amount' => (int) ($amount * 100),
                ],
                'quantity' => 1,
            ]],

            'metadata' => [
                'donation_id' => $donation->id,
                'user_id' => auth()->id(),
                'amount' => $amount,
            ],

            'success_url' => route('donations.stripe.success', $donation) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('donations.stripe.cancel', $donation),
        ]);

        return redirect($session->url);
    }

    public function success(Request $request, Donation $donation)
    {
        $sessionId = $request->query('session_id');

        if (!$sessionId) {
            return redirect()
                ->route('donations.show', $donation)
                ->with('error', 'Stripe session not found.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = Session::retrieve($sessionId);

        if ($session->payment_status !== 'paid') {
            return redirect()
                ->route('donations.show', $donation)
                ->with('error', 'Payment was not completed.');
        }

        $amount = ((float) $session->amount_total) / 100;

        DB::transaction(function () use ($donation, $amount) {
            $donation->increment('collected_amount', $amount);
        });

        return redirect()
            ->route('donations.show', $donation)
            ->with('success', 'Stripe payment successful! Donation amount added.');
    }

    public function cancel(Donation $donation)
    {
        return redirect()
            ->route('donations.show', $donation)
            ->with('error', 'Stripe payment cancelled.');
    }
}