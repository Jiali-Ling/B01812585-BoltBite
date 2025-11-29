<x-public-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $post->title }}
            </h2>
            <div class="flex gap-2">
                @can('update', $post)
                    <a href="{{ route('posts.edit', $post) }}" 
                        class="bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                        Edit
                    </a>
                @endcan
                @can('delete', $post)
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                            class="bg-gradient-to-r from-rose-500 to-red-500 hover:from-rose-600 hover:to-red-600 text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                            Delete
                        </button>
                    </form>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status') || session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 text-green-800 px-4 py-3 rounded-r mb-6 shadow-sm">
                    {{ session('status') ?? session('success') }}
                </div>
            @endif

            <div class="bg-white/90 backdrop-blur-sm overflow-hidden shadow-lg sm:rounded-xl border border-amber-100">
                <div class="p-6 text-gray-900">
                    <!-- Post Meta -->
                    <div class="text-sm text-gray-500 mb-6 flex items-center gap-4">
                        <span>By {{ $post->user->name }}</span>
                        <span>•</span>
                        <span>{{ $post->created_at->format('F d, Y') }}</span>
                        @if ($post->status === 'draft')
                            <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-xs font-medium">Draft</span>
                        @endif
                    </div>

                    <!-- Featured Image -->
                    @if ($post->path)
                        <div class="mb-6">
                            <img src="{{ asset('storage/' . $post->path) }}" 
                                alt="{{ $post->title }}" 
                                class="w-full h-96 object-cover rounded-xl shadow-lg border-2 border-amber-100">
                        </div>
                    @endif

                    <!-- Description -->
                    <div class="mb-6">
                        <p class="text-xl text-gray-600 italic">{{ $post->description }}</p>
                    </div>

                    <!-- Back Link -->
                    <div class="mt-8 pt-6 border-t border-amber-100">
                        <a href="{{ route('posts.index') }}" 
                            class="text-amber-600 hover:text-orange-600 font-medium transition-colors">
                            ← Back to all posts
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>
