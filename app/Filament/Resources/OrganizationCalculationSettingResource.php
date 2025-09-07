<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationCalculationSettingResource\Pages;
use App\Models\OrganizationCalculationSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrganizationCalculationSettingResource extends Resource
{
    protected static ?string $model = OrganizationCalculationSetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $navigationLabel = 'Organization Calculations';

    protected static ?string $modelLabel = 'Organization Calculation Setting';

    protected static ?string $pluralModelLabel = 'Organization Calculation Settings';

    protected static ?string $navigationGroup = 'Business Logic';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->label('Setting Name'),
                
                Forms\Components\TextInput::make('adhesion_fee_per_member')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->label('Adhesion Fee Per Member'),
                
                Forms\Components\TextInput::make('adhesion_fee_formula')
                    ->required()
                    ->maxLength(255)
                    ->label('Adhesion Fee Formula')
                    ->helperText('Use: adhesion_fee_per_member, member_count'),
                
                Forms\Components\Select::make('contribution_fee_formula')
                    ->required()
                    ->options([
                        'sum_of_individual_contributions' => 'Sum of Individual Contributions',
                        'average_contribution' => 'Average Contribution',
                        'max_contribution' => 'Maximum Contribution',
                    ])
                    ->label('Contribution Fee Formula'),
                
                Forms\Components\Toggle::make('include_penalties')
                    ->label('Include Penalties')
                    ->default(false),
                
                Forms\Components\TextInput::make('discount_percentage')
                    ->numeric()
                    ->suffix('%')
                    ->label('Discount Percentage')
                    ->default(0),
                
                Forms\Components\TextInput::make('min_members_for_discount')
                    ->numeric()
                    ->label('Minimum Members for Discount')
                    ->helperText('Leave empty for no discount'),
                
                Forms\Components\Textarea::make('description')
                    ->rows(3)
                    ->label('Description'),
                
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
                
                Tables\Columns\TextColumn::make('adhesion_fee_per_member')
                    ->label('Fee Per Member')
                    ->money('CAD')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('discount_percentage')
                    ->label('Discount')
                    ->suffix('%')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('min_members_for_discount')
                    ->label('Min Members for Discount')
                    ->sortable(),
                
                Tables\Columns\IconColumn::make('include_penalties')
                    ->label('Include Penalties')
                    ->boolean()
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
            'index' => Pages\ListOrganizationCalculationSettings::route('/'),
            'create' => Pages\CreateOrganizationCalculationSetting::route('/create'),
            'edit' => Pages\EditOrganizationCalculationSetting::route('/{record}/edit'),
        ];
    }
}
