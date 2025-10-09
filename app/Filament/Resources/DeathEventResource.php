<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeathEventResource\Pages;
use App\Models\DeathEvent;
use App\Services\DeathEventPublisher;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class DeathEventResource extends Resource
{
    protected static ?string $model = DeathEvent::class;
    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';
    protected static ?string $navigationGroup = 'Gestion des membres';
    protected static ?int $navigationSort = 30;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Détails du décès')
                    ->schema([
                        Forms\Components\TextInput::make('deceased_name')
                            ->label('Nom du défunt')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('date_of_death')
                            ->label('Date du décès')
                            ->required(),
                        Forms\Components\Textarea::make('description')
                            ->label('Détails supplémentaires')
                            ->columnSpanFull(),
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Brouillon',
                                'published' => 'Publié',
                                'closed' => 'Clôturé',
                            ])
                            ->default('draft')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('deceased_name')
                    ->label('Défunt')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date_of_death')
                    ->label('Date décès')
                    ->date('Y-m-d')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'draft',
                        'success' => 'published',
                        'gray' => 'closed',
                    ])
                    ->formatStateUsing(fn (string $state): string => __(
                        match ($state) {
                            'draft' => 'Brouillon',
                            'published' => 'Publié',
                            'closed' => 'Clôturé',
                            default => $state,
                        }
                    )),
                Tables\Columns\TextColumn::make('contributions_count')
                    ->counts('contributions')
                    ->label('Contributions')
                    ->suffix(' membres'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Brouillon',
                        'published' => 'Publié',
                        'closed' => 'Clôturé',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('publish')
                    ->label('Publier')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Publier l\'événement de décès')
                    ->modalDescription('Cela va :')
                    ->modalDescription(fn (DeathEvent $record) => [
                        '• Créer une contribution pour chaque membre actif',
                        '• Définir une échéance à 30 jours',
                        '• Envoyer un email de notification à tous les membres',
                        '• Rendre l\'événement visible sur le site public',
                        '\nÊtes-vous sûr de vouloir publier cet événement ?',
                    ])
                    ->action(function (DeathEvent $record, DeathEventPublisher $publisher) {
                        $publisher->publish($record);
                    })
                    ->visible(fn (DeathEvent $record): bool => $record->status === 'draft'),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListDeathEvents::route('/'),
            'create' => Pages\CreateDeathEvent::route('/create'),
            'view' => Pages\ViewDeathEvent::route('/{record}'),
            'edit' => Pages\EditDeathEvent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withCount('contributions');
    }
}
