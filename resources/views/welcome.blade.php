<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>BoltBite - Order Food Online</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-white">
        <!-- Header -->
        <header class="bg-white shadow-sm sticky top-0 z-50">
            <div class="content-shell">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="/" class="flex items-center space-x-2">
                            <div class="w-9 h-9 bg-red-500 rounded-lg flex items-center justify-center">
                                <span class="text-white text-lg font-bold">🛵</span>
                            </div>
                            <span class="text-xl font-bold text-gray-900">BoltBite</span>
                        </a>
                    </div>

                    <!-- Navigation -->
                    <nav class="hidden md:flex items-center space-x-6 text-sm">
                        <a href="{{ route('restaurants.index') }}" class="text-gray-700 hover:text-red-500 font-medium transition">Restaurants</a>
                        <a href="#how-it-works" class="text-gray-700 hover:text-red-500 font-medium transition">How It Works</a>
                        <a href="#about" class="text-gray-700 hover:text-red-500 font-medium transition">About</a>
                    </nav>

                    <!-- Auth Links -->
                    @if (Route::has('login'))
                        <div class="flex items-center space-x-3 text-sm">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-red-500 font-medium transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="text-gray-700 hover:text-red-500 font-medium transition">Sign In</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition">Register</a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white py-20 overflow-hidden">
            <div class="absolute inset-0 opacity-20">
                <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1920" alt="" class="w-full h-full object-cover">
            </div>
            <div class="absolute inset-0 bg-black/50"></div>
            <div class="content-shell relative z-10">
                <div class="grid md:grid-cols-2 gap-12 items-center">
                    <div class="w-full">
                        <h1 class="headline-xl mb-6 drop-shadow-lg">Order <span class="text-red-500">Food Online</span> From the Best Restaurants</h1>
                        <p class="subheading text-white mb-8 drop-shadow-lg">Get your favorite meals delivered fresh and fast. From local favorites to trending cuisines, we've got it all!</p>
                        
                        <!-- Stats -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10 text-sm">
                            <div class="bg-black/40 backdrop-blur-sm rounded-lg p-4 text-center border border-white/20">
                                <div class="text-xl md:text-2xl font-semibold text-red-500">127</div>
                                <div class="text-xs text-white">Restaurants</div>
                            </div>
                            <div class="bg-black/40 backdrop-blur-sm rounded-lg p-4 text-center border border-white/20">
                                <div class="text-2xl md:text-3xl font-bold text-red-500">180</div>
                                <div class="text-xs md:text-sm text-white">Menu Items</div>
                            </div>
                            <div class="bg-black/40 backdrop-blur-sm rounded-lg p-4 text-center border border-white/20">
                                <div class="text-2xl md:text-3xl font-bold text-red-500">610K</div>
                                <div class="text-xs md:text-sm text-white">Orders</div>
                            </div>
                            <div class="bg-black/40 backdrop-blur-sm rounded-lg p-4 text-center border border-white/20">
                                <div class="text-2xl md:text-3xl font-bold text-red-500">236</div>
                                <div class="text-xs md:text-sm text-white">Drivers</div>
                            </div>
                        </div>

                        <a href="{{ route('restaurants.index') }}" class="inline-block bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg text-base font-semibold transition shadow-lg hover:shadow-xl">
                            Browse Restaurants
                        </a>
                    </div>
                    <div class="relative hidden md:block w-full">
                        <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600" alt="Food" class="w-full h-auto rounded-2xl shadow-2xl object-cover">
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section id="how-it-works" class="section-spacing bg-gray-50">
            <div class="content-shell">
                <div class="text-center mb-12">
                    <h2 class="section-title text-gray-900 mb-3">Easy Order In 3 Steps</h2>
                    <p class="subheading">Simple process to get your food delivered</p>
                </div>

                <div class="grid md:grid-cols-3 gap-8 text-sm">
                    <div class="bg-white rounded-2xl p-7 shadow-lg text-center hover:shadow-xl transition">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Choose Your Restaurant</h3>
                        <p class="text-gray-600">Browse through our selection of top-rated restaurants and find your favorite cuisine.</p>
                    </div>

                    <div class="bg-white rounded-2xl p-7 shadow-lg text-center hover:shadow-xl transition">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Add to Cart</h3>
                        <p class="text-gray-600">Select your dishes, customize your order, and add everything to your cart with ease.</p>
                    </div>

                    <div class="bg-white rounded-2xl p-7 shadow-lg text-center hover:shadow-xl transition">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Get Delivery</h3>
                        <p class="text-gray-600">Sit back and relax while we deliver your hot, fresh meal right to your doorstep.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Restaurants -->
        <section class="section-spacing bg-white">
            <div class="content-shell">
                <div class="text-center mb-12">
                    <h2 class="section-title text-gray-900 mb-3">Featured Restaurants</h2>
                    <p class="subheading">Discover our top-rated local favorites</p>
                </div>

                @if($featuredRestaurants->count() > 0)
                    <div class="grid md:grid-cols-3 gap-10">
