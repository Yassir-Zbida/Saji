<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AjaxCartController extends Controller
{
    /**
     * Get cart data
     */
    public function getCart()
    {
        $cartItems = $this->getCartItems();
        $formattedCart = $this->formatCartData($cartItems);

        return response()->json([
            'success' => true,
            'cart' => $formattedCart,
        ]);
    }

    /**
     * Add item to cart
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'product_variation_id' => 'nullable|exists:product_variations,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Check if product is active
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'This product is not available.',
            ]);
        }

        // Check if product is in stock
        if (!$product->isInStock()) {
            return response()->json([
                'success' => false,
                'message' => 'This product is out of stock.',
            ]);
        }

        // Check if requested quantity is available
        if ($request->quantity > $product->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'The requested quantity is not available. Available stock: ' . $product->quantity,
            ]);
        }

        // Check if variation exists and is valid
        $variation = null;
        if ($request->has('product_variation_id') && $request->product_variation_id) {
            $variation = ProductVariation::findOrFail($request->product_variation_id);

            if ($variation->product_id != $product->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid product variation.',
                ]);
            }

            if (!$variation->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => 'This variation is not available.',
                ]);
            }

            // Check if variation is in stock
            if (!$variation->isInStock()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This variation is out of stock.',
                ]);
            }

            // Check if requested quantity is available for this variation
            if ($request->quantity > $variation->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'The requested quantity is not available for this variation. Available stock: ' . $variation->quantity,
                ]);
            }
        }

        // Get cart identifier
        $userId = Auth::id();
        $sessionId = Session::getId();

        // Check if item already exists in cart
        $cartItem = CartItem::where('product_id', $product->id)
            ->where('product_variation_id', $request->product_variation_id)
            ->where(function ($query) use ($userId, $sessionId) {
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
            if ($variation) {
                if ($newQuantity > $variation->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'The total requested quantity is not available for this variation. Available stock: ' . $variation->quantity,
                    ]);
                }
            } else {
                if ($newQuantity > $product->quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'The total requested quantity is not available. Available stock: ' . $product->quantity,
                    ]);
                }
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

        // Return updated cart data
        $cartItems = $this->getCartItems();
        $formattedCart = $this->formatCartData($cartItems);

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart.',
            'cart' => $formattedCart,
        ]);
    }

    /**
     * Update cart item quantity
     */
    public function updateCartItem(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::findOrFail($id);

        // Verify cart item belongs to current user/session
        if (!$this->verifyCartItemOwnership($cartItem)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid cart item.',
            ]);
        }

        $product = $cartItem->product;
        $variation = $cartItem->productVariation;

        // Check if requested quantity is available
        if ($variation) {
            if ($request->quantity > $variation->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'The requested quantity is not available for this variation. Available stock: ' . $variation->quantity,
                ]);
            }
        } else {
            if ($request->quantity > $product->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'The requested quantity is not available. Available stock: ' . $product->quantity,
                ]);
            }
        }

        $cartItem->update(['quantity' => $request->quantity]);

        // Return updated cart data
        $cartItems = $this->getCartItems();
        $formattedCart = $this->formatCartData($cartItems);

        return response()->json([
            'success' => true,
            'message' => 'Quantity updated.',
            'cart' => $formattedCart,
        ]);
    }

    /**
     * Remove item from cart
     */
    public function removeCartItem($id)
    {
        $cartItem = CartItem::findOrFail($id);

        // Verify cart item belongs to current user/session
        if (!$this->verifyCartItemOwnership($cartItem)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid cart item.',
            ]);
        }

        $cartItem->delete();

        // Return updated cart data
        $cartItems = $this->getCartItems();
        $formattedCart = $this->formatCartData($cartItems);

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart.',
            'cart' => $formattedCart,
        ]);
    }

    /**
     * Get cart items for the current user or session
     */
    private function getCartItems()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        $query = CartItem::with(['product.images', 'productVariation']);

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
     * Format cart data for JSON response
     */
    private function formatCartData($cartItems)
    {
        $items = [];
        $subtotal = 0;

        foreach ($cartItems as $item) {
            $product = $item->product;
            $variation = $item->productVariation;

            // Determine price
            if ($variation) {
                $price = $variation->sale_price ?? $variation->price;
                $original_price = $variation->price;
                $variationName = $variation->name ?? 'Variation';
            } else {
                $price = $product->sale_price ?? $product->price;
                $original_price = $product->price;
                $variationName = null;
            }

            // Get product image
            $imagePath = null;
            if ($product->images && $product->images->count() > 0) {
                $imagePath = asset('storage/' . $product->images->first()->path);
            } else if ($product->image) {
                $imagePath = asset('storage/' . $product->image);
            } else {
                $imagePath = asset('images/placeholder.jpg');
            }

            // Calculate item total
            $itemTotal = $price * $item->quantity;
            $subtotal += $itemTotal;

            // Add to items array
            $items[] = [
                'id' => $item->id,
                'product_id' => $product->id,
                'name' => $product->name,
                'category_name' => $product->category ? $product->category->name : null,
                'variation_id' => $variation ? $variation->id : null,
                'variation_name' => $variationName,
                'price' => $price,
                'original_price' => $original_price,
                'sale_price' => $product->sale_price ?? ($variation ? $variation->sale_price : null),
                'quantity' => $item->quantity,
                'image_path' => $imagePath,
                'item_total' => $itemTotal,
            ];
        }

        // Include coupon discount if available
        $discount = Session::get('coupon_discount', 0);
        $coupon_code = Session::get('coupon_code');

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'coupon_code' => $coupon_code,
            'total' => $subtotal - $discount,
            'item_count' => count($items),
            'total_quantity' => $cartItems->sum('quantity'),
        ];
    }

    /**
     * Verify that a cart item belongs to the current user or session
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

    /**
     * Clear cart
     */
    public function clearCart()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        if ($userId) {
            CartItem::where('user_id', $userId)->delete();
        } else {
            CartItem::where('session_id', $sessionId)->delete();
        }

        // Also clear any coupon data
        Session::forget(['coupon_code', 'coupon_discount', 'coupon_id']);

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully.',
        ]);
    }

    /**
     * Apply a coupon code to the cart.
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $couponCode = strtoupper($request->coupon_code);

        // Find valid coupon
        $coupon = Coupon::where('code', $couponCode)
            ->valid()
            ->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired coupon code.',
            ]);
        }

        // Get cart items and calculate subtotal
        $cartItems = $this->getCartItems();
        $subtotal = $this->calculateSubtotal($cartItems);

        // Check minimum spend
        if ($coupon->minimum_spend && $subtotal < $coupon->minimum_spend) {
            return response()->json([
                'success' => false,
                'message' => 'Your order does not meet the minimum spend for this coupon. Minimum spend: $' . number_format($coupon->minimum_spend, 2),
            ]);
        }

        // Calculate discount
        $discount = $coupon->calculateDiscount($subtotal);

        // Store coupon information in session
        Session::put('coupon_code', $coupon->code);
        Session::put('coupon_discount', $discount);
        Session::put('coupon_id', $coupon->id);

        // Format cart data with discount
        $formattedCart = $this->formatCartData($cartItems);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully.',
            'cart' => $formattedCart,
        ]);
    }

    /**
     * Remove a coupon from the cart.
     */
    public function removeCoupon()
    {
        // Remove coupon data from session
        Session::forget('coupon_code');
        Session::forget('coupon_discount');
        Session::forget('coupon_id');

        // Return updated cart data
        $cartItems = $this->getCartItems();
        $formattedCart = $this->formatCartData($cartItems);

        return response()->json([
            'success' => true,
            'message' => 'Coupon removed successfully.',
            'cart' => $formattedCart,
        ]);
    }

    /**
     * Calculate subtotal for cart items
     */
    private function calculateSubtotal($cartItems)
    {
        $subtotal = 0;
        
        foreach ($cartItems as $item) {
            $product = $item->product;
            $variation = $item->productVariation;
            
            // Determine price
            if ($variation) {
                $price = $variation->sale_price ?? $variation->price;
            } else {
                $price = $product->sale_price ?? $product->price;
            }
            
            // Calculate item total and add to subtotal
            $itemTotal = $price * $item->quantity;
            $subtotal += $itemTotal;
        }
        
        return $subtotal;
    }
}