<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MemberResource\Pages;
use App\Models\Member;
use App\Models\MemberType;
use App\Models\Organization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MemberResource extends Resource
{
    protected static ?string $model = Member::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Membres';

    protected static ?string $modelLabel = 'Membre';

    protected static ?string $pluralModelLabel = 'Membres';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations Personnelles')
                    ->schema([
                        Forms\Components\TextInput::make('member_number')
                            ->label('Numéro de Membre')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->default(fn () => Member::generateMemberNumber())
                            ->disabled(fn ($record) => $record !== null),
                        
                        Forms\Components\TextInput::make('pin_code')
                            ->label('Code PIN')
                            ->password()
                            ->required()
                            ->minLength(4)
                            ->maxLength(6)
                            ->numeric()
                            ->helperText('Code PIN de 4 à 6 chiffres pour la connexion'),
                        
                        Forms\Components\TextInput::make('first_name')
                            ->label('Prénom')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('last_name')
                            ->label('Nom de Famille')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\DatePicker::make('birth_date')
                            ->label('Date de Naissance')
                            ->required()
                            ->maxDate(now()),
                        
                        Forms\Components\TextInput::make('phone')
                            ->label('Téléphone')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
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

                Forms\Components\Section::make('Informations d\'Adhésion')
                    ->schema([
                        Forms\Components\Select::make('member_type_id')
                            ->label('Type de Membre')
                            ->options(MemberType::active()->pluck('name', 'id'))
                            ->required()
                            ->searchable(),
                        
                        Forms\Components\Select::make('organization_id')
                            ->label('Organisation')
                            ->options(Organization::active()->pluck('name', 'id'))
                            ->searchable()
                            ->placeholder('Aucune organisation'),
                        
                        Forms\Components\Select::make('sponsor_id')
                            ->label('Parrain')
                            ->options(Member::active()->get()->pluck('full_name', 'id'))
                            ->searchable()
                            ->placeholder('Aucun parrain'),
                        
                        Forms\Components\TextInput::make('canadian_status_proof')
                            ->label('Preuve de Statut Canadien')
                            ->required()
                            ->maxLength(255)
                            ->helperText('Ex: Carte de citoyenneté, permis de résidence permanente, etc.'),
                    ])->columns(2),

                Forms\Components\Section::make('Statut')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Membre Actif')
                            ->default(true),
                        
                        Forms\Components\DateTimePicker::make('email_verified_at')
                            ->label('Email Vérifié le')
                            ->placeholder('Non vérifié'),
                        
                        Forms\Components\DateTimePicker::make('phone_verified_at')
                            ->label('Téléphone Vérifié le')
                            ->placeholder('Non vérifié'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('member_number')
                    ->label('Numéro')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Nom Complet')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(['first_name', 'last_name']),
                
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('phone')
                    ->label('Téléphone')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('memberType.name')
                    ->label('Type')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('age')
                    ->label('Âge')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('organization.name')
                    ->label('Organisation')
                    ->sortable()
                    ->placeholder('Aucune'),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Statut')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Inscrit le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('member_type_id')
                    ->label('Type de Membre')
                    ->options(MemberType::active()->pluck('name', 'id')),
                
                Tables\Filters\SelectFilter::make('organization_id')
                    ->label('Organisation')
                    ->options(Organization::active()->pluck('name', 'id')),
                
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
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListMembers::route('/'),
            'create' => Pages\CreateMember::route('/create'),
            'edit' => Pages\EditMember::route('/{record}/edit'),
        ];
    }
}
