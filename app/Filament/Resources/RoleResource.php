<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Models\Role;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'Rôles';

    protected static ?string $modelLabel = 'Rôle';

    protected static ?string $pluralModelLabel = 'Rôles';

    protected static ?string $navigationGroup = 'Administration';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nom du Rôle')
                    ->helperText('Nom unique pour le rôle (ex: admin, manager)'),
                
                Forms\Components\TextInput::make('display_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Nom d\'Affichage')
                    ->helperText('Nom affiché dans l\'interface'),
                
                Forms\Components\Textarea::make('description')
                    ->rows(3)
                    ->label('Description')
                    ->helperText('Description du rôle et de ses responsabilités'),
                
                Forms\Components\CheckboxList::make('permissions')
                    ->label('Permissions')
                    ->options(Role::getAvailablePermissions())
                    ->columns(2)
                    ->bulkToggleable()
                    ->helperText('Sélectionnez les permissions accordées à ce rôle'),
                
                Forms\Components\Toggle::make('is_active')
                    ->label('Actif')
                    ->default(true),
                
                Forms\Components\Toggle::make('is_system')
                    ->label('Rôle Système')
                    ->disabled()
                    ->helperText('Les rôles système ne peuvent pas être supprimés'),
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
                
                Tables\Columns\TextColumn::make('display_name')
                    ->label('Nom d\'Affichage')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('permissions')
                    ->label('Permissions')
                    ->formatStateUsing(fn ($state) => count($state ?? []))
                    ->suffix(' permissions')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('users_count')
                    ->label('Utilisateurs')
                    ->counts('users')
                    ->sortable(),
                
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
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Statut Actif'),
                
                Tables\Filters\TernaryFilter::make('is_system')
                    ->label('Rôle Système'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (Role $record) => !$record->is_system),
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
