<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationResource\Pages;
use App\Models\Organization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Organisations';

    protected static ?string $modelLabel = 'Organisation';

    protected static ?string $pluralModelLabel = 'Organisations';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations de l\'Organisation')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom de l\'Organisation')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('business_number')
                            ->label('Numéro d\'Entreprise')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('Numéro d\'entreprise du gouvernement canadien'),
                        
                        Forms\Components\TextInput::make('contact_person')
                            ->label('Personne Contact')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('contact_email')
                            ->label('Email de Contact')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('contact_phone')
                            ->label('Téléphone de Contact')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Adresse')
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->label('Adresse')
                            ->required()
                            ->rows(3),
                        
                        Forms\Components\TextInput::make('city')
                            ->label('Ville')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('province')
                            ->label('Province')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('postal_code')
                            ->label('Code Postal')
                            ->required()
                            ->maxLength(10),
                        
                        Forms\Components\TextInput::make('country')
                            ->label('Pays')
                            ->default('Canada')
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Statistiques')
                    ->schema([
                        Forms\Components\TextInput::make('member_count')
                            ->label('Nombre de Membres')
                            ->numeric()
                            ->minValue(0)
                            ->disabled()
                            ->helperText('Calculé automatiquement'),
                        
                        Forms\Components\TextInput::make('total_fees')
                            ->label('Total des Frais (CAD)')
                            ->numeric()
                            ->prefix('$')
                            ->disabled()
                            ->helperText('Calculé automatiquement'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Organisation Active')
                            ->default(true)
                            ->helperText('Désactiver pour empêcher les nouvelles adhésions'),
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
                
                Tables\Columns\TextColumn::make('business_number')
                    ->label('Numéro d\'Entreprise')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('contact_person')
                    ->label('Contact')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('contact_email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('contact_phone')
                    ->label('Téléphone')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('member_count')
                    ->label('Membres')
                    ->sortable()
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('total_fees')
                    ->label('Total Frais')
                    ->money('CAD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('city')
                    ->label('Ville')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Statut')
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
                    ->label('Statut')
                    ->placeholder('Toutes')
                    ->trueLabel('Actives uniquement')
                    ->falseLabel('Inactives uniquement'),
                
                Tables\Filters\Filter::make('with_members')
                    ->label('Avec des membres')
                    ->query(fn (Builder $query): Builder => $query->where('member_count', '>', 0)),
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
            'index' => Pages\ListOrganizations::route('/'),
            'create' => Pages\CreateOrganization::route('/create'),
            'edit' => Pages\EditOrganization::route('/{record}/edit'),
        ];
    }
}
