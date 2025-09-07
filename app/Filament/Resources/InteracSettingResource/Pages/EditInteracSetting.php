<?php

namespace App\Filament\Resources\InteracSettingResource\Pages;

use App\Filament\Resources\InteracSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInteracSetting extends EditRecord
{
    protected static string $resource = InteracSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
