<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display the cart.
     */
    public function index()
    {
        $cartItems = $this->getCartItems();
        $subtotal = $this->calculateSubtotal($cartItems);
        
        return view('cart.index', compact('cartItems', 'subtotal'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variation_id' => 'nullable|exists:product_variations,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        // Check if product is active
        if (!$product->is_active) {
            return back()->with('error', 'Ce produit n\'est pas disponible.');
        }
        
        // Check if product is in stock
        if ($product->stock_status !== 'in_stock' && $product->stock_status !== 'on_backorder') {
            return back()->with('error', 'Ce produit est en rupture de stock.');
        }
        
        // Check if requested quantity is available
        if ($product->stock_quantity !== null && $request->quantity > $product->stock_quantity) {
            return back()->with('error', 'La quantité demandée n\'est pas disponible. Stock disponible: ' . $product->stock_quantity);
        }

        // Check if variation exists and is valid
        $variation = null;
        if ($request->has('product_variation_id')) {
            $variation = ProductVariation::findOrFail($request->product_variation_id);
            
            if ($variation->product_id != $product->id) {
                return back()->with('error', 'Variation de produit invalide.');
            }
            
            if (!$variation->is_active) {
                return back()->with('error', 'Cette variation n\'est pas disponible.');
            }
            
            if ($variation->stock_status !== 'in_stock' && $variation->stock_status !== 'on_backorder') {
                return back()->with('error', 'Cette variation est en rupture de stock.');
            }
            
            if ($variation->stock_quantity !== null && $request->quantity > $variation->stock_quantity) {
                return back()->with('error', 'La quantité demandée n\'est pas disponible pour cette variation. Stock disponible: ' . $variation->stock_quantity);
            }
        }

        // Get cart identifier
        $userId = Auth::id();
        $sessionId = Session::getId();

        // Check if item already exists in cart
        $cartItem = CartItem::where('product_id', $product->id)
            ->where('product_variation_id', $request->product_variation_id)
            ->where(function($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->first();

        if ($cartItem) {
            // Update quantity
            $newQuantity = $cartItem->quantity + $request->quantity;
            
            // Check if new quantity is available
            if ($product->stock_quantity !== null && $newQuantity > $product->stock_quantity) {
                return back()->with('error', 'La quantité totale demandée n\'est pas disponible. Stock disponible: ' . $product->stock_quantity);
            }
            
            if ($variation && $variation->stock_quantity !== null && $newQuantity > $variation->stock_quantity) {
                return back()->with('error', 'La quantité totale demandée n\'est pas disponible pour cette variation. Stock disponible: ' . $variation->stock_quantity);
            }
            
            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            // Create new cart item
            CartItem::create([
                'user_id' => $userId,
                'session_id' => $userId ? null : $sessionId,
                'product_id' => $product->id,
                'product_variation_id' => $request->product_variation_id,
                'quantity' => $request->quantity,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produit ajouté au panier.');
    }

    /**
     * Update cart item quantity.
     */
    public function update(Request $request, CartItem $cartItem)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Verify cart item belongs to current user/session
        if (!$this->verifyCartItemOwnership($cartItem)) {
            return back()->with('error', 'Article de panier invalide.');
        }

        $product = $cartItem->product;
        $variation = $cartItem->productVariation;

        // Check if requested quantity is available
        if ($variation) {
            if ($variation->stock_quantity !== null && $request->quantity > $variation->stock_quantity) {
                return back()->with('error', 'La quantité demandée n\'est pas disponible pour cette variation. Stock disponible: ' . $variation->stock_quantity);
            }
        } else {
            if ($product->stock_quantity !== null && $request->quantity > $product->stock_quantity) {
                return back()->with('error', 'La quantité demandée n\'est pas disponible. Stock disponible: ' . $product->stock_quantity);
            }
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Quantité mise à jour.');
    }

    /**
     * Remove an item from the cart.
     */
    public function remove(CartItem $cartItem)
    {
        // Verify cart item belongs to current user/session
        if (!$this->verifyCartItemOwnership($cartItem)) {
            return back()->with('error', 'Article de panier invalide.');
        }

        $cartItem->delete();

        return back()->with('success', 'Article supprimé du panier.');
    }

    /**
     * Clear the cart.
     */
    public function clear()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        if ($userId) {
            CartItem::where('user_id', $userId)->delete();
        } else {
            CartItem::where('session_id', $sessionId)->delete();
        }

        return back()->with('success', 'Panier vidé.');
    }

    /**
     * Get cart items for the current user or session.
     */
    private function getCartItems()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        $query = CartItem::with(['product', 'productVariation']);

        if ($userId) {
            $query->where('user_id', $userId);
            
            // Merge items from session if any
            $sessionItems = CartItem::where('session_id', $sessionId)->get();
            foreach ($sessionItems as $sessionItem) {
                $existingItem = CartItem::where('user_id', $userId)
                    ->where('product_id', $sessionItem->product_id)
                    ->where('product_variation_id', $sessionItem->product_variation_id)
                    ->first();
                
                if ($existingItem) {
                    $existingItem->update(['quantity' => $existingItem->quantity + $sessionItem->quantity]);
                    $sessionItem->delete();
                } else {
                    $sessionItem->update(['user_id' => $userId, 'session_id' => null]);
                }
            }
        } else {
            $query->where('session_id', $sessionId);
        }

        return $query->get();
    }

    /**
     * Calculate subtotal for cart items.
     */
    private function calculateSubtotal($cartItems)
    {
        $subtotal = 0;

        foreach ($cartItems as $item) {
            if ($item->productVariation) {
                $price = $item->productVariation->sale_price ?? $item->productVariation->price;
            } else {
                $price = $item->product->sale_price ?? $item->product->price;
            }
            
            $subtotal += $price * $item->quantity;
        }

        return $subtotal;
    }

    /**
     * Verify that a cart item belongs to the current user or session.
     */
    private function verifyCartItemOwnership(CartItem $cartItem)
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        if ($userId) {
            return $cartItem->user_id == $userId;
        } else {
            return $cartItem->session_id == $sessionId;
        }
    }
}