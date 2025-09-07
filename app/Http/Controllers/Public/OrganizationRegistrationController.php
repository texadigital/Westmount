<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\Member;
use App\Models\MemberType;
use App\Models\Membership;
use App\Models\Payment;
use App\Models\Sponsorship;
use App\Notifications\WelcomeMemberNotification;
use App\Notifications\OrganizationWelcomeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class OrganizationRegistrationController extends Controller
{
    /**
     * Afficher le formulaire d'inscription d'organisation
     */
    public function showRegistrationForm()
    {
        $memberTypes = MemberType::active()->get();
        return view('public.organization-registration', compact('memberTypes'));
    }

    /**
     * Traiter l'inscription d'organisation
     */
    public function register(Request $request)
    {
        $request->validate([
            // Informations de l'organisation
            'organization_name' => 'required|string|max:255',
            'business_number' => 'required|string|unique:organizations,business_number|max:255',
            'contact_person' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:255',
            'address' => 'required|string',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|max:255',
            
            // Code de parrainage (obligatoire)
            'sponsorship_code' => 'required|string|exists:sponsorships,sponsorship_code',
            'payment_method' => 'required|in:stripe,bank_transfer',
            
            // Membres de l'organisation
            'members' => 'required|array|min:1',
            'members.*.first_name' => 'required|string|max:255',
            'members.*.last_name' => 'required|string|max:255',
            'members.*.birth_date' => 'required|date|before:today',
            'members.*.phone' => 'required|string|max:255',
            'members.*.email' => 'required|email|max:255',
            'members.*.address' => 'required|string',
            'members.*.city' => 'required|string|max:255',
            'members.*.province' => 'required|string|max:255',
            'members.*.postal_code' => 'required|string|max:10',
            'members.*.country' => 'required|string|max:255',
            'members.*.canadian_status_proof' => 'required|string|max:255',
            'members.*.member_type_id' => 'required|exists:member_types,id',
            'members.*.pin_code' => 'required|string|min:4|max:6',
        ], [
            'organization_name.required' => 'Le nom de l\'organisation est requis.',
            'business_number.required' => 'Le numéro d\'entreprise est requis.',
            'business_number.unique' => 'Ce numéro d\'entreprise est déjà utilisé.',
            'contact_person.required' => 'La personne contact est requise.',
            'contact_email.required' => 'L\'email de contact est requis.',
            'contact_phone.required' => 'Le téléphone de contact est requis.',
            'sponsorship_code.required' => 'Le code de parrainage est requis.',
            'sponsorship_code.exists' => 'Le code de parrainage n\'est pas valide.',
            'payment_method.required' => 'La méthode de paiement est requise.',
            'members.required' => 'Au moins un membre doit être ajouté.',
            'members.min' => 'Au moins un membre doit être ajouté.',
        ]);

        // Vérifier le code de parrainage
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

        try {
            DB::beginTransaction();

            // Créer l'organisation
            $organization = Organization::create([
                'name' => $request->organization_name,
                'business_number' => $request->business_number,
                'contact_person' => $request->contact_person,
                'contact_email' => $request->contact_email,
                'contact_phone' => $request->contact_phone,
                'address' => $request->address,
                'city' => $request->city,
                'province' => $request->province,
                'postal_code' => $request->postal_code,
                'country' => $request->country,
                'member_count' => count($request->members),
                'is_active' => true,
            ]);

            // Calculer les frais totaux
            $totalFees = 0;
            $createdMembers = [];

            // Créer les membres
            foreach ($request->members as $memberData) {
                // Convertir la date de naissance
                $birthDate = $memberData['birth_date'];
                if (strpos($birthDate, '/') !== false) {
                    $dateParts = explode('/', $birthDate);
                    if (count($dateParts) === 3) {
                        $birthDate = $dateParts[2] . '-' . str_pad($dateParts[1], 2, '0', STR_PAD_LEFT) . '-' . str_pad($dateParts[0], 2, '0', STR_PAD_LEFT);
                    }
                }
                
                $age = Carbon::parse($birthDate)->age;
                $memberType = MemberType::find($memberData['member_type_id']);
                
                if (!$memberType->isValidAge($age)) {
                    return back()->withErrors([
                        'members' => "L'âge de {$memberData['first_name']} {$memberData['last_name']} ne correspond pas au type de membre sélectionné.",
                    ])->withInput();
                }

                $member = Member::create([
                    'member_number' => Member::generateMemberNumber(),
                    'pin_code' => $memberData['pin_code'],
                    'first_name' => $memberData['first_name'],
                    'last_name' => $memberData['last_name'],
                    'birth_date' => $birthDate,
                    'phone' => $memberData['phone'],
                    'email' => $memberData['email'],
                    'address' => $memberData['address'],
                    'city' => $memberData['city'],
                    'province' => $memberData['province'],
                    'postal_code' => $memberData['postal_code'],
                    'country' => $memberData['country'],
                    'canadian_status_proof' => $memberData['canadian_status_proof'],
                    'member_type_id' => $memberData['member_type_id'],
                    'organization_id' => $organization->id,
                    'sponsor_id' => $sponsor->id,
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

                // Calculer les frais pour ce membre
                $memberFees = $memberType->adhesion_fee + $memberType->death_contribution;
                $totalFees += $memberFees;

                // Créer le paiement pour ce membre
                Payment::create([
                    'member_id' => $member->id,
                    'membership_id' => $membership->id,
                    'type' => 'adhesion',
                    'amount' => $memberFees,
                    'currency' => 'CAD',
                    'status' => 'pending',
                    'payment_method' => $request->payment_method,
                    'description' => 'Paiement d\'adhésion organisation - ' . $member->full_name,
                ]);

                $createdMembers[] = $member;

                // Envoyer notification de bienvenue
                $member->notify(new WelcomeMemberNotification($member));
            }

            // Mettre à jour les frais totaux de l'organisation
            $organization->update([
                'total_fees' => $totalFees,
            ]);

            // Marquer le parrainage comme confirmé
            $sponsorship->confirm();
            
            // Notifier le parrain
            $sponsor->notify(new SponsorshipUsedNotification($sponsorship, $createdMembers[0]));

            // Notifier l'organisation
            $organization->notify(new OrganizationWelcomeNotification($organization, $createdMembers));

            DB::commit();

            return redirect()->route('public.organization-registration.success')
                ->with('success', 'Organisation enregistrée avec succès !')
                ->with('organization', $organization)
                ->with('members', $createdMembers);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->withErrors([
                'error' => 'Une erreur est survenue lors de l\'enregistrement. Veuillez réessayer.',
            ])->withInput();
        }
    }

    /**
     * Page de succès
     */
    public function success()
    {
        $organization = session('organization');
        $members = session('members', []);
        
        if (!$organization) {
            return redirect()->route('public.organization-registration.form');
        }

        return view('public.organization-registration-success', compact('organization', 'members'));
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
                                ->first();

        return response()->json([
            'valid' => $sponsorship !== null,
            'sponsor' => $sponsorship ? $sponsorship->sponsor->full_name : null,
        ]);
    }
}
