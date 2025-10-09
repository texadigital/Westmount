<?php

namespace App\Filament\Resources\DeathEventResource\Pages;

use App\Filament\Resources\DeathEventResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDeathEvent extends CreateRecord
{
    protected static string $resource = DeathEventResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
