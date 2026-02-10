# ⚙️ MÓDULO DE CONFIGURACIÓN DEL SISTEMA
## Sistema POS ElchePizza | Gestión Dinámica de Settings

---

## 🎯 **OBJETIVO**

Reemplazar todos los valores "hardcoded" (escritos directamente en el código) por configuraciones dinámicas gestionadas desde un panel administrativo.

**Antes:**
```php
<div class="logo">ELCHEPIZZA</div>
<div>RUC: 10447303766</div>
```

**Después:**
```php
<div class="logo">{{ Setting::get('company_name') }}</div>
<div>RUC: {{ Setting::get('company_ruc') }}</div>
```

---

## 📦 **ARCHIVOS CREADOS/MODIFICADOS**

### **1. Base de Datos**
- ✅ `database/migrations/2026_02_10_081658_create_settings_table.php`
- ✅ `database/seeders/SettingsSeeder.php`

### **2. Modelo y Lógica**
- ✅ `app/Models/Setting.php` (Modelo con helpers estáticos)

### **3. Componente Livewire**
- ✅ `app/Livewire/SettingsComponent.php` (Lógica del panel)
- ✅ `resources/views/livewire/settings-component.blade.php` (Vista con pestañas)

### **4. Filament Resource**
- ✅ `app/Filament/Resources/SettingsResource.php` (Solo navegación)

### **5. Rutas**
- ✅ `routes/web.php` (Ruta `/settings`)

### **6. Vistas Refactorizadas**
- ✅ `resources/views/ticket.blade.php` (Ahora usa `Setting::get()`)

---

## 🗄️ **ESTRUCTURA DE LA TABLA `settings`**

| Columna | Tipo | Descripción |
|---------|------|-------------|
| `id` | bigint | ID autoincremental |
| `key` | string (unique) | Clave única (ej: `company_name`) |
| `value` | text | Valor almacenado |
| `group` | string | Grupo (general, business, security, printing) |
| `type` | string | Tipo de dato (string, boolean, integer, json) |
| `description` | text | Descripción para el administrador |
| `created_at` | timestamp | Fecha de creación |
| `updated_at` | timestamp | Fecha de actualización |

---

## 🚀 **CONFIGURACIONES DISPONIBLES**

### **GRUPO: GENERAL (Información de la Empresa)**

| Key | Tipo | Descripción | Valor por Defecto |
|-----|------|-------------|-------------------|
| `company_name` | string | Nombre del negocio | ELCHEPIZZA |
| `company_ruc` | string | RUC de la empresa | 10447303766 |
| `company_address` | string | Dirección completa | Res. Praderas de Pariachi mz G lt 9 ATE |
| `company_phone` | string | Teléfono de contacto | 952 208 570 |
| `company_website` | string | Sitio web | www.elchepizza.pe |
| `ticket_footer_message` | string | Mensaje del pie del ticket | GRACIAS CHE ! |
| `timezone` | string | Zona horaria | America/Lima |

### **GRUPO: BUSINESS (Reglas de Negocio)**

| Key | Tipo | Descripción | Valor por Defecto |
|-----|------|-------------|-------------------|
| `enable_multi_pricing` | boolean | Habilitar múltiples precios (Local, Rappi, Web) | false |
| `tax_rate` | string | Tasa de IGV (0.18 = 18%) | 0.18 |
| `default_currency` | string | Símbolo de moneda | S/ |
| `enable_inventory` | boolean | Habilitar control de inventario | false |
| `enable_discounts` | boolean | Permitir descuentos en órdenes | true |

### **GRUPO: SECURITY (Seguridad)**

| Key | Tipo | Descripción | Valor por Defecto |
|-----|------|-------------|-------------------|
| `require_login` | boolean | Requerir login para acceder al POS | true |
| `session_timeout` | integer | Tiempo de sesión en minutos | 120 |
| `enable_audit_log` | boolean | Registrar log de auditoría | true |

### **GRUPO: PRINTING (Impresión)**

| Key | Tipo | Descripción | Valor por Defecto |
|-----|------|-------------|-------------------|
| `printer_width` | integer | Ancho del papel (mm) | 58 |
| `auto_print` | boolean | Imprimir automáticamente al finalizar orden | true |
| `print_copies` | integer | Número de copias a imprimir | 1 |

---

## 💻 **CÓMO USAR EL HELPER `Setting::get()`**

### **En Vistas Blade:**

```php
{{-- Obtener un valor --}}
<div>{{ Setting::get('company_name') }}</div>

{{-- Obtener con valor por defecto --}}
<div>{{ Setting::get('company_name', 'Mi Negocio') }}</div>

{{-- Usar en condiciones --}}
@if(Setting::get('enable_multi_pricing'))
    {{-- Mostrar campos de multi-precio --}}
@endif
```

### **En Controladores/Componentes Livewire:**

