<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Member;
use App\Models\Membership;
use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;

class StripePaymentService
{
    public function __construct()
    {
        $secret = Setting::get('stripe_secret', config('services.stripe.secret'));
        if ($secret) {
            Stripe::setApiKey($secret);
        }
    }

    /**
     * Créer un client Stripe pour un membre
     */
    public function createCustomer(Member $member): ?string
    {
        if (!config('services.stripe.secret')) {
            return null;
        }

        try {
            $customer = Customer::create([
                'email' => $member->email,
                'name' => $member->full_name,
                'metadata' => [
                    'member_id' => $member->id,
                    'member_number' => $member->member_number,
                ],
            ]);

            return $customer->id;
        } catch (ApiErrorException $e) {
            Log::error('Erreur lors de la création du client Stripe', [
                'member_id' => $member->id,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Créer une intention de paiement
     */
    public function createPaymentIntent(Member $member, float $amount, string $type, array $metadata = []): ?PaymentIntent
    {
        if (!config('services.stripe.secret')) {
            return null;
        }

        try {
            $customerId = $this->createCustomer($member);

            $paymentIntent = PaymentIntent::create([
                'amount' => (int)($amount * 100), // Stripe utilise les centimes
                'currency' => 'cad',
                'customer' => $customerId,
                'metadata' => array_merge([
                    'member_id' => $member->id,
                    'member_number' => $member->member_number,
                    'type' => $type,
                ], $metadata),
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

            return $paymentIntent;
        } catch (ApiErrorException $e) {
            Log::error('Erreur lors de la création de l\'intention de paiement Stripe', [
                'member_id' => $member->id,
                'amount' => $amount,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Confirmer un paiement
     */
    public function confirmPayment(Payment $payment): bool
    {
        if (!config('services.stripe.secret') || !$payment->stripe_payment_intent_id) {
            return false;
        }

        try {
            $paymentIntent = PaymentIntent::retrieve($payment->stripe_payment_intent_id);
            
            if ($paymentIntent->status === 'succeeded') {
                $payment->update([
                    'status' => 'completed',
                    'stripe_charge_id' => $paymentIntent->latest_charge,
                    'paid_at' => now(),
                ]);

                // Mettre à jour l'adhésion si nécessaire
                $this->updateMembershipAfterPayment($payment);

                return true;
            }

            return false;
        } catch (ApiErrorException $e) {
            Log::error('Erreur lors de la confirmation du paiement Stripe', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Rembourser un paiement
     */
    public function refundPayment(Payment $payment, ?float $amount = null): bool
    {
        if (!config('services.stripe.secret') || !$payment->stripe_charge_id) {
            return false;
        }

        try {
            $refundAmount = $amount ? (int)($amount * 100) : null;
            
            $refund = \Stripe\Refund::create([
                'charge' => $payment->stripe_charge_id,
                'amount' => $refundAmount,
                'metadata' => [
                    'payment_id' => $payment->id,
                    'member_id' => $payment->member_id,
                ],
            ]);

            // Mettre à jour le statut du paiement
            $payment->update([
                'status' => 'refunded',
                'refunded_at' => now(),
                'refund_amount' => $refundAmount ? $refundAmount / 100 : $payment->amount,
            ]);

            return true;
        } catch (ApiErrorException $e) {
            Log::error('Erreur lors du remboursement Stripe', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Mettre à jour l'adhésion après paiement
     */
    private function updateMembershipAfterPayment(Payment $payment): void
    {
        if ($payment->type === 'adhesion' && $payment->membership) {
            $membership = $payment->membership;
            $membership->update([
                'adhesion_fee_paid' => $membership->adhesion_fee_paid + $payment->amount,
                'last_payment_date' => now(),
                'status' => 'active',
            ]);
        } elseif ($payment->type === 'contribution') {
            $member = $payment->member;
            $membership = $member->activeMembership;
            
            if ($membership) {
                $membership->update([
                    'total_contributions_paid' => $membership->total_contributions_paid + $payment->amount,
                    'last_payment_date' => now(),
                ]);
            }
        }
    }

    /**
     * Obtenir le client secret pour le frontend
     */
    public function getClientSecret(string $paymentIntentId): ?string
    {
        if (!config('services.stripe.secret')) {
            return null;
        }

        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            return $paymentIntent->client_secret;
        } catch (ApiErrorException $e) {
            Log::error('Erreur lors de la récupération du client secret', [
                'payment_intent_id' => $paymentIntentId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
