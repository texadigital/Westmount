<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BankSettingResource\Pages;
use App\Models\BankSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BankSettingResource extends Resource
{
    protected static ?string $model = BankSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-library';

    protected static ?string $navigationLabel = 'Bank Settings';

    protected static ?string $modelLabel = 'Bank Setting';

    protected static ?string $pluralModelLabel = 'Bank Settings';

    protected static ?string $navigationGroup = 'Payment Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Setting Name'),
                
                Forms\Components\TextInput::make('bank_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Bank Name'),
                
                Forms\Components\TextInput::make('account_holder')
                    ->required()
                    ->maxLength(255)
                    ->label('Account Holder'),
                
                Forms\Components\TextInput::make('account_number')
                    ->required()
                    ->maxLength(255)
                    ->label('Account Number'),
                
                Forms\Components\TextInput::make('transit_number')
                    ->required()
                    ->maxLength(255)
                    ->label('Transit Number'),
                
                Forms\Components\TextInput::make('institution_number')
                    ->required()
                    ->maxLength(255)
                    ->label('Institution Number'),
                
                Forms\Components\TextInput::make('swift_code')
                    ->maxLength(255)
                    ->label('SWIFT Code'),
                
                Forms\Components\TextInput::make('routing_number')
                    ->maxLength(255)
                    ->label('Routing Number'),
                
                Forms\Components\Textarea::make('instructions')
                    ->rows(3)
                    ->label('Instructions for Members'),
                
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('bank_name')
                    ->label('Bank Name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('account_holder')
                    ->label('Account Holder')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('account_number')
                    ->label('Account Number')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBankSettings::route('/'),
            'create' => Pages\CreateBankSetting::route('/create'),
            'edit' => Pages\EditBankSetting::route('/{record}/edit'),
        ];
    }
}
