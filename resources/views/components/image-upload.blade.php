@props(['name' => 'image', 'currentImage' => null, 'required' => false])

<div x-data="imageUpload()" class="w-full">
    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">
            Product Image @if($required)<span class="text-red-500">*</span>@endif
        </label>
        
        <!-- Current Image Preview -->
        @if($currentImage)
        <div class="mb-4">
            <p class="text-sm text-gray-600 mb-2">Current Image:</p>
            <div class="relative inline-block">
                <img src="{{ asset('storage/' . $currentImage) }}" 
                     alt="Current image" 
                     class="w-32 h-32 object-cover rounded-lg border-2 border-gray-300">
                <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-30 transition-all rounded-lg flex items-center justify-center">
                    <span class="text-white text-xs opacity-0 hover:opacity-100">Click below to change</span>
                </div>
            </div>
        </div>
        @endif

        <!-- Upload Area -->
        <div class="mt-2">
            <div @dragover.prevent="dragover = true"
                 @dragleave.prevent="dragover = false"
                 @drop.prevent="handleDrop($event)"
                 :class="dragover ? 'border-amber-500 bg-amber-50' : 'border-gray-300 bg-white'"
                 class="border-2 border-dashed rounded-lg p-6 text-center cursor-pointer transition-all hover:border-amber-400"
                 @click="$refs.fileInput.click()">
                
                <!-- Preview Container -->
                <div x-show="preview" class="mb-4">
                    <img :src="preview" 
                         alt="Preview" 
                         class="mx-auto max-h-48 rounded-lg shadow-md">
                    <button type="button" 
                            @click.stop="clearImage()"
                            class="mt-2 text-sm text-red-600 hover:text-red-800 font-medium">
                        Remove Image
                    </button>
                </div>

                <!-- Upload Icon & Text -->
                <div x-show="!preview">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" 
                              stroke-width="2" 
                              stroke-linecap="round" 
                              stroke-linejoin="round" />
                    </svg>
                    <div class="mt-4">
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold text-amber-600 hover:text-amber-500">Click to upload</span>
                            or drag and drop
                        </p>
                        <p class="text-xs text-gray-500 mt-1">
                            PNG, JPG, GIF up to 10MB
                        </p>
                    </div>
                </div>
            </div>

            <!-- Hidden File Input -->
            <input type="file" 
                   x-ref="fileInput"
                   name="{{ $name }}"
                   accept="image/*"
                   @change="handleFileSelect($event)"
                   class="hidden"
                   {{ $required ? 'required' : '' }}>

            <!-- File Info -->
            <div x-show="fileName" class="mt-2 text-sm text-gray-600">
                <span class="font-medium">Selected:</span> 
                <span x-text="fileName"></span>
                <span x-show="fileSize" class="text-gray-500">
                    (<span x-text="fileSize"></span>)
                </span>
            </div>
        </div>
    </div>
</div>

<script>
function imageUpload() {
    return {
        dragover: false,
        preview: null,
        fileName: '',
        fileSize: '',

        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.processFile(file);
            }
        },

        handleDrop(event) {
            this.dragover = false;
            const file = event.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                this.$refs.fileInput.files = event.dataTransfer.files;
                this.processFile(file);
            }
        },

        processFile(file) {
            this.fileName = file.name;
            this.fileSize = this.formatFileSize(file.size);

            // Create preview
            const reader = new FileReader();
            reader.onload = (e) => {
                this.preview = e.target.result;
            };
            reader.readAsDataURL(file);
        },

        clearImage() {
            this.preview = null;
            this.fileName = '';
            this.fileSize = '';
            this.$refs.fileInput.value = '';
        },

        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        }
    }
}
</script>
