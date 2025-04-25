<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ShopController extends Controller
{
    /**
     * Display the shop page with all products.
     */
    public function index(Request $request)
    {
        // Get products with pagination
        $query = Product::where('is_active', 1)
            ->with('category', 'images');
            
        // Check if we're filtering for featured products
        if ($request->has('featured')) {
            $query->where('featured', 1);
        }
        
        // Apply category filter if present
        if ($request->has('category')) {
            $categoryIds = (array) $request->get('category');
            $query->whereHas('category', function($q) use ($categoryIds) {
                $q->whereIn('id', $categoryIds);
            });
        }
        
        // Apply color filter if present
        if ($request->has('color')) {
            $colors = (array) $request->get('color');
            $query->whereHas('attributes', function($q) use ($colors) {
                $q->where('type', 'color')->whereIn('value', $colors);
            });
        }
        
        // Apply price filter if present
        if ($request->has('min_price') || $request->has('max_price')) {
            $minPrice = $request->get('min_price', 0);
            $maxPrice = $request->get('max_price', 100000); // Set a high default max price
            
            $query->where(function($q) use ($minPrice, $maxPrice) {
                $q->whereBetween('price', [$minPrice, $maxPrice])
                  ->orWhereBetween('sale_price', [$minPrice, $maxPrice]);
            });
        }
        
        // Apply sorting (default: newest first)
        $sort = $request->get('sort', 'newest');
        
        switch ($sort) {
            case 'price-low':
                $query->orderBy('price', 'asc');
                break;
            case 'price-high':
                $query->orderBy('price', 'desc');
                break;
            case 'name-asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name-desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }
        
        // Set pagination to exactly 15 products per page
        $products = $query->paginate(15);

        // For AJAX requests (sorting and load more)
        if ($request->ajax() || $request->has('ajax')) {
            $html = view('partails.product-grid', compact('products'))->render();
            
            return response()->json([
                'html' => $html,
                'has_more_pages' => $products->hasMorePages(),
                'current_page' => $products->currentPage(),
                'total' => $products->total()
            ]);
        }
        
        // For regular page requests
        
        // Get featured products
        $featuredProducts = Product::where('is_active', 1)
            ->where('featured', 1)
            ->with('category', 'images')
            ->limit(8)
            ->get();
            
        // Get all categories with product count
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();
                
        return view('shop.index', compact('products', 'featuredProducts', 'categories', 'sort'));
    }

    /**
     * Filter products based on criteria.
     */
    public function filter(Request $request)
    {
        $query = Product::where('is_active', 1)
            ->with('category', 'images');

        // Apply category filter
        if ($request->has('category') && is_array($request->category)) {
            $query->whereIn('category_id', $request->category);
        }

        // Apply price range filter - FIXED VERSION
        if ($request->has('min_price') && $request->min_price !== null && $request->min_price !== '') {
            $query->where('price', '>=', (float) $request->min_price);
        }

        if ($request->has('max_price') && $request->max_price !== null && $request->max_price !== '') {
            $query->where('price', '<=', (float) $request->max_price);
        }

        // Apply sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price-low':
                $query->orderBy('price', 'asc');
                break;
            case 'price-high':
                $query->orderBy('price', 'desc');
                break;
            case 'name-asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name-desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12)->appends($request->all());

        // Get all categories with product count
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();

        // Get featured products
        $featuredProducts = Product::where('is_active', 1)
            ->where('featured', 1)
            ->with('category', 'images')
            ->limit(4)
            ->get();

        return view('shop.index', compact('products', 'categories', 'featuredProducts', 'sort'));
    }

    /**
     * Display all categories.
     */
    public function categories()
    {
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();

        return view('shop.categories', compact('categories'));
    }

    /**
     * Display products in a specific category.
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $products = Product::where('is_active', 1)
            ->where('category_id', $category->id)
            ->with('category', 'images')
            ->paginate(12);

        // Get all categories with product count for the sidebar
        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();

        return view('shop.index', compact('products', 'categories', 'category'));
    }

    /**
     * Display a specific product.
     */
    public function product($id)
    {
        \Log::info('Product page requested with ID/slug: ' . $id);
        
        try {
            // Vérifier si l'identifiant est numérique (ID) ou alphanumérique (slug)
            $product = is_numeric($id) 
                ? Product::findOrFail($id) 
                : Product::where('slug', $id)->firstOrFail();
            
            // Charger les relations nécessaires
            $product->load(['category', 'images', 'attributeValues.attribute', 'reviews.user', 'tags']);
            
            \Log::info('Product loaded successfully: ID=' . $product->id . ', Name=' . $product->name);
            
            // Récupérer les produits associés
            $relatedProducts = collect([]);
            
            if ($product->category_id) {
                $relatedProducts = Product::where('category_id', $product->category_id)
                    ->where('id', '!=', $product->id)
                    ->limit(4)
                    ->get();
            }
            
            // Retourner la vue simplifiée
            return view('products.show', compact('product', 'relatedProducts'));
            
        } catch (\Exception $e) {
            \Log::error('Exception in product page: ' . $e->getMessage());
            \Log::error('Exception trace: ' . $e->getTraceAsString());
            
            return redirect()->route('shop.index')
                ->with('error', 'Une erreur est survenue. Veuillez réessayer plus tard.');
        }
    }

    /**
     * Quick view for a product (for AJAX requests).
     */
    public function quickView(Request $request)
    {
        $product = Product::where('id', $request->product_id)
            ->with(['category', 'images'])
            ->firstOrFail();

        return view('partails.product-quick-view', compact('product'));
    }
}
