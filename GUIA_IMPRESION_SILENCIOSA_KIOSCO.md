# 🖨️ GUÍA DE IMPRESIÓN SILENCIOSA (MODO KIOSCO)
## Sistema POS - ElchePizza | Impresión Directa sin Diálogo

---

## 📌 **OBJETIVO**
Configurar Google Chrome en "Modo Kiosco" para que el POS imprima tickets **automáticamente** en la impresora térmica **sin pedir confirmación** al usuario.

---

## 🔧 **MÉTODO 1: Acceso Directo de Chrome con Banderas (RECOMENDADO)**

### **Paso 1: Crear Acceso Directo Personalizado**

1. **Localiza el ejecutable de Chrome:**
   ```
   C:\Program Files\Google\Chrome\Application\chrome.exe
   ```
   *(Si tienes Chrome en otra ubicación, ajusta la ruta)*

2. **Haz clic derecho en tu escritorio** → `Nuevo` → `Acceso directo`

3. **Pega el siguiente comando completo:**
   ```
   "C:\Program Files\Google\Chrome\Application\chrome.exe" --kiosk-printing --app=http://sistema-che.test/pos
   ```

4. **Ajusta la URL según tu entorno:**
   - **Local:** `http://sistema-che.test/pos`
   - **Producción:** `https://pos.elchepizza.pe/pos`

5. **Nombra el acceso directo:**
   ```
   POS ElchePizza - Impresión Automática
   ```

6. **Haz clic en "Finalizar"**

---

### **Paso 2: Configurar Impresora Predeterminada**

Para que la impresión sea **totalmente automática**, Chrome debe saber qué impresora usar:

1. **Abre Chrome normalmente** (no el acceso directo aún).
2. **Ve a:** `chrome://settings/printing`
3. **En "Impresora predeterminada"**, selecciona tu impresora térmica **58E**.
4. **Guarda los cambios**.

---

### **Paso 3: Probar el Acceso Directo**

1. **Cierra TODAS las ventanas de Chrome**.
2. **Haz doble clic en el acceso directo creado**.
3. **Ve al POS y haz una venta de prueba**.
4. **Presiona el botón "CUENTA"**.

**✅ Resultado esperado:**
- La ventana del ticket se abre por 1-2 segundos.
- La impresora comienza a imprimir **sin diálogo**.
- La ventana se cierra automáticamente.

---

## 🔧 **MÉTODO 2: Configuración Avanzada con Políticas de Chrome**

Si el Método 1 no funciona (por permisos o red corporativa), puedes forzar la impresión silenciosa mediante políticas de grupo:

### **Paso 1: Habilitar Impresión Silenciosa en Políticas Locales**

1. **Abre el Editor del Registro de Windows:**
   - Presiona `Win + R`
   - Escribe: `regedit`
   - Presiona Enter

2. **Navega a:**
   ```
   HKEY_LOCAL_MACHINE\SOFTWARE\Policies\Google\Chrome
   ```
   *(Si la carpeta "Policies" o "Chrome" no existe, créala)*

3. **Crea una nueva clave (DWORD de 32 bits):**
   - Nombre: `PrintingEnabled`
   - Valor: `1`

4. **Crea otra clave:**
   - Nombre: `SilentPrintingAllowed`
   - Valor: `1`

5. **Reinicia Chrome completamente**.

---

## 🔧 **MÉTODO 3: Script BAT para Iniciar Chrome en Modo Kiosco**

Si prefieres un script `.bat` para iniciar el POS:

1. **Crea un archivo de texto** en el escritorio llamado `Iniciar_POS.bat`

2. **Pega este contenido:**
   ```batch
   @echo off
   echo ========================================
   echo  Iniciando POS ElchePizza en Modo Kiosco
   echo ========================================
   
   REM Cerrar todas las instancias de Chrome
   taskkill /F /IM chrome.exe >nul 2>&1
   
   REM Esperar 2 segundos
   timeout /t 2 /nobreak >nul
   
   REM Iniciar Chrome en modo kiosco con impresión automática
   start "" "C:\Program Files\Google\Chrome\Application\chrome.exe" ^
   --kiosk-printing ^
   --disable-print-preview ^
   --app=http://sistema-che.test/pos
   
   echo.
   echo ✅ POS Iniciado correctamente
   echo.
   pause
   ```

3. **Guarda el archivo** y haz doble clic para ejecutarlo.

---

## 🎯 **BANDERAS DE CHROME EXPLICADAS**

