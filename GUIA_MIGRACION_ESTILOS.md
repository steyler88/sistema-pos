# 🔄 Guía de Migración al Nuevo Sistema de Diseño

## Migración de Estilos Antiguos al Design System Dark & Orange

### 📋 **Antes de Empezar**

1. **Backup:** Guarda una copia de tus vistas actuales
2. **Testing:** Prueba en ambiente de desarrollo primero
3. **Revisión:** Verifica que Livewire siga funcionando correctamente

---

## 🔄 **Tabla de Conversión Rápida**

### Botones

#### ❌ Antes (Estilos Antiguos)
```blade
<!-- Botón primario antiguo -->
<button class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded">
    Guardar
</button>

<!-- Botón secundario antiguo -->
<button class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded">
    Cancelar
</button>

<!-- Botón con estados de Livewire -->
<button 
    wire:click="save"
    wire:loading.attr="disabled"
    class="px-4 py-2 bg-orange-500...">
    Guardar
</button>
```

#### ✅ Después (Nuevo Sistema)
```blade
<!-- Botón primario nuevo -->
<x-button-primary>
    Guardar
</x-button-primary>

<!-- Botón secundario nuevo -->
<x-button-secondary>
    Cancelar
</x-button-secondary>

<!-- Botón con Livewire (mantiene wire:click) -->
<x-button-primary 
    wire:click="save"
    :loading="$isSaving">
    Guardar
</x-button-primary>
```

---

### Inputs de Formulario

#### ❌ Antes
```blade
<div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2">
        Nombre
    </label>
    <input 
        type="text" 
        wire:model="name"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
        placeholder="Ingresa el nombre">
</div>
```

#### ✅ Después
```blade
<x-form-input 
    label="Nombre"
    name="name"
    wire:model="name"
    placeholder="Ingresa el nombre"
/>
```

---

### Select/Dropdown

#### ❌ Antes
```blade
<div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2">
        Tipo
    </label>
    <select 
        wire:model="type"
        class="block w-full border-gray-300 rounded-md shadow-sm">
        <option value="">Selecciona...</option>
        <option value="delivery">Delivery</option>
        <option value="mesa">Mesa</option>
    </select>
</div>
```

#### ✅ Después
```blade
<x-form-select 
    label="Tipo"
    name="type"
    wire:model="type"
    :options="[
        'delivery' => 'Delivery',
        'mesa' => 'Mesa',
    ]"
    placeholder="Selecciona..."
/>
```

---

### Tarjetas/Cards

#### ❌ Antes
```blade
<div class="bg-white shadow-md rounded-lg p-6 border border-gray-200">
    <h3 class="text-xl font-bold mb-2">Título</h3>
    <p class="text-gray-600">Contenido...</p>
</div>
```

#### ✅ Después
```blade
<x-card title="Título">
    <p class="text-gray-300">Contenido...</p>
</x-card>
```

---

### Badges/Estados

#### ❌ Antes
```blade
<span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded-full">
    Completado
</span>

<span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded-full">
    Cancelado
</span>
```

#### ✅ Después
```blade
<x-badge variant="success">Completado</x-badge>
<x-badge variant="danger">Cancelado</x-badge>
```

---

## 🎨 **Conversión de Colores**

### Fondos

| Antiguo | Nuevo (Dark Mode) |
|---------|-------------------|
| `bg-white` | `bg-gray-900` o `bg-gray-800` |
| `bg-gray-50` | `bg-gray-900` |
| `bg-gray-100` | `bg-gray-800` |
| `bg-gray-200` | `bg-gray-700` |
| `bg-blue-500` | `bg-orange-500` (primario) |

### Textos

| Antiguo | Nuevo (Dark Mode) |
|---------|-------------------|
| `text-gray-900` | `text-white` |
| `text-gray-700` | `text-gray-300` |
| `text-gray-600` | `text-gray-400` |
| `text-gray-500` | `text-gray-500` (igual) |
| `text-black` | `text-white` |

### Bordes

| Antiguo | Nuevo (Dark Mode) |
|---------|-------------------|
| `border-gray-200` | `border-gray-700` |
| `border-gray-300` | `border-gray-600` |
| `border-blue-500` | `border-orange-500` |

---

## 🔧 **Migración Paso a Paso**

### **Paso 1: Identificar Elementos a Migrar**

En cada vista Blade, busca:
- Botones con clases de Tailwind
- Inputs de formulario
- Selects y textareas
- Tarjetas y contenedores
- Badges y estados

### **Paso 2: Reemplazar Componentes**

```blade
<!-- ANTES: touch-pos.blade.php (ejemplo) -->
<button 
    wire:click="$set('order_type', 'mesa')"
    class="flex-1 px-3 py-2 rounded-lg text-xs font-semibold transition-all border-2 
           bg-blue-500 text-white border-blue-500">
    Mesa
</button>

<!-- DESPUÉS: touch-pos.blade.php -->
<x-button-primary 
    wire:click="$set('order_type', 'mesa')"
    size="sm"
    fullWidth>
    Mesa
</x-button-primary>
```

### **Paso 3: Ajustar Colores al Dark Mode**

```blade
<!-- ANTES -->
<div class="bg-white border border-gray-200 rounded-lg p-4">
    <h3 class="text-gray-900 font-bold">Título</h3>
    <p class="text-gray-600">Contenido</p>
</div>

<!-- DESPUÉS -->
<div class="bg-gray-800 border border-gray-700 rounded-lg p-4">
    <h3 class="text-white font-bold">Título</h3>
    <p class="text-gray-300">Contenido</p>
</div>
```

### **Paso 4: Verificar Interactividad Livewire**

