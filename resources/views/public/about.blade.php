@extends('layouts.public')

@section('title', 'À propos - Association Westmount')
@section('description', 'Découvrez l\'histoire, la mission et les valeurs de l\'Association Westmount, une organisation d\'entraide et de solidarité.')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary to-blue-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">À propos de nous</h1>
            <p class="text-xl md:text-2xl max-w-3xl mx-auto">
                Découvrez l'histoire et les valeurs qui nous animent depuis plus de 25 ans
            </p>
        </div>
    </section>

    <!-- Our Story -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">Notre Histoire</h2>
                    <p class="text-lg text-gray-600 mb-6">
                        L'Association Westmount a été fondée en 1998 par un groupe de familles 
                        montréalaises qui souhaitaient créer un système d'entraide mutuelle. 
                        Face aux coûts élevés des funérailles et au manque de soutien financier 
                        lors des décès, ils ont décidé d'unir leurs forces.
                    </p>
                    <p class="text-lg text-gray-600 mb-6">
                        Depuis plus de 25 ans, nous avons aidé des centaines de familles à 
                        faire face aux moments les plus difficiles de leur vie. Notre communauté 
                        s'est agrandie, mais nos valeurs fondamentales restent les mêmes : 
                        solidarité, transparence et respect.
                    </p>
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
                    <img src="https://images.unsplash.com/photo-1554224155-6726b3ff858f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1000&q=80" 
                         alt="Communauté Westmount" 
                         class="w-full h-64 object-cover rounded-lg">
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
                    <p class="text-gray-600">
                        Offrir un soutien financier et moral à nos membres lors des décès d'un proche, 
                        en créant un réseau d'entraide basé sur la solidarité et la confiance mutuelle.
                    </p>
                </div>

                <!-- Vision -->
                <div class="bg-white p-8 rounded-lg shadow-lg text-center">
                    <div class="bg-secondary text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-eye text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Notre Vision</h3>
                    <p class="text-gray-600">
                        Devenir la référence en matière d'associations d'entraide au Canada, 
                        en développant un modèle durable et transparent qui inspire d'autres communautés.
                    </p>
                </div>

                <!-- Values -->
                <div class="bg-white p-8 rounded-lg shadow-lg text-center">
                    <div class="bg-accent text-white w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-heart text-2xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Nos Valeurs</h3>
                    <p class="text-gray-600">
                        Solidarité, transparence, respect, intégrité et compassion sont les piliers 
                        qui guident toutes nos actions et décisions.
                    </p>
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
                        Rejoignez notre communauté avec une cotisation unique selon votre type de membre
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
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <div class="w-24 h-24 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-user text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Marie Dubois</h3>
                    <p class="text-primary font-semibold mb-2">Présidente</p>
                    <p class="text-gray-600 text-sm">
                        Membre depuis 2010, Marie dirige l'association avec passion et dévouement.
                    </p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <div class="w-24 h-24 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-user text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Jean Tremblay</h3>
                    <p class="text-primary font-semibold mb-2">Trésorier</p>
                    <p class="text-gray-600 text-sm">
                        Expert-comptable, Jean assure la transparence financière de l'association.
                    </p>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                    <div class="w-24 h-24 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-user text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Sophie Martin</h3>
                    <p class="text-primary font-semibold mb-2">Secrétaire</p>
                    <p class="text-gray-600 text-sm">
                        Sophie coordonne les communications et l'administration quotidienne.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Nos Réalisations</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Plus de 25 ans de service à la communauté
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="text-4xl font-bold text-primary mb-2">500+</div>
                    <div class="text-gray-600">Membres actifs</div>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="text-4xl font-bold text-secondary mb-2">$2M+</div>
                    <div class="text-gray-600">Contributions versées</div>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="text-4xl font-bold text-accent mb-2">25+</div>
                    <div class="text-gray-600">Années d'expérience</div>
                </div>
                <div class="bg-gray-50 p-6 rounded-lg">
                    <div class="text-4xl font-bold text-primary mb-2">100%</div>
                    <div class="text-gray-600">Satisfaction</div>
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
                <a href="{{ route('register') }}" 
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
