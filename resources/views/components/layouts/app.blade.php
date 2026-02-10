<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TouchPOS - Sistema de Ventas</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="bg-gray-900">
    {{ $slot }}
    
    <!-- Livewire Scripts (incluye Alpine.js automáticamente en Livewire v3) -->
    @livewireScripts
</body>
</html>

