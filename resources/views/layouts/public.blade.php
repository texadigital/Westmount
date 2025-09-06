<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Association Westmount') - Entraide et Solidarité</title>
    <meta name="description" content="@yield('description', 'Rejoignez l\'Association Westmount, une communauté d\'entraide et de solidarité qui soutient ses membres dans les moments difficiles.')">
    <meta name="keywords" content="association, westmount, entraide, solidarité, décès, contributions, mutualité, montréal">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', 'Association Westmount') - Entraide et Solidarité">
    <meta property="og:description" content="@yield('description', 'Rejoignez l\'Association Westmount, une communauté d\'entraide et de solidarité.')">
    <meta property="og:image" content="{{ asset('images/og-image.jpg') }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', 'Association Westmount') - Entraide et Solidarité">
    <meta property="twitter:description" content="@yield('description', 'Rejoignez l\'Association Westmount, une communauté d\'entraide et de solidarité.')">
    <meta property="twitter:image" content="{{ asset('images/og-image.jpg') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        }
        
        .btn-primary {
            @apply bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 transform hover:scale-105;
        }
        
        .btn-secondary {
            @apply border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-bold py-3 px-6 rounded-lg transition duration-300;
        }
        
        .card {
            @apply bg-white rounded-lg shadow-lg hover:shadow-xl transition duration-300;
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#1e40af',
                        secondary: '#059669',
                        accent: '#dc2626'
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    
    @stack('styles')
</head>
<body class="bg-gray-50 font-sans">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="{{ route('public.home') }}" class="text-2xl font-bold text-primary">
                            <i class="fas fa-heart text-accent mr-2"></i>
                            Association Westmount
                        </a>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="{{ route('public.home') }}" 
                           class="text-gray-500 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition duration-300 {{ request()->routeIs('public.home') ? 'text-primary font-semibold' : '' }}">
                            Accueil
                        </a>
                        <a href="{{ route('public.about') }}" 
                           class="text-gray-500 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition duration-300 {{ request()->routeIs('public.about') ? 'text-primary font-semibold' : '' }}">
                            À propos
                        </a>
                        
                        <!-- Services Dropdown -->
                        <div class="relative group">
                            <button class="text-gray-500 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition duration-300 flex items-center">
                                Services
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="absolute left-0 mt-2 w-64 bg-white rounded-md shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                                <div class="py-1">
                                    <a href="{{ route('public.services') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Services</a>
                                    <a href="{{ route('public.death-contributions') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Contributions Décès</a>
                                    <a href="{{ route('public.sponsorship') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Système de Parrainage</a>
                                    <a href="{{ route('public.online-management') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Gestion en Ligne</a>
                                    <a href="{{ route('public.technical-support') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Support Technique</a>
                                    <a href="{{ route('public.faq') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">FAQ</a>
                                </div>
                            </div>
                        </div>
                        
                        <a href="{{ route('public.contact') }}" 
                           class="text-gray-500 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition duration-300 {{ request()->routeIs('public.contact') ? 'text-primary font-semibold' : '' }}">
                            Contact
                        </a>
                        <a href="{{ route('public.registration.form') }}" 
                           class="bg-primary text-white hover:bg-blue-700 px-4 py-2 rounded-md text-sm font-medium transition duration-300 transform hover:scale-105">
                            <i class="fas fa-user-plus mr-1"></i>
                            Rejoindre
                        </a>
                        <a href="{{ route('member.login') }}" 
                           class="text-gray-500 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition duration-300">
                            <i class="fas fa-sign-in-alt mr-1"></i>
                            Connexion
                        </a>
                    </div>
                </div>
                <div class="md:hidden">
                    <button type="button" class="mobile-menu-button bg-gray-800 inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div class="mobile-menu hidden md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t">
                <a href="{{ route('public.home') }}" 
                   class="text-gray-900 hover:text-primary block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('public.home') ? 'text-primary font-semibold' : '' }}">
                    Accueil
                </a>
                <a href="{{ route('public.about') }}" 
                   class="text-gray-500 hover:text-primary block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('public.about') ? 'text-primary font-semibold' : '' }}">
                    À propos
                </a>
                
                <!-- Services Section -->
                <div class="px-3 py-2">
                    <div class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Services</div>
                </div>
                <a href="{{ route('public.services') }}" 
                   class="text-gray-500 hover:text-primary block px-6 py-2 rounded-md text-base font-medium {{ request()->routeIs('public.services') ? 'text-primary font-semibold' : '' }}">
                    Services
                </a>
                <a href="{{ route('public.death-contributions') }}" 
                   class="text-gray-500 hover:text-primary block px-6 py-2 rounded-md text-base font-medium {{ request()->routeIs('public.death-contributions') ? 'text-primary font-semibold' : '' }}">
                    Contributions Décès
                </a>
                <a href="{{ route('public.sponsorship') }}" 
                   class="text-gray-500 hover:text-primary block px-6 py-2 rounded-md text-base font-medium {{ request()->routeIs('public.sponsorship') ? 'text-primary font-semibold' : '' }}">
                    Système de Parrainage
                </a>
                <a href="{{ route('public.online-management') }}" 
                   class="text-gray-500 hover:text-primary block px-6 py-2 rounded-md text-base font-medium {{ request()->routeIs('public.online-management') ? 'text-primary font-semibold' : '' }}">
                    Gestion en Ligne
                </a>
                <a href="{{ route('public.technical-support') }}" 
                   class="text-gray-500 hover:text-primary block px-6 py-2 rounded-md text-base font-medium {{ request()->routeIs('public.technical-support') ? 'text-primary font-semibold' : '' }}">
                    Support Technique
                </a>
                <a href="{{ route('public.faq') }}" 
                   class="text-gray-500 hover:text-primary block px-6 py-2 rounded-md text-base font-medium {{ request()->routeIs('public.faq') ? 'text-primary font-semibold' : '' }}">
                    FAQ
                </a>
                
                <a href="{{ route('public.contact') }}" 
                   class="text-gray-500 hover:text-primary block px-3 py-2 rounded-md text-base font-medium {{ request()->routeIs('public.contact') ? 'text-primary font-semibold' : '' }}">
                    Contact
                </a>
                <a href="{{ route('public.registration.form') }}" 
                   class="bg-primary text-white hover:bg-blue-700 block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-user-plus mr-1"></i>
                    Rejoindre
                </a>
                <a href="{{ route('member.login') }}" 
                   class="text-gray-500 hover:text-primary block px-3 py-2 rounded-md text-base font-medium">
                    <i class="fas fa-sign-in-alt mr-1"></i>
                    Connexion
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">
                        <i class="fas fa-heart text-accent mr-2"></i>
                        Association Westmount
                    </h3>
                    <p class="text-gray-400 mb-4">
                        Une communauté d'entraide et de solidarité qui accompagne ses membres 
                        dans les moments difficiles depuis plus de 25 ans.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <i class="fab fa-facebook text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <i class="fab fa-linkedin text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Liens rapides</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('public.home') }}" class="text-gray-400 hover:text-white transition duration-300">Accueil</a></li>
                        <li><a href="{{ route('public.about') }}" class="text-gray-400 hover:text-white transition duration-300">À propos</a></li>
                        <li><a href="{{ route('public.contact') }}" class="text-gray-400 hover:text-white transition duration-300">Contact</a></li>
                        <li><a href="{{ route('public.registration.form') }}" class="text-gray-400 hover:text-white transition duration-300">Rejoindre</a></li>
                        <li><a href="{{ route('member.login') }}" class="text-gray-400 hover:text-white transition duration-300">Connexion</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Services</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Contributions décès</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Système de parrainage</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Gestion en ligne</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Support technique</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-3 text-primary"></i>
                            <a href="tel:+15141234567" class="hover:text-white transition duration-300">(514) 123-4567</a>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-3 text-primary"></i>
                            <a href="mailto:info@associationwestmount.com" class="hover:text-white transition duration-300">info@associationwestmount.com</a>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-3 text-primary"></i>
                            <span>Montréal, QC, Canada</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-clock mr-3 text-primary"></i>
                            <span>Lun-Ven: 9h-17h</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm">
                        &copy; {{ date('Y') }} Association Westmount. Tous droits réservés.
                    </p>
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition duration-300">Politique de confidentialité</a>
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition duration-300">Conditions d'utilisation</a>
                        <a href="#" class="text-gray-400 hover:text-white text-sm transition duration-300">Mentions légales</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-button').addEventListener('click', function() {
            document.querySelector('.mobile-menu').classList.toggle('hidden');
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const mobileMenu = document.querySelector('.mobile-menu');
            const mobileMenuButton = document.querySelector('.mobile-menu-button');
            
            if (!mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
                mobileMenu.classList.add('hidden');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
