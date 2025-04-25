<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    public function getOrCreatePendingOrder()
    {
        $user = Auth::user();

        return $user->orders()->firstOrCreate(
            ['status' => 'pending'],
            [
                'user_id' => $user->id,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'quantity' => 0,
                'total_price' => 0,
            ]
        );
    }

    public function addProductToOrder($order, $productId)
    {
        $product = Product::findOrFail($productId);

        $orderItem = OrderItem::firstOrCreate(
            ['order_id' => $order->id, 'product_id' => $productId],
            ['quantity' => 0, 'price' => $product->price]
        );

        $orderItem->quantity += 1;
        $orderItem->save();

        $order->total_price += $product->price;
        $order->save();
    }

    public function completeOrder($order)
    {
        $order->status = 'completed';
        $order->save();
    }
}
