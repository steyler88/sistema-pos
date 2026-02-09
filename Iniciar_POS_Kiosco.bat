@echo off
REM ========================================
REM  POS ElchePizza - Modo Impresion Automatica MEJORADO
REM  Sistema de Ventas con Impresion Silenciosa
REM  Version: 2.0 - Impresion Forzada
REM ========================================

echo.
echo ========================================
echo   POS ElchePizza - Impresion Silenciosa
echo   Version: 2.0 MEJORADA
echo ========================================
echo.

REM ========================================
REM PASO 1: LIMPIEZA AGRESIVA DE CHROME
REM ========================================
echo [1/5] Cerrando Chrome (primera pasada)...
taskkill /F /IM chrome.exe >nul 2>&1

REM Esperar 1 segundo
timeout /t 1 /nobreak >nul

echo [2/5] Verificando procesos residuales...
taskkill /F /IM chrome.exe >nul 2>&1
taskkill /F /IM chrome.exe /T >nul 2>&1

REM Limpiar tambien procesos de GPU de Chrome
taskkill /F /FI "IMAGENAME eq chrome.exe" >nul 2>&1

REM Esperar 3 segundos para asegurar cierre completo
echo       Esperando cierre completo (3 segundos)...
timeout /t 3 /nobreak >nul

REM ========================================
REM PASO 2: VERIFICAR IMPRESORA
REM ========================================
echo [3/5] Verificando impresora termica...
echo       Asegurate de que POS58 Printer este:
echo       - Encendida
echo       - Conectada por USB
echo       - Con papel

timeout /t 2 /nobreak >nul

REM ========================================
REM PASO 3: LIMPIAR CACHE DE CHROME (opcional)
REM ========================================
echo [4/5] Limpiando cache de preferencias...
REM Eliminar archivo de estado que podria bloquear banderas
del /Q "%LOCALAPPDATA%\Google\Chrome\User Data\Local State" >nul 2>&1
timeout /t 1 /nobreak >nul

REM ========================================
REM PASO 4: INICIAR CHROME CON BANDERAS MEJORADAS
REM ========================================
echo [5/5] Iniciando Chrome en Modo Kiosco Avanzado...
echo.

REM Banderas explicadas:
REM --kiosk-printing: CLAVE - Imprime sin dialogo
REM --disable-print-preview: Desactiva vista previa
REM --app=URL: Modo aplicacion (sin UI del navegador)
REM --no-first-run: Evita dialogo de primera ejecucion
REM --no-default-browser-check: No pregunta si es navegador predeterminado
REM --disable-popup-blocking: Permite ventanas emergentes (para ticket)
REM --disable-infobars: Sin barras de informacion
REM --disable-notifications: Sin notificaciones
REM --silent-launch: Inicio silencioso

start "" "C:\Program Files\Google\Chrome\Application\chrome.exe" ^
--kiosk-printing ^
--disable-print-preview ^
--app=http://sistema-che.test/pos ^
--no-first-run ^
--no-default-browser-check ^
--disable-popup-blocking ^
--disable-infobars ^
--disable-notifications ^
--silent-launch

timeout /t 2 /nobreak >nul

echo.
echo ========================================
echo   Chrome Iniciado en Modo Kiosco
echo ========================================
echo.
echo VERIFICACION:
echo - Si el dialogo de impresion AUN aparece:
echo   1. Cierra Chrome completamente
echo   2. Ve a chrome://settings/printing
echo   3. Selecciona "POS58 Printer" como predeterminada
echo   4. Haz UNA impresion de prueba manual
echo   5. Vuelve a ejecutar este script
echo.
echo - Si la impresora no imprime:
echo   Verifica en Windows que POS58 este como
echo   impresora predeterminada del sistema
echo.
echo NOTA PRODUCCION:
echo Para usar en produccion, reemplaza:
echo   http://sistema-che.test/pos
echo por:
echo   https://pos.elchepizza.pe/pos
echo.
echo Presiona cualquier tecla para cerrar esta ventana...
pause >nul

