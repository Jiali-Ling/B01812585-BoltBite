<div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200 hover:shadow-lg transition-shadow">
    <!-- Image -->
    <div class="h-48 bg-gray-100 flex items-center justify-center overflow-hidden">
        <img src="{{ $item['image'] ?? 'https://via.placeholder.com/400x300?text=No+Image' }}" 
             alt="{{ $item['name'] }}" 
             class="w-full h-full object-cover">
    </div>
    
    <!-- Content -->
    <div class="p-4">
        <!-- Category Badge -->
        <div class="mb-2">
            <span class="inline-block bg-gray-100 text-gray-700 text-xs font-semibold px-2 py-1 rounded">
                {{ $item['category'] ?? 'UNCATEGORIZED' }}
            </span>
        </div>
        
        <!-- Name -->
        <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $item['name'] }}</h3>
        
        <!-- Description -->
        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $item['description'] ?? '' }}</p>
        
        <!-- Price and Stock -->
        <div class="flex items-center justify-between mb-3">
            <div class="text-lg font-bold text-gray-900">
                ${{ number_format($item['price'] ?? 0, 2) }}
            </div>
            <div class="text-sm text-gray-500">
                Stock: {{ $item['stock'] ?? 0 }}
            </div>
        </div>
        
        <!-- Status Badge -->
        <div class="mb-3">
            @if(($item['status'] ?? 'off_shelf') === 'on_shelf')
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    On Shelf
                </span>
            @else
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    Off Shelf
                </span>
            @endif
        </div>
        
        <!-- Actions -->
        <div class="flex items-center justify-end space-x-2">
            <button class="p-2 text-gray-600 hover:text-amber-600 hover:bg-amber-50 rounded transition-colors" title="Edit">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </button>
            <button class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded transition-colors" title="Delete">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </button>
        </div>
    </div>
</div>

