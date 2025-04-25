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
     * Display the cart page.
     */
    public function index()
    {
        $cartItems = $this->getCartItems();
        
        // Calculate totals
        $subtotal = 0;
        
        foreach ($cartItems as $item) {
            if ($item->productVariation) {
                $price = $item->productVariation->getCurrentPriceAttribute();
            } else {
                $price = $item->product->getCurrentPriceAttribute();
            }
            
            $subtotal += $price * $item->quantity;
        }
        
        // Get tax rate from settings
        $taxRate = config('app.tax_rate', 20);
        $tax = $subtotal * ($taxRate / 100);
        
        // Calculate shipping
        $shippingCost = config('app.shipping_cost', 5.99);
        $freeShippingThreshold = config('app.free_shipping_threshold', 50);
        
        // Check if order qualifies for free shipping
        if ($freeShippingThreshold && $subtotal >= $freeShippingThreshold) {
            $shipping = 0;
        } else {
            $shipping = $shippingCost;
        }
        
        // Get applied coupon if any
        $coupon = Session::get('coupon_code');
        $discount = Session::get('coupon_discount', 0);
        
        $total = $subtotal + $tax + $shipping - $discount;
        
        return view('cart.index', compact('cartItems', 'subtotal', 'tax', 'shipping', 'discount', 'total', 'coupon'));
    }
    
    /**
     * Add a product to cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variation_id' => 'nullable|exists:product_variations,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $product = Product::findOrFail($request->product_id);
        
        // Check if product is available
        if ($product->stock_status === 'out_of_stock') {
            return response()->json([
                'success' => false,
                'message' => 'This product is out of stock.'
            ], 422);
        }
        
        $variation = null;
        if ($request->product_variation_id) {
            $variation = ProductVariation::findOrFail($request->product_variation_id);
            
            // Check if variation is available
            if ($variation->stock_status === 'out_of_stock') {
                return response()->json([
                    'success' => false,
                    'message' => 'This product variation is out of stock.'
                ], 422);
            }
        }
        
        // Check if we have enough stock
        $requestedQuantity = $request->quantity;
        
        if ($variation) {
            if ($variation->stock_quantity !== null && $requestedQuantity > $variation->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'The requested quantity is not available. Available stock: ' . $variation->stock_quantity
                ], 422);
            }
        } else {
            if ($product->stock_quantity !== null && $requestedQuantity > $product->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'The requested quantity is not available. Available stock: ' . $product->stock_quantity
                ], 422);
            }
        }
        
        // Add to cart
        $userId = Auth::id();
        $sessionId = Session::getId();
        
        $cartItem = CartItem::where(function($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->where('product_id', $request->product_id)
            ->where('product_variation_id', $request->product_variation_id)
            ->first();
        
        if ($cartItem) {
            // Update existing cart item
            $cartItem->quantity += $requestedQuantity;
            $cartItem->save();
        } else {
            // Create new cart item
            $cartItem = new CartItem();
            $cartItem->product_id = $request->product_id;
            $cartItem->product_variation_id = $request->product_variation_id;
            $cartItem->quantity = $requestedQuantity;
            
            if ($userId) {
                $cartItem->user_id = $userId;
            } else {
                $cartItem->session_id = $sessionId;
            }
            
            $cartItem->save();
        }
        
        // Get updated cart count
        $cartCount = $this->getCartCount();
        
        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully.',
            'cart_count' => $cartCount
        ]);
    }
    
    /**
     * Update cart item quantity.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        
        $userId = Auth::id();
        $sessionId = Session::getId();
        
        $cartItem = CartItem::where(function($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->findOrFail($id);
        
        // Check stock availability
        $requestedQuantity = $request->quantity;
        
        if ($cartItem->productVariation) {
            if ($cartItem->productVariation->stock_quantity !== null && $requestedQuantity > $cartItem->productVariation->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'The requested quantity is not available. Available stock: ' . $cartItem->productVariation->stock_quantity
                ], 422);
            }
        } else {
            if ($cartItem->product->stock_quantity !== null && $requestedQuantity > $cartItem->product->stock_quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'The requested quantity is not available. Available stock: ' . $cartItem->product->stock_quantity
                ], 422);
            }
        }
        
        // Update quantity
        $cartItem->quantity = $requestedQuantity;
        $cartItem->save();
        
        // Recalculate cart totals
        $cartItems = $this->getCartItems();
        $subtotal = 0;
        
        foreach ($cartItems as $item) {
            if ($item->productVariation) {
                $price = $item->productVariation->getCurrentPriceAttribute();
            } else {
                $price = $item->product->getCurrentPriceAttribute();
            }
            
            $subtotal += $price * $item->quantity;
        }
        
        // Get tax rate from settings
        $taxRate = config('app.tax_rate', 20);
        $tax = $subtotal * ($taxRate / 100);
        
        // Calculate shipping
        $shippingCost = config('app.shipping_cost', 5.99);
        $freeShippingThreshold = config('app.free_shipping_threshold', 50);
        
        // Check if order qualifies for free shipping
        if ($freeShippingThreshold && $subtotal >= $freeShippingThreshold) {
            $shipping = 0;
        } else {
            $shipping = $shippingCost;
        }
        
        // Get applied coupon if any
        $discount = Session::get('coupon_discount', 0);
        
        $total = $subtotal + $tax + $shipping - $discount;
        
        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully.',
            'cart' => [
                'item_total' => number_format($price * $cartItem->quantity, 2),
                'subtotal' => number_format($subtotal, 2),
                'tax' => number_format($tax, 2),
                'shipping' => $shipping == 0 ? 'Free' : number_format($shipping, 2),
                'discount' => number_format($discount, 2),
                'total' => number_format($total, 2),
            ]
        ]);
    }
    
    /**
     * Remove an item from cart.
     */
    public function remove($id)
    {
        $userId = Auth::id();
        $sessionId = Session::getId();
        
        $cartItem = CartItem::where(function($query) use ($userId, $sessionId) {
                if ($userId) {
                    $query->where('user_id', $userId);
                } else {
                    $query->where('session_id', $sessionId);
                }
            })
            ->findOrFail($id);
        
        $cartItem->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully.'
        ]);
    }
    
    /**
     * Clear the entire cart.
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
        
        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully.'
        ]);
    }
    
    /**
     * Proceed to checkout.
     */
    public function checkout()
    {
        // Check if user is logged in
        if (!Auth::check()) {
            // Store intended URL in session
            Session::put('url.intended', route('checkout.index'));
            
            return redirect()->route('login')
                ->with('error', 'Please log in to continue with checkout.');
        }
        
        return redirect()->route('checkout.index');
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
        } else {
            $query->where('session_id', $sessionId);
        }
        
        return $query->get();
    }
    
    /**
     * Get cart count for the current user or session.
     */
    private function getCartCount()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();
        
        $query = CartItem::select(\DB::raw('SUM(quantity) as count'));
        
        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('session_id', $sessionId);
        }
        
        $result = $query->first();
        
        return $result ? $result->count : 0;
    }
}
