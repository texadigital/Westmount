<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NotificationTemplateResource\Pages;
use App\Models\NotificationTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NotificationTemplateResource extends Resource
{
    protected static ?string $model = NotificationTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope-open';

    protected static ?string $navigationLabel = 'Modèles de Notifications';

    protected static ?string $modelLabel = 'Modèle de Notification';

    protected static ?string $pluralModelLabel = 'Modèles de Notifications';

    protected static ?string $navigationGroup = 'Communication';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nom du Modèle'),
                
                Forms\Components\Select::make('type')
                    ->required()
                    ->options([
                        'email' => 'Email',
                        'sms' => 'SMS',
                        'database' => 'Base de données',
                    ])
                    ->label('Type'),
                
                Forms\Components\Select::make('event')
                    ->required()
                    ->options([
                        'member_welcome' => 'Bienvenue Membre',
                        'payment_reminder' => 'Rappel de Paiement',
                        'payment_overdue' => 'Paiement en Retard',
                        'sponsorship_used' => 'Parrainage Utilisé',
                        'contribution_due' => 'Contribution Due',
                        'payment_confirmed' => 'Paiement Confirmé',
                        'membership_renewal' => 'Renouvellement d\'Adhésion',
                        'account_suspended' => 'Compte Suspendu',
                    ])
                    ->label('Événement'),
                
                Forms\Components\TextInput::make('subject')
                    ->maxLength(255)
                    ->label('Sujet')
                    ->helperText('Utilisez {{variable}} pour les variables dynamiques'),
                
                Forms\Components\Textarea::make('body')
                    ->required()
                    ->rows(10)
                    ->label('Corps du Message')
                    ->helperText('Utilisez {{variable}} pour les variables dynamiques'),
                
                Forms\Components\TagsInput::make('variables')
                    ->label('Variables Disponibles')
                    ->helperText('Liste des variables utilisables dans le template'),
                
                Forms\Components\Toggle::make('is_active')
                    ->label('Actif')
                    ->default(true),
                
                Forms\Components\Toggle::make('is_system')
                    ->label('Modèle Système')
                    ->disabled()
                    ->helperText('Les modèles système ne peuvent pas être supprimés'),
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
                
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'email' => 'success',
                        'sms' => 'warning',
                        'database' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('event')
                    ->label('Événement')
                    ->badge()
                    ->color('primary')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('subject')
                    ->label('Sujet')
                    ->limit(50)
                    ->searchable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_system')
                    ->label('Système')
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
                    ->options([
                        'email' => 'Email',
                        'sms' => 'SMS',
                        'database' => 'Base de données',
                    ]),
                
                Tables\Filters\SelectFilter::make('event')
                    ->options([
                        'member_welcome' => 'Bienvenue Membre',
                        'payment_reminder' => 'Rappel de Paiement',
                        'payment_overdue' => 'Paiement en Retard',
                        'sponsorship_used' => 'Parrainage Utilisé',
                        'contribution_due' => 'Contribution Due',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Statut Actif'),
                
                Tables\Filters\TernaryFilter::make('is_system')
                    ->label('Modèle Système'),
            ])
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->label('Aperçu')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modal()
                    ->modalHeading('Aperçu du Modèle')
                    ->modalContent(fn (NotificationTemplate $record) => view('filament.preview-notification', ['template' => $record])),
                
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (NotificationTemplate $record) => !$record->is_system),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn ($records) => $records->where('is_system', false)->count() > 0),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotificationTemplates::route('/'),
            'create' => Pages\CreateNotificationTemplate::route('/create'),
            'edit' => Pages\EditNotificationTemplate::route('/{record}/edit'),
        ];
    }
}
