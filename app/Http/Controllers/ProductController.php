<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\ProductAttribute;
use App\Models\AttributeValue;
use App\Models\Tag;
use App\Notifications\LowStockNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::with('category', 'images')->latest()->paginate(10);
        return view('dashboard.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new product.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $attributes = ProductAttribute::with('values')->get();
        $tags = Tag::all();
        return view('dashboard.products.create', compact('categories', 'attributes', 'tags'));
    }

    /**
     * Store a newly created product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Ajout de journalisation pour le débogage
        \Log::info('Méthode store appelée');
        \Log::info('Données de la requête:', $request->all());

        try {
            $validated = $request->validate([
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
                // Retirez temporairement les validations pour les colonnes manquantes
                // 'weight' => 'nullable|numeric|min:0',
                // 'dimensions' => 'nullable|array',
                // 'dimensions.length' => 'nullable|numeric|min:0',
                // 'dimensions.width' => 'nullable|numeric|min:0',
                // 'dimensions.height' => 'nullable|numeric|min:0',
                'featured' => 'nullable|boolean',
                'is_active' => 'nullable|boolean',
                // 'meta_title' => 'nullable|string|max:255',
                // 'meta_description' => 'nullable|string|max:500',
                // 'meta_keywords' => 'nullable|string|max:255',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'attributes' => 'nullable|array',
                'tags' => 'nullable|array',
                'tags.*' => 'exists:tags,id',
            ]);
            
            \Log::info('Validation réussie');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation échouée: ' . $e->getMessage());
            \Log::error('Erreurs de validation: ', $e->errors());
            return back()->withErrors($e->errors())->withInput();
        }

        DB::beginTransaction();

        try {
            \Log::info('Création du produit');
            
            // Création du produit avec uniquement les colonnes existantes
            $product = Product::create([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'category_id' => $request->category_id,
                'description' => $request->description,
                'price' => $request->price,
                'sale_price' => $request->sale_price,
                'sku' => $request->sku ?? Str::upper(Str::random(8)),
                'quantity' => $request->stock_quantity ?? 0, // Notez que le modèle utilise 'quantity' et non 'stock_quantity'
                'is_active' => $request->has('is_active') ? 1 : 0,
                // 'meta_title' => $request->meta_title,
                // 'meta_description' => $request->meta_description,
                // 'meta_keywords' => $request->meta_keywords,
                // Retirez temporairement les colonnes manquantes
                // 'weight' => $request->weight,
                // 'length' => $request->dimensions['length'] ?? null,
                // 'width' => $request->dimensions['width'] ?? null,
                // 'height' => $request->dimensions['height'] ?? null,
                // 'is_featured' => $request->has('featured') ? 1 : 0,
            ]);
            
            \Log::info('Produit créé avec ID: ' . $product->id);

            // Gestion des images
            if ($request->hasFile('images')) {
                \Log::info('Traitement des images');
                $isPrimary = true; // La première image est principale
                
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    
                    // Vérifiez si la table utilise 'image_path' ou 'path'
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path, // Utilisez 'image_path' au lieu de 'path'
                        'is_primary' => $isPrimary,
                    ]);
                    
                    // Si c'est la première image, mettez-la également comme image principale du produit
                    if ($isPrimary) {
                        $product->update(['image' => $path]);
                    }
                    
                    $isPrimary = false;
                }
            }

            // Gestion des attributs
            if ($request->has('attributes')) {
                \Log::info('Traitement des attributs');
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

            // Gestion des tags
            if ($request->has('tags')) {
                \Log::info('Traitement des tags');
                $product->tags()->attach($request->tags);
            }

            DB::commit();
            \Log::info('Transaction validée avec succès');

            // Vérification du stock et notification
            if ($product->quantity <= ($request->stock_alert_threshold ?? 5)) {
                $admins = \App\Models\User::where('role', 'admin')->get();
                
            }

            return redirect()->route('admin.products')
                ->with('success', 'Produit créé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur lors de la création du produit: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Une erreur est survenue lors de la création du produit: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product->load('category', 'images', 'tags', 'attributeValues.attribute');
        return view('dashboard.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        $attributes = ProductAttribute::with('values')->get();
        $tags = Tag::all();
        $product->load('images', 'tags', 'attributeValues');
        
        // Préparer les attributs sélectionnés pour l'affichage
        $selectedAttributeValues = [];
        foreach ($product->attributeValues as $attributeValue) {
            $selectedAttributeValues[$attributeValue->pivot->product_attribute_id][] = $attributeValue->id;
        }
        
        return view('dashboard.products.edit', compact('product', 'categories', 'attributes', 'tags', 'selectedAttributeValues'));
    }

    /**
     * Update the specified product in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        \Log::info('Méthode update appelée');
        \Log::info('Données de la requête:', $request->all());

        try {
            $validated = $request->validate([
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
                // Retirez temporairement les validations pour les colonnes manquantes
                // 'weight' => 'nullable|numeric|min:0',
                // 'dimensions' => 'nullable|array',
                // 'dimensions.length' => 'nullable|numeric|min:0',
                // 'dimensions.width' => 'nullable|numeric|min:0',
                // 'dimensions.height' => 'nullable|numeric|min:0',
                'featured' => 'nullable|boolean',
                'is_active' => 'nullable|boolean',
                // 'meta_title' => 'nullable|string|max:255',
                // 'meta_description' => 'nullable|string|max:500',
                // 'meta_keywords' => 'nullable|string|max:255',
                'images' => 'nullable|array',
                'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
                'attributes' => 'nullable|array',
                'tags' => 'nullable|array',
                'tags.*' => 'exists:tags,id',
            ]);
            
            \Log::info('Validation réussie');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation échouée: ' . $e->getMessage());
            \Log::error('Erreurs de validation: ', $e->errors());
            return back()->withErrors($e->errors())->withInput();
        }

        DB::beginTransaction();

        try {
            \Log::info('Mise à jour du produit');
            
            // Mise à jour du produit avec uniquement les colonnes existantes
            $product->update([
                'name' => $request->name,
                'slug' => Str::slug($request->name),
                'category_id' => $request->category_id,
                'description' => $request->description,
                'price' => $request->price,
                'sale_price' => $request->sale_price,
                'sku' => $request->sku,
                'quantity' => $request->stock_quantity, // Notez que le modèle utilise 'quantity' et non 'stock_quantity'
                'is_active' => $request->has('is_active') ? 1 : 0,
                // Retirez temporairement les colonnes manquantes
                // 'weight' => $request->weight,
                // 'length' => $request->dimensions['length'] ?? null,
                // 'width' => $request->dimensions['width'] ?? null,
                // 'height' => $request->dimensions['height'] ?? null,
                // 'is_featured' => $request->has('featured') ? 1 : 0,
            ]);
            
            \Log::info('Produit mis à jour avec ID: ' . $product->id);

            // Gestion des images
            if ($request->hasFile('images')) {
                \Log::info('Traitement des nouvelles images');
                $hasPrimary = $product->images()->where('is_primary', true)->exists();
                
                foreach ($request->file('images') as $image) {
                    $path = $image->store('products', 'public');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path, // Utilisez 'image_path' au lieu de 'path'
                        'is_primary' => !$hasPrimary,
                    ]);
                    
                    // Si c'est la première image et qu'il n'y a pas d'image principale, mettez-la également comme image principale du produit
                    if (!$hasPrimary) {
                        $product->update(['image' => $path]);
                        $hasPrimary = true;
                    }
                }
            }

            // Gestion des attributs
            if ($request->has('attributes')) {
                \Log::info('Traitement des attributs');
                // Supprimer les anciennes valeurs d'attributs
                $product->attributeValues()->detach();
                
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

            // Gestion des tags
            if ($request->has('tags')) {
                \Log::info('Traitement des tags');
                $product->tags()->sync($request->tags);
            } else {
                $product->tags()->detach();
            }

            DB::commit();
            \Log::info('Transaction validée avec succès');

            // Vérification du stock et notification
            if ($product->quantity <= ($request->stock_alert_threshold ?? 5)) {
                $admins = \App\Models\User::where('role', 'admin')->get();
                // foreach ($admins as $admin) {
                //     $admin->notify(new LowStockNotification($product));
                // }
            }

            return redirect()->route('admin.products')
                ->with('success', 'Produit mis à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur lors de la mise à jour du produit: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour du produit: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified product from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        try {
            DB::beginTransaction();
            
            // Supprimer les images associées
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path); // Utilisez 'image_path' au lieu de 'path'
                $image->delete();
            }
            
            // Supprimer les relations
            $product->attributeValues()->detach();
            $product->tags()->detach();
            
            // Supprimer le produit
            $product->delete();
            
            DB::commit();
            
            return redirect()->route('admin.products')
                ->with('success', 'Produit supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de la suppression du produit: ' . $e->getMessage());
        }
    }

    /**
     * Update the status of a product.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Product $product)
    {
        try {
            $product->update([
                'is_active' => !$product->is_active
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Statut du produit mis à jour avec succès.',
                'is_active' => $product->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la mise à jour du statut du produit: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload an image for the product description.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function uploadImage(Request $request)
    {
        try {
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
            
            $path = $request->file('image')->store('products/editor', 'public');
            
            return response()->json([
                'success' => true,
                'url' => asset('storage/' . $path)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du téléchargement de l\'image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove an image from a product.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function removeImage($id)
    {
        try {
            $image = ProductImage::findOrFail($id);
            $productId = $image->product_id;
            
            // Supprimer le fichier
            Storage::disk('public')->delete($image->image_path); // Utilisez 'image_path' au lieu de 'path'
            
            // Si c'était l'image principale, mettre à jour l'image principale du produit
            if ($image->is_primary) {
                $image->delete();
                
                // Trouver une autre image pour la définir comme principale
                $newPrimaryImage = ProductImage::where('product_id', $productId)->first();
                if ($newPrimaryImage) {
                    $newPrimaryImage->update(['is_primary' => true]);
                    Product::where('id', $productId)->update(['image' => $newPrimaryImage->image_path]); // Utilisez 'image_path' au lieu de 'path'
                } else {
                    Product::where('id', $productId)->update(['image' => null]);
                }
            } else {
                $image->delete();
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Image supprimée avec succès.',
                'redirect_url' => route('admin.products')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la suppression de l\'image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the stock of a product.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateStock(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:0',
                'stock_status' => 'required|in:in_stock,out_of_stock,on_backorder',
            ]);
            
            $product = Product::findOrFail($request->product_id);
            
            $product->update([
                'quantity' => $request->quantity,
                'stock_status' => $request->stock_status,
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Stock mis à jour avec succès.',
                'quantity' => $product->quantity,
                'stock_status' => $product->stock_status
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors de la mise à jour du stock: ' . $e->getMessage()
            ], 500);
        }
    }
}