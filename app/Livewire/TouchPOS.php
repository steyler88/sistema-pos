<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class TouchPOS extends Component
{
    // Estado del carrito
    public $cart = [];
    
    // Información de la orden
    public $customer_name = 'Cliente General';
    public $order_type = 'delivery';
    public $table_location = null;
    public $payment_method = 'yape';
    public $status = 'pending';
    public $notes = '';
    
    // Categoría seleccionada
    public $selectedCategory = 'Mostrar todo';
    
    // Total calculado
    public $total = 0;

    /**
     * Agregar producto al carrito
     */
    public function addToCart($productId)
    {
        $product = Product::find($productId);
        
        if (!$product) return;

        $cartKey = 'product_' . $productId;

        if (isset($this->cart[$cartKey])) {
            // Si ya existe, aumentar cantidad
            $this->cart[$cartKey]['quantity']++;
        } else {
            // Si no existe, agregar nuevo
            $this->cart[$cartKey] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
            ];
        }

        $this->calculateTotal();
    }

    /**
     * Aumentar cantidad de un producto
     */
    public function increaseQuantity($cartKey)
    {
        if (isset($this->cart[$cartKey])) {
            $this->cart[$cartKey]['quantity']++;
            $this->calculateTotal();
        }
    }

    /**
     * Disminuir cantidad de un producto
     */
    public function decreaseQuantity($cartKey)
    {
        if (isset($this->cart[$cartKey])) {
            $this->cart[$cartKey]['quantity']--;
            
            if ($this->cart[$cartKey]['quantity'] <= 0) {
                unset($this->cart[$cartKey]);
            }
            
            $this->calculateTotal();
        }
    }

    /**
     * Eliminar producto del carrito
     */
    public function removeFromCart($cartKey)
    {
        unset($this->cart[$cartKey]);
        $this->calculateTotal();
    }

    /**
     * Calcular total del carrito
     */
    public function calculateTotal()
    {
        $this->total = collect($this->cart)->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });
    }

    /**
     * Cambiar categoría seleccionada
     */
    public function selectCategory($category)
    {
        $this->selectedCategory = $category;
    }

    /**
     * Vaciar carrito
     */
    public function clearCart()
    {
        $this->cart = [];
        $this->total = 0;
    }

    /**
     * Guardar la orden
     */
    public function saveOrder()
    {
        // Validar que haya items
        if (empty($this->cart)) {
            session()->flash('error', 'Agrega al menos un producto');
            return;
        }

        try {
            DB::transaction(function () {
                // Crear la orden
                $order = Order::create([
                    'customer_name' => $this->customer_name,
                    'order_type' => $this->order_type,
                    'table_location' => $this->table_location,
                    'payment_method' => $this->payment_method,
                    'status' => $this->status,
                    'notes' => $this->notes,
                    'total_price' => $this->total,
                ]);

                // Crear los items
                foreach ($this->cart as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                    ]);
                }
            });

            session()->flash('success', '¡Orden creada exitosamente!');
            
            // Limpiar carrito
            $this->clearCart();
            $this->customer_name = 'Cliente General';
            $this->notes = '';
            
            // Redirigir a la lista de órdenes
            return redirect()->route('filament.admin.resources.orders.index');
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar la orden: ' . $e->getMessage());
        }
    }

    /**
     * Renderizar componente
     */
    public function render()
    {
        // Obtener productos por categoría
        $categories = Product::where('is_active', true)
            ->select('category')
            ->distinct()
            ->pluck('category');

        // Si selecciona "Mostrar todo", obtener todos los productos
        if ($this->selectedCategory === 'Mostrar todo') {
            $products = Product::where('is_active', true)->get();
        } else {
            $products = Product::where('is_active', true)
                ->where('category', $this->selectedCategory)
                ->get();
        }

        return view('livewire.touch-pos', [
            'categories' => $categories,
            'products' => $products,
        ]);
    }
}

