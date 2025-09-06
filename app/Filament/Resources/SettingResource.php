<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationLabel = 'Paramètres';

    protected static ?string $modelLabel = 'Paramètre';

    protected static ?string $pluralModelLabel = 'Paramètres';

    protected static ?string $navigationGroup = 'Configuration';

    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                    ->label('Clé')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->helperText('Identifiant unique pour ce paramètre'),

                Forms\Components\Select::make('type')
                    ->label('Type')
                    ->options([
                        'text' => 'Texte',
                        'textarea' => 'Texte long',
                        'number' => 'Nombre',
                        'boolean' => 'Oui/Non',
                        'json' => 'JSON',
                    ])
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('value', '')),

                Forms\Components\Textarea::make('value')
                    ->label('Valeur')
                    ->rows(3)
                    ->visible(fn (callable $get) => in_array($get('type'), ['text', 'textarea', 'json'])),

                Forms\Components\TextInput::make('value')
                    ->label('Valeur')
                    ->numeric()
                    ->visible(fn (callable $get) => $get('type') === 'number'),

                Forms\Components\Toggle::make('value')
                    ->label('Valeur')
                    ->visible(fn (callable $get) => $get('type') === 'boolean'),

                Forms\Components\Select::make('group')
                    ->label('Groupe')
                    ->options([
                        'general' => 'Général',
                        'payment' => 'Paiements',
                        'email' => 'Email',
                        'bank' => 'Banque',
                        'membership' => 'Adhésion',
                    ])
                    ->required()
                    ->default('general'),

                Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(2)
                    ->helperText('Description de ce paramètre'),

                Forms\Components\TextInput::make('sort_order')
                    ->label('Ordre de tri')
                    ->numeric()
                    ->default(0)
                    ->helperText('Ordre d\'affichage (plus petit = plus haut)'),

                Forms\Components\Toggle::make('is_active')
                    ->label('Actif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('Clé')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('value')
                    ->label('Valeur')
                    ->limit(50)
                    ->tooltip(function (Setting $record): string {
                        return $record->value;
                    }),

                Tables\Columns\BadgeColumn::make('type')
                    ->label('Type')
                    ->colors([
                        'primary' => 'text',
                        'secondary' => 'textarea',
                        'success' => 'number',
                        'warning' => 'boolean',
                        'danger' => 'json',
                    ]),

                Tables\Columns\BadgeColumn::make('group')
                    ->label('Groupe')
                    ->colors([
                        'primary' => 'general',
                        'secondary' => 'payment',
                        'success' => 'email',
                        'warning' => 'bank',
                        'info' => 'membership',
                    ]),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->label('Ordre')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Modifié le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('group')
                    ->label('Groupe')
                    ->options([
                        'general' => 'Général',
                        'payment' => 'Paiements',
                        'email' => 'Email',
                        'bank' => 'Banque',
                        'membership' => 'Adhésion',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Actif')
                    ->boolean()
                    ->trueLabel('Actif seulement')
                    ->falseLabel('Inactif seulement')
                    ->native(false),
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
            ->defaultSort('group')
            ->defaultSort('sort_order');
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
            'bank-settings' => Pages\BankSettings::route('/bank-settings'),
        ];
    }
}