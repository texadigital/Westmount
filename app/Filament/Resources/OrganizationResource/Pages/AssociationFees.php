<?php

namespace App\Filament\Resources\OrganizationResource\Pages;

use App\Filament\Resources\OrganizationResource;
use App\Models\Organization;
use Filament\Resources\Pages\Page;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class AssociationFees extends Page
{
    protected static string $resource = OrganizationResource::class;

    protected static string $view = 'filament.resources.organization-resource.pages.association-fees';

    public ?Organization $record = null;

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    protected function resolveRecord(int|string $key): Organization
    {
        return Organization::findOrFail($key);
    }

    public function getTitle(): string
    {
        return 'Calcul des Frais - ' . $this->record->name;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Actualiser')
                ->icon('heroicon-o-arrow-path')
                ->action('refreshData'),
            
            Action::make('export')
                ->label('Exporter')
                ->icon('heroicon-o-document-arrow-down')
                ->action('exportFees'),
        ];
    }

    public function refreshData(): void
    {
        $this->record->updateStatistics();
        
        Notification::make()
            ->title('Données actualisées')
            ->success()
            ->send();
    }

    public function exportFees(): void
    {
        // TODO: Implement export functionality
        Notification::make()
            ->title('Export en cours de développement')
            ->info()
            ->send();
    }

    public function getFeesSummary(): array
    {
        return $this->record->getFeesSummary();
    }
}
