@extends('layouts.public')

@section('title', 'Système de Parrainage - Association Westmount')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Section (image + overlay) -->
    <section class="relative text-white">
        <div class="h-[320px] md:h-[420px] w-full">
            <img src="https://images.unsplash.com/photo-1526948128573-703ee1aeb6fa?auto=format&fit=crop&w=1920&q=80" alt="Parrainage et réseau" class="w-full h-full object-cover" />
        </div>
        <div class="absolute inset-0 bg-gradient-to-r from-primary/80 to-black/60"></div>
        <div class="absolute inset-0 flex items-center">
            <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6" style="text-shadow: 0 2px 6px rgba(0,0,0,.6)">Système de Parrainage</h1>
                    <p class="text-xl md:text-2xl max-w-3xl mx-auto" style="text-shadow: 0 1px 4px rgba(0,0,0,.6)">Rejoignez notre communauté grâce au parrainage</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Dynamic Content -->
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        @if($pageContent)
            <div class="prose prose-lg max-w-none">
                {!! $pageContent->content !!}
            </div>
        @else
            <!-- Default Content -->
            <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">
                <!-- How it works -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Comment ça fonctionne</h2>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold">1</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Demander un code</h3>
                                <p class="text-gray-600">Un membre existant peut demander un code de parrainage</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold">2</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Partager le code</h3>
                                <p class="text-gray-600">Le code est partagé avec la personne à parrainer</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold">3</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Inscription</h3>
                                <p class="text-gray-600">La nouvelle personne utilise le code lors de l'inscription</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold">4</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Avantages</h3>
                                <p class="text-gray-600">Le parrain et le filleul bénéficient d'avantages</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Benefits -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Avantages du Parrainage</h2>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Réduction sur les frais d'adhésion</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Accès prioritaire aux événements</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Support personnalisé</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Réseau de contacts étendu</span>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-gray-700">Programme de fidélité</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- How to get a code -->
            <div class="mt-16 bg-blue-50 rounded-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 text-center">Comment obtenir un code de parrainage</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Pour les membres existants</h3>
                        <p class="text-gray-600 mb-4">Connectez-vous à votre espace membre et demandez un code de parrainage dans la section dédiée.</p>
                        <a href="{{ route('member.login') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Se connecter
                        </a>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Pour les nouveaux membres</h3>
                        <p class="text-gray-600 mb-4">Demandez un code de parrainage à un membre existant ou contactez-nous pour plus d'informations.</p>
                        <a href="{{ route('public.contact') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-blue-600 bg-white hover:bg-blue-50">
                            Nous contacter
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection


