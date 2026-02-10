<!-- ==========================================
     PANEL DEL CARRITO - RESPONSIVE
     Usado tanto en Desktop como en Drawer Móvil
     ========================================== -->

<!-- Header del Carrito -->
<div class="bg-gradient-to-r from-orange-500 to-orange-600 p-4 text-white flex items-center justify-between sticky top-0 z-10">
    <div class="flex items-center gap-2">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
        </svg>
        <div>
            <div class="font-bold text-lg">Orden Actual</div>
            <div class="text-xs opacity-90">{{ count($cart) }} items</div>
        </div>
    </div>
    
    <!-- Botón Cerrar (Solo Móvil) -->
    <button @click="cartDrawerOpen = false" 
            class="lg:hidden text-white p-2 hover:bg-orange-700 rounded-lg transition-colors min-w-[44px] min-h-[44px]">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
</div>

<!-- Tipo de Servicio -->
<div class="bg-gray-900 p-3 md:p-4">
    <div class="text-xs text-gray-400 mb-2 font-semibold uppercase">Tipo de Servicio:</div>
    <div class="grid grid-cols-3 gap-2">
        <button wire:click="$set('order_type', 'mesa')"
                class="btn-pos btn-select {{ $order_type === 'mesa' ? 'active' : '' }}">
            <span class="text-base">🍽️</span>
            <span class="hidden sm:inline">Mesa</span>
        </button>
        <button wire:click="$set('order_type', 'delivery')"
                class="btn-pos btn-select {{ $order_type === 'delivery' ? 'active' : '' }}">
            <span class="text-base">🛵</span>
            <span class="hidden sm:inline">Delivery</span>
        </button>
        <button wire:click="$set('order_type', 'para_llevar')"
                class="btn-pos btn-select {{ $order_type === 'para_llevar' ? 'active' : '' }}">
            <span class="text-base">📦</span>
            <span class="hidden sm:inline">Llevar</span>
        </button>
    </div>
</div>

<!-- Canal de Venta -->
<div class="bg-gray-800 p-3 md:p-4 border-t-2 border-gray-700">
    <div class="text-xs text-gray-400 mb-2 font-semibold uppercase flex items-center gap-1">
        <span>💰</span> Canal de Venta:
    </div>
    <div class="grid grid-cols-3 gap-2">
        <button wire:click="$set('sales_channel', 'local')"
                class="btn-pos btn-select {{ $sales_channel === 'local' ? 'active' : '' }}">
            <span class="text-base">🏪</span>
            <span class="hidden sm:inline">Local</span>
        </button>
        <button wire:click="$set('sales_channel', 'rappi')"
                class="btn-pos btn-select {{ $sales_channel === 'rappi' ? 'active' : '' }}">
            <span class="text-base">🛵</span>
            <span class="hidden sm:inline">Rappi</span>
        </button>
        <button wire:click="$set('sales_channel', 'web')"
                class="btn-pos btn-select {{ $sales_channel === 'web' ? 'active' : '' }}">
            <span class="text-base">🌐</span>
            <span class="hidden sm:inline">Web</span>
        </button>
    </div>
    <div class="text-[10px] text-gray-500 mt-1.5 text-center">
        Los precios se ajustan según el canal
    </div>
</div>

