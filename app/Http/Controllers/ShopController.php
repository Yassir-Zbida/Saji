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
        $products = Product::where('is_active', 1)
            ->with('category')
            ->paginate(12);

        // Get featured products
        $featuredProducts = Product::where('is_active', 1)
            ->where('featured', 1) // Make sure you have this column in your products table
            ->limit(8)
            ->get();
                
            
        return view('shop.index', compact('products', 'featuredProducts'));

    }
    
    /**
     * Filter products based on criteria.
     */
    public function filter(Request $request)
    {
        $query = Product::where('is_active', 1);
        
        // Apply category filter
        if ($request->has('category')) {
            $query->whereIn('category_id', $request->category);
        }
        
        // Apply price range filter
        if ($request->has('min_price') && $request->has('max_price')) {
            $query->whereBetween('price', [$request->min_price, $request->max_price]);
        }
        
        // Apply sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price-low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price-high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
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
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $products = $query->with('category')->paginate(12);
        
        return view('shop', compact('products'));
    }
    
    /**
     * Display all categories.
     */
    public function categories()
    {
        $categories = Category::where('parent_id', null)
            ->withCount('products')
            ->orderBy('name')
            ->get();
            
        return view('categories', compact('categories'));
    }
    
    /**
     * Display products in a specific category.
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $products = Product::where('is_active', 1)
            ->where('category_id', $category->id)
            ->paginate(12);
            
        return view('category', compact('category', 'products'));
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
            ->limit(4)
            ->get();
            
        return view('product', compact('product', 'relatedProducts'));
    }
    
    /**
     * Quick view for a product (for AJAX requests).
     */
    public function quickView(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        
        return view('partials.product-quick-view', compact('product'));
    }
}