```php
use App\Models\Setting;

// Obtener un valor
$companyName = Setting::get('company_name');

// Obtener con valor por defecto
$taxRate = Setting::get('tax_rate', '0.18');

// Verificar si existe
if (Setting::has('enable_inventory')) {
    // ...
}

// Establecer un valor
Setting::set('company_name', 'Nuevo Nombre', 'general', 'string');

// Obtener todo un grupo
$generalSettings = Setting::getGroup('general');

// Eliminar una configuración
Setting::remove('old_setting');

// Limpiar caché de configuraciones
Setting::clearCache();
```

### **En PHP Puro (fuera de Laravel):**

```php
// Si necesitas usar en archivos que no tienen acceso a facades
$setting = \App\Models\Setting::where('key', 'company_name')->first();
echo $setting->value;
```

---

## 🎨 **INTERFAZ DE USUARIO**

### **Acceso:**
1. Inicia sesión en el sistema
2. Ve al menú lateral → **"Sistema"**
3. Haz clic en **"Configuración del Sistema"** ⚙️

### **Pestañas Disponibles:**

#### **TAB 1: General (Empresa) 🏢**
- Campos de texto para nombre, RUC, dirección, teléfono, sitio web
- Campo para mensaje personalizado del ticket
- Dropdown para seleccionar zona horaria

**Diseño:** Formulario vertical con inputs grandes y claros

---

#### **TAB 2: Usuarios 👥**
- Tabla con lista de usuarios (Nombre, Email, Fecha de registro)
- Botón "Nuevo Usuario" (abre modal)
- Botones "Editar" y "Eliminar" por cada usuario

**Modal de Usuario:**
- Campos: Nombre, Email, Contraseña, Confirmar Contraseña
- Al editar: Contraseña es opcional (solo si quieres cambiarla)

---

#### **TAB 3: Reglas de Negocio 📊**
- **Toggle "Habilitar Multi-Precios":** 
  - ✅ ON: Los productos mostrarán campos para Precio Local, Precio Rappi, Precio Web
  - ❌ OFF: Solo un precio único por producto

- **Campo "Tasa de IGV":** Para ajustar el porcentaje de impuesto

- **Campo "Moneda Predeterminada":** Símbolo que aparece en todo el sistema

- **Toggle "Control de Inventario":** Activar gestión de stock

- **Toggle "Permitir Descuentos":** Habilitar descuentos en órdenes

- **Toggle "Impresión Automática":** Imprimir ticket sin diálogo

**Diseño:** Interruptores (toggles) estilo iOS con colores:
- OFF: Gris (`bg-gray-600`)
- ON: Naranja (`bg-orange-500`)

---

## 🔧 **CASOS DE USO AVANZADOS**

### **1. Implementar Multi-Precios en Productos**

**Escenario:** El toggle "Habilitar Multi-Precios" está activado.

**En `ProductResource.php` (Filament):**

```php
use App\Models\Setting;

public static function form(Form $form): Form
{
    $multiPricingEnabled = Setting::get('enable_multi_pricing', false);

    $priceFields = $multiPricingEnabled 
        ? [
            Forms\Components\TextInput::make('price_local')
                ->label('Precio Local')
                ->numeric()
                ->prefix('S/'),
            Forms\Components\TextInput::make('price_rappi')
                ->label('Precio Rappi')
                ->numeric()
                ->prefix('S/'),
            Forms\Components\TextInput::make('price_web')
                ->label('Precio Web')
                ->numeric()
                ->prefix('S/'),
        ]
        : [
            Forms\Components\TextInput::make('price')
                ->label('Precio')
                ->numeric()
                ->prefix('S/'),
        ];

    return $form->schema([
        Forms\Components\Section::make('Precios')
            ->schema($priceFields),
    ]);
}
```

**Migración necesaria para productos:**

```php
// En una nueva migración
Schema::table('products', function (Blueprint $table) {
    $table->decimal('price_local', 10, 2)->nullable()->after('price');
    $table->decimal('price_rappi', 10, 2)->nullable()->after('price_local');
    $table->decimal('price_web', 10, 2)->nullable()->after('price_rappi');
});
```

---

### **2. Mostrar Configuración en el POS**

**En `TouchPOS.blade.php`:**

```php
{{-- Mostrar nombre de la empresa en el header --}}
<div class="text-xl font-bold">{{ Setting::get('company_name') }}</div>

{{-- Condicional para descuentos --}}
@if(Setting::get('enable_discounts'))
    <button wire:click="applyDiscount" class="btn-secondary">
        💸 Aplicar Descuento
    </button>
@endif
```

---

### **3. Usar en Cálculos de Impuestos**

**En `OrderResource.php` o `TouchPOS.php`:**

```php
use App\Models\Setting;

public function calculateTotal()
{
    $taxRate = (float) Setting::get('tax_rate', '0.18');
    $subtotal = $this->calculateSubtotal();
    
    $igv = $subtotal * $taxRate;
    $total = $subtotal + $igv;
    
    return $total;
}
```

---

### **4. Cambiar Zona Horaria Dinámicamente**

**En `AppServiceProvider.php` (para aplicar globalmente):**

```php
use App\Models\Setting;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Cargar zona horaria desde settings
        $timezone = Setting::get('timezone', config('app.timezone'));
        config(['app.timezone' => $timezone]);
        date_default_timezone_set($timezone);
    }
}
```

