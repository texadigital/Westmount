<?php

namespace App\Filament\Resources\OrganizationCalculationSettingResource\Pages;

use App\Filament\Resources\OrganizationCalculationSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrganizationCalculationSetting extends EditRecord
{
    protected static string $resource = OrganizationCalculationSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
