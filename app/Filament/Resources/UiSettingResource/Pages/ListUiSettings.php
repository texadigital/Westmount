<?php

namespace App\Filament\Resources\UiSettingResource\Pages;

use App\Filament\Resources\UiSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUiSettings extends ListRecords
{
    protected static string $resource = UiSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
