<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmailSettingResource\Pages;
use App\Models\EmailSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmailSettingResource extends Resource
{
    protected static ?string $model = EmailSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static ?string $navigationLabel = 'Email Settings';

    protected static ?string $modelLabel = 'Email Setting';

    protected static ?string $pluralModelLabel = 'Email Settings';

    protected static ?string $navigationGroup = 'Communication';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Setting Name'),
                
                Forms\Components\Select::make('mailer')
                    ->required()
                    ->options([
                        'smtp' => 'SMTP',
                        'mailgun' => 'Mailgun',
                        'ses' => 'Amazon SES',
                        'postmark' => 'Postmark',
                    ])
                    ->label('Mailer'),
                
                Forms\Components\TextInput::make('host')
                    ->required()
                    ->maxLength(255)
                    ->label('SMTP Host'),
                
                Forms\Components\TextInput::make('port')
                    ->required()
                    ->numeric()
                    ->label('Port')
                    ->default(587),
                
                Forms\Components\TextInput::make('username')
                    ->required()
                    ->maxLength(255)
                    ->label('Username'),
                
                Forms\Components\TextInput::make('password')
                    ->required()
                    ->password()
                    ->maxLength(255)
                    ->label('Password'),
                
                Forms\Components\Select::make('encryption')
                    ->options([
                        'tls' => 'TLS',
                        'ssl' => 'SSL',
                        'null' => 'None',
                    ])
                    ->label('Encryption')
                    ->default('tls'),
                
                Forms\Components\TextInput::make('from_address')
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->label('From Address'),
                
                Forms\Components\TextInput::make('from_name')
                    ->required()
                    ->maxLength(255)
                    ->label('From Name'),
                
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
                
                Tables\Columns\TextColumn::make('mailer')
                    ->label('Mailer')
                    ->badge()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('host')
                    ->label('Host')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('port')
                    ->label('Port')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('from_address')
                    ->label('From Address')
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
            'index' => Pages\ListEmailSettings::route('/'),
            'create' => Pages\CreateEmailSetting::route('/create'),
            'edit' => Pages\EditEmailSetting::route('/{record}/edit'),
        ];
    }
}
