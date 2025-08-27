<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\MemberType;
use App\Models\Sponsorship;
use App\Models\Membership;
use App\Models\Payment;
use App\Notifications\WelcomeMemberNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RegistrationController extends Controller
{
    /**
     * Afficher le formulaire d'enregistrement
     */
    public function showRegistrationForm()
    {
        $memberTypes = MemberType::active()->get();
        return view('public.registration', compact('memberTypes'));
    }

    /**
     * Traiter l'enregistrement
     */
    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date|before:today',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|max:255',
            'canadian_status_proof' => 'required|string|max:255',
            'member_type_id' => 'required|exists:member_types,id',
            'sponsorship_code' => 'nullable|string|exists:sponsorships,sponsorship_code',
            'pin_code' => 'required|string|min:4|max:6',
            'pin_code_confirmation' => 'required|same:pin_code',
            'organization_id' => 'nullable|exists:organizations,id',
        ], [
            'first_name.required' => 'Le prénom est requis.',
            'last_name.required' => 'Le nom est requis.',
            'birth_date.required' => 'La date de naissance est requise.',
            'birth_date.before' => 'La date de naissance doit être dans le passé.',
            'phone.required' => 'Le téléphone est requis.',
            'email.required' => 'L\'email est requis.',
            'email.email' => 'L\'email doit être valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'address.required' => 'L\'adresse est requise.',
            'city.required' => 'La ville est requise.',
            'province.required' => 'La province est requise.',
            'postal_code.required' => 'Le code postal est requis.',
            'country.required' => 'Le pays est requis.',
            'canadian_status_proof.required' => 'La preuve de statut canadien est requise.',
            'member_type_id.required' => 'Le type de membre est requis.',
            'member_type_id.exists' => 'Le type de membre sélectionné n\'existe pas.',
            'sponsorship_code.exists' => 'Le code de parrainage n\'est pas valide.',
            'pin_code.required' => 'Le code PIN est requis.',
            'pin_code.min' => 'Le code PIN doit contenir au moins 4 caractères.',
            'pin_code.max' => 'Le code PIN ne peut pas dépasser 6 caractères.',
            'pin_code_confirmation.same' => 'La confirmation du code PIN ne correspond pas.',
        ]);

        // Vérifier l'âge par rapport au type de membre
        $memberType = MemberType::find($request->member_type_id);
        $age = Carbon::parse($request->birth_date)->age;
        
        if (!$memberType->isValidAge($age)) {
            return back()->withErrors([
                'birth_date' => 'Votre âge ne correspond pas aux critères du type de membre sélectionné.',
            ])->withInput();
        }

        // Vérifier le code de parrainage si fourni
        $sponsorship = null;
        $sponsor = null;
        
        if ($request->sponsorship_code) {
            $sponsorship = Sponsorship::where('sponsorship_code', $request->sponsorship_code)
                                    ->where('status', 'pending')
                                    ->where('expires_at', '>', now())
                                    ->first();
            
            if (!$sponsorship) {
                return back()->withErrors([
                    'sponsorship_code' => 'Le code de parrainage n\'est pas valide ou a expiré.',
                ])->withInput();
            }
            
            $sponsor = $sponsorship->sponsor;
        }

        try {
            DB::beginTransaction();

            // Créer le membre
            $member = Member::create([
                'member_number' => Member::generateMemberNumber(),
                'pin_code' => $request->pin_code,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'birth_date' => $request->birth_date,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'province' => $request->province,
                'postal_code' => $request->postal_code,
                'country' => $request->country,
                'canadian_status_proof' => $request->canadian_status_proof,
                'member_type_id' => $request->member_type_id,
                'organization_id' => $request->organization_id,
                'sponsor_id' => $sponsor ? $sponsor->id : null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // Créer l'adhésion
            $membership = Membership::create([
                'member_id' => $member->id,
                'status' => 'active',
                'start_date' => now(),
                'end_date' => now()->addYear(),
                'is_active' => true,
            ]);

            // Créer le paiement initial
            $payment = Payment::create([
                'member_id' => $member->id,
                'membership_id' => $membership->id,
                'type' => 'adhesion',
                'amount' => $memberType->adhesion_fee,
                'currency' => 'CAD',
                'status' => 'pending',
                'payment_method' => 'pending',
                'description' => 'Paiement d\'adhésion initial',
            ]);

            // Marquer le parrainage comme confirmé si applicable
            if ($sponsorship) {
                $sponsorship->confirm();
            }

            DB::commit();

            // Envoyer la notification de bienvenue
            $member->notify(new WelcomeMemberNotification($member, $payment));

            return redirect()->route('public.registration.success')
                ->with('success', 'Inscription réussie ! Votre numéro de membre est : ' . $member->member_number);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors([
                'general' => 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer.',
            ])->withInput();
        }
    }

    /**
     * Afficher la page de succès
     */
    public function success()
    {
        return view('public.registration-success');
    }

    /**
     * Vérifier un code de parrainage (AJAX)
     */
    public function checkSponsorshipCode(Request $request)
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
