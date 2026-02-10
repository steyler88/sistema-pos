<div x-data="{ 
    mobileMenuOpen: false,
    cartDrawerOpen: false,
    isMobile: window.innerWidth < 1024
}" 
@resize.window="isMobile = window.innerWidth < 1024"
class="relative">

<style>
    /* ==========================================
       SISTEMA DE BOTONES - MOBILE OPTIMIZED
       ========================================== */
    
    /* Botón Base - Touch-Friendly (Min 44px height) */
    .btn-pos {
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        cursor: pointer;
        font-size: 0.875rem; /* 14px - legible en móvil */
        padding: 0.75rem 1rem; /* Mayor padding para touch */
        border: 2px solid;
        min-height: 44px; /* Estándar de accesibilidad táctil */
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    /* Botones de Selección */
    .btn-select {
        background: #374151;
        border-color: #4b5563;
        color: #d1d5db;
    }

    .btn-select:hover, .btn-select:active {
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

    /* Botón Principal (CUENTA) - Extra grande en móvil */
    .btn-primary-pos {
        background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
        border-color: #f97316;
        color: white;
        font-weight: 700;
        box-shadow: 0 4px 14px 0 rgba(249, 115, 22, 0.4);
        font-size: 1rem;
        min-height: 56px; /* Más grande que el estándar */
    }

    .btn-primary-pos:hover {
        background: linear-gradient(135deg, #ea580c 0%, #dc2626 100%);
        box-shadow: 0 6px 20px 0 rgba(249, 115, 22, 0.6);
        transform: translateY(-1px);
    }

    /* Ocultar scrollbar pero mantener funcionalidad */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Tarjetas de Producto - Touch Friendly */
    .product-card {
        min-height: 180px; /* Área táctil generosa */
        touch-action: manipulation; /* Mejora respuesta táctil */
    }

    /* Overlay oscuro para modales */
    .modal-overlay {
        backdrop-filter: blur(4px);
    }
</style>

<!-- ==========================================
     HEADER MÓVIL CON HAMBURGER MENU
     ========================================== -->
<div class="lg:hidden sticky top-0 z-50 bg-gradient-to-r from-orange-500 to-orange-600 shadow-lg">
    <div class="flex items-center justify-between p-4">
        <!-- Hamburger Menu Button -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" 
                class="text-white p-2 hover:bg-orange-700 rounded-lg transition-colors min-w-[44px] min-h-[44px] flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Logo/Título -->
        <h1 class="text-white font-bold text-lg flex-1 text-center">🍕 ElchePizza POS</h1>

        <!-- Carrito Button (Móvil) -->
        <button @click="cartDrawerOpen = !cartDrawerOpen" 
                class="relative text-white p-2 hover:bg-orange-700 rounded-lg transition-colors min-w-[44px] min-h-[44px] flex items-center justify-center">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
            </svg>
            @if(count($cart) > 0)
            <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">
                {{ count($cart) }}
            </span>
            @endif
        </button>
    </div>
</div>

<!-- ==========================================
     SLIDE-OVER MENU (MÓVIL)
     ========================================== -->
<div x-show="mobileMenuOpen" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="mobileMenuOpen = false"
     class="fixed inset-0 bg-black/50 z-50 lg:hidden modal-overlay"
     style="display: none;">
    
    <!-- Slide-Over Panel -->
    <div @click.stop
         x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="absolute left-0 top-0 h-full w-80 bg-gray-900 shadow-2xl overflow-y-auto">
        
        <!-- Header del Menu -->
        <div class="p-4 bg-gradient-to-r from-orange-500 to-orange-600 flex items-center justify-between">
            <h2 class="text-white font-bold text-lg">📱 Menú</h2>
            <button @click="mobileMenuOpen = false" 
                    class="text-white p-2 hover:bg-orange-700 rounded-lg transition-colors min-w-[44px] min-h-[44px]">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Opciones del Menu -->
        <nav class="p-4 space-y-2">
            <a href="{{ route('pos.touch') }}" class="flex items-center gap-3 p-3 text-white hover:bg-gray-800 rounded-lg transition-colors min-h-[44px]">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                </svg>
                <span>POS Táctil</span>
            </a>
            
            <a href="/admin/orders" class="flex items-center gap-3 p-3 text-white hover:bg-gray-800 rounded-lg transition-colors min-h-[44px]">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                    <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                </svg>
                <span>Órdenes</span>
            </a>
            
            <a href="/admin/products" class="flex items-center gap-3 p-3 text-white hover:bg-gray-800 rounded-lg transition-colors min-h-[44px]">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd"/>
                </svg>
                <span>Productos</span>
            </a>

            <a href="{{ route('settings') }}" class="flex items-center gap-3 p-3 text-white hover:bg-gray-800 rounded-lg transition-colors min-h-[44px]">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                </svg>
                <span>Configuración</span>
            </a>
        </nav>
    </div>
</div>

<!-- ==========================================
     LAYOUT PRINCIPAL: 2 COLUMNAS (DESKTOP) / 1 COLUMNA (MÓVIL)
     ========================================== -->
<div class="flex flex-col lg:flex-row h-screen lg:h-screen bg-gray-900">
    
    <!-- ==========================================
         COLUMNA IZQUIERDA: PRODUCTOS
         ========================================== -->
    <div class="flex-1 flex flex-col overflow-hidden">
        
        <!-- BUSCADOR -->
        <div class="p-3 md:p-4 bg-gray-800 border-b-4 border-orange-600 shrink-0">
            <div class="relative">
                <input type="text" 
                       wire:model.live="searchTerm"
                       placeholder="Buscar producto..." 
                       class="w-full px-4 py-3 md:py-3.5 pl-10 text-base md:text-base border-2 border-gray-600 rounded-lg focus:border-orange-500 focus:ring-2 focus:ring-orange-500/20 bg-gray-700 text-white placeholder-gray-400 transition-all">
                <svg class="absolute left-3 top-3.5 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
        </div>

        <!-- PESTAÑAS: COMBOS + CATEGORÍAS (Scroll Horizontal en Móvil) -->
        <div class="bg-gray-900 border-b border-gray-700 shrink-0">
            <div class="flex overflow-x-auto scrollbar-hide">
                <!-- PESTAÑA DE COMBOS -->
                <button wire:click="showCombos"
                        class="px-4 md:px-6 py-3 font-bold text-xs md:text-sm whitespace-nowrap transition-all flex items-center gap-2 min-h-[44px] {{ $selectedView === 'combos' ? 'bg-gradient-to-r from-orange-500 to-red-500 text-white shadow-lg' : 'bg-gradient-to-r from-orange-600 to-red-600 text-white hover:from-orange-500 hover:to-red-500' }}">
                    <span class="text-base">🎁</span> <span class="hidden sm:inline">COMBOS</span><span class="sm:hidden">COMBO</span>
                </button>
                
                <!-- SEPARADOR -->
                <div class="w-px bg-gray-700 my-2"></div>
                
                <!-- CATEGORÍAS -->
                <button wire:click="selectCategory('Mostrar todo')"
                        class="px-4 md:px-6 py-3 font-bold text-xs md:text-sm whitespace-nowrap transition-all flex items-center gap-2 min-h-[44px] {{ ($selectedView === 'categories' && $selectedCategory === 'Mostrar todo') ? 'bg-black text-white border-b-3 border-orange-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}">
                    <span class="text-base">📋</span> <span class="hidden sm:inline">TODO</span>
                </button>
                
                @foreach($categories as $category)
                    <button wire:click="selectCategory('{{ $category->name }}')"
                            class="px-4 md:px-6 py-3 font-bold text-xs md:text-sm whitespace-nowrap transition-all flex items-center gap-2 min-h-[44px] {{ ($selectedView === 'categories' && $selectedCategory === $category->name) ? 'bg-black text-white border-b-3 border-orange-500' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
                            style="border-color: {{ ($selectedView === 'categories' && $selectedCategory === $category->name) ? $category->color : 'transparent' }};">
                        @if($category->icon)
                            <span class="text-base">{{ $category->icon }}</span>
                        @endif
                        <span class="hidden sm:inline">{{ strtoupper($category->name) }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        <!-- GRID DE PRODUCTOS (RESPONSIVE) -->
        <div class="flex-1 overflow-y-auto p-2 md:p-3 bg-gray-900">
            <!-- 
                GRID RESPONSIVE:
                - Móvil Vertical: 2 columnas
                - Móvil Horizontal / Tablet: 3 columnas
                - Tablet Grande: 4 columnas
                - Desktop: 5+ columnas
            -->
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 2xl:grid-cols-6 gap-2 md:gap-3">
                @if($selectedView === 'combos')
                    <!-- VISTA DE COMBOS -->
                    @forelse($combos as $combo)
                        <button wire:click="addComboToCart({{ $combo->id }})"
                                class="product-card group relative bg-gradient-to-br from-orange-50 to-red-50 dark:from-orange-900/20 dark:to-red-900/20 rounded-lg overflow-hidden shadow-lg hover:shadow-2xl hover:shadow-orange-500/50 transition-all transform hover:scale-105 active:scale-95 border-2 border-orange-300 dark:border-orange-700">
                            
                            <!-- Badge "COMBO" -->
                            <div class="absolute top-1 right-1 bg-gradient-to-r from-orange-500 to-red-500 text-white px-2 py-0.5 rounded-full font-black text-[9px] md:text-[10px] shadow-md z-10">
                                🎁 COMBO
                            </div>
                            
                            <!-- Imagen -->
                            <div class="relative h-24 md:h-32 bg-gradient-to-br from-orange-200 to-red-200 dark:from-orange-800 dark:to-red-800">
                                @if($combo->image)
                                    <img src="{{ asset('storage/' . $combo->image) }}" 
                                         alt="{{ $combo->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="text-4xl md:text-5xl">🎁</span>
                                    </div>
                                @endif
                                
                                <!-- Badge de Precio -->
                                <div class="absolute bottom-1 left-1 bg-green-600 text-white px-2 py-1 rounded-md font-bold text-xs md:text-sm shadow-md">
                                    S/{{ number_format($combo->price, 2) }}
                                </div>
                            </div>
                            
                            <!-- Nombre -->
                            <div class="p-2 text-center bg-white dark:bg-gray-800 border-t-2 border-orange-300">
                                <p class="font-bold text-xs md:text-sm text-gray-900 dark:text-white line-clamp-2">
                                    {{ $combo->name }}
                                </p>
                            </div>
                        </button>
                    @empty
                        <div class="col-span-full text-center py-12 text-gray-400">
                            <p class="text-lg">🎁 No hay combos disponibles</p>
                        </div>
                    @endforelse
                @else
                    <!-- VISTA DE PRODUCTOS -->
                    @forelse($products as $product)
                        <button wire:click="addToCart({{ $product->id }})"
                                class="product-card group relative bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md hover:shadow-2xl hover:shadow-orange-500/30 transition-all transform hover:scale-105 active:scale-95 border border-gray-200 dark:border-gray-700">
                            
                            <!-- Imagen del Producto -->
                            <div class="relative h-24 md:h-32 bg-gray-100 dark:bg-gray-700">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="text-4xl md:text-5xl">🍕</span>
                                    </div>
                                @endif
                                
                                <!-- Badge de Precio -->
                                <div class="absolute bottom-1 left-1 bg-orange-500 text-white px-2 py-1 rounded-md font-bold text-xs md:text-sm shadow-md">
                                    S/{{ number_format($product->getPriceByChannel($sales_channel), 2) }}
                                </div>
                            </div>
                            
                            <!-- Nombre del Producto -->
                            <div class="p-2 text-center">
                                <p class="font-semibold text-xs md:text-sm text-gray-900 dark:text-white line-clamp-2">
                                    {{ $product->name }}
                                </p>
                            </div>
                        </button>
                    @empty
                        <div class="col-span-full text-center py-12 text-gray-400">
                            <p class="text-lg">🍕 No hay productos disponibles</p>
                        </div>
                    @endforelse
                @endif
            </div>
        </div>
    </div>

    <!-- ==========================================
         COLUMNA DERECHA: CARRITO (DESKTOP) / DRAWER (MÓVIL)
         ========================================== -->
    
    <!-- DESKTOP: Panel Fijo Derecho -->
    <div class="hidden lg:block w-96 bg-gray-800 border-l-4 border-orange-500 shadow-xl overflow-y-auto">
        @include('livewire.partials.cart-panel')
    </div>

    <!-- MÓVIL: Cart Drawer (Slide from Right) -->
    <div x-show="cartDrawerOpen || !isMobile" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="cartDrawerOpen = false"
         class="fixed inset-0 bg-black/50 z-50 lg:hidden modal-overlay"
         style="display: none;">
        
        <!-- Drawer Panel -->
        <div @click.stop
             x-show="cartDrawerOpen"
             x-transition:enter="transition ease-out duration-300 transform"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transition ease-in duration-200 transform"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full"
             class="absolute right-0 top-0 h-full w-full sm:w-96 bg-gray-800 shadow-2xl overflow-y-auto">
            
            @include('livewire.partials.cart-panel')
        </div>
    </div>
</div>

<!-- FAB: Floating Action Button para Carrito (Móvil - Solo visible cuando hay items) -->
@if(count($cart) > 0)
<button @click="cartDrawerOpen = true"
        x-show="!cartDrawerOpen && isMobile"
        class="lg:hidden fixed bottom-6 right-6 bg-gradient-to-r from-orange-500 to-orange-600 text-white rounded-full p-4 shadow-2xl hover:shadow-orange-500/50 transform hover:scale-110 active:scale-95 transition-all z-40 min-w-[56px] min-h-[56px] flex items-center justify-center">
    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
    </svg>
    <span class="absolute -top-1 -right-1 bg-red-600 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center">
        {{ count($cart) }}
    </span>
</button>
@endif

<!-- Mensajes Flash -->
@if (session()->has('success'))
    <div class="fixed top-4 right-4 z-50 animate-slide-in bg-green-500 text-white p-4 rounded-lg shadow-xl">
        {{ session('success') }}
    </div>
@endif

@if (session()->has('error'))
    <div class="fixed top-4 right-4 z-50 animate-slide-in bg-red-500 text-white p-4 rounded-lg shadow-xl">
        {{ session('error') }}
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

