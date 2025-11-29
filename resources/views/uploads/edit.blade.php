<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Upload
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('uploads.update', $id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="upload" class="block text-sm font-medium text-gray-700 mb-2">File (optional - leave empty to keep current file)</label>
                            <input type="file" name="upload" id="upload" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-50 file:text-amber-700 hover:file:bg-amber-100">
                            @error('upload')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $title) }}" class="block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content</label>
                            <textarea name="content" id="content" rows="4" class="block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">{{ old('content', $content) }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <button type="submit" class="bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold py-2 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                                Update Upload
                            </button>
                        </div>
                    </form>

                    @if(!empty($id))
                        <br>
                        <a href="{{ url('/uploads', [$id, 'file', $origName]) }}" class="text-blue-600 hover:text-blue-800">
                            {{ $id }} {{ $origName }}
                        </a>
                        <br>
                        @if(substr($mimeType, 0, 5) == 'image')
                            <img height="25%" width="25%" src="{{ url('/uploads', [$id, 'file', $origName]) }}" alt="Preview" class="mt-4 rounded-lg shadow-md">
                        @endif
                        <br>
                    @endif

                    <div class="mt-6">
                        <p class="text-sm text-gray-600 mb-2">ID: {{ $id ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600 mb-2">Path: {{ $path ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600 mb-2">Original Name: {{ $origName ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600 mb-2">MIME Type: {{ $mimeType ?? 'N/A' }}</p>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('uploads.index') }}" class="text-blue-600 hover:text-blue-800">Back to Uploads</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

