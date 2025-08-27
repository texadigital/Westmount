<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberTypeResource\Pages;
use App\Models\MemberType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MemberTypeResource extends Resource
{
    protected static ?string $model = MemberType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Types de Membres';

    protected static ?string $modelLabel = 'Type de Membre';

    protected static ?string $pluralModelLabel = 'Types de Membres';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations du Type de Membre')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->maxLength(500)
                            ->rows(3),
                        
                        Forms\Components\TextInput::make('adhesion_fee')
                            ->label('Frais d\'Adhésion (CAD)')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->step(0.01),
                        
                        Forms\Components\TextInput::make('death_contribution')
                            ->label('Contribution Décès (CAD)')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->step(0.01),
                    ])->columns(2),

                Forms\Components\Section::make('Critères d\'Âge')
                    ->schema([
                        Forms\Components\TextInput::make('min_age')
                            ->label('Âge Minimum')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(120)
                            ->placeholder('Aucune limite'),
                        
                        Forms\Components\TextInput::make('max_age')
                            ->label('Âge Maximum')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(120)
                            ->placeholder('Aucune limite'),
                    ])->columns(2),

                Forms\Components\Toggle::make('is_active')
                    ->label('Actif')
                    ->default(true)
                    ->helperText('Désactiver pour empêcher les nouvelles adhésions de ce type'),
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
                
                Tables\Columns\TextColumn::make('adhesion_fee')
                    ->label('Frais d\'Adhésion')
                    ->money('CAD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('death_contribution')
                    ->label('Contribution Décès')
                    ->money('CAD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('members_count')
                    ->label('Membres')
                    ->counts('members')
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
                    ->placeholder('Tous')
                    ->trueLabel('Actifs uniquement')
                    ->falseLabel('Inactifs uniquement'),
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
            'index' => Pages\ListMemberTypes::route('/'),
            'create' => Pages\CreateMemberType::route('/create'),
            'edit' => Pages\EditMemberType::route('/{record}/edit'),
        ];
    }
}
