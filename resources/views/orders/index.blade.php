<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            My Orders
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($orders->count() > 0)
                <div class="grid gap-6">
                    @foreach($orders as $order)
                        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-4">
                                            <div>
                                                <h3 class="text-lg font-semibold text-gray-900">Order #{{ $order->id }}</h3>
                                                <p class="text-gray-600 mt-1">{{ $order->restaurant->name }}</p>
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                                    @if($order->status === 'delivered') bg-green-100 text-green-800
                                                    @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @else bg-blue-100 text-blue-800
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="mt-4 flex items-center space-x-6 text-sm text-gray-600">
                                            <span>Placed: {{ $order->created_at->format('M d, Y H:i') }}</span>
                                            <span>Items: {{ $order->items->sum('quantity') }}</span>
                                            <span class="font-semibold text-gray-900">Total: ${{ number_format($order->total, 2) }}</span>
                                        </div>
                                        @if($order->events->count() > 0)
                                            <div class="mt-3 text-sm text-gray-600">
                                                Latest: {{ $order->events->last()->description ?? ucfirst(str_replace('_', ' ', $order->events->last()->status)) }}
                                                <span class="text-gray-400">({{ $order->events->last()->occurred_at->diffForHumans() }})</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-6">
                                        <a href="{{ route('orders.show', $order) }}" class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No orders</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by placing your first order.</p>
                        <div class="mt-6">
                            <a href="{{ route('restaurants.index') }}" class="inline-flex items-center px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition">
                                Browse Restaurants
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

