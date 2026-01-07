<div class="h-screen bg-gray-50 dark:bg-gray-900 overflow-hidden flex flex-col">
    
    <div class="flex flex-1 overflow-hidden">
        <!-- SECCIÓN CENTRAL: PRODUCTOS -->
        <div class="flex-1 flex flex-col overflow-hidden bg-white dark:bg-gray-900">
            
            <!-- PESTAÑAS DE CATEGORÍAS - ESTILO IZIREST -->
            <div class="bg-gray-900 border-b border-gray-700">
                <div class="flex overflow-x-auto scrollbar-hide">
                    <button wire:click="selectCategory('Mostrar todo')"
                            class="px-6 py-4 font-semibold text-sm whitespace-nowrap transition-all {{ $selectedCategory === 'Mostrar todo' ? 'bg-black text-white border-b-2 border-orange-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                        Mostrar todo
                    </button>
                    @foreach($categories as $category)
                        <button wire:click="selectCategory('{{ $category }}')"
                                class="px-6 py-4 font-semibold text-sm whitespace-nowrap transition-all {{ $selectedCategory === $category ? 'bg-black text-white border-b-2 border-orange-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                            {{ strtoupper($category) }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- GRID DE PRODUCTOS - ESTILO IZIREST -->
            <div class="flex-1 overflow-y-auto p-4 bg-gray-50 dark:bg-gray-900">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">
                    @forelse($products as $product)
                        <button wire:click="addToCart({{ $product->id }})"
                                class="group relative bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow hover:shadow-xl transition-all transform hover:scale-105 active:scale-95">
                            
                            <!-- Imagen del Producto -->
                            <div class="relative h-32 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-800">
                                @if($product->image)
                                    <img src="{{ Storage::url($product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="text-5xl">🍕</span>
                                    </div>
                                @endif
                                
                                <!-- Badge de Precio -->
                                <div class="absolute top-2 left-2 bg-gray-900 text-white px-3 py-1 rounded-full font-bold text-sm">
                                    S/{{ number_format($product->price, 2) }}
                                </div>
                            </div>
                            
                            <!-- Nombre del Producto -->
                            <div class="p-3 text-center">
                                <p class="font-semibold text-sm text-gray-900 dark:text-white line-clamp-2">
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
                </div>
            </div>
        </div>

        <!-- PANEL DERECHO: DETALLES DE LA ORDEN - ESTILO IZIREST -->
        <div class="w-96 bg-white dark:bg-gray-800 border-l border-gray-200 dark:border-gray-700 flex flex-col">
            
            <!-- Header: Info del Pedido -->
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-4 text-white">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <div class="text-xs opacity-90">Pedido</div>
                        <div class="text-2xl font-black">#{{ str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT) }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs opacity-90">{{ now()->format('d/m/Y') }}</div>
                        <div class="text-sm font-bold">{{ now()->format('H:i') }}</div>
                    </div>
                </div>
                
                <!-- Tipo y Ubicación -->
                <div class="grid grid-cols-2 gap-2 mt-3">
                    <button wire:click="$set('order_type', 'delivery')"
                            class="px-3 py-2 rounded text-xs font-bold transition-all {{ $order_type === 'delivery' ? 'bg-white text-orange-600' : 'bg-orange-700 text-white hover:bg-orange-800' }}">
                        🚚 Delivery
                    </button>
                    <button wire:click="$set('order_type', 'para_llevar')"
                            class="px-3 py-2 rounded text-xs font-bold transition-all {{ $order_type === 'para_llevar' ? 'bg-white text-orange-600' : 'bg-orange-700 text-white hover:bg-orange-800' }}">
                        🛍️ P/Llevar
                    </button>
                    <button wire:click="$set('order_type', 'mesa')"
                            class="px-3 py-2 rounded text-xs font-bold transition-all {{ $order_type === 'mesa' ? 'bg-white text-orange-600' : 'bg-orange-700 text-white hover:bg-orange-800' }}">
                        🪑 Mesa
                    </button>
                    <button wire:click="$set('order_type', 'barra')"
                            class="px-3 py-2 rounded text-xs font-bold transition-all {{ $order_type === 'barra' ? 'bg-white text-orange-600' : 'bg-orange-700 text-white hover:bg-orange-800' }}">
                        🍺 Barra
                    </button>
                </div>

                @if(in_array($order_type, ['mesa', 'barra']))
                    <div class="grid grid-cols-3 gap-2 mt-2">
                        <button wire:click="$set('table_location', 'Mesa 1')"
                                class="px-2 py-1 rounded text-xs font-bold {{ $table_location === 'Mesa 1' ? 'bg-white text-orange-600' : 'bg-orange-700 text-white' }}">
                            Mesa 1
                        </button>
                        <button wire:click="$set('table_location', 'Mesa 2')"
                                class="px-2 py-1 rounded text-xs font-bold {{ $table_location === 'Mesa 2' ? 'bg-white text-orange-600' : 'bg-orange-700 text-white' }}">
                            Mesa 2
                        </button>
                        <button wire:click="$set('table_location', 'Barra')"
                                class="px-2 py-1 rounded text-xs font-bold {{ $table_location === 'Barra' ? 'bg-white text-orange-600' : 'bg-orange-700 text-white' }}">
                            Barra
                        </button>
                    </div>
                @endif
            </div>

            <!-- Cliente y Camarero -->
            <div class="p-3 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                <div class="text-xs text-gray-600 dark:text-gray-400 mb-1">Comensales: 1</div>
                <input wire:model="customer_name" type="text" placeholder="Cliente"
                       class="w-full px-3 py-2 text-sm rounded border border-gray-300 dark:border-gray-600 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 bg-white dark:bg-gray-800 text-gray-900 dark:text-white">
            </div>

            <!-- TABLA DE ITEMS - ESTILO PRECISO IZIREST -->
            <div class="flex-1 overflow-y-auto bg-white dark:bg-gray-800">
                
                <!-- Encabezado de Tabla -->
                <div class="sticky top-0 bg-gray-100 dark:bg-gray-700 grid grid-cols-12 gap-2 px-3 py-2 text-xs font-bold text-gray-700 dark:text-gray-300 border-b border-gray-300 dark:border-gray-600">
                    <div class="col-span-5">NOMBRE DEL ITEM</div>
                    <div class="col-span-2 text-center">CANTIDAD</div>
                    <div class="col-span-2 text-right">PRECIO</div>
                    <div class="col-span-2 text-right">TOTAL</div>
                    <div class="col-span-1"></div>
                </div>

                <!-- Items del Pedido -->
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($cart as $key => $item)
                        <div class="grid grid-cols-12 gap-2 px-3 py-3 items-center hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <!-- Nombre -->
                            <div class="col-span-5">
                                <div class="font-semibold text-sm text-gray-900 dark:text-white">{{ $item['name'] }}</div>
                            </div>

                            <!-- Controles de Cantidad -->
                            <div class="col-span-2 flex items-center justify-center gap-1">
                                <button wire:click="decreaseQuantity('{{ $key }}')"
                                        class="w-7 h-7 flex items-center justify-center bg-red-500 hover:bg-red-600 text-white rounded font-bold text-lg leading-none transition-colors">
                                    −
                                </button>
                                <span class="w-8 text-center font-bold text-gray-900 dark:text-white">
                                    {{ $item['quantity'] }}
                                </span>
                                <button wire:click="increaseQuantity('{{ $key }}')"
                                        class="w-7 h-7 flex items-center justify-center bg-green-500 hover:bg-green-600 text-white rounded font-bold text-lg leading-none transition-colors">
                                    +
                                </button>
                            </div>

                            <!-- Precio Unitario -->
                            <div class="col-span-2 text-right text-sm text-gray-700 dark:text-gray-300">
                                S/{{ number_format($item['price'], 2) }}
                            </div>

                            <!-- Total -->
                            <div class="col-span-2 text-right font-bold text-sm text-gray-900 dark:text-white">
                                S/{{ number_format($item['quantity'] * $item['price'], 2) }}
                            </div>

                            <!-- Eliminar -->
                            <div class="col-span-1 text-center">
                                <button wire:click="removeFromCart('{{ $key }}')" 
                                        class="text-red-500 hover:text-red-700 transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-400">
                            <div class="text-6xl mb-3">🍽️</div>
                            <div class="font-semibold text-gray-500 dark:text-gray-400">Sin productos</div>
                            <div class="text-sm text-gray-400">Selecciona productos del menú</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Notas -->
            @if(!empty($cart))
            <div class="px-3 py-2 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                <textarea wire:model="notes" placeholder="Notas especiales..." rows="2"
                          class="w-full px-3 py-2 text-sm rounded border border-gray-300 dark:border-gray-600 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 bg-white dark:bg-gray-800"></textarea>
            </div>
            @endif

            <!-- Resumen de Totales -->
            <div class="border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                <div class="px-4 py-3 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                        <span class="font-semibold text-gray-900 dark:text-white">S/{{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                        <span>IGV (18%)</span>
                        <span>S/{{ number_format($total * 0.18, 2) }}</span>
                    </div>
                    <div class="pt-2 border-t border-gray-300 dark:border-gray-600">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900 dark:text-white">Total</span>
                            <span class="text-2xl font-black text-orange-600">S/{{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Forma de Pago -->
                <div class="px-4 pb-3">
                    <div class="text-xs text-gray-600 dark:text-gray-400 mb-2">Forma de Pago:</div>
                    <div class="grid grid-cols-3 gap-2">
                        <button wire:click="$set('payment_method', 'yape')"
                                class="px-3 py-2 rounded text-xs font-bold transition-all {{ $payment_method === 'yape' ? 'bg-green-500 text-white ring-2 ring-green-300' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300' }}">
                            📱 Yape
                        </button>
                        <button wire:click="$set('payment_method', 'cash')"
                                class="px-3 py-2 rounded text-xs font-bold transition-all {{ $payment_method === 'cash' ? 'bg-yellow-500 text-white ring-2 ring-yellow-300' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300' }}">
                            💵 Efectivo
                        </button>
                        <button wire:click="$set('payment_method', 'card')"
                                class="px-3 py-2 rounded text-xs font-bold transition-all {{ $payment_method === 'card' ? 'bg-blue-500 text-white ring-2 ring-blue-300' : 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-300' }}">
                            💳 Tarjeta
                        </button>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción - ESTILO IZIREST -->
            <div class="p-3 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 grid grid-cols-2 gap-2">
                <button wire:click="clearCart" 
                        class="px-4 py-3 bg-gray-600 hover:bg-gray-700 text-white font-bold text-sm rounded-lg transition-all active:scale-95 shadow-lg">
                    🗑️ Limpiar
                </button>
                <button wire:click="saveOrder" 
                        class="px-4 py-3 bg-gradient-to-r from-pink-500 to-pink-600 hover:from-pink-600 hover:to-pink-700 text-white font-bold text-sm rounded-lg transition-all active:scale-95 shadow-lg">
                    💰 COBRAR
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

