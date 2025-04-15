<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductAttribute;
use App\Models\AttributeValue;
use App\Models\Tag;
use App\Notifications\LowStockNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('category', 'images');

        // Apply filters
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        if ($request->has('stock_status')) {
            if ($request->stock_status === 'in_stock') {
                $query->where('stock_status', 'in_stock');
            } elseif ($request->stock_status === 'out_of_stock') {
                $query->where('stock_status', 'out_of_stock');
            } elseif ($request->stock_status === 'low_stock') {
                $query->whereRaw('stock_quantity <= stock_alert_threshold');
            }
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Sort products
        $sortField = $request->sort ?? 'created_at';
        $sortDirection = $request->direction ?? 'desc';
        $query->orderBy($sortField, $sortDirection);

        $products = $query->paginate(10);
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $attributes = ProductAttribute::with('attributeValues')->get();
        $tags = Tag::all();
        
        return view('products.create', compact('categories', 'attributes', 'tags'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:100|unique:products',
            'stock_quantity' => 'nullable|integer|min:0',
            'stock_status' => 'required|in:in_stock,out_of_stock,on_backorder',
            'stock_alert_threshold' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|array',
            'dimensions.length' => 'nullable|numeric|min:0',
            'dimensions.width' => 'nullable|numeric|min:0',
            'dimensions.height' => 'nullable|numeric|min:0',
            'featured' => 'boolean',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'attributes' => 'nullable|array',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        DB::beginTransaction();

        try {
            // Create product
            $product = Product::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'category_id' => $request->category_id,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'price' => $request->price,
                'sale_price' => $request->sale_price,
                'sku' => $request->sku ?? Str::upper(Str::random(8)),
                'stock_quantity' => $request->stock_quantity,
                'stock_status' => $request->stock_status,
                'stock_alert_threshold' => $request->stock_alert_threshold,
                'weight' => $request->weight,
                'dimensions' => $request->dimensions,
                'featured' => $request->featured ?? false,
                'is_active' => $request->is_active ?? true,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
            ]);

            // Handle images
            if ($request->hasFile('images')) {
                $isPrimary = true; // First image is primary
                
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => $isPrimary,
                    ]);
                    
                    $isPrimary = false;
                }
            }

            // Handle attributes
            if ($request->has('attributes')) {
                foreach ($request->attributes as $attributeId => $valueIds) {
                    if (is_array($valueIds)) {
                        foreach ($valueIds as $valueId) {
                            $product->attributeValues()->attach($valueId, [
                                'product_attribute_id' => $attributeId
                            ]);
                        }
                    }
                }
            }

            // Handle tags
            if ($request->has('tags')) {
                $product->tags()->attach($request->tags);
            }

            DB::commit();

            // Check if stock is below threshold and notify admins
            if ($product->stock_quantity <= $product->stock_alert_threshold) {
                $admins = \App\Models\User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new LowStockNotification($product));
                }
            }

            return redirect()->route('products.index')
                ->with('success', 'Produit créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de la création du produit: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category', 'images', 'attributeValues.productAttribute', 'tags', 'reviews.user');
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load('images', 'attributeValues', 'tags');
        $categories = Category::all();
        $attributes = ProductAttribute::with('attributeValues')->get();
        $tags = Tag::all();
        
        return view('products.edit', compact('product', 'categories', 'attributes', 'tags'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'sku' => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'stock_quantity' => 'nullable|integer|min:0',
            'stock_status' => 'required|in:in_stock,out_of_stock,on_backorder',
            'stock_alert_threshold' => 'nullable|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|array',
            'dimensions.length' => 'nullable|numeric|min:0',
            'dimensions.width' => 'nullable|numeric|min:0',
            'dimensions.height' => 'nullable|numeric|min:0',
            'featured' => 'boolean',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'new_images' => 'nullable|array',
            'new_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'attributes' => 'nullable|array',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        DB::beginTransaction();

        try {
            // Check if stock is going below threshold
            $oldStockQuantity = $product->stock_quantity;
            $newStockQuantity = $request->stock_quantity;
            $stockAlertThreshold = $request->stock_alert_threshold;
            $shouldNotify = $oldStockQuantity > $stockAlertThreshold && $newStockQuantity <= $stockAlertThreshold;

            // Update product
            $product->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'category_id' => $request->category_id,
                'description' => $request->description,
                'short_description' => $request->short_description,
                'price' => $request->price,
                'sale_price' => $request->sale_price,
                'sku' => $request->sku,
                'stock_quantity' => $newStockQuantity,
                'stock_status' => $request->stock_status,
                'stock_alert_threshold' => $stockAlertThreshold,
                'weight' => $request->weight,
                'dimensions' => $request->dimensions,
                'featured' => $request->featured ?? false,
                'is_active' => $request->is_active ?? true,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keywords' => $request->meta_keywords,
            ]);

            // Handle new images
            if ($request->hasFile('new_images')) {
                $hasPrimary = $product->images()->where('is_primary', true)->exists();
                
                foreach ($request->file('new_images') as $image) {
                    $path = $image->store('products', 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_primary' => !$hasPrimary,
                    ]);
                    
                    $hasPrimary = true;
                }
            }

            // Handle deleted images
            if ($request->has('delete_images')) {
                foreach ($request->delete_images as $imageId) {
                    $image = ProductImage::find($imageId);
                    if ($image && $image->product_id == $product->id) {
                        Storage::disk('public')->delete($image->image_path);
                        $image->delete();
                    }
                }
                
                // If primary image was deleted, set a new one
                if (!$product->images()->where('is_primary', true)->exists() && $product->images()->count() > 0) {
                    $product->images()->first()->update(['is_primary' => true]);
                }
            }

            // Handle primary image
            if ($request->has('primary_image')) {
                $product->images()->update(['is_primary' => false]);
                $product->images()->where('id', $request->primary_image)->update(['is_primary' => true]);
            }

            // Handle attributes
            $product->attributeValues()->detach();
            if ($request->has('attributes')) {
                foreach ($request->attributes as $attributeId => $valueIds) {
                    if (is_array($valueIds)) {
                        foreach ($valueIds as $valueId) {
                            $product->attributeValues()->attach($valueId, [
                                'product_attribute_id' => $attributeId
                            ]);
                        }
                    }
                }
            }

            // Handle tags
            $product->tags()->sync($request->tags ?? []);

            DB::commit();

            // Send notification if stock went below threshold
            if ($shouldNotify) {
                $admins = \App\Models\User::where('role', 'admin')->get();
                foreach ($admins as $admin) {
                    $admin->notify(new LowStockNotification($product));
                }
            }

            return redirect()->route('products.index')
                ->with('success', 'Produit mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour du produit: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Check if product has any orders
        if ($product->orderItems()->count() > 0) {
            return redirect()->route('products.index')
                ->with('error', 'Impossible de supprimer ce produit car il est associé à des commandes.');
        }

        DB::beginTransaction();

        try {
            // Delete images from storage
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            // Delete product and related data
            $product->images()->delete();
            $product->attributeValues()->detach();
            $product->tags()->detach();
            $product->reviews()->delete();
            $product->delete();

            DB::commit();

            return redirect()->route('products.index')
                ->with('success', 'Produit supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('products.index')
                ->with('error', 'Une erreur est survenue lors de la suppression du produit: ' . $e->getMessage());
        }
    }

    /**
     * Update product stock.
     */
    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock_quantity' => 'required|integer|min:0',
            'stock_status' => 'required|in:in_stock,out_of_stock,on_backorder',
        ]);

        $oldStockQuantity = $product->stock_quantity;
        $newStockQuantity = $request->stock_quantity;
        $stockAlertThreshold = $product->stock_alert_threshold;
        $shouldNotify = $oldStockQuantity > $stockAlertThreshold && $newStockQuantity <= $stockAlertThreshold;

        $product->update([
            'stock_quantity' => $newStockQuantity,
            'stock_status' => $request->stock_status,
        ]);

        // Send notification if stock went below threshold
        if ($shouldNotify) {
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new LowStockNotification($product));
            }
        }

        return redirect()->route('products.show', $product)
            ->with('success', 'Stock mis à jour avec succès.');
    }
}