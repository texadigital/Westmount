<?php

namespace App\Filament\Resources;

use Filament\Resources\Resource;
use Filament\Navigation\NavigationItem;

class SystemSettingsResource extends Resource
{
    protected static ?string $model = null;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'System Settings';

    protected static ?string $modelLabel = 'System Setting';

    protected static ?string $pluralModelLabel = 'System Settings';

    protected static ?string $navigationGroup = 'Configuration';

    public static function getNavigationItems(): array
    {
        return [
            NavigationItem::make('Interac Settings')
                ->url(route('filament.admin.resources.interac-settings.index'))
                ->icon('heroicon-o-credit-card')
                ->group('Payment Settings'),
            
            NavigationItem::make('Bank Settings')
                ->url(route('filament.admin.resources.bank-settings.index'))
                ->icon('heroicon-o-building-library')
                ->group('Payment Settings'),
            
            NavigationItem::make('Organization Calculations')
                ->url(route('filament.admin.resources.organization-calculation-settings.index'))
                ->icon('heroicon-o-calculator')
                ->group('Business Logic'),
            
            NavigationItem::make('Email Settings')
                ->url(route('filament.admin.resources.email-settings.index'))
                ->icon('heroicon-o-envelope')
                ->group('Communication'),
            
            NavigationItem::make('UI Settings')
                ->url(route('filament.admin.resources.ui-settings.index'))
                ->icon('heroicon-o-paint-brush')
                ->group('Appearance'),
            
            NavigationItem::make('Validation Rules')
                ->url(route('filament.admin.resources.validation-settings.index'))
                ->icon('heroicon-o-shield-check')
                ->group('Validation'),
        ];
    }
}
