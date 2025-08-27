<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Sponsorship;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SponsorshipController extends Controller
{
    /**
     * Afficher la liste des parrainages
     */
    public function index(Request $request)
    {
        $member = $request->attributes->get('current_member');
        
        $sponsorships = $member->sponsorships()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('member.sponsorship.index', compact('member', 'sponsorships'));
    }

    /**
     * Afficher le formulaire de création de parrainage
     */
    public function create(Request $request)
    {
        $member = $request->attributes->get('current_member');
        
        return view('member.sponsorship.create', compact('member'));
    }

    /**
     * Créer un nouveau parrainage
     */
    public function store(Request $request)
    {
        $request->validate([
            'prospect_first_name' => 'required|string|max:255',
            'prospect_last_name' => 'required|string|max:255',
            'prospect_email' => 'required|email',
            'prospect_phone' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ], [
            'prospect_first_name.required' => 'Le prénom du prospect est requis.',
            'prospect_last_name.required' => 'Le nom du prospect est requis.',
            'prospect_email.required' => 'L\'email du prospect est requis.',
            'prospect_email.email' => 'L\'email du prospect doit être valide.',
            'prospect_phone.required' => 'Le téléphone du prospect est requis.',
        ]);

        $member = $request->attributes->get('current_member');

        try {
            $sponsorship = Sponsorship::create([
                'sponsor_id' => $member->id,
                'prospect_first_name' => $request->prospect_first_name,
                'prospect_last_name' => $request->prospect_last_name,
                'prospect_email' => $request->prospect_email,
                'prospect_phone' => $request->prospect_phone,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);

            // Envoyer un email au prospect
            $this->sendSponsorshipEmail($sponsorship);

            return redirect()->route('member.sponsorship.index')
                ->with('success', 'Parrainage créé avec succès ! Un email a été envoyé au prospect.');

        } catch (\Exception $e) {
            return back()->withErrors([
                'general' => 'Une erreur est survenue lors de la création du parrainage.',
            ])->withInput();
        }
    }

    /**
     * Afficher les détails d'un parrainage
     */
    public function show(Request $request, Sponsorship $sponsorship)
    {
        $member = $request->attributes->get('current_member');
        
        // Vérifier que le parrainage appartient au membre connecté
        if ($sponsorship->sponsor_id !== $member->id) {
            abort(403, 'Accès non autorisé.');
        }

        return view('member.sponsorship.show', compact('member', 'sponsorship'));
    }

    /**
     * Marquer un parrainage comme complété
     */
    public function complete(Request $request, Sponsorship $sponsorship)
    {
        $member = $request->attributes->get('current_member');
        
        // Vérifier que le parrainage appartient au membre connecté
        if ($sponsorship->sponsor_id !== $member->id) {
            abort(403, 'Accès non autorisé.');
        }

        if ($sponsorship->status !== 'confirmed') {
            return back()->with('error', 'Le parrainage doit être confirmé avant d\'être marqué comme complété.');
        }

        $sponsorship->markAsCompleted();

        return back()->with('success', 'Parrainage marqué comme complété !');
    }

    /**
     * Supprimer un parrainage
     */
    public function destroy(Request $request, Sponsorship $sponsorship)
    {
        $member = $request->attributes->get('current_member');
        
        // Vérifier que le parrainage appartient au membre connecté
        if ($sponsorship->sponsor_id !== $member->id) {
            abort(403, 'Accès non autorisé.');
        }

        if ($sponsorship->status === 'completed') {
            return back()->with('error', 'Impossible de supprimer un parrainage complété.');
        }

        $sponsorship->delete();

        return redirect()->route('member.sponsorship.index')
            ->with('success', 'Parrainage supprimé avec succès.');
    }

    /**
     * Envoyer un email de parrainage
     */
    private function sendSponsorshipEmail(Sponsorship $sponsorship)
    {
        try {
            Mail::send('emails.sponsorship', [
                'sponsorship' => $sponsorship,
                'sponsor' => $sponsorship->sponsor,
            ], function ($message) use ($sponsorship) {
                $message->to($sponsorship->prospect_email)
                        ->subject('Invitation à rejoindre l\'Association Westmount')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });
        } catch (\Exception $e) {
            // Log l'erreur mais ne pas faire échouer la création du parrainage
            \Log::error('Erreur lors de l\'envoi de l\'email de parrainage', [
                'sponsorship_id' => $sponsorship->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Vérifier un code de parrainage (AJAX)
     */
    public function checkCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $sponsorship = Sponsorship::where('sponsorship_code', $request->code)
                                ->where('status', 'pending')
                                ->where('expires_at', '>', now())
                                ->with('sponsor')
                                ->first();

        if ($sponsorship) {
            return response()->json([
                'valid' => true,
                'sponsor_name' => $sponsorship->sponsor->full_name,
                'expires_at' => $sponsorship->expires_at->format('d/m/Y'),
            ]);
        }

        return response()->json([
            'valid' => false,
            'message' => 'Code de parrainage invalide ou expiré.',
        ]);
    }
}
