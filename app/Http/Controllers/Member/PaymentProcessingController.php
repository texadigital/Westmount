<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use App\Models\Payment;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentProcessingController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Afficher le formulaire de paiement d'adhésion
     */
    public function showAdhesionPayment(Request $request)
    {
        $member = $request->attributes->get('current_member');
        $activeMembership = $member->activeMembership;

        if (!$activeMembership) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Aucune adhésion active trouvée.');
        }

        $pendingPayment = $activeMembership->payments()
            ->where('type', 'adhesion')
            ->where('status', 'pending')
            ->first();

        if (!$pendingPayment) {
            return redirect()->route('member.dashboard')
                ->with('error', 'Aucun paiement d\'adhésion en attente.');
        }

        return view('member.payment.adhesion', compact('member', 'activeMembership', 'pendingPayment'));
    }

    /**
     * Afficher le formulaire de paiement de contribution
     */
    public function showContributionPayment(Request $request)
    {
        $member = $request->attributes->get('current_member');
        $pendingContributions = $member->contributions()
            ->where('status', 'pending')
            ->with('deceasedMember')
            ->get();

        $totalAmount = $pendingContributions->sum('amount');

        return view('member.payment.contribution', compact('member', 'pendingContributions', 'totalAmount'));
    }

    /**
     * Traiter un paiement d'adhésion
     */
    public function processAdhesionPayment(Request $request)
    {
        $member = $request->attributes->get('current_member');
        $activeMembership = $member->activeMembership;

        if (!$activeMembership) {
            return back()->with('error', 'Aucune adhésion active trouvée.');
        }

        try {
            $payment = $this->paymentService->createAdhesionPayment(
                $member,
                $activeMembership,
                $activeMembership->member->memberType->adhesion_fee
            );

            return response()->json([
                'success' => true,
                'payment_intent_id' => $payment->stripe_payment_intent_id,
                'client_secret' => $this->getClientSecret($payment->stripe_payment_intent_id),
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du paiement d\'adhésion', [
                'member_id' => $member->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la création du paiement.',
            ], 500);
        }
    }

    /**
     * Traiter un paiement de contribution
     */
    public function processContributionPayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
        ]);

        $member = $request->attributes->get('current_member');

        try {
            $payment = $this->paymentService->createContributionPayment(
                $member,
                $request->amount,
                $request->description
            );

            return response()->json([
                'success' => true,
                'payment_intent_id' => $payment->stripe_payment_intent_id,
                'client_secret' => $this->getClientSecret($payment->stripe_payment_intent_id),
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du paiement de contribution', [
                'member_id' => $member->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la création du paiement.',
            ], 500);
        }
    }

    /**
     * Confirmer un paiement (webhook Stripe)
     */
    public function confirmPayment(Request $request)
    {
        $request->validate([
            'payment_intent_id' => 'required|string',
        ]);

        $payment = Payment::where('stripe_payment_intent_id', $request->payment_intent_id)->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Paiement non trouvé.',
            ], 404);
        }

        try {
            $success = $this->paymentService->confirmPayment($payment);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Paiement confirmé avec succès.',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Le paiement n\'a pas pu être confirmé.',
                ], 400);
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de la confirmation du paiement', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la confirmation du paiement.',
            ], 500);
        }
    }

    /**
     * Webhook Stripe
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\Exception $e) {
            Log::error('Erreur webhook Stripe', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;
            case 'payment_intent.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;
            default:
                Log::info('Webhook Stripe non géré', ['type' => $event->type]);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Gérer un paiement réussi
     */
    private function handlePaymentSucceeded($paymentIntent)
    {
        $payment = Payment::where('stripe_payment_intent_id', $paymentIntent->id)->first();

        if ($payment) {
            $this->paymentService->confirmPayment($payment, $paymentIntent->latest_charge);
            
            Log::info('Paiement confirmé via webhook', [
                'payment_id' => $payment->id,
                'member_id' => $payment->member_id,
            ]);
        }
    }

    /**
     * Gérer un paiement échoué
     */
    private function handlePaymentFailed($paymentIntent)
    {
        $payment = Payment::where('stripe_payment_intent_id', $paymentIntent->id)->first();

        if ($payment) {
            $payment->update(['status' => 'failed']);
            
            Log::info('Paiement échoué via webhook', [
                'payment_id' => $payment->id,
                'member_id' => $payment->member_id,
            ]);
        }
    }

    /**
     * Obtenir le client secret pour Stripe
     */
    private function getClientSecret(string $paymentIntentId): string
    {
        try {
            $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);
            return $paymentIntent->client_secret;
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération du client secret', [
                'payment_intent_id' => $paymentIntentId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }
}
