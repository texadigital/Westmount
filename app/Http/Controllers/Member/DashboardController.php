<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Afficher le tableau de bord
     */
    public function index(Request $request)
    {
        $member = $request->attributes->get('current_member');
        
        // Récupérer l'adhésion active
        $activeMembership = $member->activeMembership;
        
        // Récupérer les derniers paiements
        $recentPayments = $member->payments()
            ->with('membership')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Récupérer les contributions en attente
        $pendingContributions = $member->contributions()
            ->where('status', 'pending')
            ->with('deceasedMember')
            ->orderBy('due_date', 'asc')
            ->get();
        
        // Récupérer les membres parrainés
        $sponsoredMembers = $member->sponsoredMembers()
            ->with('memberType')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Récupérer les parrainages actifs
        $activeSponsorships = $member->sponsorships()
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculer le montant dû en tenant compte des paiements en attente
        $overdueAmount = 0;
        $pendingPayments = 0;
        $completedPayments = 0;
        $pendingAmount = 0;
        
        if ($activeMembership) {
            // Montant total dû (adhésion + contribution de décès)
            $totalOwed = $member->memberType->adhesion_fee + $member->memberType->death_contribution;
            
            // Montant déjà payé (paiements confirmés uniquement)
            $totalPaid = $activeMembership->adhesion_fee_paid + $activeMembership->total_contributions_paid;
            
            // Montant des paiements en attente
            $pendingAmount = $member->payments()
                ->where('status', 'pending')
                ->sum('amount');
            
            // Montant dû = Total dû - Total payé - Montant en attente
            $overdueAmount = max(0, $totalOwed - $totalPaid - $pendingAmount);
            
            // Compter les paiements
            $pendingPayments = $member->payments()->where('status', 'pending')->count();
            $completedPayments = $member->payments()->where('status', 'completed')->count();
        }

        // Statistiques
        $stats = [
            'total_payments' => $member->payments()->count(),
            'total_contributions' => $member->contributions()->count(),
            'pending_contributions' => $member->contributions()->where('status', 'pending')->count(),
            'overdue_amount' => $overdueAmount,
            'pending_payments' => $pendingPayments,
            'completed_payments' => $completedPayments,
            'pending_amount' => $pendingAmount,
        ];

        return view('member.dashboard', compact(
            'member',
            'activeMembership',
            'recentPayments',
            'pendingContributions',
            'sponsoredMembers',
            'activeSponsorships',
            'stats'
        ));
    }

    /**
     * Afficher les détails de l'adhésion
     */
    public function membership(Request $request)
    {
        $member = $request->attributes->get('current_member');
        $activeMembership = $member->activeMembership;
        
        if (!$activeMembership) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Aucune adhésion active trouvée.');
        }
        
        // Récupérer tous les paiements de cette adhésion
        $payments = $activeMembership->payments()
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Récupérer les contributions liées
        $contributions = $member->contributions()
            ->where('status', 'pending')
            ->with('deceasedMember')
            ->orderBy('due_date', 'asc')
            ->get();

        return view('member.membership', compact(
            'member',
            'activeMembership',
            'payments',
            'contributions'
        ));
    }
}