@foreach($featuredRestaurants as $restaurant)
                        <a href="{{ route('restaurants.show', $restaurant) }}" class="group block bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                            <div class="relative w-full" style="height: 180px; background-color: #e5e7eb; overflow: hidden;">
                                @if($restaurant->image)
                                    <img src="{{ asset($restaurant->image) }}" alt="{{ $restaurant->name }}" class="w-full h-full" style="object-fit: cover; height: 180px; image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;" loading="lazy" decoding="async">
                                @else
                                    <div class="w-full h-full bg-gray-300 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                        </svg>
                                    </div>
                                @endif
                                    <div class="absolute top-4 right-4 bg-yellow-400 text-gray-900 px-3 py-1 rounded-full font-semibold text-sm flex items-center shadow-md">
                                    <span class="mr-1">⭐</span> {{ number_format($restaurant->rating ?? 4.5, 1) }}
                                </div>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-red-500 transition">{{ $restaurant->name }}</h3>
                                <p class="text-gray-600 text-sm mb-4">{{ Str::limit($restaurant->description ?? '', 80) }}</p>
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <span>⭐ Rating: {{ number_format($restaurant->rating ?? 4.5, 1) }}</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                @else
                    <div class="text-center py-12">
                        <p class="text-gray-600 text-lg">No featured restaurants available at the moment.</p>
                    </div>
                @endif

                <div class="text-center mt-14">
                            <a href="{{ route('restaurants.index') }}" class="inline-block bg-gray-900 hover:bg-gray-800 text-white px-6 py-3 rounded-lg text-base font-semibold transition">
                        View All Restaurants
                    </a>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid md:grid-cols-4 gap-8">
                    <div>
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                                <span class="text-white text-xl font-bold">🛵</span>
                            </div>
                            <span class="text-2xl font-bold">BoltBite</span>
                        </div>
                        <p class="text-gray-400">Fast food delivery from your favorite restaurants.</p>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Company</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="#" class="hover:text-white transition">About Us</a></li>
                            <li><a href="#" class="hover:text-white transition">Careers</a></li>
                            <li><a href="#" class="hover:text-white transition">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Support</h4>
                        <ul class="space-y-2 text-gray-400">
                            <li><a href="#" class="hover:text-white transition">Help Center</a></li>
                            <li><a href="#" class="hover:text-white transition">Safety</a></li>
                            <li><a href="#" class="hover:text-white transition">Terms</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-lg font-semibold mb-4">Get the App</h4>
                        <div class="space-y-2">
                            <a href="#" class="block bg-gray-800 hover:bg-gray-700 px-4 py-2 rounded-lg transition">
                                <span class="text-sm">Download on</span><br>
                                <span class="font-semibold">App Store</span>
                            </a>
                            <a href="#" class="block bg-gray-800 hover:bg-gray-700 px-4 py-2 rounded-lg transition">
                                <span class="text-sm">Get it on</span><br>
                                <span class="font-semibold">Google Play</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                    <p>&copy; {{ date('Y') }} BoltBite. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </body>
</html>
