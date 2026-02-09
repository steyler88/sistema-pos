@echo off
REM ========================================
REM  DIAGNOSTICO DE IMPRESION - POS ElchePizza
REM  Verifica configuracion de Chrome e impresoras
REM ========================================

echo.
echo ========================================
echo   DIAGNOSTICO DE IMPRESION
echo   POS ElchePizza
echo ========================================
echo.

REM ========================================
REM VERIFICACION 1: Procesos de Chrome
REM ========================================
echo [1/5] Verificando procesos de Chrome activos...
echo.

tasklist /FI "IMAGENAME eq chrome.exe" 2>nul | find /I /N "chrome.exe">nul
if "%ERRORLEVEL%"=="0" (
    echo [!] ATENCION: Chrome esta ejecutandose
    echo.
    echo Procesos encontrados:
    tasklist /FI "IMAGENAME eq chrome.exe"
    echo.
    echo SOLUCION: Cierra TODAS las ventanas de Chrome
    echo           o ejecuta: taskkill /F /IM chrome.exe
    echo.
) else (
    echo [OK] Chrome NO esta ejecutandose
    echo.
)

timeout /t 2 /nobreak >nul

REM ========================================
REM VERIFICACION 2: Impresoras instaladas
REM ========================================
echo [2/5] Verificando impresoras instaladas...
echo.

wmic printer get name,default,drivername 2>nul
if "%ERRORLEVEL%"=="0" (
    echo.
    echo [?] Verifica que "POS58 Printer" aparezca en la lista
    echo [?] Verifica que tenga "TRUE" en Default si debe ser predeterminada
    echo.
) else (
    echo [!] No se pudieron listar las impresoras
    echo.
)

timeout /t 3 /nobreak >nul

REM ========================================
REM VERIFICACION 3: Ubicacion de Chrome
REM ========================================
echo [3/5] Verificando instalacion de Chrome...
echo.

if exist "C:\Program Files\Google\Chrome\Application\chrome.exe" (
    echo [OK] Chrome encontrado en: C:\Program Files\Google\Chrome\Application\
    for %%I in ("C:\Program Files\Google\Chrome\Application\chrome.exe") do echo     Version: %%~tI
    echo.
) else (
    echo [!] Chrome NO encontrado en la ubicacion predeterminada
    echo     Ruta esperada: C:\Program Files\Google\Chrome\Application\chrome.exe
    echo.
    echo SOLUCION: Instala Chrome o actualiza la ruta en los scripts .bat
    echo.
)

timeout /t 2 /nobreak >nul

REM ========================================
REM VERIFICACION 4: Perfil de usuario de Chrome
REM ========================================
echo [4/5] Verificando perfiles de Chrome...
echo.

set USER_DATA=%LOCALAPPDATA%\Google\Chrome\User Data

if exist "%USER_DATA%" (
    echo [OK] Carpeta de perfiles encontrada:
    echo     %USER_DATA%
    echo.
    
    if exist "%USER_DATA%\Default\Preferences" (
        echo [OK] Perfil predeterminado existe
    ) else (
        echo [!] Perfil predeterminado no encontrado
    )
    echo.
    
    if exist "%USER_DATA%\POS-ElchePizza" (
        echo [OK] Perfil dedicado POS-ElchePizza encontrado
        echo     (Para usar con Iniciar_POS_Kiosco_ALTERNATIVO.bat)
    ) else (
        echo [INFO] Perfil dedicado POS-ElchePizza NO existe aun
        echo       Se creara al ejecutar Iniciar_POS_Kiosco_ALTERNATIVO.bat
    )
    echo.
) else (
    echo [!] Carpeta de perfiles de Chrome no encontrada
    echo.
)

timeout /t 2 /nobreak >nul

REM ========================================
REM VERIFICACION 5: Conectividad al POS
REM ========================================
echo [5/5] Verificando acceso al sistema POS...
echo.

ping -n 1 sistema-che.test >nul 2>&1
if "%ERRORLEVEL%"=="0" (
    echo [OK] sistema-che.test es accesible
) else (
    echo [!] No se puede acceder a sistema-che.test
    echo.
    echo POSIBLES CAUSAS:
    echo - Laragon no esta iniciado
    echo - El archivo hosts no esta configurado
    echo - Apache/MySQL no estan corriendo
    echo.
)

echo.

REM ========================================
REM RESUMEN Y RECOMENDACIONES
REM ========================================
echo.
echo ========================================
echo   RESUMEN DEL DIAGNOSTICO
echo ========================================
echo.
echo PASOS SIGUIENTES:
echo.
echo 1. Si Chrome esta abierto:
echo    - Cierralo completamente desde el Administrador de tareas
echo.
echo 2. Si POS58 Printer NO aparece en la lista:
echo    - Instala el driver de la impresora termica
echo    - Conectala por USB y enciendela
echo.
echo 3. Si POS58 NO es la predeterminada:
echo    - Abre: Panel de control ^> Dispositivos e impresoras
echo    - Clic derecho en POS58 Printer
echo    - "Establecer como impresora predeterminada"
echo.
echo 4. Para configurar Chrome:
echo    - Abre Chrome normalmente
echo    - Ve a: chrome://settings/printing
echo    - Selecciona POS58 Printer como predeterminada
echo.
echo 5. Prueba los scripts en este orden:
echo    A) Iniciar_POS_Kiosco.bat (version mejorada)
echo    B) Iniciar_POS_Kiosco_ALTERNATIVO.bat (si A no funciona)
echo.
echo ========================================
echo.
echo Presiona cualquier tecla para cerrar...
pause >nul

