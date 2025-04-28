@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
    <div class="container mx-auto" x-data="productFormData()">
        <!-- Page Header -->
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data"
            id="product-form" class="space-y-6">
            @csrf
            @method('PUT')
            <!-- CSRF Token pour les requêtes AJAX -->
            <meta name="csrf-token" content="{{ csrf_token() }}">

            <!-- Affichage des erreurs de validation -->
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-md p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="ri-error-warning-line text-red-500 text-xl"></i>
                        </div>

                        <!-- Product Info Card -->
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-gray-100 flex items-center">
                                <i class="ri-information-line text-xl text-primary mr-3"></i>
                                <h3 class="font-medium text-gray-900">Product Info</h3>
                            </div>
                            <div class="p-5 space-y-4">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Created:</span>
                                    <span
                                        class="text-gray-700 font-medium">{{ $product->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Last Updated:</span>
                                    <span
                                        class="text-gray-700 font-medium">{{ $product->updated_at->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Product ID:</span>
                                    <span class="text-gray-700 font-medium">{{ $product->id }}</span>
                                </div>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">SKU:</span>
                                    <span class="text-gray-700 font-medium">{{ $product->sku ?? 'Not set' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Update Actions Card -->
                        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-gray-100 flex items-center">
                                <i class="ri-save-line text-xl text-primary mr-3"></i>
                                <h3 class="font-medium text-gray-900">Update Product</h3>
                            </div>
                            <div class="p-5 space-y-3">
                                <button type="submit" name="action" value="update"
                                    class="w-full flex justify-center items-center px-5 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                    <i class="ri-save-line mr-2"></i>
                                    Update Product
                                </button>

                                <a href="{{ route('admin.products', $product->id) }}"
                                    class="w-full flex justify-center items-center px-5 py-3 border border-gray-200 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                    <i class="ri-eye-line mr-2"></i>
                                    View Product
                                </a>
                            </div>
                        </div>

                        <!-- Danger Zone Card -->
                        <div class="bg-white rounded-lg border border-red-200 shadow-sm overflow-hidden">
                            <div class="px-5 py-4 border-b border-red-100 flex items-center">
                                <i class="ri-alert-line text-xl text-red-500 mr-3"></i>
                                <h3 class="font-medium text-red-700">Danger Zone</h3>
                            </div>
                            <div class="p-5">
                                <button type="button" onclick="confirmDelete()"
                                    class="w-full flex justify-center items-center px-5 py-3 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                    <i class="ri-delete-bin-line mr-2"></i>
                                    Delete Product
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
        </form>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center hidden transition-all duration-200">
        <div class="bg-white rounded-lg max-w-md w-full p-6 transform scale-90 transition-transform duration-200">
            <div class="text-center">
                <i class="ri-error-warning-line text-red-500 text-5xl mb-4"></i>
                <h3 class="text-xl font-medium text-gray-900 mb-2">Delete Product</h3>
                <p class="text-gray-500 mb-6">Are you sure you want to delete "{{ $product->name }}"? This action cannot be
                    undone.</p>
            </div>
            <div class="flex justify-center space-x-4">
                <button type="button" onclick="closeDeleteModal()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    Cancel
                </button>
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <i class="ri-delete-bin-line mr-2"></i>
                        Delete Product
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

<script>
    function productFormData() {
        return {
            deleteInProgress: false,
            successMessage: null,
            errorMessage: null,

            init() {
                // Initialize Alpine.js data for the form
                this.setupFormSubmit();
                this.setupImageDeletes();
                console.log('Form initialized');
            },

            setupFormSubmit() {
                // Get the form element
                const form = document.getElementById('product-form');

                // Add event listener for form submission
                form.addEventListener('submit', function(e) {
                    console.log('Form submitted');

                    // Show loading state or disable submit button if needed
                    const submitButtons = form.querySelectorAll('button[type="submit"]');
                    submitButtons.forEach(button => {
                        button.disabled = true;
                        // Add loading spinner or text
                        const originalText = button.innerHTML;
                        button.innerHTML =
                            '<i class="ri-loader-4-line animate-spin mr-2"></i> Updating...';

                        // Restore button state if form submission fails
                        setTimeout(() => {
                            if (button.disabled) {
                                button.disabled = false;
                                button.innerHTML = originalText;
                            }
                        }, 8000); // 8 second timeout as a safeguard
                    });
                });
            },

            setupImageDeletes() {
                // Handle image deletion
                document.querySelectorAll('.delete-image').forEach(button => {
                    button.addEventListener('click', async function() {
                            const imageId = this.getAttribute('data-image-id');
                            const imageContainer = this.closest('.relative.group');

                            if (confirm('Are you sure you want to delete this image?')) {
                                // Add loading state
                                const originalText = this.innerHTML;
                                this.innerHTML = '<i class="ri-loader-4-line animate-spin"></i>';
                                this.disabled = true;

                                try {
                                    const response = await fetch(
                                            `/admin/products/${productId}/images/${imageId}`, {
                                                ...
                                            }),
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector(
                                                'meta[name="csrf-token"]').getAttribute(
                                                'content'),
                                            'Accept': 'application/json',
                                            'Content-Type': 'application/json'
                                        }
                                });

                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }

                            const result = await response.json();

                            if (result.success) {
                                // Add fade-out animation
                                imageContainer.classList.add('opacity-0', 'transition-opacity',
                                    'duration-300');

                                // Remove the image from the UI after animation
                                setTimeout(() => {
                                    imageContainer.remove();
                                    if (result.success) {
                                        if (result.redirect_url) {
                                            window.location.href = result.redirect_url;
                                        } 
                                    }

                                    // Show success message
                                    const successAlert = document.createElement('div');
                                    successAlert.classList.add('fixed', 'bottom-4',
                                        'right-4', 'bg-green-50', 'border',
                                        'border-green-200', 'text-green-800',
                                        'rounded-md', 'p-4', 'z-50');
                                    successAlert.innerHTML = `
                                            <div class="flex">
                                                <div class="flex-shrink-0">
                                                    <i class="ri-checkbox-circle-line text-green-500 text-xl"></i>
                                                </div>
                                                <div class="ml-3">
                                                    <p class="text-sm font-medium">Image removed successfully</p>
                                                </div>
                                            </div>
                                        `;

                                    document.body.appendChild(successAlert);

                                    // Remove the alert after 3 seconds
                                    setTimeout(() => {
                                        successAlert.classList.add('opacity-0',
                                            'transition-opacity',
                                            'duration-300');
                                        setTimeout(() => successAlert.remove(),
                                            300);
                                    }, 3000);
                                }, 300);
                            } else {
                                this.innerHTML = originalText;
                                this.disabled = false;
                                showError(result.message || 'Failed to remove image');
                            }
                        } catch (error) {
                            console.error('Error:', error);
                            this.innerHTML = originalText;
                            this.disabled = false;
                            showError('An error occurred while deleting the image');
                        }
                    }
                });
            });
    }
    }
    }

    function showError(message) {
        const errorAlert = document.createElement('div');
        errorAlert.classList.add('fixed', 'bottom-4', 'right-4', 'bg-red-50', 'border', 'border-red-200',
            'text-red-800', 'rounded-md', 'p-4', 'z-50');
        errorAlert.innerHTML = `
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="ri-error-warning-line text-red-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">${message}</p>
                </div>
            </div>
        `;

        document.body.appendChild(errorAlert);

        // Remove the alert after 5 seconds
        setTimeout(() => {
            errorAlert.classList.add('opacity-0', 'transition-opacity', 'duration-300');
            setTimeout(() => errorAlert.remove(), 300);
        }, 5000);
    }

    function confirmDelete() {
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');

        // Add fade-in animation
        setTimeout(() => {
            document.getElementById('deleteModal').querySelector('div').classList.add('scale-100');
            document.getElementById('deleteModal').querySelector('div').classList.remove('scale-90');
        }, 10);
    }

    function closeDeleteModal() {
        // Add fade-out animation
        document.getElementById('deleteModal').querySelector('div').classList.add('scale-90');
        document.getElementById('deleteModal').querySelector('div').classList.remove('scale-100');

        setTimeout(() => {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }, 200);
    }

    // Close modal when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('deleteModal');

        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Close modal when pressing Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeDeleteModal();
            }
        });
    });
