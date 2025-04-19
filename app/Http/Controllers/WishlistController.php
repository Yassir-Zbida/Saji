<?php

namespace App\Http\Controllers;

use App\Models\Wishlists;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display the wishlist.
     */
    public function index()
    {
        $wishlistItems = Wishlists::where('user_id', Auth::id())
            ->with('product.images')
            ->get();
            
        return view('wishlist.index', compact('wishlistItems'));
    }
    
    /**
     * Add a product to the wishlist.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variation_id' => 'nullable|exists:product_variations,id',
            'notes' => 'nullable|string',
        ]);
        
        // Check if product already exists in wishlist
        $existingItem = Wishlists::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->where('variation_id', $request->variation_id)
            ->first();
            
        if ($existingItem) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'info',
                    'message' => 'This product is already in your wishlist.'
                ]);
            }
            
            return back()->with('info', 'This product is already in your wishlist.');
        }
        
        Wishlists::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'variation_id' => $request->variation_id,
            'notes' => $request->notes,
        ]);
        
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Product added to wishlist successfully.'
            ]);
        }
        
        return back()->with('success', 'Product added to wishlist successfully.');
    }
    
    /**
     * Remove an item from the wishlist.
     */
    public function remove(Request $request, $id)
    {
        $wishlistItem = Wishlists::find($id);
        
        if (!$wishlistItem) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Item not found.'
                ], 404);
            }
            
            return back()->with('error', 'Item not found.');
        }
        
        // Verify ownership
        if ($wishlistItem->user_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized.'
                ], 403);
            }
            
            abort(403);
        }
        
        $wishlistItem->delete();
        
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Item removed from wishlist.'
            ]);
        }
        
        return back()->with('success', 'Item removed from wishlist.');
    }
    
    /**
     * Clear the entire wishlist.
     */
    public function clear(Request $request)
    {
        Wishlists::where('user_id', Auth::id())->delete();
        
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Wishlist cleared successfully.'
            ]);
        }
        
        return back()->with('success', 'Wishlist cleared successfully.');
    }
    
    /**
     * Move an item from wishlist to cart.
     */
    public function moveToCart(Request $request, $id)
    {
        $wishlistItem = Wishlists::find($id);
        
        if (!$wishlistItem) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Item not found.'
                ], 404);
            }
            
            return back()->with('error', 'Item not found.');
        }
        
        // Verify ownership
        if ($wishlistItem->user_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized.'
                ], 403);
            }
            
            abort(403);
        }
        
        // Add to cart
        app(CartController::class)->add(new Request([
            'product_id' => $wishlistItem->product_id,
            'variation_id' => $wishlistItem->variation_id,
            'quantity' => 1,
        ]));
        
        // Remove from wishlist
        $wishlistItem->delete();
        
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Item moved to cart successfully.'
            ]);
        }
        
        return back()->with('success', 'Item moved to cart successfully.');
    }
    
    /**
     * Update wishlist item notes.
     */
    public function updateNotes(Request $request, $id)
    {
        $wishlistItem = Wishlists::find($id);
        
        if (!$wishlistItem) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Item not found.'
                ], 404);
            }
            
            return back()->with('error', 'Item not found.');
        }
        
        // Verify ownership
        if ($wishlistItem->user_id !== Auth::id()) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized.'
                ], 403);
            }
            
            abort(403);
        }
        
        $request->validate([
            'notes' => 'nullable|string',
        ]);
        
        $wishlistItem->update([
            'notes' => $request->notes,
        ]);
        
        if ($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Notes updated successfully.'
            ]);
        }
        
        return back()->with('success', 'Notes updated successfully.');
    }
    
    /**
     * Toggle product in wishlist (add if not exists, remove if exists)
     * Perfect for the heart button on product cards
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'variation_id' => 'nullable|exists:product_variations,id',
        ]);
        
        // Check if product already exists in wishlist
        $existingItem = Wishlists::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->where('variation_id', $request->variation_id)
            ->first();
            
        if ($existingItem) {
            // Remove from wishlist
            $existingItem->delete();
            
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'action' => 'removed',
                    'message' => 'Product removed from wishlist.'
                ]);
            }
            
            return back()->with('success', 'Product removed from wishlist.');
        } else {
            // Add to wishlist
            Wishlists::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'variation_id' => $request->variation_id,
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'action' => 'added',
                    'message' => 'Product added to wishlist.'
                ]);
            }
            
            return back()->with('success', 'Product added to wishlist.');
        }
    }
    
    /**
     * Check if products are in user's wishlist
     * Useful for pre-filling heart icons on product listing pages
     */
    public function checkProducts(Request $request)
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
        ]);
        
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not logged in',
                'in_wishlist' => []
            ]);
        }
        
        $wishlistItems = Wishlists::where('user_id', Auth::id())
            ->whereIn('product_id', $request->product_ids)
            ->pluck('product_id')
            ->toArray();
            
        return response()->json([
            'status' => 'success',
            'in_wishlist' => $wishlistItems
        ]);
    }
    
    /**
     * Get wishlist count for the current user
     * Useful for updating the wishlist counter in the header
     */
    public function getCount()
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not logged in',
                'count' => 0
            ]);
        }
        
        $count = Wishlists::where('user_id', Auth::id())->count();
        
        return response()->json([
            'status' => 'success',
            'count' => $count
        ]);
    }
}