<?php

namespace App\Filament\Resources\OrganizationCalculationSettingResource\Pages;

use App\Filament\Resources\OrganizationCalculationSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrganizationCalculationSettings extends ListRecords
{
    protected static string $resource = OrganizationCalculationSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
