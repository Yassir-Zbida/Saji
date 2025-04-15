<?php

namespace App\Http\Controllers;

use App\Models\WishlistItem;
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
        $wishlistItems = WishlistItem::where('user_id', Auth::id())
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
            'notes' => 'nullable|string',
        ]);
        
        // Check if product already exists in wishlist
        $existingItem = WishlistItem::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();
            
        if ($existingItem) {
            return back()->with('info', 'This product is already in your wishlist.');
        }
        
        WishlistItem::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'notes' => $request->notes,
        ]);
        
        return back()->with('success', 'Product added to wishlist successfully.');
    }
    
    /**
     * Remove an item from the wishlist.
     */
    public function remove(WishlistItem $wishlistItem)
    {
        // Verify ownership
        if ($wishlistItem->user_id !== Auth::id()) {
            abort(403);
        }
        
        $wishlistItem->delete();
        
        return back()->with('success', 'Item removed from wishlist.');
    }
    
    /**
     * Clear the entire wishlist.
     */
    public function clear()
    {
        WishlistItem::where('user_id', Auth::id())->delete();
        
        return back()->with('success', 'Wishlist cleared successfully.');
    }
    
    /**
     * Move an item from wishlist to cart.
     */
    public function moveToCart(WishlistItem $wishlistItem)
    {
        // Verify ownership
        if ($wishlistItem->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Add to cart
        app(CartController::class)->add(new Request([
            'product_id' => $wishlistItem->product_id,
            'quantity' => 1,
        ]));
        
        // Remove from wishlist
        $wishlistItem->delete();
        
        return back()->with('success', 'Item moved to cart successfully.');
    }
    
    /**
     * Update wishlist item notes.
     */
    public function updateNotes(Request $request, WishlistItem $wishlistItem)
    {
        // Verify ownership
        if ($wishlistItem->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'notes' => 'nullable|string',
        ]);
        
        $wishlistItem->update([
            'notes' => $request->notes,
        ]);
        
        return back()->with('success', 'Notes updated successfully.');
    }
}