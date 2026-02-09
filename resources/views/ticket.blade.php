<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket #{{ $order->id }} - ElchePizza</title>
    <style>
        /* ===================================
           ESTILOS PARA IMPRESORA TÉRMICA 58mm
           Máximo 32 caracteres por línea
           =================================== */
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background: #fff;
            width: 58mm;
            margin: 0 auto;
            padding: 2mm;
        }
        
        /* Estilos de impresión */
        @media print {
            @page {
                size: 58mm auto;
                margin: 0;
            }
            
            body {
                width: 58mm;
                margin: 0;
                padding: 2mm;
            }
            
            /* Ocultar elementos del navegador */
            header, footer, nav, aside {
                display: none !important;
            }
        }
        
        /* Estilos del ticket */
        .ticket {
            width: 100%;
        }
        
        .header {
            text-align: center;
            margin-bottom: 8px;
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
        }
        
        .logo {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 4px;
        }
        
        .order-type {
            font-size: 16px;
            font-weight: bold;
            margin: 8px 0;
            padding: 4px 0;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }
        
        .info-line {
            font-size: 11px;
            margin: 2px 0;
        }
        
        .section {
            margin: 8px 0;
        }
        
        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        
        .divider-solid {
            border-top: 1px solid #000;
            margin: 6px 0;
        }
        
        .items-header {
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            margin-bottom: 4px;
        }
        
        .item-row {
            margin: 3px 0;
            font-size: 11px;
        }
        
        .totals {
            margin-top: 8px;
            border-top: 1px solid #000;
            padding-top: 6px;
        }
        
        .total-line {
            display: flex;
            justify-content: space-between;
            margin: 3px 0;
        }
        
        .total-final {
            font-size: 14px;
            font-weight: bold;
            margin-top: 6px;
            padding-top: 6px;
            border-top: 2px solid #000;
        }
        
        .footer {
            text-align: center;
            margin-top: 12px;
            padding-top: 8px;
            border-top: 1px dashed #000;
            font-size: 11px;
        }
        
        .bold {
            font-weight: bold;
        }
        
        .center {
            text-align: center;
        }
        
        .right {
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <!-- CABECERA -->
        <div class="header">
            <div class="logo">ELCHEPIZZA</div>
            <div class="info-line">RUC: 10447303766</div>
            <div class="info-line" style="word-wrap: break-word; white-space: normal;">Res. Praderas de Pariachi mz G lt 9 ATE</div>
            <div class="info-line">Tel: 952 208 570</div>
        </div>
        
        <!-- TIPO DE SERVICIO -->
        <div class="order-type center">
            @if($order->order_type === 'mesa')
                MESA @if($order->table_location) - {{ $order->table_location }} @endif
            @elseif($order->order_type === 'delivery')
                DELIVERY
            @elseif($order->order_type === 'para_llevar')
                PARA LLEVAR
            @else
                {{ strtoupper($order->order_type) }}
            @endif
        </div>
        
        <!-- INFORMACIÓN DE LA ORDEN -->
        <div class="section">
            <div class="info-line">Orden: #{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</div>
            <div class="info-line">Fecha: {{ $order->created_at->timezone('America/Lima')->format('d/m/Y h:i A') }}</div>
            <div class="info-line">Cliente: {{ Str::limit($order->customer_name, 20, '...') }}</div>
            <div class="info-line">Cajero: {{ Auth::user()->name ?? 'Sistema' }}</div>
        </div>
        
        <div class="divider-solid"></div>
        
        <!-- DETALLE DE PRODUCTOS -->
        <div class="section">
            <div class="items-header">
                CANT  PRODUCTO           TOTAL
            </div>
            
            @foreach($order->items as $item)
            <div class="item-row">
                <!-- Formato: Cant (4 chars) + Producto (16 chars) + Total (8 chars) = 28 chars -->
                {{ str_pad($item->quantity, 4, ' ', STR_PAD_LEFT) }}  {{ str_pad(Str::limit($item->product->name ?? 'Producto', 16, '...'), 16) }}  {{ str_pad(number_format($item->quantity * $item->unit_price, 2), 8, ' ', STR_PAD_LEFT) }}
            </div>
            @endforeach
        </div>
        
        <div class="divider"></div>
        
        <!-- TOTALES -->
        <div class="totals">
            @php
                $subtotal = $order->total_price;
                $igv = $subtotal * 0.18;
                $total = $subtotal;
            @endphp
            
            <div class="total-line">
                <span>Subtotal:</span>
                <span>S/ {{ number_format($subtotal - $igv, 2) }}</span>
            </div>
            
            <div class="total-line">
                <span>IGV (18%):</span>
                <span>S/ {{ number_format($igv, 2) }}</span>
            </div>
            
            <div class="total-final">
                <div class="total-line" style="font-size: 16px;">
                    <span>TOTAL A PAGAR:</span>
                    <span>S/ {{ number_format($total, 2) }}</span>
                </div>
            </div>
        </div>
        
        <div class="divider"></div>
        
        <!-- FORMA DE PAGO -->
        <div class="section center">
            <div class="info-line bold">
                PAGO: 
                @if($order->payment_method === 'cash')
                    EFECTIVO
                @elseif($order->payment_method === 'yape')
                    YAPE/PLIN
                @elseif($order->payment_method === 'card')
                    TARJETA
                @else
                    {{ strtoupper($order->payment_method) }}
                @endif
            </div>
        </div>
        
        <!-- PIE DE PÁGINA -->
        <div class="footer">
            <div>********************************</div>
            <div class="bold" style="margin: 8px 0; font-size: 14px;">GRACIAS CHE !</div>
            <div style="margin-top: 6px; font-size: 10px;">www.elchepizza.pe</div>
        </div>
    </div>
    
    <!-- Script de auto-impresión SILENCIOSA -->
    <script>
        window.onload = function() {
            // Esperar 300ms para que el contenido cargue completamente
            setTimeout(function() {
                // Disparar impresión inmediatamente
                window.print();
                
                // Cerrar ventana automáticamente después de imprimir
                window.onafterprint = function() {
                    console.log('✅ Ticket enviado a impresora');
                    window.close();
                };
                
                // Fallback: Cerrar después de 1.5 segundos aunque no imprima
                // (En modo kiosco con --kiosk-printing, se imprime sin diálogo)
                setTimeout(function() {
                    window.close();
                }, 1500);
            }, 300);
        };
        
        // Prevenir que el usuario cierre la ventana antes de tiempo
        window.onbeforeunload = null;
    </script>
</body>
</html>

