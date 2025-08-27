<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SponsorshipResource\Pages;
use App\Models\Sponsorship;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SponsorshipResource extends Resource
{
    protected static ?string $model = Sponsorship::class;

    protected static ?string $navigationIcon = 'heroicon-o-hand-thumb-up';

    protected static ?string $navigationLabel = 'Parrainages';

    protected static ?string $modelLabel = 'Parrainage';

    protected static ?string $pluralModelLabel = 'Parrainages';

    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations du Parrainage')
                    ->schema([
                        Forms\Components\Select::make('sponsor_id')
                            ->label('Parrain')
                            ->options(Member::active()->pluck('full_name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\TextInput::make('sponsorship_code')
                            ->label('Code de Parrainage')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('Code unique généré automatiquement')
                            ->disabled(fn ($record) => $record !== null),
                        
                        Forms\Components\Select::make('status')
                            ->label('Statut')
                            ->options([
                                'pending' => 'En Attente',
                                'confirmed' => 'Confirmé',
                                'completed' => 'Complété',
                                'expired' => 'Expiré',
                            ])
                            ->required()
                            ->default('pending'),
                    ])->columns(2),

                Forms\Components\Section::make('Informations du Prospect')
                    ->schema([
                        Forms\Components\TextInput::make('prospect_first_name')
                            ->label('Prénom du Prospect')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('prospect_last_name')
                            ->label('Nom du Prospect')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('prospect_email')
                            ->label('Email du Prospect')
                            ->email()
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('prospect_phone')
                            ->label('Téléphone du Prospect')
                            ->tel()
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),

                Forms\Components\Section::make('Suivi')
                    ->schema([
                        Forms\Components\DateTimePicker::make('confirmed_at')
                            ->label('Confirmé le')
                            ->placeholder('Non confirmé'),
                        
                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Expire le')
                            ->required()
                            ->default(now()->addDays(30)),
                        
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->placeholder('Notes additionnelles sur ce parrainage'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sponsor.full_name')
                    ->label('Parrain')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('sponsorship_code')
                    ->label('Code')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->fontFamily('mono'),
                
                Tables\Columns\TextColumn::make('prospect_full_name')
                    ->label('Prospect')
                    ->searchable(['prospect_first_name', 'prospect_last_name'])
                    ->sortable(['prospect_first_name', 'prospect_last_name']),
                
                Tables\Columns\TextColumn::make('prospect_email')
                    ->label('Email Prospect')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'pending' => 'warning',
                        'confirmed' => 'info',
                        'completed' => 'success',
                        'expired' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'pending' => 'En Attente',
                        'confirmed' => 'Confirmé',
                        'completed' => 'Complété',
                        'expired' => 'Expiré',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('confirmed_at')
                    ->label('Confirmé le')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Non confirmé'),
                
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expire le')
                    ->dateTime()
                    ->sortable()
                    ->color(fn ($state) => $state && $state->isPast() ? 'danger' : 'success'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'pending' => 'En Attente',
                        'confirmed' => 'Confirmé',
                        'completed' => 'Complété',
                        'expired' => 'Expiré',
                    ]),
                
                Tables\Filters\Filter::make('expired')
                    ->label('Expirés')
                    ->query(fn (Builder $query): Builder => $query->where('expires_at', '<', now())),
                
                Tables\Filters\Filter::make('active')
                    ->label('Actifs')
                    ->query(fn (Builder $query): Builder => $query->where('expires_at', '>', now())->where('status', '!=', 'expired')),
            ])
            ->actions([
                Tables\Actions\Action::make('confirm')
                    ->label('Confirmer')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (Sponsorship $record) => $record->confirm())
                    ->visible(fn (Sponsorship $record) => $record->isPending()),
                
                Tables\Actions\Action::make('complete')
                    ->label('Marquer comme Complété')
                    ->icon('heroicon-o-flag')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->action(fn (Sponsorship $record) => $record->markAsCompleted())
                    ->visible(fn (Sponsorship $record) => $record->isConfirmed()),
                
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
            'index' => Pages\ListSponsorships::route('/'),
            'create' => Pages\CreateSponsorship::route('/create'),
            'edit' => Pages\EditSponsorship::route('/{record}/edit'),
        ];
    }
}
