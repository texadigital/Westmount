@extends('layouts.public')

@section('title', 'Association Westmount - Entraide et Solidarité')
@section('description', 'Rejoignez l\'Association Westmount, une communauté d\'entraide et de solidarité qui soutient ses membres dans les moments difficiles.')

@section('content')
    <!-- Hero Section with Swiper Slider -->
    <section id="home" class="relative text-white">
        <!-- Slider -->
        <div class="swiper hero-swiper h-[420px] md:h-[560px] z-0">
            <div class="swiper-wrapper">
                <!-- Slide 1 (community/solidarity) -->
                <div class="swiper-slide">
                    <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=1920&q=80" alt="Communauté solidaire" class="w-full h-full object-cover" />
                </div>
                <!-- Slide 2 (support/helping hands) -->
                <div class="swiper-slide">
                    <img src="https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=1920&q=80" alt="Soutien et entraide" class="w-full h-full object-cover" />
                </div>
                <!-- Slide 3 (family/comfort) -->
                <div class="swiper-slide">
                    <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?auto=format&fit=crop&w=1920&q=80" alt="Famille et compassion" class="w-full h-full object-cover" />
                </div>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>

        <!-- Overlay gradient for readability -->
        <div class="absolute inset-0 bg-gradient-to-r from-primary/80 to-black/60 z-10"></div>

        <!-- Overlay content -->
        <div class="absolute inset-0 flex items-center z-20">
            <div class="max-w-7xl mx-auto w-full px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6" style="text-shadow: 0 2px 6px rgba(0,0,0,.6)">
                        Solidarité & Entraide
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto" style="text-shadow: 0 1px 4px rgba(0,0,0,.6)">
                        L'Association Westmount Canada est une communauté solidaire et d'entraide qui vise à apporter un soutien à la famille d'un membre décédé. Ce soutien inclut notamment une aide financière pour aider la famille à faire face aux défis quotidiens.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('public.registration.form') }}" 
                           class="bg-white text-primary hover:bg-gray-100 font-bold py-3 px-8 rounded-lg text-lg transition duration-300 transform hover:scale-105">
                            <i class="fas fa-user-plus mr-2"></i>
                            Rejoindre l'Association
                        </a>
                        <a href="{{ route('member.login') }}" 
                           class="border-2 border-white text-white hover:bg-white hover:text-primary font-bold py-3 px-8 rounded-lg text-lg transition duration-300">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Connexion
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="text-3xl font-bold text-primary mb-2">{{ $stats['total_members'] ?? '500+' }}</div>
                    <div class="text-gray-600">Membres actifs</div>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="text-3xl font-bold text-secondary mb-2">${{ number_format($stats['total_funds'] ?? 50000, 0) }}</div>
                    <div class="text-gray-600">Fonds disponibles</div>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="text-3xl font-bold text-accent mb-2">{{ $stats['years_active'] ?? '1' }}</div>
                    <div class="text-gray-600">An d'expérience</div>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="text-3xl font-bold text-primary mb-2">100%</div>
                    <div class="text-gray-600">Transparence</div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">À propos de nous</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                   
                </p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Notre Mission</h3>
                    <p class="text-gray-600 mb-6">
                    Nous offrons un soutien financier et moral à nos membres lors du décès d'un proche. Notre système de contributions permet d’optimiser l’entraide dans les moments les plus difficiles.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <i class="fas fa-heart text-accent mt-1 mr-3"></i>
                            <div>
                                <h4 class="font-semibold text-gray-900">Solidarité</h4>
                                <p class="text-gray-600">Nous nous soutenons mutuellement dans les épreuves</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-shield-alt text-primary mt-1 mr-3"></i>
                            <div>
                                <h4 class="font-semibold text-gray-900">Sécurité</h4>
                                <p class="text-gray-600">Protection financière pour vous et vos proches</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-handshake text-secondary mt-1 mr-3"></i>
                            <div>
                                <h4 class="font-semibold text-gray-900">Communauté</h4>
                                <p class="text-gray-600">Une famille élargie qui se soucie de ses membres</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Comment ça marche ?</h3>
                    <div class="space-y-6">
                        <div class="flex items-center">
                            <div class="bg-primary text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4">1</div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Adhésion</h4>
                                <p class="text-gray-600">Rejoignez notre communauté avec une cotisation unique</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-primary text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4">2</div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Contributions</h4>
                                <p class="text-gray-600">Participez aux contributions lors des décès</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="bg-primary text-white rounded-full w-8 h-8 flex items-center justify-center font-bold mr-4">3</div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Soutien</h4>
                                <p class="text-gray-600">Recevez un soutien financier en cas de besoin</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Nos Services</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Nous offrons une gamme complète de services pour soutenir nos membres
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-gray-50 p-8 rounded-lg text-center hover:shadow-lg transition duration-300">
                    <div class="bg-primary text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-dollar-sign text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Contributions Décès</h3>
                    <p class="text-gray-600 mb-6">
                        Soutien financier immédiat lors du décès d'un proche.
                    </p>
                    <ul class="text-left text-sm text-gray-600 space-y-2">
                        <li>• Montant Contribution selon le type de membre décédé.</li>
                        <li>• Paiement rapide et sécurisé</li>
                        <li>• Processus simplifié</li>
                    </ul>
                </div>
                
                <div class="bg-gray-50 p-8 rounded-lg text-center hover:shadow-lg transition duration-300">
                    <div class="bg-secondary text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Système de Parrainage</h3>
                    <p class="text-gray-600 mb-6">
                        Invitez vos proches à rejoindre notre communauté et bénéficiez d'avantages
                    </p>
                    <ul class="text-left text-sm text-gray-600 space-y-2">
                        <li>• Codes de parrainage uniques</li>
                        <li>• Suivi des filleuls</li>
                        <li>• Récompenses pour les parrains</li>
                    </ul>
                </div>
                
                <div class="bg-gray-50 p-8 rounded-lg text-center hover:shadow-lg transition duration-300">
                    <div class="bg-accent text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-mobile-alt text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Gestion en Ligne</h3>
                    <p class="text-gray-600 mb-6">
                        Accédez à votre espace membre 24/7 pour gérer vos paiements et contributions
                    </p>
                    <ul class="text-left text-sm text-gray-600 space-y-2">
                        <li>• Tableau de bord personnel</li>
                        <li>• Historique des paiements</li>
                        <li>• Notifications automatiques</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Membership Types -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Types d'Adhésions</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Choisissez le type d'adhésion qui correspond à votre situation
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($memberTypes ?? [] as $type)
                <div class="bg-white p-6 rounded-lg shadow-lg hover:shadow-xl transition duration-300 {{ $type->name === 'Régulier' ? 'ring-2 ring-primary' : '' }}">
                    @if($type->name === 'Régulier')
                    <div class="bg-primary text-white text-xs font-bold px-3 py-1 rounded-full inline-block mb-4">Populaire</div>
                    @endif
                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $type->name }}</h3>
                    <div class="text-3xl font-bold text-primary mb-4">${{ number_format($type->adhesion_fee, 0) }}</div>
                    <p class="text-gray-600 mb-6">{{ $type->description ?? 'Adhésion standard' }}</p>
                    <ul class="text-sm text-gray-600 space-y-2 mb-6">
                        <li>• Contribution: ${{ number_format($type->death_contribution, 0) }} </li>
                        <li>• Accès complet aux services</li>
                        <li>• Support 24/7</li>
                    </ul>
                    <a href="{{ route('public.registration.form') }}" 
                       class="w-full bg-primary text-white hover:bg-blue-700 font-bold py-2 px-4 rounded-lg text-center block transition duration-300">
                        S'inscrire
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Lapsed Members Section -->
    <section class="py-20 bg-yellow-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Membre Caduc ?</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Réactivez votre adhésion facilement avec votre code de réactivation
                </p>
            </div>
            
            <div class="max-w-md mx-auto">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Réactivation d'adhésion</h3>
                        <p class="text-gray-600 mb-6">
                            Utilisez le code de réactivation qui vous a été envoyé pour réactiver votre adhésion.
                        </p>
                        <a href="{{ route('public.reactivation.form') }}" 
                           class="w-full bg-yellow-600 text-white hover:bg-yellow-700 font-bold py-2 px-4 rounded-lg text-center block transition duration-300">
                            Réactiver mon adhésion
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Organization Registration Section -->
    <section class="py-20 bg-green-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Organisation ou Association ?</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Inscrivez votre organisation et bénéficiez de tarifs préférentiels pour vos membres
                </p>
            </div>
            
            <div class="max-w-4xl mx-auto">
                <div class="bg-white p-8 rounded-lg shadow-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 mb-4">Avantages pour les Organisations</h3>
                            <ul class="space-y-3 text-gray-600">
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-green-500 mt-1 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Frais d'adhésion : 50$ × nombre de membres
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-green-500 mt-1 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Gestion centralisée des membres
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-green-500 mt-1 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Rapports détaillés pour l'organisation
                                </li>
                                <li class="flex items-start">
                                    <svg class="h-5 w-5 text-green-500 mt-1 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Support dédié aux organisations
                                </li>
                            </ul>
                        </div>
                        <div class="text-center">
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-6">
                                <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Inscription d'Organisation</h3>
                            <p class="text-gray-600 mb-6">
                                Inscrivez votre organisation et ajoutez tous vos membres en une seule fois.
                            </p>
                            <a href="{{ route('public.organization-registration.form') }}" 
                               class="w-full bg-green-600 text-white hover:bg-green-700 font-bold py-3 px-6 rounded-lg text-center block transition duration-300">
                                Inscrire mon Organisation
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Contactez-nous</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Nous sommes là pour répondre à vos questions et vous accompagner
                </p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Informations de contact</h3>
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <i class="fas fa-map-marker-alt text-primary mt-1 mr-4"></i>
                            <div>
                                <h4 class="font-semibold text-gray-900">Adresse</h4>
                                <p class="text-gray-600">573 Pierre-Dugua-De Mons, L'Assomption, QC J5W 0E3<br>CANADA</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-phone text-primary mt-1 mr-4"></i>
                            <div>
                                <h4 class="font-semibold text-gray-900">Téléphone</h4>
                                <p class="text-gray-600">
                                    <a href="tel:+15141234567" class="hover:text-primary">514-566-4029</a><br>
                                    <span class="text-sm text-gray-500">Lun - Ven: 9h00 - 17h00</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-envelope text-primary mt-1 mr-4"></i>
                            <div>
                                <h4 class="font-semibold text-gray-900">Email</h4>
                                <p class="text-gray-600">
                                    <a href="mailto:contact@associationwestmount.com" class="hover:text-primary">info@associationwestmount.com</a><br>
                                    <span class="text-sm text-gray-500">Réponse sous 24h</span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-clock text-primary mt-1 mr-4"></i>
                            <div>
                                <h4 class="font-semibold text-gray-900">Heures d'ouverture</h4>
                                <div class="text-gray-600">
                                    <p><strong>Lundi - Vendredi:</strong> 9h00 - 17h00</p>
                                    <p><strong>Samedi:</strong> 9h00 - 13h00</p>
                                    <p><strong>Dimanche:</strong> Fermé</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-8 rounded-lg">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Envoyez-nous un message</h3>
                    <form class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nom complet</label>
                            <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sujet</label>
                            <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                            <textarea rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
                        </div>
                        <button type="submit" class="w-full bg-primary text-white hover:bg-blue-700 font-bold py-3 px-4 rounded-lg transition duration-300">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Envoyer le message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Swiper('.hero-swiper', {
                loop: true,
                effect: 'fade',
                speed: 800,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        });
    </script>
    @endpush

@endsection
