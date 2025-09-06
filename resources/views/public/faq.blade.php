@extends('layouts.public')

@section('title', 'FAQ - Association Westmount')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-16 px-4 sm:py-24 sm:px-6 lg:px-8">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                    Questions Fréquentes
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    Trouvez les réponses à vos questions
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
            <div class="max-w-3xl mx-auto">
                <!-- FAQ Items -->
                <div class="space-y-6">
                    <!-- FAQ Item 1 -->
                    <div class="bg-white rounded-lg shadow-md">
                        <button class="w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-blue-500" onclick="toggleFAQ('faq1')">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-900">Comment m'inscrire à l'association?</h3>
                                <svg id="faq1-icon" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </button>
                        <div id="faq1-content" class="hidden px-6 pb-4">
                            <p class="text-gray-600">
                                Pour vous inscrire, rendez-vous sur notre page d'inscription, remplissez le formulaire avec vos informations personnelles, choisissez votre type de membre, et effectuez le paiement des frais d'adhésion. Vous recevrez un email de confirmation une fois votre inscription validée.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ Item 2 -->
                    <div class="bg-white rounded-lg shadow-md">
                        <button class="w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-blue-500" onclick="toggleFAQ('faq2')">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-900">Quels sont les frais d'adhésion?</h3>
                                <svg id="faq2-icon" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </button>
                        <div id="faq2-content" class="hidden px-6 pb-4">
                            <p class="text-gray-600">
                                Les frais d'adhésion sont de 50$ CAD pour tous les types de membres. Les contributions mensuelles varient selon le type de membre : 10$ pour les membres réguliers et associations, 2$ pour les membres juniors et seniors.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ Item 3 -->
                    <div class="bg-white rounded-lg shadow-md">
                        <button class="w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-blue-500" onclick="toggleFAQ('faq3')">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-900">Comment effectuer les paiements?</h3>
                                <svg id="faq3-icon" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </button>
                        <div id="faq3-content" class="hidden px-6 pb-4">
                            <p class="text-gray-600">
                                Nous acceptons les paiements par virement bancaire et Interac. Les informations bancaires sont disponibles dans votre espace membre. Les paiements par virement peuvent prendre 1-3 jours ouvrables pour être confirmés.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ Item 4 -->
                    <div class="bg-white rounded-lg shadow-md">
                        <button class="w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-blue-500" onclick="toggleFAQ('faq4')">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-900">Comment fonctionne le système de parrainage?</h3>
                                <svg id="faq4-icon" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </button>
                        <div id="faq4-content" class="hidden px-6 pb-4">
                            <p class="text-gray-600">
                                Les membres existants peuvent demander un code de parrainage dans leur espace membre. Ce code peut être partagé avec de nouvelles personnes qui souhaitent s'inscrire. Le parrain et le filleul bénéficient d'avantages spéciaux.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ Item 5 -->
                    <div class="bg-white rounded-lg shadow-md">
                        <button class="w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-blue-500" onclick="toggleFAQ('faq5')">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-900">Que faire si j'oublie mon mot de passe?</h3>
                                <svg id="faq5-icon" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </button>
                        <div id="faq5-content" class="hidden px-6 pb-4">
                            <p class="text-gray-600">
                                Sur la page de connexion, cliquez sur "Mot de passe oublié" et suivez les instructions pour réinitialiser votre mot de passe. Un email vous sera envoyé avec un lien de réinitialisation.
                            </p>
                        </div>
                    </div>

                    <!-- FAQ Item 6 -->
                    <div class="bg-white rounded-lg shadow-md">
                        <button class="w-full px-6 py-4 text-left focus:outline-none focus:ring-2 focus:ring-blue-500" onclick="toggleFAQ('faq6')">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-900">Comment contacter le support technique?</h3>
                                <svg id="faq6-icon" class="w-5 h-5 text-gray-500 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </button>
                        <div id="faq6-content" class="hidden px-6 pb-4">
                            <p class="text-gray-600">
                                Vous pouvez nous contacter par email à support@westmount.ca ou par téléphone au +1 (514) 555-0123. Notre équipe de support est disponible du lundi au vendredi de 9h00 à 17h00.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Contact Section -->
                <div class="mt-16 bg-blue-50 rounded-lg p-8 text-center">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Vous ne trouvez pas votre réponse?</h2>
                    <p class="text-gray-600 mb-6">Notre équipe de support est là pour vous aider avec toutes vos questions.</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('public.contact') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Nous contacter
                        </a>
                        <a href="{{ route('public.technical-support') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Support technique
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
function toggleFAQ(id) {
    const content = document.getElementById(id + '-content');
    const icon = document.getElementById(id + '-icon');
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.style.transform = 'rotate(180deg)';
    } else {
        content.classList.add('hidden');
        icon.style.transform = 'rotate(0deg)';
    }
}
</script>
@endsection

