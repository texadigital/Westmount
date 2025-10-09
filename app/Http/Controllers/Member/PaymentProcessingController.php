<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use App\Services\StripePaymentService;
use App\Models\Payment;
use App\Models\Member;
use App\Notifications\PaymentReceivedNotification;
use App\Notifications\AdminPaymentReceivedNotification;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Stripe\Webhook as StripeWebhook;

class PaymentProcessingController extends Controller
{
    protected $paymentService;
    protected $stripePaymentService;

    public function __construct(PaymentService $paymentService, StripePaymentService $stripePaymentService)
    {
        $this->paymentService = $paymentService;
        $this->stripePaymentService = $stripePaymentService;
    }

    /**
     * Afficher le formulaire de paiement d'adhésion
     */
    public function showAdhesionPayment(Request $request)
    {
        $member = $request->attributes->get('current_member');
        
        Log::info('Payment page accessed', [
            'member_id' => $member->id,
            'member_name' => $member->first_name . ' ' . $member->last_name
        ]);
        
        $activeMembership = $member->activeMembership;

        if (!$activeMembership) {
            Log::warning('No active membership found for member, creating one', [
                'member_id' => $member->id,
                'all_memberships' => $member->memberships->pluck('id', 'status')->toArray()
            ]);
            
            // Create an active membership if none exists
            $activeMembership = \App\Models\Membership::create([
                'member_id' => $member->id,
                'member_type_id' => $member->member_type_id,
                'start_date' => now(),
                'end_date' => now()->addYear(),
                'status' => 'active',
                'is_active' => true,  // This was missing!
                'adhesion_fee' => $member->memberType->adhesion_fee,
                'death_contribution' => $member->memberType->death_contribution,
            ]);
            
            Log::info('Created new active membership', [
                'membership_id' => $activeMembership->id
            ]);
        }
        
        Log::info('Active membership found', [
            'membership_id' => $activeMembership->id,
            'status' => $activeMembership->status
        ]);

        $pendingPayment = $activeMembership->payments()
            ->where('type', 'adhesion')
            ->where('status', 'pending')
            ->first();

        // If no pending payment exists, create one
        if (!$pendingPayment) {
            try {
                Log::info('Creating adhesion payment for member', [
                    'member_id' => $member->id,
                    'membership_id' => $activeMembership->id,
                    'amount' => $member->memberType->adhesion_fee
                ]);
                
                $pendingPayment = $this->paymentService->createAdhesionPayment(
                    $member,
                    $activeMembership,
                    $member->memberType->adhesion_fee,
                    'bank_transfer' // Default payment method
                );
                
                Log::info('Adhesion payment created successfully', [
                    'payment_id' => $pendingPayment->id
                ]);
            } catch (\Exception $e) {
                Log::error('Error creating adhesion payment', [
                    'member_id' => $member->id,
                    'membership_id' => $activeMembership->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return redirect()->route('member.dashboard')
                    ->with('error', 'Erreur lors de la création du paiement: ' . $e->getMessage());
            }
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
        // Debug logging
        Log::info('Payment processing started', [
            'session_id' => $request->session()->getId(),
            'member_id_in_session' => $request->session()->get('member_id'),
            'has_member_in_request' => $request->attributes->has('current_member'),
            'expects_json' => $request->expectsJson(),
            'ajax' => $request->ajax(),
            'headers' => $request->headers->all()
        ]);

        $request->validate([
            'payment_method' => 'required|in:interac,bank_transfer,stripe',
        ]);

        $member = $request->attributes->get('current_member');
        
        if (!$member) {
            Log::error('No member found in request attributes', [
                'session_id' => $request->session()->getId(),
                'member_id_in_session' => $request->session()->get('member_id')
            ]);
            
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Session expirée. Veuillez vous reconnecter.'], 401);
            }
            return redirect()->route('member.login')->with('error', 'Session expirée. Veuillez vous reconnecter.');
        }
        
        $activeMembership = $member->activeMembership;

        if (!$activeMembership) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Aucune adhésion active trouvée.'], 400);
            }
            return back()->with('error', 'Aucune adhésion active trouvée.');
        }

        try {
            // Route by payment method
            if ($request->payment_method === 'stripe') {
                $amount = $activeMembership->member->memberType->adhesion_fee;

                // Create PaymentIntent via Stripe
                $intent = $this->stripePaymentService->createPaymentIntent(
                    $member,
                    $amount,
                    'adhesion',
                    ['membership_id' => $activeMembership->id]
                );

                if (!$intent) {
                    throw new \RuntimeException('Impossible de créer le paiement Stripe.');
                }

                // Persist payment row linked to the Stripe intent
                $payment = Payment::create([
                    'member_id' => $member->id,
                    'membership_id' => $activeMembership->id,
                    'type' => 'adhesion',
                    'amount' => $amount,
                    'currency' => 'CAD',
                    'status' => 'pending',
                    'payment_method' => 'stripe',
                    'stripe_payment_intent_id' => $intent->id,
                    'description' => "Paiement d'adhésion - ".$member->full_name,
                ]);

                $response = [
                    'success' => true,
                    'payment_id' => $payment->id,
                    'amount' => $payment->amount,
                    'currency' => $payment->currency,
                    'payment_method' => $payment->payment_method,
                    'client_secret' => $intent->client_secret,
                ];

                return response()->json($response);
            }

            // Default to offline methods (Interac / Bank transfer)
            $payment = $this->paymentService->createAdhesionPayment(
                $member,
                $activeMembership,
                $activeMembership->member->memberType->adhesion_fee,
                $request->payment_method
            );

            $response = [
                'success' => true,
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'payment_method' => $payment->payment_method,
            ];

            if ($payment->payment_method === 'interac') {
                $response['interac_reference'] = $payment->interac_reference;
                $response['interac_info'] = $this->paymentService->getInteracInfo();
            }

            if ($payment->payment_method === 'bank_transfer') {
                $response['bank_reference'] = $payment->bank_reference;
                $response['banking_info'] = $this->paymentService->getBankingInfo();
            }

            if ($request->expectsJson()) {
                return response()->json($response);
            }

            return redirect()->route('member.membership')
                ->with('success', 'Paiement créé avec succès. Veuillez effectuer le virement avec les informations fournies.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du paiement d\'adhésion', [
                'member_id' => $member->id,
                'error' => $e->getMessage(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de la création du paiement.',
                ], 500);
            }

            return back()->with('error', 'Une erreur est survenue lors de la création du paiement.');
        }
    }

