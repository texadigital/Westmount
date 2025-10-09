@extends('layouts.public')

@section('title', 'Inscription - Association Westmount')
@section('description', 'Rejoignez l\'Association Westmount en remplissant notre formulaire d\'inscription simple et sécurisé.')
@section('content')
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="text-3xl font-bold text-gray-900 text-center">
                Association Westmount
            </h2>
            <p class="mt-2 text-sm text-gray-600 text-center">
                Rejoignez notre communauté
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-2xl">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <form method="POST" action="{{ route('public.registration.register') }}">
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

                    <!-- Informations personnelles -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Informations personnelles</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700">Prénom *</label>
                                <input type="text" name="first_name" id="first_name" 
                                       value="{{ old('first_name') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       required>
                            </div>

                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700">Nom *</label>
                                <input type="text" name="last_name" id="last_name" 
                                       value="{{ old('last_name') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       required>
                            </div>

                            <div>
                                <label for="birth_date" class="block text-sm font-medium text-gray-700">Date de naissance *</label>
                                <input type="date" name="birth_date" id="birth_date" 
                                       value="{{ old('birth_date') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       required>
                            </div>

                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone *</label>
                                <input type="tel" name="phone" id="phone" 
                                       value="{{ old('phone') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       required>
                            </div>

                            <div class="md:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-700">Email *</label>
                                <input type="email" name="email" id="email" 
                                       value="{{ old('email') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       required>
                            </div>
                        </div>
                    </div>

                    <!-- Adresse -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Adresse</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700">Adresse *</label>
                                <input type="text" name="address" id="address" 
                                       value="{{ old('address') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       required>
                            </div>

                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700">Ville *</label>
                                <input type="text" name="city" id="city" 
                                       value="{{ old('city') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       required>
                            </div>

                            <div>
                                <label for="province" class="block text-sm font-medium text-gray-700">Province *</label>
                                <input type="text" name="province" id="province" 
                                       value="{{ old('province') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       required>
                            </div>

                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700">Code postal *</label>
                                <input type="text" name="postal_code" id="postal_code" 
                                       value="{{ old('postal_code') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       required>
                            </div>

                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700">Pays *</label>
                                <input type="text" name="country" id="country" 
                                       value="{{ old('country') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       required>
                            </div>

                            <div class="md:col-span-2">
                                <label for="canadian_status_proof" class="block text-sm font-medium text-gray-700">Preuve de statut canadien *</label>
                                <select name="canadian_status_proof" id="canadian_status_proof" 
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                        required>
                                    <option value="">Sélectionner une option</option>
                                    <option value="citizen" {{ old('canadian_status_proof') == 'citizen' ? 'selected' : '' }}>Citoyen canadien</option>
                                    <option value="permanent_resident" {{ old('canadian_status_proof') == 'permanent_resident' ? 'selected' : '' }}>Résident permanent</option>
                                    <option value="work_permit" {{ old('canadian_status_proof') == 'work_permit' ? 'selected' : '' }}>Permis de travail</option>
                                    <option value="study_permit" {{ old('canadian_status_proof') == 'study_permit' ? 'selected' : '' }}>Permis d'études</option>
                                    <option value="visitor" {{ old('canadian_status_proof') == 'visitor' ? 'selected' : '' }}>Visiteur</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Type de membre -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Type de membre</h3>
                        <div>
                            <label for="member_type_id" class="block text-sm font-medium text-gray-700">Type de membre *</label>
                            <select name="member_type_id" id="member_type_id" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    required>
                                <option value="">Sélectionner un type</option>
                                @foreach($memberTypes as $type)
                                    <option value="{{ $type->id }}" {{ old('member_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} - {{ number_format($type->adhesion_fee, 2) }} CAD
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Code de parrainage -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Code de parrainage</h3>
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">
                                        Inscription sur invitation uniquement
                                    </h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <p>Pour rejoindre l'Association Westmount, vous devez être parrainé par un membre existant. Demandez à votre parrain de vous fournir un code de parrainage.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label for="sponsorship_code" class="block text-sm font-medium text-gray-700">Code de parrainage *</label>
                            <input type="text" name="sponsorship_code" id="sponsorship_code" 
                                   value="{{ old('sponsorship_code') }}"
                                   placeholder="Entrez le code de parrainage fourni par votre parrain"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('sponsorship_code') border-red-300 @enderror"
                                   required>
                            @error('sponsorship_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Ce code vous a été fourni par un membre de l'association</p>
                        </div>
                    </div>

                    <!-- Méthode de paiement -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Méthode de paiement</h3>
                        <input type="hidden" name="payment_method" value="stripe">
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.683-1.305 1.901-1.305 2.227 0 4.515.858 6.09 1.631l.89-5.494C18.252.274 15.697 0 12.165 0 9.667 0 7.589.654 6.104 1.872 4.56 3.147 3.757 4.992 3.757 7.218c0 4.039 2.467 5.76 6.476 7.219 2.585.92 3.445 1.574 3.445 2.583 0 .98-.84 1.386-2.399 1.386-1.87 0-4.807-.921-6.47-1.797l-.89 5.494C5.713 22.99 8.426 24 11.564 24c2.508 0 4.47-.654 5.89-1.818 1.498-1.188 2.266-3.041 2.266-5.282 0-4.128-2.524-5.851-6.744-7.75z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium text-blue-800">Carte de crédit (Stripe) — Recommandé</p>
                                    <p class="text-xs text-blue-700">Paiement sécurisé en ligne en temps réel après l'inscription</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sécurité -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Sécurité</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="pin_code" class="block text-sm font-medium text-gray-700">Code PIN *</label>
                                <input type="password" name="pin_code" id="pin_code" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       minlength="4" maxlength="6" required>
                                <p class="mt-1 text-sm text-gray-500">4 à 6 chiffres</p>
                            </div>

                            <div>
                                <label for="pin_code_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le code PIN *</label>
                                <input type="password" name="pin_code_confirmation" id="pin_code_confirmation" 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                       minlength="4" maxlength="6" required>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton d'enregistrement -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            S'inscrire
                        </button>
                    </div>
                </form>

                <!-- Bouton de connexion -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600 mb-4">
                        Déjà membre ?
                    </p>
                    <a href="{{ route('member.login') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        Se connecter
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
