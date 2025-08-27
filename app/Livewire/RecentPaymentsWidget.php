<?php

namespace App\Livewire;

use App\Models\Payment;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class RecentPaymentsWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Payment::query()
                    ->where('status', 'completed')
                    ->with(['member', 'membership'])
                    ->orderBy('paid_at', 'desc')
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
                
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'adhesion' => 'primary',
                        'contribution' => 'success',
                        'penalty' => 'warning',
                        'renewal' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'adhesion' => 'Adhésion',
                        'contribution' => 'Contribution',
                        'penalty' => 'Pénalité',
                        'renewal' => 'Renouvellement',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('amount')
                    ->label('Montant')
                    ->money('CAD')
                    ->sortable()
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Méthode')
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Payé le')
                    ->dateTime()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view_payment')
                    ->label('Voir Paiement')
                    ->url(fn (Payment $record): string => route('filament.admin.resources.payments.edit', $record))
                    ->icon('heroicon-o-eye')
                    ->color('primary'),
            ])
            ->paginated(false);
    }

    protected function getTableHeading(): string
    {
        return 'Derniers Paiements (Top 10)';
    }
}
