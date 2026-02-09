<x-filament-panels::page>
    {{-- Fondo dark mode forzado --}}
    <div class="min-h-screen bg-gray-900 -m-6 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @php
            $activeOrders = \App\Models\Order::where('status', 'pending')
                ->with('items.product')
                ->latest()
                ->get();
        @endphp

        @forelse($activeOrders as $order)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden border-2 {{ $order->order_type === 'delivery' ? 'border-green-500' : ($order->order_type === 'mesa' ? 'border-blue-500' : 'border-yellow-500') }}">
                
                <!-- Header -->
                <div class="bg-gradient-to-r {{ $order->order_type === 'delivery' ? 'from-green-500 to-green-600' : ($order->order_type === 'mesa' ? 'from-blue-500 to-blue-600' : 'from-yellow-500 to-yellow-600') }} text-white p-4">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <div class="text-xs opacity-90">Pedido</div>
                            <div class="text-2xl font-black">#{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs opacity-90">{{ $order->created_at->format('H:i') }}</div>
                            <div class="text-xs font-semibold">{{ $order->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        <span class="px-2 py-1 bg-white bg-opacity-20 rounded text-xs font-bold">
                            @if($order->order_type === 'delivery')
                                🚚 Delivery
                            @elseif($order->order_type === 'para_llevar')
                                🛍️ Para Llevar
                            @elseif($order->order_type === 'mesa')
                                🪑 {{ $order->table_location ?? 'Mesa' }}
                            @else
                                🍺 {{ $order->table_location ?? 'Barra' }}
                            @endif
                        </span>
                        
                        <span class="px-2 py-1 bg-white bg-opacity-20 rounded text-xs font-bold">
                            @if($order->payment_method === 'yape')
                                📱 Yape
                            @elseif($order->payment_method === 'cash')
                                💵 Efectivo
                            @else
                                💳 Tarjeta
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Cliente -->
                <div class="p-3 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                    <div class="text-xs text-gray-500 dark:text-gray-400">Cliente</div>
                    <div class="font-bold text-sm text-gray-900 dark:text-white">{{ $order->customer_name }}</div>
                </div>

                <!-- Items -->
                <div class="p-3 max-h-48 overflow-y-auto">
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-gray-700 last:border-0">
                            <div class="flex-1">
                                <div class="font-semibold text-sm text-gray-900 dark:text-white">{{ $item->product->name ?? 'Producto eliminado' }}</div>
                                <div class="text-xs text-gray-500">S/ {{ number_format($item->unit_price, 2) }} x {{ $item->quantity }}</div>
                            </div>
                            <div class="font-bold text-sm text-gray-900 dark:text-white">
                                S/ {{ number_format($item->unit_price * $item->quantity, 2) }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Total y Acciones -->
                <div class="p-4 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-center mb-3">
                        <span class="font-bold text-gray-700 dark:text-gray-300">TOTAL</span>
                        <span class="text-2xl font-black text-orange-600">S/ {{ number_format($order->total_price, 2) }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <x-button-secondary 
                            size="sm" 
                            fullWidth
                            onclick="window.location.href='{{ route('filament.admin.resources.orders.edit', $order) }}'">
                            ✏️ Editar
                        </x-button-secondary>
                        
                        <x-button-primary 
                            size="sm" 
                            fullWidth
                            class="!bg-green-500 hover:!bg-green-600 focus:!ring-green-500"
                            onclick="window.location.href='{{ route('filament.admin.resources.orders.edit', $order) }}'">
                            ✅ Completar
                        </x-button-primary>
                    </div>
                </div>

                @if($order->notes)
                    <div class="px-4 py-2 bg-yellow-50 dark:bg-yellow-900 border-t border-yellow-200 dark:border-yellow-700">
                        <div class="text-xs text-yellow-800 dark:text-yellow-200">
                            📝 {{ $order->notes }}
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-full flex items-center justify-center min-h-[60vh]">
                <div class="text-center max-w-md">
                    <div class="text-8xl mb-6 opacity-50">✅</div>
                    <h3 class="text-2xl font-bold text-white mb-3">
                        No hay órdenes pendientes
                    </h3>
                    <p class="text-gray-400 mb-6">
                        Todas las órdenes han sido completadas. ¡Excelente trabajo!
                    </p>
                    <x-button-primary 
                        size="lg"
                        icon="➕"
                        onclick="window.location.href='{{ route('pos.touch') }}'">
                        Nueva Venta
                    </x-button-primary>
                </div>
            </div>
        @endforelse
        </div>
    </div>
</x-filament-panels::page>

