# 🎨 ElchePizza Design System

## Sistema de Diseño - Dark Mode con Acentos Naranja

### 📋 Tabla de Contenidos

1. [Paleta de Colores](#paleta-de-colores)
2. [Componentes UI](#componentes-ui)
3. [Tipografía](#tipografía)
4. [Espaciado](#espaciado)
5. [Guía de Uso](#guía-de-uso)
6. [Ejemplos](#ejemplos)

---

## 🎨 Paleta de Colores

### Colores Principales

```css
/* Fondo */
bg-gray-900    /* Fondo principal (muy oscuro) */
bg-gray-800    /* Tarjetas y paneles */
bg-gray-700    /* Elementos secundarios */

/* Primario (Naranja) */
bg-orange-500  /* Color principal */
bg-orange-600  /* Hover */
bg-orange-700  /* Active/Pressed */

/* Texto */
text-white     /* Texto principal */
text-gray-300  /* Texto secundario */
text-gray-400  /* Texto terciario/labels */
text-gray-500  /* Placeholders */

/* Bordes */
border-gray-700  /* Bordes normales */
border-gray-600  /* Bordes de inputs */
border-orange-500 /* Bordes activos */
```

### Colores de Estado

```css
/* Success */
bg-green-500, text-green-400, border-green-500

/* Warning */
bg-yellow-500, text-yellow-400, border-yellow-500

/* Danger */
bg-red-600, text-red-400, border-red-500

/* Info */
bg-blue-500, text-blue-400, border-blue-500
```

---

## 🧩 Componentes UI

### 1. Botones

#### Button Primary (Acción Principal)
```blade
<x-button-primary>
    Guardar Cambios
</x-button-primary>

<!-- Con tamaño -->
<x-button-primary size="lg">
    Crear Orden
</x-button-primary>

<!-- Con ícono -->
<x-button-primary icon="➕">
    Nueva Venta
</x-button-primary>

<!-- Ancho completo -->
<x-button-primary fullWidth>
    Continuar
</x-button-primary>

<!-- Con loading -->
<x-button-primary :loading="$isLoading">
    Procesando...
</x-button-primary>
```

**Props:**
- `type` (button|submit|reset) - Tipo de botón
- `size` (sm|md|lg) - Tamaño del botón
- `fullWidth` (boolean) - Ancho completo
- `icon` (string) - HTML del ícono
- `loading` (boolean) - Estado de carga

**Clases aplicadas:**
- Base: `bg-orange-500 hover:bg-orange-600 text-white`
- Shadow: `shadow-md hover:shadow-lg`
- Focus: `focus:ring-2 focus:ring-orange-500`

#### Button Secondary (Acción Secundaria)
```blade
<x-button-secondary>
    Cancelar
</x-button-secondary>

<x-button-secondary size="sm" icon="↩️">
    Volver
</x-button-secondary>
```

**Clases aplicadas:**
- Base: `bg-gray-700 hover:bg-gray-600 text-white`
- Border: `border border-gray-600`

#### Button Danger (Acción Destructiva)
```blade
<x-button-danger icon="🗑️">
    Eliminar
</x-button-danger>
```

---

### 2. Formularios

#### Form Input
```blade
<x-form-input 
    label="Nombre del Cliente"
    name="customer_name"
    placeholder="Ej: Juan Pérez"
    required
    helpText="Ingresa el nombre completo del cliente"
/>

<!-- Con ícono -->
<x-form-input 
    label="Teléfono"
    name="phone"
    type="tel"
    icon='<svg>...</svg>'
/>

<!-- Con error -->
<x-form-input 
    label="Email"
    name="email"
    type="email"
    error="El email es requerido"
/>
```

**Props:**
- `label` (string) - Etiqueta del campo
- `name` (string) - Nombre del input
- `type` (text|email|number|tel|password) - Tipo de input
- `placeholder` (string) - Texto placeholder
- `required` (boolean) - Campo requerido
- `error` (string) - Mensaje de error
- `helpText` (string) - Texto de ayuda
- `icon` (string) - HTML del ícono

**Clases aplicadas:**
- Base: `bg-gray-800 border-gray-600 text-white`
- Placeholder: `placeholder-gray-500`
- Focus: `focus:border-orange-500 focus:ring-2 focus:ring-orange-500`

#### Form Select
```blade
<x-form-select 
    label="Tipo de Pedido"
    name="order_type"
    :options="[
        'delivery' => 'Delivery',
        'mesa' => 'Mesa',
        'para_llevar' => 'Para Llevar',
    ]"
    required
/>
```

#### Form Textarea
```blade
<x-form-textarea 
    label="Notas del Pedido"
    name="notes"
    rows="4"
    placeholder="Instrucciones especiales..."
    helpText="Opcional: agrega cualquier detalle importante"
/>
```

---

### 3. Cards (Tarjetas)

```blade
<!-- Card simple -->
<x-card>
    <p>Contenido de la tarjeta</p>
</x-card>

<!-- Card con título -->
<x-card title="Información del Pedido">
    <p>Detalles del pedido...</p>
</x-card>

<!-- Card con título y subtítulo -->
<x-card 
    title="Orden #0045"
    subtitle="Creado hace 5 minutos">
    <p>Contenido...</p>
</x-card>

<!-- Card sin padding -->
<x-card :padding="false">
    <div class="p-0">Contenido custom</div>
</x-card>
```

**Clases aplicadas:**
- Base: `bg-gray-800 border border-gray-700 rounded-lg`
- Shadow: `shadow-lg hover:shadow-xl`
- Transition: `transition-all duration-200`

---

### 4. Badges (Insignias)

```blade
<!-- Default -->
<x-badge>Pendiente</x-badge>

<!-- Variantes -->
<x-badge variant="success">Completado</x-badge>
<x-badge variant="warning">En proceso</x-badge>
<x-badge variant="danger">Cancelado</x-badge>
<x-badge variant="info">Información</x-badge>
<x-badge variant="primary">Destacado</x-badge>

<!-- Tamaños -->
<x-badge size="sm">Pequeño</x-badge>
<x-badge size="md">Mediano</x-badge>
<x-badge size="lg">Grande</x-badge>
```

**Variantes de colores:**
- `success`: Verde (para estados completados)
- `warning`: Amarillo (para advertencias)
- `danger`: Rojo (para errores o cancelaciones)
- `info`: Azul (para información)
- `primary`: Naranja (para destacar)
- `default`: Gris (neutro)

---

## 📝 Tipografía

### Jerarquía de Textos

```blade
<!-- Títulos -->
<h1 class="text-3xl font-bold text-white">Título Principal</h1>
<h2 class="text-2xl font-semibold text-white">Título Secundario</h2>
<h3 class="text-xl font-semibold text-white">Título Terciario</h3>
<h4 class="text-lg font-medium text-white">Subtítulo</h4>

<!-- Texto Normal -->
<p class="text-base text-gray-300">Texto regular</p>
<p class="text-sm text-gray-400">Texto secundario</p>
<p class="text-xs text-gray-500">Texto pequeño / metadata</p>

<!-- Labels -->
<label class="text-sm font-medium text-gray-300">Etiqueta de Campo</label>

<!-- Texto de ayuda -->
<span class="text-xs text-gray-400">Texto de ayuda o descripción</span>
```

### Pesos de Fuente

```css
font-light      /* 300 */
font-normal     /* 400 */
font-medium     /* 500 */
font-semibold   /* 600 */
font-bold       /* 700 */
font-black      /* 900 */
```

---

## 📏 Espaciado

### Sistema de Spacing (Tailwind)

```css
/* Padding */
p-0   /* 0px */
p-1   /* 4px */
p-2   /* 8px */
p-3   /* 12px */
p-4   /* 16px */
p-6   /* 24px */
p-8   /* 32px */

/* Margin */
m-0, m-1, m-2, m-3, m-4, m-6, m-8 /* Igual que padding */

/* Gap (para Flexbox/Grid) */
gap-1, gap-2, gap-3, gap-4, gap-6 /* Igual que padding */
```

### Espaciado Recomendado

```blade
<!-- Entre secciones -->
<div class="mb-8"></div>

<!-- Entre elementos de formulario -->
<div class="mb-4"></div>

<!-- Entre botones -->
<div class="gap-2"></div>

<!-- Padding de contenedores -->
<div class="p-6"></div>
```

---

## 🚀 Guía de Uso

### ✅ Buenas Prácticas

1. **Usar siempre los componentes del sistema:**
   ```blade
   <!-- ✅ Correcto -->
   <x-button-primary>Guardar</x-button-primary>
   
   <!-- ❌ Evitar -->
   <button class="bg-orange-500...">Guardar</button>
   ```

2. **Mantener consistencia en el spacing:**
   ```blade
   <!-- ✅ Correcto -->
   <x-form-input class="mb-4" />
   <x-form-input class="mb-4" />
   
   <!-- ❌ Evitar -->
   <x-form-input class="mb-2" />
   <x-form-input class="mb-6" />
   ```

3. **Usar variantes semánticas:**
   ```blade
   <!-- ✅ Correcto -->
   <x-button-danger>Eliminar</x-button-danger>
   
   <!-- ❌ Evitar -->
   <x-button-primary class="bg-red-500">Eliminar</x-button-primary>
   ```

4. **Dark mode siempre activo:**
   ```blade
   <!-- ✅ Correcto -->
   <div class="bg-gray-900 text-white">
   
   <!-- ❌ Evitar -->
   <div class="bg-white text-black">
   ```

### ❌ Anti-patrones

1. **No usar colores arbitrarios:**
   ```blade
   <!-- ❌ Malo -->
   <button class="bg-purple-500">Click</button>
   
   <!-- ✅ Bueno -->
   <x-button-primary>Click</x-button-primary>
   ```

2. **No mezclar estilos inline:**
   ```blade
   <!-- ❌ Malo -->
   <x-button-primary style="background: red;">Click</x-button-primary>
   
   <!-- ✅ Bueno -->
   <x-button-danger>Click</x-button-danger>
   ```

3. **No omitir labels en formularios:**
   ```blade
   <!-- ❌ Malo -->
   <input type="text" placeholder="Nombre">
   
   <!-- ✅ Bueno -->
   <x-form-input label="Nombre" placeholder="Ej: Juan Pérez" />
   ```

---

## 💡 Ejemplos Completos

### Formulario de Pedido

```blade
<x-card title="Nueva Orden" subtitle="Completa los datos del pedido">
    <form wire:submit.prevent="save">
        <x-form-input 
            label="Cliente"
            name="customer_name"
            wire:model="customer_name"
            required
        />
        
        <x-form-select 
            label="Tipo de Pedido"
            name="order_type"
            wire:model="order_type"
            :options="[
                'delivery' => 'Delivery',
                'mesa' => 'Mesa',
                'para_llevar' => 'Para Llevar',
            ]"
            required
        />
        
        <x-form-textarea 
            label="Notas"
            name="notes"
            wire:model="notes"
            rows="3"
            placeholder="Instrucciones especiales..."
        />
        
        <div class="flex gap-2 mt-6">
            <x-button-secondary type="button" onclick="history.back()">
                Cancelar
            </x-button-secondary>
            
            <x-button-primary type="submit" fullWidth :loading="$isSaving">
                Guardar Orden
            </x-button-primary>
        </div>
    </form>
</x-card>
```

### Tarjeta de Orden

```blade
<x-card :padding="false">
    <div class="bg-gradient-to-r from-green-500 to-green-600 p-4 text-white">
        <div class="text-2xl font-black">#045</div>
        <x-badge class="bg-white/20 text-white mt-2">
            🚚 Delivery
        </x-badge>
    </div>
    
    <div class="p-4 bg-gray-900">
        <p class="text-sm text-gray-400">Cliente</p>
        <p class="font-bold text-white">Juan Pérez</p>
    </div>
    
    <div class="p-4 bg-gray-800">
        <div class="flex justify-between mb-4">
            <span class="text-gray-300">Total</span>
            <span class="text-2xl font-black text-orange-500">S/ 45.00</span>
        </div>
        
        <div class="grid grid-cols-2 gap-2">
            <x-button-secondary size="sm" fullWidth>
                Editar
            </x-button-secondary>
            <x-button-primary size="sm" fullWidth>
                Completar
            </x-button-primary>
        </div>
    </div>
</x-card>
```

---

## 🎯 Reglas de Oro

1. **Siempre usar Dark Mode** - `bg-gray-900`, `bg-gray-800`
2. **Naranja es el color primario** - Para acciones principales
3. **Consistencia en spacing** - `mb-4` entre elementos de formulario
4. **Usar componentes, no clases sueltas** - Mantiene el diseño unificado
5. **Semántica en colores** - Verde=success, Rojo=danger, etc.
6. **Focus rings siempre visibles** - Para accesibilidad
7. **Transiciones suaves** - `transition-all duration-200`
8. **Shadows para profundidad** - `shadow-lg` en tarjetas

---

## 📦 Resumen de Componentes

| Componente | Archivo | Uso Principal |
|------------|---------|---------------|
| `<x-button-primary>` | button-primary.blade.php | Acciones principales |
| `<x-button-secondary>` | button-secondary.blade.php | Acciones secundarias |
| `<x-button-danger>` | button-danger.blade.php | Acciones destructivas |
| `<x-form-input>` | form-input.blade.php | Campos de texto |
| `<x-form-select>` | form-select.blade.php | Listas desplegables |
| `<x-form-textarea>` | form-textarea.blade.php | Texto multilínea |
| `<x-card>` | card.blade.php | Contenedores |
| `<x-badge>` | badge.blade.php | Estados e insignias |

---

**Versión:** 1.0  
**Fecha:** Febrero 2026  
**Autor:** Sistema ElchePizza  
**Framework:** Laravel + Livewire + Tailwind CSS  

🎨 **¡Mantén la consistencia y tu UI será profesional!**

