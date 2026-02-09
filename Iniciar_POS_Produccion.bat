@echo off
REM ========================================
REM  POS ElchePizza - PRODUCCION MEJORADO
REM  https://pos.elchepizza.pe/pos
REM  Version: 2.0 - Impresion Forzada
REM ========================================

echo.
echo ========================================
echo   POS ElchePizza - PRODUCCION
echo   Version: 2.0 MEJORADA
echo ========================================
echo.

REM ========================================
REM PASO 1: LIMPIEZA AGRESIVA DE CHROME
REM ========================================
echo [1/6] Cerrando Chrome (primera pasada)...
taskkill /F /IM chrome.exe >nul 2>&1
timeout /t 1 /nobreak >nul

echo [2/6] Verificando procesos residuales...
taskkill /F /IM chrome.exe >nul 2>&1
taskkill /F /IM chrome.exe /T >nul 2>&1
taskkill /F /FI "IMAGENAME eq chrome.exe" >nul 2>&1

echo       Esperando cierre completo (3 segundos)...
timeout /t 3 /nobreak >nul

REM ========================================
REM PASO 2: VERIFICAR CONEXION A INTERNET
REM ========================================
echo [3/6] Verificando conexion a Internet...
ping -n 1 pos.elchepizza.pe >nul 2>&1
if errorlevel 1 (
    echo.
    echo [ERROR] No se puede conectar a pos.elchepizza.pe
    echo.
    echo Verifica:
    echo - Conexion a Internet activa
    echo - Firewall no bloquea la conexion
    echo - DNS funcionando correctamente
    echo.
    pause
    exit /b 1
)
echo       [OK] Conexion exitosa a pos.elchepizza.pe

timeout /t 1 /nobreak >nul

REM ========================================
REM PASO 3: VERIFICAR IMPRESORA
REM ========================================
echo [4/6] Verificando impresora termica...
echo       Asegurate de que POS58 Printer este:
echo       - Encendida
echo       - Conectada por USB
echo       - Con papel

timeout /t 2 /nobreak >nul

REM ========================================
REM PASO 4: LIMPIAR CACHE DE CHROME
REM ========================================
echo [5/6] Limpiando cache de preferencias...
del /Q "%LOCALAPPDATA%\Google\Chrome\User Data\Local State" >nul 2>&1
timeout /t 1 /nobreak >nul

REM ========================================
REM PASO 5: INICIAR CHROME EN PRODUCCION
REM ========================================
echo [6/6] Iniciando Chrome en Modo Kiosco (Produccion)...
echo.

REM Iniciar Chrome con todas las banderas mejoradas
start "" "C:\Program Files\Google\Chrome\Application\chrome.exe" ^
--kiosk-printing ^
--disable-print-preview ^
--app=https://pos.elchepizza.pe/pos ^
--no-first-run ^
--no-default-browser-check ^
--disable-popup-blocking ^
--disable-infobars ^
--disable-notifications ^
--silent-launch

timeout /t 2 /nobreak >nul

echo.
echo ========================================
echo   POS Iniciado en PRODUCCION
echo   Conectado a: pos.elchepizza.pe
echo ========================================
echo.
echo VERIFICACION:
echo - Si el dialogo de impresion aparece:
echo   1. Abre chrome://settings/printing
echo   2. Selecciona POS58 como predeterminada
echo   3. Haz una impresion de prueba manual
echo   4. Vuelve a ejecutar este script
echo.
echo - Configura POS58 como impresora predeterminada
echo   del sistema Windows para mejores resultados
echo.
echo Presiona cualquier tecla para cerrar...
pause >nul

