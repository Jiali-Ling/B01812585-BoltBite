<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Customer Reviews - {{ $restaurant->name }}
            </h2>
            <p class="text-sm text-gray-500">Merchants can read and reply but cannot publish reviews</p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
                    {{ session('status') }}
                </div>
            @endif
            <div class="bg-white shadow sm:rounded-lg divide-y border border-gray-200">
                @forelse ($reviews as $review)
                    <div class="p-6 space-y-4">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-bold text-gray-900 text-lg">{{ $review->user->name }}</p>
                                <p class="text-sm text-gray-600 mt-1">
                                    <span class="font-medium">{{ $review->menuItem->name }}</span> · 
                                    <span class="text-amber-600">⭐ {{ $review->rating }}/5</span>
                                </p>
                            </div>
                            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded">{{ $review->created_at->diffForHumans() }}</span>
                        </div>
                        <p class="text-gray-700 bg-gray-50 p-3 rounded-lg">{{ $review->comment }}</p>
                        @foreach ($review->replies as $reply)
                            <div class="bg-amber-50 border-l-4 border-amber-500 rounded px-4 py-3">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="font-semibold text-amber-800">{{ $reply->user->name }} <span class="text-xs font-normal text-amber-600">(Merchant reply)</span></p>
                                    <span class="text-xs text-amber-600">{{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-gray-700 text-sm">{{ $reply->body }}</p>
                            </div>
                        @endforeach
                        <form action="{{ route('merchant.reviews.reply', [$restaurant, $review]) }}" method="POST" class="space-y-2 border-t border-gray-200 pt-4">
                            @csrf
                            <label class="block text-sm font-medium text-gray-700 mb-1">Reply to customer</label>
                            <textarea name="body" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500" required placeholder="Type your reply here..."></textarea>
                            <button type="submit" class="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-lg text-sm font-medium transition">
                                Send Reply
                            </button>
                        </form>
                    </div>
                @empty
                    <div class="p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p class="text-gray-500 text-lg">No reviews yet.</p>
                        <p class="text-gray-400 text-sm mt-2">Customer reviews will appear here once they start leaving feedback.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>

