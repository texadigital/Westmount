<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\PasswordReset;
use App\Notifications\PinResetNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AccountRecoveryController extends Controller
{
    /**
     * Afficher le formulaire de récupération de compte
     */
    public function showRecoveryForm()
    {
        return view('public.account-recovery');
    }

    /**
     * Traiter la demande de récupération
     */
    public function requestRecovery(Request $request)
    {
        $request->validate([
            'member_number' => 'required|string|exists:members,member_number',
            'email' => 'required|email',
        ], [
            'member_number.required' => 'Le numéro de membre est requis.',
            'member_number.exists' => 'Ce numéro de membre n\'existe pas.',
            'email.required' => 'L\'email est requis.',
            'email.email' => 'L\'email doit être valide.',
        ]);

        // Vérifier que le numéro de membre et l'email correspondent
        $member = Member::where('member_number', $request->member_number)
                       ->where('email', $request->email)
                       ->first();

        if (!$member) {
            return back()->withErrors([
                'email' => 'L\'email ne correspond pas au numéro de membre fourni.',
            ])->withInput();
        }

        // Vérifier si le membre est actif
        if (!$member->is_active) {
            return back()->withErrors([
                'member_number' => 'Ce compte est désactivé. Contactez l\'administration.',
            ])->withInput();
        }

        // Supprimer les anciens tokens de récupération
        PasswordReset::where('email', $member->email)->delete();

        // Créer un nouveau token de récupération
        $token = Str::random(64);
        PasswordReset::create([
            'email' => $member->email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        // Envoyer l'email de récupération
        $member->notify(new PinResetNotification($member, $token));

        return redirect()->route('public.account-recovery.sent')
            ->with('success', 'Un email de récupération a été envoyé à ' . $member->email);
    }

    /**
     * Page de confirmation d'envoi
     */
    public function sent()
    {
        return view('public.account-recovery-sent');
    }

    /**
     * Afficher le formulaire de réinitialisation du PIN
     */
    public function showResetForm(Request $request, $token)
    {
        // Vérifier le token
        $passwordReset = PasswordReset::where('email', $request->email)
                                    ->where('created_at', '>', now()->subHours(24))
                                    ->get()
                                    ->first(function ($reset) use ($token) {
                                        return Hash::check($token, $reset->token);
                                    });

        if (!$passwordReset) {
            return redirect()->route('public.account-recovery.form')
                ->withErrors(['token' => 'Le lien de récupération est invalide ou a expiré.']);
        }

        $member = Member::where('email', $passwordReset->email)->first();

        if (!$member) {
            return redirect()->route('public.account-recovery.form')
                ->withErrors(['token' => 'Membre non trouvé.']);
        }

        return view('public.account-recovery-reset', compact('member', 'token'));
    }

    /**
     * Traiter la réinitialisation du PIN
     */
    public function resetPin(Request $request, $token)
    {
        $request->validate([
            'email' => 'required|email',
            'pin_code' => 'required|string|min:4|max:6',
            'pin_code_confirmation' => 'required|same:pin_code',
        ], [
            'pin_code.required' => 'Le nouveau code PIN est requis.',
            'pin_code.min' => 'Le code PIN doit contenir au moins 4 caractères.',
            'pin_code.max' => 'Le code PIN ne peut pas dépasser 6 caractères.',
            'pin_code_confirmation.required' => 'La confirmation du code PIN est requise.',
            'pin_code_confirmation.same' => 'La confirmation du code PIN ne correspond pas.',
        ]);

        // Vérifier le token
        $passwordReset = PasswordReset::where('email', $request->email)
                                    ->where('created_at', '>', now()->subHours(24))
                                    ->get()
                                    ->first(function ($reset) use ($token) {
                                        return Hash::check($token, $reset->token);
                                    });

        if (!$passwordReset) {
            return back()->withErrors(['token' => 'Le lien de récupération est invalide ou a expiré.']);
        }

        $member = Member::where('email', $passwordReset->email)->first();

        if (!$member) {
            return back()->withErrors(['email' => 'Membre non trouvé.']);
        }

        // Mettre à jour le code PIN
        $member->update([
            'pin_code' => $request->pin_code,
        ]);

        // Supprimer le token de récupération
        $passwordReset->delete();

        return redirect()->route('public.account-recovery.success')
            ->with('success', 'Votre code PIN a été réinitialisé avec succès.');
    }

    /**
     * Page de succès
     */
    public function success()
    {
        return view('public.account-recovery-success');
    }
}
