<?php

namespace App\Filament\Resources\DeathEventResource\Pages;

use App\Filament\Resources\DeathEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDeathEvents extends ListRecords
{
    protected static string $resource = DeathEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Nouvel événement'),
        ];
    }
}
