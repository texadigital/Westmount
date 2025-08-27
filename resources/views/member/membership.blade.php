@extends('member.layouts.app')

@section('title', 'Mon adhésion')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- En-tête -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Mon adhésion</h1>
        <p class="mt-2 text-gray-600">Détails de votre adhésion à l'Association Westmount.</p>
    </div>

    @if($activeMembership)
    <!-- Informations de l'adhésion -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informations générales</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Statut</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($activeMembership->status === 'active') bg-green-100 text-green-800
                            @elseif($activeMembership->status === 'overdue') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            @if($activeMembership->status === 'active') Actif
                            @elseif($activeMembership->status === 'overdue') En retard
                            @else Caduc @endif
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Date de début</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $activeMembership->start_date->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Date de fin</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $activeMembership->end_date->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Type de membre</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $member->memberType->name }}</dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Résumé financier -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Résumé financier</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Frais d'adhésion payés</dt>
                    <dd class="mt-1 text-lg font-medium text-green-600">{{ number_format($activeMembership->adhesion_fee_paid, 2) }} CAD</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Contributions payées</dt>
                    <dd class="mt-1 text-lg font-medium text-green-600">{{ number_format($activeMembership->total_contributions_paid, 2) }} CAD</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Montant dû</dt>
                    <dd class="mt-1 text-lg font-medium {{ $activeMembership->amount_due > 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ number_format($activeMembership->amount_due, 2) }} CAD
                    </dd>
                </div>
            </div>
        </div>
    </div>

    <!-- Historique des paiements -->
    <div class="bg-white shadow rounded-lg mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Historique des paiements</h3>
        </div>
        <div class="overflow-hidden">
            @if($payments->count() > 0)
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Méthode</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($payments as $payment)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $payment->created_at->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ ucfirst($payment->type) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ number_format($payment->amount, 2) }} CAD
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($payment->status === 'completed') bg-green-100 text-green-800
                                @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                @if($payment->status === 'completed') Complété
                                @elseif($payment->status === 'pending') En attente
                                @else Échoué @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ ucfirst($payment->payment_method) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="px-6 py-4 text-center text-gray-500">
                Aucun paiement trouvé pour cette adhésion.
            </div>
            @endif
        </div>
    </div>

    <!-- Contributions en attente -->
    @if($contributions->count() > 0)
    <div class="bg-white shadow rounded-lg">
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($contributions as $contribution)
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
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                En attente
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @else
    <div class="bg-white shadow rounded-lg">
        <div class="p-6 text-center">
            <div class="mx-auto h-12 w-12 text-gray-400">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Aucune adhésion active</h3>
            <p class="mt-1 text-sm text-gray-500">Vous n'avez pas d'adhésion active pour le moment.</p>
        </div>
    </div>
    @endif
</div>
@endsection
