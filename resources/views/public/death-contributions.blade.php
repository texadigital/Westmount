@extends('layouts.public')

@section('title', 'Contributions Décès - Association Westmount')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                    Contributions Décès
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    Solidarité et soutien en cas de décès d'un membre
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
                <!-- Information Card -->
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
                                <h3 class="text-lg font-semibold text-gray-900">Cotisation mensuelle</h3>
                                <p class="text-gray-600">Chaque membre contribue mensuellement selon sa catégorie</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold">2</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Fonds de solidarité</h3>
                                <p class="text-gray-600">Les contributions sont versées dans un fonds de solidarité</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold">3</span>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Aide financière</h3>
                                <p class="text-gray-600">En cas de décès, la famille reçoit une aide financière</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rates Card -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Tarifs des Contributions</h2>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-gray-700">Membres réguliers</span>
                            <span class="text-lg font-semibold text-blue-600">10$ CAD</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-gray-700">Membres seniors</span>
                            <span class="text-lg font-semibold text-blue-600">2$ CAD</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-200">
                            <span class="text-gray-700">Membres juniors</span>
                            <span class="text-lg font-semibold text-blue-600">2$ CAD</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-700">Associations</span>
                            <span class="text-lg font-semibold text-blue-600">10$ CAD</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Benefits Section -->
            <div class="mt-16">
                <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Avantages du Système</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Solidarité</h3>
                        <p class="text-gray-600">Soutien mutuel entre membres en cas de difficultés</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Transparence</h3>
                        <p class="text-gray-600">Gestion transparente et traçable de tous les fonds</p>
                    </div>
                    <div class="text-center">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Sécurité</h3>
                        <p class="text-gray-600">Protection financière pour les familles des membres</p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

