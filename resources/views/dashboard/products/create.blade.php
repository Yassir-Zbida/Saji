@extends('layouts.admin')

@section('title', 'Create Product')

@section('content')
    <div class="container mx-auto" x-data="productFormData()">
        <!-- Page Header -->
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="product-form" class="space-y-6">
            @csrf
            
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">Create Product</h1>
                    <p class="mt-1 text-sm text-gray-500">Add a new product to your inventory</p>
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
                        Save Product
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
                                <input type="text" name="name" id="name" required autofocus
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
                                    placeholder="Enter detailed product description"></textarea>
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
                                    placeholder="Enter a brief summary (displayed in listings)"></textarea>
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
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
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
                                        <input type="number" name="price" id="price" required step="0.01" min="0"
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
                                <input type="text" name="sku" id="sku" 
                                    class="block w-full px-4 py-3 rounded-md border border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                    placeholder="Enter unique product SKU (leave empty for auto-generation)">
                                <p class="mt-1 text-xs text-gray-500">Leave empty to auto-generate</p>
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
                                    <input type="number" name="stock_quantity" id="stock_quantity" min="0" step="1" value="0"
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
                                        <option value="in_stock">In Stock</option>
                                        <option value="out_of_stock">Out of Stock</option>
                                        <option value="on_backorder">On Backorder</option>
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
                                    <input type="number" name="stock_alert_threshold" id="stock_alert_threshold" min="0" step="1" value="5"
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
                                    <input type="number" name="dimensions[length]" id="dimensions_length" step="0.1" min="0"
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
                                    <input type="number" name="dimensions[width]" id="dimensions_width" step="0.1" min="0"
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
                                    <input type="number" name="dimensions[height]" id="dimensions_height" step="0.1" min="0"
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
                            <div 
                                x-data="{ 
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
                                }"
                            >
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Product Images
                                </label>
                                
                                <!-- Drop Zone -->
                                <div class="mt-2 border-2 border-dashed border-gray-200 rounded-lg p-6 text-center hover:border-primary transition-colors duration-200 cursor-pointer" @click="$refs.fileInput.click()">
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
                                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-6 gap-4 mt-2" x-show="previewUrls.length > 0">
                                        <template x-for="(url, index) in previewUrls" :key="index">
                                            <div class="relative group">
                                                <div class="h-28 bg-gray-100 rounded-md overflow-hidden border border-gray-200">
                                                    <img :src="url" class="h-full w-full object-cover object-center">
                                                </div>
                                                <div class="absolute inset-0 bg-black bg-opacity-40 rounded-md opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                    <span class="text-xs text-white bg-primary px-2 py-1 rounded">Image <span x-text="index + 1"></span></span>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                
                                <input 
                                    type="file" 
                                    name="images[]" 
                                    id="images" 
                                    multiple 
                                    accept="image/png, image/jpeg, image/gif" 
                                    class="hidden" 
                                    x-ref="fileInput"
                                    @change="handleFileChange"
                                >
                                
                                <p class="mt-2 text-xs text-gray-500 flex items-center">
                                    <i class="ri-information-line mr-1"></i>
                                    First image will be used as the product thumbnail
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
                            @if(count($attributes) > 0)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">
                                    Product Attributes
                                </label>
                                
                                <div class="space-y-4">
                                    @foreach($attributes as $attribute)
                                        <div class="border border-gray-200 rounded-md p-4">
                                            <h5 class="font-medium text-gray-700 mb-3">{{ $attribute->name }}</h5>
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                                @foreach($attribute->values as $value)
                                                    <div class="flex items-center">
                                                        <input type="checkbox" name="attributes[{{ $attribute->id }}][]" 
                                                            id="attr_{{ $attribute->id }}_{{ $value->id }}" 
                                                            value="{{ $value->id }}"
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
                                
                                @if(count($tags) > 0)
                                <div class="flex flex-wrap gap-2">
                                    @foreach($tags as $tag)
                                        <label class="inline-flex items-center px-3 py-1.5 border border-gray-200 rounded-full text-sm cursor-pointer hover:bg-gray-50 hover:border-gray-300 transition-colors">
                                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}" class="h-4 w-4 mr-2 text-primary focus:ring-primary border-gray-200 rounded">
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
                    
                    <!-- SEO Card -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 flex items-center">
                            <i class="ri-global-line text-xl text-primary mr-3"></i>
                            <h3 class="font-medium text-gray-900">SEO Information</h3>
                        </div>
                        <div class="p-5 space-y-5">
                            <!-- Meta Title -->
                            <div>
                                <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">
                                    Meta Title
                                </label>
                                <input type="text" name="meta_title" id="meta_title" 
                                    class="block w-full px-4 py-3 rounded-md border border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                    placeholder="Enter meta title">
                                @error('meta_title')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Meta Description -->
                            <div>
                                <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">
                                    Meta Description
                                </label>
                                <textarea name="meta_description" id="meta_description" rows="3" 
                                    class="block w-full px-4 py-3 rounded-md border border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                    placeholder="Enter meta description"></textarea>
                                @error('meta_description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Meta Keywords -->
                            <div>
                                <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">
                                    Meta Keywords
                                </label>
                                <input type="text" name="meta_keywords" id="meta_keywords" 
                                    class="block w-full px-4 py-3 rounded-md border border-gray-200 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50"
                                    placeholder="keyword1, keyword2, keyword3">
                                <p class="mt-1 text-xs text-gray-500">Separate keywords with commas</p>
                                @error('meta_keywords')
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
                                        <input type="checkbox" name="is_active" id="is_active" checked value="1"
                                            class="absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer checked:right-0 duration-200 ease-in checked:border-primary right-6"
                                        />
                                        <label for="is_active" class="block overflow-hidden h-5 rounded-full bg-gray-300 cursor-pointer"></label>
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
                                            class="absolute block w-5 h-5 rounded-full bg-white border-4 appearance-none cursor-pointer checked:right-0 duration-200 ease-in checked:border-primary right-6"
                                        />
                                        <label for="featured" class="block overflow-hidden h-5 rounded-full bg-gray-300 cursor-pointer"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Visibility Card -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 flex items-center">
                            <i class="ri-eye-line text-xl text-primary mr-3"></i>
                            <h3 class="font-medium text-gray-900">Visibility</h3>
                        </div>
                        <div class="p-5">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                                <div class="flex items-start">
                                    <i class="ri-information-line text-yellow-500 mt-0.5 mr-2"></i>
                                    <div>
                                        <p class="text-sm text-yellow-700">This product will be visible in the store once saved.</p>
                                        <p class="text-xs text-yellow-600 mt-1">You can change the visibility status in the product settings.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Publish Actions Card -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 flex items-center">
                            <i class="ri-save-line text-xl text-primary mr-3"></i>
                            <h3 class="font-medium text-gray-900">Save Product</h3>
                        </div>
                        <div class="p-5 space-y-3">
                            <button type="submit" name="action" value="save"
                                class="w-full flex justify-center items-center px-5 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                <i class="ri-save-line mr-2"></i>
                                Save Product
                            </button>
                            
                            <button type="submit" name="action" value="save_draft"
                                class="w-full flex justify-center items-center px-5 py-3 border border-gray-200 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                <i class="ri-draft-line mr-2"></i>
                                Save as Draft
                            </button>
                        </div>
                    </div>
                    
                    <!-- Help Card -->
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 flex items-center">
                            <i class="ri-question-line text-xl text-primary mr-3"></i>
                            <h3 class="font-medium text-gray-900">Help</h3>
                        </div>
                        <div class="p-5">
                            <div class="text-sm text-gray-600 space-y-3">
                                <p class="flex items-start">
                                    <i class="ri-checkbox-circle-line text-green-500 mt-0.5 mr-2"></i>
                                    <span>Fill in all required fields marked with *</span>
                                </p>
                                <p class="flex items-start">
                                    <i class="ri-checkbox-circle-line text-green-500 mt-0.5 mr-2"></i>
                                    <span>Upload at least one product image</span>
                                </p>
                                <p class="flex items-start">
                                    <i class="ri-checkbox-circle-line text-green-500 mt-0.5 mr-2"></i>
                                    <span>Set price and inventory options</span>
                                </p>
                                <p class="flex items-start">
                                    <i class="ri-checkbox-circle-line text-green-500 mt-0.5 mr-2"></i>
                                    <span>Add product attributes if needed</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
<script>
    function productFormData() {
        return {
            init() {
                // Initialize Alpine.js data for the form
                this.setupFormSubmit();
            },
            
            setupFormSubmit() {
                // Get the form element
                const form = document.getElementById('product-form');
                
                // Add event listener for form submission
                form.addEventListener('submit', function(e) {
                    // Show loading state or disable submit button if needed
                    const submitButtons = form.querySelectorAll('button[type="submit"]');
                    submitButtons.forEach(button => {
                        button.disabled = true;
                        // Add loading spinner or text
                        const originalText = button.innerHTML;
                        button.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Saving...';
                        
                        // Restore button state if form submission fails
                        setTimeout(() => {
                            if (button.disabled) {
                                button.disabled = false;
                                button.innerHTML = originalText;
                            }
                        }, 8000); // 8 second timeout as a safeguard
                    });
                });
            }
        }
    }
</script>
@endsection