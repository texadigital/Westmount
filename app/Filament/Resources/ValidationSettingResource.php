<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ValidationSettingResource\Pages;
use App\Models\ValidationSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ValidationSettingResource extends Resource
{
    protected static ?string $model = ValidationSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'Validation Rules';

    protected static ?string $modelLabel = 'Validation Setting';

    protected static ?string $pluralModelLabel = 'Validation Settings';

    protected static ?string $navigationGroup = 'Validation';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('field_name')
                    ->required()
                    ->maxLength(255)
                    ->label('Field Name')
                    ->helperText('e.g., first_name, email, phone'),
                
                Forms\Components\Select::make('field_type')
                    ->required()
                    ->options([
                        'text' => 'Text',
                        'email' => 'Email',
                        'phone' => 'Phone',
                        'date' => 'Date',
                        'number' => 'Number',
                        'password' => 'Password',
                    ])
                    ->label('Field Type'),
                
                Forms\Components\TagsInput::make('rules')
                    ->label('Validation Rules')
                    ->helperText('Enter rules separated by commas: required,string,max:255'),
                
                Forms\Components\KeyValue::make('custom_messages')
                    ->label('Custom Error Messages')
                    ->keyLabel('Rule')
                    ->valueLabel('Message')
                    ->helperText('e.g., required => This field is required'),
                
                Forms\Components\Toggle::make('is_required')
                    ->label('Required Field')
                    ->default(true),
                
                Forms\Components\TextInput::make('min_length')
                    ->numeric()
                    ->label('Minimum Length'),
                
                Forms\Components\TextInput::make('max_length')
                    ->numeric()
                    ->label('Maximum Length'),
                
                Forms\Components\TextInput::make('pattern')
                    ->label('Regex Pattern')
                    ->helperText('e.g., /^[0-9]+$/ for numbers only'),
                
                Forms\Components\Textarea::make('help_text')
                    ->rows(2)
                    ->label('Help Text')
                    ->helperText('Text to show users below the field'),
                
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('field_name')
                    ->label('Field Name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('field_type')
                    ->label('Type')
                    ->badge()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('rules')
                    ->label('Rules')
                    ->limit(50)
                    ->searchable(),
                
                Tables\Columns\IconColumn::make('is_required')
                    ->label('Required')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('min_length')
                    ->label('Min Length')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('max_length')
                    ->label('Max Length')
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
                Tables\Filters\SelectFilter::make('field_type')
                    ->options([
                        'text' => 'Text',
                        'email' => 'Email',
                        'phone' => 'Phone',
                        'date' => 'Date',
                        'number' => 'Number',
                        'password' => 'Password',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
                Tables\Filters\TernaryFilter::make('is_required')
                    ->label('Required Status'),
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
            'index' => Pages\ListValidationSettings::route('/'),
            'create' => Pages\CreateValidationSetting::route('/create'),
            'edit' => Pages\EditValidationSetting::route('/{record}/edit'),
        ];
    }
}
