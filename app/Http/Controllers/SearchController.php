<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Display search results.
     */
    public function index(Request $request)
    {
        $query = $request->input('query');
        $categoryId = $request->input('category');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $sort = $request->input('sort', 'relevance');
        
        $productsQuery = Product::where('is_active', true);
        
        // Apply search query
        if ($query) {
            $productsQuery->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('short_description', 'like', "%{$query}%");
            });
        }
        
        // Apply category filter
        if ($categoryId) {
            $category = Category::find($categoryId);
            
            if ($category) {
                // Get all subcategory IDs
                $categoryIds = $category->getAllChildrenIds();
                $categoryIds[] = $category->id;
                
                $productsQuery->whereIn('category_id', $categoryIds);
            }
        }
        
        // Apply price filters
        if ($minPrice) {
            $productsQuery->where('price', '>=', $minPrice);
        }
        
        if ($maxPrice) {
            $productsQuery->where('price', '<=', $maxPrice);
        }
        
        // Apply sorting
        switch ($sort) {
            case 'price_asc':
                $productsQuery->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $productsQuery->orderBy('price', 'desc');
                break;
            case 'newest':
                $productsQuery->orderBy('created_at', 'desc');
                break;
            case 'name_asc':
                $productsQuery->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $productsQuery->orderBy('name', 'desc');
                break;
            default:
                // For relevance, if there's a search query, we'll use a custom ordering
                if ($query) {
                    $productsQuery->orderByRaw("
                        CASE 
                            WHEN name LIKE ? THEN 1
                            WHEN name LIKE ? THEN 2
                            WHEN short_description LIKE ? THEN 3
                            WHEN description LIKE ? THEN 4
                            ELSE 5
                        END
                    ", ["{$query}%", "%{$query}%", "%{$query}%", "%{$query}%"]);
                } else {
                    $productsQuery->orderBy('created_at', 'desc');
                }
                break;
        }
        
        $products = $productsQuery->with('category', 'images')->paginate(12);
        
        // Get all categories for the filter
        $categories = Category::where('is_active', true)->get();
        
        return view('search.index', compact('products', 'categories', 'query', 'categoryId', 'minPrice', 'maxPrice', 'sort'));
    }
    
    /**
     * Autocomplete suggestions for search.
     */
    public function autocomplete(Request $request)
    {
        $query = $request->input('query');
        
        if (!$query) {
            return response()->json([]);
        }
        
        $products = Product::where('is_active', true)
            ->where('name', 'like', "%{$query}%")
            ->select('id', 'name', 'slug')
            ->limit(10)
            ->get();
            
        return response()->json($products);
    }
}