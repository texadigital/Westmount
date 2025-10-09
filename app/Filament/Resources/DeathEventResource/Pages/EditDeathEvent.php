<?php

namespace App\Filament\Resources\DeathEventResource\Pages;

use App\Filament\Resources\DeathEventResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDeathEvent extends EditRecord
{
    protected static string $resource = DeathEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
