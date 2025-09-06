@extends('member.layouts.app')

@section('title', 'Paiement de contribution')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Paiement de contribution</h1>
        <p class="mt-2 text-gray-600">Effectuez vos paiements de contribution pour les membres décédés.</p>
    </div>

    <div class="max-w-4xl mx-auto">
        @if($pendingContributions->count() > 0)
        <!-- Liste des contributions en attente -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Contributions en attente</h3>
            </div>
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Membre décédé</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Échéance</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendingContributions as $contribution)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $contribution->deceasedMember->full_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ number_format($contribution->amount, 2) }} CAD
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $contribution->due_date->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <button onclick="selectContribution({{ $contribution->id }}, {{ $contribution->amount }}, '{{ $contribution->deceasedMember->full_name }}')"
                                        class="text-blue-600 hover:text-blue-900 font-medium">
                                    Sélectionner
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Résumé des paiements sélectionnés -->
        <div id="selected-contributions" class="bg-white shadow rounded-lg mb-8 hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Contributions sélectionnées</h3>
            </div>
            <div class="p-6">
                <div id="contributions-list" class="space-y-3 mb-6">
                    <!-- Les contributions sélectionnées apparaîtront ici -->
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-lg font-medium text-gray-900">Total:</span>
                    <span id="total-amount" class="text-xl font-bold text-blue-600">0.00 CAD</span>
                </div>
            </div>
        </div>

        <!-- Formulaire de paiement -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Informations de paiement</h3>
            </div>
            <div class="p-6">
                <form id="payment-form" method="POST" action="{{ route('member.payment.contribution.process') }}">
                    @csrf
                    
                    <!-- Montant total -->
                    <div class="mb-6">
                        <label for="amount" class="block text-sm font-medium text-gray-700">Montant total à payer</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">$</span>
                            </div>
                            <input type="text" 
                                   name="amount" 
                                   id="amount" 
                                   value="0.00" 
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
                               value="Paiement de contribution"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
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
                                <p><strong>Montant:</strong> <span id="bank-amount">0.00</span> {{ \App\Models\Setting::get('payment_currency', 'CAD') }}</p>
                                <p><strong>Référence:</strong> {{ $member->member_number }}-CONT</p>
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
                            <span id="button-text">Payer <span id="button-amount">0.00</span> CAD</span>
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

        @else
        <!-- Aucune contribution en attente -->
        <div class="bg-white shadow rounded-lg">
            <div class="p-6 text-center">
                <div class="mx-auto h-12 w-12 text-gray-400">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune contribution en attente</h3>
                <p class="mt-1 text-sm text-gray-500">Vous n'avez actuellement aucune contribution à payer.</p>
                <div class="mt-6">
                    <a href="{{ route('member.membership') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Retour à mon adhésion
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
let selectedContributions = [];
let totalAmount = 0;

document.addEventListener('DOMContentLoaded', function() {
    // Gestion de la soumission du formulaire
    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');
    
    form.addEventListener('submit', async function(event) {
        event.preventDefault();
        
        if (selectedContributions.length === 0) {
            alert('Veuillez sélectionner au moins une contribution à payer.');
            return;
        }
        
        // Afficher le spinner
        submitButton.disabled = true;
        buttonText.classList.add('hidden');
        spinner.classList.remove('hidden');
        
        try {
            const response = await fetch('{{ route("member.payment.contribution.process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    amount: totalAmount,
                    description: document.getElementById('description').value,
                    contributions: selectedContributions
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

function selectContribution(id, amount, memberName) {
    // Vérifier si la contribution est déjà sélectionnée
    const existingIndex = selectedContributions.findIndex(c => c.id === id);
    
    if (existingIndex > -1) {
        // Retirer la contribution
        selectedContributions.splice(existingIndex, 1);
        totalAmount -= amount;
    } else {
        // Ajouter la contribution
        selectedContributions.push({ id, amount, memberName });
        totalAmount += amount;
    }
    
    updateUI();
}

function updateUI() {
    const selectedSection = document.getElementById('selected-contributions');
    const contributionsList = document.getElementById('contributions-list');
    const totalAmountElement = document.getElementById('total-amount');
    const amountInput = document.getElementById('amount');
    const buttonAmount = document.getElementById('button-amount');
    const bankAmount = document.getElementById('bank-amount');
    const submitButton = document.getElementById('submit-button');
    
    if (selectedContributions.length > 0) {
        selectedSection.classList.remove('hidden');
        
        // Mettre à jour la liste des contributions
        contributionsList.innerHTML = selectedContributions.map(contribution => `
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-md">
                <span class="text-sm text-gray-900">${contribution.memberName}</span>
                <div class="flex items-center space-x-3">
                    <span class="text-sm font-medium text-gray-900">${contribution.amount.toFixed(2)} CAD</span>
                    <button onclick="selectContribution(${contribution.id}, ${contribution.amount}, '${contribution.memberName}')"
                            class="text-red-600 hover:text-red-900 text-sm">
                        Retirer
                    </button>
                </div>
            </div>
        `).join('');
        
        // Mettre à jour les montants
        const formattedAmount = totalAmount.toFixed(2);
        totalAmountElement.textContent = formattedAmount + ' CAD';
        amountInput.value = formattedAmount;
        buttonAmount.textContent = formattedAmount;
        bankAmount.textContent = formattedAmount;
        
        // Activer le bouton de paiement
        submitButton.disabled = false;
    } else {
        selectedSection.classList.add('hidden');
        totalAmount = 0;
        amountInput.value = '0.00';
        buttonAmount.textContent = '0.00';
        bankAmount.textContent = '0.00';
        submitButton.disabled = true;
    }
}
</script>
@endsection
