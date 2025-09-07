<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\PenaltySetting;
use App\Models\Membership;
use App\Models\Contribution;
use App\Notifications\PenaltyAppliedNotification;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PenaltyService
{
    /**
     * Appliquer les pénalités aux paiements en retard
     */
    public function applyPenalties(): array
    {
        $results = [
            'processed' => 0,
            'penalties_applied' => 0,
            'errors' => [],
        ];

        // Traiter les paiements d'adhésion en retard
        $this->processOverdueAdhesionPayments($results);

        // Traiter les contributions en retard
        $this->processOverdueContributions($results);

        return $results;
    }

    /**
     * Traiter les paiements d'adhésion en retard
     */
    private function processOverdueAdhesionPayments(array &$results): void
    {
        $overdueMemberships = Membership::where('status', 'overdue')
            ->where('next_payment_due', '<', now())
            ->with(['member', 'payments' => function($query) {
                $query->where('type', 'adhesion')
                      ->where('status', 'pending');
            }])
            ->get();

        foreach ($overdueMemberships as $membership) {
            try {
                $this->processMembershipPenalties($membership, $results);
            } catch (\Exception $e) {
                $results['errors'][] = "Erreur pour l'adhésion {$membership->id}: " . $e->getMessage();
                Log::error('Erreur lors du traitement des pénalités d\'adhésion', [
                    'membership_id' => $membership->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Traiter les contributions en retard
     */
    private function processOverdueContributions(array &$results): void
    {
        $overdueContributions = Contribution::where('status', 'pending')
            ->where('due_date', '<', now())
            ->with(['member', 'payments' => function($query) {
                $query->where('type', 'contribution')
                      ->where('status', 'pending');
            }])
            ->get();

        foreach ($overdueContributions as $contribution) {
            try {
                $this->processContributionPenalties($contribution, $results);
            } catch (\Exception $e) {
                $results['errors'][] = "Erreur pour la contribution {$contribution->id}: " . $e->getMessage();
                Log::error('Erreur lors du traitement des pénalités de contribution', [
                    'contribution_id' => $contribution->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    /**
     * Traiter les pénalités pour une adhésion
     */
    private function processMembershipPenalties(Membership $membership, array &$results): void
    {
        $results['processed']++;

        $pendingPayments = $membership->payments()
            ->where('type', 'adhesion')
            ->where('status', 'pending')
            ->get();

        foreach ($pendingPayments as $payment) {
            $this->applyPenaltyToPayment($payment, 'adhesion', $results);
        }
    }

    /**
     * Traiter les pénalités pour une contribution
     */
    private function processContributionPenalties(Contribution $contribution, array &$results): void
    {
        $results['processed']++;

        $pendingPayments = $contribution->payments()
            ->where('type', 'contribution')
            ->where('status', 'pending')
            ->get();

        foreach ($pendingPayments as $payment) {
            $this->applyPenaltyToPayment($payment, 'contribution', $results);
        }
    }

    /**
     * Appliquer une pénalité à un paiement
     */
    private function applyPenaltyToPayment(Payment $payment, string $type, array &$results): void
    {
        $penaltySetting = PenaltySetting::getDefaultForType($type);
        
        if (!$penaltySetting) {
            Log::warning("Aucun paramètre de pénalité trouvé pour le type: {$type}");
            return;
        }

        $overdueDays = $this->calculateOverdueDays($payment);
        
        if ($overdueDays <= $penaltySetting->grace_period_days) {
            return; // Période de grâce
        }

        // Vérifier si une pénalité a déjà été appliquée
        if ($payment->penalty_applied) {
            return;
        }

        $penaltyAmount = $penaltySetting->calculatePenalty($overdueDays, $payment->amount);
        
        if ($penaltyAmount > 0) {
            $payment->update([
                'penalty_amount' => $penaltyAmount,
                'total_amount' => $payment->amount + $penaltyAmount,
                'overdue_days' => $overdueDays,
                'penalty_applied' => true,
                'penalty_applied_at' => now(),
                'penalty_reason' => "Pénalité appliquée après {$overdueDays} jours de retard",
            ]);

            $results['penalties_applied']++;

            // Envoyer notification au membre
            $payment->member->notify(new PenaltyAppliedNotification($payment));

            Log::info('Pénalité appliquée', [
                'payment_id' => $payment->id,
                'member_id' => $payment->member_id,
                'penalty_amount' => $penaltyAmount,
                'overdue_days' => $overdueDays,
            ]);
        }
    }

    /**
     * Calculer le nombre de jours de retard
     */
    private function calculateOverdueDays(Payment $payment): int
    {
        if ($payment->type === 'adhesion' && $payment->membership) {
            return now()->diffInDays($payment->membership->next_payment_due);
        } elseif ($payment->type === 'contribution') {
            $contribution = $payment->member->contributions()
                ->where('status', 'pending')
                ->where('due_date', '<', now())
                ->first();
            
            if ($contribution) {
                return now()->diffInDays($contribution->due_date);
            }
        }

        return 0;
    }

    /**
     * Recalculer les pénalités pour un paiement
     */
    public function recalculatePenalty(Payment $payment): bool
    {
        $penaltySetting = PenaltySetting::getDefaultForType($payment->type);
        
        if (!$penaltySetting) {
            return false;
        }

        $overdueDays = $this->calculateOverdueDays($payment);
        $penaltyAmount = $penaltySetting->calculatePenalty($overdueDays, $payment->amount);

        $payment->update([
            'penalty_amount' => $penaltyAmount,
            'total_amount' => $payment->amount + $penaltyAmount,
            'overdue_days' => $overdueDays,
            'penalty_reason' => "Pénalité recalculée après {$overdueDays} jours de retard",
        ]);

        return true;
    }

    /**
     * Supprimer une pénalité
     */
    public function removePenalty(Payment $payment, string $reason = null): bool
    {
        $payment->update([
            'penalty_amount' => 0,
            'total_amount' => $payment->amount,
            'penalty_applied' => false,
            'penalty_applied_at' => null,
            'penalty_reason' => $reason ?? 'Pénalité supprimée manuellement',
        ]);

        return true;
    }

    /**
     * Obtenir le résumé des pénalités pour un membre
     */
    public function getMemberPenaltySummary(int $memberId): array
    {
        $payments = Payment::where('member_id', $memberId)
            ->where('penalty_applied', true)
            ->get();

        return [
            'total_penalties' => $payments->sum('penalty_amount'),
            'total_payments' => $payments->count(),
            'total_overdue_days' => $payments->sum('overdue_days'),
            'payments' => $payments->map(function($payment) {
                return [
                    'id' => $payment->id,
                    'type' => $payment->type,
                    'amount' => $payment->amount,
                    'penalty_amount' => $payment->penalty_amount,
                    'total_amount' => $payment->total_amount,
                    'overdue_days' => $payment->overdue_days,
                    'applied_at' => $payment->penalty_applied_at,
                ];
            }),
        ];
    }
}
