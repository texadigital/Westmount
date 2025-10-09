@extends('layouts.public')

@section('title', $pageContent->meta_title ?? 'À propos - Association Westmount')
@section('description', $pageContent->meta_description ?? 'Découvrez l\'histoire, la mission et les valeurs de l\'Association Westmount, une organisation d\'entraide et de solidarité.')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary to-blue-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">{{ $pageContent->title ?? 'À propos de nous' }}</h1>
            @php($heroSubtitle = $about['about_hero_subtitle'] ?? null)
            <p class="text-xl md:text-2xl max-w-3xl mx-auto">
                {{ $heroSubtitle ?? "Découvrez l'histoire et les valeurs qui nous animent." }}
            </p>
        </div>
    </section>

        <!-- Styled Static Content (design preserved) -->
        <!-- Our Story -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div>
                        <h2 class="text-4xl font-bold text-gray-900 mb-6">{{ $about['history_title'] ?? 'Notre Histoire' }}</h2>
                        <p class="text-lg text-gray-600 mb-6">{!! nl2br(e($about['history_body'] ?? '')) !!}</p>
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Nos Fondateurs</h3>
                            <p class="text-gray-600">
                                "Nous croyons que personne ne devrait faire face seul aux épreuves de la vie. 
                                C'est pourquoi nous avons créé cette association - pour que nos membres 
                                puissent compter les uns sur les autres."
                            </p>
                            <p class="text-sm text-gray-500 mt-4 italic">- Les fondateurs de l'Association Westmount</p>
                        </div>
                    </div>
                    <div class="bg-gray-100 p-8 rounded-lg">
                        @php($historyImage = $about['history_image'] ?? null)
                        @if($historyImage)
                            <img src="{{ asset('storage/' . $historyImage) }}" alt="Communauté Westmount" class="w-full h-64 object-cover rounded-lg">
                        @else
                            <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" alt="Communauté Westmount" class="w-full h-64 object-cover rounded-lg">
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Mission, Vision, Values -->
        <section class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Notre Mission, Vision & Valeurs</h2>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Mission -->
                    <div class="bg-white p-8 rounded-lg shadow-lg text-center">
                        <div class="bg-primary text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-bullseye text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Notre Mission</h3>
                        <p class="text-gray-600">{!! nl2br(e($about['mission_text'] ?? '')) !!}</p>
                    </div>

                    <!-- Vision -->
                    <div class="bg-white p-8 rounded-lg shadow-lg text-center">
                        <div class="bg-secondary text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-eye text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Notre Vision</h3>
                        <p class="text-gray-600">{!! nl2br(e($about['vision_text'] ?? '')) !!}</p>
                    </div>

                    <!-- Values -->
                    <div class="bg-white p-8 rounded-lg shadow-lg text-center">
                        <div class="bg-accent text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-heart text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Nos Valeurs</h3>
                        <p class="text-gray-600">{!! nl2br(e($about['values_text'] ?? '')) !!}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How We Work -->
        <section class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Comment nous fonctionnons</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Un système simple, transparent et efficace basé sur la confiance mutuelle
                    </p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="text-center">
                        <div class="bg-primary text-white w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-2xl font-bold">1</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Adhésion</h3>
                        <p class="text-gray-600">
                            Rejoignez notre communauté par une cotisation unique selon votre profil  de membre
                        </p>
                    </div>
                    
                    <div class="text-center">
                        <div class="bg-primary text-white w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-2xl font-bold">2</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Contributions</h3>
                        <p class="text-gray-600">
                            Participez aux contributions lors des décès selon un montant prédéfini
                        </p>
                    </div>
                    
                    <div class="text-center">
                        <div class="bg-primary text-white w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-2xl font-bold">3</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Soutien</h3>
                        <p class="text-gray-600">
                            Recevez un soutien financier immédiat en cas de décès d'un proche
                        </p>
                    </div>
                    
                    <div class="text-center">
                        <div class="bg-primary text-white w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-2xl font-bold">4</div>
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Transparence</h3>
                        <p class="text-gray-600">
                            Suivez toutes les transactions et contributions dans votre espace membre
                        </p>
                    </div>
                </div>
            </div>
        </section>

    <!-- Team Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Notre Équipe</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Des professionnels dévoués qui travaillent pour le bien-être de notre communauté
                </p>
            </div>
            
            @php($hasTeam = isset($teamMembers) && $teamMembers->count() > 0)
            @if($hasTeam)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($teamMembers as $member)
                        <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                            <div class="w-24 h-24 rounded-full mx-auto mb-4 flex items-center justify-center overflow-hidden bg-gray-200">
                                @if($member->photo_path)
                                    <img src="{{ asset('storage/' . $member->photo_path) }}" alt="{{ $member->name }}" class="w-24 h-24 object-cover">
                                @else
                                    <i class="fas fa-user text-3xl text-gray-400"></i>
                                @endif
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $member->name }}</h3>
                            @if($member->role)
                                <p class="text-primary font-semibold mb-2">{{ $member->role }}</p>
                            @endif
                            @if($member->bio)
                                <p class="text-gray-600 text-sm">{{ $member->bio }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                        <div class="w-24 h-24 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <i class="fas fa-user text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Membre d'équipe</h3>
                        <p class="text-primary font-semibold mb-2">Rôle</p>
                        <p class="text-gray-600 text-sm">Ajoutez des membres d'équipe depuis votre tableau de bord administrateur.</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                        <div class="w-24 h-24 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <i class="fas fa-user text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Membre d'équipe</h3>
                        <p class="text-primary font-semibold mb-2">Rôle</p>
                        <p class="text-gray-600 text-sm">Ajoutez des membres d'équipe depuis votre tableau de bord administrateur.</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                        <div class="w-24 h-24 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <i class="fas fa-user text-3xl text-gray-400"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Membre d'équipe</h3>
                        <p class="text-primary font-semibold mb-2">Rôle</p>
                        <p class="text-gray-600 text-sm">Ajoutez des membres d'équipe depuis votre tableau de bord administrateur.</p>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <!-- Statistics -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Notre Tableau</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Notre nombre au service de la communauté.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="text-4xl font-bold text-primary mb-2">{{ $about['stats_members_value'] ?? '1000+' }}</div>
                    <div class="text-gray-600">{{ $about['stats_members_label'] ?? 'Membres actifs' }}</div>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="text-4xl font-bold text-secondary mb-2">{{ $about['stats_contrib_value'] ?? '$2M+' }}</div>
                    <div class="text-gray-600">{{ $about['stats_contrib_label'] ?? 'Contributions versées' }}</div>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="text-4xl font-bold text-accent mb-2">{{ $about['stats_years_value'] ?? '25+' }}</div>
                    <div class="text-gray-600">{{ $about['stats_years_label'] ?? "Années d'expérience" }}</div>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="text-4xl font-bold text-primary mb-2">{{ $about['stats_satisfaction_value'] ?? '100%' }}</div>
                    <div class="text-gray-600">{{ $about['stats_satisfaction_label'] ?? 'Satisfaction' }}</div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-primary to-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-6">Rejoignez notre communauté</h2>
            <p class="text-xl mb-8 max-w-3xl mx-auto">
                Faites partie d'une communauté qui se soutient mutuellement dans les moments difficiles
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('public.registration.form') }}" 
                   class="bg-white text-primary hover:bg-gray-100 font-bold py-3 px-8 rounded-lg text-lg transition duration-300 transform hover:scale-105">
                    <i class="fas fa-user-plus mr-2"></i>
                    Rejoindre maintenant
                </a>
                <a href="{{ route('public.contact') }}" 
                   class="border-2 border-white text-white hover:bg-white hover:text-primary font-bold py-3 px-8 rounded-lg text-lg transition duration-300">
                    <i class="fas fa-phone mr-2"></i>
                    Nous contacter
                </a>
            </div>
        </div>
    </section>

@endsection
