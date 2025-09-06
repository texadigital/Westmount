@extends('layouts.public')

@section('title', 'Connexion Membre - Association Westmount')
@section('description', 'Connectez-vous à votre espace membre pour gérer vos adhésions, paiements et contributions.')
@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo et titre -->
            <div class="text-center">
                <div class="mx-auto h-16 w-16 bg-blue-600 rounded-full flex items-center justify-center">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    Espace Membre
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Association d'entraide et de solidarité Westmount
                </p>
            </div>

            <!-- Messages d'erreur/succès -->
            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Formulaire de connexion -->
            <form class="mt-8 space-y-6" action="{{ route('member.login.post') }}" method="POST">
                @csrf
                
                <div class="space-y-4">
                    <!-- Numéro de membre -->
                    <div>
                        <label for="member_number" class="block text-sm font-medium text-gray-700">
                            Numéro de membre
                        </label>
                        <input id="member_number" name="member_number" type="text" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('member_number') border-red-300 @enderror"
                               placeholder="Ex: M001"
                               value="{{ old('member_number') }}">
                        @error('member_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Code PIN -->
                    <div>
                        <label for="pin_code" class="block text-sm font-medium text-gray-700">
                            Code PIN
                        </label>
                        <input id="pin_code" name="pin_code" type="password" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('pin_code') border-red-300 @enderror"
                               placeholder="Votre code PIN à 4 chiffres">
                        @error('pin_code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Bouton de connexion -->
                <div>
                    <button type="submit" 
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </span>
                        Se connecter
                    </button>
                </div>

                <!-- Bouton d'inscription -->
                <div class="text-center">
                    <p class="text-sm text-gray-600 mb-4">
                        Pas encore membre ?
                    </p>
                    <a href="{{ route('public.registration.form') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                        S'inscrire
                    </a>
                </div>

                <!-- Informations d'aide -->
                <div class="text-center">
                    <p class="text-xs text-gray-500">
                        Vous avez oublié votre code PIN ? Contactez-nous au 
                        <a href="tel:+15145551234" class="text-blue-600 hover:text-blue-500">(514) 555-1234</a>
                    </p>
                </div>
            </form>

            <!-- Lien vers l'administration -->
            <div class="text-center">
                <a href="{{ route('filament.admin.auth.login') }}" 
                   class="text-sm text-gray-500 hover:text-gray-700">
                    Accès administration
                </a>
            </div>
        </div>
    </div>
@endsection
