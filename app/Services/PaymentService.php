<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Member;
use App\Models\Membership;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

class PaymentService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Créer un paiement d'adhésion
     */
    public function createAdhesionPayment(Member $member, Membership $membership, float $amount): Payment
    {
        try {
            // Créer l'intention de paiement Stripe
            $paymentIntent = PaymentIntent::create([
                'amount' => (int)($amount * 100), // Stripe utilise les centimes
                'currency' => 'cad',
                'metadata' => [
                    'member_id' => $member->id,
                    'membership_id' => $membership->id,
                    'type' => 'adhesion',
                ],
            ]);

            // Créer le paiement en base
            $payment = Payment::create([
                'member_id' => $member->id,
                'membership_id' => $membership->id,
                'type' => 'adhesion',
                'amount' => $amount,
                'currency' => 'CAD',
                'status' => 'pending',
                'payment_method' => 'stripe',
                'stripe_payment_intent_id' => $paymentIntent->id,
                'description' => 'Paiement d\'adhésion - ' . $member->full_name,
            ]);

            return $payment;

        } catch (ApiErrorException $e) {
            Log::error('Erreur Stripe lors de la création du paiement', [
                'member_id' => $member->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Créer un paiement de contribution
     */
    public function createContributionPayment(Member $member, float $amount, string $description = null): Payment
    {
        try {
            // Créer l'intention de paiement Stripe
            $paymentIntent = PaymentIntent::create([
                'amount' => (int)($amount * 100),
                'currency' => 'cad',
                'metadata' => [
                    'member_id' => $member->id,
                    'type' => 'contribution',
                ],
            ]);

            // Créer le paiement en base
            $payment = Payment::create([
                'member_id' => $member->id,
                'membership_id' => $member->activeMembership?->id,
                'type' => 'contribution',
                'amount' => $amount,
                'currency' => 'CAD',
                'status' => 'pending',
                'payment_method' => 'stripe',
                'stripe_payment_intent_id' => $paymentIntent->id,
                'description' => $description ?? 'Contribution - ' . $member->full_name,
            ]);

            return $payment;

        } catch (ApiErrorException $e) {
            Log::error('Erreur Stripe lors de la création du paiement de contribution', [
                'member_id' => $member->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Confirmer un paiement
     */
    public function confirmPayment(Payment $payment, string $stripeChargeId = null): bool
    {
        try {
            // Récupérer l'intention de paiement Stripe
            $paymentIntent = PaymentIntent::retrieve($payment->stripe_payment_intent_id);
            
            if ($paymentIntent->status === 'succeeded') {
                // Mettre à jour le paiement
                $payment->update([
                    'status' => 'completed',
                    'stripe_charge_id' => $stripeChargeId ?? $paymentIntent->latest_charge,
                    'paid_at' => now(),
                ]);

                // Mettre à jour l'adhésion si c'est un paiement d'adhésion
                if ($payment->type === 'adhesion' && $payment->membership) {
                    $this->updateMembershipAfterPayment($payment->membership, $payment->amount);
                }

                // Mettre à jour les contributions si c'est un paiement de contribution
                if ($payment->type === 'contribution') {
                    $this->updateContributionsAfterPayment($payment);
                }

                return true;
            }

            return false;

        } catch (ApiErrorException $e) {
            Log::error('Erreur Stripe lors de la confirmation du paiement', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Mettre à jour l'adhésion après un paiement
     */
    private function updateMembershipAfterPayment(Membership $membership, float $amount): void
    {
        $membership->update([
            'adhesion_fee_paid' => $membership->adhesion_fee_paid + $amount,
        ]);
        
        // Recalculer le montant dû
        $membership->updateStatus();
    }

    /**
     * Mettre à jour les contributions après un paiement
     */
    private function updateContributionsAfterPayment(Payment $payment): void
    {
        // Trouver les contributions en attente pour ce membre
        $pendingContributions = $payment->member->contributions()
            ->where('status', 'pending')
            ->orderBy('due_date', 'asc')
            ->get();

        $remainingAmount = $payment->amount;

        foreach ($pendingContributions as $contribution) {
            if ($remainingAmount <= 0) break;

            $amountToPay = min($remainingAmount, $contribution->amount);
            
            $contribution->update([
                'status' => 'paid',
                'paid_date' => now(),
                'payment_id' => $payment->id,
            ]);

            $remainingAmount -= $amountToPay;
        }

        // Mettre à jour l'adhésion
        if ($payment->membership) {
            $payment->membership->update([
                'total_contributions_paid' => $payment->membership->total_contributions_paid + ($payment->amount - $remainingAmount),
            ]);
            $payment->membership->updateStatus();
        }
    }

    /**
     * Rembourser un paiement
     */
    public function refundPayment(Payment $payment, float $amount = null): bool
    {
        try {
            if ($payment->stripe_charge_id) {
                $refund = \Stripe\Refund::create([
                    'charge' => $payment->stripe_charge_id,
                    'amount' => $amount ? (int)($amount * 100) : null,
                ]);

                // Créer un nouveau paiement de remboursement
                Payment::create([
                    'member_id' => $payment->member_id,
                    'membership_id' => $payment->membership_id,
                    'type' => 'refund',
                    'amount' => $amount ?? $payment->amount,
                    'currency' => $payment->currency,
                    'status' => 'completed',
                    'payment_method' => 'stripe',
                    'stripe_charge_id' => $refund->id,
                    'description' => 'Remboursement - ' . $payment->description,
                    'paid_at' => now(),
                ]);

                return true;
            }

            return false;

        } catch (ApiErrorException $e) {
            Log::error('Erreur Stripe lors du remboursement', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Obtenir les détails d'un paiement Stripe
     */
    public function getPaymentDetails(string $paymentIntentId): ?array
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
            
            return [
                'id' => $paymentIntent->id,
                'status' => $paymentIntent->status,
                'amount' => $paymentIntent->amount / 100,
                'currency' => $paymentIntent->currency,
                'created' => $paymentIntent->created,
                'metadata' => $paymentIntent->metadata->toArray(),
            ];

        } catch (ApiErrorException $e) {
            Log::error('Erreur Stripe lors de la récupération des détails', [
                'payment_intent_id' => $paymentIntentId,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
