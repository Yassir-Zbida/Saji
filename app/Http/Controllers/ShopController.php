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
        
        $products = $query->paginate(12);

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
    public function product($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', 1)
            ->with(['category', 'images'])
            ->firstOrFail();
            
        // Get related products
        $relatedProducts = Product::where('is_active', 1)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('category', 'images')
            ->limit(4)
            ->get();
            
        return view('product', compact('product', 'relatedProducts'));
    }
    
    /**
     * Quick view for a product (for AJAX requests).
     */
    public function quickView(Request $request)
    {
        $product = Product::where('id', $request->product_id)
            ->with(['category', 'images'])
            ->firstOrFail();
        
        return view('partials.product-quick-view', compact('product'));
    }
}