<div class="h-screen bg-white dark:bg-gray-900 flex w-full m-0 p-0">
    
    <!-- SECCIÓN CENTRAL: PRODUCTOS (ZONA AZUL) - Ocupa todo el espacio disponible -->
    <div class="flex-1 flex flex-col bg-white dark:bg-gray-900 border-r-2 border-blue-500">
            
            <!-- BUSCADOR PRINCIPAL - ESTILO IZIREST -->
            <div class="p-4 bg-white dark:bg-gray-800 border-b-4 border-blue-600">
                <div class="relative">
                    <input type="text" 
                           wire:model.live="searchTerm"
                           placeholder="Busque su elemento de menú aquí" 
                           class="w-full px-4 py-3 pl-10 text-base border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:border-purple-500 focus:ring-2 focus:ring-purple-200 dark:bg-gray-700 dark:text-white">
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
                            class="px-6 py-3 font-bold text-xs whitespace-nowrap transition-all flex items-center gap-2 {{ ($selectedView === 'categories' && $selectedCategory === 'Mostrar todo') ? 'bg-black text-white border-b-3 border-blue-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        <span class="text-base">📋</span> MOSTRAR TODO
                    </button>
                    @foreach($categories as $category)
                        <button wire:click="selectCategory('{{ $category->name }}')"
                                class="px-6 py-3 font-bold text-xs whitespace-nowrap transition-all flex items-center gap-2 {{ ($selectedView === 'categories' && $selectedCategory === $category->name) ? 'bg-black text-white border-b-3 border-blue-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
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
                                    class="group relative bg-gradient-to-br from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 rounded-lg overflow-hidden shadow-lg hover:shadow-2xl transition-all transform hover:scale-105 active:scale-95 border-2 border-orange-300 dark:border-orange-700">
                                
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
                                    class="group relative bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow hover:shadow-xl transition-all transform hover:scale-105 active:scale-95">
                                
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
                                    <div class="absolute top-1 left-1 bg-gray-900 text-white px-2 py-1 rounded-md font-bold text-xs">
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

    <!-- PANEL DERECHO: DETALLES DE LA ORDEN (ZONA VERDE) - Ancho fijo optimizado -->
    <div class="w-96 bg-white dark:bg-gray-800 border-l-4 border-green-500 shadow-xl overflow-y-auto h-screen">
            
            <!-- Header: Info del Pedido con ícono -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 p-3 text-white flex items-center gap-3">
                <!-- Ícono y título -->
                <div class="flex items-center gap-2 flex-1">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                    </svg>
                    <div>
                        <div class="text-xs opacity-90">CAJA COCINA</div>
                        <div class="flex items-center gap-2">
                            <span class="text-lg font-bold">Saldo: S/${{ number_format($total, 2) }}</span>
                            <span class="text-xs bg-green-700 px-2 py-0.5 rounded-full">Estado: Abierta</span>
                        </div>
                    </div>
                </div>
                
                <!-- Botón cambiar -->
                <button class="text-xs bg-white bg-opacity-20 hover:bg-opacity-30 px-3 py-1 rounded transition-all">
                    🔄 Cambiar
                </button>
            </div>

            <!-- Tipo de Servicio (En Restaurante / Delivery / Recogida) - HORIZONTAL -->
