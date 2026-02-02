# 🛒 Integración WooCommerce - Documentación

## ✅ TAREA 1: Campo SKU para Productos

### 📦 Archivos Modificados/Creados:
1. **Migración**: `database/migrations/2026_02_02_000001_add_sku_to_products_table.php`
2. **Modelo**: `app/Models/Product.php`
3. **Recurso Filament**: `app/Filament/Resources/ProductResource.php`

### 🚀 Comando para Ejecutar la Migración:
```bash
php artisan migrate
```

### 📝 Características del SKU:
- **Campo**: `sku` (string, nullable, unique)
- **Ubicación**: Después del campo `name` en la tabla
- **UI**: Campo visible en el formulario de Filament con:
  - Label: "SKU (Código Único)"
  - Validación: Único en la base de datos
  - Helper text para guiar al usuario
  - Visible en la tabla de listado como badge azul

---

## ✅ TAREA 2: Webhook para Órdenes de WooCommerce

### 📦 Archivos Creados/Modificados:
1. **Controlador**: `app/Http/Controllers/WooCommerceWebhookController.php`
2. **Rutas API**: `routes/api.php` (creado)
3. **Bootstrap**: `bootstrap/app.php` (actualizado para cargar rutas API)

### 🌐 Endpoint del Webhook:
```
POST https://tu-dominio.com/api/webhooks/woocommerce/order-created
```

### 🔧 Configuración en WooCommerce:
1. Ve a **WooCommerce > Settings > Advanced > Webhooks**
2. Click en **Add webhook**
3. Configura:
   - **Name**: Orden Creada - Sistema CHE
   - **Status**: Active
   - **Topic**: Order created
   - **Delivery URL**: `https://tu-dominio.com/api/webhooks/woocommerce/order-created`
   - **Secret**: (opcional, para mayor seguridad)
   - **API Version**: WP REST API Integration v3

---

## 🧪 Prueba Manual del Webhook

### Usando cURL:
```bash
curl -X POST https://tu-dominio.com/api/webhooks/woocommerce/order-created \
  -H "Content-Type: application/json" \
  -d '{
    "id": 12345,
    "status": "processing",
    "total": "150.50",
    "payment_method": "paypal",
    "customer_note": "Sin cebolla por favor",
    "billing": {
      "first_name": "Juan",
      "last_name": "Pérez",
      "email": "juan@example.com"
    },
    "line_items": [
      {
        "id": 1,
        "name": "Pizza Pepperoni Grande",
        "sku": "PIZZA-PEP-G",
        "quantity": 2,
        "price": "35.00"
      },
      {
        "id": 2,
        "name": "Coca Cola 1L",
        "sku": "BEB-COCA-1L",
        "quantity": 1,
        "price": "8.50"
      }
    ]
  }'
```

### Usando Postman:
1. **Method**: POST
2. **URL**: `https://tu-dominio.com/api/webhooks/woocommerce/order-created`
3. **Headers**: 
   - `Content-Type: application/json`
4. **Body** (raw JSON):
```json
{
  "id": 12345,
  "status": "processing",
  "total": "150.50",
  "payment_method": "paypal",
  "customer_note": "Sin cebolla por favor",
  "billing": {
    "first_name": "Juan",
    "last_name": "Pérez",
    "email": "juan@example.com"
  },
  "line_items": [
    {
      "id": 1,
      "name": "Pizza Pepperoni Grande",
      "sku": "PIZZA-PEP-G",
      "quantity": 2,
      "price": "35.00"
    }
  ]
}
```

---

## 📋 Funcionalidades del Webhook

### ✨ Lo que hace:
1. **Validación**: Verifica que el payload contenga datos válidos
2. **Transacción**: Usa transacciones DB para garantizar integridad
3. **Búsqueda de Cliente**: 
   - Busca cliente por email
   - Si no existe, usa "Cliente Web" como nombre
4. **Matching de Productos**:
   - Busca productos por **SKU**
   - Registra advertencias si un SKU no existe
5. **Creación de Orden**: Guarda la orden completa con todos sus items
6. **Logging**: Registra todo en `storage/logs/laravel.log`

### 🔄 Mapeo de Estados:
| WooCommerce | Sistema CHE |
|------------|-------------|
| pending, processing, on-hold | pending |
| completed | completed |
| cancelled, refunded, failed | cancelled |

### 💳 Mapeo de Métodos de Pago:
| WooCommerce | Sistema CHE |
|------------|-------------|
| bacs, cheque, cod | efectivo |
| paypal | yape |
| stripe, card | tarjeta |

---

## 📝 Respuestas del Endpoint

### ✅ Éxito (200 OK):
```json
{
  "success": true,
  "message": "Orden procesada exitosamente",
  "data": {
    "local_order_id": 45,
    "woocommerce_order_id": 12345,
    "total": "150.50",
    "items_count": 2
  }
}
```

### ⚠️ Éxito con Advertencias:
```json
{
  "success": true,
  "message": "Orden procesada exitosamente",
  "data": { ... },
  "warnings": {
    "items_not_found": [
      "Pizza Hawaiana (SKU: PIZZA-HAW)"
    ],
    "message": "Algunos productos no se encontraron por SKU"
  }
}
```

### ❌ Error (400 Bad Request):
```json
{
  "success": false,
  "message": "Payload inválido: faltan datos requeridos"
}
```

### ❌ Error Interno (500):
```json
{
  "success": false,
  "message": "Error procesando la orden: [detalle del error]"
}
```

---

## 🔍 Verificación de Logs

Para ver los logs del webhook:
```bash
tail -f storage/logs/laravel.log
```

Buscar entradas como:
- `Webhook WooCommerce recibido`
- `Orden WooCommerce procesada exitosamente`
- `Error procesando webhook WooCommerce`

---

## 📚 Próximos Pasos

### 1️⃣ Ejecutar la Migración:
```bash
php artisan migrate
```

### 2️⃣ Asignar SKU a Productos Existentes:
- Ve a **Productos** en el panel de Filament
- Edita cada producto y asigna un SKU único
- Ejemplo: `PIZZA-PEP-G`, `BEB-COCA-1L`, etc.

### 3️⃣ Hacer coincidir SKUs con WooCommerce:
- En WooCommerce, edita cada producto
- Asigna el **mismo SKU** que pusiste en tu sistema local

### 4️⃣ Configurar el Webhook en WooCommerce:
- Sigue las instrucciones de la sección "Configuración en WooCommerce"

### 5️⃣ Probar:
- Crea una orden de prueba en WooCommerce
- Verifica que aparezca en tu sistema local en **Ventas / Caja > Historial**

---

## 🛡️ Seguridad (Recomendación)

Para mayor seguridad, considera agregar autenticación al webhook:

1. **Usar un Secret Key de WooCommerce**
2. **Validar la firma HMAC** en el controlador
3. **Restringir IPs** si WooCommerce está en un servidor fijo

---

## 🆘 Troubleshooting

### Problema: "SKU no encontrado"
- **Solución**: Asegúrate de que el SKU en WooCommerce coincida exactamente con el SKU en tu sistema local

### Problema: "Endpoint no encontrado (404)"
- **Solución**: Ejecuta `php artisan route:clear` y `php artisan optimize:clear`

### Problema: "No se guardan las órdenes"
- **Solución**: Revisa los logs en `storage/logs/laravel.log` para ver detalles del error

---

## 📞 Soporte

Para debugging adicional, todos los eventos se registran en el log.
Usa `Log::info()` para rastrear el flujo de datos.

