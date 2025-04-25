<?php

namespace App\Livewire;

use App\Services\CartService;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use App\Services\OrderService;

class Shop extends Component
{
    protected $orderService;
    protected $cartService;

    public $search = '';
    public $selectedCategory = '';
    public $sortBy = 'name_asc';

    public $showCart = false;
    public $cartItems = [];
    public $totalPrice = 0;

    public $selectedProduct = null;
    public $showModal = false;

    public function __construct()
    {
        $this->orderService = new OrderService();
        $this->cartService = new CartService();
    }

    public function render()
    {
        $categories = Category::all();

        return view('livewire.shop', [
            'products' => $this->getFilteredProducts(),
            'categories' => Category::all(),
        ])->layout('layouts.app');
    }

    protected function getFilteredProducts()
    {
        return Product::when($this->search, fn($q) =>
                $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->selectedCategory, fn($q) =>
                $q->whereHas('category', fn($cat) =>
                    $cat->where('id', $this->selectedCategory)))
            ->when($this->sortBy, fn($q) => $this->applySorting($q))
            ->paginate(5);
    }

    protected function applySorting($query)
    {
        return match ($this->sortBy) {
            'name_desc' => $query->orderBy('name', 'desc'),
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default => $query->orderBy('name', 'asc'),
        };
    }

    public function viewProduct($productId)
    {
        $this->selectedProduct = Product::with('category')->find($productId);
        $this->showModal = true;
    }

    public function addToCart($productId)
    {
        $order = $this->orderService->getOrCreatePendingOrder();
        $this->orderService->addProductToOrder($order, $productId);

        $this->loadCart();
    }

    public function mount()
    {
        $this->loadCart();
    }

    protected function loadCart()
    {
        $order = Auth::user()->orders()->where('status', 'pending')->first();

        $this->cartItems = $this->cartService->loadCart($order);
        $this->totalPrice = $this->cartService->calculateTotalPrice($order);
    }

    public function incrementQuantity($itemId)
    {
        $this->cartService->incrementQuantity($itemId);
        $this->loadCart();
    }

    public function decrementQuantity($itemId)
    {
        $this->cartService->decrementQuantity($itemId);
        $this->loadCart();
    }

    public function removeFromCart($itemId)
    {
        $this->cartService->removeFromCart($itemId);
        $this->loadCart();
    }

    public function getCartCountProperty()
    {
        return collect($this->cartItems)->sum('quantity');
    }

    public function completeOrder()
    {
        $order = Auth::user()->orders()->where('status', 'pending')->first();

        if (!$order || $order->orderItems->isEmpty()) {
            session()->flash('error', 'Your cart is empty or already completed.');
            return;
        }

        $this->orderService->completeOrder($order);

        $this->showCart = false;
        $this->loadCart();

        $this->dispatch('notify', [
            'type' => 'success',
            'message' => 'Order completed successfully!',
        ]);
    }
}
