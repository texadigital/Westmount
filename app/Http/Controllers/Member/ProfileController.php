<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Member;

class ProfileController extends Controller
{
    /**
     * Afficher le profil
     */
    public function index(Request $request)
    {
        $member = $request->attributes->get('current_member');
        
        return view('member.profile', compact('member'));
    }

    /**
     * Mettre à jour le profil
     */
    public function update(Request $request)
    {
        $member = $request->attributes->get('current_member');
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'current_pin' => 'nullable|string|min:4',
            'new_pin' => 'nullable|string|min:4|confirmed',
        ], [
            'first_name.required' => 'Le prénom est requis.',
            'last_name.required' => 'Le nom est requis.',
            'phone.required' => 'Le téléphone est requis.',
            'email.required' => 'L\'email est requis.',
            'email.email' => 'L\'email doit être valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'address.required' => 'L\'adresse est requise.',
            'city.required' => 'La ville est requise.',
            'province.required' => 'La province est requise.',
            'postal_code.required' => 'Le code postal est requis.',
            'current_pin.required' => 'Le code PIN actuel est requis pour le modifier.',
            'new_pin.min' => 'Le nouveau code PIN doit contenir au moins 4 caractères.',
            'new_pin.confirmed' => 'La confirmation du nouveau code PIN ne correspond pas.',
        ]);

        // Vérifier le code PIN actuel si on veut le changer
        if ($request->filled('new_pin')) {
            if (!$request->filled('current_pin')) {
                return back()->withErrors([
                    'current_pin' => 'Le code PIN actuel est requis pour le modifier.',
                ])->withInput();
            }
            
            if (!$member->verifyPinCode($request->current_pin)) {
                return back()->withErrors([
                    'current_pin' => 'Le code PIN actuel est incorrect.',
                ])->withInput();
            }
        }

        // Mettre à jour les informations
        $member->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
        ]);

        // Mettre à jour le code PIN si fourni
        if ($request->filled('new_pin')) {
            $member->pin_code = $request->new_pin;
            $member->save();
        }

        return back()->with('success', 'Profil mis à jour avec succès !');
    }
}
