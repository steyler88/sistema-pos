<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Product; 
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get; // Importante para leer datos del formulario
use Filament\Forms\Set; // Importante para escribir datos (como el total)
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    // --- CONFIGURACIÓN VISUAL DEL MENÚ ---
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar'; 
    protected static ?string $navigationLabel = 'Ventas / Caja'; 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ============================================================
                // SECCIÓN 1: DATOS DEL CLIENTE Y ESTADO
                // ============================================================
                Forms\Components\Section::make('Información del Pedido')
                    ->schema([
                        Forms\Components\TextInput::make('customer_name')
                            ->label('Cliente')
                            ->placeholder('Público General')
                            ->required(), // Campo obligatorio

                        Forms\Components\Select::make('payment_method')
                            ->label('Forma de Pago')
                            ->options([
                                'cash' => 'Efectivo',
                                'card' => 'Tarjeta',
                                'yape' => 'Yape / Plin',
                            ])
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Estado')
                            ->options([
                                'pending' => 'Pendiente',
                                'completed' => 'Pagado y Entregado',
                                'cancelled' => 'Cancelado',
                            ])
                            ->default('pending') // Por defecto nace como pendiente
                            ->required(),
                    ])->columns(3), // Organiza estos 3 campos en columnas

                // ============================================================
                // SECCIÓN 2: CARRITO DE COMPRAS (DONDE OCURRE LA MAGIA)
                // ============================================================
                Forms\Components\Section::make('Productos')
                    ->schema([
                        Forms\Components\Repeater::make('items') // Permite agregar muchas líneas
                            ->relationship()
                            ->schema([
                                // --- COLUMNA 1: SELECCIONAR PIZZA ---
                                Forms\Components\Select::make('product_id')
                                    ->label('Producto')
                                    ->options(Product::query()->pluck('name', 'id'))
                                    ->required()
                                    ->searchable() // Permite escribir para buscar
                                    ->reactive()   // Escucha cambios para actualizar precios
                                    
                                    // LOGICA: Cuando eliges pizza, busca el precio y actualiza el total
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        $price = Product::find($state)?->price ?? 0;
                                        $set('unit_price', $price); // Pone el precio unitario
                                        self::updateTotal($get, $set); // Recalcula la suma total
                                    }),

                                // --- COLUMNA 2: CANTIDAD ---
                                Forms\Components\TextInput::make('quantity')
                                    ->label('Cantidad')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->live() // Escucha en vivo cada vez que escribes
                                    // LOGICA: Al cambiar cantidad, recalcula el total
                                    ->afterStateUpdated(fn (Get $get, Set $set) => self::updateTotal($get, $set)),

                                // --- COLUMNA 3: PRECIO UNITARIO ---
                                Forms\Components\TextInput::make('unit_price')
                                    ->label('Precio Unit.')
                                    ->numeric()
                                    ->required()
                                    ->prefix('S/')
                                    ->live() // Escucha en vivo
                                    // LOGICA: Si cambias el precio manualmente, recalcula el total
                                    ->afterStateUpdated(fn (Get $get, Set $set) => self::updateTotal($get, $set)),
                            ])
                            ->columns(3) // Alinea producto, cantidad y precio
                            ->addActionLabel('Agregar otro producto')
                            ->live() // Escucha si borras una línea para restar del total
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::updateTotal($get, $set)),
                        
                        // --- CAMPO FINAL: TOTAL AUTOMÁTICO ---
                        Forms\Components\TextInput::make('total_price')
                            ->label('TOTAL A COBRAR')
                            ->numeric()
                            ->prefix('S/')
                            ->readOnly() // No se puede editar a mano, solo se calcula solo
                            ->default(0),
                    ]),
            ]);
    }

    // ============================================================
    // TABLA: LISTA DE VENTAS (LO QUE VES AL ENTRAR AL MENÚ)
    // ============================================================
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('N° Venta')
                    ->sortable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Cliente')
                    ->searchable(),

                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total')
                    ->money('PEN')
                    ->sortable()
                    ->weight('bold'), // Pone el texto en negrita

                Tables\Columns\TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                    }),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha/Hora')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    // ============================================================
    // FUNCIÓN AUXILIAR: LA MATEMÁTICA DE LA SUMA
    // ============================================================
    // Esta función recorre todas las filas del carrito y suma (precio * cantidad)
    public static function updateTotal(Get $get, Set $set): void
    {
        $items = $get('items'); // Obtiene la lista de productos
        $sum = 0;

        if ($items) {
            foreach ($items as $item) {
                // Si la cantidad o el precio están vacíos, asume 0
                $qty = (int) ($item['quantity'] ?? 0);
                $price = (float) ($item['unit_price'] ?? 0);
                
                $sum += $qty * $price;
            }
        }

        $set('total_price', $sum); // Escribe el resultado en el campo TOTAL
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}