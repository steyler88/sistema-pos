<style>
/* ============================================
   LAYOUT PRINCIPAL: 3 COLUMNAS (15-60-25)
   ============================================ */

/* Variables CSS */
:root {
    --sidebar-width: 250px;
    --topbar-height: 60px;
    --primary-orange: #f97316;
    --primary-orange-dark: #ea580c;
}

/* Contenedor principal con layout de 3 columnas */
body {
    overflow-x: hidden; /* Solo ocultar scroll horizontal */
    overflow-y: auto; /* Permitir scroll vertical */
}

.fi-layout {
    display: flex;
    min-height: 100vh;
    width: 100%;
}

/* COLUMNA 1: SIDEBAR IZQUIERDO - Ancho fijo */
.fi-sidebar-nav {
    width: var(--sidebar-width) !important;
    min-width: var(--sidebar-width) !important;
    max-width: var(--sidebar-width) !important;
    height: 100vh !important;
    overflow-y: auto !important;
    background: linear-gradient(180deg, #1f2937 0%, #111827 100%) !important;
    border-right: 1px solid #374151 !important;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1) !important;
}

/* COLUMNA 2: CONTENIDO CENTRAL - Ocupa el resto del espacio (85%) */
.fi-main {
    flex: 1 !important;
    min-height: 100vh !important;
    overflow-y: auto !important;
    background: #f9fafb !important;
    padding: 1.5rem !important;
}

/* Sin padding para página del POS */
.fi-resource-create-record-page .fi-main,
.fi-resource-create-record-page .fi-page {
    padding: 0 !important;
    margin: 0 !important;
    overflow-y: auto !important; /* Permitir scroll vertical */
}

.dark .fi-main {
    background: #0f172a !important;
}

/* Topbar con gradiente naranja */
.fi-topbar {
    background: linear-gradient(90deg, var(--primary-orange) 0%, var(--primary-orange-dark) 100%) !important;
    border-bottom: 2px solid var(--primary-orange-dark) !important;
    box-shadow: 0 2px 10px rgba(249, 115, 22, 0.2) !important;
    min-height: var(--topbar-height) !important;
}

/* Ajustar contenido para altura completa */
.fi-simple-page,
.fi-page {
    min-height: calc(100vh - var(--topbar-height)) !important;
}

/* ============================================
   SCROLL PERSONALIZADO
   ============================================ */
.fi-sidebar-nav::-webkit-scrollbar,
.fi-main::-webkit-scrollbar {
    width: 8px;
}

.fi-sidebar-nav::-webkit-scrollbar-track {
    background: #1f2937;
}

.fi-sidebar-nav::-webkit-scrollbar-thumb {
    background: #4b5563;
    border-radius: 4px;
}

.fi-sidebar-nav::-webkit-scrollbar-thumb:hover {
    background: #6b7280;
}

.fi-main::-webkit-scrollbar-track {
    background: #f3f4f6;
}

.fi-main::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 4px;
}

.fi-main::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
}

/* ============================================
   MEJORAS VISUALES
   ============================================ */

/* Cards y secciones */
.fi-section {
    background: white !important;
    border-radius: 0.75rem !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
    margin-bottom: 1.5rem !important;
}

.dark .fi-section {
    background: #1e293b !important;
}

/* Botones */
.fi-btn-primary {
    background: var(--primary-orange) !important;
    border-color: var(--primary-orange) !important;
}

.fi-btn-primary:hover {
    background: var(--primary-orange-dark) !important;
    border-color: var(--primary-orange-dark) !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Tablas */
.fi-ta {
    border-radius: 0.5rem !important;
    overflow: hidden !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
}

/* Items del sidebar */
.fi-sidebar-item {
    transition: all 0.2s ease !important;
}

.fi-sidebar-item:hover {
    background: rgba(249, 115, 22, 0.1) !important;
}

.fi-sidebar-item-active {
    background: rgba(249, 115, 22, 0.2) !important;
    border-left: 3px solid var(--primary-orange) !important;
}

/* ============================================
   RESPONSIVE
   ============================================ */

/* Tablet (768px - 1024px) */
@media (max-width: 1024px) {
    .fi-sidebar-nav {
        width: 20% !important;
        min-width: 200px !important;
    }
}

/* Mobile (< 768px) */
@media (max-width: 768px) {
    .fi-layout {
        flex-direction: column !important;
    }
    
    .fi-sidebar-nav {
        width: 100% !important;
        height: auto !important;
        position: relative !important;
        min-height: 60px !important;
    }
    
    .fi-main {
        width: 100% !important;
        min-height: calc(100vh - 60px) !important;
    }
}

/* ============================================
   ANIMACIONES
   ============================================ */
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-10px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.fi-sidebar-item {
    animation: slideIn 0.3s ease-out;
}

/* Transiciones suaves */
* {
    transition: background-color 0.2s ease, color 0.2s ease, border-color 0.2s ease;
}
</style>

