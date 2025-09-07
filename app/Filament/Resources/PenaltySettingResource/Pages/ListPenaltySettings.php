<?php

namespace App\Filament\Resources\PenaltySettingResource\Pages;

use App\Filament\Resources\PenaltySettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenaltySettings extends ListRecords
{
    protected static string $resource = PenaltySettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