---

## 🔒 **CACHÉ Y RENDIMIENTO**

### **Caché Automático:**
El modelo `Setting` implementa caché automático con **1 hora de duración**:

```php
// Se almacena en caché la primera vez que se consulta
Setting::get('company_name'); // Consulta a BD

// Las siguientes veces usa caché
Setting::get('company_name'); // Desde caché (más rápido)
```

### **Limpiar Caché:**

```php
// Limpiar caché de una configuración específica
Cache::forget('setting_company_name');

// Limpiar todas las configuraciones
Setting::clearCache();
```

### **Caché se limpia automáticamente al:**
- Guardar una configuración desde el panel
- Actualizar con `Setting::set()`
- Eliminar con `Setting::remove()`

---

## 🧪 **TESTING**

### **Verificar que un Setting existe:**

```bash
# En tinker
php artisan tinker

# Verificar
Setting::get('company_name')
Setting::has('company_name')
Setting::getGroup('general')
```

### **Cambiar valores desde consola:**

```bash
php artisan tinker

Setting::set('company_name', 'Nuevo Nombre', 'general', 'string')
Setting::set('enable_multi_pricing', true, 'business', 'boolean')
```

---

## 📊 **CHECKLIST DE IMPLEMENTACIÓN**

Marca cuando hayas completado:

- [x] Ejecutar migración (`php artisan migrate`)
- [x] Ejecutar seeder (`php artisan db:seed --class=SettingsSeeder`)
- [x] Verificar que aparece "Configuración del Sistema" en el menú
- [ ] Acceder a `/settings` y probar las 3 pestañas
- [ ] Cambiar el nombre de la empresa y verificar en ticket
- [ ] Cambiar el mensaje del pie de página y verificar en ticket
- [ ] Activar toggle "Multi-Precios" y verificar que funciona
- [ ] Crear un nuevo usuario desde el panel
- [ ] Editar un usuario y cambiar contraseña
- [ ] Cambiar zona horaria y verificar la hora en tickets

---

## 🚀 **PRÓXIMAS MEJORAS SUGERIDAS**

### **1. Agregar más Settings:**
- Logo de la empresa (upload de imagen)
- Colores personalizados del POS
- Configuración de email (SMTP)
- Integraciones con APIs (Rappi, PedidosYa)

### **2. Roles y Permisos:**
```php
// En SettingsResource.php
public static function canViewAny(): bool
{
    return auth()->user()->hasRole('admin');
}
```

### **3. Historial de Cambios:**
- Crear tabla `settings_history` para auditoría
- Registrar quién cambió qué y cuándo

### **4. Importar/Exportar Configuraciones:**
- Botón "Exportar Settings" (genera JSON)
- Botón "Importar Settings" (carga JSON)

---

## 📞 **SOLUCIÓN DE PROBLEMAS**

### **Problema 1: No aparece el menú "Configuración"**

**Solución:**
```bash
php artisan route:clear
php artisan view:clear
php artisan config:clear
```

---

### **Problema 2: Error "Class Setting not found"**

**Solución:**
```bash
composer dump-autoload
```

---

### **Problema 3: Los cambios no se reflejan**

**Solución:**
```php
// Limpiar caché de settings
Setting::clearCache();

// Limpiar caché de Laravel
php artisan cache:clear
```

---

### **Problema 4: Ticket sigue mostrando valores hardcoded**

**Solución:**
```bash
# Limpiar caché de vistas Blade
php artisan view:clear

# Verificar que ticket.blade.php usa Setting::get()
```

---

## 🎓 **EJEMPLO COMPLETO DE REFACTORIZACIÓN**

### **ANTES (Hardcoded):**

```php
<!-- ticket.blade.php -->
<div class="header">
    <div class="logo">ELCHEPIZZA</div>
    <div>RUC: 10447303766</div>
    <div>Res. Praderas de Pariachi mz G lt 9 ATE</div>
    <div>Tel: 952 208 570</div>
</div>

<div class="footer">
    <div>GRACIAS CHE !</div>
    <div>www.elchepizza.pe</div>
</div>
```

### **DESPUÉS (Dinámico):**

```php
<!-- ticket.blade.php -->
<div class="header">
    <div class="logo">{{ Setting::get('company_name') }}</div>
    <div>RUC: {{ Setting::get('company_ruc') }}</div>
    <div>{{ Setting::get('company_address') }}</div>
    <div>Tel: {{ Setting::get('company_phone') }}</div>
</div>

<div class="footer">
    <div>{{ Setting::get('ticket_footer_message') }}</div>
    <div>{{ Setting::get('company_website') }}</div>
</div>
```

**Ventajas:**
✅ Sin tocar código para cambiar datos de la empresa  
✅ Configuración centralizada  
✅ Caché automático para rendimiento  
✅ Historial de cambios (si se implementa)  
✅ Interfaz visual profesional  

---

**Fecha de Implementación:** 10/02/2026  
**Versión:** 1.0  
**Estado:** ✅ LISTO PARA USAR

---

**¡GRACIAS CHE! 🍕**

