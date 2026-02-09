#!/bin/bash

# Script para solucionar las imágenes en producción
# Ejecutar en el servidor: bash fix-images-production.sh

echo "🔧 Solucionando imágenes en producción..."
echo ""

# 1. Crear storage link
echo "📁 Paso 1/4: Creando storage link..."
php artisan storage:link
echo "✅ Storage link creado"
echo ""

# 2. Configurar permisos
echo "🔐 Paso 2/4: Configurando permisos..."
chmod -R 775 storage
chmod -R 775 bootstrap/cache
echo "✅ Permisos configurados"
echo ""

# 3. Cambiar propietario (ajusta www-data según tu servidor)
echo "👤 Paso 3/4: Cambiando propietario..."
# Descomenta la línea que corresponda a tu servidor:
# chown -R www-data:www-data storage bootstrap/cache  # Ubuntu/Debian con Apache
# chown -R nginx:nginx storage bootstrap/cache         # CentOS/RedHat con Nginx
# chown -R apache:apache storage bootstrap/cache       # CentOS/RedHat con Apache
echo "⚠️  Ajusta manualmente el propietario según tu servidor"
echo ""

# 4. Limpiar cachés
echo "🧹 Paso 4/4: Limpiando cachés..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✅ Cachés limpiados"
echo ""

# Verificación
echo "🔍 Verificando estructura..."
if [ -L "public/storage" ]; then
    echo "✅ Storage link existe"
else
    echo "❌ Storage link NO existe"
fi
echo ""

echo "✅ ¡Proceso completado!"
echo ""
echo "🎯 Próximos pasos:"
echo "1. Verifica que las imágenes estén en: storage/app/public/"
echo "2. Prueba una imagen: https://pos.elchepizza.pe/storage/nombre-imagen.png"
echo "3. Recarga el POS: https://pos.elchepizza.pe/pos (Ctrl+F5)"
echo ""
echo "📚 Más info: SOLUCION_IMAGENES_NO_SE_VEN.md"

