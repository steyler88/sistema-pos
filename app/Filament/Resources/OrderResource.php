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
    protected static ?string $navigationLabel = 'Nueva Venta';
    protected static ?string $modelLabel = 'Venta';
    protected static ?string $pluralModelLabel = 'Ventas';
    protected static ?string $navigationGroup = 'Ventas / Caja'; 

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // ============================================================
                // SECCIÓN 1: TIPO DE PEDIDO (BOTONES GRANDES Y VISUALES)
                // ============================================================
                Forms\Components\Section::make('🍕 Tipo de Pedido')
                    ->description('Selecciona dónde se consumirá el pedido')
                    ->schema([
                        Forms\Components\ToggleButtons::make('order_type')
                            ->label('¿Dónde será el servicio?')
                            ->options([
                                'delivery' => 'Delivery',
                                'para_llevar' => 'Para Llevar',
                                'mesa' => 'Mesa',
                                'barra' => 'Barra',
                            ])
                            ->icons([
                                'delivery' => 'heroicon-o-truck',
                                'para_llevar' => 'heroicon-o-shopping-bag',
                                'mesa' => 'heroicon-o-table-cells',
                                'barra' => 'heroicon-o-chart-bar',
                            ])
                            ->colors([
                                'delivery' => 'success',
                                'para_llevar' => 'warning',
                                'mesa' => 'info',
                                'barra' => 'danger',
                            ])
                            ->inline()
                            ->required()
                            ->default('delivery') // 👈 VALOR POR DEFECTO: DELIVERY
                            ->live(), // Para mostrar/ocultar el campo de ubicación

                        // Campo condicional: solo se muestra si es Mesa o Barra
                        Forms\Components\Select::make('table_location')
                            ->label('Ubicación')
                            ->options([
                                'Mesa 1' => 'Mesa 1',
                                'Mesa 2' => 'Mesa 2',
                                'Barra' => 'Barra',
                            ])
                            ->visible(fn (Get $get): bool => 
                                in_array($get('order_type'), ['mesa', 'barra'])
                            )
                            ->required(fn (Get $get): bool => 
                                in_array($get('order_type'), ['mesa', 'barra'])
                            ),
                        
                        // Canal de Venta (Multi-Precios)
                        Forms\Components\ToggleButtons::make('sales_channel')
                            ->label('💰 Canal de Venta')
                            ->options([
                                'local' => 'Local',
                                'rappi' => 'Rappi',
                                'web' => 'Web',
                            ])
                            ->icons([
                                'local' => 'heroicon-o-building-storefront',
                                'rappi' => 'heroicon-o-truck',
                                'web' => 'heroicon-o-globe-alt',
                            ])
                            ->colors([
                                'local' => 'info',
                                'rappi' => 'warning',
                                'web' => 'success',
                            ])
                            ->inline()
                            ->required()
                            ->default('local') // 👈 VALOR POR DEFECTO: LOCAL
                            ->helperText('Los precios de los productos se ajustarán según el canal seleccionado'),
                    ])->columns(1),

                // ============================================================
                // SECCIÓN 2: DATOS DEL CLIENTE Y PAGO
                // ============================================================
                Forms\Components\Section::make('👤 Datos del Cliente')
                    ->schema([
                        Forms\Components\TextInput::make('customer_name')
                            ->label('Nombre del Cliente')
                            ->placeholder('Ej: Juan Pérez')
                            ->default('Cliente General')
                            ->required(),

                        Forms\Components\ToggleButtons::make('payment_method')
                            ->label('Forma de Pago')
                            ->options([
                                'yape' => 'Yape / Plin',
                                'cash' => 'Efectivo',
                                'card' => 'Tarjeta',
                            ])
                            ->icons([
                                'yape' => 'heroicon-o-device-phone-mobile',
                                'cash' => 'heroicon-o-banknotes',
                                'card' => 'heroicon-o-credit-card',
                            ])
                            ->colors([
                                'yape' => 'success',
                                'cash' => 'warning',
                                'card' => 'info',
                            ])
                            ->inline()
                            ->default('yape') // 👈 VALOR POR DEFECTO: YAPE
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label('Estado del Pedido')
                            ->options([
                                'pending' => '⏳ Pendiente',
                                'completed' => '✅ Pagado y Entregado',
                                'cancelled' => '❌ Cancelado',
                            ])
                            ->default('pending') // 👈 VALOR POR DEFECTO: PENDIENTE
                            ->required(),
                    ])->columns(3),

                // ============================================================
                // SECCIÓN 3: CARRITO DE PRODUCTOS
                // ============================================================
                Forms\Components\Section::make('🛒 Productos del Pedido')
                    ->schema([
                        Forms\Components\Repeater::make('items')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('product_id')
                                    ->label('Producto')
                                    ->options(Product::query()->pluck('name', 'id'))
                                    ->required()
                                    ->searchable()
                                    ->live() // ✅ Se activa INMEDIATAMENTE al seleccionar el producto
                                    ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                        if ($state) {
                                            $price = Product::find($state)?->price ?? 0;
                                            $set('unit_price', $price);
                                            
                                            // ✅ Calcular total inmediatamente con cantidad por defecto
                                            self::updateTotal($get, $set);
                                        }
                                    }),

                                Forms\Components\TextInput::make('quantity')
                                    ->label('Cantidad')
                                    ->numeric()
                                    ->default(1)
                                    ->required()
                                    ->minValue(1)
                                    ->live()
                                    ->afterStateUpdated(fn (Get $get, Set $set) => self::updateTotal($get, $set)),

                                Forms\Components\TextInput::make('unit_price')
                                    ->label('Precio Unit.')
                                    ->numeric()
                                    ->required()
                                    ->prefix('S/')
                                    ->live()
                                    ->afterStateUpdated(fn (Get $get, Set $set) => self::updateTotal($get, $set)),
                            ])
                            ->columns(3)
                            ->addActionLabel('➕ Agregar otro producto')
                            ->live()
                            ->afterStateUpdated(fn (Get $get, Set $set) => self::updateTotal($get, $set))
                            ->defaultItems(1), // Inicia con una fila lista
                        
                        Forms\Components\TextInput::make('total_price')
                            ->label('💰 TOTAL A COBRAR')
                            ->numeric()
                            ->prefix('S/')
                            ->readOnly()
                            ->default(0)
                            ->extraAttributes(['class' => 'text-2xl font-bold']),
                    ]),

                // ============================================================
                // SECCIÓN 4: NOTAS ADICIONALES
                // ============================================================
                Forms\Components\Section::make('📝 Notas Adicionales')
                    ->description('Instrucciones especiales para el pedido')
                    ->schema([
                        Forms\Components\Textarea::make('notes')
                            ->label('Notas')
                            ->placeholder('Ej: Sin cebolla, extra salsa, etc.')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible()
                    ->collapsed(),
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
                    ->label('#')
                    ->sortable()
                    ->searchable(),

                // Nuevo: Tipo de Pedido con Badge
                Tables\Columns\BadgeColumn::make('order_type')
                    ->label('Tipo')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'delivery' => 'Delivery',
                        'para_llevar' => 'Para Llevar',
                        'mesa' => 'Mesa',
                        'barra' => 'Barra',
                        default => $state,
                    })
                    ->colors([
                        'success' => 'delivery',
                        'warning' => 'para_llevar',
                        'info' => 'mesa',
                        'danger' => 'barra',
                    ])
                    ->icons([
                        'delivery' => 'heroicon-o-truck',
                        'para_llevar' => 'heroicon-o-shopping-bag',
                        'mesa' => 'heroicon-o-table-cells',
                        'barra' => 'heroicon-o-chart-bar',
                    ])
                    ->sortable(),
                
                // Nuevo: Canal de Venta
                Tables\Columns\BadgeColumn::make('sales_channel')
                    ->label('Canal')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'local' => 'Local',
                        'rappi' => 'Rappi',
                        'web' => 'Web',
                        default => 'Local',
                    })
                    ->colors([
                        'info' => 'local',
                        'warning' => 'rappi',
                        'success' => 'web',
                    ])
                    ->icons([
                        'local' => 'heroicon-o-building-storefront',
                        'rappi' => 'heroicon-o-truck',
                        'web' => 'heroicon-o-globe-alt',
                    ])
                    ->sortable(),

                // Nuevo: Ubicación (Mesa/Barra)
                Tables\Columns\TextColumn::make('table_location')
                    ->label('Ubicación')
                    ->default('—')
                    ->searchable(),

                Tables\Columns\TextColumn::make('customer_name')
                    ->label('Cliente')
                    ->searchable()
                    ->limit(20),

                // Nuevo: Forma de Pago con icono
                Tables\Columns\BadgeColumn::make('payment_method')
                    ->label('Pago')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'yape' => 'Yape',
                        'cash' => 'Efectivo',
                        'card' => 'Tarjeta',
                        default => $state,
                    })
                    ->colors([
                        'success' => 'yape',
                        'warning' => 'cash',
                        'info' => 'card',
                    ]),

                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total')
                    ->money('PEN')
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                Tables\Columns\BadgeColumn::make('status')
                    ->label('Estado')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Pendiente',
                        'completed' => 'Completado',
                        'cancelled' => 'Cancelado',
                        default => $state,
                    })
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha/Hora')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                // Filtro por tipo de pedido
                Tables\Filters\SelectFilter::make('order_type')
                    ->label('Tipo de Pedido')
                    ->options([
                        'delivery' => 'Delivery',
                        'para_llevar' => 'Para Llevar',
                        'mesa' => 'Mesa',
                        'barra' => 'Barra',
                    ]),
                
                // Filtro por estado
                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'completed' => 'Completado',
                        'cancelled' => 'Cancelado',
                    ]),

                // Filtro por forma de pago
                Tables\Filters\SelectFilter::make('payment_method')
                    ->label('Forma de Pago')
                    ->options([
                        'yape' => 'Yape',
                        'cash' => 'Efectivo',
                        'card' => 'Tarjeta',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
    /**
     * Calcula el total sumando todos los items del carrito.
     * Se ejecuta cada vez que cambia un producto, cantidad o precio.
     */
    public static function updateTotal(Get $get, Set $set): void
    {
        $items = $get('items'); // Obtiene la lista de productos
        $sum = 0;

        if ($items && is_array($items)) {
            foreach ($items as $item) {
                // Validar que el item sea un array
                if (!is_array($item)) {
                    continue;
                }

                // Si la cantidad está vacía o es 0, usa 1 por defecto
                $qty = isset($item['quantity']) ? (int) $item['quantity'] : 1;
                if ($qty <= 0) {
                    $qty = 1;
                }
                
                // Obtener el precio unitario
                $price = isset($item['unit_price']) ? (float) $item['unit_price'] : 0;
                
                // Sumar al total
                $sum += $qty * $price;
            }
        }

        // Establecer el total con 2 decimales
        $set('total_price', round($sum, 2));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'active' => Pages\ActiveOrders::route('/active'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
    
    // Configurar submenú
    public static function getNavigationItems(): array
    {
        return [
            \Filament\Navigation\NavigationItem::make('POS Táctil')
                ->group(static::getNavigationGroup())
                ->icon('heroicon-o-device-tablet')
                ->url(route('pos.touch'))
                ->isActiveWhen(fn () => request()->routeIs('pos.touch'))
                ->sort(1),
            
            \Filament\Navigation\NavigationItem::make('Nueva Venta (Formulario)')
                ->group(static::getNavigationGroup())
                ->icon('heroicon-o-plus-circle')
                ->url(static::getUrl('create'))
                ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.orders.create'))
                ->sort(2),
            
            \Filament\Navigation\NavigationItem::make('Órdenes Activas')
                ->group(static::getNavigationGroup())
                ->icon('heroicon-o-clock')
                ->url(static::getUrl('active'))
                ->badge(fn () => \App\Models\Order::where('status', 'pending')->count(), color: 'warning')
                ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.orders.active'))
                ->sort(3),
            
            \Filament\Navigation\NavigationItem::make('Historial')
                ->group(static::getNavigationGroup())
                ->icon('heroicon-o-clipboard-document-list')
                ->url(static::getUrl('index'))
                ->isActiveWhen(fn () => request()->routeIs('filament.admin.resources.orders.index'))
                ->sort(4),
        ];
    }
}