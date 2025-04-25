<?php

namespace App\Services;

use App\Models\OrderItem;

class CartService
{
    public function loadCart($order)
    {
        return $order
            ? $order->orderItems()->with('product')->get()->toArray()
            : [];
    }

    public function calculateTotalPrice($order)
    {
        return $order ? $order->total_price : 0;
    }

    public function incrementQuantity($itemId)
    {
        $item = OrderItem::findOrFail($itemId);
        $item->quantity++;
        $item->save();

        $item->order->total_price += $item->price;
        $item->order->save();
    }

    public function decrementQuantity($itemId)
    {
        $item = OrderItem::findOrFail($itemId);

        if ($item->quantity > 1) {
            $item->quantity--;
            $item->save();

            $item->order->total_price -= $item->price;
            $item->order->save();
        } else {
            $this->removeFromCart($itemId);
        }
    }

    public function removeFromCart($itemId)
    {
        $item = OrderItem::findOrFail($itemId);
        $order = $item->order;

        $order->total_price -= $item->quantity * $item->price;
        $order->save();

        $item->delete();
    }
}