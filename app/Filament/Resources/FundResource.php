<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FundResource\Pages;
use App\Models\Fund;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FundResource extends Resource
{
    protected static ?string $model = Fund::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationLabel = 'Fonds';

    protected static ?string $modelLabel = 'Fonds';

    protected static ?string $pluralModelLabel = 'Fonds';

    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations du Fonds')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom du Fonds')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->rows(3)
                            ->maxLength(500),
                        
                        Forms\Components\Select::make('type')
                            ->label('Type de Fonds')
                            ->options([
                                'general' => 'Général',
                                'death_benefit' => 'Prestation de Décès',
                                'emergency' => 'Urgence',
                            ])
                            ->required()
                            ->default('general'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Fonds Actif')
                            ->default(true)
                            ->helperText('Désactiver pour empêcher les transactions'),
                    ])->columns(2),

                Forms\Components\Section::make('Statistiques Financières')
                    ->schema([
                        Forms\Components\TextInput::make('current_balance')
                            ->label('Solde Actuel (CAD)')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->step(0.01)
                            ->default(0)
                            ->disabled()
                            ->helperText('Calculé automatiquement'),
                        
                        Forms\Components\TextInput::make('total_contributions')
                            ->label('Total Contributions (CAD)')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->step(0.01)
                            ->default(0)
                            ->disabled()
                            ->helperText('Calculé automatiquement'),
                        
                        Forms\Components\TextInput::make('total_distributions')
                            ->label('Total Distributions (CAD)')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->step(0.01)
                            ->default(0)
                            ->disabled()
                            ->helperText('Calculé automatiquement'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'general' => 'primary',
                        'death_benefit' => 'success',
                        'emergency' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'general' => 'Général',
                        'death_benefit' => 'Prestation de Décès',
                        'emergency' => 'Urgence',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('current_balance')
                    ->label('Solde Actuel')
                    ->money('CAD')
                    ->sortable()
                    ->color(fn (string $state): string => 
                        $state > 0 ? 'success' : 'danger'
                    ),
                
                Tables\Columns\TextColumn::make('total_contributions')
                    ->label('Total Contributions')
                    ->money('CAD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('total_distributions')
                    ->label('Total Distributions')
                    ->money('CAD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('usage_percentage')
                    ->label('Utilisation')
                    ->formatStateUsing(fn ($state) => number_format($state, 1) . '%')
                    ->sortable()
                    ->alignCenter(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Type de Fonds')
                    ->options([
                        'general' => 'Général',
                        'death_benefit' => 'Prestation de Décès',
                        'emergency' => 'Urgence',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Statut')
                    ->placeholder('Tous')
                    ->trueLabel('Actifs uniquement')
                    ->falseLabel('Inactifs uniquement'),
                
                Tables\Filters\Filter::make('with_balance')
                    ->label('Avec solde')
                    ->query(fn (Builder $query): Builder => $query->where('current_balance', '>', 0)),
                
                Tables\Filters\Filter::make('empty_funds')
                    ->label('Fonds vides')
                    ->query(fn (Builder $query): Builder => $query->where('current_balance', '=', 0)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
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
            'index' => Pages\ListFunds::route('/'),
            'create' => Pages\CreateFund::route('/create'),
            'edit' => Pages\EditFund::route('/{record}/edit'),
        ];
    }
}
