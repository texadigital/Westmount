<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription réussie - Association Westmount</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="text-center">
                <div class="mx-auto h-12 w-12 text-green-500 mb-4">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-gray-900">
                    Inscription réussie !
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Bienvenue dans l'Association Westmount
                </p>
            </div>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                @if(session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Prochaines étapes
                    </h3>
                    
                    <div class="space-y-4 text-sm text-gray-600">
                        <div class="flex items-center justify-center">
                            <div class="h-6 w-6 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-blue-600 font-medium">1</span>
                            </div>
                            <span>Effectuez votre paiement d'adhésion</span>
                        </div>
                        
                        <div class="flex items-center justify-center">
                            <div class="h-6 w-6 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-blue-600 font-medium">2</span>
                            </div>
                            <span>Connectez-vous à votre espace membre</span>
                        </div>
                        
                        <div class="flex items-center justify-center">
                            <div class="h-6 w-6 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                <span class="text-blue-600 font-medium">3</span>
                            </div>
                            <span>Accédez à vos informations personnelles</span>
                        </div>
                    </div>

                    <div class="mt-8 space-y-4">
                        <a href="{{ route('member.login') }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out inline-block">
                            Se connecter
                        </a>
                        
                        <a href="{{ route('public.registration.form') }}" 
                           class="w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 ease-in-out inline-block">
                            Nouvelle inscription
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
