<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen">
            <!-- Yellow Header Bar -->
            <nav class="bg-yellow-400 shadow-sm sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16 items-center">
                        <div class="flex items-center">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center mr-8">
                                <a href="{{ route('restaurants.index') }}" class="flex items-center gap-2">
                                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-sm">
                                        <span class="text-2xl">🛵</span>
                                    </div>
                                    <div class="text-xl font-bold text-gray-800">
                                        BoltBite
                                    </div>
                                </a>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:flex">
                                <a href="{{ route('restaurants.index') }}" class="text-gray-800 font-medium hover:text-gray-600 transition-colors {{ request()->routeIs('restaurants.*') ? 'font-bold' : '' }}">
                                    Home
                                </a>
                                <a href="#" class="text-gray-800 font-medium hover:text-gray-600 transition-colors">
                                    Menu
                                </a>
                                @auth
                                    @php
                                        $merchantRestaurant = Auth::user()->restaurants->first();
                                    @endphp
                                    @if(Auth::user()->isMerchant() && $merchantRestaurant)
                                    <a href="{{ route('merchant.menu.index', $merchantRestaurant) }}" class="text-gray-800 font-bold hover:text-amber-600 transition-colors {{ request()->routeIs('merchant.*') ? 'text-amber-600' : '' }}">
                                        Merchant Panel
                                    </a>
                                    @endif
                                    
                                    <a href="{{ route('orders.index') }}" class="text-gray-800 font-medium hover:text-gray-600 transition-colors">
                                        Orders
                                    </a>
                                    <a href="{{ route('cart.index') }}" class="text-gray-800 font-medium hover:text-gray-600 transition-colors flex items-center gap-1">
                                        Cart
                                        @php
                                            $cartCount = auth()->check() ? auth()->user()->cartItems()->count() : 0;
                                        @endphp
                                        @if($cartCount > 0)
                                            <span class="bg-red-500 text-white text-xs px-1.5 py-0.5 rounded-full">{{ $cartCount }}</span>
                                        @endif
                                    </a>
                                @endauth
                            </div>
                        </div>

                        <!-- Right Side User Info -->
                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            @auth
                                <div class="flex items-center gap-4">
                                    <span class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</span>
                                    <form method="POST" action="{{ route('logout') }}" class="inline">
                                        @csrf
                                        <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 font-medium">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="flex items-center gap-4">
                                    <a href="{{ route('login') }}" class="text-sm font-medium text-gray-800 hover:text-gray-600">Login</a>
                                    <span class="text-gray-300">|</span>
                                    <a href="{{ route('register') }}" class="text-sm font-medium text-gray-800 hover:text-gray-600">Register</a>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
            
            <!-- Footer -->
            <footer class="bg-gray-800 text-white py-8 mt-12">
                <div class="max-w-7xl mx-auto px-4 text-center">
                    <p class="text-sm text-gray-400">&copy; {{ date('Y') }} BoltBite Delivery. All rights reserved.</p>
                </div>
            </footer>
        </div>
    </body>
</html>