</script>
<div class="ml-3">
    <h3 class="text-sm font-medium text-red-800">
        Veuillez corriger les erreurs suivantes:
    </h3>
    <div class="mt-2 text-sm text-red-700">
        <ul class="list-disc pl-5 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
</div>
</div>
@endif

<!-- Affichage des messages de succès -->
@if (session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 rounded-md p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="ri-checkbox-circle-line text-green-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">
                    {{ session('success') }}
                </p>
            </div>
        </div>
    </div>
@endif

<!-- Affichage des messages d'erreur -->
@if (session('error'))
    <div class="bg-red-50 border border-red-200 text-red-800 rounded-md p-4 mb-6">
        <div class="flex">
            <div class="flex-shrink-0">
                <i class="ri-error-warning-line text-red-500 text-xl"></i>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-red-800">
                    {{ session('error') }}
                </p>
            </div>
        </div>
    </div>
@endif

<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Edit Product: {{ $product->name }}</h1>
        <p class="mt-1 text-sm text-gray-500">Update product information and settings</p>
    </div>
    <div class="mt-4 md:mt-0 flex space-x-3">
        <a href="{{ route('admin.products') }}"
            class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
            <i class="ri-arrow-left-line mr-2"></i>
            Back to Products
        </a>
        <button type="submit"
            class="inline-flex items-center px-5 py-2.5 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
            <i class="ri-save-line mr-2"></i>
            Update Product
        </button>
    </div>
</div>

<!-- Product Form Layout -->
<div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
    <!-- Main Content Column (3/4 width) -->
    <div class="lg:col-span-3 space-y-6">
        <!-- General Information Card -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center">
                <i class="ri-information-line text-xl text-primary mr-3"></i>
                <h3 class="font-medium text-gray-900">General Information</h3>
            </div>
            <div class="p-5 space-y-5">
                <!-- Product Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                        Product Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                        value="{{ old('name', $product->name) }}"
                        class="block w-full px-4 py-3 rounded-md border border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        placeholder="Enter product name">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="5"
                        class="block w-full px-4 py-3 rounded-md border border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        placeholder="Enter detailed product description">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Short Description -->
                <div>
                    <label for="short_description" class="block text-sm font-medium text-gray-700 mb-1">
                        Short Description
                    </label>
                    <textarea name="short_description" id="short_description" rows="2"
                        class="block w-full px-4 py-3 rounded-md border border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        placeholder="Enter a brief summary (displayed in listings)">{{ old('short_description', $product->short_description) }}</textarea>
                    <p class="mt-1 text-xs text-gray-500">Maximum 500 characters</p>
                    @error('short_description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select name="category_id" id="category_id" required
                        class="block w-full px-4 py-3 rounded-md border border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                        <option value="">Select a category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Pricing & Inventory Card -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center">
                <i class="ri-price-tag-3-line text-xl text-primary mr-3"></i>
                <h3 class="font-medium text-gray-900">Pricing & Inventory</h3>
            </div>
            <div class="p-5 space-y-5">
                <!-- Price Fields - Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Regular Price -->
                    <div>
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">
                            Regular Price <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500">€</span>
                            </div>
                            <input type="number" name="price" id="price" required step="0.01"
                                min="0" value="{{ old('price', $product->price) }}"
                                class="block w-full pl-7 px-4 py-3 border rounded-md border-gray-200 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                placeholder="0.00">
                        </div>
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sale Price -->
                    <div>
                        <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-1">
                            Sale Price
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500">€</span>
                            </div>
                            <input type="number" name="sale_price" id="sale_price" step="0.01" min="0"
                                value="{{ old('sale_price', $product->sale_price) }}"
                                class="block w-full pl-7 px-4 py-3 rounded-md border border-gray-200 focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                placeholder="0.00">
                        </div>
                        @error('sale_price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- SKU -->
                <div>
                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-1">
                        SKU
                    </label>
                    <input type="text" name="sku" id="sku" value="{{ old('sku', $product->sku) }}"
                        class="block w-full px-4 py-3 rounded-md border border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                        placeholder="Enter unique product SKU">
                    @error('sku')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Inventory Fields - Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <!-- Stock Quantity -->
                    <div>
                        <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-1">
                            Stock Quantity
                        </label>
                        <input type="number" name="stock_quantity" id="stock_quantity" min="0"
                            step="1" value="{{ old('stock_quantity', $product->quantity) }}"
                            class="block w-full px-4 py-3 rounded-md border border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                            placeholder="0">
                        @error('stock_quantity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Stock Status -->
                    <div>
                        <label for="stock_status" class="block text-sm font-medium text-gray-700 mb-1">
                            Stock Status <span class="text-red-500">*</span>
                        </label>
                        <select name="stock_status" id="stock_status" required
                            class="block w-full px-4 py-3 rounded-md border border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            <option value="in_stock"
                                {{ old('stock_status', $product->stock_status) == 'in_stock' ? 'selected' : '' }}>In
                                Stock</option>
                            <option value="out_of_stock"
                                {{ old('stock_status', $product->stock_status) == 'out_of_stock' ? 'selected' : '' }}>
                                Out of Stock</option>
                            <option value="on_backorder"
                                {{ old('stock_status', $product->stock_status) == 'on_backorder' ? 'selected' : '' }}>
                                On Backorder</option>
                        </select>
                        @error('stock_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Low Stock Threshold -->
                    <div>
                        <label for="stock_alert_threshold" class="block text-sm font-medium text-gray-700 mb-1">
                            Low Stock Alert
                        </label>
                        <input type="number" name="stock_alert_threshold" id="stock_alert_threshold" min="0"
                            step="1"
                            value="{{ old('stock_alert_threshold', $product->stock_alert_threshold ?? 5) }}"
                            class="block w-full px-4 py-3 rounded-md border border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                            placeholder="5">
                        @error('stock_alert_threshold')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping & Dimensions Card -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center">
                <i class="ri-truck-line text-xl text-primary mr-3"></i>
                <h3 class="font-medium text-gray-900">Shipping & Dimensions</h3>
            </div>
            <div class="p-5 space-y-5">
                <!-- Weight & Dimensions -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
                    <!-- Weight -->
                    <div>
                        <label for="weight" class="block text-sm font-medium text-gray-700 mb-1">
                            Weight (kg)
                        </label>
                        <input type="number" name="weight" id="weight" step="0.01" min="0"
                            value="{{ old('weight', $product->weight ?? null) }}"
                            class="block w-full px-4 py-3 rounded-md border border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                            placeholder="0.00">
                        @error('weight')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Dimensions (Length, Width, Height) -->
                    <div>
                        <label for="dimensions[length]" class="block text-sm font-medium text-gray-700 mb-1">
                            Length (cm)
                        </label>
                        <input type="number" name="dimensions[length]" id="dimensions_length" step="0.1"
                            min="0" value="{{ old('dimensions.length', $product->length ?? null) }}"
                            class="block w-full px-4 py-3 rounded-md border border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                            placeholder="0.0">
                        @error('dimensions.length')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="dimensions[width]" class="block text-sm font-medium text-gray-700 mb-1">
                            Width (cm)
                        </label>
                        <input type="number" name="dimensions[width]" id="dimensions_width" step="0.1"
                            min="0" value="{{ old('dimensions.width', $product->width ?? null) }}"
                            class="block w-full px-4 py-3 rounded-md border border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                            placeholder="0.0">
                        @error('dimensions.width')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="dimensions[height]" class="block text-sm font-medium text-gray-700 mb-1">
                            Height (cm)
                        </label>
                        <input type="number" name="dimensions[height]" id="dimensions_height" step="0.1"
                            min="0" value="{{ old('dimensions.height', $product->height ?? null) }}"
                            class="block w-full px-4 py-3 rounded-md border border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                            placeholder="0.0">
                        @error('dimensions.height')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Images Card -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center">
                <i class="ri-image-line text-xl text-primary mr-3"></i>
                <h3 class="font-medium text-gray-900">Product Images</h3>
            </div>
            <div class="p-5">
                <!-- Current Images -->
                @if ($product->images->count() > 0)
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Current Images
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-4">
                            @foreach ($product->images as $image)
                                <div class="relative group">
                                    <div class="h-28 bg-gray-100 rounded-md overflow-hidden border border-gray-200">
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                            alt="{{ $product->name }}"
                                            class="h-full w-full object-cover object-center">
                                    </div>
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-40 rounded-md opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <form action="{{ route('admin.products.images.destroy', $image->id) }}"
                                            method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-xs text-white bg-red-500 px-2 py-1 rounded"
                                                onclick="return confirm('Are you sure you want to delete this image?')">Remove</button>
                                        </form>
                                        @if ($image->is_primary)
                                            <span
                                                class="text-xs text-white bg-primary ml-2 px-2 py-1 rounded">Primary</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Upload New Images -->
                <div x-data="{
                    files: [],
                    previewUrls: [],
                    handleFileChange(event) {
                        this.files = event.target.files;
                        this.previewUrls = [];
                
                        for (let i = 0; i < this.files.length; i++) {
                            const file = this.files[i];
                            const reader = new FileReader();
                
                            reader.onload = (e) => {
                                this.previewUrls.push(e.target.result);
                            };
                
                            reader.readAsDataURL(file);
                        }
                    }
                }">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Add New Images
                    </label>

                    <!-- Drop Zone -->
                    <div class="mt-2 border-2 border-dashed border-gray-200 rounded-lg p-6 text-center hover:border-primary transition-colors duration-200 cursor-pointer"
                        @click="$refs.fileInput.click()">
                        <template x-if="previewUrls.length === 0">
                            <div>
                                <i class="ri-image-add-line text-4xl text-gray-400 mb-2"></i>
                                <p class="text-gray-500">
                                    <span class="text-primary font-medium">Click to upload</span> or drag and drop
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    PNG, JPG, GIF up to 2MB
                                </p>
                            </div>
                        </template>

                        <!-- Image Previews -->
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-4 mt-2"
                            x-show="previewUrls.length > 0">
                            <template x-for="(url, index) in previewUrls" :key="index">
                                <div class="relative group">
                                    <div class="h-28 bg-gray-100 rounded-md overflow-hidden border border-gray-200">
                                        <img :src="url" class="h-full w-full object-cover object-center">
                                    </div>
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-40 rounded-md opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <span class="text-xs text-white bg-primary px-2 py-1 rounded">New Image</span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <input type="file" name="images[]" id="images" multiple
                        accept="image/png, image/jpeg, image/gif" class="hidden" x-ref="fileInput"
                        @change="handleFileChange">

                    <p class="mt-2 text-xs text-gray-500 flex items-center">
                        <i class="ri-information-line mr-1"></i>
                        If no primary image exists, the first new image will be set as primary
                    </p>

                    @error('images')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Attributes & Tags Card -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center">
                <i class="ri-price-tag-3-line text-xl text-primary mr-3"></i>
                <h3 class="font-medium text-gray-900">Attributes & Tags</h3>
            </div>
            <div class="p-5 space-y-6">
                <!-- Product Attributes -->
                @if (count($attributes) > 0)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Product Attributes
                        </label>

                        <div class="space-y-4">
                            @foreach ($attributes as $attribute)
                                <div class="border border-gray-200 rounded-md p-4">
                                    <h5 class="font-medium text-gray-700 mb-3">{{ $attribute->name }}</h5>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                        @foreach ($attribute->values as $value)
                                            <div class="flex items-center">
                                                <input type="checkbox" name="attributes[{{ $attribute->id }}][]"
                                                    id="attr_{{ $attribute->id }}_{{ $value->id }}"
                                                    value="{{ $value->id }}"
                                                    {{ in_array($value->id, $selectedAttributeValues[$attribute->id] ?? []) ? 'checked' : '' }}
                                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-200 rounded">
                                                <label for="attr_{{ $attribute->id }}_{{ $value->id }}"
                                                    class="ml-2 block text-sm text-gray-700">
                                                    {{ $value->value }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="ri-price-tag-3-line text-3xl text-gray-300 mb-2"></i>
                        <p class="text-gray-500">No product attributes found</p>
                        <p class="text-sm text-gray-400 mt-1">You can add attributes in the product settings</p>
                    </div>
                @endif

                <!-- Tags -->
                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-3">
                        Tags
                    </label>

                    @if (count($tags) > 0)
                        <div class="flex flex-wrap gap-2">
                            @foreach ($tags as $tag)
                                <label
                                    class="inline-flex items-center px-3 py-1.5 border border-gray-200 rounded-full text-sm cursor-pointer hover:bg-gray-50 hover:border-gray-300 transition-colors">
                                    <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                        {{ in_array($tag->id, $product->tags->pluck('id')->toArray()) ? 'checked' : '' }}
                                        class="h-4 w-4 mr-2 text-primary focus:ring-primary border-gray-200 rounded">
                                    {{ $tag->name }}
                                </label>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4 border border-dashed border-gray-200 rounded-md">
                            <i class="ri-price-tag-3-line text-2xl text-gray-300 mb-1"></i>
                            <p class="text-gray-500">No tags found</p>
                            <p class="text-sm text-gray-400 mt-1">You can add tags in the product settings</p>
                        </div>
                    @endif

                    @error('tags')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>


    </div>

    <!-- Sidebar Column (1/4 width) -->
    <div class="space-y-6">
        <!-- Product Status Card -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center">
                <i class="ri-settings-line text-xl text-primary mr-3"></i>
                <h3 class="font-medium text-gray-900">Product Status</h3>
            </div>
            <div class="p-5 space-y-5">
                <!-- Published Status -->
                <div>
                    <span class="block text-sm font-medium text-gray-700 mb-2">Published Status</span>
                    <div class="bg-gray-50 rounded-md p-3 flex items-center justify-between">
                        <label for="is_active" class="text-sm text-gray-700">
                            Active
                        </label>
                        <div class="relative inline-block w-11 mr-2 align-middle select-none">
                            <input type="checkbox" name="is_active" id="is_active"
                                {{ $product->is_active ? 'checked' : '' }} value="1"
                                class="absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer checked:right-0 duration-200 ease-in checked:border-primary right-6" />
                            <label for="is_active"
                                class="block overflow-hidden h-5 rounded-full bg-gray-300 cursor-pointer"></label>
                        </div>
                    </div>
                </div>

                <!-- Featured Product -->
                <div>
                    <span class="block text-sm font-medium text-gray-700 mb-2">Featured Status</span>
                    <div class="bg-gray-50 rounded-md p-3 flex items-center justify-between">
                        <label for="featured" class="text-sm text-gray-700">
                            Featured
                        </label>
                        <div class="relative inline-block w-11 mr-2 align-middle select-none">
                            <input type="checkbox" name="featured" id="featured" value="1"
                                {{ $product->is_featured ? 'checked' : '' }}
                                class="absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer checked:right-0 duration-200 ease-in checked:border-primary right-6" />
                            <label for="featured"
                                class="block overflow-hidden h-5 rounded-full bg-gray-300 cursor-pointer"></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>

<!-- Product Info Card -->
<div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center">
        <i class="ri-information-line text-xl text-primary mr-3"></i>
        <h3 class="font-medium text-gray-900">Product Info</h3>
    </div>
    <div class="p-5 space-y-4">
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Created:</span>
            <span class="text-gray-700 font-medium">{{ $product->created_at->format('M d, Y') }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Last Updated:</span>
            <span class="text-gray-700 font-medium">{{ $product->updated_at->format('M d, Y') }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Product ID:</span>
            <span class="text-gray-700 font-medium">{{ $product->id }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">SKU:</span>
            <span class="text-gray-700 font-medium">{{ $product->sku ?? 'Not set' }}</span>
        </div>
    </div>
</div>

<!-- Update Actions Card -->
<div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center">
        <i class="ri-save-line text-xl text-primary mr-3"></i>
        <h3 class="font-medium text-gray-900">Update Product</h3>
    </div>
    <div class="p-5 space-y-3">
        <button type="submit" name="action" value="update"
            class="w-full flex justify-center items-center px-5 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
            <i class="ri-save-line mr-2"></i>
            Update Product
        </button>

        <a href="{{ route('admin.products', $product->id) }}"
            class="w-full flex justify-center items-center px-5 py-3 border border-gray-200 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
            <i class="ri-eye-line mr-2"></i>
            View Product
        </a>
    </div>
</div>

<!-- Danger Zone Card -->
<div class="bg-white rounded-lg border border-red-200 shadow-sm overflow-hidden">
    <div class="px-5 py-4 border-b border-red-100 flex items-center">
        <i class="ri-alert-line text-xl text-red-500 mr-3"></i>
        <h3 class="font-medium text-red-700">Danger Zone</h3>
    </div>
    <div class="p-5">
        <button type="button" onclick="confirmDelete()"
            class="w-full flex justify-center items-center px-5 py-3 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
            <i class="ri-delete-bin-line mr-2"></i>
            Delete Product
        </button>
    </div>
</div>
</div>
</div>
</form>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal"
    class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center hidden transition-all duration-200">
    <div class="bg-white rounded-lg max-w-md w-full p-6 transform scale-90 transition-transform duration-200">
        <div class="text-center">
            <i class="ri-error-warning-line text-red-500 text-5xl mb-4"></i>
            <h3 class="text-xl font-medium text-gray-900 mb-2">Delete Product</h3>
            <p class="text-gray-500 mb-6">Are you sure you want to delete "{{ $product->name }}"? This action cannot
                be undone.</p>
        </div>
        <div class="flex justify-center space-x-4">
            <button type="button" onclick="closeDeleteModal()"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                Cancel
            </button>
            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" id="deleteForm">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <i class="ri-delete-bin-line mr-2"></i>
                    Delete Product
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

<script>
    function productFormData() {
        return {
            deleteInProgress: false,
            successMessage: null,
            errorMessage: null,

            init() {
                // Initialize Alpine.js data for the form
                this.setupFormSubmit();
                this.setupImageDeletes();
                console.log('Form initialized');
            },

            setupFormSubmit() {
                // Get the form element
                const form = document.getElementById('product-form');

                // Add event listener for form submission
                form.addEventListener('submit', function(e) {
                    console.log('Form submitted');

                    // Show loading state or disable submit button if needed
                    const submitButtons = form.querySelectorAll('button[type="submit"]');
                    submitButtons.forEach(button => {
                        button.disabled = true;
                        // Add loading spinner or text
                        const originalText = button.innerHTML;
                        button.innerHTML =
                            '<i class="ri-loader-4-line animate-spin mr-2"></i> Updating...';

                        // Restore button state if form submission fails
                        setTimeout(() => {
                            if (button.disabled) {
                                button.disabled = false;
                                button.innerHTML = originalText;
                            }
                        }, 8000); // 8 second timeout as a safeguard
                    });
                });
            },

            setupImageDeletes() {
                // Handle image deletion
                document.querySelectorAll('.delete-image').forEach(button => {
                    button.addEventListener('click', async function() {
                        const imageId = this.getAttribute('data-image-id');
                        const imageContainer = this.closest('.relative.group');

                        if (confirm('Are you sure you want to delete this image?')) {
                            // Add loading state
                            const originalText = this.innerHTML;
                            this.innerHTML = '<i class="ri-loader-4-line animate-spin"></i>';
                            this.disabled = true;

                            try {
                                const response = await fetch(`/admin/products/images/${imageId}`, {
                                    method: 'DELETE',
                                    headers: {
                                        'X-CSRF-TOKEN': document.querySelector(
                                            'meta[name="csrf-token"]').getAttribute(
                                            'content'),
                                        'Accept': 'application/json',
                                        'Content-Type': 'application/json'
                                    }
                                });

                                if (!response.ok) {
                                    throw new Error(`HTTP error! status: ${response.status}`);
                                }

                                const result = await response.json();

                                if (result.success) {
                                    // Add fade-out animation
                                    imageContainer.classList.add('opacity-0', 'transition-opacity',
                                        'duration-300');

                                    // Remove the image from the UI after animation
                                    setTimeout(() => {
                                        imageContainer.remove();

                                        // Show success message
                                        const successAlert = document.createElement('div');
                                        successAlert.classList.add('fixed', 'bottom-4',
                                            'right-4', 'bg-green-50', 'border',
                                            'border-green-200', 'text-green-800',
                                            'rounded-md', 'p-4', 'z-50');
                                        successAlert.innerHTML = `
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="ri-checkbox-circle-line text-green-500 text-xl"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium">Image removed successfully</p>
                            </div>
                        </div>
                    `;

                                        document.body.appendChild(successAlert);

                                        // Remove the alert after 3 seconds
                                        setTimeout(() => {
                                            successAlert.classList.add('opacity-0',
                                                'transition-opacity',
                                                'duration-300');
                                            setTimeout(() => successAlert.remove(),
                                                300);
                                        }, 3000);
                                    }, 300);
                                } else {
                                    this.innerHTML = originalText;
                                    this.disabled = false;
                                    showError(result.message || 'Failed to remove image');
                                }
                            } catch (error) {
                                console.error('Error:', error);
                                this.innerHTML = originalText;
                                this.disabled = false;
                                showError('An error occurred while deleting the image');
                            }
                        }
                    });
                });
            }
        }
    }

    function showError(message) {
        const errorAlert = document.createElement('div');
        errorAlert.classList.add('fixed', 'bottom-4', 'right-4', 'bg-red-50', 'border', 'border-red-200',
            'text-red-800', 'rounded-md', 'p-4', 'z-50');
        errorAlert.innerHTML = `
<div class="flex">
<div class="flex-shrink-0">
<i class="ri-error-warning-line text-red-500 text-xl"></i>
</div>
<div class="ml-3">
<p class="text-sm font-medium">${message}</p>
</div>
</div>
`;

        document.body.appendChild(errorAlert);

        // Remove the alert after 5 seconds
        setTimeout(() => {
            errorAlert.classList.add('opacity-0', 'transition-opacity', 'duration-300');
            setTimeout(() => errorAlert.remove(), 300);
        }, 5000);
    }

    function confirmDelete() {
        document.getElementById('deleteModal').classList.remove('hidden');
        document.getElementById('deleteModal').classList.add('flex');

        // Add fade-in animation
        setTimeout(() => {
            document.getElementById('deleteModal').querySelector('div').classList.add('scale-100');
            document.getElementById('deleteModal').querySelector('div').classList.remove('scale-90');
        }, 10);
    }

    function closeDeleteModal() {
        // Add fade-out animation
        document.getElementById('deleteModal').querySelector('div').classList.add('scale-90');
        document.getElementById('deleteModal').querySelector('div').classList.remove('scale-100');

        setTimeout(() => {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
        }, 200);
    }

    // Close modal when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('deleteModal');

        // Close modal when clicking outside
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Close modal when pressing Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeDeleteModal();
            }
        });
    });
</script>
