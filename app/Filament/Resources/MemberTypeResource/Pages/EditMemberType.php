<?php

namespace App\Filament\Resources\MemberTypeResource\Pages;

use App\Filament\Resources\MemberTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMemberType extends EditRecord
{
    protected static string $resource = MemberTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
