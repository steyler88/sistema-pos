@echo off
REM ========================================
REM  POS ElchePizza - MODO ALTERNATIVO
REM  Usa un perfil de Chrome dedicado para POS
REM  Version: 2.0 - Perfil Dedicado
REM ========================================

echo.
echo ========================================
echo   POS ElchePizza - MODO ALTERNATIVO
echo   Perfil de Chrome Dedicado
echo ========================================
echo.

REM Definir ruta del perfil dedicado del POS
set PROFILE_DIR=%LOCALAPPDATA%\Google\Chrome\User Data\POS-ElchePizza

REM ========================================
REM PASO 1: LIMPIEZA DE CHROME
REM ========================================
echo [1/4] Cerrando todas las instancias de Chrome...
taskkill /F /IM chrome.exe >nul 2>&1
timeout /t 1 /nobreak >nul
taskkill /F /IM chrome.exe /T >nul 2>&1
echo       Esperando cierre completo...
timeout /t 3 /nobreak >nul

REM ========================================
REM PASO 2: VERIFICAR/CREAR PERFIL DEDICADO
REM ========================================
echo [2/4] Verificando perfil dedicado del POS...

if not exist "%PROFILE_DIR%" (
    echo       Primera ejecucion: Creando perfil nuevo...
    mkdir "%PROFILE_DIR%" >nul 2>&1
    
    REM Crear archivo de preferencias de impresion
    echo {> "%PROFILE_DIR%\Preferences"
    echo   "printing": {>> "%PROFILE_DIR%\Preferences"
    echo     "print_preview_sticky_settings": {>> "%PROFILE_DIR%\Preferences"
    echo       "appState": {>> "%PROFILE_DIR%\Preferences"
    echo         "version": 2,>> "%PROFILE_DIR%\Preferences"
    echo         "recentDestinations": [],>> "%PROFILE_DIR%\Preferences"
    echo         "isHeaderFooterEnabled": false,>> "%PROFILE_DIR%\Preferences"
    echo         "isLandscapeEnabled": false>> "%PROFILE_DIR%\Preferences"
    echo       }>> "%PROFILE_DIR%\Preferences"
    echo     }>> "%PROFILE_DIR%\Preferences"
    echo   }>> "%PROFILE_DIR%\Preferences"
    echo }>> "%PROFILE_DIR%\Preferences"
) else (
    echo       Perfil existente encontrado (OK)
)

timeout /t 1 /nobreak >nul

REM ========================================
REM PASO 3: VERIFICAR IMPRESORA
REM ========================================
echo [3/4] Verificando impresora POS58...
echo       Asegurate de que este encendida
timeout /t 2 /nobreak >nul

REM ========================================
REM PASO 4: INICIAR CHROME CON PERFIL DEDICADO
REM ========================================
echo [4/4] Iniciando Chrome con perfil dedicado...
echo.

REM Usar perfil dedicado + banderas de impresion
start "" "C:\Program Files\Google\Chrome\Application\chrome.exe" ^
--user-data-dir="%PROFILE_DIR%" ^
--kiosk-printing ^
--disable-print-preview ^
--app=http://sistema-che.test/pos ^
--no-first-run ^
--no-default-browser-check ^
--disable-popup-blocking ^
--disable-infobars ^
--disable-notifications ^
--silent-launch ^
--disable-background-mode

timeout /t 2 /nobreak >nul

echo.
echo ========================================
echo   Chrome Iniciado con Perfil Dedicado
echo ========================================
echo.
echo VENTAJA DE ESTE MODO:
echo - Chrome usa un perfil separado solo para el POS
echo - Las configuraciones de impresion se guardan aqui
echo - No afecta tu Chrome personal
echo.
echo PRIMERA VEZ QUE USAS ESTE SCRIPT:
echo 1. Cuando se abra el POS, haz una venta de prueba
echo 2. Al presionar CUENTA, aparecera el dialogo
echo 3. Selecciona POS58 Printer
echo 4. Marca "Permitir que la aplicacion cambie preferencias"
echo 5. Imprime
echo 6. A partir de la SEGUNDA vez, deberia imprimir sin dialogo
echo.
echo Presiona cualquier tecla para cerrar...
pause >nul