```blade
<!-- IMPORTANTE: wire: attributes se mantienen -->
<x-form-input 
    label="Nombre"
    wire:model.live="customer_name"  <!-- ✅ Se mantiene -->
    wire:loading.class="opacity-50"  <!-- ✅ Se mantiene -->
/>
```

---

## 📝 **Checklist de Migración por Vista**

### Para cada archivo .blade.php:

- [ ] **Botones**
  - [ ] Reemplazar botones primarios con `<x-button-primary>`
  - [ ] Reemplazar botones secundarios con `<x-button-secondary>`
  - [ ] Reemplazar botones de eliminar con `<x-button-danger>`
  - [ ] Verificar que wire:click funciona
  - [ ] Agregar loading states donde aplique

- [ ] **Formularios**
  - [ ] Reemplazar inputs con `<x-form-input>`
  - [ ] Reemplazar selects con `<x-form-select>`
  - [ ] Reemplazar textareas con `<x-form-textarea>`
  - [ ] Verificar wire:model
  - [ ] Agregar validaciones visuales

- [ ] **Contenedores**
  - [ ] Reemplazar divs con `<x-card>` donde aplique
  - [ ] Convertir fondos blancos a dark mode
  - [ ] Ajustar colores de texto

- [ ] **Estados**
  - [ ] Reemplazar badges con `<x-badge>`
  - [ ] Usar variantes semánticas (success, danger, etc.)

---

## 🚨 **Errores Comunes y Soluciones**

### Error 1: "Component not found"
```bash
# Solución: Limpiar caché de vistas
php artisan view:clear
```

### Error 2: "Estilos no se aplican"
```bash
# Solución: Verificar que Tailwind CSS esté cargado
# En tu layout principal debe estar:
<script src="https://cdn.tailwindcss.com"></script>
```

### Error 3: "Wire:model no funciona"
```blade
<!-- ❌ Mal: Olvidaste wire:model -->
<x-form-input label="Nombre" name="name" />

<!-- ✅ Bien: Con wire:model -->
<x-form-input label="Nombre" name="name" wire:model="name" />
```

### Error 4: "Atributos no se pasan"
```blade
<!-- ❌ Mal: Intentando override mal -->
<x-button-primary class="bg-red-500">Click</x-button-primary>

<!-- ✅ Bien: Usar el componente correcto -->
<x-button-danger>Click</x-button-danger>
```

---

## 📊 **Ejemplo Completo de Migración**

### Vista: `resources/views/orders/create.blade.php`

#### ❌ **ANTES**
```blade
<div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Nueva Orden</h2>
    
    <form wire:submit.prevent="save">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Cliente
            </label>
            <input 
                type="text"
                wire:model="customer_name"
                class="shadow border rounded w-full py-2 px-3 text-gray-700"
                placeholder="Nombre del cliente">
        </div>
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2">
                Tipo
            </label>
            <select wire:model="order_type" class="w-full border rounded py-2 px-3">
                <option value="delivery">Delivery</option>
                <option value="mesa">Mesa</option>
            </select>
        </div>
        
        <div class="flex gap-2">
            <button 
                type="button"
                class="px-4 py-2 bg-gray-500 text-white rounded"
                onclick="history.back()">
                Cancelar
            </button>
            <button 
                type="submit"
                class="px-4 py-2 bg-blue-500 text-white rounded">
                Guardar
            </button>
        </div>
    </form>
</div>
```

#### ✅ **DESPUÉS**
```blade
<x-card title="Nueva Orden">
    <form wire:submit.prevent="save">
        <x-form-input 
            label="Cliente"
            name="customer_name"
            wire:model="customer_name"
            placeholder="Nombre del cliente"
            required
        />
        
        <x-form-select 
            label="Tipo"
            name="order_type"
            wire:model="order_type"
            :options="[
                'delivery' => 'Delivery',
                'mesa' => 'Mesa',
            ]"
            required
        />
        
        <div class="flex gap-2 mt-6">
            <x-button-secondary type="button" onclick="history.back()">
                Cancelar
            </x-button-secondary>
            <x-button-primary type="submit" fullWidth>
                Guardar
            </x-button-primary>
        </div>
    </form>
</x-card>
```

---

## ⏱️ **Tiempo Estimado de Migración**

| Vista | Complejidad | Tiempo Estimado |
|-------|-------------|-----------------|
| Formulario simple | Baja | 10-15 min |
| Lista con tarjetas | Media | 20-30 min |
| Dashboard completo | Alta | 45-60 min |
| POS completo | Alta | 60-90 min |

---

## ✅ **Verificación Final**

Después de la migración, verifica:

1. [ ] Todos los botones usan componentes del sistema
2. [ ] Todos los inputs usan `<x-form-input>`
3. [ ] Los colores siguen el tema dark (bg-gray-900, bg-gray-800)
4. [ ] Livewire funciona correctamente (wire:model, wire:click)
5. [ ] No hay estilos inline arbitrarios
6. [ ] Los espaciados son consistentes (mb-4)
7. [ ] Los focus rings son visibles (naranja)
8. [ ] Las transiciones son suaves

---

## 🎯 **Prioridad de Migración**

1. **Alta Prioridad:**
   - TouchPOS (interfaz principal)
   - Formulario de órdenes
   - Vista de órdenes activas

2. **Media Prioridad:**
   - Dashboard
   - Listados de productos
   - Reportes

3. **Baja Prioridad:**
   - Configuración
   - Páginas de ayuda
   - Documentación interna

---

**Fecha:** Febrero 2026  
**Versión:** 1.0  
**Estado:** ✅ Listo para usar  

🎨 **¡Empieza con las vistas de alta prioridad y verás resultados inmediatos!**

