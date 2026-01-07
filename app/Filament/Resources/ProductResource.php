<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'Productos';
    protected static ?string $modelLabel = 'Producto';
    protected static ?string $pluralModelLabel = 'Productos';
    protected static ?string $navigationGroup = 'Productos';
    protected static ?int $navigationSort = 2;

    // CONFIGURACIÓN DEL FORMULARIO (Crear/Editar Producto)
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nombre del Producto'),
                
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->unique()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('icon')
                            ->label('Icono (Emoji)')
                            ->maxLength(10)
                            ->placeholder('🍕'),
                        Forms\Components\ColorPicker::make('color')
                            ->label('Color')
                            ->default('#3b82f6'),
                        Forms\Components\TextInput::make('order')
                            ->label('Orden')
                            ->numeric()
                            ->default(0),
                    ])
                    ->label('Categoría')
                    ->helperText('Selecciona una categoría o crea una nueva'),
                
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric()
                    ->prefix('S/')
                    ->label('Precio'),

                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->label('Foto'),

                Forms\Components\Toggle::make('is_active')
                    ->label('¿Disponible para venta?')
                    ->default(true),
            ]);
    }

    // CONFIGURACIÓN DE LA TABLA (Lista de Pizzas)
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('Producto'),

                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->sortable()
                    ->label('Categoría')
                    ->badge()
                    ->color(fn ($record) => $record->category?->color ?? 'gray'),

                Tables\Columns\TextColumn::make('price')
                    ->money('PEN') 
                    ->sortable()
                    ->label('Precio'),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Activo'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // AQUÍ CONECTAMOS CON LA RECETA (Ingredientes)
    public static function getRelations(): array
    {
        return [
            RelationManagers\IngredientsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}