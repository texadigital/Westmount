<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UiSettingResource\Pages;
use App\Models\UiSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UiSettingResource extends Resource
{
    protected static ?string $model = UiSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?string $navigationLabel = 'UI Settings';

    protected static ?string $modelLabel = 'UI Setting';

    protected static ?string $pluralModelLabel = 'UI Settings';

    protected static ?string $navigationGroup = 'Appearance';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Setting Name'),
                
                Forms\Components\ColorPicker::make('primary_color')
                    ->default('#3B82F6')
                    ->label('Primary Color'),
                
                Forms\Components\ColorPicker::make('secondary_color')
                    ->default('#6B7280')
                    ->label('Secondary Color'),
                
                Forms\Components\ColorPicker::make('accent_color')
                    ->default('#10B981')
                    ->label('Accent Color'),
                
                Forms\Components\ColorPicker::make('background_color')
                    ->default('#FFFFFF')
                    ->label('Background Color'),
                
                Forms\Components\ColorPicker::make('text_color')
                    ->default('#1F2937')
                    ->label('Text Color'),
                
                Forms\Components\TextInput::make('logo_url')
                    ->url()
                    ->maxLength(255)
                    ->label('Logo URL'),
                
                Forms\Components\TextInput::make('favicon_url')
                    ->url()
                    ->maxLength(255)
                    ->label('Favicon URL'),
                
                Forms\Components\TextInput::make('site_title')
                    ->required()
                    ->maxLength(255)
                    ->label('Site Title'),
                
                Forms\Components\Textarea::make('site_description')
                    ->rows(2)
                    ->label('Site Description'),
                
                Forms\Components\TextInput::make('contact_email')
                    ->email()
                    ->maxLength(255)
                    ->label('Contact Email'),
                
                Forms\Components\TextInput::make('contact_phone')
                    ->tel()
                    ->maxLength(255)
                    ->label('Contact Phone'),
                
                Forms\Components\Textarea::make('footer_text')
                    ->rows(2)
                    ->label('Footer Text'),
                
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
                
                Tables\Columns\TextColumn::make('site_title')
                    ->label('Site Title')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\ColorColumn::make('primary_color')
                    ->label('Primary Color'),
                
                Tables\Columns\ColorColumn::make('secondary_color')
                    ->label('Secondary Color'),
                
                Tables\Columns\TextColumn::make('contact_email')
                    ->label('Contact Email')
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
            'index' => Pages\ListUiSettings::route('/'),
            'create' => Pages\CreateUiSetting::route('/create'),
            'edit' => Pages\EditUiSetting::route('/{record}/edit'),
        ];
    }
}
