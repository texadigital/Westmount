@extends('layouts.public')

@section('title', 'Code PIN Réinitialisé - Association Westmount')
@section('description', 'Votre code PIN a été réinitialisé avec succès.')
@section('content')
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <!-- Success Icon -->
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="mt-6 text-3xl font-bold text-gray-900">
                    Code PIN Réinitialisé !
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Votre code PIN a été mis à jour avec succès
                </p>
            </div>

            <!-- Success Message -->
            <div class="mt-8">
                <div class="bg-green-50 border border-green-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-green-800">
                                Réinitialisation Réussie
                            </h3>
                            <div class="mt-2 text-sm text-green-700">
                                <p>Votre code PIN a été mis à jour avec succès. Vous pouvez maintenant vous connecter avec votre nouveau code PIN.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="mt-6">
                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">
                                Important
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Pour votre sécurité, nous vous recommandons de :</p>
                                <ul class="list-disc list-inside mt-2 space-y-1">
                                    <li>Garder votre code PIN confidentiel</li>
                                    <li>Ne pas le partager avec d'autres personnes</li>
                                    <li>Le changer régulièrement</li>
                                    <li>Déconnecter votre session après utilisation</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex flex-col space-y-3">
                <a href="{{ route('member.login') }}" 
                   class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Se Connecter Maintenant
                </a>
                <a href="{{ route('public.home') }}" 
                   class="w-full inline-flex justify-center items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Retour à l'Accueil
                </a>
            </div>

            <!-- Contact Information -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="text-center">
                    <h4 class="text-sm font-medium text-gray-900">Besoin d'Aide ?</h4>
                    <p class="mt-2 text-sm text-gray-600">
                        Si vous rencontrez des difficultés, contactez-nous.
                    </p>
                    <div class="mt-4 flex justify-center space-x-6">
                        <a href="mailto:contact@associationwestmount.com" class="text-sm text-blue-600 hover:text-blue-500">
                            contact@associationwestmount.com
                        </a>
                        <a href="tel:+15141234567" class="text-sm text-blue-600 hover:text-blue-500">
                            (514) 123-4567
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
