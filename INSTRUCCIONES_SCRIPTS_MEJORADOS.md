# 🚀 INSTRUCCIONES - SCRIPTS MEJORADOS v2.0
## Sistema de Impresión Silenciosa - POS ElchePizza

---

## ✅ **SCRIPTS ACTUALIZADOS**

Se han creado/mejorado los siguientes scripts:

| **Script** | **Propósito** | **Cuándo Usar** |
|------------|---------------|-----------------|
| `Iniciar_POS_Kiosco.bat` | ⭐ **PRINCIPAL** - Versión mejorada con limpieza agresiva | Usar PRIMERO (Local) |
| `Iniciar_POS_Kiosco_ALTERNATIVO.bat` | **PLAN B** - Usa perfil dedicado de Chrome | Si el principal no funciona |
| `Iniciar_POS_Produccion.bat` | **PRODUCCIÓN** - Versión mejorada para servidor real | Solo en PC del negocio |
| `Diagnostico_Impresion.bat` | **DIAGNÓSTICO** - Verifica configuración | Para resolver problemas |

---

## 🎯 **MEJORAS IMPLEMENTADAS EN v2.0**

### **1. Limpieza Agresiva de Chrome**
- ✅ Mata procesos 3 veces (en lugar de 1)
- ✅ Incluye procesos de GPU y procesos hijo
- ✅ Espera 3 segundos (en lugar de 2) para cierre completo
- ✅ Limpia archivo de estado que podría bloquear banderas

### **2. Banderas Adicionales de Chrome**
```
--kiosk-printing          (Ya estaba - CLAVE para impresión sin diálogo)
--disable-print-preview   (Ya estaba)
--app=URL                 (Ya estaba)
--no-first-run            (NUEVO - Evita diálogo de primera vez)
--no-default-browser-check (NUEVO - No pregunta navegador predeterminado)
--disable-popup-blocking  (NUEVO - Permite ventana del ticket)
--disable-infobars        (NUEVO - Sin barras de información)
--disable-notifications   (NUEVO - Sin notificaciones)
--silent-launch           (NUEVO - Inicio silencioso)
```

### **3. Mensajes de Diagnóstico**
- ✅ Feedback detallado en cada paso
- ✅ Instrucciones si algo falla
- ✅ Consejos de solución de problemas

---

## 📋 **INSTRUCCIONES DE USO - PASO A PASO**

### **PASO 1: Ejecutar Diagnóstico (Opcional pero Recomendado)**

1. **Haz doble clic en:** `Diagnostico_Impresion.bat`

2. **Revisa el reporte:**
   - ¿Chrome está cerrado? ✅
   - ¿POS58 Printer aparece en la lista? ✅
   - ¿POS58 es la predeterminada? ✅
   - ¿Chrome está instalado correctamente? ✅
   - ¿sistema-che.test es accesible? ✅

3. **Sigue las recomendaciones** si hay problemas.

---

### **PASO 2: Probar el Script Principal Mejorado**

1. **Cierra TODAS las ventanas de Chrome**
   - Incluyendo las que estén en segundo plano
   - Usa `Ctrl + Shift + Esc` y finaliza todos los procesos "Google Chrome"

2. **Haz doble clic en:** `Iniciar_POS_Kiosco.bat`

3. **Verás la secuencia:**
   ```
   [1/5] Cerrando Chrome (primera pasada)...
   [2/5] Verificando procesos residuales...
   [3/5] Verificando impresora termica...
   [4/5] Limpiando cache de preferencias...
   [5/5] Iniciando Chrome en Modo Kiosco Avanzado...
   ```

4. **Chrome se abrirá en modo aplicación** (sin barra de direcciones)

5. **Realiza una venta de prueba:**
   - Agrega productos
   - Selecciona Mesa / Delivery / Para Llevar
   - Selecciona método de pago
   - **Presiona "CUENTA"**

