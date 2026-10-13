<?php

namespace App\Services;

use App\Models\{Donation, DonationContribution, User};
use Illuminate\Support\Facades\DB;

class DonationCardPaymentService
{
    public function record($session, ?int $expectedDonation = null, ?int $expectedUser = null): bool
    {
        $donationId = (int) ($session->metadata->donation_id ?? 0);
        $userId = (int) ($session->metadata->user_id ?? 0);
        abort_unless($session->payment_status === 'paid' && $session->mode === 'payment'
            && $donationId > 0 && $userId > 0 && $session->amount_total > 0
            && strtolower($session->currency) === strtolower(config('services.stripe.currency', 'bdt'))
            && (!$expectedDonation || $donationId === $expectedDonation)
            && (!$expectedUser || $userId === $expectedUser), 422, 'Payment does not match this donation.');
        return DB::transaction(function () use ($session, $donationId, $userId) {
            $donation = Donation::whereKey($donationId)->lockForUpdate()->firstOrFail();
            if (DB::table('donation_card_payments')->where('session_id', $session->id)->exists()) return false;
            $user = User::findOrFail($userId);
            $amount = $session->amount_total / 100;
            DB::table('donation_card_payments')->insert(['session_id' => $session->id, 'donation_id' => $donationId, 'user_id' => $userId, 'amount' => $amount, 'created_at' => now(), 'updated_at' => now()]);
            DonationContribution::create(['donation_id' => $donationId, 'user_id' => $userId, 'amount' => $amount,
                'donor_name' => $user->name, 'donor_email' => $user->email, 'payment_method' => 'other', 'message' => 'Verified VISA/Card payment', 'status' => 'confirmed', 'is_anonymous' => false]);
            $donation->increment('collected_amount', $amount);
            return true;
        });
    }
}
