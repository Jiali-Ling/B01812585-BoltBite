<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Message -->
            <div class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-lg sm:rounded-xl border border-amber-100 mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-2xl font-bold mb-2">Welcome, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600">You're logged in as: <span class="font-semibold">{{ ucfirst(Auth::user()->role) }}</span></p>
                </div>
            </div>

            <!-- Merchant Zone -->
            @php
                try {
                    $userRestaurants = Auth::user()->restaurants;
                    $hasRestaurants = $userRestaurants && $userRestaurants->count() > 0;
                } catch (\Exception $e) {
                    $userRestaurants = collect();
                    $hasRestaurants = false;
                }
            @endphp
            @if($hasRestaurants)
            <div class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-lg sm:rounded-xl border border-amber-100 mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Merchant Dashboard</h3>
                        <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2.5 py-0.5 rounded">Merchant Access</span>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <a href="{{ route('merchant.orders.index') }}" class="block p-4 bg-amber-50 rounded-lg border border-amber-100 hover:shadow-md transition-all">
                            <div class="flex items-center">
                                <div class="p-3 bg-amber-200 rounded-full text-amber-700 mr-4">
                                    📦
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Manage Orders</h4>
                                    <p class="text-sm text-gray-600">View and update incoming orders</p>
                                </div>
                            </div>
                        </a>

                        @foreach($userRestaurants as $restaurant)
                        <a href="{{ route('merchant.menu.index', $restaurant) }}" class="block p-4 bg-amber-50 rounded-lg border border-amber-100 hover:shadow-md transition-all">
                            <div class="flex items-center">
                                <div class="p-3 bg-amber-200 rounded-full text-amber-700 mr-4">
                                    🍽️
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Menu: {{ $restaurant->name }}</h4>
                                    <p class="text-sm text-gray-600">Edit dishes and prices</p>
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('merchant.reviews.index', $restaurant) }}" class="block p-4 bg-amber-50 rounded-lg border border-amber-100 hover:shadow-md transition-all">
                            <div class="flex items-center">
                                <div class="p-3 bg-amber-200 rounded-full text-amber-700 mr-4">
                                    💬
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Review Inbox</h4>
                                    <p class="text-sm text-gray-600">Read customer feedback and reply</p>
                                </div>
                            </div>
                        </a>
                        @endforeach

                        <a href="{{ route('merchant.stats.index') }}" class="block p-4 bg-amber-50 rounded-lg border border-amber-100 hover:shadow-md transition-all">
                            <div class="flex items-center">
                                <div class="p-3 bg-amber-200 rounded-full text-amber-700 mr-4">
                                    📊
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800">Sales Statistics</h4>
                                    <p class="text-sm text-gray-600">View revenue and performance</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- User Statistics -->
            @php
                try {
                    $ordersCount = Auth::user()->orders->count();
                    $completedCount = Auth::user()->orders->where('status', 'delivered')->count();
                    $totalSpent = Auth::user()->orders->where('status', 'delivered')->sum('total');
                } catch (\Exception $e) {
                    $ordersCount = 0;
                    $completedCount = 0;
                    $totalSpent = 0;
                }
            @endphp
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-br from-amber-50 to-orange-50 overflow-hidden shadow-lg sm:rounded-xl border border-amber-200">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold text-amber-800">Your Orders</h4>
                        <p class="text-3xl font-bold text-orange-600">{{ $ordersCount }}</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 overflow-hidden shadow-lg sm:rounded-xl border border-emerald-200">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold text-emerald-800">Completed</h4>
                        <p class="text-3xl font-bold text-teal-600">{{ $completedCount }}</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-amber-50 to-yellow-50 overflow-hidden shadow-lg sm:rounded-xl border border-amber-200">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold text-amber-800">Total Spent</h4>
                        <p class="text-3xl font-bold text-yellow-600">${{ number_format($totalSpent, 2) }}</p>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-pink-50 overflow-hidden shadow-lg sm:rounded-xl border border-purple-200">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold text-purple-800">Loyalty Points</h4>
                        <p class="text-3xl font-bold text-pink-600">{{ number_format(Auth::user()->points ?? 0) }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-lg sm:rounded-xl border border-amber-100">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-4">Quick Actions</h3>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('restaurants.index') }}" class="bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                            Browse Restaurants
                        </a>
                        <a href="{{ route('orders.index') }}" class="bg-gradient-to-r from-stone-400 to-stone-500 hover:from-stone-500 hover:to-stone-600 text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                            View My Orders
                        </a>
                        <a href="{{ route('cart.index') }}" class="bg-gradient-to-r from-blue-400 to-blue-500 hover:from-blue-500 hover:to-blue-600 text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                            View Cart
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Orders -->
            @php
                try {
                    $userOrders = Auth::user()->orders;
                    $hasOrders = $userOrders && $userOrders->count() > 0;
                } catch (\Exception $e) {
                    $userOrders = collect();
                    $hasOrders = false;
                }
            @endphp
            @if ($hasOrders)
            <div class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-lg sm:rounded-xl border border-amber-100 mt-6">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-4">Recent Orders</h3>
                    <div class="space-y-4">
                        @foreach ($userOrders->sortByDesc('created_at')->take(5) as $order)
                        <div class="flex justify-between items-center border-b border-amber-100 pb-3">
                            <div>
                                <p class="font-semibold">Order #{{ $order->id }}</p>
                                <p class="text-sm text-gray-600">{{ $order->restaurant ? $order->restaurant->name : 'N/A' }}</p>
                                <p class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-amber-600">${{ number_format($order->total, 2) }}</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                                    @elseif($order->status === 'preparing') bg-purple-100 text-purple-800
                                    @elseif($order->status === 'delivered') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('orders.index') }}" class="text-amber-600 hover:text-amber-700 font-semibold">View All Orders →</a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Role-based Information -->
            <div class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-lg sm:rounded-xl border border-amber-100 mt-6">
                <div class="p-6">
                    <h3 class="text-xl font-bold mb-4">Account Information</h3>
                    <ul class="list-disc list-inside space-y-2 text-gray-700">
                        @if (Auth::user()->isAdmin())
                            <li class="text-green-600">✓ You have <strong>Admin</strong> access</li>
                            <li class="text-green-600">✓ You can manage restaurants, merchants, and orders</li>
                            <li class="text-green-600">✓ Full system access and reporting</li>
                        @elseif (Auth::user()->role === 'merchant')
                            <li class="text-amber-600">✓ You have <strong>Merchant</strong> access</li>
                            <li class="text-amber-600">✓ You can manage your restaurants, menus, and orders</li>
                            <li class="text-amber-600">✓ You can reply to reviews and view performance</li>
                        @elseif (Auth::user()->role === 'user')
                            <li class="text-blue-600">✓ You have <strong>User</strong> access</li>
                            <li class="text-blue-600">✓ You can browse restaurants and place orders</li>
                            <li class="text-blue-600">✓ You can track your order history</li>
                        @else
                            <li class="text-gray-600">• You have <strong>Guest</strong> access</li>
                            <li class="text-gray-600">• You can browse restaurants</li>
                            <li class="text-amber-600">• Sign up to place orders</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
