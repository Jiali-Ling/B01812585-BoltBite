<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Admin Control Center
            </h2>
            <p class="text-sm text-gray-500">Global overview across restaurants, orders, and merchants.</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <div class="bg-white shadow rounded-2xl p-5">
                    <p class="text-sm text-gray-500">Restaurants</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['restaurants'] }}</p>
                </div>
                <div class="bg-white shadow rounded-2xl p-5">
                    <p class="text-sm text-gray-500">Menu Items</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['menuItems'] }}</p>
                </div>
                <div class="bg-white shadow rounded-2xl p-5">
                    <p class="text-sm text-gray-500">Customers</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['customers'] }}</p>
                </div>
                <div class="bg-white shadow rounded-2xl p-5">
                    <p class="text-sm text-gray-500">Pending Orders</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pendingOrders'] }}</p>
                </div>
            </div>

            <div class="bg-white shadow rounded-2xl p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Latest Orders</h3>
                        <p class="text-sm text-gray-500">Full visibility across all restaurants</p>
                    </div>
                    <a href="{{ route('orders.index') }}" class="text-red-500 hover:text-red-600 text-sm font-semibold">View customer view →</a>
                </div>
                <div class="divide-y">
                    @forelse ($recentOrders as $order)
                        <div class="py-4 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-3">
                            <div>
                                <p class="font-semibold text-gray-900">Order #{{ $order->id }} · {{ $order->restaurant->name }}</p>
                                <p class="text-sm text-gray-500">Customer: {{ $order->user->name }} · {{ $order->created_at->format('M d, Y H:i') }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                </span>
                                <p class="font-semibold text-gray-900">${{ number_format($order->total, 2) }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No orders yet.</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white shadow rounded-2xl p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Newest Merchants</h3>
                <div class="divide-y">
                    @forelse ($merchantSignups as $merchant)
                        <div class="py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $merchant->name }}</p>
                                <p class="text-sm text-gray-500">{{ $merchant->email }}</p>
                            </div>
                            <p class="text-sm text-gray-500 mt-2 sm:mt-0">Restaurants: {{ $merchant->restaurants_count }}</p>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No merchants registered yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

