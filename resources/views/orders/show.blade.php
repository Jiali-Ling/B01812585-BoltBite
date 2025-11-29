<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Order Details
            </h2>
            <a href="{{ route('orders.index') }}" class="text-gray-600 hover:text-gray-900">
                ← Back to Orders
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">Order #{{ $order->id }}</h3>
                            <p class="text-gray-600 mt-1">{{ $order->restaurant->name }}</p>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                @if($order->status === 'delivered') bg-green-100 text-green-800
                                @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-blue-100 text-blue-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $order->status)) }}
                            </span>
                            <p class="text-gray-600 mt-2">Total: <span class="font-bold text-lg">${{ number_format($order->total, 2) }}</span></p>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Delivery Information</h4>
                            <p class="text-gray-600">{{ $order->delivery_address }}</p>
                            <p class="text-gray-600 mt-1">{{ $order->contact_phone }}</p>
                            @if($order->notes)
                                <p class="text-gray-600 mt-2"><strong>Notes:</strong> {{ $order->notes }}</p>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-2">Order Information</h4>
                            <p class="text-gray-600">Placed: {{ $order->created_at->format('M d, Y H:i') }}</p>
                            @if($order->updated_at !== $order->created_at)
                                <p class="text-gray-600">Updated: {{ $order->updated_at->format('M d, Y H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Order Items</h3>
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-center justify-between border-b border-gray-200 pb-4">
                                <div class="flex items-center space-x-4">
                                    @if($item->menuItem && $item->menuItem->image_url)
                                        <img src="{{ $item->menuItem->image_url }}" alt="{{ $item->item_name }}" class="w-16 h-16 object-cover rounded">
                                    @endif
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $item->item_name }}</p>
                                        <p class="text-gray-600">Quantity: {{ $item->quantity }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-semibold text-gray-900">${{ number_format($item->price * $item->quantity, 2) }}</p>
                                    <p class="text-sm text-gray-600">${{ number_format($item->price, 2) }} each</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Delivery Progress Timeline</h3>
                    <div class="relative">
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>
                        <div class="space-y-6">
                            @forelse($order->events as $event)
                                <div class="relative flex items-start">
                                    <div class="absolute left-3 w-3 h-3 rounded-full bg-red-500 border-2 border-white"></div>
                                    <div class="ml-8 flex-1">
                                        <div class="flex items-center justify-between">
                                            <p class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $event->status)) }}</p>
                                            <p class="text-sm text-gray-500">{{ $event->occurred_at->format('M d, Y H:i') }}</p>
                                        </div>
                                        @if($event->description)
                                            <p class="text-gray-600 mt-1">{{ $event->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 ml-8">No delivery events yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            @if($order->status === 'delivered' && !$order->comments->where('user_id', auth()->id())->count())
                <div class="bg-white shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Add a Comment</h3>
                        <form action="{{ route('orders.comments.store', $order) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
                                <select name="rating" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                                    <option value="">Select rating</option>
                                    <option value="5">5 - Excellent</option>
                                    <option value="4">4 - Very Good</option>
                                    <option value="3">3 - Good</option>
                                    <option value="2">2 - Fair</option>
                                    <option value="1">1 - Poor</option>
                                </select>
                                @error('rating')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Comment</label>
                                <textarea name="content" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2" required placeholder="Share your experience..."></textarea>
                                @error('content')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-medium transition">
                                Submit Comment
                            </button>
                        </form>
                    </div>
                </div>
            @endif

            @if($order->comments->count() > 0)
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Comments</h3>
                        <div class="space-y-4">
                            @foreach($order->comments as $comment)
                                <div class="border-b border-gray-200 pb-4 last:border-0">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="flex items-center space-x-2">
                                            <p class="font-semibold text-gray-900">{{ $comment->user->name }}</p>
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= $comment->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                        </div>
                                        @if($comment->user_id === auth()->id())
                                            <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm">Delete</button>
                                            </form>
                                        @endif
                                    </div>
                                    <p class="text-gray-600">{{ $comment->content }}</p>
                                    <p class="text-sm text-gray-500 mt-2">{{ $comment->created_at->format('M d, Y H:i') }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @can('update', $order)
                @if($order->restaurant->user_id === auth()->id())
                    <div class="bg-white shadow-sm sm:rounded-lg mt-6">
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4">Update Order Status</h3>
                            <form action="{{ route('orders.update', $order) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                    <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                        <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>Preparing</option>
                                        <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Ready</option>
                                        <option value="out_for_delivery" {{ $order->status === 'out_for_delivery' ? 'selected' : '' }}>Out for Delivery</option>
                                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                                    <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Add a note about this status change..."></textarea>
                                </div>
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-lg font-medium transition">
                                    Update Status
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            @endcan
        </div>
    </div>
</x-app-layout>

