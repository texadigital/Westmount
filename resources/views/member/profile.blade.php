@extends('member.layouts.app')

@section('title', 'Mon profil')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mon profil</h1>
        <p class="mt-2 text-gray-600">Gérez vos informations personnelles et votre compte.</p>
    </div>

    <!-- Messages d'erreur/succès -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulaire de profil -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informations personnelles</h3>
        </div>
        
        <form action="{{ route('member.profile.update') }}" method="POST" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Prénom -->
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">Prénom</label>
                    <input type="text" name="first_name" id="first_name" 
                           value="{{ old('first_name', $member->first_name) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           required>
                </div>

                <!-- Nom -->
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Nom</label>
                    <input type="text" name="last_name" id="last_name" 
                           value="{{ old('last_name', $member->last_name) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           required>
                </div>

                <!-- Téléphone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Téléphone</label>
                    <input type="tel" name="phone" id="phone" 
                           value="{{ old('phone', $member->phone) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           required>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" 
                           value="{{ old('email', $member->email) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           required>
                </div>

                <!-- Adresse -->
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700">Adresse</label>
                    <textarea name="address" id="address" rows="3"
                              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                              required>{{ old('address', $member->address) }}</textarea>
                </div>

                <!-- Ville -->
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700">Ville</label>
                    <input type="text" name="city" id="city" 
                           value="{{ old('city', $member->city) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           required>
                </div>

                <!-- Province -->
                <div>
                    <label for="province" class="block text-sm font-medium text-gray-700">Province</label>
                    <select name="province" id="province" 
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required>
                        <option value="">Sélectionner une province</option>
                        <option value="Québec" {{ old('province', $member->province) === 'Québec' ? 'selected' : '' }}>Québec</option>
                        <option value="Ontario" {{ old('province', $member->province) === 'Ontario' ? 'selected' : '' }}>Ontario</option>
                        <option value="Colombie-Britannique" {{ old('province', $member->province) === 'Colombie-Britannique' ? 'selected' : '' }}>Colombie-Britannique</option>
                        <option value="Alberta" {{ old('province', $member->province) === 'Alberta' ? 'selected' : '' }}>Alberta</option>
                        <option value="Manitoba" {{ old('province', $member->province) === 'Manitoba' ? 'selected' : '' }}>Manitoba</option>
                        <option value="Saskatchewan" {{ old('province', $member->province) === 'Saskatchewan' ? 'selected' : '' }}>Saskatchewan</option>
                        <option value="Nouvelle-Écosse" {{ old('province', $member->province) === 'Nouvelle-Écosse' ? 'selected' : '' }}>Nouvelle-Écosse</option>
                        <option value="Nouveau-Brunswick" {{ old('province', $member->province) === 'Nouveau-Brunswick' ? 'selected' : '' }}>Nouveau-Brunswick</option>
                        <option value="Terre-Neuve-et-Labrador" {{ old('province', $member->province) === 'Terre-Neuve-et-Labrador' ? 'selected' : '' }}>Terre-Neuve-et-Labrador</option>
                        <option value="Île-du-Prince-Édouard" {{ old('province', $member->province) === 'Île-du-Prince-Édouard' ? 'selected' : '' }}>Île-du-Prince-Édouard</option>
                    </select>
                </div>

                <!-- Code postal -->
                <div>
                    <label for="postal_code" class="block text-sm font-medium text-gray-700">Code postal</label>
                    <input type="text" name="postal_code" id="postal_code" 
                           value="{{ old('postal_code', $member->postal_code) }}"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           required>
                </div>
            </div>

            <!-- Informations du compte -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h4 class="text-lg font-medium text-gray-900 mb-4">Informations du compte</h4>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Numéro de membre (lecture seule) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Numéro de membre</label>
                        <input type="text" value="{{ $member->member_number }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 sm:text-sm"
                               readonly>
                    </div>

                    <!-- Type de membre (lecture seule) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Type de membre</label>
                        <input type="text" value="{{ $member->memberType->name }}" 
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 sm:text-sm"
                               readonly>
                    </div>
                </div>

                <!-- Changement de code PIN -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h5 class="text-md font-medium text-gray-900 mb-4">Changer le code PIN</h5>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label for="current_pin" class="block text-sm font-medium text-gray-700">Code PIN actuel</label>
                            <input type="password" name="current_pin" id="current_pin" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Code PIN actuel">
                        </div>

                        <div>
                            <label for="new_pin" class="block text-sm font-medium text-gray-700">Nouveau code PIN</label>
                            <input type="password" name="new_pin" id="new_pin" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Nouveau code PIN">
                        </div>

                        <div>
                            <label for="new_pin_confirmation" class="block text-sm font-medium text-gray-700">Confirmer le nouveau code PIN</label>
                            <input type="password" name="new_pin_confirmation" id="new_pin_confirmation" 
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                   placeholder="Confirmer le nouveau code PIN">
                        </div>
                    </div>
                    
                    <p class="mt-2 text-sm text-gray-500">
                        Laissez vide si vous ne souhaitez pas changer votre code PIN.
                    </p>
                </div>
            </div>

            <!-- Boutons -->
            <div class="mt-8 flex justify-end">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                    Mettre à jour le profil
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
