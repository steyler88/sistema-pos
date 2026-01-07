<x-filament-panels::page class="fi-resource-create-record-page p-0 m-0" :class="null">
    <style>
        /* Eliminar padding del contenedor principal */
        .fi-main-ctn {
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .fi-main {
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .fi-page {
            padding: 0 !important;
            margin: 0 !important;
            max-width: 100% !important;
        }
        
        /* Ocultar el header de la página */
        .fi-section-header,
        .fi-header,
        .fi-page-header {
            display: none !important;
        }
        
        /* El contenido debe ocupar todo el espacio */
        .fi-page-content {
            padding: 0 !important;
            margin: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }
    </style>
    
    @livewire('touch-p-o-s')
</x-filament-panels::page>
