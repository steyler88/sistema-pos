# Dashboard del Sistema de Ventas

## Widgets Implementados

### 1. **SalesOverview** - Resumen de Ventas Diarias
Muestra estadísticas clave de ventas:
- 💰 **Ventas de Hoy**: Total de ventas completadas hoy con comparación porcentual vs ayer
- 📅 **Ventas de Ayer**: Total de ventas del día anterior
- 📊 **Ventas (7 días)**: Total acumulado de los últimos 7 días
- 🛒 **Ticket Promedio Hoy**: Valor promedio de cada orden del día

### 2. **StatsOverview** - Resumen Mensual
Widget existente que muestra:
- Ventas del mes
- Gastos del mes
- Ganancia neta (Ventas - Gastos)

### 3. **SalesComparisonChart** - Gráfico de Tendencia
Gráfico de líneas que muestra la evolución de ventas de los últimos 7 días, permitiendo visualizar tendencias y patrones.

### 4. **OrdersStatusWidget** - Órdenes por Estado
Gráfico circular (donut) que muestra la distribución de órdenes del día:
- 🟡 Pendientes
- 🟢 Completadas
- 🔴 Canceladas

### 5. **TopProductsWidget** - Productos Más Vendidos
Tabla con los 10 productos más vendidos en los últimos 7 días, mostrando:
- Nombre del producto
- Cantidad vendida
- Total de ventas generadas
- Precio unitario

### 6. **LatestOrdersWidget** - Últimas Órdenes
Tabla con las 10 órdenes más recientes, mostrando:
- Número de orden
- Cliente
- Total
- Estado
- Método de pago
- Fecha y hora

## Características Especiales

### Auto-actualización
Los widgets se actualizan automáticamente:
- **Estadísticas**: Cada 15 segundos
- **Gráficos**: Cada 30 segundos

Esto permite tener información en tiempo real sin recargar la página.

### Ordenamiento de Widgets
Los widgets están ordenados por importancia:
1. StatsOverview (métricas mensuales)
2. SalesOverview (métricas diarias)
3. SalesComparisonChart (tendencias)
4. OrdersStatusWidget (estado actual)
5. TopProductsWidget (análisis de productos)
6. LatestOrdersWidget (actividad reciente)

## Métricas Clave Incluidas

Basado en mejores prácticas de sistemas comerciales, el dashboard incluye:

✅ **Ventas totales** (día, semana, mes)  
✅ **Comparaciones temporales** (vs día anterior, porcentajes)  
✅ **Estado de órdenes** (pendientes, completadas, canceladas)  
✅ **Productos más vendidos** (top performers)  
✅ **Ticket promedio** (valor promedio de venta)  
✅ **Gastos y balance** (rentabilidad)  
✅ **Tendencias visuales** (gráficos de línea y circular)  
✅ **Actividad reciente** (últimas transacciones)  

## Personalización

Puedes ajustar:
- Número de filas en las tablas (actualmente 10 y 5 respectivamente)
- Período de análisis (actualmente 7 días)
- Frecuencia de actualización (polling interval)
- Colores y estilos de los gráficos

Para modificar el número de filas, edita los archivos:
- `TopProductsWidget.php`: Cambia `->limit(10)` en la línea 29
- `LatestOrdersWidget.php`: Cambia `->limit(10)` en la línea 20 y `->paginated([5, 10])` en la línea 68

