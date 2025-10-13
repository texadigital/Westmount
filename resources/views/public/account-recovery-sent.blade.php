@extends('layouts.public')

@section('title', 'Email Envoyé - Association Westmount')
@section('description', 'Un email de récupération a été envoyé à votre adresse.')
@section('content')
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <!-- Success Icon -->
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h2 class="mt-6 text-3xl font-bold text-gray-900">
                    Email Envoyé !
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Un lien de récupération a été envoyé à votre adresse email
                </p>
            </div>

            <!-- Instructions -->
            <div class="mt-8">
                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-blue-800">
                                Prochaines Étapes
                            </h3>
                            <div class="mt-2 text-sm text-blue-700">
                                <ul class="list-disc list-inside space-y-1">
                                    <li>Vérifiez votre boîte email (et le dossier spam)</li>
                                    <li>Cliquez sur le lien dans l'email reçu</li>
                                    <li>Créez un nouveau code PIN sécurisé</li>
                                    <li>Connectez-vous avec votre nouveau code PIN</li>
                                </ul>
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
                                <p>Le lien de récupération est valide pendant 24 heures. Si vous ne recevez pas l'email dans les 5 minutes, vérifiez votre dossier spam ou contactez l'administration.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex flex-col space-y-3">
                <a href="{{ route('member.login') }}" 
                   class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Se Connecter
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
                            514-566-4029
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
