<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Member;
use App\Models\Membership;
use App\Notifications\BankTransferInstructionsNotification;
use App\Notifications\InteracInstructionsNotification;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    public function __construct()
    {
        // No external payment gateway initialization needed
    }

    /**
     * Créer un paiement d'adhésion
     */
    public function createAdhesionPayment(Member $member, Membership $membership, float $amount, string $paymentMethod = 'interac'): Payment
    {
        $bankReference = null;
        $interacReference = null;
        
        if ($paymentMethod === 'bank_transfer') {
            $bankReference = 'BT' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } elseif ($paymentMethod === 'interac') {
            $interacReference = 'INT' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        }

        // Créer le paiement en base
        $payment = Payment::create([
            'member_id' => $member->id,
            'membership_id' => $membership->id,
            'type' => 'adhesion',
            'amount' => $amount,
            'penalty_amount' => 0,
            'total_amount' => $amount, // Initialiser total_amount avec le montant de base
            'currency' => 'CAD',
            'status' => 'pending',
            'payment_method' => $paymentMethod,
            'bank_reference' => $bankReference,
            'interac_reference' => $interacReference,
            'description' => 'Paiement d\'adhésion - ' . $member->first_name . ' ' . $member->last_name,
        ]);

        // Envoyer les instructions de paiement
        if ($paymentMethod === 'bank_transfer') {
            $member->notify(new BankTransferInstructionsNotification($payment));
        } elseif ($paymentMethod === 'interac') {
            $member->notify(new InteracInstructionsNotification($payment));
        }

        return $payment;
    }


    /**
     * Créer un paiement de contribution
     */
    public function createContributionPayment(Member $member, float $amount, ?string $description = null, string $paymentMethod = 'interac'): Payment
    {
        $bankReference = null;
        $interacReference = null;
        
        if ($paymentMethod === 'bank_transfer') {
            $bankReference = 'BT' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        } elseif ($paymentMethod === 'interac') {
            $interacReference = 'INT' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        }

        // Créer le paiement en base
        $payment = Payment::create([
            'member_id' => $member->id,
            'membership_id' => $member->activeMembership?->id,
            'type' => 'contribution',
            'amount' => $amount,
            'penalty_amount' => 0,
            'total_amount' => $amount, // Initialiser total_amount avec le montant de base
            'currency' => 'CAD',
            'status' => 'pending',
            'payment_method' => $paymentMethod,
            'bank_reference' => $bankReference,
            'interac_reference' => $interacReference,
            'description' => $description ?? 'Contribution - ' . $member->full_name,
        ]);

        // Envoyer les instructions de paiement
        if ($paymentMethod === 'bank_transfer') {
            $member->notify(new BankTransferInstructionsNotification($payment));
        } elseif ($paymentMethod === 'interac') {
            $member->notify(new InteracInstructionsNotification($payment));
        }

        return $payment;
    }

    /**
     * Confirmer un paiement Interac
     */
    public function confirmInteracPayment(Payment $payment, ?string $interacReference = null): bool
    {
        try {
            $payment->update([
                'status' => 'completed',
                'paid_at' => now(),
                'interac_reference' => $interacReference ?? $payment->interac_reference,
                'confirmed_by' => auth()->id(),
            ]);

            // Mettre à jour l'adhésion
            if ($payment->type === 'adhesion' && $payment->membership) {
                $this->updateMembershipAfterPayment($payment->membership, $payment->amount);
            } elseif ($payment->type === 'contribution') {
                $this->updateContributionsAfterPayment($payment);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la confirmation du paiement Interac', [
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
     * Confirmer un paiement par virement bancaire
     */
    public function confirmBankTransferPayment(Payment $payment, ?string $bankReference = null): bool
    {
        try {
            $payment->update([
                'status' => 'completed',
                'paid_at' => now(),
                'bank_reference' => $bankReference ?? $payment->bank_reference,
                'confirmed_by' => auth()->id(),
            ]);

            // Mettre à jour l'adhésion
            if ($payment->type === 'adhesion' && $payment->membership) {
                $this->updateMembershipAfterPayment($payment->membership, $payment->amount);
            } elseif ($payment->type === 'contribution') {
                $this->updateContributionsAfterPayment($payment);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la confirmation du virement bancaire', [
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
        if ($payment->payment_method === 'interac') {
            return $this->refundInteracPayment($payment, $amount);
        } elseif ($payment->payment_method === 'bank_transfer') {
            return $this->refundBankTransferPayment($payment, $amount);
        }

        return false;
    }

    /**
     * Rembourser un paiement Interac
     */
    private function refundInteracPayment(Payment $payment, ?float $amount = null): bool
    {
        try {
            $refundAmount = $amount ?? $payment->amount;
            
            $payment->update([
                'status' => 'refunded',
                'refunded_at' => now(),
                'refund_amount' => $refundAmount,
                'refund_method' => 'interac',
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors du remboursement Interac', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Rembourser un paiement par virement bancaire
     */
    private function refundBankTransferPayment(Payment $payment, ?float $amount = null): bool
    {
        try {
            $refundAmount = $amount ?? $payment->amount;
            
            $payment->update([
                'status' => 'refunded',
                'refunded_at' => now(),
                'refund_amount' => $refundAmount,
                'refund_method' => 'bank_transfer',
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors du remboursement par virement', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Obtenir les informations bancaires pour le virement
     */
    public function getBankingInfo(): array
    {
        $bankSetting = \App\Models\BankSetting::getActive();
        
        if ($bankSetting) {
            return $bankSetting->getInfo();
        }
        
        // Fallback to config if no database setting
        return [
            'bank_name' => config('services.bank.name', 'Banque Nationale du Canada'),
            'account_holder' => config('services.bank.account_holder', 'Association Westmount'),
            'account_number' => config('services.bank.account_number', '1234567890'),
            'transit_number' => config('services.bank.transit_number', '12345'),
            'institution_number' => config('services.bank.institution_number', '006'),
            'swift_code' => config('services.bank.swift_code', 'BNCACAMM'),
            'routing_number' => config('services.bank.routing_number', '00060012345'),
        ];
    }

    /**
     * Obtenir les informations Interac
     */
    public function getInteracInfo(): array
    {
        $interacSetting = \App\Models\InteracSetting::getActive();
        
        if ($interacSetting) {
            return [
                'email' => $interacSetting->email,
                'name' => $interacSetting->name,
                'security_question' => $interacSetting->security_question,
                'security_answer' => $interacSetting->security_answer,
                'instructions' => $interacSetting->instructions,
            ];
        }
        
        // Fallback to config if no database setting
        return [
            'email' => config('services.interac.email', 'paiements@associationwestmount.com'),
            'name' => config('services.interac.name', 'Association Westmount'),
            'security_question' => config('services.interac.security_question', 'Quel est le nom de l\'association?'),
            'security_answer' => config('services.interac.security_answer', 'Westmount'),
        ];
    }
}
