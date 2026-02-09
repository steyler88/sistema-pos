<x-filament-panels::page>
    {{-- Fondo dark mode forzado --}}
    <div class="min-h-screen bg-gray-900">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 p-4">
            @php
                $activeOrders = \App\Models\Order::where('status', 'pending')
                    ->with('items.product')
                    ->latest()
                    ->get();
            @endphp

            @forelse($activeOrders as $order)
                @php
                    $borderColor = match($order->order_type) {
                        'delivery' => 'border-green-500',
                        'mesa' => 'border-blue-500',
                        default => 'border-yellow-500',
                    };
                    
                    $gradientColor = match($order->order_type) {
                        'delivery' => 'from-green-500 to-green-600',
                        'mesa' => 'from-blue-500 to-blue-600',
                        default => 'from-yellow-500 to-yellow-600',
                    };
                @endphp
                
                <x-card :padding="false" class="border-2 {{ $borderColor }} hover:shadow-2xl hover:scale-105 transition-transform duration-200">
                    {{-- Header con gradiente --}}
                    <div class="bg-gradient-to-r {{ $gradientColor }} text-white p-4">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <div class="text-xs opacity-90 font-medium">Pedido</div>
                                <div class="text-3xl font-black tracking-tight">#{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs opacity-90">{{ $order->created_at->format('H:i') }}</div>
                                <div class="text-xs font-semibold mt-1 bg-white/20 px-2 py-1 rounded">
                                    {{ $order->created_at->diffForHumans() }}
                                </div>
                            </div>
                        </div>
                        
                        {{-- Badges de tipo y pago --}}
                        <div class="flex gap-2 flex-wrap">
                            <x-badge variant="default" class="bg-white/20 text-white border-white/30">
                                @if($order->order_type === 'delivery')
                                    🚚 Delivery
                                @elseif($order->order_type === 'para_llevar')
                                    🛍️ Para Llevar
                                @elseif($order->order_type === 'mesa')
                                    🪑 {{ $order->table_location ?? 'Mesa' }}
                                @else
                                    🍺 {{ $order->table_location ?? 'Barra' }}
                                @endif
                            </x-badge>
                            
                            <x-badge variant="default" class="bg-white/20 text-white border-white/30">
                                @if($order->payment_method === 'yape')
                                    📱 Yape
                                @elseif($order->payment_method === 'cash')
                                    💵 Efectivo
                                @else
                                    💳 Tarjeta
                                @endif
                            </x-badge>
                        </div>
                    </div>

                    {{-- Cliente --}}
                    <div class="px-4 py-3 bg-gray-900 border-b border-gray-700">
                        <div class="text-xs text-gray-400 mb-1 font-medium">Cliente</div>
                        <div class="font-bold text-base text-white">{{ $order->customer_name }}</div>
                    </div>

                    {{-- Items del pedido --}}
                    <div class="px-4 py-3 max-h-48 overflow-y-auto bg-gray-800">
                        @foreach($order->items as $item)
                            <div class="flex justify-between items-center py-2 border-b border-gray-700 last:border-0">
                                <div class="flex-1">
                                    <div class="font-semibold text-sm text-white">
                                        {{ $item->product->name ?? 'Producto eliminado' }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        S/ {{ number_format($item->unit_price, 2) }} × {{ $item->quantity }}
                                    </div>
                                </div>
                                <div class="font-bold text-sm text-orange-400">
                                    S/ {{ number_format($item->unit_price * $item->quantity, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Total y Acciones --}}
                    <div class="p-4 bg-gray-900 border-t border-gray-700">
                        <div class="flex justify-between items-center mb-4">
                            <span class="font-bold text-gray-300 uppercase tracking-wide">Total</span>
                            <span class="text-2xl font-black text-orange-500">
                                S/ {{ number_format($order->total_price, 2) }}
                            </span>
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
                                class="bg-green-500 hover:bg-green-600 focus:ring-green-500"
                                onclick="window.location.href='{{ route('filament.admin.resources.orders.edit', $order) }}'">
                                ✅ Completar
                            </x-button-primary>
                        </div>
                    </div>

                    {{-- Notas (si existen) --}}
                    @if($order->notes)
                        <div class="px-4 py-3 bg-yellow-900/30 border-t border-yellow-700/50">
                            <div class="text-xs text-yellow-300">
                                📝 {{ $order->notes }}
                            </div>
                        </div>
                    @endif
                </x-card>
            @empty
                {{-- Estado vacío --}}
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

