<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripeController extends Controller
{
    public function checkout(Order $order)
    {
        // Get the Stripe secret key directly from env
        $stripeSecretKey = env('STRIPE_SECRET_KEY');
        
        // Check if the key is available
        if (!$stripeSecretKey) {
            Log::error('Stripe secret key is missing');
            return redirect()->route('checkout.payment', ['order' => $order->id])
                ->with('error', 'Configuration Stripe manquante. Veuillez contacter l\'administrateur.');
        }
        
        try {
            // Initialize Stripe with the secret key - FIXED THIS LINE
            Stripe::setApiKey($stripeSecretKey);

            // Directly query the order items using the correct relationship name
            $orderItems = $order->items()->get();

            // Check if we have items
            if ($orderItems->isEmpty()) {
                Log::error('No order items found for order #' . $order->id);
                return redirect()->route('checkout.payment', ['order' => $order->id])
                    ->with('error', 'Aucun article trouvé pour cette commande.');
            }

            $lineItems = [];
            foreach ($orderItems as $item) {
                // Get product name - handle potential null values
                $productName = 'Product';
                if (isset($item->name) && !empty($item->name)) {
                    $productName = $item->name;
                } elseif (isset($item->product) && isset($item->product->name)) {
                    $productName = $item->product->name;
                }
                
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $productName,
                        ],
                        'unit_amount' => (int)($item->price * 100), // Convert to cents for Stripe
                    ],
                    'quantity' => $item->quantity,
                ];
            }

            Log::info('Creating Stripe session for order #' . $order->id);
            
            // Create the Stripe Checkout session - FIXED THIS SECTION
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('checkout.success', ['order' => $order->id]) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('checkout.cancel', ['order' => $order->id]),
                'client_reference_id' => (string)$order->id, // Ensure it's a string
                'customer_email' => auth()->user()->email,
                'metadata' => [
                    'order_id' => (string)$order->id,
                    'order_number' => $order->order_number
                ],
            ]);

            // Update the order with the Stripe session ID
            $order->update([
                'stripe_session_id' => $session->id
            ]);

            Log::info('Stripe session created: ' . $session->id . ' for order #' . $order->id);
            
            return redirect($session->url);
            
        } catch (Exception $e) {
            // Log the error
            Log::error('Stripe Error: ' . $e->getMessage());
            
            return redirect()->route('checkout.payment', ['order' => $order->id])
                ->with('error', 'Une erreur est survenue lors de la création de la session de paiement: ' . $e->getMessage());
        }
    }

    public function success(Request $request, Order $order)
    {
        $stripeSecretKey = env('STRIPE_SECRET_KEY');
        
        if (!$stripeSecretKey) {
            Log::error('Stripe secret key is missing');
            return redirect()->route('checkout.payment', ['order' => $order->id])
                ->with('error', 'Configuration Stripe manquante. Veuillez contacter l\'administrateur.');
        }
        
        try {
            // Initialize Stripe with the secret key - FIXED THIS LINE
            Stripe::setApiKey($stripeSecretKey);

            $sessionId = $request->get('session_id');
            
            if (!$sessionId) {
                Log::error('No session_id provided in success callback for order #' . $order->id);
                return redirect()->route('checkout.payment', ['order' => $order->id])
                    ->with('error', 'Aucun identifiant de session fourni.');
            }
            
            $session = Session::retrieve($sessionId);

            // Verify that the payment is for this order
            if ($session->client_reference_id != $order->id) {
                Log::error('Session client_reference_id (' . $session->client_reference_id . ') does not match order ID (' . $order->id . ')');
                return redirect()->route('checkout.payment', ['order' => $order->id])
                    ->with('error', 'Session de paiement invalide pour cette commande.');
            }

            // Update the order with the session ID if it's not already set
            if (!$order->stripe_session_id) {
                $order->update([
                    'stripe_session_id' => $sessionId
                ]);
            }

            // Check payment status
            if ($session->payment_status === 'paid') {
                // Update order status
                $order->update([
                    'status' => 'processing',
                    'payment_status' => 'paid'
                ]);
                
                Log::info('Payment confirmed for order #' . $order->id);
                
                return redirect()->route('checkout.complete', ['order' => $order->id])
                    ->with('success', 'Paiement confirmé avec succès.');
            } else {
                Log::warning('Payment not confirmed for order #' . $order->id . '. Status: ' . $session->payment_status);
                
                return redirect()->route('checkout.payment', ['order' => $order->id])
                    ->with('error', 'Le paiement n\'a pas été confirmé. Veuillez réessayer.');
            }
            
        } catch (Exception $e) {
            Log::error('Stripe Success Error: ' . $e->getMessage());
            
            return redirect()->route('checkout.payment', ['order' => $order->id])
                ->with('error', 'Une erreur est survenue lors de la vérification du paiement: ' . $e->getMessage());
        }
    }

    public function cancel(Order $order)
    {
        Log::info('Payment cancelled for order #' . $order->id);
        return view('checkout.cancel', compact('order'));
    }

    public function payment(Order $order)
    {
        return view('checkout.payment', compact('order'));
    }

    public function handleWebhook(Request $request)
    {
        $stripeSecretKey = env('STRIPE_SECRET_KEY');
        
        if (!$stripeSecretKey) {
            Log::error('Stripe secret key is missing for webhook');
            return response()->json(['error' => 'Stripe configuration missing'], 500);
        }
        
        try {
            // Initialize Stripe with the secret key - FIXED THIS LINE
            Stripe::setApiKey($stripeSecretKey);

            $payload = $request->getContent();
            
            $event = json_decode($payload);
            if (!$event || !isset($event->type)) {
                Log::error('Invalid webhook payload');
                return response()->json(['error' => 'Invalid payload'], 400);
            }
            
            // Process the event without signature verification
            $this->processWebhookEvent($event);
            
            return response()->json(['status' => 'success']);
        } catch (Exception $e) {
            Log::error('Webhook Error: ' . $e->getMessage());
            return response()->json(['error' => 'Error processing webhook'], 500);
        }
    }
    
    private function processWebhookEvent($event)
    {
        // Handle the event based on its type
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $this->handleCheckoutSessionCompleted($session);
                break;
            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                $this->handlePaymentIntentSucceeded($paymentIntent);
                break;
            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                $this->handlePaymentIntentFailed($paymentIntent);
                break;
            default:
                Log::info('Unhandled event type: ' . $event->type);
                break;
        }
    }

    private function handleCheckoutSessionCompleted($session)
    {
        Log::info('Checkout session completed: ' . $session->id);
        
        // Retrieve the order by session ID
        $order = Order::where('stripe_session_id', $session->id)->first();

        if ($order) {
            // Update order status
            $order->update([
                'status' => 'processing',
                'payment_status' => 'paid'
            ]);
            
            Log::info('Order #' . $order->id . ' updated to paid status');
        } else {
            // Try to find by client_reference_id if stripe_session_id is not found
            $orderId = $session->client_reference_id;
            $order = Order::find($orderId);
            
            if ($order) {
                $order->update([
                    'stripe_session_id' => $session->id,
                    'status' => 'processing',
                    'payment_status' => 'paid'
                ]);
                
                Log::info('Order #' . $order->id . ' updated to paid status (found by client_reference_id)');
            } else {
                Log::error('Order not found for session: ' . $session->id);
            }
        }
    }

    private function handlePaymentIntentSucceeded($paymentIntent)
    {
        Log::info('Payment succeeded: ' . $paymentIntent->id);
        
        // For now, we'll just log the event
        // In a real application, you would update the order status
    }

    private function handlePaymentIntentFailed($paymentIntent)
    {
        Log::info('Payment failed: ' . $paymentIntent->id);
        
        // For now, we'll just log the event
        // In a real application, you would update the order status
    }
}
