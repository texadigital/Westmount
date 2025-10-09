@php /** @var \App\Filament\Pages\SystemSettings $this */ @endphp
<x-filament-panels::page>
    <div class="space-y-6">
        {{ $this->form }}

        <div class="flex items-center gap-3">
            <x-filament::button wire:click="save" color="primary">
                Enregistrer
            </x-filament::button>
            <x-filament::button wire:click="sendTestEmail" color="gray">
                Envoyer un email de test
            </x-filament::button>
        </div>

        <x-filament::section>
            <x-slot name="heading">Conseils</x-slot>
            <x-slot name="description">Après modification des paramètres, l'application recharge la configuration et redémarre les files d'attente pour appliquer immédiatement les changements.</x-slot>
            <div class="text-sm text-gray-600">
                - Stripe: assurez-vous que la clé publique correspond à la clé secrète et au secret de webhook.<br>
                - Email: utilisez une boîte de réception de test (Mailpit/Mailhog/Mailtrap) en développement.
            </div>
        </x-filament::section>
    </div>
</x-filament-panels::page>
