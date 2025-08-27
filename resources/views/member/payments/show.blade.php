@extends('member.layouts.app')

@section('title', 'Détails du paiement')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Détails du paiement</h1>
                <p class="mt-2 text-gray-600">Informations détaillées sur votre paiement.</p>
            </div>
            <a href="{{ route('member.payments') }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 ease-in-out">
                Retour aux paiements
            </a>
        </div>
    </div>

    <!-- Informations du paiement -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Informations du paiement</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Numéro de paiement</dt>
                    <dd class="mt-1 text-sm text-gray-900">#{{ $payment->id }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Date de création</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $payment->created_at->format('d/m/Y H:i') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Type de paiement</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($payment->type) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Montant</dt>
                    <dd class="mt-1 text-lg font-medium text-gray-900">{{ number_format($payment->amount, 2) }} {{ $payment->currency }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Statut</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($payment->status === 'completed') bg-green-100 text-green-800
                            @elseif($payment->status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            @if($payment->status === 'completed') Complété
                            @elseif($payment->status === 'pending') En attente
                            @else Échoué @endif
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Méthode de paiement</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($payment->payment_method) }}</dd>
                </div>
                @if($payment->paid_at)
                <div>
                    <dt class="text-sm font-medium text-gray-500">Date de paiement</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $payment->paid_at->format('d/m/Y H:i') }}</dd>
                </div>
                @endif
                @if($payment->description)
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Description</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $payment->description }}</dd>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Informations de l'adhésion -->
    @if($payment->membership)
    <div class="bg-white shadow rounded-lg mt-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Adhésion associée</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500">Statut de l'adhésion</dt>
                    <dd class="mt-1">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($payment->membership->status === 'active') bg-green-100 text-green-800
                            @elseif($payment->membership->status === 'overdue') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800 @endif">
                            @if($payment->membership->status === 'active') Actif
                            @elseif($payment->membership->status === 'overdue') En retard
                            @else Caduc @endif
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Date de début</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $payment->membership->start_date->format('d/m/Y') }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500">Date de fin</dt>
                    <dd class="mt-1 text-sm text-gray-900">{{ $payment->membership->end_date->format('d/m/Y') }}</dd>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Actions -->
    <div class="mt-8 flex justify-end">
        <a href="{{ route('member.payments') }}" 
           class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 ease-in-out">
            Retour à la liste
        </a>
    </div>
</div>
@endsection
