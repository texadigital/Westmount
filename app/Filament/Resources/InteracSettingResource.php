<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InteracSettingResource\Pages;
use App\Models\InteracSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InteracSettingResource extends Resource
{
    protected static ?string $model = InteracSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Interac Settings';

    protected static ?string $modelLabel = 'Interac Setting';

    protected static ?string $pluralModelLabel = 'Interac Settings';

    protected static ?string $navigationGroup = 'Payment Settings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Setting Name'),
                
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->label('Interac Email'),
                
                Forms\Components\TextInput::make('security_question')
                    ->required()
                    ->maxLength(255)
                    ->label('Security Question'),
                
                Forms\Components\TextInput::make('security_answer')
                    ->required()
                    ->maxLength(255)
                    ->label('Security Answer'),
                
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
                
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('security_question')
                    ->label('Security Question')
                    ->limit(50)
                    ->searchable(),
                
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
            'index' => Pages\ListInteracSettings::route('/'),
            'create' => Pages\CreateInteracSetting::route('/create'),
            'edit' => Pages\EditInteracSetting::route('/{record}/edit'),
        ];
    }
}
