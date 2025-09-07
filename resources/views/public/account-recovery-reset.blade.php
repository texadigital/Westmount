@extends('layouts.public')

@section('title', 'Réinitialiser le Code PIN - Association Westmount')
@section('description', 'Créez un nouveau code PIN pour votre compte membre.')
@section('content')
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="text-3xl font-bold text-gray-900 text-center">
            Association Westmount
        </h2>
        <p class="mt-2 text-sm text-gray-600 text-center">
            Réinitialisation du Code PIN
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form method="POST" action="{{ route('public.account-recovery.reset', $token) }}">
                @csrf
                <input type="hidden" name="email" value="{{ $member->email }}">
                
                @if($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Member Information -->
                <div class="mb-6">
                    <div class="bg-gray-50 rounded-md p-4">
                        <h3 class="text-sm font-medium text-gray-900 mb-2">Compte à réinitialiser</h3>
                        <p class="text-sm text-gray-600">
                            <strong>{{ $member->full_name }}</strong><br>
                            Numéro de membre: {{ $member->member_number }}<br>
                            Email: {{ $member->email }}
                        </p>
                    </div>
                </div>

                <!-- New PIN Form -->
                <div class="space-y-4">
                    <div>
                        <label for="pin_code" class="block text-sm font-medium text-gray-700">
                            Nouveau Code PIN *
                        </label>
                        <input id="pin_code" name="pin_code" type="password" required minlength="4" maxlength="6"
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('pin_code') border-red-300 @enderror"
                               placeholder="4 à 6 chiffres">
                        @error('pin_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">Le code PIN doit contenir entre 4 et 6 caractères.</p>
                    </div>

                    <div>
                        <label for="pin_code_confirmation" class="block text-sm font-medium text-gray-700">
                            Confirmer le Code PIN *
                        </label>
                        <input id="pin_code_confirmation" name="pin_code_confirmation" type="password" required minlength="4" maxlength="6"
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('pin_code_confirmation') border-red-300 @enderror"
                               placeholder="Répétez le code PIN">
                        @error('pin_code_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Security Tips -->
                <div class="mt-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-blue-800">
                                    Conseils de Sécurité
                                </h3>
                                <div class="mt-2 text-sm text-blue-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        <li>Choisissez un code PIN facile à retenir mais difficile à deviner</li>
                                        <li>Évitez les séquences simples (1234, 0000, etc.)</li>
                                        <li>Ne partagez jamais votre code PIN</li>
                                        <li>Changez régulièrement votre code PIN</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-6">
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Réinitialiser le Code PIN
                    </button>
                </div>

                <!-- Navigation Links -->
                <div class="mt-6 text-center">
                    <a href="{{ route('public.home') }}" class="text-sm text-blue-600 hover:text-blue-500">
                        Retour à l'accueil
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pinCode = document.getElementById('pin_code');
    const pinCodeConfirmation = document.getElementById('pin_code_confirmation');
    
    // Validation en temps réel
    function validatePin() {
        const value = pinCode.value;
        const isValid = value.length >= 4 && value.length <= 6 && /^\d+$/.test(value);
        
        if (value.length > 0) {
            if (isValid) {
                pinCode.classList.remove('border-red-300');
                pinCode.classList.add('border-green-300');
            } else {
                pinCode.classList.remove('border-green-300');
                pinCode.classList.add('border-red-300');
            }
        } else {
            pinCode.classList.remove('border-red-300', 'border-green-300');
        }
    }
    
    function validateConfirmation() {
        const value = pinCodeConfirmation.value;
        const pinValue = pinCode.value;
        
        if (value.length > 0) {
            if (value === pinValue) {
                pinCodeConfirmation.classList.remove('border-red-300');
                pinCodeConfirmation.classList.add('border-green-300');
            } else {
                pinCodeConfirmation.classList.remove('border-green-300');
                pinCodeConfirmation.classList.add('border-red-300');
            }
        } else {
            pinCodeConfirmation.classList.remove('border-red-300', 'border-green-300');
        }
    }
    
    pinCode.addEventListener('input', validatePin);
    pinCodeConfirmation.addEventListener('input', validateConfirmation);
    
    // Empêcher la saisie de caractères non numériques
    pinCode.addEventListener('keypress', function(e) {
        if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter'].includes(e.key)) {
            e.preventDefault();
        }
    });
    
    pinCodeConfirmation.addEventListener('keypress', function(e) {
        if (!/[0-9]/.test(e.key) && !['Backspace', 'Delete', 'Tab', 'Enter'].includes(e.key)) {
            e.preventDefault();
        }
    });
});
</script>
@endsection
