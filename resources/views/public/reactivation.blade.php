@extends('layouts.public')

@section('title', 'Réactivation - Association Westmount')
@section('description', 'Réactivez votre adhésion à l\'Association Westmount avec votre code de réactivation.')
@section('content')
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="text-3xl font-bold text-gray-900 text-center">
                Association Westmount
            </h2>
            <p class="mt-2 text-sm text-gray-600 text-center">
                Réactivation de votre adhésion
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-2xl">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <form method="POST" action="{{ route('public.reactivation.reactivate') }}">
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

                    <!-- Code de réactivation -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Code de réactivation</h3>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">
                                        Réactivation d'adhésion
                                    </h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>Réactivez facilement votre adhésion par votre numéro de membre.</p>
                                        <p class="mt-2">Vous n'avez pas de code ? <a href="{{ route('public.contact') }}" class="underline">Contactez-nous</a> pour en obtenir un.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <label for="lapsed_code" class="block text-sm font-medium text-gray-700">Code de réactivation *</label>
                            <input type="text" name="lapsed_code" id="lapsed_code" 
                                   value="{{ old('lapsed_code') }}"
                                   placeholder="Entrez votre code de réactivation"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm @error('lapsed_code') border-red-300 @enderror"
                                   required>
                            @error('lapsed_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Ce code a été envoyé à votre adresse email lorsque votre adhésion est devenue caduque.</p>
                        </div>
                    </div>

                    <!-- Méthode de paiement -->
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Méthode de paiement</h3>
                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Choisissez votre méthode de paiement *</label>
                            <div class="mt-2 space-y-3">
                                <div class="flex items-center">
                                    <input id="payment_stripe" name="payment_method" type="radio" value="stripe" 
                                           {{ old('payment_method') == 'stripe' ? 'checked' : '' }}
                                           class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                    <label for="payment_stripe" class="ml-3 block text-sm font-medium text-gray-700">
                                        <span class="flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M13.976 9.15c-2.172-.806-3.356-1.426-3.356-2.409 0-.831.683-1.305 1.901-1.305 2.227 0 4.515.858 6.09 1.631l.89-5.494C18.252.274 15.697 0 12.165 0 9.667 0 7.589.654 6.104 1.872 4.56 3.147 3.757 4.992 3.757 7.218c0 4.039 2.467 5.76 6.476 7.219 2.585.92 3.445 1.574 3.445 2.583 0 .98-.84 1.386-2.399 1.386-1.87 0-4.807-.921-6.47-1.797l-.89 5.494C5.713 22.99 8.426 24 11.564 24c2.508 0 4.47-.654 5.89-1.818 1.498-1.188 2.266-3.041 2.266-5.282 0-4.128-2.524-5.851-6.744-7.75z"/>
                                            </svg>
                                            Carte de crédit (Stripe)
                                        </span>
                                        <span class="text-xs text-gray-500">Paiement sécurisé en ligne</span>
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input id="payment_bank" name="payment_method" type="radio" value="bank_transfer" 
                                           {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}
                                           class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                    <label for="payment_bank" class="ml-3 block text-sm font-medium text-gray-700">
                                        <span class="flex items-center">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                            </svg>
                                            Virement bancaire
                                        </span>
                                        <span class="text-xs text-gray-500">Instructions de paiement par email</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bouton de réactivation -->
                    <div class="flex justify-end">
                        <button type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                            Réactiver mon adhésion
                        </button>
                    </div>
                </form>

                <!-- Bouton de connexion -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600 mb-4">
                        Nouveau membre ?
                    </p>
                    <a href="{{ route('public.registration.form') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        S'inscrire
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
