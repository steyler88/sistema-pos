<!-- Contenedor Principal del Carrito - 100% altura -->
<div class="h-screen flex flex-col bg-gray-800">

    <!-- Header: Info del Pedido (Fijo arriba) -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 p-3 text-white flex items-center gap-3 shrink-0">
        <!-- Botón Cerrar/Volver (Solo visible en móvil) -->
        <button @click="cartDrawerOpen = false" 
                class="lg:hidden text-white hover:bg-orange-700 p-2 rounded-lg transition-colors min-w-[40px] min-h-[40px] flex items-center justify-center shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </button>

        <div class="flex items-center gap-2 flex-1">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
            </svg>
            <div>
                <div class="text-xs opacity-90">CAJA COCINA</div>
                <div class="flex items-center gap-2">
                    <span class="text-base lg:text-lg font-bold">Saldo: S/${{ number_format($total, 2) }}</span>
                    <span class="text-[10px] lg:text-xs bg-orange-700 px-2 py-0.5 rounded-full">Abierta</span>
                </div>
            </div>
        </div>
        
        <!-- Botón Agregar más productos (Visible en desktop) -->
        <button @click="cartDrawerOpen = false" 
                class="hidden lg:flex items-center gap-2 text-xs bg-white bg-opacity-20 hover:bg-opacity-30 px-3 py-1.5 rounded-md transition-all font-medium">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Agregar más
        </button>
    </div>

    <!-- Tipo de Servicio (Fijo) -->
    <div class="p-3 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shrink-0">
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

    <!-- Canal de Venta (Fijo) -->
    <div class="p-3 bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shrink-0">
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

    <!-- Datos del Pedido (Fijo - Compacto) -->
    <div class="px-2 py-1.5 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700 shrink-0">
        <div class="flex flex-wrap gap-x-3 gap-y-1 text-xs items-center">
            <div class="flex items-center gap-1">
                <span class="text-gray-500 dark:text-gray-400">📋</span>
                <span class="font-bold text-gray-900 dark:text-white">#M06</span>
            </div>
            
            @if(in_array($order_type, ['mesa', 'barra']))
            <div class="flex items-center gap-1">
                <span class="text-gray-500 dark:text-gray-400">🍽️</span>
                <select wire:model="table_location" class="text-xs px-1 py-0.5 min-w-[80px] bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded text-gray-900 dark:text-white">
                    <option value="Mesa 1">Mesa 1</option>
                    <option value="Mesa 2">Mesa 2</option>
                    <option value="Barra">Barra</option>
                </select>
            </div>
            @endif
            
            <div class="flex items-center gap-1 flex-1 min-w-[120px]">
                <span class="text-gray-500 dark:text-gray-400">👤</span>
                <input wire:model="customer_name" type="text" placeholder="Cliente" class="text-xs px-1 py-0.5 flex-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded text-gray-900 dark:text-white" />
            </div>
        </div>
    </div>

    <!-- TABLA DE ITEMS - CON SCROLL (Flexible, crece para ocupar espacio) -->
    <div class="flex-1 overflow-y-auto bg-white dark:bg-gray-800">
        <!-- Encabezado de Tabla - Sticky -->
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
                            <div class="font-semibold text-[10px] text-gray-900 dark:text-white leading-tight">
                                {{ $item['name'] }}
                            </div>
                        </div>

                        <!-- Controles de Cantidad -->
                        <div class="col-span-3 flex items-center justify-center gap-1">
                            <button wire:click="decreaseQuantity('{{ $key }}')"
                                    class="w-5 h-5 flex items-center justify-center bg-gray-200 hover:bg-red-500 hover:text-white dark:bg-gray-600 dark:hover:bg-red-600 text-gray-700 dark:text-white rounded text-xs font-bold transition-all">
                                −
                            </button>
                            <span class="w-6 text-center font-bold text-[10px] text-gray-900 dark:text-white">
                                {{ $item['quantity'] }}
                            </span>
                            <button wire:click="increaseQuantity('{{ $key }}')"
                                    class="w-5 h-5 flex items-center justify-center bg-gray-200 hover:bg-green-500 hover:text-white dark:bg-gray-600 dark:hover:bg-green-600 text-gray-700 dark:text-white rounded text-xs font-bold transition-all">
                                +
                            </button>
                        </div>

                        <!-- Precio Unitario -->
                        <div class="col-span-2 text-right text-[10px] font-semibold text-gray-700 dark:text-gray-300 pr-1">
                            S/{{ number_format($item['price'], 2) }}
                        </div>

                        <!-- Total + Eliminar -->
                        <div class="col-span-3 flex items-center justify-end gap-1 pr-1">
                            <span class="font-bold text-[10px] text-gray-900 dark:text-white">
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

    <!-- Resumen de Totales (Fijo abajo) -->
    <div class="border-t-2 border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 shrink-0">
        <div class="px-4 py-2 space-y-1">
            <!-- Contador de Items -->
            <div class="flex justify-between text-xs">
                <span class="text-gray-600 dark:text-gray-400">Item(s)</span>
                <span class="font-semibold text-gray-900 dark:text-white">{{ count($cart) }}</span>
            </div>
            
            <!-- Subtotal -->
            <div class="flex justify-between text-xs">
                <span class="text-gray-600 dark:text-gray-400">Sub Total</span>
                <span class="font-semibold text-gray-900 dark:text-white">S/{{ number_format($subtotal ?? $total, 2) }}</span>
            </div>
            
            <!-- IGV -->
            <div class="flex justify-between text-xs">
                <span class="text-gray-600 dark:text-gray-400">IGV (18%)</span>
                <span class="font-semibold text-gray-900 dark:text-white">S/{{ number_format(($total - ($discount ?? 0)) * 0.18, 2) }}</span>
            </div>
            
            <!-- TOTAL PRINCIPAL -->
            <div class="pt-1 border-t border-gray-300 dark:border-gray-600 mt-1">
                <div class="flex justify-between items-center">
                    <span class="text-base font-bold text-gray-900 dark:text-white">Total</span>
                    <span class="text-2xl font-black text-gray-900 dark:text-white">S/{{ number_format($total - ($discount ?? 0), 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Forma de Pago -->
        <div class="px-3 pb-2 border-t border-gray-200 dark:border-gray-700 pt-2">
            <div class="text-[10px] text-gray-600 dark:text-gray-400 mb-1 font-semibold uppercase">Forma de Pago:</div>
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

    <!-- Botones de Acción (Fijo abajo) -->
    <div class="p-3 bg-white dark:bg-gray-800 border-t-2 border-gray-300 dark:border-gray-600 shrink-0">
        <!-- Botón principal CUENTA destacado -->
        <button wire:click="saveOrder" 
                class="btn-pos btn-primary-pos w-full">
            💰 CUENTA
        </button>
    </div>

</div>

<!-- Estilos de Botones -->
<style>
    .btn-payment {
        background: #374151;
        border-color: #4b5563;
        color: #d1d5db;
        font-size: 0.7rem;
        padding: 0.4rem;
    }

    .btn-payment:hover, .btn-payment:active {
        background: #4b5563;
        border-color: #6b7280;
        color: #fff;
    }

    .btn-payment.active {
        background: #10b981;
        border-color: #10b981;
        color: white;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.2);
    }
</style>
