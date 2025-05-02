<div class="max-w-7xl mx-auto px-4 py-8">
    @foreach($orders as $order)
        <div class="bg-white shadow-sm rounded-lg border border-gray-200 px-6 py-4 mb-4">
            <div class="flex justify-between items-center mb-3">
                <h2 class="text-md font-semibold text-gray-800">
                    Order #{{ $order->id }}
                </h2>
                <span class="text-xs px-3 py-1 rounded-full
                             {{
                                $order->status === 'completed'
                                    ? 'bg-green-100 text-green-700'
                                    : ($order->status === 'canceled'
                                        ? 'bg-red-100 text-red-700'
                                        : 'bg-yellow-100 text-yed-700')
                            }}">
                    Status: {{ ucfirst($order->status) }}
                </span>
            </div>

            <ul class="space-y-2">
                @foreach($order->orderItems as $item)
                    <li class="flex items-center space-x-3 text-sm text-gray-700">
                        <div>
                        <img src="{{ $item->product->image ? asset('storage/' . $item->product->image) : 'https://www.freeiconspng.com/uploads/no-image-icon-6.png' }}"
                            alt="{{ $item->product->name }}"
                            class="w-20 h-20 object-cover rounded">
                        </div>

                        <div class="flex-1">
                            <span class="font-medium">{{ $item->product->name }}</span>
                            <span class="text-xs text-gray-500 ml-1">×{{ $item->quantity }}</span>
                            <p class="text-sm text-gray-600 mt-1">
                                ${{ number_format($item->price, 2) }} per item
                            </p>
                        </div>
                        <div class="text-sm text-gray-700 font-medium">
                            ${{ number_format($item->price * $item->quantity, 2) }}
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="mt-4 text-right">
                @if ($order->status === 'completed')
                    <button wire:click="cancelOrder({{ $order->id }})"
                            class="px-4 py-2 text-sm text-white bg-red-500 hover:bg-red-600 rounded">
                        Cancel Order
                    </button>
                @elseif ($order->status === 'pending')
                    <button wire:click="$toggle('showCart')" class="px-4 py-2 text-sm text-white bg-blue-500 hover:bg-blue-600 rounded">
                        Edit Order
                    </button>
                @endif
            </div>
        </div>
    @endforeach

    <div class="flex items-center justify-between mt-6 mb-4">
        <div>
            <label for="perPage" class="text-sm font-medium text-gray-700">Items per page:</label>
            <select wire:model.live="perPage" id="perPage" class="ml-2 border-gray-300 rounded-md shadow-sm text-sm">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
            </select>
        </div>

        <div>
            {{ $orders->links() }}
        </div>
    </div>

    @if ($showCart)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-xl p-6 relative">
            <button wire:click="$set('showCart', false)" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                ✕
            </button>

            <h2 class="text-xl font-bold mb-4">Your Cart</h2>

            @forelse ($cartItems as $item)
            <div wire:key="cart-item-{{ $item['id'] }}" class="flex flex-col sm:flex-row justify-between items-start sm:items-center border-b py-4 gap-4">
                <!-- Product Info -->
                <div class="flex-1">
                    <p class="font-semibold text-gray-800">{{ $item['product']['name'] }}</p>
                    <p class="text-sm text-gray-600">${{ number_format($item['price'], 2) }} x {{ $item['quantity'] }}</p>
                </div>

                <!-- Subtotal -->
                <div class="text-sm text-gray-700 font-medium w-28 text-right sm:text-center">
                    ${{ number_format($item['price'] * $item['quantity'], 2) }}
                </div>

                <!-- Controls -->
                <div class="flex items-center gap-2">
                    <button wire:click="decrementQuantity({{ $item['id'] }})" class="bg-gray-200 px-2 py-1 rounded hover:bg-gray-300">-</button>
                    <span class="min-w-[1.5rem] text-center">{{ $item['quantity'] }}</span>
                    <button wire:click="incrementQuantity({{ $item['id'] }})" class="bg-gray-200 px-2 py-1 rounded hover:bg-gray-300">+</button>
                    <button wire:click="removeFromCart({{ $item['id'] }})" class="text-red-500 hover:underline ml-2">Remove</button>
                </div>
            </div>
            @empty
                <p class="text-gray-500">Your cart is empty.</p>
            @endforelse

            @if (count($cartItems))
            <div class="mt-4 text-right">
                <p class="text-lg font-semibold">Total: ${{ number_format($totalPrice, 2) }}</p>
            </div>
                <div class="mt-6 text-right">
                    <button
                        wire:click="completeOrder"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition"
                    >
                        Complete Order
                    </button>
                </div>
            @endif
        </div>
    </div>
    @endif
</div>