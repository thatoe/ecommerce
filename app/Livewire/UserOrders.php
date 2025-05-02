<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Support\Facades\Auth;

class UserOrders extends Component
{
    use WithPagination;

    public $perPage = 5;

    public function __construct()
    {
        $this->orderService = new OrderService();
        $this->cartService = new CartService();
    }

    public function render()
    {
        $orders = Auth::user()
            ->orders()
            ->with('orderItems.product')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.user-orders', compact('orders'))
            ->layout('layouts.app');
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function cancelOrder($orderId)
    {
        $order = Order::where('id', $orderId)->where('user_id', Auth::id())->firstOrFail();

        if ($order->status === 'completed') {
            $order->status = 'canceled';
            $order->save();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Cancelled order successfully!',
            ]);
        }
    }

    /* TODO: merge with shopping cart code. create cart Component and add cart logic to there and use blade inside shop and order summary. */

    public $showCart = false;
    public $cartItems = [];
    public $totalPrice = 0;

    public function loadCart()
    {
        $order = Auth::user()->orders()->where('status', 'pending')->first();

        $this->cartItems = $this->cartService->loadCart($order);
        $this->totalPrice = $this->cartService->calculateTotalPrice($order);
    }

    public function mount()
    {
        $this->loadCart();
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

    /* End */
}
