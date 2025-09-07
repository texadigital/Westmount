@extends('member.layouts.app')

@section('title', 'Détails du Parrainage')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- En-tête -->
    <div class="mb-8">
        <div class="flex items-center">
            <a href="{{ route('sponsorship.index') }}" 
               class="mr-4 inline-flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Retour aux parrainages
            </a>
        </div>
        <h1 class="text-3xl font-bold text-gray-900">Détails du Parrainage</h1>
        <p class="mt-2 text-gray-600">Informations détaillées sur ce parrainage.</p>
    </div>

    <div class="max-w-4xl">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Informations du parrainage</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Détails complets de ce parrainage.</p>
            </div>
            
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Code de parrainage</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="font-mono text-lg font-bold text-blue-600">{{ $sponsorship->sponsorship_code }}</span>
                        </dd>
                    </div>
                    
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Statut</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($sponsorship->status === 'completed') bg-green-100 text-green-800
                                @elseif($sponsorship->status === 'confirmed') bg-blue-100 text-blue-800
                                @elseif($sponsorship->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ $sponsorship->status_label }}
                            </span>
                        </dd>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Prospect</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <div class="font-medium">{{ $sponsorship->prospect_full_name }}</div>
                            <div class="text-gray-500">{{ $sponsorship->prospect_email }}</div>
                            <div class="text-gray-500">{{ $sponsorship->prospect_phone }}</div>
                        </dd>
                    </div>
                    
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Créé le</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $sponsorship->created_at->format('d/m/Y à H:i') }}
                        </dd>
                    </div>
                    
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Expire le</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $sponsorship->expires_at->format('d/m/Y à H:i') }}
                            @if($sponsorship->expires_at->isPast())
                                <span class="ml-2 text-red-600 font-medium">(Expiré)</span>
                            @elseif($sponsorship->expires_at->diffInDays(now()) <= 7)
                                <span class="ml-2 text-yellow-600 font-medium">(Expire bientôt)</span>
                            @endif
                        </dd>
                    </div>
                    
                    @if($sponsorship->confirmed_at)
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Confirmé le</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $sponsorship->confirmed_at->format('d/m/Y à H:i') }}
                        </dd>
                    </div>
                    @endif
                    
                    @if($sponsorship->notes)
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Notes</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            {{ $sponsorship->notes }}
                        </dd>
                    </div>
                    @endif
                </dl>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-6 flex justify-between">
            <div>
                @if($sponsorship->status === 'pending')
                    <form method="POST" action="{{ route('sponsorship.destroy', $sponsorship) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce parrainage ?')">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Supprimer
                        </button>
                    </form>
                @endif
            </div>
            
            <div class="flex space-x-3">
                <a href="{{ route('sponsorship.index') }}" 
                   class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Retour à la liste
                </a>
                
                @if($sponsorship->status === 'confirmed')
                    <form method="POST" action="{{ route('sponsorship.complete', $sponsorship) }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Marquer comme complété
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
