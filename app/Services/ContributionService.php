<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Contribution;
use App\Models\Membership;
use App\Models\MemberType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ContributionService
{
    /**
     * Créer automatiquement les contributions lors du décès d'un membre
     */
    public function createDeathContributions(Member $deceasedMember, Carbon $deathDate = null): array
    {
        $deathDate = $deathDate ?? now();
        $contributions = [];

        try {
            DB::beginTransaction();

            // Récupérer tous les membres actifs
            $activeMembers = Member::active()
                ->where('id', '!=', $deceasedMember->id)
                ->with('memberType')
                ->get();

            foreach ($activeMembers as $member) {
                $contribution = $this->createContributionForMember($member, $deceasedMember, $deathDate);
                if ($contribution) {
                    $contributions[] = $contribution;
                }
            }

            // Marquer le membre comme décédé
            $deceasedMember->update([
                'is_active' => false,
                'deceased_at' => $deathDate,
            ]);

            DB::commit();

            Log::info('Contributions de décès créées', [
                'deceased_member_id' => $deceasedMember->id,
                'contributions_count' => count($contributions),
            ]);

            return $contributions;

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur lors de la création des contributions de décès', [
                'deceased_member_id' => $deceasedMember->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Créer une contribution pour un membre spécifique
     */
    private function createContributionForMember(Member $member, Member $deceasedMember, Carbon $deathDate): ?Contribution
    {
        // Vérifier si le membre a un type valide
        if (!$member->memberType) {
            Log::warning('Membre sans type de membre', ['member_id' => $member->id]);
            return null;
        }

        // Calculer le montant de la contribution
        $contributionAmount = $this->calculateContributionAmount($member, $deceasedMember);

        if ($contributionAmount <= 0) {
            return null;
        }

        // Créer la contribution
        $contribution = Contribution::create([
            'member_id' => $member->id,
            'deceased_member_id' => $deceasedMember->id,
            'amount' => $contributionAmount,
            'status' => 'pending',
            'due_date' => $deathDate->addDays(30), // 30 jours pour payer
            'notes' => "Contribution de décès pour {$deceasedMember->full_name}",
        ]);

        return $contribution;
    }

    /**
     * Calculer le montant de la contribution
     */
    private function calculateContributionAmount(Member $member, Member $deceasedMember): float
    {
        $memberType = $member->memberType;
        $deceasedMemberType = $deceasedMember->memberType;

        // Montant de base Contribution selon le type de membre décédé.
        $baseAmount = $memberType->death_contribution;

        // Ajustements selon les règles métier
        $adjustment = $this->calculateAdjustment($member, $deceasedMember);

        return max(0, $baseAmount + $adjustment);
    }

    /**
     * Calculer les ajustements selon les règles métier
     */
    private function calculateAdjustment(Member $member, Member $deceasedMember): float
    {
        $adjustment = 0;

        // Ajustement selon l'ancienneté du membre
        $membershipDuration = $this->calculateMembershipDuration($member);
        if ($membershipDuration >= 5) {
            $adjustment += 5.00; // Bonus pour ancienneté
        }

        // Ajustement selon l'âge du membre décédé
        $deceasedAge = $deceasedMember->age;
        if ($deceasedAge >= 65) {
            $adjustment += 2.00; // Bonus pour membre senior décédé
        }

        // Ajustement Contribution selon le type de membre décédé. décédé
        if ($deceasedMember->memberType && $deceasedMember->memberType->name === 'Senior') {
            $adjustment += 1.00; // Bonus pour décès d'un senior
        }

        return $adjustment;
    }

    /**
     * Calculer la durée d'adhésion d'un membre
     */
    private function calculateMembershipDuration(Member $member): int
    {
        $firstMembership = $member->memberships()
            ->orderBy('start_date', 'asc')
            ->first();

        if (!$firstMembership) {
            return 0;
        }

        return $firstMembership->start_date->diffInYears(now());
    }

    /**
     * Traiter le paiement d'une contribution
     */
    public function processContributionPayment(Contribution $contribution, float $amount): bool
    {
        try {
            DB::beginTransaction();

            // Mettre à jour la contribution
            $contribution->update([
                'status' => 'paid',
                'paid_date' => now(),
            ]);

            // Créer un paiement associé
            $contribution->payment()->create([
                'member_id' => $contribution->member_id,
                'membership_id' => $contribution->member->activeMembership?->id,
                'type' => 'contribution',
                'amount' => $amount,
                'currency' => 'CAD',
                'status' => 'completed',
                'payment_method' => 'manual',
                'description' => "Paiement contribution - {$contribution->deceasedMember->full_name}",
                'paid_at' => now(),
            ]);

            // Mettre à jour les statistiques du membre
            $this->updateMemberStatistics($contribution->member, $amount);

            DB::commit();

            Log::info('Paiement de contribution traité', [
                'contribution_id' => $contribution->id,
                'member_id' => $contribution->member_id,
                'amount' => $amount,
            ]);

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erreur lors du traitement du paiement de contribution', [
                'contribution_id' => $contribution->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Mettre à jour les statistiques du membre
     */
    private function updateMemberStatistics(Member $member, float $amount): void
    {
        $activeMembership = $member->activeMembership;
        
        if ($activeMembership) {
            $activeMembership->update([
                'total_contributions_paid' => $activeMembership->total_contributions_paid + $amount,
            ]);
            
            $activeMembership->updateStatus();
        }
    }

    /**
     * Annuler une contribution
     */
    public function cancelContribution(Contribution $contribution, string $reason = null): bool
    {
        try {
            $contribution->update([
                'status' => 'cancelled',
                'notes' => $contribution->notes . "\nAnnulée: " . ($reason ?? 'Aucune raison spécifiée'),
            ]);

            Log::info('Contribution annulée', [
                'contribution_id' => $contribution->id,
                'reason' => $reason,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'annulation de la contribution', [
                'contribution_id' => $contribution->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Obtenir les statistiques des contributions
     */
    public function getContributionStatistics(): array
    {
        $totalContributions = Contribution::count();
        $pendingContributions = Contribution::pending()->count();
        $paidContributions = Contribution::paid()->count();
        $totalAmount = Contribution::paid()->sum('amount');
        $pendingAmount = Contribution::pending()->sum('amount');

        return [
            'total_contributions' => $totalContributions,
            'pending_contributions' => $pendingContributions,
            'paid_contributions' => $paidContributions,
            'total_amount' => $totalAmount,
            'pending_amount' => $pendingAmount,
            'completion_rate' => $totalContributions > 0 ? ($paidContributions / $totalContributions) * 100 : 0,
        ];
    }

    /**
     * Envoyer des rappels pour les contributions en retard
     */
    public function sendOverdueReminders(): int
    {
        $overdueContributions = Contribution::overdue()
            ->with(['member', 'deceasedMember'])
            ->get();

        $sentCount = 0;

        foreach ($overdueContributions as $contribution) {
            try {
                // Envoyer notification
                $contribution->member->notify(new \App\Notifications\ContributionOverdueNotification($contribution));
                $sentCount++;

            } catch (\Exception $e) {
                Log::error('Erreur lors de l\'envoi du rappel de contribution', [
                    'contribution_id' => $contribution->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('Rappels de contributions envoyés', [
            'total_overdue' => $overdueContributions->count(),
            'sent_count' => $sentCount,
        ]);

        return $sentCount;
    }
}
