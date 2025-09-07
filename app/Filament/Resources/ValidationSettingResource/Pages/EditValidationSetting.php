<?php

namespace App\Filament\Resources\ValidationSettingResource\Pages;

use App\Filament\Resources\ValidationSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditValidationSetting extends EditRecord
{
    protected static string $resource = ValidationSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
