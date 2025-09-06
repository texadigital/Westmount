@extends('layouts.public')

@section('title', 'Support Technique - Association Westmount')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                    Support Technique
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    Nous sommes là pour vous aider
                </p>
            </div>
        </div>
    </div>

    <!-- Dynamic Content -->
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        @if($pageContent)
            <div class="prose prose-lg max-w-none">
                {!! $pageContent->content !!}
            </div>
        @else
            <!-- Default Content -->
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                <!-- Contact Information -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Nous Contacter</h2>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Email</h3>
                                <p class="text-gray-600">support@westmount.ca</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Téléphone</h3>
                                <p class="text-gray-600">+1 (514) 555-0123</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Heures d'ouverture</h3>
                                <p class="text-gray-600">Lundi - Vendredi: 9h00 - 17h00</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Common Issues -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Problèmes Courants</h2>
                    <div class="space-y-4">
                        <div class="border-l-4 border-blue-500 pl-4">
                            <h3 class="text-lg font-semibold text-gray-900">Problème de connexion</h3>
                            <p class="text-gray-600">Vérifiez votre email et mot de passe, ou réinitialisez votre mot de passe.</p>
                        </div>
                        <div class="border-l-4 border-green-500 pl-4">
                            <h3 class="text-lg font-semibold text-gray-900">Paiement en attente</h3>
                            <p class="text-gray-600">Les paiements par virement peuvent prendre 1-3 jours ouvrables pour être confirmés.</p>
                        </div>
                        <div class="border-l-4 border-yellow-500 pl-4">
                            <h3 class="text-lg font-semibold text-gray-900">Documents manquants</h3>
                            <p class="text-gray-600">Assurez-vous d'avoir téléchargé tous les documents requis dans votre profil.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Support Channels -->
            <div class="mt-16">
                <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Canaux de Support</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Email Support</h3>
                        <p class="text-gray-600 mb-4">Réponse sous 24h en semaine</p>
                        <a href="mailto:support@westmount.ca" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Envoyer un email
                        </a>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Support Téléphonique</h3>
                        <p class="text-gray-600 mb-4">Lundi - Vendredi: 9h00 - 17h00</p>
                        <a href="tel:+15145550123" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            Appeler maintenant
                        </a>
                    </div>
                    <div class="bg-white rounded-lg shadow-md p-6 text-center">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">FAQ</h3>
                        <p class="text-gray-600 mb-4">Réponses aux questions fréquentes</p>
                        <a href="{{ route('public.faq') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700">
                            Consulter la FAQ
                        </a>
                    </div>
                </div>
            </div>

            <!-- Troubleshooting Guide -->
            <div class="mt-16 bg-gray-100 rounded-lg p-8">
                <h2 class="text-2xl font-bold text-center text-gray-900 mb-8">Guide de Dépannage</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Problèmes de Connexion</h3>
                        <ul class="space-y-2 text-gray-600">
                            <li>• Vérifiez que votre email est correct</li>
                            <li>• Assurez-vous que votre mot de passe est correct</li>
                            <li>• Videz le cache de votre navigateur</li>
                            <li>• Essayez un autre navigateur</li>
                            <li>• Contactez-nous si le problème persiste</li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Problèmes de Paiement</h3>
                        <ul class="space-y-2 text-gray-600">
                            <li>• Vérifiez les informations bancaires</li>
                            <li>• Assurez-vous d'avoir suffisamment de fonds</li>
                            <li>• Vérifiez que le virement est bien effectué</li>
                            <li>• Attendez 1-3 jours ouvrables</li>
                            <li>• Contactez votre banque si nécessaire</li>
                        </ul>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

