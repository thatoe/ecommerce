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
        </div>
    @endforeach
</div>