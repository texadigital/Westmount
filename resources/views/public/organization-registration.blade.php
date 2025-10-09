@extends('layouts.public')

@section('title', 'Inscription Organisation - Association Westmount')
@section('description', 'Inscrivez votre organisation à l\'Association Westmount et bénéficiez de tarifs préférentiels.')
@section('content')
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="text-3xl font-bold text-gray-900 text-center">
            Association Westmount
        </h2>
        <p class="mt-2 text-sm text-gray-600 text-center">
            Inscription d'Organisation
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-4xl">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded-md">
                <p class="text-blue-800 text-sm">
                    Pour l’adhésion d’une organisation: <strong>Coût unitaire</strong> x <strong>Nombre de membres</strong><br>
                    Soit <strong>${{ number_format($adhesionUnitFee, 0) }} CAD</strong> x nombre de membres
                </p>
            </div>
            <form method="POST" action="{{ route('public.organization-registration.register') }}" id="organizationForm">
                @csrf
                
                @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Informations de l'organisation -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informations de l'Organisation</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="organization_name" class="block text-sm font-medium text-gray-700">
                                Nom de l'Organisation *
                            </label>
                            <input id="organization_name" name="organization_name" type="text" required 
                                   class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('organization_name') border-red-300 @enderror"
                                   placeholder="Nom de votre organisation"
                                   value="{{ old('organization_name') }}">
                            @error('organization_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="business_number" class="block text-sm font-medium text-gray-700">
                                Numéro d'Entreprise *
                            </label>
                            <input id="business_number" name="business_number" type="text" required 
                                   class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('business_number') border-red-300 @enderror"
                                   placeholder="123456789"
                                   value="{{ old('business_number') }}">
                            @error('business_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_person" class="block text-sm font-medium text-gray-700">
                                Personne Contact *
                            </label>
                            <input id="contact_person" name="contact_person" type="text" required 
                                   class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('contact_person') border-red-300 @enderror"
                                   placeholder="Nom complet"
                                   value="{{ old('contact_person') }}">
                            @error('contact_person')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700">
                                Email de Contact *
                            </label>
                            <input id="contact_email" name="contact_email" type="email" required 
                                   class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('contact_email') border-red-300 @enderror"
                                   placeholder="contact@organisation.com"
                                   value="{{ old('contact_email') }}">
                            @error('contact_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="contact_phone" class="block text-sm font-medium text-gray-700">
                                Téléphone de Contact *
                            </label>
                            <input id="contact_phone" name="contact_phone" type="tel" required 
                                   class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('contact_phone') border-red-300 @enderror"
                                   placeholder="(514) 123-4567"
                                   value="{{ old('contact_phone') }}">
                            @error('contact_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Adresse de l'organisation -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Adresse de l'Organisation</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700">
                                Adresse *
                            </label>
                            <input id="address" name="address" type="text" required 
                                   class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('address') border-red-300 @enderror"
                                   placeholder="123 Rue de l'Organisation"
                                   value="{{ old('address') }}">
                            @error('address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700">
                                Ville *
                            </label>
                            <input id="city" name="city" type="text" required 
                                   class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('city') border-red-300 @enderror"
                                   placeholder="Montréal"
                                   value="{{ old('city') }}">
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="province" class="block text-sm font-medium text-gray-700">
                                Province *
                            </label>
                            <select id="province" name="province" required 
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('province') border-red-300 @enderror">
                                <option value="">Sélectionner une province</option>
                                <option value="QC" {{ old('province') == 'QC' ? 'selected' : '' }}>Québec</option>
                                <option value="ON" {{ old('province') == 'ON' ? 'selected' : '' }}>Ontario</option>
                                <option value="BC" {{ old('province') == 'BC' ? 'selected' : '' }}>Colombie-Britannique</option>
                                <option value="AB" {{ old('province') == 'AB' ? 'selected' : '' }}>Alberta</option>
                                <option value="MB" {{ old('province') == 'MB' ? 'selected' : '' }}>Manitoba</option>
                                <option value="SK" {{ old('province') == 'SK' ? 'selected' : '' }}>Saskatchewan</option>
                                <option value="NS" {{ old('province') == 'NS' ? 'selected' : '' }}>Nouvelle-Écosse</option>
                                <option value="NB" {{ old('province') == 'NB' ? 'selected' : '' }}>Nouveau-Brunswick</option>
                                <option value="NL" {{ old('province') == 'NL' ? 'selected' : '' }}>Terre-Neuve-et-Labrador</option>
                                <option value="PE" {{ old('province') == 'PE' ? 'selected' : '' }}>Île-du-Prince-Édouard</option>
                                <option value="YT" {{ old('province') == 'YT' ? 'selected' : '' }}>Yukon</option>
                                <option value="NT" {{ old('province') == 'NT' ? 'selected' : '' }}>Territoires du Nord-Ouest</option>
                                <option value="NU" {{ old('province') == 'NU' ? 'selected' : '' }}>Nunavut</option>
                            </select>
                            @error('province')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700">
                                Code Postal *
                            </label>
                            <input id="postal_code" name="postal_code" type="text" required 
                                   class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('postal_code') border-red-300 @enderror"
                                   placeholder="H1A 1A1"
                                   value="{{ old('postal_code') }}">
                            @error('postal_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700">
                                Pays *
                            </label>
                            <input id="country" name="country" type="text" required 
                                   class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('country') border-red-300 @enderror"
                                   placeholder="Canada"
                                   value="{{ old('country', 'Canada') }}">
                            @error('country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Code de parrainage -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Code de Parrainage</h3>
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    Inscription sur Invitation
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <p>L'inscription d'organisations nécessite un code de parrainage valide. Contactez un membre actif pour obtenir un code.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="sponsorship_code" class="block text-sm font-medium text-gray-700">
                            Code de Parrainage *
                        </label>
                        <input id="sponsorship_code" name="sponsorship_code" type="text" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('sponsorship_code') border-red-300 @enderror"
                               placeholder="Entrez le code de parrainage"
                               value="{{ old('sponsorship_code') }}">
                        @error('sponsorship_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <div id="sponsorship-feedback" class="mt-2 text-sm"></div>
                    </div>
                </div>

                <!-- Méthode de paiement -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Méthode de Paiement</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input id="stripe" name="payment_method" type="radio" value="stripe" 
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" 
                                   {{ old('payment_method', 'stripe') == 'stripe' ? 'checked' : '' }}>
                            <label for="stripe" class="ml-3 block text-sm font-medium text-gray-700">
                                Paiement en ligne (Stripe) - Recommandé
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="bank_transfer" name="payment_method" type="radio" value="bank_transfer" 
                                   class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300" 
                                   {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}>
                            <label for="bank_transfer" class="ml-3 block text-sm font-medium text-gray-700">
                                Virement bancaire
                            </label>
                        </div>
                    </div>
                    @error('payment_method')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Membres de l'organisation -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Membres de l'Organisation</h3>
                    <div class="mb-4 text-sm text-gray-700">
                        Membres ajoutés: <span id="member-count" class="font-semibold">0</span>
                        · Estimation totale: <span id="estimated-total" class="font-semibold">$0 CAD</span>
                    </div>
                    <div id="members-container">
                        <!-- Les membres seront ajoutés dynamiquement -->
                    </div>
                    <button type="button" id="add-member" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Ajouter un Membre
                    </button>
                </div>

                <!-- Bouton de soumission -->
                <div class="flex items-center justify-between">
                    <a href="{{ route('public.home') }}" class="text-sm text-gray-600 hover:text-gray-500">
                        Retour à l'accueil
                    </a>
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Enregistrer l'Organisation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let memberCount = 0;
    const membersContainer = document.getElementById('members-container');
    const addMemberBtn = document.getElementById('add-member');
    const memberTypes = @json($memberTypes);
    const adhesionUnitFee = {{ (int) $adhesionUnitFee }};
    const memberCountEl = document.getElementById('member-count');
    const estimatedTotalEl = document.getElementById('estimated-total');

    function updateSummary() {
        const forms = membersContainer.querySelectorAll('.member-form');
        memberCount = forms.length;
        memberCountEl.textContent = memberCount;
        const total = adhesionUnitFee * memberCount;
        estimatedTotalEl.textContent = `$${total.toLocaleString('en-CA')} CAD`;
    }

    // Fonction pour créer un formulaire de membre
    function createMemberForm() {
        memberCount++;
        const memberDiv = document.createElement('div');
        memberDiv.className = 'member-form border border-gray-200 rounded-lg p-6 mb-4';
        memberDiv.innerHTML = `
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-md font-medium text-gray-900">Membre ${memberCount}</h4>
                <button type="button" class="remove-member text-red-600 hover:text-red-800">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Prénom *</label>
                    <input type="text" name="members[${memberCount}][first_name]" required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nom *</label>
                    <input type="text" name="members[${memberCount}][last_name]" required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date de naissance *</label>
                    <input type="date" name="members[${memberCount}][birth_date]" required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Téléphone *</label>
                    <input type="tel" name="members[${memberCount}][phone]" required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email *</label>
                    <input type="email" name="members[${memberCount}][email]" required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Type de Membre *</label>
                    <select name="members[${memberCount}][member_type_id]" required 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Sélectionner un type</option>
                        ${memberTypes.map(type => `<option value="${type.id}">${type.name} - $${type.adhesion_fee} (Contribution: $${type.death_contribution})</option>`).join('')}
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Code PIN *</label>
                    <input type="password" name="members[${memberCount}][pin_code]" required minlength="4" maxlength="6"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Preuve de Statut Canadien *</label>
                    <select name="members[${memberCount}][canadian_status_proof]" required 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Sélectionner</option>
                        <option value="citizen">Citoyen canadien</option>
                        <option value="permanent_resident">Résident permanent</option>
                        <option value="work_permit">Permis de travail</option>
                        <option value="student_permit">Permis d'études</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Adresse *</label>
                    <input type="text" name="members[${memberCount}][address]" required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ville *</label>
                    <input type="text" name="members[${memberCount}][city]" required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Province *</label>
                    <select name="members[${memberCount}][province]" required 
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        <option value="">Sélectionner</option>
                        <option value="QC">Québec</option>
                        <option value="ON">Ontario</option>
                        <option value="BC">Colombie-Britannique</option>
                        <option value="AB">Alberta</option>
                        <option value="MB">Manitoba</option>
                        <option value="SK">Saskatchewan</option>
                        <option value="NS">Nouvelle-Écosse</option>
                        <option value="NB">Nouveau-Brunswick</option>
                        <option value="NL">Terre-Neuve-et-Labrador</option>
                        <option value="PE">Île-du-Prince-Édouard</option>
                        <option value="YT">Yukon</option>
                        <option value="NT">Territoires du Nord-Ouest</option>
                        <option value="NU">Nunavut</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Code Postal *</label>
                    <input type="text" name="members[${memberCount}][postal_code]" required 
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pays *</label>
                    <input type="text" name="members[${memberCount}][country]" required value="Canada"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
            </div>
        `;
        
        membersContainer.appendChild(memberDiv);
        
        // Ajouter l'événement de suppression
        memberDiv.querySelector('.remove-member').addEventListener('click', function() {
            memberDiv.remove();
            updateSummary();
        });

        updateSummary();
    }

    // Ajouter le premier membre
    createMemberForm();

    // Événement pour ajouter un membre
    addMemberBtn.addEventListener('click', createMemberForm);

    // Vérification du code de parrainage
    const sponsorshipCodeInput = document.getElementById('sponsorship_code');
    const sponsorshipFeedback = document.getElementById('sponsorship-feedback');
    
    sponsorshipCodeInput.addEventListener('blur', function() {
        const code = this.value;
        if (code.length > 0) {
            fetch('{{ route("public.organization-registration.check-code") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ code: code })
            })
            .then(response => response.json())
            .then(data => {
                if (data.valid) {
                    sponsorshipFeedback.innerHTML = `<span class="text-green-600">✓ Code valide - Parrain: ${data.sponsor}</span>`;
                } else {
                    sponsorshipFeedback.innerHTML = `<span class="text-red-600">✗ Code invalide ou expiré</span>`;
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        } else {
            sponsorshipFeedback.innerHTML = '';
        }
    });
});
</script>
@endsection
