<?php

namespace App\Filament\Resources\DeathEventResource\Pages;

use App\Filament\Resources\DeathEventResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewDeathEvent extends ViewRecord
{
    protected static string $resource = DeathEventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
