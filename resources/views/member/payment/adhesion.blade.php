@extends('member.layouts.app')

@section('title', 'Paiement d\'adhésion')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Paiement d'adhésion</h1>
        <p class="mt-2 text-gray-600">Effectuez votre paiement d'adhésion pour activer votre compte membre.</p>
    </div>

    <div class="max-w-2xl mx-auto">
        <!-- Informations du paiement -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Détails du paiement</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Type de membre</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $member->memberType->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Frais d'adhésion</dt>
                        <dd class="mt-1 text-lg font-medium text-gray-900">{{ number_format($member->memberType->adhesion_fee, 2) }} CAD</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Numéro de membre</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $member->member_number }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Date d'échéance</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $pendingPayment->created_at->addDays(30)->format('d/m/Y') }}</dd>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire de paiement -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informations de paiement</h3>
            </div>
            <div class="p-6">
                <form id="payment-form" method="POST" action="{{ route('member.payment.adhesion.process') }}">
                    @csrf
                    
                    <!-- Montant -->
                    <div class="mb-6">
                        <label for="amount" class="block text-sm font-medium text-gray-700">Montant à payer</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="text" 
                                   name="amount" 
                                   id="amount" 
                                   value="{{ number_format($member->memberType->adhesion_fee, 2) }}" 
                                   readonly
                                   class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md bg-gray-50">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">CAD</span>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                        <input type="text" 
                               name="description" 
                               id="description" 
                               value="Paiement d'adhésion - {{ $member->memberType->name }}"
                               readonly
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm bg-gray-50">
                    </div>

                    <!-- Section Virement bancaire -->
                    <div class="mb-6">
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                            <h4 class="text-sm font-medium text-blue-800 mb-2">Informations pour virement bancaire</h4>
                            <div class="text-sm text-blue-700 space-y-1">
                                <p><strong>Banque:</strong> {{ \App\Models\Setting::get('bank_name', 'Association Westmount') }}</p>
                                <p><strong>Compte:</strong> {{ \App\Models\Setting::get('bank_account', '1234567890') }}</p>
                                <p><strong>Transit:</strong> {{ \App\Models\Setting::get('bank_transit', '00123') }}</p>
                                @if(\App\Models\Setting::get('bank_swift'))
                                <p><strong>SWIFT:</strong> {{ \App\Models\Setting::get('bank_swift') }}</p>
                                @endif
                                @if(\App\Models\Setting::get('bank_address'))
                                <p><strong>Adresse:</strong> {{ \App\Models\Setting::get('bank_address') }}</p>
                                @endif
                                <p><strong>Montant:</strong> {{ number_format($member->memberType->adhesion_fee, 2) }} {{ \App\Models\Setting::get('payment_currency', 'CAD') }}</p>
                                <p><strong>Référence:</strong> {{ $member->member_number }}-ADH</p>
                            </div>
                            <p class="text-xs text-blue-600 mt-2">
                                {{ \App\Models\Setting::get('bank_instructions', 'Veuillez inclure votre numéro de membre dans la référence du virement.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('member.membership') }}" 
                           class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Annuler
                        </a>
                        <button type="submit" 
                                id="submit-button"
                                class="bg-blue-600 py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="button-text">Payer {{ number_format($member->memberType->adhesion_fee, 2) }} CAD</span>
                            <span id="spinner" class="hidden">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Traitement...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion de la soumission du formulaire
    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');
    
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        // Afficher le spinner
        submitButton.disabled = true;
        buttonText.classList.add('hidden');
        spinner.classList.remove('hidden');
        
        try {
            const response = await fetch('{{ route("member.payment.adhesion.process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    amount: {{ $member->memberType->adhesion_fee }},
                    description: 'Paiement d\'adhésion - {{ $member->memberType->name }}'
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                // Paiement créé avec succès, rediriger vers la page de confirmation
                window.location.href = '{{ route("member.membership") }}?payment=created';
            } else {
                throw new Error(result.message || 'Erreur lors de la création du paiement');
            }
        } catch (error) {
            console.error('Erreur:', error);
            alert('Erreur: ' + error.message);
        } finally {
            submitButton.disabled = false;
            buttonText.classList.remove('hidden');
            spinner.classList.add('hidden');
        }
    });
});
</script>
@endsection