<!-- Lista de Items del Carrito -->
<div class="flex-1 overflow-y-auto p-3 md:p-4 space-y-2 bg-gray-900">
    @forelse($cart as $cartKey => $item)
        <div class="bg-gray-800 rounded-lg p-3 border border-gray-700 hover:border-orange-500 transition-colors">
            <!-- Nombre y Precio -->
            <div class="flex items-start justify-between mb-2">
                <div class="flex-1">
                    <p class="font-semibold text-white text-sm md:text-base">{{ $item['name'] }}</p>
                    <p class="text-orange-500 font-bold text-lg md:text-xl">S/ {{ number_format($item['price'], 2) }}</p>
                </div>
            </div>
            
            <!-- Controles de Cantidad + Eliminar -->
            <div class="flex items-center justify-between gap-2">
                <!-- Controles de Cantidad -->
                <div class="flex items-center gap-2 bg-gray-700 rounded-lg p-1">
                    <button wire:click="decreaseQuantity('{{ $cartKey }}')" 
                            class="w-10 h-10 md:w-11 md:h-11 flex items-center justify-center bg-gray-600 hover:bg-gray-500 text-white rounded-lg transition-colors font-bold text-lg min-w-[44px] min-h-[44px]">
                        −
                    </button>
                    <span class="text-white font-bold text-lg md:text-xl px-3 md:px-4">{{ $item['quantity'] }}</span>
                    <button wire:click="increaseQuantity('{{ $cartKey }}')" 
                            class="w-10 h-10 md:w-11 md:h-11 flex items-center justify-center bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors font-bold text-lg min-w-[44px] min-h-[44px]">
                        +
                    </button>
                </div>
                
                <!-- Subtotal + Eliminar -->
                <div class="flex items-center gap-2">
                    <div class="text-right">
                        <p class="text-xs text-gray-400">Subtotal</p>
                        <p class="text-white font-bold text-sm md:text-base">S/ {{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                    </div>
                    <button wire:click="removeFromCart('{{ $cartKey }}')" 
                            class="w-10 h-10 md:w-11 md:h-11 flex items-center justify-center bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors min-w-[44px] min-h-[44px]">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-12 text-gray-400">
            <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
            </svg>
            <p class="text-base md:text-lg">Carrito vacío</p>
            <p class="text-sm mt-2">Agrega productos para comenzar</p>
        </div>
    @endforelse
</div>

<!-- Método de Pago -->
<div class="bg-gray-900 p-3 md:p-4 border-t-2 border-gray-700">
    <div class="text-xs text-gray-400 mb-2 font-semibold uppercase">Forma de Pago:</div>
    <div class="grid grid-cols-3 gap-2">
        <button wire:click="$set('payment_method', 'yape')"
                class="btn-pos btn-select {{ $payment_method === 'yape' ? 'active' : '' }}">
            <span class="text-base">📱</span>
            <span class="hidden sm:inline">Yape</span>
        </button>
        <button wire:click="$set('payment_method', 'cash')"
                class="btn-pos btn-select {{ $payment_method === 'cash' ? 'active' : '' }}">
            <span class="text-base">💵</span>
            <span class="hidden sm:inline">Efectivo</span>
        </button>
        <button wire:click="$set('payment_method', 'card')"
                class="btn-pos btn-select {{ $payment_method === 'card' ? 'active' : '' }}">
            <span class="text-base">💳</span>
            <span class="hidden sm:inline">Tarjeta</span>
        </button>
    </div>
</div>

<!-- Resumen Total -->
<div class="bg-gray-800 p-4 md:p-5 border-t-4 border-orange-500">
    <div class="space-y-2 mb-4">
        <div class="flex justify-between text-gray-300 text-sm md:text-base">
            <span>Subtotal:</span>
            <span class="font-semibold">S/ {{ number_format($subtotal, 2) }}</span>
        </div>
        @if($discount > 0)
        <div class="flex justify-between text-green-400 text-sm md:text-base">
            <span>Descuento:</span>
            <span class="font-semibold">- S/ {{ number_format($discount, 2) }}</span>
        </div>
        @endif
    </div>
    
    <!-- Total Grande -->
    <div class="bg-gradient-to-r from-orange-600 to-orange-700 rounded-lg p-4 mb-4">
        <div class="flex justify-between items-center">
            <span class="text-white text-lg md:text-xl font-bold">TOTAL:</span>
            <span class="text-white text-2xl md:text-3xl font-black">S/ {{ number_format($total, 2) }}</span>
        </div>
    </div>
    
    <!-- Botón CUENTA - Full Width en Móvil, Sticky en parte inferior -->
    <button wire:click="saveOrder" 
            @disabled(count($cart) === 0)
            class="btn-pos btn-primary-pos w-full text-lg md:text-xl py-4 md:py-5 disabled:opacity-50 disabled:cursor-not-allowed">
        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
            <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
        </svg>
        <span>CUENTA</span>
    </button>
    
    <!-- Botón Limpiar Carrito -->
    @if(count($cart) > 0)
    <button wire:click="clearCart" 
            wire:confirm="¿Limpiar todo el carrito?"
            class="w-full mt-2 py-3 bg-gray-700 hover:bg-red-600 text-white rounded-lg transition-colors font-semibold text-sm md:text-base min-h-[44px]">
        🗑️ Limpiar Carrito
    </button>
    @endif
</div>

