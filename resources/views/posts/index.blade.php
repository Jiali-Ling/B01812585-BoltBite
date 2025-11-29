<x-public-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Posts') }}
            </h2>
            @auth
                @can('create', App\Models\Post::class)
                    <a href="{{ route('posts.create') }}" class="bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                        Create New Post
                    </a>
                @endcan
            @endauth
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
                <div class="p-6 text-gray-800">
                    @forelse ($posts as $post)
                        <article class="mb-6 p-4 border-b">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <h3 class="text-2xl font-bold mb-2">
                                        <a href="{{ route('posts.show', $post) }}" class="text-amber-700 hover:text-orange-600 transition-colors">
                                            {{ $post->title }}
                                        </a>
                                    </h3>
                                    <p class="text-gray-600 mb-2">{{ $post->description }}</p>
                                    <div class="text-sm text-gray-500">
                                        By {{ $post->user->name }} | {{ $post->created_at->diffForHumans() }}
                                        @if ($post->status === 'draft')
                                            <span class="bg-amber-100 text-amber-700 px-2 py-1 rounded-full text-xs ml-2 font-medium">Draft</span>
                                        @endif
                                    </div>
                                </div>
                                @if ($post->path)
                                    <img src="{{ asset('storage/' . $post->path) }}" alt="{{ $post->title }}" class="w-32 h-32 object-cover rounded-lg shadow-md ml-4 border-2 border-amber-100">
                                @endif
                            </div>
                            <div class="mt-4 flex gap-3">
                                <a href="{{ route('posts.show', $post) }}" class="text-amber-600 hover:text-orange-600 font-medium transition-colors">Read more →</a>
                                @can('update', $post)
                                    <a href="{{ route('posts.edit', $post) }}" class="text-emerald-600 hover:text-emerald-700 font-medium transition-colors">Edit</a>
                                @endcan
                                @can('delete', $post)
                                    <form action="{{ route('posts.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-600 hover:text-rose-700 font-medium transition-colors">Delete</button>
                                    </form>
                                @endcan
                            </div>
                        </article>
                    @empty
                        <p class="text-gray-500">No posts found.</p>
                    @endforelse

                    <div class="mt-6">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-public-layout>
