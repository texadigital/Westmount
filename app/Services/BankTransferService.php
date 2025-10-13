<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Member;
use App\Models\Membership;
use App\Notifications\BankTransferInstructionsNotification;
use Illuminate\Support\Facades\Log;

class BankTransferService
{
    /**
     * Créer un paiement par virement bancaire
     */
    public function createBankTransferPayment(Member $member, float $amount, string $type, ?string $description = null): Payment
    {
        $payment = Payment::create([
            'member_id' => $member->id,
            'membership_id' => $member->activeMembership?->id,
            'type' => $type,
            'amount' => $amount,
            'currency' => 'CAD',
            'status' => 'pending',
            'payment_method' => 'bank_transfer',
            'description' => $description ?? "Paiement par virement - {$member->full_name}",
            'bank_reference' => $this->generateBankReference(),
        ]);

        // Envoyer les instructions de virement
        $member->notify(new BankTransferInstructionsNotification($payment));

        return $payment;
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
            $this->updateMembershipAfterPayment($payment);

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
     * Marquer un paiement par virement comme échoué
     */
    public function markBankTransferFailed(Payment $payment, ?string $reason = null): bool
    {
        try {
            $payment->update([
                'status' => 'failed',
                'failure_reason' => $reason,
                'failed_at' => now(),
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur lors du marquage du virement comme échoué', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Générer une référence bancaire
     */
    private function generateBankReference(): string
    {
        return 'BT' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
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
     * Obtenir les informations bancaires pour le virement
     */
    public function getBankingInfo(): array
    {
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
}
