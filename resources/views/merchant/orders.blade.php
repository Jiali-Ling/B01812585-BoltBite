<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manage Orders
            </h2>
            <p class="text-sm text-gray-500">Monitor customer orders in real time and update their status.</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            @if (! $hasRestaurants)
                <div class="bg-white shadow rounded-2xl p-6 text-center">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">No restaurants connected</h3>
                    <p class="text-gray-600">Create or claim a restaurant to start receiving customer orders.</p>
                </div>
            @else
                <div class="bg-white shadow rounded-2xl p-6">
                    <form method="GET" class="grid gap-4 md:grid-cols-3">
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium text-gray-700 mb-1 block">Search</label>
                            <div class="relative">
                                <input
                                    type="text"
                                    name="search"
                                    value="{{ $search }}"
                                    placeholder="Search by order #, customer name, email, or phone"
                                    class="w-full border border-gray-300 rounded-xl py-2.5 pl-4 pr-10 focus:ring-2 focus:ring-orange-400 focus:border-orange-400"
                                >
                                <svg class="w-5 h-5 text-gray-400 absolute right-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-700 mb-1 block">Status</label>
                            <select
                                name="status"
                                class="w-full border border-gray-300 rounded-xl py-2.5 px-3 focus:ring-2 focus:ring-orange-400 focus:border-orange-400"
                            >
                                <option value="">All statuses</option>
                                @foreach ($statusOptions as $option)
                                    <option value="{{ $option }}" @selected($statusFilter === $option)>{{ ucfirst(str_replace('_', ' ', $option)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-3 flex flex-wrap gap-3">
                            <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-6 py-2.5 rounded-xl font-semibold transition">Apply Filters</button>
                            <a href="{{ route('merchant.orders.index') }}" class="px-6 py-2.5 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50 transition">Reset</a>
                        </div>
                    </form>
                </div>

                @php
                    $statusBadges = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'confirmed' => 'bg-blue-100 text-blue-800',
                        'preparing' => 'bg-sky-100 text-sky-800',
                        'ready' => 'bg-indigo-100 text-indigo-800',
                        'out_for_delivery' => 'bg-purple-100 text-purple-800',
                        'delivered' => 'bg-emerald-100 text-emerald-800',
                        'cancelled' => 'bg-red-100 text-red-800',
                    ];
                    $quickStatusButtons = [
                        'confirmed' => 'Confirm Order',
                        'preparing' => 'Start Preparing',
                        'ready' => 'Order Ready',
                        'out_for_delivery' => 'Out for Delivery',
                        'delivered' => 'Mark Delivered',
                    ];
                @endphp

                @if ($orders->isEmpty())
                    <div class="bg-white shadow rounded-2xl p-10 text-center">
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">No orders found</h3>
                        <p class="text-gray-600">Customers have not placed any orders that match your filters yet.</p>
                    </div>
                @else
                    <div class="space-y-6">
                        @foreach ($orders as $order)
                            <div class="bg-white shadow rounded-2xl p-6 space-y-4">
                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                    <div>
                                        <p class="text-sm text-gray-500">Order #{{ $order->id }} · {{ $order->created_at->format('M d, Y H:i') }}</p>
                                        <h3 class="text-xl font-semibold text-gray-900 mt-1">{{ $order->restaurant->name }}</h3>
                                        <p class="text-sm text-gray-600 mt-1">
                                            Customer: <span class="font-medium text-gray-900">{{ $order->user->name }}</span> · {{ $order->contact_phone }}
                                        </p>
                                    </div>
                                    <div class="text-left lg:text-right space-y-1">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusBadges[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                        </span>
                                        <p class="text-2xl font-bold text-gray-900">${{ number_format($order->total, 2) }}</p>
                                    </div>
                                </div>

                                <div class="border rounded-2xl divide-y">
                                    @foreach ($order->items as $item)
                                        <div class="p-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-sm">
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $item->item_name }}</p>
                                                <p class="text-gray-500">Qty {{ $item->quantity }}</p>
                                            </div>
                                            <p class="font-semibold text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</p>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <p class="text-sm text-gray-500">Deliver to: {{ $order->delivery_address }}</p>
                                    <form method="POST" action="{{ route('orders.update', $order) }}" class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="border border-gray-300 rounded-xl py-2 px-3 focus:ring-2 focus:ring-orange-400 focus:border-orange-400 text-sm">
                                            @foreach ($statusOptions as $option)
                                                <option value="{{ $option }}" @selected($order->status === $option)>{{ ucfirst(str_replace('_', ' ', $option)) }}</option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="bg-orange-500 hover:bg-orange-600 text-white px-5 py-2 rounded-xl font-semibold text-sm transition">
                                            Update Status
                                        </button>
                                    </form>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    @foreach ($quickStatusButtons as $statusCode => $label)
                                        <form method="POST" action="{{ route('orders.update', $order) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $statusCode }}">
                                            <button type="submit" class="px-4 py-2 rounded-xl text-sm font-semibold border transition {{ $order->status === $statusCode ? 'bg-gray-200 text-gray-500 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-orange-50 border-orange-200' }}" {{ $order->status === $statusCode ? 'disabled' : '' }}>
                                                {{ $label }}
                                            </button>
                                        </form>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if (method_exists($orders, 'hasPages') && $orders->hasPages())
                        <div class="bg-white shadow rounded-2xl p-4">
                            {{ $orders->links() }}
                        </div>
                    @endif
                @endif
            @endif
        </div>
    </div>
</x-app-layout>
