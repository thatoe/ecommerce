<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <h1 class="text-2xl font-bold mb-6">Welcome, {{ auth()->user()->name }} !</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        <div class="bg-white p-6 rounded shadow">
            <div class="text-gray-500">Total Orders</div>
            <div class="text-2xl font-semibold text-gray-800">{{ $totalOrders }}</div>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <div class="text-gray-500">Orders This Month</div>
            <div class="text-2xl font-semibold text-gray-800">{{ $totalOrdersThisMonth }}</div>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <div class="text-gray-500">Orders This Year</div>
            <div class="text-2xl font-semibold text-gray-800">{{ $totalOrdersThisYear }}</div>
        </div>
    </div>

    <div class="flex justify-between items-center mb-4 border-b pb-4 mt-6">
        <h2 class="text-xl font-semibold">Top 5 Popular Products</h2>
        <a href="{{ route('shop') }}" class="text-sm text-blue-500 hover:underline whitespace-nowrap">
            View all products >>>
        </a>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
        @foreach ($popularProducts as $product)
            <div class="bg-white p-4 rounded shadow text-center">
                <img
                    src="{{ $product->image ? asset('storage/' . $product->image) : 'https://www.freeiconspng.com/uploads/no-image-icon-6.png' }}"
                    alt="{{ $product->name }}"
                    class="w-full h-32 object-cover rounded mb-2"
                >
                <div class="font-medium text-gray-800">{{ $product->name }}</div>
                <div class="text-sm text-gray-500">{{ $product->orders_count }} orders</div>
            </div>
        @endforeach
    </div>
</div>