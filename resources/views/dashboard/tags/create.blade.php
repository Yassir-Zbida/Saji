@extends('layouts.admin')

@section('title', 'Create Tag')

@section('content')
    <div class="container mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Create Tag</h1>
                <p class="mt-1 text-sm text-gray-500">Add a new tag to your store</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.tags') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="ri-arrow-left-line mr-2"></i>
                    Back to Tags
                </a>
            </div>
        </div>

        <!-- Create Tag Form -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-lg font-medium text-gray-900">Tag Information</h3>
                <p class="mt-1 text-sm text-gray-500">Fill in the details for the new tag</p>
            </div>

            <form action="{{ route('tags.store') }}" method="POST" class="p-6 space-y-6" x-data="tagFormData()">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-600">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="block w-full rounded-md border p-2 border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 sm:text-sm @error('name') border-red-300 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                            class="block w-full rounded-md border p-2 border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 sm:text-sm @error('slug') border-red-300 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Leave empty to auto-generate from name</p>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select name="type" id="type"
                            class="block w-full rounded-md border p-2 px-2 border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 sm:text-sm @error('type') border-red-300 @enderror">
                            <option value="product" {{ old('type') == 'product' ? 'selected' : '' }}>Product</option>
                            <option value="blog" {{ old('type') == 'blog' ? 'selected' : '' }}>Blog</option>
                            <option value="content" {{ old('type') == 'content' ? 'selected' : '' }}>Content</option>
                            <option value="custom" {{ old('type') == 'custom' ? 'selected' : '' }}>Custom</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Position -->
                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                        <input type="number" name="position" id="position" value="{{ old('position', 0) }}" min="0"
                            class="block w-full rounded-md border p-2 border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 sm:text-sm @error('position') border-red-300 @enderror">
                        @error('position')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Color Picker -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                    <div class="mt-2">
                        <div class="flex flex-wrap gap-3">
                            <template x-for="(colorOption, index) in colors" :key="index">
                                <div class="relative">
                                    <button type="button"
                                        @click="selectColor(colorOption.value)"
                                        :class="[
                                            'w-10 h-10 rounded-full border-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary',
                                            selectedColor === colorOption.value ? 'border-gray-900' : 'border-transparent'
                                        ]"
                                        :style="{ backgroundColor: colorOption.hex }">
                                        <span class="sr-only" x-text="colorOption.label"></span>
                                        <span x-show="selectedColor === colorOption.value" class="absolute inset-0 flex items-center justify-center">
                                            <svg class="h-3 w-3 text-white" viewBox="0 0 12 12" fill="currentColor">
                                                <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
                                            </svg>
                                        </span>
                                    </button>
                                    <span class="block text-xs text-center mt-1" x-text="colorOption.label"></span>
                                </div>
                            </template>
                            
                            <!-- Custom Color Option -->
                            <div class="relative">
                                <button type="button"
                                    @click="customColorMode = true"
                                    :class="[
                                        'w-10 h-10 rounded-full border-2 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary',
                                        customColorSelected ? 'border-gray-900' : 'border-transparent'
                                    ]"
                                    :style="{ backgroundColor: customColorHex }">
                                    <span class="sr-only">Custom</span>
                                    <span x-show="customColorSelected" class="absolute inset-0 flex items-center justify-center">
                                        <svg class="h-3 w-3 text-white" viewBox="0 0 12 12" fill="currentColor">
                                            <path d="M3.707 5.293a1 1 0 00-1.414 1.414l1.414-1.414zM5 8l-.707.707a1 1 0 001.414 0L5 8zm4.707-3.293a1 1 0 00-1.414-1.414l1.414 1.414zm-7.414 2l2 2 1.414-1.414-2-2-1.414 1.414zm3.414 2l4-4-1.414-1.414-4 4 1.414 1.414z" />
                                        </svg>
                                    </span>
                                </button>
                                <span class="block text-xs text-center mt-1">Custom</span>
                            </div>
                        </div>
                        
                        <!-- Custom Color Picker -->
                        <div x-show="customColorMode" class="mt-4 p-4 border rounded-md shadow-sm">
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Select Custom Color</label>
                                <div class="flex items-center space-x-3">
                                    <input type="color" x-model="customColorHex" class="h-10 w-16 p-0 border-0"
                                        @change="selectCustomColor()">
                                    <input type="text" x-model="customColorHex" class="block w-32 rounded-md border p-2 border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 sm:text-sm"
                                        placeholder="#000000" 
                                        @input="selectCustomColor()">
                                    <button type="button" 
                                        @click="applyCustomColor()"
                                        class="inline-flex items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-20">
                                        Apply
                                    </button>
                                    <button type="button" 
                                        @click="customColorMode = false"
                                        class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring focus:ring-primary focus:ring-opacity-20">
                                        Cancel
                                    </button>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="color" :value="selectedColorValue">
                        <input type="hidden" name="color_hex" :value="selectedColorHex">
                    </div>
                    
                    <div class="mt-3">
                        <div class="flex items-center space-x-2">
                            <div class="w-6 h-6 rounded-full" :style="{ backgroundColor: selectedColorHex }"></div>
                            <span class="text-sm font-medium" x-text="selectedColorLabel"></span>
                        </div>
                    </div>
                    @error('color')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('color_hex')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="4"
                        class="block w-full rounded-md border p-2 border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 sm:text-sm @error('description') border-red-300 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tag Status -->
                <div class="flex items-center">
                    <div class="flex items-center h-5">
                        <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}
                            class="focus:ring-primary h-4 w-4 text-primary border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_active" class="font-medium text-gray-700">Active</label>
                        <p class="text-gray-500">Make this tag visible on the shop</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 border-t border-gray-200 pt-6">
                    <a href="{{ route('admin.tags') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        Create Tag
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function tagFormData() {
            return {
                selectedColor: '{{ old('color', 'blue') }}',
                customColorMode: false,
                customColorSelected: {{ old('color') == 'custom' ? 'true' : 'false' }},
                customColorHex: '{{ old('color_hex', '#3B82F6') }}',
                colors: [
                    { value: 'red', label: 'Red', hex: '#EF4444' },
                    { value: 'blue', label: 'Blue', hex: '#3B82F6' },
                    { value: 'green', label: 'Green', hex: '#10B981' },
                    { value: 'yellow', label: 'Yellow', hex: '#F59E0B' },
                    { value: 'purple', label: 'Purple', hex: '#8B5CF6' },
                    { value: 'pink', label: 'Pink', hex: '#EC4899' },
                    { value: 'indigo', label: 'Indigo', hex: '#6366F1' },
                    { value: 'gray', label: 'Gray', hex: '#6B7280' },
                    { value: 'black', label: 'Black', hex: '#111827' },
                ],
                
                get selectedColorValue() {
                    return this.customColorSelected ? 'custom' : this.selectedColor;
                },
                
                get selectedColorHex() {
                    return this.customColorSelected ? this.customColorHex : this.getColorHex(this.selectedColor);
                },
                
                get selectedColorLabel() {
                    return this.customColorSelected ? 'Custom' : this.getColorLabel(this.selectedColor);
                },
                
                init() {
                    // Auto-generate slug from name
                    const nameInput = document.getElementById('name');
                    const slugInput = document.getElementById('slug');
                    
                    if (nameInput && slugInput) {
                        // Set data attribute to track if slug was manually edited
                        slugInput.dataset.auto = slugInput.value === '' ? 'true' : 'false';
                        
                        nameInput.addEventListener('input', function() {
                            if (slugInput.value === '' || slugInput.dataset.auto === 'true') {
                                slugInput.value = nameInput.value
                                    .toLowerCase()
                                    .replace(/\s+/g, '-')
                                    .replace(/[^\w\-]+/g, '')
                                    .replace(/\-\-+/g, '-')
                                    .replace(/^-+/, '')
                                    .replace(/-+$/, '');
                                slugInput.dataset.auto = 'true';
                            }
                        });
                        
                        slugInput.addEventListener('input', function() {
                            slugInput.dataset.auto = 'false';
                        });
                    }
                    
                    // Initialize custom color if needed
                    if (this.selectedColor === 'custom') {
                        this.customColorSelected = true;
                    }
                },
                
                selectColor(color) {
                    this.selectedColor = color;
                    this.customColorSelected = false;
                },
                
                selectCustomColor() {
                    // Validate hex color format
                    const hexRegex = /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/;
                    if (!hexRegex.test(this.customColorHex)) {
                        this.customColorHex = '#3B82F6'; // Default to blue if invalid
                    }
                },
                
                applyCustomColor() {
                    this.customColorSelected = true;
                    this.customColorMode = false;
                },
                
                getColorHex(colorValue) {
                    const color = this.colors.find(c => c.value === colorValue);
                    return color ? color.hex : '#3B82F6'; // Default to blue if not found
                },
                
                getColorLabel(colorValue) {
                    const color = this.colors.find(c => c.value === colorValue);
                    return color ? color.label : 'Blue'; // Default to blue if not found
                }
            }
        }
    </script>
@endsection