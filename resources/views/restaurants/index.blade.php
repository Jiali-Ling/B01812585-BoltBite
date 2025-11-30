<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restaurants - BoltBite</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .restaurant-card-image { 
            aspect-ratio: 16/9; 
            object-fit: cover; 
            height: 180px;
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
        }
    </style>
</head>
<body class="bg-gray-50">
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="content-shell">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <div class="w-9 h-9 bg-red-500 rounded-lg flex items-center justify-center">
                            <span class="text-white text-lg font-bold">🛵</span>
                        </div>
                        <span class="text-xl font-bold text-gray-900">BoltBite</span>
                    </a>
                </div>
                <nav class="hidden md:flex items-center space-x-6 text-sm">
                    <a href="/" class="text-gray-700 hover:text-red-500 font-medium transition">Home</a>
                    <a href="{{ route('restaurants.index') }}" class="text-red-500 font-medium">Restaurants</a>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-red-500 font-medium transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-red-500 font-medium transition">Sign In</a>
                        <a href="{{ route('register') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-medium transition">Register</a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <main class="content-shell section-spacing">
        <div class="mb-8">
            <h1 class="section-title text-gray-900 mb-2">Restaurants</h1>
            <p class="subheading">Discover our top-rated local favorites</p>
        </div>

        @if($restaurants->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($restaurants as $restaurant)
                    <a href="{{ route('restaurants.show', $restaurant->id) }}" class="group block bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="relative w-full bg-gray-200" style="height: 180px; overflow: hidden;">
                            @if($restaurant->image)
                                <img src="{{ asset($restaurant->image) }}" alt="{{ $restaurant->name }}" class="restaurant-card-image w-full" loading="lazy" decoding="async" style="object-fit: cover; height: 180px;">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gray-300">
                                    <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-4 right-4 bg-yellow-400 text-gray-900 px-3 py-1 rounded-full font-semibold text-xs flex items-center">
                                <span class="mr-1">⭐</span> {{ number_format($restaurant->rating ?? 4.5, 1) }}
                            </div>
                        </div>
                        <div class="p-5">
                            <h3 class="text-lg font-semibold text-gray-900 mb-1 group-hover:text-red-500 transition">{{ $restaurant->name }}</h3>
                            <p class="text-gray-600 text-xs mb-3">{{ Str::limit($restaurant->description ?? '', 100) }}</p>
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <span>⭐ Rating: {{ number_format($restaurant->rating ?? 4.5, 1) }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $restaurants->links() }}
            </div>
        @else
            <div class="bg-white rounded-lg shadow p-8 text-center">
                <p class="text-gray-600 text-lg">No restaurants available at the moment.</p>
                <p class="text-gray-500 mt-2">Please check back later.</p>
            </div>
        @endif
    </main>

    <footer class="bg-gray-900 text-white py-12 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex items-center justify-center space-x-2 mb-4">
                    <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                        <span class="text-white text-xl font-bold">🛵</span>
                    </div>
                    <span class="text-2xl font-bold">BoltBite</span>
                </div>
                <p class="text-gray-400">&copy; {{ date('Y') }} BoltBite. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
