<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContributionResource\Pages;
use App\Models\Contribution;
use App\Models\Member;
use App\Models\Payment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContributionResource extends Resource
{
    protected static ?string $model = Contribution::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $navigationLabel = 'Contributions Décès';

    protected static ?string $modelLabel = 'Contribution Décès';

    protected static ?string $pluralModelLabel = 'Contributions Décès';

    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations de la Contribution')
                    ->schema([
                        Forms\Components\Select::make('member_id')
                            ->label('Membre qui Paie')
                            ->options(Member::active()->pluck('full_name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\Select::make('deceased_member_id')
                            ->label('Membre Décédé')
                            ->options(Member::pluck('full_name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\TextInput::make('amount')
                            ->label('Montant (CAD)')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->step(0.01),
                        
                        Forms\Components\Select::make('status')
                            ->label('Statut')
                            ->options([
                                'pending' => 'En Attente',
                                'paid' => 'Payée',
                                'cancelled' => 'Annulée',
                            ])
                            ->required()
                            ->default('pending'),
                    ])->columns(2),

                Forms\Components\Section::make('Dates et Paiement')
                    ->schema([
                        Forms\Components\DatePicker::make('due_date')
                            ->label('Date d\'Échéance')
                            ->required()
                            ->default(now()->addDays(30)),
                        
                        Forms\Components\DatePicker::make('paid_date')
                            ->label('Date de Paiement')
                            ->placeholder('Non payée'),
                        
                        Forms\Components\Select::make('payment_id')
                            ->label('Paiement Associé')
                            ->options(Payment::completed()->pluck('id', 'id'))
                            ->searchable()
                            ->placeholder('Aucun paiement')
                            ->helperText('Lier à un paiement existant'),
                    ])->columns(3),

                Forms\Components\Section::make('Détails')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->placeholder('Notes additionnelles sur cette contribution'),
                    ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('member.full_name')
                    ->label('Membre qui Paie')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('member.member_number')
                    ->label('Numéro Membre')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('deceased_member.full_name')
                    ->label('Membre Décédé')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('amount')
                    ->label('Montant')
                    ->money('CAD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'pending' => 'warning',
                        'paid' => 'success',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'pending' => 'En Attente',
                        'paid' => 'Payée',
                        'cancelled' => 'Annulée',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Échéance')
                    ->date()
                    ->sortable()
                    ->color(fn ($state) => $state && $state->isPast() ? 'danger' : 'success'),
                
                Tables\Columns\TextColumn::make('paid_date')
                    ->label('Payée le')
                    ->date()
                    ->sortable()
                    ->placeholder('Non payée'),
                
                Tables\Columns\TextColumn::make('overdue_days')
                    ->label('Jours Retard')
                    ->sortable()
                    ->alignCenter()
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'success'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créée le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'pending' => 'En Attente',
                        'paid' => 'Payée',
                        'cancelled' => 'Annulée',
                    ]),
                
                Tables\Filters\Filter::make('overdue')
                    ->label('En retard')
                    ->query(fn (Builder $query): Builder => $query->where('due_date', '<', now())->where('status', 'pending')),
                
                Tables\Filters\Filter::make('paid')
                    ->label('Payées uniquement')
                    ->query(fn (Builder $query): Builder => $query->where('status', 'paid')),
            ])
            ->actions([
                Tables\Actions\Action::make('mark_paid')
                    ->label('Marquer comme Payée')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (Contribution $record) => $record->markAsPaid())
                    ->visible(fn (Contribution $record) => $record->isPending()),
                
                Tables\Actions\Action::make('cancel')
                    ->label('Annuler')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (Contribution $record) => $record->markAsCancelled())
                    ->visible(fn (Contribution $record) => $record->isPending()),
                
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContributions::route('/'),
            'create' => Pages\CreateContribution::route('/create'),
            'edit' => Pages\EditContribution::route('/{record}/edit'),
        ];
    }
}
