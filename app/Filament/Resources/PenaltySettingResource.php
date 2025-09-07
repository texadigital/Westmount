<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PenaltySettingResource\Pages;
use App\Models\PenaltySetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Builder;

class PenaltySettingResource extends Resource
{
    protected static ?string $model = PenaltySetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationLabel = 'Paramètres de Pénalités';

    protected static ?string $modelLabel = 'Paramètre de Pénalité';

    protected static ?string $pluralModelLabel = 'Paramètres de Pénalités';

    protected static ?string $navigationGroup = 'Configuration';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informations Générales')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('Identifiant unique pour ce paramètre'),
                        
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->maxLength(500),
                        
                        Toggle::make('is_active')
                            ->label('Actif')
                            ->default(true)
                            ->helperText('Désactiver pour suspendre l\'application de cette pénalité'),
                    ])->columns(2),

                Section::make('Configuration de la Pénalité')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('penalty_amount')
                                    ->label('Montant de la Pénalité')
                                    ->numeric()
                                    ->required()
                                    ->prefix('$')
                                    ->step(0.01)
                                    ->helperText('Montant fixe ou pourcentage de base'),
                                
                                Select::make('penalty_type')
                                    ->label('Type de Pénalité')
                                    ->options([
                                        1 => 'Montant Fixe',
                                        2 => 'Pourcentage',
                                    ])
                                    ->required()
                                    ->default(1)
                                    ->helperText('1 = Montant fixe, 2 = Pourcentage du montant original'),
                            ]),
                    ]),

                Section::make('Périodes et Escalation')
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextInput::make('grace_period_days')
                                    ->label('Période de Grâce (jours)')
                                    ->numeric()
                                    ->required()
                                    ->default(30)
                                    ->minValue(0)
                                    ->helperText('Nombre de jours avant l\'application de la pénalité'),
                                
                                TextInput::make('escalation_days')
                                    ->label('Début d\'Escalation (jours)')
                                    ->numeric()
                                    ->required()
                                    ->default(60)
                                    ->minValue(0)
                                    ->helperText('Nombre de jours avant l\'escalation de la pénalité'),
                                
                                TextInput::make('escalation_multiplier')
                                    ->label('Facteur d\'Escalation')
                                    ->numeric()
                                    ->required()
                                    ->default(1.5)
                                    ->step(0.1)
                                    ->minValue(1.0)
                                    ->helperText('Facteur par lequel la pénalité est multipliée'),
                            ]),
                    ]),

                Section::make('Planification des Notifications')
                    ->schema([
                        Repeater::make('notification_schedule')
                            ->label('Jours de Notification')
                            ->schema([
                                TextInput::make('day')
                                    ->label('Jour')
                                    ->numeric()
                                    ->required()
                                    ->minValue(1)
                                    ->helperText('Jour de retard pour envoyer une notification'),
                            ])
                            ->defaultItems(4)
                            ->addActionLabel('Ajouter un jour')
                            ->reorderable(false)
                            ->collapsible()
                            ->helperText('Définir les jours de retard auxquels envoyer des notifications'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 50 ? $state : null;
                    }),
                
                TextColumn::make('penalty_amount')
                    ->label('Montant')
                    ->money('CAD')
                    ->sortable(),
                
                BadgeColumn::make('penalty_type')
                    ->label('Type')
                    ->formatStateUsing(fn (int $state): string => match($state) {
                        1 => 'Fixe',
                        2 => 'Pourcentage',
                        default => 'Inconnu',
                    })
                    ->colors([
                        'primary' => 1,
                        'secondary' => 2,
                    ]),
                
                TextColumn::make('grace_period_days')
                    ->label('Grâce (j)')
                    ->sortable(),
                
                TextColumn::make('escalation_days')
                    ->label('Escalation (j)')
                    ->sortable(),
                
                TextColumn::make('escalation_multiplier')
                    ->label('Facteur')
                    ->formatStateUsing(fn (float $state): string => $state . 'x')
                    ->sortable(),
                
                BooleanColumn::make('is_active')
                    ->label('Actif')
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('penalty_type')
                    ->label('Type de Pénalité')
                    ->options([
                        1 => 'Montant Fixe',
                        2 => 'Pourcentage',
                    ]),
                
                SelectFilter::make('is_active')
                    ->label('Statut')
                    ->options([
                        1 => 'Actif',
                        0 => 'Inactif',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    BulkAction::make('activate')
                        ->label('Activer')
                        ->icon('heroicon-o-check')
                        ->action(fn ($records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('deactivate')
                        ->label('Désactiver')
                        ->icon('heroicon-o-x-mark')
                        ->action(fn ($records) => $records->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ])
            ->defaultSort('name');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenaltySettings::route('/'),
            'create' => Pages\CreatePenaltySetting::route('/create'),
            'edit' => Pages\EditPenaltySetting::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->orderBy('name');
    }
}