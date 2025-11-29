<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $menuItem->name }} - {{ $restaurant->name }} - BoltBite</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .menu-item-image { 
            aspect-ratio: 4/3; 
            object-fit: cover; 
            width: 100%;
            height: 350px;
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
        }
    </style>
</head>
<body class="bg-gray-50">
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                            <span class="text-white text-xl font-bold">🛵</span>
                        </div>
                        <span class="text-2xl font-bold text-gray-900">BoltBite</span>
                    </a>
                </div>
                <nav class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-gray-700 hover:text-red-500 font-medium transition">Home</a>
                    <a href="{{ route('restaurants.index') }}" class="text-gray-700 hover:text-red-500 font-medium transition">Restaurants</a>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-red-500 font-medium transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-red-500 font-medium transition">Sign In</a>
                        <a href="{{ route('register') }}" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2.5 rounded-lg font-medium transition">Register</a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <a href="{{ route('restaurants.show', $restaurant->id) }}" class="inline-flex items-center text-gray-600 hover:text-red-500 mb-6 transition">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to {{ $restaurant->name }}
        </a>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
            <div class="grid md:grid-cols-2 gap-0">
                <div class="relative w-full bg-gray-100 overflow-hidden" style="height: 350px;">
                    @if($menuItem->image_url)
                        <img src="{{ $menuItem->image_url }}" alt="{{ $menuItem->name }}" class="menu-item-image" loading="eager">
                    @else
                        <div class="w-full h-full flex items-center justify-center bg-gray-200">
                            <svg class="w-24 h-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    <div class="absolute top-4 right-4">
                        <span class="bg-green-500 text-white px-4 py-2 rounded-full text-sm font-semibold flex items-center gap-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            On Shelf
                        </span>
                    </div>
                </div>
                <div class="p-8">
                    <div class="mb-4">
                        <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">{{ $menuItem->category ?? 'Uncategorized' }}</p>
                        <h1 class="text-4xl font-bold text-gray-900 mb-4">{{ $menuItem->name }}</h1>
                        <p class="text-lg text-gray-600 mb-6">{{ $menuItem->description ?? 'No description provided.' }}</p>
                    </div>
                    <div class="border-t border-b border-gray-200 py-6 mb-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Price</p>
                                <p class="text-3xl font-bold text-gray-900">${{ number_format($menuItem->price, 2) }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500 mb-1">Stock Available</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $menuItem->stock }}</p>
                            </div>
                        </div>
                    </div>
                    @auth
                        @if(!auth()->user()->isMerchant())
                            <form action="{{ route('cart.add') }}" method="POST" class="mb-6">
                                @csrf
                                <input type="hidden" name="menu_item_id" value="{{ $menuItem->id }}">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="w-full px-6 py-4 bg-red-500 hover:bg-red-600 text-white rounded-lg text-lg font-semibold transition shadow-lg hover:shadow-xl">
                                    Add to Cart
                                </button>
                            </form>
                        @endif
                    @else
                        <a href="{{ route('login') }}" class="block w-full px-6 py-4 bg-red-500 hover:bg-red-600 text-white rounded-lg text-lg font-semibold transition shadow-lg hover:shadow-xl text-center mb-6">
                            Sign In to Add to Cart
                        </a>
                    @endauth
                    <div class="text-sm text-gray-500">
                        <p>From: <a href="{{ route('restaurants.show', $restaurant->id) }}" class="text-red-500 hover:text-red-600 font-medium">{{ $restaurant->name }}</a></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-6">Customer Reviews</h2>
            
            @if($menuItem->reviews->count() > 0)
                <div class="space-y-6 mb-8">
                    @foreach($menuItem->reviews->sortByDesc('created_at') as $review)
                        <div class="border-b border-gray-200 pb-6 last:border-b-0 last:pb-0">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <p class="font-bold text-gray-900 text-lg">{{ $review->user->name }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-amber-600">⭐ {{ $review->rating }}/5</span>
                                        <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            <p class="text-gray-700 mb-4">{{ $review->comment }}</p>
                            
                            @if($review->replies->count() > 0)
                                @foreach($review->replies as $reply)
                                    <div class="bg-amber-50 border-l-4 border-amber-500 rounded px-4 py-3 ml-4">
                                        <div class="flex items-center justify-between mb-1">
                                            <p class="font-semibold text-amber-800">{{ $reply->user->name }} <span class="text-xs font-normal text-amber-600">(Merchant reply)</span></p>
                                            <span class="text-xs text-amber-600">{{ $reply->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-gray-700 text-sm">{{ $reply->body }}</p>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12 mb-8">
                    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">No reviews yet.</p>
                    <p class="text-gray-400 text-sm mt-2">Be the first to review this item!</p>
                </div>
            @endif

            @auth
                @if(!auth()->user()->isMerchant())
                    <div class="border-t border-gray-200 pt-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Leave a Review</h3>
                        <form action="{{ route('menu-items.reviews.store', $menuItem) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                                <select name="rating" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-base focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    @for($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}">{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Your Review</label>
                                <textarea name="comment" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-3 text-base focus:ring-2 focus:ring-red-500 focus:border-red-500" required placeholder="Share your experience with this item..."></textarea>
                            </div>
                            <button type="submit" class="w-full px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-base font-semibold transition shadow-md hover:shadow-lg">
                                Submit Review
                            </button>
                        </form>
                    </div>
                @else
                    <div class="border-t border-gray-200 pt-8">
                        <div class="bg-gray-50 rounded-lg p-6 text-center">
                            <p class="text-gray-600">Merchant accounts cannot post reviews.</p>
                            <p class="text-sm text-gray-500 mt-2">You can view and reply to customer reviews from your merchant dashboard.</p>
                        </div>
                    </div>
                @endif
            @else
                <div class="border-t border-gray-200 pt-8">
                    <div class="bg-gray-50 rounded-lg p-6 text-center">
                        <p class="text-gray-600 mb-4">Sign in to leave a review</p>
                        <a href="{{ route('login') }}" class="inline-block px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg font-semibold transition">
                            Sign In
                        </a>
                    </div>
                </div>
            @endauth
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

