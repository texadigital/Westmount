<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AuditLogResource\Pages;
use App\Models\AuditLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AuditLogResource extends Resource
{
    protected static ?string $model = AuditLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Journaux d\'Audit';

    protected static ?string $modelLabel = 'Journal d\'Audit';

    protected static ?string $pluralModelLabel = 'Journaux d\'Audit';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('event')
                    ->disabled()
                    ->label('Événement'),
                
                Forms\Components\TextInput::make('model_type')
                    ->disabled()
                    ->label('Type de Modèle'),
                
                Forms\Components\TextInput::make('model_id')
                    ->disabled()
                    ->label('ID du Modèle'),
                
                Forms\Components\KeyValue::make('old_values')
                    ->disabled()
                    ->label('Anciennes Valeurs'),
                
                Forms\Components\KeyValue::make('new_values')
                    ->disabled()
                    ->label('Nouvelles Valeurs'),
                
                Forms\Components\TextInput::make('user_type')
                    ->disabled()
                    ->label('Type d\'Utilisateur'),
                
                Forms\Components\TextInput::make('user_id')
                    ->disabled()
                    ->label('ID Utilisateur'),
                
                Forms\Components\TextInput::make('ip_address')
                    ->disabled()
                    ->label('Adresse IP'),
                
                Forms\Components\Textarea::make('description')
                    ->disabled()
                    ->rows(3)
                    ->label('Description'),
                
                Forms\Components\KeyValue::make('metadata')
                    ->disabled()
                    ->label('Métadonnées'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('event')
                    ->label('Événement')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        'login' => 'info',
                        'logout' => 'gray',
                        default => 'primary',
                    })
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('model_type')
                    ->label('Modèle')
                    ->formatStateUsing(fn ($state) => $state ? class_basename($state) : 'N/A')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('model_id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Utilisateur')
                    ->formatStateUsing(fn ($record) => $record->user ? $record->user->name : 'Système')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('user_type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'admin' => 'warning',
                        'member' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event')
                    ->options([
                        'created' => 'Créé',
                        'updated' => 'Modifié',
                        'deleted' => 'Supprimé',
                        'login' => 'Connexion',
                        'logout' => 'Déconnexion',
                        'payment' => 'Paiement',
                        'registration' => 'Inscription',
                    ]),
                
                Tables\Filters\SelectFilter::make('model_type')
                    ->options([
                        'App\Models\Member' => 'Membre',
                        'App\Models\Organization' => 'Organisation',
                        'App\Models\Payment' => 'Paiement',
                        'App\Models\Membership' => 'Adhésion',
                        'App\Models\User' => 'Utilisateur',
                    ]),
                
                Tables\Filters\SelectFilter::make('user_type')
                    ->options([
                        'admin' => 'Administrateur',
                        'member' => 'Membre',
                    ]),
                
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Du'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Au'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s'); // Auto-refresh every 30 seconds
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAuditLogs::route('/'),
            'view' => Pages\ViewAuditLog::route('/{record}'),
        ];
    }
}