| **Bandera** | **Función** |
|-------------|-------------|
| `--kiosk-printing` | **Imprime sin mostrar el diálogo de impresión** (el más importante). |
| `--disable-print-preview` | Desactiva la vista previa de impresión (opcional pero recomendado). |
| `--app=URL` | Abre Chrome en modo aplicación (sin barra de direcciones ni pestañas). |
| `--start-fullscreen` | Inicia en pantalla completa (opcional, para kioscos reales). |
| `--incognito` | Modo incógnito (para no guardar historial en POS públicos). |

---

## ✅ **VERIFICACIÓN FINAL**

### **Checklist de Configuración Correcta:**

- [✅] La zona horaria en `config/app.php` está en `'America/Lima'`.
- [✅] El ticket muestra la hora correcta de Perú.
- [✅] Los datos de la empresa (RUC, dirección, teléfono) son correctos.
- [✅] La impresora térmica 58E está configurada como predeterminada en Windows.
- [✅] El acceso directo de Chrome usa la bandera `--kiosk-printing`.
- [✅] Al presionar "CUENTA", el ticket se imprime sin diálogo.

---

## 🚨 **SOLUCIÓN DE PROBLEMAS COMUNES**

### **Problema 1: Aún aparece el diálogo de impresión**
**Solución:**
- Cierra TODAS las ventanas de Chrome (incluso en segundo plano).
- Ejecuta `taskkill /F /IM chrome.exe` en CMD como administrador.
- Vuelve a abrir usando el acceso directo con `--kiosk-printing`.

### **Problema 2: La impresora no está seleccionada automáticamente**
**Solución:**
- Ve a `chrome://settings/printing`
- Selecciona tu impresora térmica como predeterminada.
- Imprime una página de prueba desde Windows para confirmar que funciona.

### **Problema 3: La hora sigue saliendo mal**
**Solución:**
```bash
# Limpia la caché de configuración de Laravel
php artisan config:clear
php artisan cache:clear
```

### **Problema 4: La ventana del ticket no se cierra**
**Solución:**
- Asegúrate de que JavaScript esté habilitado en Chrome.
- Verifica que no haya bloqueadores de ventanas emergentes activos.

---

## 📦 **CONFIGURACIÓN PARA PRODUCCIÓN (Hostinger)**

Si vas a subir esto a producción:

1. **Modifica el acceso directo** para usar la URL de producción:
   ```
   "C:\Program Files\Google\Chrome\Application\chrome.exe" --kiosk-printing --app=https://pos.elchepizza.pe/pos
   ```

2. **Verifica que el servidor tenga la zona horaria correcta:**
   ```bash
   # Conéctate por SSH a Hostinger
   ssh usuario@pos.elchepizza.pe
   
   # Verifica la zona horaria
   php -r "echo date_default_timezone_get();"
   
   # Debería mostrar: America/Lima
   ```

3. **Limpia el caché en producción:**
   ```bash
   php artisan config:cache
   php artisan cache:clear
   ```

---

## 🎓 **NOTAS TÉCNICAS**

- **Seguridad del Navegador:** Los navegadores modernos bloquean `window.print()` automático por seguridad. La bandera `--kiosk-printing` desactiva esta protección específicamente para entornos POS.

- **Compatibilidad:** Esta configuración funciona en:
  - ✅ Google Chrome (Recomendado)
  - ✅ Microsoft Edge (Chromium) - Usar `msedge.exe --kiosk-printing`
  - ❌ Firefox (No soporta `--kiosk-printing`, requiere extensiones)

- **Alternativa para Firefox:**
  Si usas Firefox, necesitarás la extensión **"Auto Print"** y configurar `about:config` → `print.always_print_silent` → `true`.

---

## 🏆 **RESULTADO FINAL**

**Flujo Optimizado:**
1. Usuario presiona **"CUENTA"** en el POS.
2. El sistema valida y guarda la orden.
3. Se abre una ventana emergente (invisible en modo kiosco).
4. La impresora térmica comienza a imprimir **inmediatamente**.
5. La ventana se cierra sola después de 1.5 segundos.
6. **¡Listo!** El usuario puede continuar con la siguiente venta.

---

## 📞 **SOPORTE**

Si después de seguir esta guía sigues teniendo problemas:

1. **Verifica los logs del navegador:**
   - Presiona `F12` en Chrome
   - Ve a la pestaña "Console"
   - Busca errores en rojo

2. **Verifica los logs de Laravel:**
   ```
   storage/logs/laravel.log
   ```

3. **Toma capturas de pantalla** de cualquier error y repórtalo.

---

**¡GRACIAS CHE! 🍕**

