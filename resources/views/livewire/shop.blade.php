<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Filters -->
    <div class="flex flex-col md:flex-row justify-between mb-6 gap-4">
        <!-- Search -->
        <input
            type="text"
            wire:model.live="search"
            placeholder="Search products..."
            class="w-full md:w-1/2 px-4 py-2 border rounded"
        />

        <!-- Category Filter -->
        <select wire:model.live="selectedCategory" class="w-full md:w-1/4 px-4 py-2 border rounded">
            <option value="">All Categories</option>
            @foreach ($categories as $category)
                <option
                    value="{{ $category->id }}"
                    @if($category->parent_id == null) disabled @endif
                >
                    {!! $category->parent_id ? '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' : '' !!}{{ $category->name }}
                </option>
            @endforeach
        </select>

        <!-- Sort -->
        <select wire:model.live="sortBy" class="w-full md:w-1/4 px-4 py-2 border rounded">
            <option value="name_asc">Name (A - Z)</option>
            <option value="name_desc">Name (Z - A)</option>
            <option value="price_asc">Price (Low to High)</option>
            <option value="price_desc">Price (High to Low)</option>
        </select>
    </div>

    <!-- Cart Icon -->
    <div class="flex justify-end mb-4">
        <button wire:click="$toggle('showCart')" class="relative">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-700 hover:text-gray-900" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-1 5m10-5l1 5m-6-1h2"/>
            </svg>
            <span class="absolute -top-1 -right-2 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">
                {{ $this->cartCount }}
            </span>
        </button>
    </div>

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @forelse ($products as $product)
        <div wire:key="product-{{ $product->id }}" class="border rounded p-4 flex flex-col justify-between">
            <div>
                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://www.freeiconspng.com/uploads/no-image-icon-6.png' }}"
                    alt="{{ $product->name }}"
                    class="w-full h-48 object-cover bg-gray-100 rounded mb-4">
                <h3 class="text-lg font-semibold">{{ $product->name }}</h3>
                <p class="text-gray-600">{{ $product->price }} USD</p>
            </div>

            <div class="mt-4 text-left">
            <button
                class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-200 ease-in-out"
                wire:click="viewProduct({{ $product->id }})"
            >
                View Details
            </button>
            <button
                wire:click="addToCart({{ $product->id }})"
                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
            >
                Add to Cart
            </button>
            </div>
        </div>
        @empty
            <div class="col-span-3 text-center text-gray-500">No products found.</div>
        @endforelse
    </div>

    <div class="flex items-center justify-between mt-6 mb-4">
    <div>
        <label for="perPage" class="text-sm font-medium text-gray-700">Items per page:</label>
        <select wire:model.live="perPage" id="perPage" class="ml-2 border-gray-300 rounded-md shadow-sm text-sm">
            <option value="6">6</option>
            <option value="12">12</option>
            <option value="18">18</option>
        </select>
    </div>

    <div>
        {{ $products->links() }}
    </div>
</div>

    @if ($showModal && $selectedProduct)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div
            class="bg-white rounded-2xl shadow-xl w-full max-w-2xl p-6 relative animate-fade-in"
            x-data
        >
            <!-- Close Button -->
            <button
                wire:click="$set('showModal', false)"
                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 transition"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <img src="{{ $selectedProduct->image ? asset('storage/' . $selectedProduct->image) : 'https://www.freeiconspng.com/uploads/no-image-icon-6.png' }}" alt="{{ $selectedProduct->name }}"
                    class="w-full h-64 object-cover rounded mb-4">

            <!-- Product Info -->
            <h2 class="text-2xl font-bold text-gray-800">{{ $selectedProduct->name }}</h2>
            <p class="text-gray-600 mt-2">{{ $selectedProduct->description }}</p>
            <p class="text-lg font-semibold text-gray-900 mt-4">
                ${{ number_format($selectedProduct->price, 2) }}
            </p>

            <!-- Categories -->
            @if ($selectedProduct->category)
                <div class="mt-4">
                    <span class="text-sm text-gray-500">Categories:</span>
                    <div class="mt-1 flex flex-wrap gap-2">
                        <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded-full">
                            {{ $selectedProduct->category->name }}
                        </span>
                    </div>
                </div>
            @endif

            <div class="mt-6">
            <button
                wire:click="addToCart({{ $selectedProduct->id }})"
                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition duration-200"
            >
                Add to Cart
            </button>
        </div>
        </div>
    </div>
    @endif

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


