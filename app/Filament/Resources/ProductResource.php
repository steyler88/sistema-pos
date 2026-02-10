<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\Setting;
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
                
                Forms\Components\TextInput::make('sku')
                    ->label('SKU (Código Único)')
                    ->unique(ignoreRecord: true)
                    ->maxLength(255)
                    ->helperText('Código único para sincronizar con WooCommerce')
                    ->placeholder('PROD-001'),
                
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
                
                // PRECIOS CONDICIONALES SEGÚN CONFIGURACIÓN
                Forms\Components\Section::make('💰 Precios')
                    ->schema(function () {
                        $multiPricingEnabled = Setting::get('enable_multi_pricing', false);
                        
                        if ($multiPricingEnabled) {
                            return [
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('price_local')
                                            ->label('💵 Precio Local')
                                            ->required()
                                            ->numeric()
                                            ->prefix('S/')
                                            ->helperText('Precio para ventas en el local'),
                                        
                                        Forms\Components\TextInput::make('price_rappi')
                                            ->label('🛵 Precio Rappi')
                                            ->required()
                                            ->numeric()
                                            ->prefix('S/')
                                            ->helperText('Precio para pedidos de Rappi'),
                                        
                                        Forms\Components\TextInput::make('price_web')
                                            ->label('🌐 Precio Web')
                                            ->required()
                                            ->numeric()
                                            ->prefix('S/')
                                            ->helperText('Precio para pedidos web'),
                                    ]),
                                
                                Forms\Components\Placeholder::make('info')
                                    ->label('')
                                    ->content('✅ Multi-precios habilitado. Para desactivar, ve a Configuración del Sistema → Reglas de Negocio'),
                            ];
                        } else {
                            return [
                                Forms\Components\TextInput::make('price')
                                    ->label('💰 Precio Único')
                                    ->required()
                                    ->numeric()
                                    ->prefix('S/'),
                                
                                Forms\Components\Placeholder::make('info')
                                    ->label('')
                                    ->content('ℹ️ Multi-precios deshabilitado. Para habilitar, ve a Configuración del Sistema → Reglas de Negocio'),
                            ];
                        }
                    })
                    ->description('Configura los precios del producto')
                    ->collapsible(),

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

                Tables\Columns\TextColumn::make('sku')
                    ->searchable()
                    ->sortable()
                    ->label('SKU')
                    ->badge()
                    ->color('info'),

                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->sortable()
                    ->label('Categoría')
                    ->badge()
                    ->color(fn ($record) => $record->category?->color ?? 'gray'),

                Tables\Columns\TextColumn::make('price')
                    ->money('PEN') 
                    ->sortable()
                    ->label('Precio')
                    ->formatStateUsing(function ($record) {
                        $multiPricing = Setting::get('enable_multi_pricing', false);
                        
                        if ($multiPricing) {
                            return sprintf(
                                'Local: S/ %s | Rappi: S/ %s | Web: S/ %s',
                                number_format($record->price_local ?? $record->price, 2),
                                number_format($record->price_rappi ?? $record->price, 2),
                                number_format($record->price_web ?? $record->price, 2)
                            );
                        }
                        
                        return 'S/ ' . number_format($record->price, 2);
                    })
                    ->wrap(),

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