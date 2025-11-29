<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Shopping Cart
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @auth
                @php
                    $cartItems = auth()->user()->cartItems()->with('menuItem.restaurant')->get();
                    $total = 0;
                @endphp

                @if($cartItems->count() > 0)
                    <div class="bg-white shadow sm:rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">Cart Items ({{ $cartItems->count() }})</h3>
                            
                            <div class="space-y-4">
                                @foreach($cartItems as $cartItem)
                                    @php
                                        $item = $cartItem->menuItem;
                                        $subtotal = $item->price * $cartItem->quantity;
                                        $total += $subtotal;
                                    @endphp
                                    <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition">
                                        <div class="flex gap-5">
                                            <div class="relative w-24 h-24 sm:w-32 sm:h-32 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0" style="aspect-ratio: 1/1;">
                                                @if($item->image_url)
                                                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="absolute inset-0 w-full h-full" style="object-fit: cover; image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;" loading="lazy">
                                                @else
                                                    <div class="absolute inset-0 w-full h-full flex items-center justify-center bg-gray-200">
                                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <h4 class="text-lg font-bold text-gray-900">{{ $item->name }}</h4>
                                                        <p class="text-sm text-gray-500 uppercase">{{ $item->category ?? 'Uncategorized' }}</p>
                                                        <p class="text-sm text-gray-600 mt-1">{{ $item->restaurant->name }}</p>
                                                    </div>
                                                    <div class="text-right">
                                                        <p class="text-lg font-bold text-gray-900">${{ number_format($subtotal, 2) }}</p>
                                                        <p class="text-sm text-gray-500">${{ number_format($item->price, 2) }} each</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center justify-between mt-4">
                                                    <div class="flex items-center gap-4">
                                                        <span class="text-sm text-gray-600">Quantity: <span class="font-semibold">{{ $cartItem->quantity }}</span></span>
                                                        <span class="text-sm text-gray-600">Stock: <span class="font-semibold">{{ $item->stock }}</span></span>
                                                    </div>
                                                    <form method="POST" action="{{ route('cart.remove') }}" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="menu_item_id" value="{{ $item->id }}">
                                                        <button type="submit" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg text-sm font-medium transition">
                                                            Remove
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    @php
                        $restaurants = $cartItems->groupBy(function($item) {
                            return $item->menuItem->restaurant_id;
                        });
                        $singleRestaurant = $restaurants->count() === 1;
                        $restaurant = $singleRestaurant ? $restaurants->first()->first()->menuItem->restaurant : null;
                    @endphp

                    <div class="bg-white shadow sm:rounded-lg p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-xl font-semibold text-gray-900">Total:</span>
                            <span class="text-3xl font-bold text-red-600">${{ number_format($total, 2) }}</span>
                        </div>
                        
                        @if($singleRestaurant && $restaurant)
                            <form method="POST" action="{{ route('checkout.process') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
                                
                                <div>
                                    <label for="delivery_address" class="block text-sm font-medium text-gray-700 mb-2">Delivery Address *</label>
                                    <input type="text" name="delivery_address" id="delivery_address" required
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-base focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                           placeholder="Enter your delivery address" value="{{ old('delivery_address') }}">
                                    @error('delivery_address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Contact Phone *</label>
                                    <input type="text" name="contact_phone" id="contact_phone" required
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 text-base focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                           placeholder="Enter your phone number" value="{{ old('contact_phone') }}">
                                    @error('contact_phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                                    <textarea name="notes" id="notes" rows="3"
                                              class="w-full border border-gray-300 rounded-lg px-4 py-3 text-base focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                              placeholder="Any special instructions...">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                @if($errors->has('cart') || $errors->has('error'))
                                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                                        @if($errors->has('cart'))
                                            {{ $errors->first('cart') }}
                                        @endif
                                        @if($errors->has('error'))
                                            {{ $errors->first('error') }}
                                        @endif
                                    </div>
                                @endif
                                
                                <div class="flex gap-4 pt-4">
                                    <a href="{{ route('restaurants.index') }}" class="flex-1 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-center font-medium transition">
                                        Continue Shopping
                                    </a>
                                    <button type="submit" class="flex-1 px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg font-semibold transition">
                                        Proceed to Checkout
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg mb-4">
                                <p class="font-semibold">Multiple Restaurants in Cart</p>
                                <p class="text-sm mt-1">Please remove items from other restaurants. You can only checkout items from one restaurant at a time.</p>
                            </div>
                            <div class="flex gap-4">
                                <a href="{{ route('restaurants.index') }}" class="flex-1 px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 text-center font-medium transition">
                                    Continue Shopping
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="bg-white shadow sm:rounded-lg p-8 sm:p-12 text-center">
                        <div class="max-w-md mx-auto">
                            <svg class="w-20 h-20 sm:w-24 sm:h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-2">Your cart is empty</h3>
                            <p class="text-sm text-gray-600 mb-6">Start adding items to your cart to see them here.</p>
                            <a href="{{ route('restaurants.index') }}" class="inline-block px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg font-semibold transition text-sm">
                                Browse Restaurants
                            </a>
                        </div>
                    </div>
                @endif
            @else
                <div class="bg-white shadow sm:rounded-lg p-12 text-center">
                    <div class="max-w-md mx-auto">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-2">Please sign in</h3>
                        <p class="text-sm sm:text-base text-gray-600 mb-6">You need to be signed in to view your cart.</p>
                        <a href="{{ route('login') }}" class="inline-block px-6 py-3 bg-red-500 hover:bg-red-600 text-white rounded-lg font-semibold transition text-sm sm:text-base">
                            Sign In
                        </a>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</x-app-layout>

