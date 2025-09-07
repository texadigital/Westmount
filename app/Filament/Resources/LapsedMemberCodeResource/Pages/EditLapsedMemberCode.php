<?php

namespace App\Filament\Resources\LapsedMemberCodeResource\Pages;

use App\Filament\Resources\LapsedMemberCodeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLapsedMemberCode extends EditRecord
{
    protected static string $resource = LapsedMemberCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