    /**
     * Confirmer un paiement Stripe (fallback côté serveur)
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

        $success = $this->stripePaymentService->confirmPayment($payment);

        if ($success) {
            // Notify the member
            try {
                $payment->member?->notify(new PaymentReceivedNotification($payment));
            } catch (\Throwable $e) {
                Log::warning('Notification paiement non envoyée', ['payment_id' => $payment->id, 'error' => $e->getMessage()]);
            }

            // Notify admin(s)
            try {
                $adminEmail = Setting::get('admin_notification_email', env('ADMIN_NOTIFICATION_EMAIL'));
                if ($adminEmail) {
                    Notification::route('mail', $adminEmail)->notify(new AdminPaymentReceivedNotification($payment));
                }
                foreach (User::all() as $adminUser) {
                    $adminUser->notify(new AdminPaymentReceivedNotification($payment));
                }
            } catch (\Throwable $e) {
                Log::warning('Admin notification paiement non envoyée', ['payment_id' => $payment->id, 'error' => $e->getMessage()]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Paiement confirmé.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Paiement non confirmé.',
        ], 400);
    }

    /**
     * Webhook Stripe pour mises à jour en temps réel
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = Setting::get('stripe_webhook_secret', config('services.stripe.webhook.secret'));

        if (!$endpointSecret) {
            Log::error('Stripe webhook secret not configured');
            return response()->json(['error' => 'Webhook non configuré'], 500);
        }

        try {
            $event = StripeWebhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response()->json(['error' => 'Payload invalide'], 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response()->json(['error' => 'Signature invalide'], 400);
        }

        $type = $event['type'] ?? null;
        $object = $event['data']['object'] ?? [];

        if (in_array($type, ['payment_intent.succeeded', 'payment_intent.payment_failed'])) {
            $intentId = $object['id'] ?? null;
            if ($intentId) {
                $payment = Payment::where('stripe_payment_intent_id', $intentId)->first();
                if ($payment) {
                    if ($type === 'payment_intent.succeeded') {
                        // Confirm payment and notify
                        if ($this->stripePaymentService->confirmPayment($payment)) {
                            try {
                                $payment->member?->notify(new PaymentReceivedNotification($payment));
                            } catch (\Throwable $e) {
                                Log::warning('Notification paiement non envoyée (webhook)', ['payment_id' => $payment->id, 'error' => $e->getMessage()]);
                            }

                            // Notify admin(s)
                            try {
                                $adminEmail = Setting::get('admin_notification_email', env('ADMIN_NOTIFICATION_EMAIL'));
                                if ($adminEmail) {
                                    Notification::route('mail', $adminEmail)->notify(new AdminPaymentReceivedNotification($payment));
                                }
                                foreach (User::all() as $adminUser) {
                                    $adminUser->notify(new AdminPaymentReceivedNotification($payment));
                                }
                            } catch (\Throwable $e) {
                                Log::warning('Admin notification paiement non envoyée (webhook)', ['payment_id' => $payment->id, 'error' => $e->getMessage()]);
                            }
                        }
                    } elseif ($type === 'payment_intent.payment_failed') {
                        $payment->markAsFailed();
                    }
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Traiter un paiement de contribution
     */
    public function processContributionPayment(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'payment_method' => 'required|in:interac,bank_transfer,stripe',
        ]);

        $member = $request->attributes->get('current_member');

        try {
            if ($request->payment_method === 'stripe') {
                $amount = (float) $request->amount;

                $intent = $this->stripePaymentService->createPaymentIntent(
                    $member,
                    $amount,
                    'contribution',
                    ['description' => $request->description]
                );

                if (!$intent) {
                    throw new \RuntimeException('Impossible de créer le paiement Stripe.');
                }

                $payment = Payment::create([
                    'member_id' => $member->id,
                    'membership_id' => $member->activeMembership?->id,
                    'type' => 'contribution',
                    'amount' => $amount,
                    'currency' => 'CAD',
                    'status' => 'pending',
                    'payment_method' => 'stripe',
                    'stripe_payment_intent_id' => $intent->id,
                    'description' => $request->description ?? ('Contribution - '.$member->full_name),
                ]);

                return response()->json([
                    'success' => true,
                    'payment_id' => $payment->id,
                    'amount' => $payment->amount,
                    'currency' => $payment->currency,
                    'payment_method' => $payment->payment_method,
                    'client_secret' => $intent->client_secret,
                ]);
            }

            $payment = $this->paymentService->createContributionPayment(
                $member,
                $request->amount,
                $request->description,
                $request->payment_method
            );

            $response = [
                'success' => true,
                'payment_id' => $payment->id,
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'payment_method' => $payment->payment_method,
            ];

            if ($payment->payment_method === 'interac') {
                $response['interac_reference'] = $payment->interac_reference;
                $response['interac_info'] = $this->paymentService->getInteracInfo();
            }

            if ($payment->payment_method === 'bank_transfer') {
                $response['bank_reference'] = $payment->bank_reference;
                $response['banking_info'] = $this->paymentService->getBankingInfo();
            }

            return response()->json($response);

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
     * Confirmer un paiement Interac (admin)
     */
    public function confirmInteracPayment(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'interac_reference' => 'nullable|string|max:255',
        ]);

        $payment = Payment::findOrFail($request->payment_id);

        if ($payment->payment_method !== 'interac') {
            return response()->json([
                'success' => false,
                'message' => 'Ce paiement n\'est pas un paiement Interac.',
            ], 400);
        }

        if ($payment->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Ce paiement a déjà été traité.',
            ], 400);
        }

        try {
            $success = $this->paymentService->confirmInteracPayment(
                $payment,
                $request->interac_reference
            );

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Paiement confirmé avec succès.',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la confirmation du paiement.',
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la confirmation du paiement Interac', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la confirmation.',
            ], 500);
        }
    }


    /**
     * Confirmer un paiement par virement bancaire (admin)
     */
    public function confirmBankTransferPayment(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'bank_reference' => 'nullable|string|max:255',
        ]);

        $payment = Payment::findOrFail($request->payment_id);

        if ($payment->payment_method !== 'bank_transfer') {
            return response()->json([
                'success' => false,
                'message' => 'Ce paiement n\'est pas un virement bancaire.',
            ], 400);
        }

        if ($payment->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Ce paiement a déjà été traité.',
            ], 400);
        }

        try {
            $success = $this->paymentService->confirmBankTransferPayment(
                $payment,
                $request->bank_reference
            );

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Paiement confirmé avec succès.',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la confirmation du paiement.',
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de la confirmation du virement bancaire', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la confirmation.',
            ], 500);
        }
    }

    /**
     * Rembourser un paiement
     */
    public function refundPayment(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'amount' => 'nullable|numeric|min:0.01',
        ]);

        $payment = Payment::findOrFail($request->payment_id);

        if ($payment->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Seuls les paiements complétés peuvent être remboursés.',
            ], 400);
        }

        try {
            $success = $this->paymentService->refundPayment(
                $payment,
                $request->amount
            );

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Remboursement effectué avec succès.',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du remboursement.',
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors du remboursement', [
                'payment_id' => $payment->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du remboursement.',
            ], 500);
        }
    }

}
