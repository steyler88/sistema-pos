# 🚀 INSTRUCCIONES RÁPIDAS DE PRUEBA
## Impresión Silenciosa - POS ElchePizza

---

## ✅ **TODOS LOS CAMBIOS APLICADOS**

### **1. Zona Horaria Configurada ✓**
- Archivo: `config/app.php`
- Cambio: `'timezone' => 'America/Lima'`

### **2. Datos Reales de ElchePizza ✓**
- **RUC:** 10447303766
- **Dirección:** Res. Praderas de Pariachi mz G lt 9 ATE
- **Teléfono:** 952 208 570
- **Mensaje:** GRACIAS CHE !

### **3. JavaScript de Impresión Automática ✓**
- Tiempo de espera: 300ms
- Cierre automático: 1.5 segundos
- Auto-print activado

---

## 🧪 **PRUEBA RÁPIDA - MÉTODO 1 (SIN MODO KIOSCO)**

### **Paso 1: Probar Cambios Básicos**

1. **Abre tu navegador** (Chrome, Firefox, Edge).

2. **Ve a:** `http://sistema-che.test/pos`

3. **Inicia sesión** si es necesario.

4. **Agrega productos al carrito** (2-3 productos de prueba).

5. **Selecciona:**
   - Tipo de servicio: **MESA**
   - Número de mesa: **5**
   - Método de pago: **EFECTIVO**

6. **Presiona el botón grande "CUENTA"**.

**✅ Deberías ver:**
- Se abre una nueva ventana/pestaña con el ticket.
- **Aparece el diálogo de impresión del navegador** (normal en este modo).
- Datos correctos en el ticket:
  - RUC: 10447303766
  - Dirección completa (en 2 líneas si es necesario).
  - Teléfono: 952 208 570
  - Fecha y hora correcta de Perú.
  - Mensaje: "GRACIAS CHE !"

---

## 🏆 **PRUEBA COMPLETA - MÉTODO 2 (CON MODO KIOSCO)**

### **Paso 1: Configurar Acceso Directo**

1. **Haz doble clic** en el archivo:
   ```
   Iniciar_POS_Kiosco.bat
   ```
   *(Este archivo está en la carpeta raíz del proyecto)*

2. **Verás una ventana negra** que dice:
   ```
   [1/3] Cerrando instancias previas de Chrome...
   [2/3] Configurando impresora...
   [3/3] Iniciando Chrome en Modo Kiosco...
   ```

3. **Se abrirá Chrome** en modo aplicación (sin barra de direcciones).

---

### **Paso 2: Realizar Venta de Prueba**

1. **Agrega productos** al carrito.

2. **Selecciona:**
   - Mesa: 1
   - Pago: Efectivo

3. **Presiona "CUENTA"**.

**✅ Resultado Esperado (Impresión Silenciosa):**
- ⚡ Se abre ventana del ticket (1 segundo).
- 🖨️ **La impresora comienza a imprimir SIN DIÁLOGO**.
- ✅ La ventana se cierra sola.
- 🎉 **¡Listo! Sin clics adicionales.**

---

## 🔧 **SI NO FUNCIONA LA IMPRESIÓN AUTOMÁTICA**

### **Solución 1: Configurar Impresora Predeterminada**

1. **Abre Chrome normalmente** (no el script).

2. **Ve a:**
   ```
   chrome://settings/printing
   ```

3. **En "Impresora predeterminada"**, selecciona:
   ```
   58E (tu impresora térmica)
   ```

4. **Cierra Chrome completamente**.

5. **Ejecuta de nuevo** `Iniciar_POS_Kiosco.bat`.

---

### **Solución 2: Crear Acceso Directo Manual**

1. **Haz clic derecho en el escritorio** → `Nuevo` → `Acceso directo`.

2. **Pega este comando:**
   ```
   "C:\Program Files\Google\Chrome\Application\chrome.exe" --kiosk-printing --app=http://sistema-che.test/pos
   ```

3. **Nombre:** `POS ElchePizza`

4. **Haz doble clic** en el acceso directo.

---

### **Solución 3: Verificar que Chrome se Cerró Completamente**

1. **Abre el Administrador de Tareas** (`Ctrl + Shift + Esc`).

2. **Busca "Google Chrome"** en la lista.

3. **Si hay instancias**, selecciónalas y presiona **"Finalizar tarea"**.

4. **Vuelve a ejecutar** el script `Iniciar_POS_Kiosco.bat`.

---

## 📊 **VERIFICACIÓN DE DATOS EN EL TICKET**

Imprime un ticket de prueba y verifica:

| **Campo** | **Valor Esperado** | **¿Correcto?** |
|-----------|-------------------|----------------|
| RUC | 10447303766 | [ ] |
| Dirección | Res. Praderas de Pariachi mz G lt 9 ATE | [ ] |
| Teléfono | 952 208 570 | [ ] |
| Fecha | Fecha actual de Perú | [ ] |
| Hora | Hora actual en formato 12h (AM/PM) | [ ] |
| Pie de página | GRACIAS CHE ! | [ ] |

**Si todos los campos están correctos → ✅ Implementación exitosa.**

---

## 🚀 **PARA USAR EN PRODUCCIÓN**

### **Opción 1: Script de Producción**

1. **Haz doble clic** en:
   ```
   Iniciar_POS_Produccion.bat
   ```

2. **Verificará la conexión** a `pos.elchepizza.pe`.

3. **Abrirá el POS** en modo kiosco con la URL de producción.

---

### **Opción 2: Acceso Directo de Producción**

1. **Crea un acceso directo** con:
   ```
   "C:\Program Files\Google\Chrome\Application\chrome.exe" --kiosk-printing --app=https://pos.elchepizza.pe/pos
   ```

2. **Colócalo en el escritorio** de la PC del POS.

3. **Instrucciones para el personal:**
   - Encender PC.
   - Encender impresora térmica.
   - Doble clic en "POS ElchePizza".
   - Iniciar sesión.
   - ¡Listo para vender!

---

## 📞 **¿NECESITAS AYUDA?**

### **Revisa los Logs:**

1. **Logs de Laravel:**
   ```
   c:\laragon\www\sistema-che\storage\logs\laravel.log
   ```

2. **Consola de Chrome:**
   - Presiona `F12`
   - Pestaña "Console"
   - Busca: `✅ Ticket enviado a impresora`

---

### **Documentación Completa:**

- **Guía de Configuración:** `GUIA_IMPRESION_SILENCIOSA_KIOSCO.md`
- **Resumen Técnico:** `RESUMEN_IMPRESION_SILENCIOSA.md`
- **Sistema de Impresión:** `SISTEMA_IMPRESION_TICKETS.md`

---

## ✅ **CHECKLIST FINAL**

Marca cuando hayas completado:

- [ ] Ejecuté `Iniciar_POS_Kiosco.bat`
- [ ] Chrome abrió en modo aplicación
- [ ] Realicé una venta de prueba
- [ ] Presioné "CUENTA"
- [ ] La impresora imprimió automáticamente
- [ ] La ventana se cerró sola
- [ ] Los datos del ticket son correctos
- [ ] La hora está en zona horaria de Perú
- [ ] El pie de página dice "GRACIAS CHE !"

**Si marcaste todos → 🎉 ¡IMPLEMENTACIÓN EXITOSA!**

---

**Fecha:** 09/02/2026  
**Versión:** 1.0 - Impresión Silenciosa  
**Estado:** ✅ LISTO PARA PRODUCCIÓN

