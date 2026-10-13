<?php

namespace App\Http\Controllers;

use App\Services\DonationCardPaymentService;
use Illuminate\Http\Request;
use Stripe\Webhook;

class DonationWebhookController extends Controller
{
    public function __invoke(Request $request, DonationCardPaymentService $payments)
    {
        $secret = config('services.stripe.webhook_secret');
        abort_unless($secret, 503, 'Payment webhook is not configured.');
        try {
            $event = Webhook::constructEvent($request->getContent(), $request->header('Stripe-Signature', ''), $secret);
        } catch (\UnexpectedValueException|\Stripe\Exception\SignatureVerificationException $error) {
            abort(400, 'Invalid payment signature.');
        }
        if (in_array($event->type, ['checkout.session.completed', 'checkout.session.async_payment_succeeded'], true)
            && $event->data->object->payment_status === 'paid') {
            $payments->record($event->data->object);
        }
        return response()->json(['received' => true]);
    }
}
