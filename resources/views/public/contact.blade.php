@extends('layouts.public')

@section('title', 'Contact - Association Westmount')
@section('description', 'Contactez l\'Association Westmount pour toute question ou information. Nous sommes là pour vous accompagner.')

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary to-blue-600 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-6xl font-bold mb-6">Contactez-nous</h1>
            <p class="text-xl md:text-2xl max-w-3xl mx-auto">
                Nous sommes là pour répondre à vos questions et vous accompagner dans votre démarche
            </p>
        </div>
    </section>

    <!-- Contact Information -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div class="bg-gray-50 p-8 rounded-lg">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Envoyez-nous un message</h2>
                    <form method="POST" action="{{ route('public.contact.send') }}" class="space-y-6">
                        @csrf
                        
                        @if(session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Prénom *</label>
                                <input type="text" name="first_name" id="first_name" 
                                       value="{{ old('first_name') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                       required>
                            </div>
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Nom *</label>
                                <input type="text" name="last_name" id="last_name" 
                                       value="{{ old('last_name') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                       required>
                            </div>
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input type="email" name="email" id="email" 
                                   value="{{ old('email') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   required>
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Téléphone</label>
                            <input type="tel" name="phone" id="phone" 
                                   value="{{ old('phone') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Sujet *</label>
                            <select name="subject" id="subject" 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                    required>
                                <option value="">Sélectionner un sujet</option>
                                <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>Question générale</option>
                                <option value="membership" {{ old('subject') == 'membership' ? 'selected' : '' }}>Adhésion</option>
                                <option value="payment" {{ old('subject') == 'payment' ? 'selected' : '' }}>Paiement</option>
                                <option value="contribution" {{ old('subject') == 'contribution' ? 'selected' : '' }}>Contribution décès</option>
                                <option value="sponsorship" {{ old('subject') == 'sponsorship' ? 'selected' : '' }}>Parrainage</option>
                                <option value="technical" {{ old('subject') == 'technical' ? 'selected' : '' }}>Support technique</option>
                                <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Autre</option>
                            </select>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                            <textarea name="message" id="message" rows="6" 
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                      placeholder="Décrivez votre question ou votre demande..."
                                      required>{{ old('message') }}</textarea>
                        </div>

                        <div class="flex items-start">
                            <input type="checkbox" name="privacy" id="privacy" 
                                   class="mt-1 h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded"
                                   required>
                            <label for="privacy" class="ml-2 text-sm text-gray-600">
                                J'accepte que mes données personnelles soient utilisées pour répondre à ma demande. *
                            </label>
                        </div>

                        <button type="submit" 
                                class="w-full bg-primary text-white hover:bg-blue-700 font-bold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Envoyer le message
                        </button>
                    </form>
                </div>

                <!-- Contact Information -->
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Informations de contact</h2>
                    
                    <div class="space-y-8">
                        <!-- Address -->
                        <div class="flex items-start">
                            <div class="bg-primary text-white p-3 rounded-lg mr-4">
                                <i class="fas fa-map-marker-alt text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Adresse</h3>
                                <p class="text-gray-600">
                                    573 Pierre-Dugua-De Mons, L'Assomption, QC J5W 0E3<br>
                                    CANADA
                                </p>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="flex items-start">
                            <div class="bg-secondary text-white p-3 rounded-lg mr-4">
                                <i class="fas fa-phone text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Téléphone</h3>
                                <p class="text-gray-600">
                                    <a href="tel:+1 (514) 555-0123" class="hover:text-primary">514-566-4029</a><br>
                                    <span class="text-sm text-gray-500">Lun - Ven: 9h00 - 17h00</span>
                                </p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start">
                            <div class="bg-accent text-white p-3 rounded-lg mr-4">
                                <i class="fas fa-envelope text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Email</h3>
                                <p class="text-gray-600">
                                    <a href="mailto:contact@associationwestmount.com" class="hover:text-primary">info@associationwestmount.com</a><br>
                                    <span class="text-sm text-gray-500">Réponse sous 24h</span>
                                </p>
                            </div>
                        </div>

                        <!-- Hours -->
                        <div class="flex items-start">
                            <div class="bg-gray-600 text-white p-3 rounded-lg mr-4">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold text-gray-900 mb-2">Heures d'ouverture</h3>
                                <div class="text-gray-600">
                                    <p><strong>Lundi - Vendredi:</strong> 9h00 - 17h00</p>
                                    <p><strong>Samedi:</strong> 9h00 - 13h00</p>
                                    <p><strong>Dimanche:</strong> Fermé</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    <div class="mt-8 bg-red-50 border border-red-200 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-red-800 mb-2">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Contact d'urgence
                        </h3>
                        <p class="text-red-700 text-sm mb-2">
                            Pour les décès et situations urgentes, contactez-nous immédiatement :
                        </p>
                        <p class="text-red-800 font-semibold">
                            <a href="tel:+15141234567" class="hover:underline">514-566-4029</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Questions fréquentes</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Trouvez rapidement les réponses aux questions les plus courantes
                </p>
            </div>
            
            <div class="max-w-4xl mx-auto">
                <div class="space-y-6">
                    <div class="bg-white rounded-lg shadow-md">
                        <button class="w-full text-left p-6 focus:outline-none faq-toggle">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-900">Comment rejoindre l'association ?</h3>
                                <i class="fas fa-chevron-down text-gray-500 faq-icon"></i>
                            </div>
                        </button>
                        <div class="faq-content hidden px-6 pb-6">
                            <p class="text-gray-600">
                                Pour rejoindre l'Association Westmount, vous devez remplir le formulaire d'inscription 
                                en ligne, choisir votre type d'adhésion, et effectuer le paiement de la cotisation. 
                                Une fois votre inscription validée, vous recevrez vos identifiants de connexion.
                            </p>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md">
                        <button class="w-full text-left p-6 focus:outline-none faq-toggle">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-900">Quels sont les montants des contributions ?</h3>
                                <i class="fas fa-chevron-down text-gray-500 faq-icon"></i>
                            </div>
                        </button>
                        <div class="faq-content hidden px-6 pb-6">
                            <p class="text-gray-600">
                                Les montants des contributions varient Contribution selon le type de membre décédé. :
                            </p>
                            <ul class="list-disc list-inside mt-2 text-gray-600">
                                <li>Membres réguliers : 10$ CAD</li>
                                <li>Membres seniors : 2$ CAD</li>
                                <li>Membres juniors : 2$ CAD</li>
                                <li>Associations : 10$ CAD</li>
                            </ul>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md">
                        <button class="w-full text-left p-6 focus:outline-none faq-toggle">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-900">Comment fonctionne le système de parrainage ?</h3>
                                <i class="fas fa-chevron-down text-gray-500 faq-icon"></i>
                            </div>
                        </button>
                        <div class="faq-content hidden px-6 pb-6">
                            <p class="text-gray-600">
                                Le système de parrainage permet aux membres existants d'inviter de nouveaux membres. 
                                Chaque membre reçoit un code de parrainage unique qu'il peut partager. 
                                Les nouveaux membres peuvent utiliser ce code lors de leur inscription pour bénéficier 
                                d'avantages et permettre au parrain de gagner des récompenses.
                            </p>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md">
                        <button class="w-full text-left p-6 focus:outline-none faq-toggle">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-900">Comment recevoir le soutien en cas de décès ?</h3>
                                <i class="fas fa-chevron-down text-gray-500 faq-icon"></i>
                            </div>
                        </button>
                        <div class="faq-content hidden px-6 pb-6">
                            <p class="text-gray-600">
                                En cas de décès d'un proche, contactez-nous immédiatement par téléphone ou email. 
                                Nous vous guiderons dans les démarches et vous fournirons le soutien financier 
                                prévu selon votre type d'adhésion. Le processus est simple et rapide pour vous 
                                accompagner dans ce moment difficile.
                            </p>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-md">
                        <button class="w-full text-left p-6 focus:outline-none faq-toggle">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold text-gray-900">Puis-je modifier mes informations personnelles ?</h3>
                                <i class="fas fa-chevron-down text-gray-500 faq-icon"></i>
                            </div>
                        </button>
                        <div class="faq-content hidden px-6 pb-6">
                            <p class="text-gray-600">
                                Oui, vous pouvez modifier vos informations personnelles à tout moment depuis votre 
                                espace membre. Connectez-vous à votre compte et accédez à la section "Mon profil" 
                                pour mettre à jour vos coordonnées, adresse, téléphone, etc.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-primary to-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold mb-6">Prêt à nous rejoindre ?</h2>
            <p class="text-xl mb-8 max-w-3xl mx-auto">
                Rejoignez notre communauté d'entraide et bénéficiez de notre soutien
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('public.registration.form') }}" 
                   class="bg-white text-primary hover:bg-gray-100 font-bold py-3 px-8 rounded-lg text-lg transition duration-300 transform hover:scale-105">
                    <i class="fas fa-user-plus mr-2"></i>
                    Rejoindre maintenant
                </a>
                <a href="{{ route('member.login') }}" 
                   class="border-2 border-white text-white hover:bg-white hover:text-primary font-bold py-3 px-8 rounded-lg text-lg transition duration-300">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Se connecter
                </a>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
    // FAQ Toggle
    document.querySelectorAll('.faq-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const content = this.nextElementSibling;
            const icon = this.querySelector('.faq-icon');
            
            content.classList.toggle('hidden');
            icon.classList.toggle('fa-chevron-down');
            icon.classList.toggle('fa-chevron-up');
        });
    });
</script>
@endpush
