@extends('layouts.public')

@section('title', 'Association Westmount - Entraide et Solidarité')
@section('description', 'Rejoignez l\'Association Westmount, une communauté d\'entraide et de solidarité qui soutient ses membres dans les moments difficiles.')

@section('content')
    <!-- Hero Section -->
    <section id="home" class="bg-gradient-to-r from-primary to-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    {{ $content['hero']->where('key', 'title')->first()->value ?? 'Solidarité & Entraide' }}
                </h1>
                <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">
                    {{ $content['hero']->where('key', 'description')->first()->value ?? 'Rejoignez une communauté qui se soutient mutuellement dans les moments difficiles. Ensemble, nous sommes plus forts.' }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('register') }}" 
                           class="bg-white text-primary hover:bg-gray-100 font-bold py-3 px-8 rounded-lg text-lg transition duration-300 transform hover:scale-105">
                            <i class="fas fa-user-plus mr-2"></i>
                            Rejoindre l'Association
                        </a>
                    <a href="#about" 
                       class="border-2 border-white text-white hover:bg-white hover:text-primary font-bold py-3 px-8 rounded-lg text-lg transition duration-300">
                        <i class="fas fa-info-circle mr-2"></i>
                        En savoir plus
                    </a>
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
                    <div class="text-3xl font-bold text-accent mb-2">{{ $stats['years_active'] ?? '25+' }}</div>
                    <div class="text-gray-600">Années d'expérience</div>
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
                <h2 class="text-4xl font-bold text-gray-900 mb-4">{{ $content['about']->where('key', 'title')->first()->value ?? 'À propos de nous' }}</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    {{ $content['about']->where('key', 'description')->first()->value ?? 'L\'Association Westmount est une organisation d\'entraide et de solidarité qui accompagne ses membres dans les moments difficiles de la vie.' }}
                </p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Notre Mission</h3>
                    <p class="text-gray-600 mb-6">
                        Depuis plus de 25 ans, nous offrons un soutien financier et moral à nos membres 
                        lors des décès d'un proche. Notre système de contributions mutuelles permet 
                        de partager les coûts et de s'entraider dans les moments les plus difficiles.
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
                        Soutien financier immédiat lors du décès d'un proche pour couvrir les frais funéraires
                    </p>
                    <ul class="text-left text-sm text-gray-600 space-y-2">
                        <li>• Montant selon le type de membre</li>
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
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Types d'Adhésion</h2>
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
                        <li>• Contribution décès: ${{ number_format($type->death_contribution, 0) }}</li>
                        <li>• Accès complet aux services</li>
                        <li>• Support 24/7</li>
                    </ul>
                    <a href="{{ route('register') }}" 
                       class="w-full bg-primary text-white hover:bg-blue-700 font-bold py-2 px-4 rounded-lg text-center block transition duration-300">
                        Choisir ce plan
                    </a>
                </div>
                @endforeach
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
                                <p class="text-gray-600">123 Rue Westmount<br>Montréal, QC H3Z 1A1</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-phone text-primary mt-1 mr-4"></i>
                            <div>
                                <h4 class="font-semibold text-gray-900">Téléphone</h4>
                                <p class="text-gray-600">(514) 123-4567</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-envelope text-primary mt-1 mr-4"></i>
                            <div>
                                <h4 class="font-semibold text-gray-900">Email</h4>
                                <p class="text-gray-600">info@associationwestmount.com</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <i class="fas fa-clock text-primary mt-1 mr-4"></i>
                            <div>
                                <h4 class="font-semibold text-gray-900">Heures d'ouverture</h4>
                                <p class="text-gray-600">Lun - Ven: 9h00 - 17h00<br>Sam: 9h00 - 13h00</p>
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

@endsection
