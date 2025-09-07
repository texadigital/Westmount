<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\LapsedMemberCode;
use App\Models\Member;
use App\Models\MemberType;
use App\Models\Membership;
use App\Models\Payment;
use App\Notifications\WelcomeMemberNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReactivationController extends Controller
{
    /**
     * Afficher le formulaire de réactivation
     */
    public function showReactivationForm()
    {
        $memberTypes = MemberType::active()->get();
        return view('public.reactivation', compact('memberTypes'));
    }

    /**
     * Traiter la réactivation
     */
    public function reactivate(Request $request)
    {
        $request->validate([
            'lapsed_code' => 'required|string|exists:lapsed_member_codes,code',
            'payment_method' => 'required|in:stripe,bank_transfer',
        ], [
            'lapsed_code.required' => 'Le code de réactivation est requis.',
            'lapsed_code.exists' => 'Le code de réactivation n\'est pas valide.',
            'payment_method.required' => 'La méthode de paiement est requise.',
            'payment_method.in' => 'Méthode de paiement invalide.',
        ]);

        // Vérifier le code de réactivation
        $lapsedCode = LapsedMemberCode::findValidCode($request->lapsed_code);
        
        if (!$lapsedCode) {
            return back()->withErrors([
                'lapsed_code' => 'Le code de réactivation n\'est pas valide ou a expiré.',
            ])->withInput();
        }
        
        $member = $lapsedCode->member;

        try {
            DB::beginTransaction();

            // Réactiver le membre
            $member->reactivate();
            $lapsedCode->markAsUsed();

            // Créer une nouvelle adhésion
            $membership = Membership::create([
                'member_id' => $member->id,
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addYear(),
                'is_active' => true,
            ]);

            // Créer le paiement de réactivation
            $payment = Payment::create([
                'member_id' => $member->id,
                'membership_id' => $membership->id,
                'type' => 'adhesion',
                'amount' => $member->memberType->adhesion_fee,
                'currency' => 'CAD',
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'description' => 'Paiement de réactivation d\'adhésion',
            ]);

            DB::commit();

            // Envoyer la notification de bienvenue
            $member->notify(new WelcomeMemberNotification($member));

            return redirect()->route('public.reactivation.success')
                ->with('success', 'Réactivation réussie ! Votre numéro de membre est : ' . $member->member_number);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors([
                'general' => 'Une erreur est survenue lors de la réactivation. Veuillez réessayer.',
            ])->withInput();
        }
    }

    /**
     * Afficher la page de succès
     */
    public function success()
    {
        return view('public.reactivation-success');
    }

    /**
     * Vérifier un code de réactivation (AJAX)
     */
    public function checkLapsedCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $lapsedCode = LapsedMemberCode::findValidCode($request->code);

        if ($lapsedCode) {
            return response()->json([
                'valid' => true,
                'member_name' => $lapsedCode->member->full_name,
                'expires_at' => $lapsedCode->expires_at->format('d/m/Y'),
            ]);
        }

        return response()->json([
            'valid' => false,
            'message' => 'Code de réactivation invalide ou expiré.',
        ]);
    }
}
