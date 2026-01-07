<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComboResource\Pages;
use App\Filament\Resources\ComboResource\RelationManagers;
use App\Models\Combo;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComboResource extends Resource
{
    protected static ?string $model = Combo::class;

    protected static ?string $navigationIcon = 'heroicon-o-gift';
    
    protected static ?string $navigationGroup = 'Productos';
    
    protected static ?string $navigationLabel = 'Combos';
    
    protected static ?string $modelLabel = 'Combo';
    
    protected static ?string $pluralModelLabel = 'Combos';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Información del Combo')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre del Combo')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ej: Combo Familiar, Combo Personal'),
                        
                        Forms\Components\Textarea::make('description')
                            ->label('Descripción')
                            ->rows(3)
                            ->placeholder('Describe qué incluye el combo...'),
                        
                        Forms\Components\TextInput::make('price')
                            ->label('Precio del Combo')
                            ->required()
                            ->numeric()
                            ->prefix('S/')
                            ->helperText('Precio especial del combo (puede ser menor a la suma de productos)'),
                        
                        Forms\Components\FileUpload::make('image')
                            ->label('Imagen')
                            ->image()
                            ->directory('combos')
                            ->helperText('Imagen representativa del combo'),
                        
                        Forms\Components\TextInput::make('order')
                            ->label('Orden')
                            ->numeric()
                            ->default(0)
                            ->helperText('Orden de aparición en el POS (menor = primero)'),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true)
                            ->helperText('Desactiva para ocultar del POS'),
                    ])
                    ->columns(2),
                
                Forms\Components\Section::make('Productos del Combo')
                    ->schema([
                        Forms\Components\Repeater::make('combo_products')
                            ->label('Productos')
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Producto')
                                    ->options(\App\Models\Product::where('is_active', true)->pluck('name', 'id'))
                                    ->searchable()
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        if ($state) {
                                            $product = \App\Models\Product::find($state);
                                            if ($product) {
                                                $set('_product_price', $product->price);
                                            }
                                        }
                                    }),
                                
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Cantidad')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->minValue(1),
                                
                                Forms\Components\Placeholder::make('_product_price')
                                    ->label('Precio unitario')
                                    ->content(function ($get) {
                                        $productId = $get('product_id');
                                        if ($productId) {
                                            $product = \App\Models\Product::find($productId);
                                            if ($product) {
                                                return 'S/' . number_format($product->price, 2);
                                            }
                                        }
                                        return '-';
                                    }),
                            ])
                            ->columns(3)
                            ->defaultItems(1)
                            ->addActionLabel('Agregar Producto')
                            ->helperText('Selecciona los productos que incluye el combo')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Imagen')
                    ->circular()
                    ->defaultImageUrl(url('/images/combo-default.png')),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('price')
                    ->label('Precio')
                    ->money('PEN')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('products_count')
                    ->label('Productos')
                    ->counts('products')
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\TextColumn::make('products_total')
                    ->label('Valor Real')
                    ->money('PEN')
                    ->state(function ($record) {
                        return $record->products_total;
                    })
                    ->description(function ($record) {
                        $savings = $record->savings;
                        if ($savings > 0) {
                            return '¡Ahorro: S/' . number_format($savings, 2) . '!';
                        }
                        return null;
                    })
                    ->color('warning'),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('order')
                    ->label('Orden')
                    ->sortable()
                    ->alignCenter(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Activo')
                    ->placeholder('Todos')
                    ->trueLabel('Solo activos')
                    ->falseLabel('Solo inactivos'),
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
            'index' => Pages\ListCombos::route('/'),
            'create' => Pages\CreateCombo::route('/create'),
            'edit' => Pages\EditCombo::route('/{record}/edit'),
        ];
    }
}
