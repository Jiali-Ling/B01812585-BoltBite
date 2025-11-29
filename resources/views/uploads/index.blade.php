<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Uploads
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="bg-green-50 border-l-4 border-green-400 text-green-800 px-4 py-3 rounded-r mb-6 shadow-sm">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if($uploads->count() > 0)
                        <ol type="1">
                            @foreach ($uploads as $index => $upload)
                                <li value="{{ $index + 1 }}" class="mb-2 pb-2">
                                    @php
                                        $imageUrl = $upload['path'];
                                        if (strpos($upload['path'], 'images/') === 0) {
                                            $imageUrl = asset($upload['path']);
                                        } elseif (strpos($upload['path'], 'storage/') === 0) {
                                            $imageUrl = asset($upload['path']);
                                        } elseif (strpos($upload['path'], 'uploads/') === 0 || strpos($upload['path'], 'menu-items/') === 0) {
                                            $imageUrl = asset('storage/' . $upload['path']);
                                        } else {
                                            $imageUrl = asset($upload['path']);
                                        }
                                    @endphp
                                    <a href="{{ $imageUrl }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                        {{ $upload['origName'] }}
                                    </a>
                                    @if($upload['title'])
                                        <span class="ml-2">{{ $upload['title'] }}</span>
                                    @endif
                                    @if($upload['user'])
                                        <span class="ml-2 text-gray-600">{{ $upload['user']->name }}</span>
                                    @endif
                                    @auth
                                        @if(auth()->id() === $upload['user_id'] || (auth()->user() && auth()->user()->isAdmin()))
                                            @if($upload['type'] === 'upload')
                                                <a href="{{ route('uploads.edit', $upload['model']) }}" class="ml-2 text-emerald-600 hover:text-emerald-700">Edit</a>
                                                <form action="{{ route('uploads.destroy', $upload['model']) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Are you sure you want to delete this file?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-600 hover:text-rose-700">Delete</button>
                                                </form>
                                            @elseif($upload['type'] === 'menuitem')
                                                <a href="{{ route('merchant.menu.index', $upload['model']->restaurant) }}" class="ml-2 text-emerald-600 hover:text-emerald-700">Edit</a>
                                                <form action="{{ route('merchant.menu-items.destroy', ['restaurant' => $upload['model']->restaurant, 'menuItem' => $upload['model']]) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Are you sure you want to delete this menu item?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-rose-600 hover:text-rose-700">Delete</button>
                                                </form>
                                            @elseif($upload['type'] === 'restaurant')
                                                <a href="{{ route('uploads.restaurant.edit', $upload['model']->id) }}" class="ml-2 text-emerald-600 hover:text-emerald-700">Edit</a>
                                            @endif
                                        @endif
                                    @endauth
                                </li>
                            @endforeach
                        </ol>
                    @else
                        <p class="text-gray-500">No files found.</p>
                    @endif
                    
                    @auth
                        @if(auth()->user()->isMerchant() || auth()->user()->isAdmin())
                            <div class="mt-6">
                                <a href="{{ route('uploads.create') }}" class="text-blue-600 hover:text-blue-800 underline">Add file</a>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
