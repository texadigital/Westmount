<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MembershipResource\Pages;
use App\Models\Membership;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MembershipResource extends Resource
{
    protected static ?string $model = Membership::class;

    protected static ?string $navigationIcon = 'heroicon-o-identification';

    protected static ?string $navigationLabel = 'Adhésions';

    protected static ?string $modelLabel = 'Adhésion';

    protected static ?string $pluralModelLabel = 'Adhésions';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations de l\'Adhésion')
                    ->schema([
                        Forms\Components\Select::make('member_id')
                            ->label('Membre')
                            ->options(Member::active()->pluck('full_name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\Select::make('status')
                            ->label('Statut')
                            ->options([
                                'active' => 'Actif',
                                'overdue' => 'En Retard',
                                'lapsed' => 'Caduc',
                            ])
                            ->required()
                            ->default('active'),
                        
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Date de Début')
                            ->required()
                            ->default(now()),
                        
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Date de Fin')
                            ->required()
                            ->default(now()->addYear()),
                    ])->columns(2),

                Forms\Components\Section::make('Paiements')
                    ->schema([
                        Forms\Components\TextInput::make('adhesion_fee_paid')
                            ->label('Frais d\'Adhésion Payés (CAD)')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->step(0.01)
                            ->default(0),
                        
                        Forms\Components\TextInput::make('total_contributions_paid')
                            ->label('Total Contributions Payées (CAD)')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->step(0.01)
                            ->default(0),
                        
                        Forms\Components\TextInput::make('amount_due')
                            ->label('Montant Dû (CAD)')
                            ->numeric()
                            ->prefix('$')
                            ->disabled()
                            ->helperText('Calculé automatiquement'),
                        
                        Forms\Components\DatePicker::make('last_payment_date')
                            ->label('Dernier Paiement le')
                            ->placeholder('Aucun paiement'),
                        
                        Forms\Components\DatePicker::make('next_payment_due')
                            ->label('Prochain Paiement Dû le')
                            ->placeholder('À définir'),
                    ])->columns(2),

                Forms\Components\Section::make('Suivi')
                    ->schema([
                        Forms\Components\TextInput::make('overdue_days')
                            ->label('Jours de Retard')
                            ->numeric()
                            ->minValue(0)
                            ->disabled()
                            ->helperText('Calculé automatiquement'),
                        
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->placeholder('Notes additionnelles sur cette adhésion'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Adhésion Active')
                            ->default(true)
                            ->helperText('Désactiver pour archiver cette adhésion'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('member.full_name')
                    ->label('Membre')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('member.member_number')
                    ->label('Numéro Membre')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'active' => 'success',
                        'overdue' => 'warning',
                        'lapsed' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'active' => 'Actif',
                        'overdue' => 'En Retard',
                        'lapsed' => 'Caduc',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Début')
                    ->date()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('end_date')
                    ->label('Fin')
                    ->date()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('amount_due')
                    ->label('Montant Dû')
                    ->money('CAD')
                    ->sortable()
                    ->color(fn (string $state): string => 
                        $state > 0 ? 'danger' : 'success'
                    ),
                
                Tables\Columns\TextColumn::make('overdue_days')
                    ->label('Jours Retard')
                    ->sortable()
                    ->alignCenter()
                    ->color(fn (string $state): string => 
                        $state > 0 ? 'danger' : 'success'
                    ),
                
                Tables\Columns\TextColumn::make('last_payment_date')
                    ->label('Dernier Paiement')
                    ->date()
                    ->sortable()
                    ->placeholder('Aucun'),
                
                Tables\Columns\TextColumn::make('next_payment_due')
                    ->label('Prochain Paiement')
                    ->date()
                    ->sortable()
                    ->placeholder('Non défini'),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Actif')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'active' => 'Actif',
                        'overdue' => 'En Retard',
                        'lapsed' => 'Caduc',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Adhésion Active')
                    ->placeholder('Toutes')
                    ->trueLabel('Actives uniquement')
                    ->falseLabel('Inactives uniquement'),
                
                Tables\Filters\Filter::make('overdue')
                    ->label('En retard')
                    ->query(fn (Builder $query): Builder => $query->where('status', 'overdue')),
                
                Tables\Filters\Filter::make('lapsed')
                    ->label('Caducs')
                    ->query(fn (Builder $query): Builder => $query->where('status', 'lapsed')),
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
            'index' => Pages\ListMemberships::route('/'),
            'create' => Pages\CreateMembership::route('/create'),
            'edit' => Pages\EditMembership::route('/{record}/edit'),
        ];
    }
}
