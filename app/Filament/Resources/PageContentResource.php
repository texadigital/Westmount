<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageContentResource\Pages;
use App\Filament\Resources\PageContentResource\RelationManagers;
use App\Models\PageContent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PageContentResource extends Resource
{
    protected static ?string $model = PageContent::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationLabel = 'Contenu des pages';
    
    protected static ?string $modelLabel = 'Contenu de page';
    
    protected static ?string $pluralModelLabel = 'Contenu des pages';
    
    protected static ?string $navigationGroup = 'Contenu';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('page')
                    ->required()
                    ->options([
                        'home' => 'Page d\'accueil',
                        'about' => 'À propos',
                        'contact' => 'Contact',
                    ])
                    ->reactive(),
                Forms\Components\Select::make('section')
                    ->required()
                    ->options(function (callable $get) {
                        $page = $get('page');
                        $sections = [
                            'home' => [
                                'hero' => 'Section héro',
                                'about' => 'Section à propos',
                                'services' => 'Section services',
                                'stats' => 'Section statistiques',
                            ],
                            'about' => [
                                'hero' => 'Section héro',
                                'history' => 'Notre histoire',
                                'mission' => 'Notre mission',
                                'vision' => 'Notre vision',
                                'values' => 'Nos valeurs',
                            ],
                            'contact' => [
                                'hero' => 'Section héro',
                                'info' => 'Informations de contact',
                                'form' => 'Formulaire de contact',
                            ],
                        ];
                        return $sections[$page] ?? [];
                    })
                    ->reactive(),
                Forms\Components\TextInput::make('key')
                    ->required()
                    ->maxLength(255)
                    ->helperText('Ex: title, subtitle, description, content'),
                Forms\Components\Select::make('type')
                    ->required()
                    ->options([
                        'text' => 'Texte simple',
                        'html' => 'HTML',
                        'image' => 'Image',
                        'number' => 'Nombre',
                    ])
                    ->default('text')
                    ->reactive(),
                Forms\Components\Textarea::make('value')
                    ->required()
                    ->columnSpanFull()
                    ->rows(4)
                    ->helperText('Le contenu à afficher sur la page'),
                Forms\Components\TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->helperText('Ordre d\'affichage (plus petit = plus haut)'),
                Forms\Components\Toggle::make('is_active')
                    ->required()
                    ->default(true)
                    ->helperText('Désactiver pour masquer temporairement'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('page')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'home' => 'success',
                        'about' => 'info',
                        'contact' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'home' => 'Accueil',
                        'about' => 'À propos',
                        'contact' => 'Contact',
                        default => $state,
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('section')
                    ->badge()
                    ->color('gray')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('key')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('value')
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'text' => 'gray',
                        'html' => 'success',
                        'image' => 'info',
                        'number' => 'warning',
                        default => 'gray',
                    })
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Actif'),
                Tables\Columns\TextColumn::make('sort_order')
                    ->numeric()
                    ->sortable()
                    ->label('Ordre'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Modifié'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('page')
                    ->options([
                        'home' => 'Page d\'accueil',
                        'about' => 'À propos',
                        'contact' => 'Contact',
                    ]),
                Tables\Filters\Filter::make('is_active')
                    ->query(fn (Builder $query, array $data): Builder => $query->when(
                        $data['is_active'] ?? null,
                        fn (Builder $query, $isActive): Builder => $query->where('is_active', $isActive),
                    ))
                    ->form([
                        Forms\Components\Select::make('is_active')
                            ->label('Statut')
                            ->options([
                                1 => 'Actif',
                                0 => 'Inactif',
                            ])
                            ->placeholder('Tous'),
                    ]),
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
            ->defaultSort('page');
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
            'index' => Pages\ListPageContents::route('/'),
            'create' => Pages\CreatePageContent::route('/create'),
            'edit' => Pages\EditPageContent::route('/{record}/edit'),
        ];
    }
}
