<?php
/**
 * Script para crear el storage link cuando exec() está deshabilitado
 * Subir este archivo a la raíz del proyecto y ejecutar desde el navegador:
 * https://pos.elchepizza.pe/create-storage-link.php
 */

// Configuración
$targetDir = __DIR__ . '/storage/app/public';
$linkDir = __DIR__ . '/public/storage';

echo "<h2>🔗 Creador de Storage Link</h2>";
echo "<p>Creando enlace simbólico...</p>";

// Verificar si el directorio target existe
if (!is_dir($targetDir)) {
    echo "<p style='color: red;'>❌ Error: El directorio storage/app/public no existe.</p>";
    exit;
}

// Eliminar enlace antiguo si existe
if (file_exists($linkDir)) {
    if (is_link($linkDir)) {
        unlink($linkDir);
        echo "<p style='color: orange;'>⚠️ Enlace anterior eliminado.</p>";
    } else {
        echo "<p style='color: red;'>❌ Error: 'public/storage' existe pero no es un enlace simbólico.</p>";
        echo "<p>Por favor, elimínalo manualmente y ejecuta este script de nuevo.</p>";
        exit;
    }
}

// Crear el enlace simbólico
if (symlink($targetDir, $linkDir)) {
    echo "<p style='color: green;'>✅ ¡Enlace creado exitosamente!</p>";
    echo "<p><strong>De:</strong> public/storage</p>";
    echo "<p><strong>A:</strong> storage/app/public</p>";
    echo "<hr>";
    echo "<h3>🧪 Pruebas</h3>";
    
    // Listar archivos en storage/app/public
    $files = glob($targetDir . '/*');
    if (!empty($files)) {
        echo "<p>Archivos encontrados en storage/app/public:</p>";
        echo "<ul>";
        foreach ($files as $file) {
            $filename = basename($file);
            $publicUrl = '/storage/' . $filename;
            echo "<li><a href='{$publicUrl}' target='_blank'>{$filename}</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<p style='color: orange;'>⚠️ No hay archivos en storage/app/public</p>";
    }
    
    echo "<hr>";
    echo "<h3>✅ Próximos pasos:</h3>";
    echo "<ol>";
    echo "<li>Verifica que las imágenes están en <code>storage/app/public/</code></li>";
    echo "<li>Recarga tu POS: <a href='/pos'>https://pos.elchepizza.pe/pos</a></li>";
    echo "<li><strong>⚠️ IMPORTANTE: Elimina este archivo (create-storage-link.php) por seguridad</strong></li>";
    echo "</ol>";
} else {
    echo "<p style='color: red;'>❌ Error: No se pudo crear el enlace simbólico.</p>";
    echo "<p>Posibles causas:</p>";
    echo "<ul>";
    echo "<li>Permisos insuficientes</li>";
    echo "<li>La función symlink() está deshabilitada</li>";
    echo "<li>El sistema de archivos no soporta enlaces simbólicos</li>";
    echo "</ul>";
    echo "<p><strong>Solución alternativa:</strong> Contacta a tu proveedor de hosting.</p>";
}

// Información adicional
echo "<hr>";
echo "<h3>ℹ️ Información del sistema:</h3>";
echo "<ul>";
echo "<li><strong>PHP Version:</strong> " . PHP_VERSION . "</li>";
echo "<li><strong>Directorio actual:</strong> " . __DIR__ . "</li>";
echo "<li><strong>symlink() habilitado:</strong> " . (function_exists('symlink') ? '✅ Sí' : '❌ No') . "</li>";
echo "<li><strong>exec() habilitado:</strong> " . (function_exists('exec') ? '✅ Sí' : '❌ No') . "</li>";
echo "</ul>";
?>

