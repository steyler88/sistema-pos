<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';
    
    protected static ?string $navigationGroup = 'Productos';
    
    protected static ?string $navigationLabel = 'Categorías';
    
    protected static ?string $modelLabel = 'Categoría';
    
    protected static ?string $pluralModelLabel = 'Categorías';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información de la Categoría')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('Ej: Pizzas, Bebidas, Postres'),
                        
                        Forms\Components\TextInput::make('icon')
                            ->label('Icono (Emoji)')
                            ->maxLength(10)
                            ->placeholder('🍕')
                            ->helperText('Usa un emoji para representar esta categoría'),
                        
                        Forms\Components\ColorPicker::make('color')
                            ->label('Color')
                            ->default('#3b82f6')
                            ->helperText('Color que se mostrará en la interfaz'),
                        
                        Forms\Components\TextInput::make('order')
                            ->label('Orden')
                            ->numeric()
                            ->default(0)
                            ->helperText('Número para ordenar las categorías (menor = primero)'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Activa')
                            ->default(true)
                            ->helperText('Desactiva una categoría para ocultarla sin eliminarla'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('icon')
                    ->label('Icono')
                    ->size('lg')
                    ->searchable(false)
                    ->sortable(false),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\ColorColumn::make('color')
                    ->label('Color'),
                
                Tables\Columns\TextColumn::make('order')
                    ->label('Orden')
                    ->sortable()
                    ->alignCenter(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activa')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('products_count')
                    ->label('Productos')
                    ->counts('products')
                    ->sortable()
                    ->alignCenter()
                    ->badge(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Activa')
                    ->placeholder('Todas')
                    ->trueLabel('Solo activas')
                    ->falseLabel('Solo inactivas'),
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
            ->defaultSort('order', 'asc');
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
