<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Invoice;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Address;
use App\Models\Transaction;
use App\Models\Coupon;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function index()
    {
        // Get cart items
        $cartItems = $this->getCartItems();
        
        // Redirect to cart if empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Votre panier est vide. Veuillez ajouter des produits avant de passer à la caisse.');
        }
        
        // Calculate totals
        $subtotal = $this->calculateSubtotal($cartItems);
        $tax = $this->calculateTax($subtotal);
        $shipping = $this->calculateShipping($cartItems, $subtotal);
        $total = $subtotal + $tax + $shipping;
        
        // Get user addresses if logged in
        $shippingAddresses = collect();
        $billingAddresses = collect();
        
        if (Auth::check()) {
            $user = Auth::user();
            $shippingAddresses = $user->addresses()->where('address_type', 'shipping')->get();
            $billingAddresses = $user->addresses()->where('address_type', 'billing')->get();
        }
        
        // Get applied coupon if any
        $coupon = Session::get('coupon');
        $discount = 0;
        
        if ($coupon) {
            $couponObj = Coupon::where('code', $coupon)->first();
            if ($couponObj && $couponObj->isValidForCart($subtotal, $this->formatCartItemsForCoupon($cartItems))) {
                $discount = $couponObj->calculateDiscount($subtotal, $this->formatCartItemsForCoupon($cartItems));
                $total -= $discount;
            } else {
                Session::forget('coupon');
                $coupon = null;
            }
        }
        
        return view('checkout.index', compact(
            'cartItems', 
            'subtotal', 
            'tax', 
            'shipping', 
            'discount',
            'total', 
            'shippingAddresses', 
            'billingAddresses',
            'coupon'
        ));
    }
    
    /**
     * Apply a coupon code.
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50',
        ]);
        
        $couponCode = $request->coupon_code;
        $coupon = Coupon::where('code', $couponCode)->first();
        
        if (!$coupon) {
            return back()->with('error', 'Code coupon invalide.');
        }
        
        if (!$coupon->isValid()) {
            return back()->with('error', 'Ce coupon n\'est plus valide.');
        }
        
        if (Auth::check() && !$coupon->isValidForUser(Auth::id())) {
            return back()->with('error', 'Vous avez déjà utilisé ce coupon le nombre maximum de fois autorisé.');
        }
        
        $cartItems = $this->getCartItems();
        $subtotal = $this->calculateSubtotal($cartItems);
        
        if (!$coupon->isValidForCart($subtotal, $this->formatCartItemsForCoupon($cartItems))) {
            return back()->with('error', 'Ce coupon ne peut pas être appliqué à votre panier actuel.');
        }
        
        Session::put('coupon', $couponCode);
        
        return back()->with('success', 'Coupon appliqué avec succès.');
    }
    
    /**
     * Remove the applied coupon.
     */
    public function removeCoupon()
    {
        Session::forget('coupon');
        return back()->with('success', 'Coupon supprimé.');
    }
    
    /**
     * Process the checkout.
     */
    public function process(Request $request)
    {
        // Validate request
        $request->validate([
            'shipping_address_type' => 'required|in:existing,new',
            'shipping_address_id' => 'required_if:shipping_address_type,existing|nullable|exists:addresses,id',
            'shipping_first_name' => 'required_if:shipping_address_type,new|nullable|string|max:255',
            'shipping_last_name' => 'required_if:shipping_address_type,new|nullable|string|max:255',
            'shipping_company' => 'nullable|string|max:255',
            'shipping_address_line_1' => 'required_if:shipping_address_type,new|nullable|string|max:255',
            'shipping_address_line_2' => 'nullable|string|max:255',
            'shipping_city' => 'required_if:shipping_address_type,new|nullable|string|max:255',
            'shipping_state' => 'required_if:shipping_address_type,new|nullable|string|max:255',
            'shipping_postal_code' => 'required_if:shipping_address_type,new|nullable|string|max:20',
            'shipping_country' => 'required_if:shipping_address_type,new|nullable|string|max:2',
            'shipping_phone' => 'required_if:shipping_address_type,new|nullable|string|max:20',
            'shipping_email' => 'required_if:shipping_address_type,new|nullable|email|max:255',
            'shipping_save_address' => 'boolean',
            
            'billing_address_type' => 'required|in:existing,new,same_as_shipping',
            'billing_address_id' => 'required_if:billing_address_type,existing|nullable|exists:addresses,id',
            'billing_first_name' => 'required_if:billing_address_type,new|nullable|string|max:255',
            'billing_last_name' => 'required_if:billing_address_type,new|nullable|string|max:255',
            'billing_company' => 'nullable|string|max:255',
            'billing_address_line_1' => 'required_if:billing_address_type,new|nullable|string|max:255',
            'billing_address_line_2' => 'nullable|string|max:255',
            'billing_city' => 'required_if:billing_address_type,new|nullable|string|max:255',
            'billing_state' => 'required_if:billing_address_type,new|nullable|string|max:255',
            'billing_postal_code' => 'required_if:billing_address_type,new|nullable|string|max:20',
            'billing_country' => 'required_if:billing_address_type,new|nullable|string|max:2',
            'billing_phone' => 'required_if:billing_address_type,new|nullable|string|max:20',
            'billing_email' => 'required_if:billing_address_type,new|nullable|email|max:255',
            'billing_save_address' => 'boolean',
            
            'payment_method' => 'required|in:card,paypal,bank_transfer',
            'notes' => 'nullable|string',
            'terms_accepted' => 'required|accepted',
        ]);
        
        // Get cart items
        $cartItems = $this->getCartItems();
        
        // Redirect to cart if empty
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', 'Votre panier est vide. Veuillez ajouter des produits avant de passer à la caisse.');
        }
        
        // Check stock availability
        foreach ($cartItems as $item) {
            $product = $item->product;
            $variation = $item->productVariation;
            
            if ($variation) {
                if ($variation->stock_status === 'out_of_stock') {
                    return back()->with('error', 'Le produit "' . $product->name . ' - ' . $variation->name . '" est en rupture de stock.');
                }
                
                if ($variation->stock_quantity !== null && $item->quantity > $variation->stock_quantity) {
                    return back()->with('error', 'La quantité demandée pour "' . $product->name . ' - ' . $variation->name . '" n\'est pas disponible. Stock disponible: ' . $variation->stock_quantity);
                }
            } else {
                if ($product->stock_status === 'out_of_stock') {
                    return back()->with('error', 'Le produit "' . $product->name . '" est en rupture de stock.');
                }
                
                if ($product->stock_quantity !== null && $item->quantity > $product->stock_quantity) {
                    return back()->with('error', 'La quantité demandée pour "' . $product->name . '" n\'est pas disponible. Stock disponible: ' . $product->stock_quantity);
                }
            }
        }
        
        // Calculate totals
        $subtotal = $this->calculateSubtotal($cartItems);
        $tax = $this->calculateTax($subtotal);
        $shipping = $this->calculateShipping($cartItems, $subtotal);
        $total = $subtotal + $tax + $shipping;
        
        // Apply coupon if any
        $couponCode = Session::get('coupon');
        $coupon = null;
        $discount = 0;
        
        if ($couponCode) {
            $coupon = Coupon::where('code', $couponCode)->first();
            if ($coupon && $coupon->isValidForCart($subtotal, $this->formatCartItemsForCoupon($cartItems))) {
                $discount = $coupon->calculateDiscount($subtotal, $this->formatCartItemsForCoupon($cartItems));
                $total -= $discount;
            }
        }
        
        DB::beginTransaction();
        
        try {
            // Process shipping address
            $shippingAddressId = null;
            
            if ($request->shipping_address_type === 'existing') {
                $shippingAddressId = $request->shipping_address_id;
                
                // Verify address belongs to user
                if (Auth::check()) {
                    $address = Address::find($shippingAddressId);
                    if (!$address || $address->user_id !== Auth::id()) {
                        throw new \Exception('Adresse de livraison invalide.');
                    }
                }
            } else {
                // Create new shipping address
                $shippingAddress = [
                    'address_type' => 'shipping',
                    'first_name' => $request->shipping_first_name,
                    'last_name' => $request->shipping_last_name,
                    'company' => $request->shipping_company,
                    'address_line_1' => $request->shipping_address_line_1,
                    'address_line_2' => $request->shipping_address_line_2,
                    'city' => $request->shipping_city,
                    'state' => $request->shipping_state,
                    'postal_code' => $request->shipping_postal_code,
                    'country' => $request->shipping_country,
                    'phone' => $request->shipping_phone,
                    'email' => $request->shipping_email,
                    'is_default' => false,
                ];
                
                if (Auth::check() && $request->shipping_save_address) {
                    // Save address to user account
                    $address = Auth::user()->addresses()->create($shippingAddress);
                    $shippingAddressId = $address->id;
                } else {
                    // Create temporary address
                    $address = Address::create(array_merge($shippingAddress, [
                        'user_id' => Auth::id(),
                    ]));
                    $shippingAddressId = $address->id;
                }
            }
            
            // Process billing address
            $billingAddressId = null;
            
            if ($request->billing_address_type === 'same_as_shipping') {
                $billingAddressId = $shippingAddressId;
            } elseif ($request->billing_address_type === 'existing') {
                $billingAddressId = $request->billing_address_id;
                
                // Verify address belongs to user
                if (Auth::check()) {
                    $address = Address::find($billingAddressId);
                    if (!$address || $address->user_id !== Auth::id()) {
                        throw new \Exception('Adresse de facturation invalide.');
                    }
                }
            } else {
                // Create new billing address
                $billingAddress = [
                    'address_type' => 'billing',
                    'first_name' => $request->billing_first_name,
                    'last_name' => $request->billing_last_name,
                    'company' => $request->billing_company,
                    'address_line_1' => $request->billing_address_line_1,
                    'address_line_2' => $request->billing_address_line_2,
                    'city' => $request->billing_city,
                    'state' => $request->billing_state,
                    'postal_code' => $request->billing_postal_code,
                    'country' => $request->billing_country,
                    'phone' => $request->billing_phone,
                    'email' => $request->billing_email,
                    'is_default' => false,
                ];
                
                if (Auth::check() && $request->billing_save_address) {
                    // Save address to user account
                    $address = Auth::user()->addresses()->create($billingAddress);
                    $billingAddressId = $address->id;
                } else {
                    // Create temporary address
                    $address = Address::create(array_merge($billingAddress, [
                        'user_id' => Auth::id(),
                    ]));
                    $billingAddressId = $address->id;
                }
            }
            
            // Create order
            $orderPrefix = config('app.order_prefix', 'ORD-');
            $orderNumber = $orderPrefix . strtoupper(Str::random(8));
            
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => $orderNumber,
                'status' => 'pending',
                'total_amount' => $total,
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'shipping_amount' => $shipping,
                'discount_amount' => $discount,
                'coupon_code' => $couponCode,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'shipping_address_id' => $shippingAddressId,
                'billing_address_id' => $billingAddressId,
                'notes' => $request->notes,
            ]);
            
            // Create order items
            foreach ($cartItems as $item) {
                $product = $item->product;
                $variation = $item->productVariation;
                
                $price = $variation ? $variation->getCurrentPriceAttribute() : $product->getCurrentPriceAttribute();
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_variation_id' => $variation ? $variation->id : null,
                    'quantity' => $item->quantity,
                    'price' => $price,
                    'subtotal' => $price * $item->quantity,
                    'tax_amount' => $this->calculateItemTax($price * $item->quantity),
                    'discount_amount' => 0, // Individual item discounts not implemented yet
                    'total' => $price * $item->quantity + $this->calculateItemTax($price * $item->quantity),
                ]);
                
                // Update product stock
                if ($variation) {
                    if ($variation->stock_quantity !== null) {
                        $variation->decrement('stock_quantity', $item->quantity);
                        
                        if ($variation->stock_quantity <= 0) {
                            $variation->update(['stock_status' => 'out_of_stock']);
                        }
                    }
                } else {
                    if ($product->stock_quantity !== null) {
                        $product->decrement('stock_quantity', $item->quantity);
                        
                        if ($product->stock_quantity <= 0) {
                            $product->update(['stock_status' => 'out_of_stock']);
                        }
                    }
                }
            }
            
            // Create invoice
            $invoicePrefix = config('app.invoice_prefix', 'INV-');
            $invoiceNumber = $invoicePrefix . strtoupper(Str::random(8));
            
            Invoice::create([
                'order_id' => $order->id,
                'invoice_number' => $invoiceNumber,
                'invoice_date' => now(),
                'due_date' => now()->addDays(15),
                'total_amount' => $total,
                'status' => 'unpaid',
            ]);
            
            // Process payment
            $transactionId = 'TXN-' . strtoupper(Str::random(12));
            $paymentStatus = 'pending';
            $gatewayResponse = null;
            
            // Here you would integrate with your payment gateway
            // For now, we'll simulate different payment methods
            switch ($request->payment_method) {
                case 'card':
                    // Simulate card payment processing
                    $paymentStatus = 'completed';
                    $gatewayResponse = [
                        'transaction_id' => $transactionId,
                        'status' => 'success',
                        'message' => 'Payment processed successfully',
                    ];
                    break;
                    
                case 'paypal':
                    // Simulate PayPal payment processing
                    $paymentStatus = 'pending';
                    $gatewayResponse = [
                        'transaction_id' => $transactionId,
                        'status' => 'pending',
                        'message' => 'Payment pending approval',
                    ];
                    break;
                    
                case 'bank_transfer':
                    // Simulate bank transfer
                    $paymentStatus = 'pending';
                    $gatewayResponse = [
                        'transaction_id' => $transactionId,
                        'status' => 'pending',
                        'message' => 'Awaiting bank transfer',
                        'bank_details' => [
                            'account_name' => 'Saji E-commerce',
                            'account_number' => '1234567890',
                            'bank_name' => 'Example Bank',
                            'reference' => $orderNumber,
                        ],
                    ];
                    break;
            }
            
            // Create transaction record
            Transaction::create([
                'order_id' => $order->id,
                'transaction_id' => $transactionId,
                'payment_method' => $request->payment_method,
                'amount' => $total,
                'currency' => 'EUR',
                'status' => $paymentStatus,
                'payment_gateway' => $request->payment_method,
                'gateway_response' => $gatewayResponse,
            ]);
            
            // Update order payment status
            $order->update(['payment_status' => $paymentStatus]);
            
            // If payment is completed, update invoice status
            if ($paymentStatus === 'completed') {
                $order->invoice->update(['status' => 'paid']);
            }
            
            // Apply coupon usage if applicable
            if ($coupon) {
                $coupon->incrementUsage();
            }
            
            // Clear cart
            $this->clearCart();
            
            // Clear coupon session
            Session::forget('coupon');
            
            // Send notifications
            $this->sendOrderNotifications($order);
            
            DB::commit();
            
            // Redirect to thank you page
            return redirect()->route('checkout.complete', ['order' => $order->id])
                ->with('success', 'Votre commande a été traitée avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors du traitement de votre commande: ' . $e->getMessage())->withInput();
        }
    }
    
    /**
     * Display the order complete page.
     */
    public function complete(Order $order)
    {
        // Verify order belongs to current user
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403);
        }
        
        $order->load('items.product', 'items.productVariation', 'shippingAddress', 'billingAddress', 'transactions');
        
        return view('checkout.complete', compact('order'));
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
                $price = $item->productVariation->getCurrentPriceAttribute();
            } else {
                $price = $item->product->getCurrentPriceAttribute();
            }
            
            $subtotal += $price * $item->quantity;
        }

        return $subtotal;
    }
    
    /**
     * Calculate tax amount.
     */
    private function calculateTax($subtotal)
    {
        // Get tax rate from settings
        $taxRate = config('app.tax_rate', 20);
        
        return $subtotal * ($taxRate / 100);
    }
    
    /**
     * Calculate tax for an individual item.
     */
    private function calculateItemTax($itemSubtotal)
    {
        // Get tax rate from settings
        $taxRate = config('app.tax_rate', 20);
        
        return $itemSubtotal * ($taxRate / 100);
    }
    
    /**
     * Calculate shipping cost.
     */
    private function calculateShipping($cartItems, $subtotal)
    {
        // Get shipping settings
        $shippingCost = config('app.shipping_cost', 5.99);
        $freeShippingThreshold = config('app.free_shipping_threshold', 50);
        
        // Check if order qualifies for free shipping
        if ($freeShippingThreshold && $subtotal >= $freeShippingThreshold) {
            return 0;
        }
        
        return $shippingCost;
    }
    
    /**
     * Format cart items for coupon validation.
     */
    private function formatCartItemsForCoupon($cartItems)
    {
        $formattedItems = [];
        
        foreach ($cartItems as $item) {
            $formattedItems[] = [
                'product_id' => $item->product_id,
                'category_ids' => [$item->product->category_id],
                'price' => $item->productVariation ? $item->productVariation->getCurrentPriceAttribute() : $item->product->getCurrentPriceAttribute(),
                'quantity' => $item->quantity,
            ];
        }
        
        return $formattedItems;
    }
    
    /**
     * Clear the cart.
     */
    private function clearCart()
    {
        $userId = Auth::id();
        $sessionId = Session::getId();

        if ($userId) {
            CartItem::where('user_id', $userId)->delete();
        } else {
            CartItem::where('session_id', $sessionId)->delete();
        }
    }
    
    /**
     * Send order notifications.
     */
    private function sendOrderNotifications(Order $order)
    {
        // Notify admins
        $admins = \App\Models\User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewOrderNotification($order));
        }
        
        // Notify customer if registered
        if ($order->user) {
            // You could create a customer-specific notification here
        }
    }
}