6. **RESULTADO ESPERADO:**

   **✅ CASO A: ¡FUNCIONA!**
   - La ventana del ticket se abre por 1-2 segundos
   - La impresora empieza a imprimir **SIN DIÁLOGO**
   - La ventana se cierra sola
   - **¡Éxito! Ya está configurado correctamente**

   **⚠️ CASO B: Aún aparece el diálogo**
   - Aparece la ventana de impresión de Windows
   - **VE AL PASO 3** (Script Alternativo)

---

### **PASO 3: Probar el Script Alternativo (Si el principal no funcionó)**

1. **Cierra Chrome completamente** de nuevo

2. **Haz doble clic en:** `Iniciar_POS_Kiosco_ALTERNATIVO.bat`

3. **Este script:**
   - Crea un perfil de Chrome dedicado solo para el POS
   - Guarda las preferencias de impresión separadamente
   - No afecta tu Chrome personal

4. **Primera vez usando este script:**
   - Aparecerá el diálogo de impresión (NORMAL en la primera vez)
   - Selecciona **POS58 Printer**
   - **IMPORTANTE:** Marca la casilla "Permitir que la aplicación cambie las preferencias de impresión"
   - Haz clic en "Imprimir"

5. **Segunda vez en adelante:**
   - Debería imprimir **SIN DIÁLOGO**
   - Chrome recordará la configuración en el perfil dedicado

---

### **PASO 4: Configurar Chrome Manualmente (Si aún no funciona)**

Si ninguno de los scripts funciona, necesitas configurar Chrome manualmente:

1. **Abre Chrome NORMALMENTE** (sin los scripts)

2. **Ve a:**
   ```
   chrome://settings/printing
   ```

3. **En "Impresora predeterminada":**
   - Selecciona: **POS58 Printer**

4. **Haz una impresión de prueba:**
   - Abre cualquier página web
   - Presiona `Ctrl + P`
   - Selecciona POS58 Printer
   - Haz clic en "Imprimir"
   - **Asegúrate de que imprima correctamente**

5. **Configura POS58 como predeterminada en Windows:**
   - Presiona `Win + R`
   - Escribe: `control printers`
   - Presiona Enter
   - Clic derecho en **POS58 Printer**
   - Selecciona **"Establecer como impresora predeterminada"**
   - Debería tener un ✅ verde

6. **Cierra Chrome completamente**

7. **Vuelve a ejecutar:** `Iniciar_POS_Kiosco.bat`

---

## 🔧 **SOLUCIÓN DE PROBLEMAS ESPECÍFICOS**

### **Problema 1: "Chrome sigue abierto en segundo plano"**

**Síntomas:**
- Los scripts no funcionan
- Chrome ignora las banderas
- La impresión sigue mostrando diálogo

**Solución:**
```
1. Abrir Administrador de Tareas (Ctrl + Shift + Esc)
2. Buscar TODOS los procesos "Google Chrome"
3. Seleccionar cada uno y hacer clic en "Finalizar tarea"
4. Asegurarse de que NO quede ningún proceso
5. Volver a ejecutar el script
```

---

### **Problema 2: "POS58 Printer no aparece en la lista"**

**Síntomas:**
- El diálogo de impresión no muestra POS58
- Sale error de impresora no encontrada

**Solución:**
```
1. Verificar que la impresora esté:
   - Encendida
   - Conectada por USB
   - Con papel

2. Instalar/reinstalar el driver:
   - Buscar "Driver POS58" o "Driver impresora térmica 58mm"
   - Instalar desde el CD o descargar del fabricante
   - Reiniciar el PC

3. Verificar en Windows:
   - Win + R → "control printers"
   - Debería aparecer "POS58 Printer" o similar
```

---

### **Problema 3: "El ticket sale cortado o con caracteres raros"**

**Síntomas:**
- El ticket imprime pero sale mal formateado
- Caracteres extraños
- Texto cortado

