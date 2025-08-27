<?php

namespace App\Livewire;

use App\Models\Membership;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class OverdueMembershipsWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Membership::query()
                    ->where('status', 'overdue')
                    ->where('is_active', true)
                    ->with('member')
                    ->orderBy('overdue_days', 'desc')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('member.full_name')
                    ->label('Membre')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('member.member_number')
                    ->label('Numéro')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('member.phone')
                    ->label('Téléphone')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('amount_due')
                    ->label('Montant Dû')
                    ->money('CAD')
                    ->sortable()
                    ->color('danger'),
                
                Tables\Columns\TextColumn::make('overdue_days')
                    ->label('Jours Retard')
                    ->sortable()
                    ->alignCenter()
                    ->color('danger'),
                
                Tables\Columns\TextColumn::make('next_payment_due')
                    ->label('Échéance')
                    ->date()
                    ->sortable()
                    ->color('danger'),
            ])
            ->actions([
                Tables\Actions\Action::make('view_member')
                    ->label('Voir Membre')
                    ->url(fn (Membership $record): string => route('filament.admin.resources.members.edit', $record->member))
                    ->icon('heroicon-o-eye')
                    ->color('primary'),
            ])
            ->paginated(false);
    }

    protected function getTableHeading(): string
    {
        return 'Adhésions en Retard';
    }
}