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
        
        // Statistiques
        $stats = [
            'total_payments' => $member->payments()->count(),
            'total_contributions' => $member->contributions()->count(),
            'pending_contributions' => $member->contributions()->where('status', 'pending')->count(),
            'overdue_amount' => $activeMembership ? $activeMembership->amount_due : 0,
        ];

        return view('member.dashboard', compact(
            'member',
            'activeMembership',
            'recentPayments',
            'pendingContributions',
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
