<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Category;
use App\Models\Combo;
use Illuminate\Support\Facades\DB;

class TouchPOS extends Component
{
    // Layout del componente
    protected $layout = 'components.layouts.app';
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
    
    // Vista seleccionada (categorias o combos)
    public $selectedView = 'categories'; // 'categories' o 'combos'
    
    // Búsqueda
    public $searchTerm = '';
    
    // Total calculado
    public $total = 0;
    public $subtotal = 0;
    public $discount = 0;
    public $showDiscountInput = false;

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
        $this->subtotal = collect($this->cart)->sum(function ($item) {
            return $item['quantity'] * $item['price'];
        });
        
        $this->total = $this->subtotal - $this->discount;
    }
    
    /**
     * Aplicar descuento
     */
    public function applyDiscount()
    {
        if ($this->discount < 0) {
            $this->discount = 0;
        }
        
        if ($this->discount > $this->subtotal) {
            $this->discount = $this->subtotal;
        }
        
        $this->calculateTotal();
        $this->showDiscountInput = false;
    }
    
    /**
     * Eliminar descuento
     */
    public function removeDiscount()
    {
        $this->discount = 0;
        $this->calculateTotal();
    }

    /**
     * Cambiar categoría seleccionada
     */
    public function selectCategory($category)
    {
        $this->selectedCategory = $category;
        $this->selectedView = 'categories';
    }
    
    /**
     * Cambiar a vista de combos
     */
    public function showCombos()
    {
        $this->selectedView = 'combos';
        $this->selectedCategory = 'Mostrar todo';
    }
    
    /**
     * Agregar combo al carrito
     */
    public function addComboToCart($comboId)
    {
        $combo = Combo::with('products')->find($comboId);
        
        if (!$combo) return;

        $cartKey = 'combo_' . $comboId;

        if (isset($this->cart[$cartKey])) {
            // Si ya existe, aumentar cantidad
            $this->cart[$cartKey]['quantity']++;
        } else {
            // Si no existe, agregar nuevo
            $this->cart[$cartKey] = [
                'combo_id' => $combo->id,
                'name' => '🎁 ' . $combo->name,
                'price' => $combo->price,
                'quantity' => 1,
                'is_combo' => true,
            ];
        }

        $this->calculateTotal();
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
     * Guardar la orden e imprimir ticket
     */
    public function saveOrder()
    {
        // Validar que haya items
        if (empty($this->cart)) {
            session()->flash('error', 'Agrega al menos un producto');
            return;
        }

        // Validar tipo de orden
        if (empty($this->order_type)) {
            session()->flash('error', 'Selecciona el tipo de servicio');
            return;
        }

        // Validar método de pago
        if (empty($this->payment_method)) {
            session()->flash('error', 'Selecciona la forma de pago');
            return;
        }

        try {
            $orderId = null;
            
            DB::transaction(function () use (&$orderId) {
                // Crear la orden
                $order = Order::create([
                    'customer_name' => $this->customer_name ?? 'Cliente General',
                    'order_type' => $this->order_type,
                    'table_location' => $this->table_location,
                    'payment_method' => $this->payment_method,
                    'status' => 'pending',
                    'notes' => $this->notes ?? '',
                    'total_price' => $this->total,
                ]);

                $orderId = $order->id;

                // Crear los items
                foreach ($this->cart as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product_id'] ?? null,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                    ]);
                }
            });

            // Emitir evento para imprimir el ticket
            $this->dispatch('print-ticket', orderId: $orderId);
            
            session()->flash('success', '¡Orden #' . $orderId . ' creada exitosamente!');
            
            // Limpiar carrito y resetear campos
            $this->clearCart();
            $this->customer_name = 'Cliente General';
            $this->notes = '';
            $this->discount = 0;
            $this->showDiscountInput = false;
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar la orden: ' . $e->getMessage());
        }
    }

    /**
     * Renderizar componente
     */
    public function render()
    {
        // Obtener categorías activas ordenadas
        $categories = Category::where('is_active', true)
            ->orderBy('order', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        // Obtener combos activos
        $combos = Combo::where('is_active', true)
            ->orderBy('order', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        // Si estamos en vista de combos
        if ($this->selectedView === 'combos') {
            $query = Combo::where('is_active', true);
            
            // Filtrar por búsqueda
            if (!empty($this->searchTerm)) {
                $query->where('name', 'like', '%' . $this->searchTerm . '%');
            }
            
            $items = $query->get();
            
            return view('livewire.touch-pos', [
                'categories' => $categories,
                'combos' => $combos,
                'products' => collect(), // Colección vacía
                'items' => $items, // Los combos para mostrar
                'isComboView' => true,
            ]);
        }

        // Vista normal de productos
        $query = Product::where('is_active', true)
            ->with('category'); // Cargar la relación

        // Filtrar por categoría
        if ($this->selectedCategory !== 'Mostrar todo') {
            $query->whereHas('category', function($q) {
                $q->where('name', $this->selectedCategory);
            });
        }

        // Filtrar por búsqueda
        if (!empty($this->searchTerm)) {
            $query->where('name', 'like', '%' . $this->searchTerm . '%');
        }

        $products = $query->get();

        return view('livewire.touch-pos', [
            'categories' => $categories,
            'combos' => $combos,
            'products' => $products,
            'items' => $products, // Los productos para mostrar
            'isComboView' => false,
        ]);
    }
}

