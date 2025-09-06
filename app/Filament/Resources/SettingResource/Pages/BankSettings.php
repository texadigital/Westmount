<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use App\Models\Setting;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRecords;
use Filament\Tables;
use Filament\Tables\Table;

class BankSettings extends ManageRecords
{
    protected static string $resource = SettingResource::class;

    protected static ?string $navigationLabel = 'Paramètres bancaires';

    protected static ?string $title = 'Paramètres bancaires';

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $navigationGroup = 'Configuration';

    protected static ?int $navigationSort = 11;

    public function getBreadcrumbs(): array
    {
        return [
            'Paramètres' => SettingResource::getUrl(),
            'Bancaires' => null,
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Setting::query()->where('group', 'bank'))
            ->columns([
                Tables\Columns\TextColumn::make('key')
                    ->label('Paramètre')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'bank_name' => 'Nom de la banque',
                        'bank_account' => 'Numéro de compte',
                        'bank_transit' => 'Numéro de transit',
                        'bank_swift' => 'Code SWIFT/BIC',
                        'bank_address' => 'Adresse de la banque',
                        'bank_instructions' => 'Instructions de virement',
                        default => $state,
                    })
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('value')
                    ->label('Valeur')
                    ->limit(50)
                    ->tooltip(function (Setting $record): string {
                        return $record->value;
                    }),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(30)
                    ->tooltip(function (Setting $record): string {
                        return $record->description ?? '';
                    }),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Modifié le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\TextInput::make('value')
                            ->label('Valeur')
                            ->required()
                            ->maxLength(255),
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('add_bank_setting')
                ->label('Ajouter un paramètre bancaire')
                ->icon('heroicon-o-plus')
                ->form([
                    Forms\Components\TextInput::make('key')
                        ->label('Clé')
                        ->required()
                        ->unique(Setting::class, 'key')
                        ->maxLength(255)
                        ->helperText('Identifiant unique pour ce paramètre'),

                    Forms\Components\TextInput::make('value')
                        ->label('Valeur')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Textarea::make('description')
                        ->label('Description')
                        ->rows(2)
                        ->helperText('Description de ce paramètre'),

                    Forms\Components\TextInput::make('sort_order')
                        ->label('Ordre de tri')
                        ->numeric()
                        ->default(0)
                        ->helperText('Ordre d\'affichage (plus petit = plus haut)'),
                ])
                ->action(function (array $data): void {
                    Setting::create([
                        'key' => $data['key'],
                        'value' => $data['value'],
                        'type' => 'text',
                        'group' => 'bank',
                        'description' => $data['description'],
                        'sort_order' => $data['sort_order'],
                        'is_active' => true,
                    ]);
                }),
        ];
    }
}