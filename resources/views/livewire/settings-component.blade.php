<div class="min-h-screen bg-gray-900 p-6">
    {{-- HEADER --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-white">⚙️ Configuración del Sistema</h1>
        <p class="text-gray-400 mt-1">Gestiona los parámetros generales, usuarios y reglas de negocio</p>
    </div>

    {{-- TABS NAVIGATION --}}
    <div class="bg-gray-800 rounded-lg shadow-lg mb-6">
        <div class="flex border-b border-gray-700">
            <button 
                wire:click="setActiveTab('general')"
                class="flex-1 px-6 py-4 text-sm font-medium transition-all {{ $activeTab === 'general' ? 'text-orange-500 border-b-2 border-orange-500 bg-gray-700' : 'text-gray-400 hover:text-white hover:bg-gray-750' }}">
                🏢 General
            </button>
            <button 
                wire:click="setActiveTab('users')"
                class="flex-1 px-6 py-4 text-sm font-medium transition-all {{ $activeTab === 'users' ? 'text-orange-500 border-b-2 border-orange-500 bg-gray-700' : 'text-gray-400 hover:text-white hover:bg-gray-750' }}">
                👥 Usuarios
            </button>
            <button 
                wire:click="setActiveTab('business')"
                class="flex-1 px-6 py-4 text-sm font-medium transition-all {{ $activeTab === 'business' ? 'text-orange-500 border-b-2 border-orange-500 bg-gray-700' : 'text-gray-400 hover:text-white hover:bg-gray-750' }}">
                📊 Reglas de Negocio
            </button>
        </div>
    </div>

    {{-- TAB 1: GENERAL (EMPRESA) --}}
    @if($activeTab === 'general')
    <div class="bg-gray-800 rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-white mb-4">🏢 Información de la Empresa</h2>
        
        <form wire:submit.prevent="saveGeneralSettings" class="space-y-4">
            {{-- Nombre del Negocio --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Nombre del Negocio</label>
                <input type="text" wire:model="company_name" 
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/50">
                @error('company_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- RUC --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">RUC</label>
                <input type="text" wire:model="company_ruc" 
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/50">
                @error('company_ruc') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Dirección --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Dirección</label>
                <textarea wire:model="company_address" rows="2"
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/50"></textarea>
                @error('company_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Teléfono --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Teléfono</label>
                <input type="text" wire:model="company_phone" 
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/50">
                @error('company_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Sitio Web --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Sitio Web</label>
                <input type="text" wire:model="company_website" 
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/50">
                @error('company_website') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Mensaje del Ticket --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Mensaje del Ticket (Pie de Página)</label>
                <input type="text" wire:model="ticket_footer_message" 
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/50"
                    placeholder="Ej: GRACIAS CHE !">
                @error('ticket_footer_message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Zona Horaria --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Zona Horaria</label>
                <select wire:model="timezone" 
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-orange-500 focus:ring-2 focus:ring-orange-500/50">
                    @foreach($timezones as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('timezone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            {{-- Botón Guardar --}}
            <div class="flex justify-end">
                <button type="submit" 
                    class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded-lg transition-all shadow-lg hover:shadow-orange-500/50">
                    💾 Guardar Configuración
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- TAB 2: USUARIOS --}}
    @if($activeTab === 'users')
    <div class="bg-gray-800 rounded-lg shadow-lg p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-white">👥 Gestión de Usuarios</h2>
            <button wire:click="openNewUserModal" 
                class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-4 py-2 rounded-lg transition-all shadow-lg">
                ➕ Nuevo Usuario
            </button>
        </div>

        {{-- Tabla de Usuarios --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-300">Nombre</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-300">Email</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-gray-300">Fecha de Registro</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-300">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-700">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-750 transition-colors">
                        <td class="px-4 py-3 text-white">{{ $user['name'] }}</td>
                        <td class="px-4 py-3 text-gray-300">{{ $user['email'] }}</td>
                        <td class="px-4 py-3 text-gray-400">{{ \Carbon\Carbon::parse($user['created_at'])->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-center">
                            <button wire:click="editUser({{ $user['id'] }})" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm mr-2">
                                ✏️ Editar
                            </button>
                            @if($user['id'] !== auth()->id())
                            <button wire:click="deleteUser({{ $user['id'] }})" 
                                onclick="return confirm('¿Eliminar este usuario?')"
                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-sm">
                                🗑️ Eliminar
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal de Usuario --}}
    @if($showUserModal)
    <div class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-gray-800 rounded-lg shadow-2xl p-6 w-full max-w-md">
            <h3 class="text-xl font-bold text-white mb-4">
                {{ $editingUserId ? '✏️ Editar Usuario' : '➕ Nuevo Usuario' }}
            </h3>

            <form wire:submit.prevent="saveUser" class="space-y-4">
                {{-- Nombre --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nombre</label>
                    <input type="text" wire:model="user_name" 
                        class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-orange-500">
                    @error('user_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <input type="email" wire:model="user_email" 
                        class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-orange-500">
                    @error('user_email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- Contraseña --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">
                        {{ $editingUserId ? 'Nueva Contraseña (dejar vacío para no cambiar)' : 'Contraseña' }}
                    </label>
                    <input type="password" wire:model="user_password" 
                        class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-orange-500">
                    @error('user_password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                {{-- Confirmar Contraseña --}}
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Confirmar Contraseña</label>
                    <input type="password" wire:model="user_password_confirmation" 
                        class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-orange-500">
                </div>

                {{-- Botones --}}
                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" wire:click="closeModal" 
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">
                        Cancelar
                    </button>
                    <button type="submit" 
                        class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-lg">
                        {{ $editingUserId ? 'Actualizar' : 'Crear' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
    @endif

    {{-- TAB 3: REGLAS DE NEGOCIO --}}
    @if($activeTab === 'business')
    <div class="bg-gray-800 rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-white mb-4">📊 Reglas de Negocio</h2>
        
        <form wire:submit.prevent="saveBusinessSettings" class="space-y-6">
            {{-- Toggle: Multi-Precios --}}
            <div class="flex items-center justify-between p-4 bg-gray-700 rounded-lg">
                <div>
                    <h3 class="text-white font-semibold">🏷️ Habilitar Multi-Precios</h3>
                    <p class="text-gray-400 text-sm">Permite definir precios diferentes (Local, Rappi, Web) por producto</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="enable_multi_pricing" class="sr-only peer">
                    <div class="w-14 h-7 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-500/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-orange-500"></div>
                </label>
            </div>

            {{-- Tasa de IGV --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Tasa de IGV (%)</label>
                <input type="text" wire:model="tax_rate" 
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-orange-500"
                    placeholder="0.18">
                <p class="text-gray-400 text-sm mt-1">0.18 = 18%</p>
            </div>

            {{-- Moneda --}}
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Moneda Predeterminada</label>
                <input type="text" wire:model="default_currency" 
                    class="w-full bg-gray-700 border border-gray-600 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-orange-500"
                    placeholder="S/">
            </div>

            {{-- Toggle: Inventario --}}
            <div class="flex items-center justify-between p-4 bg-gray-700 rounded-lg">
                <div>
                    <h3 class="text-white font-semibold">📦 Control de Inventario</h3>
                    <p class="text-gray-400 text-sm">Activar gestión de stock de productos</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="enable_inventory" class="sr-only peer">
                    <div class="w-14 h-7 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-500/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-orange-500"></div>
                </label>
            </div>

            {{-- Toggle: Descuentos --}}
            <div class="flex items-center justify-between p-4 bg-gray-700 rounded-lg">
                <div>
                    <h3 class="text-white font-semibold">💰 Permitir Descuentos</h3>
                    <p class="text-gray-400 text-sm">Habilitar la opción de aplicar descuentos en órdenes</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="enable_discounts" class="sr-only peer">
                    <div class="w-14 h-7 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-500/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-orange-500"></div>
                </label>
            </div>

            {{-- Toggle: Impresión Automática --}}
            <div class="flex items-center justify-between p-4 bg-gray-700 rounded-lg">
                <div>
                    <h3 class="text-white font-semibold">🖨️ Impresión Automática</h3>
                    <p class="text-gray-400 text-sm">Imprimir ticket automáticamente al finalizar orden</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" wire:model="auto_print" class="sr-only peer">
                    <div class="w-14 h-7 bg-gray-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-500/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-orange-500"></div>
                </label>
            </div>

            {{-- Botón Guardar --}}
            <div class="flex justify-end">
                <button type="submit" 
                    class="bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-3 rounded-lg transition-all shadow-lg hover:shadow-orange-500/50">
                    💾 Guardar Reglas
                </button>
            </div>
        </form>
    </div>
    @endif
</div>
