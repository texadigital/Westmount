@extends('layouts.public')

@section('title', 'Inscription Réussie - Association Westmount')
@section('description', 'Votre organisation a été enregistrée avec succès.')
@section('content')
<div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-2xl">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <!-- Success Icon -->
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="mt-6 text-3xl font-bold text-gray-900">
                    Inscription Réussie !
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Votre organisation a été enregistrée avec succès
                </p>
            </div>

            <!-- Organization Details -->
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Détails de l'Organisation</h3>
                <div class="bg-gray-50 rounded-lg p-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Nom de l'Organisation</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $organization->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Numéro d'Entreprise</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $organization->business_number }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Personne Contact</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $organization->contact_person }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email de Contact</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $organization->contact_email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Téléphone</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $organization->contact_phone }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Adresse</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $organization->full_address }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Members List -->
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Membres Enregistrés ({{ count($members) }})</h3>
                <div class="space-y-4">
                    @foreach($members as $member)
                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">{{ $member->full_name }}</h4>
                                <p class="text-sm text-gray-500">
                                    {{ $member->memberType->name }} • {{ $member->member_number }}
                                </p>
                                <p class="text-sm text-gray-500">{{ $member->email }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">
                                    ${{ number_format($member->memberType->adhesion_fee + $member->memberType->death_contribution, 2) }} CAD
                                </p>
                                <p class="text-xs text-gray-500">Frais d'adhésion</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Payment Information -->
            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Informations de Paiement</h3>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
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
                                    <li>Chaque membre recevra un email de bienvenue avec ses identifiants de connexion</li>
                                    <li>Les frais d'adhésion totalisent <strong>${{ number_format($organization->total_fees, 2) }} CAD</strong></li>
                                    <li>Les paiements peuvent être effectués via l'espace membre de chaque membre</li>
                                    <li>Un reçu sera envoyé après confirmation du paiement</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="mt-8 flex flex-col sm:flex-row gap-4">
                <a href="{{ route('public.home') }}" 
                   class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Retour à l'Accueil
                </a>
                <a href="{{ route('member.login') }}" 
                   class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Se Connecter
                </a>
            </div>

            <!-- Contact Information -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="text-center">
                    <h4 class="text-sm font-medium text-gray-900">Besoin d'Aide ?</h4>
                    <p class="mt-2 text-sm text-gray-600">
                        Pour toute question concernant votre inscription, n'hésitez pas à nous contacter.
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
