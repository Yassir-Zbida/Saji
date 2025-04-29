@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
    <div class="container mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Edit Category</h1>
                <p class="mt-1 text-sm text-gray-500">Update category information</p>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="{{ route('admin.categories') }}"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                    <i class="ri-arrow-left-line mr-2"></i>
                    Back to Categories
                </a>
            </div>
        </div>

        <!-- Edit Category Form -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-gray-100">
                <h3 class="text-lg font-medium text-gray-900">Category Information</h3>
                <p class="mt-1 text-sm text-gray-500">Update details for "{{ $category->name }}"</p>
            </div>

            <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name <span class="text-red-600">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                            class="block w-full rounded-md border p-2 border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 sm:text-sm @error('name') border-red-300 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Slug -->
                    <div>
                        <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                        <input type="text" name="slug" id="slug" value="{{ old('slug', $category->slug) }}"
                            class="block w-full rounded-md border p-2 border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 sm:text-sm @error('slug') border-red-300 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Leave empty to auto-generate from name</p>
                        @error('slug')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Parent Category -->
                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">Parent Category</label>
                        <select name="parent_id" id="parent_id"
                            class="block w-full rounded-md border p-2 border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 sm:text-sm @error('parent_id') border-red-300 @enderror">
                            <option value="">None (Root Category)</option>
                            @foreach($parentCategories as $parentCategory)
                                <option value="{{ $parentCategory->id }}" {{ old('parent_id', $category->parent_id) == $parentCategory->id ? 'selected' : '' }}>
                                    {{ $parentCategory->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('parent_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Position -->
                    <div>
                        <label for="position" class="block text-sm font-medium text-gray-700 mb-1">Position</label>
                        <input type="number" name="position" id="position" value="{{ old('position', $category->position) }}" min="0"
                            class="block w-full rounded-md border p-2 border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 sm:text-sm @error('position') border-red-300 @enderror">
                        @error('position')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="4"
                        class="block w-full rounded-md p-2 border border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-20 sm:text-sm @error('description') border-red-300 @enderror">{{ old('description', $category->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category Status -->
                <div class="flex items-center">
                    <div class="flex items-center h-5">
                        <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                            class="focus:ring-primary h-4 w-4 text-primary border-gray-300 rounded">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="is_active" class="font-medium text-gray-700">Active</label>
                        <p class="text-gray-500">Make this category visible on the shop</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3 border-t border-gray-200 pt-6">
                    <a href="{{ route('admin.categories') }}"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        Cancel
                    </a>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage() {
            const input = document.getElementById('image');
            const preview = document.getElementById('image_preview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            const preview = document.getElementById('image_preview');
            const removeInput = document.getElementById('remove_image_input');
            
            preview.src = '/images/placeholder-category.jpg';
            removeInput.value = '1';
            
            // Hide the remove button
            document.getElementById('remove_image').style.display = 'none';
        }
    </script>
@endsection