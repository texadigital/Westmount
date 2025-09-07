<?php

namespace App\Filament\Resources\BankSettingResource\Pages;

use App\Filament\Resources\BankSettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBankSettings extends ListRecords
{
    protected static string $resource = BankSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
