<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $restaurant->name ?? 'Restaurant' }} - BoltBite</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
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
                    <a href="{{ route('restaurants.index') }}" class="text-gray-700 hover:text-red-500 font-medium transition">Restaurants</a>
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
        <a href="{{ route('restaurants.index') }}" class="inline-flex items-center text-gray-600 hover:text-red-500 mb-4 transition text-sm">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Restaurants
        </a>

        <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-8">
            <div class="relative w-full bg-gray-200" style="height: 260px; overflow: hidden;">
                @if($restaurant->image)
                    <img src="{{ asset($restaurant->image) }}" alt="{{ $restaurant->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-300">
                        <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                @endif
                <div class="absolute bottom-3 right-3 bg-yellow-400 text-gray-900 px-3 py-1.5 rounded-full font-semibold text-sm flex items-center shadow-lg">
                    <span class="mr-2">⭐</span> {{ number_format($restaurant->rating ?? 4.5, 1) }}
                </div>
            </div>
            <div class="p-5 space-y-2">
                <h1 class="section-title text-gray-900">{{ $restaurant->name }}</h1>
                <p class="subheading">{{ $restaurant->description ?? 'No description available.' }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="section-title text-gray-900 mb-4">Menu</h2>
            
            @if($menuByCategory->count() > 0)
                @php
                    $onShelfCount = 0;
                    foreach($menuByCategory as $items) {
                        $onShelfCount += $items->count();
                    }
                @endphp
                
                <div class="mb-4">
                    <h3 class="text-base font-semibold text-gray-800">All Items ({{ $onShelfCount }})</h3>
                </div>

                @foreach($menuByCategory as $section => $items)
                    <div class="mb-10">
                        <h3 class="text-xs font-semibold text-gray-500 tracking-widest mb-4 pb-2 border-b border-gray-200">{{ $section }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($items as $item)
                                <div class="menu-card">
                                    <div class="menu-card__image" style="height: 160px;">
                                        @if($item->image_url)
                                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-full h-full" style="object-fit: cover; height: 160px; image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;" loading="lazy">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="absolute top-3 left-3">
                                            <span class="pill bg-green-500 text-white gap-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                                On Shelf
                                            </span>
                                        </div>
                                    </div>
                                    <div class="menu-card__body">
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                            <div>
                                                <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-widest mb-1">{{ $item->category ?? 'Uncategorized' }}</p>
                                                <h4 class="text-lg font-semibold text-gray-900">{{ $item->name }}</h4>
                                            </div>
                                            <span class="text-lg font-semibold text-gray-900">${{ number_format($item->price, 2) }}</span>
                                        </div>
                                        <p class="text-sm text-gray-600 line-clamp-2 min-h-[2.5rem]">{{ $item->description ?? 'No description provided.' }}</p>
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-3 border-t border-gray-200">
                                            <span class="text-xs text-gray-500">Stock: {{ $item->stock }}</span>
                                            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                                                <a href="{{ route('menu-items.show', $item) }}" class="flex-1 px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-medium transition text-center">
                                                    View
                                                </a>
                                                @auth
                                                    @if(!auth()->user()->isMerchant())
                                                        <form action="{{ route('cart.add') }}" method="POST" class="flex-1">
                                                            @csrf
                                                            <input type="hidden" name="menu_item_id" value="{{ $item->id }}">
                                                            <input type="hidden" name="quantity" value="1">
                                                            <button type="submit" class="w-full px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-medium transition">
                                                                Add
                                                            </button>
                                                        </form>
                                                    @endif
                                                @else
                                                    <a href="{{ route('login') }}" class="flex-1 px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-xs font-medium transition text-center">
                                                        Add
                                                    </a>
                                                @endauth
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                <div class="bg-gray-50 rounded-lg p-8 text-center">
                    <p class="text-gray-600 text-lg">No menu items are available right now.</p>
                </div>
            @endif
        </div>
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
