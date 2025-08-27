<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Afficher le formulaire de connexion
     */
    public function showLoginForm()
    {
        return view('member.auth.login');
    }

    /**
     * Traiter la connexion
     */
    public function login(Request $request)
    {
        $request->validate([
            'member_number' => 'required|string',
            'pin_code' => 'required|string|min:4',
        ], [
            'member_number.required' => 'Le numéro de membre est requis.',
            'pin_code.required' => 'Le code PIN est requis.',
            'pin_code.min' => 'Le code PIN doit contenir au moins 4 caractères.',
        ]);

        // Rechercher le membre par numéro
        $member = Member::where('member_number', $request->member_number)
                       ->where('is_active', true)
                       ->first();

        if (!$member) {
            return back()->withErrors([
                'member_number' => 'Numéro de membre invalide ou compte désactivé.',
            ])->withInput($request->only('member_number'));
        }

        // Vérifier le code PIN
        if (!$member->verifyPinCode($request->pin_code)) {
            return back()->withErrors([
                'pin_code' => 'Code PIN incorrect.',
            ])->withInput($request->only('member_number'));
        }

        // Connecter le membre
        $request->session()->put('member_id', $member->id);
        $request->session()->put('member_name', $member->full_name);

        return redirect()->route('member.dashboard')
            ->with('success', 'Connexion réussie ! Bienvenue ' . $member->first_name . '.');
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        $request->session()->forget(['member_id', 'member_name']);
        
        return redirect()->route('member.login')
            ->with('success', 'Vous avez été déconnecté avec succès.');
    }
}
