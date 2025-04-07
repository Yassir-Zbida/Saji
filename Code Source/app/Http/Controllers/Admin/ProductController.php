<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Afficher la liste des produits
     */
    public function index()
    {
        $products = Product::with('category', 'primaryImage')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /**
     * Afficher le formulaire de création de produit
     */
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Enregistrer un nouveau produit
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'quantity' => 'required|integer|min:0',
            'stock_alert_threshold' => 'required|integer|min:1',
            'description' => 'required',
            'sku' => 'required|unique:products,sku',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'is_online' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Créer le slug
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;

        // S'assurer que le slug est unique
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        // Créer le produit
        $product = Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'quantity' => $request->quantity,
            'stock_alert_threshold' => $request->stock_alert_threshold,
            'is_available' => $request->boolean('is_available', true),
            'is_featured' => $request->boolean('is_featured', false),
            'is_online' => $request->boolean('is_online', true),
            'sku' => $request->sku,
            'features' => $request->features ?? [],
            'category_id' => $request->category_id,
        ]);

        // Gérer les images
        if ($request->hasFile('images')) {
            $isPrimary = true; // La première image sera principale
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => $isPrimary,
                ]);
                
                $isPrimary = false; // Les autres images ne sont pas principales
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit créé avec succès.');
    }

    /**
     * Afficher un produit spécifique
     */
    public function show(Product $product)
    {
        $product->load('category', 'images');
        return view('admin.products.show', compact('product'));
    }

    /**
     * Afficher le formulaire de modification d'un produit
     */
    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $product->load('images');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Mettre à jour un produit existant
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'quantity' => 'required|integer|min:0',
            'stock_alert_threshold' => 'required|integer|min:1',
            'description' => 'required',
            'sku' => 'required|unique:products,sku,' . $product->id,
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'is_online' => 'boolean',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'delete_images' => 'nullable|array',
            'primary_image' => 'nullable|exists:product_images,id',
        ]);

        // Mettre à jour le produit
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'quantity' => $request->quantity,
            'stock_alert_threshold' => $request->stock_alert_threshold,
            'is_available' => $request->boolean('is_available', true),
            'is_featured' => $request->boolean('is_featured', false),
            'is_online' => $request->boolean('is_online', true),
            'sku' => $request->sku,
            'features' => $request->features ?? [],
            'category_id' => $request->category_id,
        ]);

        // Supprimer les images sélectionnées
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = ProductImage::find($imageId);
                if ($image) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        // Définir l'image principale
        if ($request->has('primary_image')) {
            ProductImage::where('product_id', $product->id)
                ->update(['is_primary' => false]);
                
            ProductImage::where('id', $request->primary_image)
                ->update(['is_primary' => true]);
        }

        // Ajouter de nouvelles images
        if ($request->hasFile('images')) {
            $hasPrimary = $product->images()->where('is_primary', true)->exists();
            
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_primary' => !$hasPrimary, // Si pas d'image principale, la première est principale
                ]);
                
                $hasPrimary = true; // Les autres images ne sont pas principales
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * Supprimer un produit
     */
    public function destroy(Product $product)
    {
        // Supprimer les images du stockage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        
        // Supprimer le produit
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Produit supprimé avec succès.');
    }
}