<div class="p-2 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="flex gap-2">
                    <button wire:click="$set('order_type', 'mesa')"
                            class="flex-1 px-3 py-2 rounded-lg text-xs font-semibold transition-all border-2 {{ $order_type === 'mesa' ? 'bg-blue-500 text-white border-blue-500' : 'bg-transparent text-blue-500 border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20' }}">
                        Mesa
                    </button>
                    <button wire:click="$set('order_type', 'delivery')"
                            class="flex-1 px-3 py-2 rounded-lg text-xs font-semibold transition-all border-2 {{ $order_type === 'delivery' ? 'bg-green-500 text-white border-green-500' : 'bg-transparent text-green-600 border-green-500 hover:bg-green-50 dark:hover:bg-green-900/20' }}">
                        Delivery
                    </button>
                    <button wire:click="$set('order_type', 'para_llevar')"
                            class="flex-1 px-3 py-2 rounded-lg text-xs font-semibold transition-all border-2 {{ $order_type === 'para_llevar' ? 'bg-orange-500 text-white border-orange-500' : 'bg-transparent text-orange-600 border-orange-500 hover:bg-orange-50 dark:hover:bg-orange-900/20' }}">
                        Para Llevar
                    </button>
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
                        <select wire:model="table_location" class="text-xs border border-gray-300 dark:border-gray-600 rounded px-1 py-0.5 dark:bg-gray-700">
                            <option value="Mesa 1">Mesa 1</option>
                            <option value="Mesa 2">Mesa 2</option>
                            <option value="Barra">Barra</option>
                        </select>
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
                        <input wire:model="customer_name" type="text" class="text-xs border border-gray-300 dark:border-gray-600 rounded px-1 py-0.5 flex-1 dark:bg-gray-700 dark:text-white" placeholder="Cliente">
                    </div>
                    
                    <!-- Camarero -->
                    <div class="flex items-center gap-1 flex-1 min-w-[100px]">
                        <span class="text-gray-500 dark:text-gray-400">🧑‍🍳</span>
                        <select class="text-xs border border-gray-300 dark:border-gray-600 rounded px-1 py-0.5 flex-1 dark:bg-gray-700 dark:text-white">
                            <option>Jaquelyn Battle</option>
                        </select>
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
                <button wire:click="$toggle('showDiscountInput')" class="w-full px-2 py-1.5 bg-white dark:bg-gray-700 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded text-xs font-semibold text-gray-700 dark:text-gray-300 hover:border-blue-500 hover:text-blue-600 transition-all flex items-center justify-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Agregar Descuento
                </button>
                
                @if($showDiscountInput ?? false)
                <div class="mt-1 flex gap-1">
                    <input wire:model="discount" type="number" step="0.01" placeholder="Monto" 
                           class="flex-1 px-2 py-1 border border-gray-300 dark:border-gray-600 rounded text-xs dark:bg-gray-700 dark:text-white">
                    <button wire:click="applyDiscount" class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white rounded text-xs font-semibold">
                        Aplicar
                    </button>
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

                <!-- Forma de Pago - HORIZONTAL -->
                <div class="px-2 pb-2 border-t border-gray-200 dark:border-gray-700 pt-2">
                    <div class="text-[10px] text-gray-600 dark:text-gray-400 mb-1 font-semibold">Forma de Pago:</div>
                    <div class="flex gap-2">
                        <button wire:click="$set('payment_method', 'yape')"
                                class="flex-1 px-3 py-2 rounded-lg text-xs font-semibold transition-all border-2 {{ $payment_method === 'yape' ? 'bg-purple-500 text-white border-purple-500' : 'bg-transparent text-purple-600 border-purple-500 hover:bg-purple-50 dark:hover:bg-purple-900/20' }}">
                            Yape
                        </button>
                        <button wire:click="$set('payment_method', 'cash')"
                                class="flex-1 px-3 py-2 rounded-lg text-xs font-semibold transition-all border-2 {{ $payment_method === 'cash' ? 'bg-emerald-500 text-white border-emerald-500' : 'bg-transparent text-emerald-600 border-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/20' }}">
                            Efectivo
                        </button>
                        <button wire:click="$set('payment_method', 'card')"
                                class="flex-1 px-3 py-2 rounded-lg text-xs font-semibold transition-all border-2 {{ $payment_method === 'card' ? 'bg-cyan-500 text-white border-cyan-500' : 'bg-transparent text-cyan-600 border-cyan-500 hover:bg-cyan-50 dark:hover:bg-cyan-900/20' }}">
                            Tarjeta
                        </button>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción - 2 FILAS: 3 botones + 4 botones -->
            <div class="p-2 bg-white dark:bg-gray-800 border-t-2 border-gray-300 dark:border-gray-600 space-y-2">
                <!-- Primera Fila: 3 botones outline -->
                <div class="grid grid-cols-3 gap-2">
                    <button class="px-2 py-2.5 rounded-lg text-xs font-semibold transition-all border-2 bg-transparent text-gray-700 dark:text-gray-300 border-gray-400 dark:border-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Orden de cocina
                    </button>
                    <button class="px-2 py-2.5 rounded-lg text-xs font-semibold transition-all border-2 bg-transparent text-gray-700 dark:text-gray-300 border-gray-400 dark:border-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Orden & Imprimir
                    </button>
                    <button class="px-2 py-2.5 rounded-lg text-xs font-semibold transition-all border-2 bg-transparent text-gray-700 dark:text-gray-300 border-gray-400 dark:border-gray-500 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Orden, Cuenta & Pago
                    </button>
                </div>
                
                <!-- Segunda Fila: 4 botones de colores -->
                <div class="grid grid-cols-4 gap-2">
                    <!-- Pre-cuenta (Rosado) -->
                    <button class="px-2 py-2.5 rounded-lg text-xs font-semibold transition-all border-2 bg-transparent text-pink-600 dark:text-pink-400 border-pink-500 hover:bg-pink-50 dark:hover:bg-pink-900/20">
                        Pre-cuenta
                    </button>
                    
                    <!-- CUENTA (Morado/Azul - Destacado con relleno) -->
                    <button wire:click="saveOrder" 
                            class="px-2 py-2.5 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-black text-sm rounded-lg transition-all active:scale-95 shadow-lg uppercase border-2 border-purple-600">
                        💰 CUENTA
                    </button>
                    
                    <!-- Cuenta y Pagar (Verde) -->
                    <button class="px-2 py-2.5 rounded-lg text-xs font-semibold transition-all border-2 bg-transparent text-green-600 dark:text-green-400 border-green-500 hover:bg-green-50 dark:hover:bg-green-900/20">
                        Cuenta & Pagar
                    </button>
                    
                    <!-- Cuenta e Imprimir (Azul) -->
                    <button class="px-2 py-2.5 rounded-lg text-xs font-semibold transition-all border-2 bg-transparent text-blue-600 dark:text-blue-400 border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20">
                        Cuenta & Imprimir
                    </button>
                </div>
            </div>
    </div>

    <!-- Mensajes Flash -->
    @if (session()->has('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif
</div>


