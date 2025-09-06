<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Models\Member;
use App\Models\Membership;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Paiements';

    protected static ?string $modelLabel = 'Paiement';

    protected static ?string $pluralModelLabel = 'Paiements';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations du Paiement')
                    ->schema([
                        Forms\Components\Select::make('membership_id')
                            ->label('Adhésion')
                            ->options(Membership::active()->with('member')->get()->mapWithKeys(function ($membership) {
                                return [$membership->id => "Adhésion #{$membership->id} - {$membership->member->full_name}"];
                            }))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state) {
                                    $membership = Membership::with('member')->find($state);
                                    if ($membership) {
                                        $set('member_id', $membership->member_id);
                                    }
                                }
                            }),
                        
                        Forms\Components\Select::make('member_id')
                            ->label('Membre')
                            ->options(Member::active()->get()->pluck('full_name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->disabled()
                            ->dehydrated(),
                        
                        Forms\Components\Select::make('type')
                            ->label('Type de Paiement')
                            ->options([
                                'adhesion' => 'Adhésion',
                                'contribution' => 'Contribution',
                                'penalty' => 'Pénalité',
                                'renewal' => 'Renouvellement',
                            ])
                            ->required()
                            ->default('adhesion'),
                        
                        Forms\Components\TextInput::make('amount')
                            ->label('Montant (CAD)')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->step(0.01),
                        
                        Forms\Components\Select::make('currency')
                            ->label('Devise')
                            ->options([
                                'CAD' => 'Dollar Canadien (CAD)',
                                'USD' => 'Dollar US (USD)',
                                'EUR' => 'Euro (EUR)',
                            ])
                            ->required()
                            ->default('CAD'),
                    ])->columns(2),

                Forms\Components\Section::make('Statut et Méthode')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->label('Statut')
                            ->options([
                                'pending' => 'En Attente',
                                'completed' => 'Complété',
                                'failed' => 'Échoué',
                                'refunded' => 'Remboursé',
                            ])
                            ->required()
                            ->default('pending'),
                        
                        Forms\Components\Select::make('payment_method')
                            ->label('Méthode de Paiement')
                            ->options([
                                'stripe' => 'Stripe',
                                'paypal' => 'PayPal',
                                'bank_transfer' => 'Virement Bancaire',
                                'interac' => 'Interac',
                                'check' => 'Chèque',
                            ])
                            ->required()
                            ->default('bank_transfer'),
                        
                        Forms\Components\TextInput::make('stripe_payment_intent_id')
                            ->label('ID Intent Stripe')
                            ->maxLength(255)
                            ->placeholder('Stripe Payment Intent ID'),
                        
                        Forms\Components\TextInput::make('stripe_charge_id')
                            ->label('ID Charge Stripe')
                            ->maxLength(255)
                            ->placeholder('Stripe Charge ID'),
                    ])->columns(2),

                Forms\Components\Section::make('Détails')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->placeholder('Description du paiement'),
                        
                        Forms\Components\KeyValue::make('metadata')
                            ->label('Métadonnées')
                            ->keyLabel('Clé')
                            ->valueLabel('Valeur')
                            ->columnSpanFull(),
                        
                        Forms\Components\DateTimePicker::make('paid_at')
                            ->label('Payé le')
                            ->placeholder('Date et heure du paiement'),
                    ])->columns(2),
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
                
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'adhesion' => 'primary',
                        'contribution' => 'success',
                        'penalty' => 'danger',
                        'renewal' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'adhesion' => 'Adhésion',
                        'contribution' => 'Contribution',
                        'penalty' => 'Pénalité',
                        'renewal' => 'Renouvellement',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('amount')
                    ->label('Montant')
                    ->money('CAD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'failed' => 'danger',
                        'refunded' => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'pending' => 'En Attente',
                        'completed' => 'Complété',
                        'failed' => 'Échoué',
                        'refunded' => 'Remboursé',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('payment_method')
                    ->label('Méthode')
                    ->formatStateUsing(fn (string $state): string => match($state) {
                        'stripe' => 'Stripe',
                        'paypal' => 'PayPal',
                        'bank_transfer' => 'Virement',
                        'interac' => 'Interac',
                        'check' => 'Chèque',
                        default => $state,
                    }),
                
                Tables\Columns\TextColumn::make('paid_at')
                    ->label('Payé le')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('Non payé'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Type de Paiement')
                    ->options([
                        'adhesion' => 'Adhésion',
                        'contribution' => 'Contribution',
                        'penalty' => 'Pénalité',
                        'renewal' => 'Renouvellement',
                    ]),
                
                Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'pending' => 'En Attente',
                        'completed' => 'Complété',
                        'failed' => 'Échoué',
                        'refunded' => 'Remboursé',
                    ]),
                
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Méthode de Paiement')
                    ->options([
                        'stripe' => 'Stripe',
                        'paypal' => 'PayPal',
                        'bank_transfer' => 'Virement Bancaire',
                        'cash' => 'Espèces',
                        'check' => 'Chèque',
                    ]),
                
                Tables\Filters\Filter::make('completed')
                    ->label('Complétés uniquement')
                    ->query(fn (Builder $query): Builder => $query->where('status', 'completed')),
                
                Tables\Filters\Filter::make('pending')
                    ->label('En attente uniquement')
                    ->query(fn (Builder $query): Builder => $query->where('status', 'pending')),
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
        ];
    }
}
