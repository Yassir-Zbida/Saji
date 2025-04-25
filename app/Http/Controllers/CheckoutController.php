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
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\StripeClient;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     */
    public function index()
    {
        // Check if user is logged in
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please log in to continue with checkout.')
                ->with('redirect', route('checkout.index'));
        }
        
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
        $coupon = Session::get('coupon_code');
        $discount = Session::get('coupon_discount', 0);
        
        if ($discount > 0) {
            $total -= $discount;
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
     * Process the checkout.
     */
    public function process(Request $request)
    {
        // Ensure user is logged in
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to complete checkout.',
                'redirect_url' => route('login')
            ], 401);
        }
        
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
            
            'payment_method' => 'required|in:card,payment_on_delivery,bank_transfer',
            'notes' => 'nullable|string',
            'terms_accepted' => 'required|accepted',
        ]);
        
        // Get cart items
        $cartItems = $this->getCartItems();
        
        // Redirect to cart if empty
        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Votre panier est vide. Veuillez ajouter des produits avant de passer à la caisse.'
            ], 422);
        }
        
        // Check stock availability
        foreach ($cartItems as $item) {
            $product = $item->product;
            $variation = $item->productVariation ?? null;
            
            if ($variation) {
                if ($variation->stock_status === 'out_of_stock') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Le produit "' . $product->name . ' - ' . $variation->name . '" est en rupture de stock.'
                    ], 422);
                }
                
                if ($variation->stock_quantity !== null && $item->quantity > $variation->stock_quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La quantité demandée pour "' . $product->name . ' - ' . $variation->name . '" n\'est pas disponible. Stock disponible: ' . $variation->stock_quantity
                    ], 422);
                }
            } else {
                if ($product->stock_status === 'out_of_stock') {
                    return response()->json([
                        'success' => false,
                        'message' => 'Le produit "' . $product->name . '" est en rupture de stock.'
                    ], 422);
                }
                
                if ($product->stock_quantity !== null && $item->quantity > $product->stock_quantity) {
                    return response()->json([
                        'success' => false,
                        'message' => 'La quantité demandée pour "' . $product->name . '" n\'est pas disponible. Stock disponible: ' . $product->stock_quantity
                    ], 422);
                }
            }
        }
        
        // Calculate totals
        $subtotal = $this->calculateSubtotal($cartItems);
        $tax = $this->calculateTax($subtotal);
        $shipping = $this->calculateShipping($cartItems, $subtotal);
        $total = $subtotal + $tax + $shipping;
        
        // Apply coupon if any
        $couponCode = Session::get('coupon_code');
        $couponId = Session::get('coupon_id');
        $discount = Session::get('coupon_discount', 0);
        
        if ($discount > 0) {
            $total -= $discount;
        }
        
        DB::beginTransaction();
        
        try {
            // Process shipping address
            $shippingAddressId = null;
            $shippingAddressData = [];
            
            if ($request->shipping_address_type === 'existing') {
                $shippingAddressId = $request->shipping_address_id;
                
                // Verify address belongs to user
                if (Auth::check()) {
                    $address = Address::find($shippingAddressId);
                    if (!$address || $address->user_id !== Auth::id()) {
                        throw new \Exception('Adresse de livraison invalide.');
                    }
                    
                    // Get address data for order
                    $shippingAddressData = [
                        'shipping_name' => $address->first_name . ' ' . $address->last_name,
                        'shipping_address' => $address->address_line_1 . ($address->address_line_2 ? ', ' . $address->address_line_2 : ''),
                        'shipping_city' => $address->city,
                        'shipping_state' => $address->state,
                        'shipping_zip_code' => $address->postal_code,
                        'shipping_country' => $address->country,
                        'shipping_phone' => $address->phone,
                    ];
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
                
                // Get address data for order
                $shippingAddressData = [
                    'shipping_name' => $request->shipping_first_name . ' ' . $request->shipping_last_name,
                    'shipping_address' => $request->shipping_address_line_1 . ($request->shipping_address_line_2 ? ', ' . $request->shipping_address_line_2 : ''),
                    'shipping_city' => $request->shipping_city,
                    'shipping_state' => $request->shipping_state,
                    'shipping_zip_code' => $request->shipping_postal_code,
                    'shipping_country' => $request->shipping_country,
                    'shipping_phone' => $request->shipping_phone,
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
            $billingAddressData = [];
            
            if ($request->billing_address_type === 'same_as_shipping') {
                $billingAddressId = $shippingAddressId;
                $billingAddressData = [
                    'billing_name' => $shippingAddressData['shipping_name'],
                    'billing_address' => $shippingAddressData['shipping_address'],
                    'billing_city' => $shippingAddressData['shipping_city'],
                    'billing_state' => $shippingAddressData['shipping_state'],
                    'billing_zip_code' => $shippingAddressData['shipping_zip_code'],
                    'billing_country' => $shippingAddressData['shipping_country'],
                    'billing_phone' => $shippingAddressData['shipping_phone'],
                ];
            } elseif ($request->billing_address_type === 'existing') {
                $billingAddressId = $request->billing_address_id;
                
                // Verify address belongs to user
                if (Auth::check()) {
                    $address = Address::find($billingAddressId);
                    if (!$address || $address->user_id !== Auth::id()) {
                        throw new \Exception('Adresse de facturation invalide.');
                    }
                    
                    // Get address data for order
                    $billingAddressData = [
                        'billing_name' => $address->first_name . ' ' . $address->last_name,
                        'billing_address' => $address->address_line_1 . ($address->address_line_2 ? ', ' . $address->address_line_2 : ''),
                        'billing_city' => $address->city,
                        'billing_state' => $address->state,
                        'billing_zip_code' => $address->postal_code,
                        'billing_country' => $address->country,
                        'billing_phone' => $address->phone,
                    ];
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
                
                // Get address data for order
                $billingAddressData = [
                    'billing_name' => $request->billing_first_name . ' ' . $request->billing_last_name,
                    'billing_address' => $request->billing_address_line_1 . ($request->billing_address_line_2 ? ', ' . $request->billing_address_line_2 : ''),
                    'billing_city' => $request->billing_city,
                    'billing_state' => $request->billing_state,
                    'billing_zip_code' => $request->billing_postal_code,
                    'billing_country' => $request->billing_country,
                    'billing_phone' => $request->billing_phone,
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
            
            // Build order data array with all required fields
            $orderData = [
                'user_id' => Auth::id(),
                'order_number' => $orderNumber,
                'status' => 'pending',
                'total_amount' => $total,
                'tax_amount' => $tax,
                'shipping_amount' => $shipping,
                'discount_amount' => $discount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'notes' => $request->notes,
                'shipping_method' => 'standard', // Default shipping method
                'stripe_session_id' => null, // Initialize stripe_session_id field
            ];
            
            // Merge shipping and billing address data
            $orderData = array_merge($orderData, $shippingAddressData, $billingAddressData);
            
            $order = Order::create($orderData);
            
            // Create order items
            foreach ($cartItems as $item) {
                $product = $item->product;
                $variation = $item->productVariation ?? null;
                
                $price = $variation ? $variation->getCurrentPriceAttribute() : $product->getCurrentPriceAttribute();
                
                // Get the columns that exist in the order_items table
                $orderItemColumns = Schema::getColumnListing('order_items');
                
                // Build order item data array with only existing columns
                $orderItemData = [
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item->quantity,
                ];
                
                // Add optional columns only if they exist in the database
                $optionalOrderItemColumns = [
                    'product_variation_id' => $variation ? $variation->id : null,
                    'price' => $price,
                    'subtotal' => $price * $item->quantity,
                    'tax_amount' => $this->calculateItemTax($price * $item->quantity),
                    'discount_amount' => 0,
                    'total' => $price * $item->quantity + $this->calculateItemTax($price * $item->quantity),
                ];
                
                foreach ($optionalOrderItemColumns as $column => $value) {
                    if (in_array($column, $orderItemColumns)) {
                        $orderItemData[$column] = $value;
                    }
                }
                
                OrderItem::create($orderItemData);
                
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
            
            // Create invoice - using the actual database structure
            $invoicePrefix = config('app.invoice_prefix', 'INV-');
            $invoiceNumber = $invoicePrefix . strtoupper(Str::random(8));
            
            // Create invoice with the fields that exist in your database
            $invoice = new Invoice();
            $invoice->invoice_number = $invoiceNumber;
            $invoice->order_id = $order->id;
            $invoice->user_id = Auth::id(); // This is required
            $invoice->total_amount = $total;
            $invoice->tax_amount = $tax;
            $invoice->status = 'unpaid';
            $invoice->due_date = now()->addDays(15);
            $invoice->save();
            
            // Apply coupon usage if applicable
            if ($couponId) {
                $coupon = Coupon::find($couponId);
                if ($coupon) {
                    $coupon->incrementUsage();
                }
            }
            
            // Clear the cart after successful order creation
            $this->clearCart();
            
            DB::commit();
            
            // Redirect based on payment method
            if ($request->payment_method === 'card') {
                return response()->json([
                    'success' => true,
                    'redirect_url' => route('checkout.payment', ['order' => $order->id])
                ]);
            } else if ($request->payment_method === 'payment_on_delivery' || $request->payment_method === 'bank_transfer') {
                // For cash on delivery or bank transfer, redirect directly to completion page
                return response()->json([
                    'success' => true,
                    'message' => 'Votre commande a été traitée avec succès.',
                    'redirect_url' => route('checkout.complete', ['order' => $order->id])
                ]);
            }
            
            // Default fallback for other payment methods
            return response()->json([
                'success' => true,
                'message' => 'Votre commande a été traitée avec succès.',
                'redirect_url' => route('checkout.complete', ['order' => $order->id])
            ]);
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Checkout Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Une erreur est survenue lors du traitement de votre commande: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Display the payment page
     */
    public function payment(Order $order)
    {
        // Vérifier que l'utilisateur est connecté et que la commande lui appartient
        if (!Auth::check() || $order->user_id !== Auth::id()) {
            return redirect()->route('login')
                ->with('error', 'Veuillez vous connecter pour accéder à cette page.');
        }
        
        // Vérifier que la commande est en attente de paiement
        if ($order->payment_status !== 'pending' && $order->stripe_session_id) {
            return redirect()->route('account.orders')
                ->with('error', 'Cette commande a déjà été payée ou annulée.');
        }
        
        return view('checkout.payment', compact('order'));
    }
    
    /**
     * Create a Stripe payment intent
     */
    public function createPaymentIntent(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);
        
        $order = Order::findOrFail($request->order_id);
        
        // Vérifier que l'utilisateur est connecté et que la commande lui appartient
        if (!Auth::check() || $order->user_id !== Auth::id()) {
            return response()->json([
                'error' => 'Vous n\'êtes pas autorisé à effectuer cette action.'
            ], 403);
        }
        
        try {
            // Initialiser Stripe avec la clé secrète
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        
            // Convertir en centimes pour Stripe
            $amount = (int)($order->total_amount * 100);
        
            $paymentIntent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => 'eur',
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number
                ],
                'description' => 'Commande #' . $order->order_number,
            ]);
        
            return response()->json([
                'clientSecret' => $paymentIntent->client_secret,
                'publicKey' => config('services.stripe.key')
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur Stripe: ' . $e->getMessage());
            return response()->json([
                'error' => 'Une erreur est survenue lors de la création du paiement: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Display the order complete page.
     */
    public function complete(Order $order)
    {
        // Verify order belongs to current user if needed
        if (Auth::check() && $order->user_id !== Auth::id()) {
            return redirect()->route('login')
                ->with('error', 'Please log in to view your order details.');
        }
        
        $order->load('items.product', 'items.productVariation', 'transactions');
        
        return view('checkout.complete', compact('order'));
    }
    
    /**
     * Handle successful payment
     */
    public function success(Order $order)
    {
        // Verify order belongs to current user
        if (!Auth::check() || $order->user_id !== Auth::id()) {
            return redirect()->route('login')
                ->with('error', 'Please log in to view your order details.');
        }
        
        // Check if the order has a Stripe session ID
        if (!$order->stripe_session_id) {
            return redirect()->route('checkout.payment', ['order' => $order->id])
                ->with('error', 'Aucune session de paiement trouvée pour cette commande.');
        }
        
        // Verify payment status with Stripe
        try {
            $stripe = new StripeClient(env('STRIPE_SECRET_KEY'));
            $session = $stripe->checkout->sessions->retrieve($order->stripe_session_id);
            
            if ($session->payment_status === 'paid') {
                // Update order status
                $order->update([
                    'payment_status' => 'paid',
                    'status' => 'processing'
                ]);
                
                // Update invoice status
                if ($order->invoice) {
                    $order->invoice->update(['status' => 'paid']);
                }
                
                // // Send notifications
                // $this->sendOrderNotifications($order);
                
                // Redirect to complete page
                return redirect()->route('checkout.complete', ['order' => $order->id])
                    ->with('success', 'Paiement confirmé avec succès.');
            }
            
            // Payment not confirmed
            return redirect()->route('checkout.payment', ['order' => $order->id])
                ->with('error', 'Le paiement n\'a pas été confirmé. Veuillez réessayer.');
                
        } catch (\Exception $e) {
            Log::error('Erreur Stripe Success: ' . $e->getMessage());
            return redirect()->route('checkout.payment', ['order' => $order->id])
                ->with('error', 'Une erreur est survenue lors de la vérification du paiement: ' . $e->getMessage());
        }
    }
    
    /**
     * Handle canceled payment
     */
    public function cancel(Order $order)
    {
        // Verify order belongs to current user
        if (!Auth::check() || $order->user_id !== Auth::id()) {
            return redirect()->route('login')
                ->with('error', 'Please log in to view your order details.');
        }
        
        return redirect()->route('checkout.payment', ['order' => $order->id])
            ->with('error', 'Paiement annulé. Veuillez réessayer.');
    }
    
    /**
     * Apply a coupon code.
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);
        
        $couponCode = strtoupper($request->coupon_code);
        
        // Find valid coupon
        $coupon = Coupon::where('code', $couponCode)
            ->where(function($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>=', now());
            })
            ->where(function($query) {
                $query->whereNull('usage_limit')
                      ->orWhereRaw('usage_count < usage_limit');
            })
            ->first();
        
        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Code coupon invalide ou expiré.'
            ], 422);
        }
        
        // Get cart items and calculate subtotal
        $cartItems = $this->getCartItems();
        $subtotal = $this->calculateSubtotal($cartItems);
        
        // Check minimum spend
        if ($coupon->minimum_spend && $subtotal < $coupon->minimum_spend) {
            return response()->json([
                'success' => false,
                'message' => 'Votre commande ne répond pas au montant minimum pour ce coupon. Montant minimum: €' . number_format($coupon->minimum_spend, 2)
            ], 422);
        }
        
        // Calculate discount
        $discount = 0;
        if ($coupon->type === 'percentage') {
            $discount = $subtotal * ($coupon->amount / 100);
        } else {
            $discount = $coupon->amount;
        }
        
        // Cap discount at subtotal
        $discount = min($discount, $subtotal);
        
        // Store coupon information in session
        Session::put('coupon_code', $coupon->code);
        Session::put('coupon_discount', $discount);
        Session::put('coupon_id', $coupon->id);
        
        // Calculate new totals
        $tax = $this->calculateTax($subtotal);
        $shipping = $this->calculateShipping($cartItems, $subtotal);
        $total = $subtotal + $tax + $shipping - $discount;
        
        return response()->json([
            'success' => true,
            'message' => 'Coupon appliqué avec succès.',
            'cart' => [
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'coupon_code' => $coupon->code
            ]
        ]);
    }
    
    /**
     * Remove the applied coupon.
     */
    public function removeCoupon()
    {
        // Remove coupon data from session
        Session::forget('coupon_code');
        Session::forget('coupon_discount');
        Session::forget('coupon_id');
        
        // Return updated cart data
        $cartItems = $this->getCartItems();
        $subtotal = $this->calculateSubtotal($cartItems);
        $tax = $this->calculateTax($subtotal);
        $shipping = $this->calculateShipping($cartItems, $subtotal);
        $total = $subtotal + $tax + $shipping;
        
        return response()->json([
            'success' => true,
            'message' => 'Coupon supprimé avec succès.',
            'cart' => [
                'subtotal' => $subtotal,
                'discount' => 0,
                'tax' => $tax,
                'shipping' => $shipping,
                'total' => $total,
                'coupon_code' => null
            ]
        ]);
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
            if (isset($item->productVariation) && $item->productVariation) {
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
        
        // Also clear any coupon data
        Session::forget('coupon_code');
        Session::forget('coupon_discount');
        Session::forget('coupon_id');
    }
}