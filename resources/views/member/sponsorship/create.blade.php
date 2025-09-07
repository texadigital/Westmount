@extends('member.layouts.app')

@section('title', 'Créer un Parrainage')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="flex items-center">
            <a href="{{ route('sponsorship.index') }}" 
               class="mr-4 inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Retour aux parrainages
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-900">Créer un Parrainage</h1>
        <p class="mt-2 text-gray-600">Générez un code de parrainage pour inviter quelqu'un à rejoindre l'association.</p>
    </div>

    <div class="max-w-2xl">
        <form action="{{ route('sponsorship.store') }}" method="POST" class="space-y-6">
            @csrf
            
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Informations du parrainé</h3>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Prénom -->
                        <div>
                            <label for="prospect_first_name" class="block text-sm font-medium text-gray-700">
                                Prénom <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="prospect_first_name" 
                                   id="prospect_first_name" 
                                   value="{{ old('prospect_first_name') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('prospect_first_name') border-red-300 @enderror"
                                   required>
                            @error('prospect_first_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nom -->
                        <div>
                            <label for="prospect_last_name" class="block text-sm font-medium text-gray-700">
                                Nom <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="prospect_last_name" 
                                   id="prospect_last_name" 
                                   value="{{ old('prospect_last_name') }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('prospect_last_name') border-red-300 @enderror"
                                   required>
                            @error('prospect_last_name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mt-6">
                        <label for="prospect_email" class="block text-sm font-medium text-gray-700">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="prospect_email" 
                               id="prospect_email" 
                               value="{{ old('prospect_email') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('prospect_email') border-red-300 @enderror"
                               required>
                        @error('prospect_email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-2 text-sm text-gray-500">L'email sera utilisé pour envoyer le code de parrainage.</p>
                    </div>

                    <!-- Téléphone -->
                    <div class="mt-6">
                        <label for="prospect_phone" class="block text-sm font-medium text-gray-700">
                            Téléphone <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" 
                               name="prospect_phone" 
                               id="prospect_phone" 
                               value="{{ old('prospect_phone') }}"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('prospect_phone') border-red-300 @enderror"
                               required>
                        @error('prospect_phone')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="mt-6">
                        <label for="notes" class="block text-sm font-medium text-gray-700">
                            Notes (optionnel)
                        </label>
                        <textarea name="notes" 
                                  id="notes" 
                                  rows="4"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('notes') border-red-300 @enderror"
                                  placeholder="Ajoutez des notes sur cette personne...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Informations sur le parrainage -->
            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Comment fonctionne le parrainage ?</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc list-inside space-y-1">
                                <li>Un code de parrainage unique sera généré automatiquement</li>
                                <li>Le code sera valide pendant 30 jours</li>
                                <li>La personne parrainée pourra s'inscrire avec ce code</li>
                                <li>Vous recevrez une notification quand l'inscription sera confirmée</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="flex justify-end space-x-3">
                <a href="{{ route('sponsorship.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Annuler
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Créer le parrainage
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
