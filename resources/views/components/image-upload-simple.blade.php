@props(['name' => 'image', 'currentImage' => null, 'required' => false])

<div class="w-full">
    <label class="block text-sm font-medium text-gray-700 mb-2">
        Product Image @if($required)<span class="text-red-500">*</span>@endif
    </label>
    
    <!-- Current Image Preview -->
    @if($currentImage)
    <div class="mb-4">
        <p class="text-sm text-gray-600 mb-2">Current Image:</p>
        <div class="relative inline-block">
            <img src="{{ asset('storage/' . $currentImage) }}" 
                 alt="Current image" 
                 class="w-32 h-32 object-cover rounded-lg border-2 border-gray-300 shadow-sm">
        </div>
        <p class="text-xs text-gray-500 mt-1">Upload a new image to replace this one</p>
    </div>
    @endif

    <!-- Simple File Upload -->
    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-amber-400 transition-colors bg-gray-50">
        <div class="space-y-2">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" 
                      stroke-width="2" 
                      stroke-linecap="round" 
                      stroke-linejoin="round" />
            </svg>
            <div class="flex text-sm text-gray-600 justify-center">
                <label for="{{ $name }}-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-amber-600 hover:text-amber-500 focus-within:outline-none px-2 py-1">
                    <span>Click to upload</span>
                    <input id="{{ $name }}-upload" 
                           name="{{ $name }}" 
                           type="file" 
                           accept="image/*" 
                           class="sr-only"
                           onchange="previewImage(this)"
                           {{ $required ? 'required' : '' }}>
                </label>
                <p class="pl-1">or drag and drop</p>
            </div>
            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 20MB (will be auto-compressed)</p>
        </div>
        
        <!-- Preview Area -->
        <div id="{{ $name }}-preview" class="mt-4 hidden">
            <img id="{{ $name }}-preview-img" class="mx-auto max-h-48 rounded-lg shadow-md" alt="Preview">
            <p id="{{ $name }}-file-info" class="mt-2 text-sm text-gray-600"></p>
            <button type="button" onclick="clearImagePreview('{{ $name }}')" class="mt-2 text-sm text-red-600 hover:text-red-800 font-medium">
                Remove
            </button>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    const name = input.name;
    const preview = document.getElementById(name + '-preview');
    const previewImg = document.getElementById(name + '-preview-img');
    const fileInfo = document.getElementById(name + '-file-info');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
            
            const sizeMB = (file.size / (1024 * 1024)).toFixed(2);
            fileInfo.textContent = file.name + ' (' + sizeMB + ' MB)';
        };
        
        reader.readAsDataURL(file);
    }
}

function clearImagePreview(name) {
    const input = document.getElementById(name + '-upload');
    const preview = document.getElementById(name + '-preview');
    
    input.value = '';
    preview.classList.add('hidden');
}

// Drag and drop support
document.addEventListener('DOMContentLoaded', function() {
    const dropZones = document.querySelectorAll('[id$="-upload"]');
    
    dropZones.forEach(function(input) {
        const container = input.closest('.border-dashed');
        
        if (container) {
            container.addEventListener('dragover', function(e) {
                e.preventDefault();
                this.classList.add('border-amber-500', 'bg-amber-50');
            });
            
            container.addEventListener('dragleave', function(e) {
                e.preventDefault();
                this.classList.remove('border-amber-500', 'bg-amber-50');
            });
            
            container.addEventListener('drop', function(e) {
                e.preventDefault();
                this.classList.remove('border-amber-500', 'bg-amber-50');
                
                if (e.dataTransfer.files.length) {
                    input.files = e.dataTransfer.files;
                    previewImage(input);
                }
            });
        }
    });
});
</script>