**Solución:**
```
1. Verificar el driver de la impresora
2. En el diálogo de impresión, verificar:
   - Tamaño de papel: 58mm o "Receipt"
   - Orientación: Vertical (Portrait)
   - Márgenes: 0 o mínimos

3. Si sigue fallando, ir a:
   chrome://settings/printing
   Y restablecer configuraciones de impresión
```

---

### **Problema 4: "La ventana del ticket no se cierra"**

**Síntomas:**
- El ticket imprime (o muestra el diálogo)
- La ventana se queda abierta

**Solución:**
```
1. Verificar que JavaScript esté habilitado en Chrome
2. Revisar si hay bloqueadores de ventanas emergentes
3. En la consola de Chrome (F12), buscar errores
```

---

## 📊 **COMPARACIÓN DE LOS 3 SCRIPTS**

| **Característica** | **Principal** | **Alternativo** | **Diagnóstico** |
|--------------------|---------------|-----------------|-----------------|
| Limpieza de Chrome | ✅✅✅ Muy agresiva | ✅✅ Normal | ❌ No aplica |
| Perfil dedicado | ❌ No | ✅ Sí | ❌ No aplica |
| Banderas mejoradas | ✅ 9 banderas | ✅ 10 banderas | ❌ No aplica |
| Primera vez | Puede funcionar | Requiere config | ❌ No aplica |
| Después de config | ✅ Impresión directa | ✅ Impresión directa | ❌ No aplica |
| Verifica problemas | ❌ No | ❌ No | ✅✅✅ Sí |

---

## 🎓 **EXPLICACIÓN TÉCNICA**

### **¿Por qué aparece el diálogo de impresión?**

**Razón 1: Chrome está abierto**
- Si Chrome ya está corriendo, ignora las banderas `--kiosk-printing`
- Necesita iniciarse con las banderas desde el principio

**Razón 2: No hay impresora predeterminada**
- Chrome no sabe qué impresora usar
- Muestra el diálogo para que elijas

**Razón 3: Política de seguridad**
- Los navegadores bloquean `window.print()` automático por seguridad
- `--kiosk-printing` desactiva esta protección

**Razón 4: Primera vez**
- Chrome necesita "aprender" la preferencia del usuario
- Después de la primera impresión manual, la recuerda

---

### **¿Por qué el script alternativo funciona mejor a veces?**

- Usa un **perfil separado** solo para el POS
- Este perfil guarda las configuraciones independientemente
- No hay conflicto con tu Chrome personal
- Las preferencias de impresión persisten entre sesiones

---

## 📞 **CHECKLIST FINAL**

Marca cuando hayas completado cada paso:

- [ ] Ejecuté `Diagnostico_Impresion.bat` y revisé el reporte
- [ ] POS58 Printer aparece en la lista de impresoras de Windows
- [ ] POS58 Printer está configurada como predeterminada
- [ ] Cerré TODAS las instancias de Chrome
- [ ] Ejecuté `Iniciar_POS_Kiosco.bat`
- [ ] Chrome abrió en modo aplicación (sin barra de direcciones)
- [ ] Realicé una venta de prueba
- [ ] Presioné "CUENTA"
- [ ] Resultado:
  - [ ] ✅ FUNCIONA: Imprimió sin diálogo
  - [ ] ⚠️ NO FUNCIONA: Apareció el diálogo

---

## 🚀 **PRÓXIMOS PASOS SEGÚN RESULTADO**

### **SI FUNCIONA ✅:**
1. Crea un acceso directo en el escritorio al script que funcionó
2. Úsalo siempre para abrir el POS
3. Para producción, usa `Iniciar_POS_Produccion.bat`

### **SI NO FUNCIONA ⚠️:**
1. Intenta el script alternativo
2. Configura Chrome manualmente (Paso 4)
3. Ejecuta el diagnóstico y envíame el reporte
4. Revisa los logs de Chrome (F12 → Console)

---

**Fecha:** 09/02/2026  
**Versión:** 2.0 - Scripts Mejorados  
**Estado:** ✅ LISTO PARA PROBAR

---

**¡GRACIAS CHE! 🍕**

