<?php

namespace App\Filament\Resources\LapsedMemberCodeResource\Pages;

use App\Filament\Resources\LapsedMemberCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLapsedMemberCodes extends ListRecords
{
    protected static string $resource = LapsedMemberCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
