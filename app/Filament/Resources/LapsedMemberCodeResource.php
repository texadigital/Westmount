<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LapsedMemberCodeResource\Pages;
use App\Models\LapsedMemberCode;
use App\Models\Member;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LapsedMemberCodeResource extends Resource
{
    protected static ?string $model = LapsedMemberCode::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?string $navigationLabel = 'Codes de Réactivation';

    protected static ?string $modelLabel = 'Code de Réactivation';

    protected static ?string $pluralModelLabel = 'Codes de Réactivation';

    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informations du Code')
                    ->schema([
                        Forms\Components\Select::make('member_id')
                            ->label('Membre')
                            ->options(Member::where('is_active', false)->get()->pluck('full_name', 'id'))
                            ->required()
                            ->searchable()
                            ->preload(),
                        
                        Forms\Components\TextInput::make('code')
                            ->label('Code de Réactivation')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->helperText('Code unique généré automatiquement')
                            ->disabled(fn ($record) => $record !== null),
                        
                        Forms\Components\DateTimePicker::make('expires_at')
                            ->label('Date d\'Expiration')
                            ->required()
                            ->default(now()->addDays(30)),
                        
                        Forms\Components\Toggle::make('is_used')
                            ->label('Utilisé')
                            ->disabled(),
                        
                        Forms\Components\DateTimePicker::make('used_at')
                            ->label('Date d\'Utilisation')
                            ->disabled(),
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
                    ->label('Numéro de Membre')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Code copié!')
                    ->copyMessageDuration(1500),
                
                Tables\Columns\TextColumn::make('expires_at')
                    ->label('Expire le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->color(fn ($record) => $record->expires_at->isPast() ? 'danger' : 'success'),
                
                Tables\Columns\IconColumn::make('is_used')
                    ->label('Utilisé')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                Tables\Columns\TextColumn::make('used_at')
                    ->label('Utilisé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->placeholder('Non utilisé'),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('member_id')
                    ->label('Membre')
                    ->options(Member::where('is_active', false)->get()->pluck('full_name', 'id')),
                
                Tables\Filters\TernaryFilter::make('is_used')
                    ->label('Statut')
                    ->placeholder('Tous')
                    ->trueLabel('Utilisés uniquement')
                    ->falseLabel('Disponibles uniquement'),
                
                Tables\Filters\Filter::make('expired')
                    ->label('Expirés')
                    ->query(fn (Builder $query): Builder => $query->where('expires_at', '<', now())),
                
                Tables\Filters\Filter::make('valid')
                    ->label('Valides')
                    ->query(fn (Builder $query): Builder => $query->where('expires_at', '>', now())->where('is_used', false)),
            ])
            ->actions([
                Tables\Actions\Action::make('generate_new')
                    ->label('Générer Nouveau')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->action(function (LapsedMemberCode $record) {
                        $record->delete();
                        $newCode = LapsedMemberCode::createForMember($record->member);
                        \Filament\Notifications\Notification::make()
                            ->title('Nouveau code généré')
                            ->body("Code: {$newCode->code}")
                            ->success()
                            ->send();
                    })
                    ->visible(fn (LapsedMemberCode $record) => !$record->is_used),
                
                Tables\Actions\Action::make('mark_used')
                    ->label('Marquer comme Utilisé')
                    ->icon('heroicon-o-check')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(fn (LapsedMemberCode $record) => $record->markAsUsed())
                    ->visible(fn (LapsedMemberCode $record) => !$record->is_used),
                
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
            'index' => Pages\ListLapsedMemberCodes::route('/'),
            'create' => Pages\CreateLapsedMemberCode::route('/create'),
            'edit' => Pages\EditLapsedMemberCode::route('/{record}/edit'),
        ];
    }
}
