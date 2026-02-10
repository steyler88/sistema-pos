<div>
<style>
    /* Sistema de Botones Uniforme - POS ElchePizza */
    
    /* Botón Base - Todos los botones comparten estos estilos */
    .btn-pos {
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        cursor: pointer;
        font-size: 0.75rem;
        padding: 0.625rem 1rem;
        border: 2px solid;
    }

    /* Botones de Selección (Mesa, Delivery, Para Llevar) */
    .btn-select {
        background: #374151;
        border-color: #4b5563;
        color: #d1d5db;
    }

    .btn-select:hover {
        background: #4b5563;
        border-color: #6b7280;
        color: #fff;
    }

    .btn-select.active {
        background: #f97316;
        border-color: #f97316;
        color: white;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.2);
    }

    /* Botones de Pago (Yape, Efectivo, Tarjeta) */
    .btn-payment {
        background: #374151;
        border-color: #4b5563;
        color: #d1d5db;
    }

    .btn-payment:hover {
        background: #4b5563;
        border-color: #6b7280;
        color: #fff;
    }

    .btn-payment.active {
        background: #f97316;
        border-color: #f97316;
        color: white;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.2);
    }

    /* Botones de Acción Secundaria (Orden de Cocina, etc.) */
    .btn-secondary {
        background: #1f2937;
        border-color: #374151;
        color: #9ca3af;
    }

    .btn-secondary:hover {
        background: #374151;
        border-color: #4b5563;
        color: #d1d5db;
    }

    /* Botón Principal (CUENTA) */
    .btn-primary-pos {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        border-color: #f97316;
        color: white;
        font-weight: 700;
        box-shadow: 0 4px 14px 0 rgba(249, 115, 22, 0.4);
        font-size: 0.875rem;
    }

    .btn-primary-pos:hover {
        background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%);
        box-shadow: 0 6px 20px 0 rgba(249, 115, 22, 0.6);
        transform: translateY(-1px);
    }

    .btn-primary-pos:active {
        transform: translateY(0);
    }

    /* Animación para mensajes flash */
    @keyframes slide-in {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    .animate-slide-in {
        animation: slide-in 0.3s ease-out;
    }
</style>

<div class="h-screen bg-white dark:bg-gray-900 flex w-full m-0 p-0">
    
    <!-- SECCIÓN CENTRAL: PRODUCTOS (ZONA NARANJA) - Ocupa todo el espacio disponible -->
    <div class="flex-1 flex flex-col bg-white dark:bg-gray-900 border-r-2 border-orange-500">
            
            <!-- BUSCADOR PRINCIPAL - ESTILO IZIREST -->
            <div class="p-4 bg-white dark:bg-gray-800 border-b-4 border-orange-600">
                <div class="relative">
                    <input type="text" 
                           wire:model.live="searchTerm"
                           placeholder="Busque su elemento de menú aquí" 
                           class="w-full px-4 py-3 pl-10 text-base border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 dark:bg-gray-700 dark:text-white placeholder-gray-400 transition-all">
                    <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- PESTAÑAS: COMBOS + CATEGORÍAS -->
            <div class="bg-gray-900 border-b border-gray-700">
                <div class="flex overflow-x-auto scrollbar-hide">
                    <!-- PESTAÑA DE COMBOS - DESTACADA -->
                    <button wire:click="showCombos"
                            class="px-6 py-3 font-bold text-xs whitespace-nowrap transition-all flex items-center gap-2 {{ $selectedView === 'combos' ? 'bg-gradient-to-r from-orange-500 to-red-500 text-white shadow-lg' : 'bg-gradient-to-r from-orange-600 to-red-600 text-white hover:from-orange-500 hover:to-red-500' }}">
                        <span class="text-base">🎁</span> COMBOS
                    </button>
                    
                    <!-- SEPARADOR -->
                    <div class="w-px bg-gray-700 my-2"></div>
                    
                    <!-- CATEGORÍAS -->
                    <button wire:click="selectCategory('Mostrar todo')"
                            class="px-6 py-3 font-bold text-xs whitespace-nowrap transition-all flex items-center gap-2 {{ ($selectedView === 'categories' && $selectedCategory === 'Mostrar todo') ? 'bg-black text-white border-b-3 border-orange-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <span class="text-base">📋</span> MOSTRAR TODO
                    </button>
                    @foreach($categories as $category)
                        <button wire:click="selectCategory('{{ $category->name }}')"
                                class="px-6 py-3 font-bold text-xs whitespace-nowrap transition-all flex items-center gap-2 {{ ($selectedView === 'categories' && $selectedCategory === $category->name) ? 'bg-black text-white border-b-3 border-orange-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
                                style="border-color: {{ ($selectedView === 'categories' && $selectedCategory === $category->name) ? $category->color : 'transparent' }};">
                            @if($category->icon)
                                <span class="text-base">{{ $category->icon }}</span>
                            @endif
                            {{ strtoupper($category->name) }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- GRID DE PRODUCTOS O COMBOS -->
            <div class="flex-1 overflow-y-auto p-3 bg-gray-50 dark:bg-gray-900" style="max-height: calc(100vh - 200px);">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-2">
                    @if($isComboView ?? false)
                        <!-- VISTA DE COMBOS -->
                        @forelse($items as $combo)
                            <button wire:click="addComboToCart({{ $combo->id }})"
                                    class="group relative bg-gradient-to-br from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 rounded-lg overflow-hidden shadow-lg hover:shadow-2xl hover:shadow-orange-500/50 transition-all transform hover:scale-105 active:scale-95 border-2 border-orange-300 dark:border-orange-700 hover:border-orange-500">
                                
                                <!-- Badge "COMBO" -->
                                <div class="absolute top-1 right-1 bg-gradient-to-r from-orange-500 to-red-500 text-white px-2 py-0.5 rounded-full font-black text-[9px] shadow-md z-10">
                                    🎁 COMBO
                                </div>
                                
                                <!-- Imagen del Combo -->
                                <div class="relative h-28 bg-gradient-to-br from-orange-200 to-red-200 dark:from-orange-800 dark:to-red-800">
                                    @if($combo->image)
                                        <img src="{{ asset('storage/' . $combo->image) }}" 
                                             alt="{{ $combo->name }}" 
                                             class="w-full h-full object-cover"
                                             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center\'><span class=\'text-5xl\'>🎁</span></div>';">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <span class="text-5xl">🎁</span>
                                        </div>
                                    @endif
                                    
                                    <!-- Badge de Precio -->
                                    <div class="absolute bottom-1 left-1 bg-green-600 text-white px-2 py-1 rounded-md font-bold text-xs shadow-md">
                                        S/{{ number_format($combo->price, 2) }}
                                    </div>
                                    
                                    <!-- Badge de Ahorro -->
                                    @if($combo->savings > 0)
                                    <div class="absolute bottom-1 right-1 bg-yellow-400 text-gray-900 px-2 py-0.5 rounded-md font-bold text-[9px] shadow-md">
                                        ¡Ahorro S/{{ number_format($combo->savings, 2) }}!
                                    </div>
                                    @endif
                                </div>
                                
                                <!-- Nombre del Combo -->
                                <div class="p-2 text-center bg-white dark:bg-gray-800 border-t-2 border-orange-300 dark:border-orange-700">
                                    <p class="font-bold text-xs text-gray-900 dark:text-white line-clamp-2">
                                        {{ $combo->name }}
                                    </p>
                                    @if($combo->products_count > 0)
                                    <p class="text-[9px] text-orange-600 dark:text-orange-400 mt-0.5">
                                        {{ $combo->products_count }} productos
                                    </p>
                                    @endif
                                </div>
                            </button>
                        @empty
                            <div class="col-span-full text-center py-12 text-gray-500">
                                <div class="text-6xl mb-4">🎁</div>
                                <p class="text-lg font-semibold">No hay combos disponibles</p>
                                <p class="text-sm">Crea combos desde el menú "Combos"</p>
                            </div>
                        @endforelse
                    @else
                        <!-- VISTA DE PRODUCTOS -->
                        @forelse($items as $product)
                            <button wire:click="addToCart({{ $product->id }})"
                                    class="group relative bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow hover:shadow-xl hover:shadow-orange-500/50 transition-all transform hover:scale-105 active:scale-95 border-2 border-transparent hover:border-orange-500">
                                
                                <!-- Imagen del Producto -->
                                <div class="relative h-28 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-800">
                                    @if($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             class="w-full h-full object-cover"
                                             onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center\'><span class=\'text-4xl\'>🍕</span></div>';">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <span class="text-4xl">🍕</span>
                                        </div>
                                    @endif
                                    
                                    <!-- Badge de Precio -->
                                    <div class="absolute top-1 left-1 bg-gradient-to-br from-orange-500 to-orange-600 text-white px-2 py-1 rounded-md font-bold text-xs shadow-lg">
                                        S/{{ number_format($product->price, 2) }}
                                    </div>
                                </div>
                                
                                <!-- Nombre del Producto -->
                                <div class="p-2 text-center bg-white dark:bg-gray-800">
                                    <p class="font-semibold text-xs text-gray-900 dark:text-white line-clamp-2">
                                        {{ $product->name }}
                                    </p>
                                </div>
                            </button>
                        @empty
                            <div class="col-span-full text-center py-12 text-gray-500">
                                <div class="text-6xl mb-4">📦</div>
                                <p class="text-lg font-semibold">No hay productos disponibles</p>
                                <p class="text-sm">Agrega productos desde el menú "Productos"</p>
                            </div>
                        @endforelse
                    @endif
                </div>
            </div>
    </div>

    <!-- PANEL DERECHO: DETALLES DE LA ORDEN (ZONA NARANJA) - Ancho fijo optimizado -->
    <div class="w-96 bg-white dark:bg-gray-800 border-l-4 border-orange-500 shadow-xl overflow-y-auto h-screen">
            
            <!-- Header: Info del Pedido con ícono -->
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-3 text-white flex items-center gap-3">
                <!-- Ícono y título -->
                <div class="flex items-center gap-2 flex-1">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                    </svg>
                    <div>
                        <div class="text-xs opacity-90">CAJA COCINA</div>
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-bold">Saldo: S/${{ number_format($total, 2) }}</span>
                            <span class="text-xs bg-orange-700 px-2 py-0.5 rounded-full">Estado: Abierta</span>
                        </div>
                    </div>
                </div>
                
                <!-- Botón cambiar -->
                <button class="text-xs bg-white bg-opacity-20 hover:bg-opacity-30 px-3 py-1.5 rounded-md transition-all font-medium">
                    🔄 Cambiar
                </button>
            </div>

            <!-- Tipo de Servicio -->
<div class="p-3 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="text-[10px] text-gray-600 dark:text-gray-400 mb-2 font-semibold uppercase">Tipo de Servicio:</div>
                <div class="flex gap-2">
                    <button wire:click="$set('order_type', 'mesa')"
                            class="btn-pos btn-select flex-1 {{ $order_type === 'mesa' ? 'active' : '' }}">
                        🍽️ Mesa
                    </button>
                    <button wire:click="$set('order_type', 'delivery')"
                            class="btn-pos btn-select flex-1 {{ $order_type === 'delivery' ? 'active' : '' }}">
                        🛵 Delivery
                    </button>
                    <button wire:click="$set('order_type', 'para_llevar')"
                            class="btn-pos btn-select flex-1 {{ $order_type === 'para_llevar' ? 'active' : '' }}">
                        📦 Para Llevar
                    </button>
                </div>
            </div>

            <!-- CANAL DE VENTA (NUEVO - Multi-Precios) -->
            <div class="bg-gray-100 dark:bg-gray-800 p-3 rounded-lg">
                <div class="text-[10px] text-gray-600 dark:text-gray-400 mb-2 font-semibold uppercase flex items-center gap-1">
                    <span>💰</span> Canal de Venta:
                </div>
                <div class="flex gap-2">
                    <button wire:click="$set('sales_channel', 'local')"
                            class="btn-pos btn-select flex-1 {{ $sales_channel === 'local' ? 'active' : '' }}">
                        🏪 Local
                    </button>
                    <button wire:click="$set('sales_channel', 'rappi')"
                            class="btn-pos btn-select flex-1 {{ $sales_channel === 'rappi' ? 'active' : '' }}">
                        🛵 Rappi
                    </button>
                    <button wire:click="$set('sales_channel', 'web')"
                            class="btn-pos btn-select flex-1 {{ $sales_channel === 'web' ? 'active' : '' }}">
                        🌐 Web
                    </button>
                </div>
                <div class="text-[9px] text-gray-500 dark:text-gray-500 mt-1 text-center">
                    Los precios se ajustan según el canal seleccionado
                </div>
            </div>

            <!-- Pedido, Mesa, Comensales, Cliente y Camarero - MÁS COMPACTO -->
            <div class="px-2 py-1.5 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                <div class="flex flex-wrap gap-x-3 gap-y-1 text-xs items-center">
                    <!-- Pedido -->
                    <div class="flex items-center gap-1">
                        <span class="text-gray-500 dark:text-gray-400">📋</span>
                        <span class="font-bold text-gray-900 dark:text-white">#M06</span>
                        <button class="text-blue-600 hover:text-blue-700 text-xs">⚙️</button>
                    </div>
                    
                    <!-- Mesa (si aplica) -->
                    @if(in_array($order_type, ['mesa', 'barra']))
                    <div class="flex items-center gap-1">
                        <span class="text-gray-500 dark:text-gray-400">🍽️</span>
                        <x-form-select wire:model="table_location" class="text-xs !px-1 !py-0.5 min-w-[80px]">
                            <option value="Mesa 1">Mesa 1</option>
                            <option value="Mesa 2">Mesa 2</option>
                            <option value="Barra">Barra</option>
                        </x-form-select>
                    </div>
                    @endif
                    
                    <!-- Comensales -->
                    <div class="flex items-center gap-1">
                        <span class="text-gray-500 dark:text-gray-400">👥</span>
                        <span class="font-bold text-gray-900 dark:text-white">1</span>
                        <button class="text-blue-600 hover:text-blue-700 text-xs">✏️</button>
                    </div>
                    
                    <!-- Cliente -->
                    <div class="flex items-center gap-1 flex-1 min-w-[120px]">
                        <span class="text-gray-500 dark:text-gray-400">👤</span>
                        <x-form-input wire:model="customer_name" type="text" placeholder="Cliente" class="text-xs !px-1 !py-0.5 flex-1" />
                    </div>
                    
                    <!-- Camarero -->
                    <div class="flex items-center gap-1 flex-1 min-w-[100px]">
                        <span class="text-gray-500 dark:text-gray-400">🧑‍🍳</span>
                        <x-form-select class="text-xs !px-1 !py-0.5 flex-1">
                            <option>Jaquelyn Battle</option>
                        </x-form-select>
                    </div>
                </div>
            </div>

            <!-- TABLA DE ITEMS - COMPACTA, LIMPIA Y ALINEADA -->
            <div class="flex-1 overflow-y-auto bg-white dark:bg-gray-800" style="min-height: 300px; max-height: 400px;">
                
                <!-- Encabezado de Tabla - ALINEADO -->
                <div class="bg-gray-800 dark:bg-gray-700 px-2 py-1.5 border-b-2 border-gray-600 sticky top-0 z-10">
                    <div class="grid grid-cols-12 gap-1 text-[9px] font-bold text-white uppercase tracking-wide">
                        <div class="col-span-4">Nombre</div>
                        <div class="col-span-3 text-center">Cant.</div>
                        <div class="col-span-2 text-right pr-1">Precio</div>
                        <div class="col-span-3 text-right pr-1">Total</div>
                    </div>
                </div>

                <!-- Items del Pedido -->
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($cart as $key => $item)
                        <div class="px-2 py-1.5 hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors group">
                            <div class="grid grid-cols-12 gap-1 items-center">
                                <!-- Nombre -->
                                <div class="col-span-4">
                                    <div class="font-semibold text-[10px] text-gray-900 dark:text-white leading-tight group-hover:text-gray-900 dark:group-hover:text-white">
                                        {{ $item['name'] }}
                                    </div>
                                    <button class="text-[8px] text-blue-600 hover:text-blue-700 dark:text-blue-400">+ Nota</button>
                                </div>

                                <!-- Controles de Cantidad -->
                                <div class="col-span-3 flex items-center justify-center gap-1">
                                    <button wire:click="decreaseQuantity('{{ $key }}')"
                                            class="w-5 h-5 flex items-center justify-center bg-gray-200 hover:bg-red-500 hover:text-white dark:bg-gray-600 dark:hover:bg-red-600 text-gray-700 dark:text-white rounded text-xs font-bold transition-all">
                                        −
                                    </button>
                                    <span class="w-6 text-center font-bold text-[10px] text-gray-900 dark:text-white group-hover:text-gray-900 dark:group-hover:text-white">
                                        {{ $item['quantity'] }}
                                    </span>
                                    <button wire:click="increaseQuantity('{{ $key }}')"
                                            class="w-5 h-5 flex items-center justify-center bg-gray-200 hover:bg-green-500 hover:text-white dark:bg-gray-600 dark:hover:bg-green-600 text-gray-700 dark:text-white rounded text-xs font-bold transition-all">
                                        +
                                    </button>
                                </div>

                                <!-- Precio Unitario -->
                                <div class="col-span-2 text-right text-[10px] font-semibold text-gray-700 dark:text-gray-300 pr-1 group-hover:text-gray-900 dark:group-hover:text-white">
                                    S/{{ number_format($item['price'], 2) }}
                                </div>

                                <!-- Total + Eliminar -->
                                <div class="col-span-3 flex items-center justify-end gap-1 pr-1">
                                    <span class="font-bold text-[10px] text-gray-900 dark:text-white group-hover:text-gray-900 dark:group-hover:text-white">
                                        S/{{ number_format($item['quantity'] * $item['price'], 2) }}
                                    </span>
                                    <button wire:click="removeFromCart('{{ $key }}')" 
                                            class="w-5 h-5 flex items-center justify-center text-red-500 hover:text-white hover:bg-red-600 rounded transition-all ml-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400">
                            <div class="text-4xl mb-2">🍽️</div>
                            <div class="font-semibold text-xs text-gray-500 dark:text-gray-400">Sin productos</div>
                            <div class="text-[10px] text-gray-400">Selecciona productos del menú</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Botón Agregar Descuento -->
            @if(!empty($cart))
            <div class="px-2 py-1.5 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                <button wire:click="$toggle('showDiscountInput')" class="w-full px-2 py-1.5 bg-white dark:bg-gray-700 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded text-xs font-semibold text-gray-700 dark:text-gray-300 hover:border-orange-500 hover:text-orange-600 dark:hover:text-orange-400 transition-all flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Agregar Descuento
                </button>
                
                @if($showDiscountInput ?? false)
                <div class="mt-1 flex gap-1">
                    <x-form-input wire:model="discount" type="number" step="0.01" placeholder="Monto" class="flex-1 !px-2 !py-1 text-xs" />
                    <x-button-primary wire:click="applyDiscount" class="!px-3 !py-1 text-xs">
                        Aplicar
                    </x-button-primary>
                </div>
                @endif
            </div>
            @endif

            <!-- Resumen de Totales - IDÉNTICO A LA REFERENCIA -->
            <div class="border-t-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800">
                <div class="px-4 py-3 space-y-1.5">
                    <!-- Contador de Items -->
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Item(s)</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ count($cart) }}</span>
                    </div>
                    
                    <!-- Subtotal -->
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Sub Total</span>
                        <span class="font-semibold text-gray-900 dark:text-white">S/{{ number_format($subtotal ?? $total, 2) }}</span>
                    </div>
                    
                    <!-- Descuento (si existe) -->
                    @if(($discount ?? 0) > 0)
                    <div class="flex justify-between text-sm items-center">
                        <span class="text-red-600 dark:text-red-400">Descuento</span>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-red-600 dark:text-red-400">-S/{{ number_format($discount, 2) }}</span>
                            <button wire:click="removeDiscount" class="text-red-500 hover:text-red-700">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    @endif
                    
                    <!-- IGV -->
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">IGV (18%)</span>
                        <span class="font-semibold text-gray-900 dark:text-white">S/{{ number_format(($total - ($discount ?? 0)) * 0.18, 2) }}</span>
                    </div>
                    
                    <!-- Total de Impuestos -->
                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                        <span>Total de Impuestos (Impuesto Incluido)</span>
                        <span>S/{{ number_format(($total - ($discount ?? 0)) * 0.18, 2) }}</span>
                    </div>
                    
                    <!-- TOTAL PRINCIPAL -->
                    <div class="pt-2 border-t-2 border-gray-300 dark:border-gray-600 mt-2">
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-gray-900 dark:text-white">Total</span>
                            <span class="text-3xl font-black text-gray-900 dark:text-white">S/{{ number_format($total - ($discount ?? 0), 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Forma de Pago -->
                <div class="px-3 pb-3 border-t border-gray-200 dark:border-gray-700 pt-3">
                    <div class="text-[10px] text-gray-600 dark:text-gray-400 mb-2 font-semibold uppercase">Forma de Pago:</div>
                    <div class="flex gap-2">
                        <button wire:click="$set('payment_method', 'yape')"
                                class="btn-pos btn-payment flex-1 {{ $payment_method === 'yape' ? 'active' : '' }}">
                            💳 Yape
                        </button>
                        <button wire:click="$set('payment_method', 'cash')"
                                class="btn-pos btn-payment flex-1 {{ $payment_method === 'cash' ? 'active' : '' }}">
                            💵 Efectivo
                        </button>
                        <button wire:click="$set('payment_method', 'card')"
                                class="btn-pos btn-payment flex-1 {{ $payment_method === 'card' ? 'active' : '' }}">
                            💳 Tarjeta
                        </button>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="p-3 bg-white dark:bg-gray-800 border-t-2 border-gray-300 dark:border-gray-600 space-y-2">
                <!-- Primera Fila: Botones secundarios -->
                <div class="grid grid-cols-3 gap-2">
                    <button class="btn-pos btn-secondary">
                        📋 Orden Cocina
                    </button>
                    <button class="btn-pos btn-secondary">
                        🖨️ Orden & Imp.
                    </button>
                    <button class="btn-pos btn-secondary">
                        ⚡ Orden & Pago
                    </button>
                </div>
                
                <!-- Segunda Fila: Botón principal CUENTA destacado -->
                <div class="grid grid-cols-4 gap-2">
                    <button class="btn-pos btn-secondary">
                        📄 Pre-cuenta
                    </button>
                    
                    <!-- BOTÓN PRINCIPAL -->
                    <button wire:click="saveOrder" 
                            class="btn-pos btn-primary-pos col-span-2">
                        💰 CUENTA
                    </button>
                    
                    <button class="btn-pos btn-secondary">
                        🖨️ Cuenta & Imp.
                    </button>
                </div>
            </div>
    </div>

    <!-- Mensajes Flash -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 z-50 animate-slide-in">
            <x-alert type="success">
                {{ session('success') }}
            </x-alert>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 z-50 animate-slide-in">
            <x-alert type="error">
                {{ session('error') }}
            </x-alert>
        </div>
    @endif
</div>

<!-- Script para Impresión de Tickets -->
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('print-ticket', (event) => {
            const orderId = event.orderId;
            console.log('📄 Imprimiendo ticket para orden #' + orderId);
            
            // Abrir ventana de impresión con el ticket
            const ticketUrl = '/ticket/' + orderId;
            const ticketWindow = window.open(ticketUrl, '_blank', 'width=300,height=600,toolbar=no,menubar=no,scrollbars=yes');
            
            // Esperar a que cargue y auto-imprimir
            if (ticketWindow) {
                ticketWindow.focus();
            } else {
                alert('⚠️ Por favor, habilita las ventanas emergentes para imprimir el ticket');
            }
        });
    });
</script>
</div>
