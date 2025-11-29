<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Upload Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h3 class="text-2xl font-bold mb-2">{{ $upload->origName }}</h3>
                        @if($upload->title)
                            <p class="text-lg text-gray-700 mb-2">{{ $upload->title }}</p>
                        @endif
                        @if($upload->content)
                            <p class="text-gray-600 mb-4">{{ $upload->content }}</p>
                        @endif
                        <p class="text-sm text-gray-500">
                            Uploaded by {{ $upload->user->name }} (ID: {{ $upload->user->id }}) on {{ $upload->created_at->format('Y-m-d H:i:s') }}
                        </p>
                    </div>

                    <div class="mb-4">
                        <a href="{{ url('/uploads', [$upload->id, 'file', $upload->origName]) }}" class="text-blue-600 hover:text-blue-800 underline">
                            View File: {{ $upload->id }} {{ $upload->origName }}
                        </a>
                    </div>

                    @if(substr($upload->mimeType, 0, 5) == 'image')
                        <div class="mb-4">
                            <img src="{{ url('/uploads', [$upload->id, 'file', $upload->origName]) }}" alt="{{ $upload->origName }}" class="max-w-full h-auto rounded-lg shadow-md">
                        </div>
                    @endif

                    <div class="mt-6 space-y-2 text-sm text-gray-600">
                        <p><strong>Path:</strong> {{ $upload->path }}</p>
                        <p><strong>MIME Type:</strong> {{ $upload->mimeType }}</p>
                        <p><strong>Created:</strong> {{ $upload->created_at->diffForHumans() }}</p>
                        <p><strong>Updated:</strong> {{ $upload->updated_at->diffForHumans() }}</p>
                    </div>

                    <div class="mt-6 flex gap-3">
                        @auth
                            @if(auth()->id() === $upload->user_id || auth()->user()->isAdmin())
                                <a href="{{ route('uploads.edit', $upload) }}" class="bg-emerald-500 hover:bg-emerald-600 text-white font-semibold py-2 px-6 rounded-lg transition">
                                    Edit
                                </a>
                                <form action="{{ route('uploads.destroy', $upload) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this upload?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-rose-500 hover:bg-rose-600 text-white font-semibold py-2 px-6 rounded-lg transition">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        @endauth
                        <a href="{{ route('uploads.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-6 rounded-lg transition">
                            Back to Uploads
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

