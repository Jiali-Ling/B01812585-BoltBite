<x-app-layout>
    @php
        $canManage = auth()->user()->isAdmin() || $restaurant->user_id === auth()->id();
    @endphp
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Menu Management - {{ $restaurant->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white shadow sm:rounded-lg p-6 mb-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900">All Items ({{ $menuItems->count() }})</h3>
                    <div class="flex gap-4">
                        <a href="{{ route('merchant.menu.index', ['restaurant' => $restaurant, 'status' => 'on_shelf']) }}" 
                           class="px-4 py-2 rounded-lg font-medium transition {{ request('status') != 'off_shelf' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            On Shelf ({{ $onShelfCount }})
                        </a>
                        <a href="{{ route('merchant.menu.index', ['restaurant' => $restaurant, 'status' => 'off_shelf']) }}" 
                           class="px-4 py-2 rounded-lg font-medium transition {{ request('status') == 'off_shelf' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            Off Shelf ({{ $offShelfCount }})
                        </a>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($menuItems as $item)
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="relative w-full" style="height: 180px; background-color: #f3f4f6; overflow: hidden;">
                                @if ($item->image_url)
                                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="w-full h-full" style="object-fit: cover; height: 180px; image-rendering: -webkit-optimize-contrast; image-rendering: crisp-edges;" loading="lazy">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div class="absolute top-2 right-2">
                                    @if($item->status === 'on_shelf')
                                        <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-semibold flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            On Shelf
                                        </span>
                                    @else
                                        <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                            Off Shelf
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="p-5 space-y-3">
                                <div>
                                    <h4 class="text-xl font-bold text-gray-900 mb-1">{{ $item->name }}</h4>
                                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ $item->category ?? 'Uncategorized' }}</p>
                                </div>
                                <p class="text-sm text-gray-600 line-clamp-2 min-h-[2.5rem]">{{ $item->description ?? 'No description provided.' }}</p>
                                <div class="flex items-center justify-between pt-3 border-t border-gray-200">
                                    <div class="flex flex-col gap-1">
                                        <span class="font-bold text-gray-900 text-lg">${{ number_format($item->price, 2) }}</span>
                                        <span class="text-xs text-gray-500">Stock: {{ $item->stock }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        @if($canManage)
                                            <button onclick="openEditModal({{ $item->id }})" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded transition">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                            <form method="POST" action="{{ route('merchant.menu-items.destroy', [$restaurant, $item]) }}" onsubmit="return confirm('Delete this menu item?')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-xs text-gray-400">View only</span>
                                        @endif
                                    </div>
                                </div>
                                <form action="{{ route('merchant.menu-items.toggle', [$restaurant, $item]) }}" method="POST" class="pt-3 border-t border-gray-200">
                                    @csrf
                                    <button type="submit" {{ $canManage ? '' : 'disabled' }} class="w-full px-4 py-2.5 text-sm font-semibold rounded-lg transition {{ $item->status === 'on_shelf' ? 'bg-red-50 text-red-700 hover:bg-red-100 border border-red-200' : 'bg-green-50 text-green-700 hover:bg-green-100 border border-green-200' }} {{ $canManage ? '' : 'opacity-50 cursor-not-allowed' }}">
                                        {{ $item->status === 'on_shelf' ? 'Take Off Shelf' : 'Put On Shelf' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <p class="text-gray-500 text-lg">No menu items found.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            @if($canManage)
            <div class="bg-white shadow sm:rounded-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Add New Item</h3>
                <form action="{{ route('merchant.menu-items.store', $restaurant) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="w-full border @error('name') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <input type="text" name="category" value="{{ old('category') }}" class="w-full border @error('category') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500" placeholder="e.g. PIZZA, DESSERT, PASTA">
                        @error('category')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" class="w-full border @error('price') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                        @error('price')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                        <input type="number" name="stock" min="0" value="{{ old('stock') }}" class="w-full border @error('stock') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                        @error('stock')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" rows="3" class="w-full border @error('description') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" class="w-full border @error('status') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="on_shelf" {{ old('status', 'on_shelf') === 'on_shelf' ? 'selected' : '' }}>On Shelf</option>
                            <option value="off_shelf" {{ old('status') === 'off_shelf' ? 'selected' : '' }}>Off Shelf</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                        <input type="file" name="image" accept="image/*" class="w-full border @error('image') border-red-500 @else border-gray-300 @enderror rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <p class="text-xs text-gray-500 mt-1">Select a file to upload (PNG, JPG, GIF up to 2MB)</p>
                        @error('image')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-2 flex justify-end">
                        <x-primary-button>Create Item</x-primary-button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>

    @if($canManage)
        @foreach ($menuItems as $item)
            <div id="editModal{{ $item->id }}" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-900">Edit Item: {{ $item->name }}</h3>
                        <button onclick="closeEditModal({{ $item->id }})" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form action="{{ route('merchant.menu-items.update', [$restaurant, $item]) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                <input type="text" name="name" value="{{ $item->name }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <input type="text" name="category" value="{{ $item->category }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                                <input type="number" step="0.01" name="price" value="{{ $item->price }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Stock</label>
                                <input type="number" name="stock" min="0" value="{{ $item->stock }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea name="description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">{{ $item->description }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                    <option value="on_shelf" @selected($item->status === 'on_shelf')>On Shelf</option>
                                    <option value="off_shelf" @selected($item->status === 'off_shelf')>Off Shelf</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
                                <input type="file" name="image" accept="image/*" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                @if($item->image_path)
                                    <p class="text-xs text-gray-500 mt-1">Current: {{ basename($item->image_path) }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Upload a new image to replace the current one</p>
                                @else
                                    <p class="text-xs text-gray-500 mt-1">No image selected. Select a file to upload.</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 pt-4">
                            <button type="button" onclick="closeEditModal({{ $item->id }})" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Cancel</button>
                            <x-primary-button>Save Changes</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    @endif

    <script>
        function openEditModal(id) {
            document.getElementById('editModal' + id).classList.remove('hidden');
        }
        function closeEditModal(id) {
            document.getElementById('editModal' + id).classList.add('hidden');
        }
    </script>
</x-app-layout>
