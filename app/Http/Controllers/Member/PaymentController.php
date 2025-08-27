<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    /**
     * Afficher l'historique des paiements
     */
    public function index(Request $request)
    {
        $member = $request->attributes->get('current_member');
        
        $payments = $member->payments()
            ->with('membership')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('member.payments.index', compact('member', 'payments'));
    }

    /**
     * Afficher les détails d'un paiement
     */
    public function show(Request $request, Payment $payment)
    {
        $member = $request->attributes->get('current_member');
        
        // Vérifier que le paiement appartient au membre connecté
        if ($payment->member_id !== $member->id) {
            abort(403, 'Accès non autorisé.');
        }

        return view('member.payments.show', compact('member', 'payment'));
    }
}
