<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WooCommerceWebhookController extends Controller
{
    /**
     * Endpoint de prueba para ver qué envía WooCommerce
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function test(Request $request)
    {
        Log::info('TEST Webhook recibido', [
            'headers' => $request->headers->all(),
            'payload' => $request->all(),
            'raw_content' => $request->getContent()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Test webhook recibido correctamente',
            'received_data' => $request->all()
        ], 200);
    }

    /**
     * Endpoint para recibir webhooks de órdenes creadas en WooCommerce
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderCreated(Request $request)
    {
        try {
            // Log del payload recibido para debugging
            Log::info('Webhook WooCommerce recibido', ['payload' => $request->all()]);

            // Extraer datos de la orden de WooCommerce
            $wooOrder = $request->all();
            
            // Validación básica - solo requerir ID
            if (!isset($wooOrder['id'])) {
                Log::warning('Webhook sin ID de orden', ['payload' => $wooOrder]);
                return response()->json([
                    'success' => false,
                    'message' => 'Payload inválido: falta el ID de la orden'
                ], 400);
            }
            
            // Si no hay line_items, crear array vacío
            if (!isset($wooOrder['line_items'])) {
                Log::info('Orden sin items, creando array vacío', ['order_id' => $wooOrder['id']]);
                $wooOrder['line_items'] = [];
            }

            // Iniciar transacción para garantizar integridad
            DB::beginTransaction();

            try {
                // PASO 1: Manejo del Cliente
                $customerEmail = $wooOrder['billing']['email'] ?? null;
                $customerName = trim(
                    ($wooOrder['billing']['first_name'] ?? '') . ' ' . 
                    ($wooOrder['billing']['last_name'] ?? '')
                );

                // Buscar o crear cliente "Cliente Web" por defecto
                $customer = null;
                if ($customerEmail) {
                    $customer = User::where('email', $customerEmail)->first();
                }

                // Si no existe, usar nombre genérico
                if (!$customer) {
                    $customerName = $customerName ?: 'Cliente Web';
                }

                // PASO 2: Crear la Orden en nuestra base de datos
                $order = Order::create([
                    'customer_name' => $customer ? $customer->name : $customerName,
                    'order_type' => 'delivery', // Pedidos web son delivery por defecto
                    'table_location' => null,
                    'notes' => 'Pedido WooCommerce #' . $wooOrder['id'] . 
                              ($wooOrder['customer_note'] ? ' - ' . $wooOrder['customer_note'] : ''),
                    'total_price' => $wooOrder['total'] ?? 0,
                    'status' => $this->mapWooCommerceStatus($wooOrder['status'] ?? 'pending'),
                    'payment_method' => $this->mapPaymentMethod($wooOrder['payment_method'] ?? 'unknown'),
                ]);

                // PASO 3: Procesar Items de la Orden
                $itemsNotFound = [];
                
                // Verificar si line_items es un array válido
                $lineItems = is_array($wooOrder['line_items']) ? $wooOrder['line_items'] : [];
                
                foreach ($lineItems as $lineItem) {
                    $sku = $lineItem['sku'] ?? null;
                    
                    if (!$sku) {
                        Log::warning('Item sin SKU en orden WooCommerce', [
                            'order_id' => $wooOrder['id'],
                            'item' => $lineItem
                        ]);
                        $itemsNotFound[] = $lineItem['name'] ?? 'Item sin nombre';
                        continue;
                    }

                    // Buscar el producto por SKU en nuestra base de datos
                    $product = Product::where('sku', $sku)->first();

                    if (!$product) {
                        Log::warning('Producto no encontrado por SKU', [
                            'sku' => $sku,
                            'item' => $lineItem
                        ]);
                        $itemsNotFound[] = $lineItem['name'] . " (SKU: $sku)";
                        continue;
                    }

                    // Crear el item de la orden
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $lineItem['quantity'] ?? 1,
                        'price' => $lineItem['price'] ?? $product->price,
                    ]);
                }

                // Commit de la transacción
                DB::commit();

                // Respuesta exitosa
                $response = [
                    'success' => true,
                    'message' => 'Orden procesada exitosamente',
                    'data' => [
                        'local_order_id' => $order->id,
                        'woocommerce_order_id' => $wooOrder['id'],
                        'total' => $order->total_price,
                        'items_count' => $order->items()->count(),
                    ]
                ];

                if (!empty($itemsNotFound)) {
                    $response['warnings'] = [
                        'items_not_found' => $itemsNotFound,
                        'message' => 'Algunos productos no se encontraron por SKU'
                    ];
                }

                Log::info('Orden WooCommerce procesada exitosamente', $response);

                return response()->json($response, 200);

            } catch (\Exception $e) {
                // Rollback en caso de error
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error procesando webhook WooCommerce', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error procesando la orden: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mapear estados de WooCommerce a nuestros estados
     */
    private function mapWooCommerceStatus(string $wooStatus): string
    {
        $statusMap = [
            'pending' => 'pending',
            'processing' => 'pending',
            'on-hold' => 'pending',
            'completed' => 'completed',
            'cancelled' => 'cancelled',
            'refunded' => 'cancelled',
            'failed' => 'cancelled',
        ];

        return $statusMap[$wooStatus] ?? 'pending';
    }

    /**
     * Mapear métodos de pago de WooCommerce
     */
    private function mapPaymentMethod(string $wooMethod): string
    {
        $methodMap = [
            'bacs' => 'efectivo',
            'cheque' => 'efectivo',
            'cod' => 'efectivo',
            'paypal' => 'yape',
            'stripe' => 'tarjeta',
            'card' => 'tarjeta',
        ];

        return $methodMap[$wooMethod] ?? 'efectivo';
    }
}

