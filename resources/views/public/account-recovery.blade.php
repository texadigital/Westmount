@extends('layouts.public')

@section('title', 'Récupération de Compte - Association Westmount')
@section('description', 'Récupérez l\'accès à votre compte membre en réinitialisant votre code PIN.')
@section('content')
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="text-3xl font-bold text-gray-900 text-center">
            Association Westmount
        </h2>
        <p class="mt-2 text-sm text-gray-600 text-center">
            Récupération de Compte
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form method="POST" action="{{ route('public.account-recovery.request') }}">
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

                <div class="mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">
                                Récupération de Compte
                            </h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <p>Entrez votre numéro de membre et votre email pour recevoir un lien de réinitialisation de votre code PIN.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Numéro de membre -->
                    <div>
                        <label for="member_number" class="block text-sm font-medium text-gray-700">
                            Numéro de membre
                        </label>
                        <input id="member_number" name="member_number" type="text" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('member_number') border-red-300 @enderror"
                               placeholder="Ex: WM000001"
                               value="{{ old('member_number') }}">
                        @error('member_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Adresse email
                        </label>
                        <input id="email" name="email" type="email" required 
                               class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm @error('email') border-red-300 @enderror"
                               placeholder="votre@email.com"
                               value="{{ old('email') }}">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Bouton de soumission -->
                <div class="mt-6">
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Envoyer le Lien de Récupération
                    </button>
                </div>

                <!-- Liens de navigation -->
                <div class="mt-6 text-center space-y-2">
                    <a href="{{ route('member.login') }}" class="text-sm text-blue-600 hover:text-blue-500">
                        Se connecter
                    </a>
                    <span class="text-gray-300">•</span>
                    <a href="{{ route('public.home') }}" class="text-sm text-blue-600 hover:text-blue-500">
                        Retour à l'accueil
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
