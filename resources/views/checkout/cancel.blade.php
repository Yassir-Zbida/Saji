<h1 class="text-2xl sm:text-3xl font-semibold text-primary mt-6 mb-3">
    Payment Cancelled
</h1>
<p class="text-gray-600">
    Your order <span class="font-medium text-primary">{{ $order->order_number }}</span> has not been processed
</p>
</div>

<!-- Order content -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
<div class="lg:col-span-2">
    <!-- Payment Status Card -->
    <div class="bg-white rounded-lg shadow-soft p-6 sm:p-8 border border-gray-200">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-primary">Payment Status</h2>
            <div class="px-3 py-1.5 bg-amber-50 text-amber-600 rounded-full text-sm font-medium">
                <i class="ri-close-circle-line mr-1"></i>
                Cancelled
            </div>
        </div>

        <div class="bg-amber-50 border border-amber-100 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <i class="ri-information-line text-amber-500 text-xl mt-1 mr-3"></i>
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900 mb-1">No amount has been charged</h4>
                    <p class="text-gray-600 text-sm">
                        Your payment has been cancelled and no money has been debited from your account.
                    </p>
                </div>
            </div>
        </div>

        <!-- Order Information -->
        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="text-sm text-gray-500 mb-1">Order Date</p>
                <p class="font-medium">{{ $order->created_at->format('F d, Y') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Order Number</p>
                <p class="font-medium">{{ $order->order_number }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Payment Method</p>
                <p class="font-medium">{{ ucfirst($order->payment_method) }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 mb-1">Payment Status</p>
                <p class="font-medium text-amber-600 flex items-center">
                    <i class="ri-time-line mr-1"></i>
                    Cancelled
                </p>
            </div>
        </div>
    </div>

    <!-- What to do next -->
    <div class="bg-white rounded-lg shadow-soft p-6 sm:p-8 border border-gray-200 mt-6">
        <h3 class="text-lg font-semibold text-primary mb-6">What to do next?</h3>

        <div class="space-y-4">
            <div class="flex items-start">
                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-3 mt-0.5">
                    <i class="ri-refresh-line text-gray-600"></i>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Try payment again</h4>
                    <p class="text-sm text-gray-600 mt-1">You can retry the payment process using the same or a different payment method.</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-3 mt-0.5">
                    <i class="ri-customer-service-2-line text-gray-600"></i>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Contact customer support</h4>
                    <p class="text-sm text-gray-600 mt-1">If you're experiencing difficulties with the payment process, our customer support team is ready to assist you.</p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center mr-3 mt-0.5">
                    <i class="ri-shopping-cart-line text-gray-600"></i>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900">Modify your order</h4>
                    <p class="text-sm text-gray-600 mt-1">You can review your order items or shipping details before attempting payment again.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Right column - Order summary -->
<div class="lg:col-span-1">
    <div class="bg-white rounded-lg shadow-soft p-6 sm:p-8 sticky top-4 border border-gray-200">
        <h3 class="text-lg font-semibold text-primary mb-6">Order Summary</h3>

        @php
            $subtotal = 0;
            foreach ($order->items as $item) {
                $subtotal += $item->price * $item->quantity;
            }
        @endphp

        <div class="space-y-4 border-b border-gray-100 pb-4 mb-4">
            <div class="flex justify-between text-gray-600">
                <span>Subtotal</span>
                <span>{{ number_format($subtotal, 2) }} €</span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span>Tax</span>
                <span>{{ number_format($order->tax_amount, 2) }} €</span>
            </div>
            <div class="flex justify-between text-gray-600">
                <span>Shipping</span>
                <span>
                    @if ($order->shipping_amount > 0)
                        {{ number_format($order->shipping_amount, 2) }} €
                    @else
                        Free
                    @endif
                </span>
            </div>

            @if ($order->discount_amount > 0)
                <div class="flex justify-between text-green-600">
                    <span>Discount</span>
                    <span>-{{ number_format($order->discount_amount, 2) }} €</span>
                </div>
            @endif
        </div>

        <div class="flex justify-between font-semibold text-lg mb-8">
            <span>Total</span>
            <span>{{ number_format($order->total_amount, 2) }} €</span>
        </div>

        <div class="space-y-3">
            <a href="{{ route('checkout.payment', ['order' => $order->id]) }}"
                class="flex items-center justify-center w-full bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary/90 transition-colors duration-200">
                <i class="ri-bank-card-line mr-2"></i>
                Retry Payment
            </a>

            <a href="{{ route('account.orders') }}"
                class="flex items-center justify-center w-full bg-white border border-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                <i class="ri-file-list-3-line mr-2"></i>
                My Orders
            </a>

            <a href="{{ route('shop.index') }}"
                class="flex items-center justify-center w-full bg-white border border-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                <i class="ri-shopping-bag-line mr-2"></i>
                Continue Shopping
            </a>
        </div>

        <!-- Support Information -->
        <div class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <h4 class="font-medium mb-2 flex items-center">
                <i class="ri-customer-service-line mr-2"></i>
                Need Help?
            </h4>
            <p class="text-sm text-gray-600 mb-3">Our customer support team is ready to assist you with any payment issues.</p>
            @if (Route::has('account.tickets.create'))
                <a href="{{ route('account.tickets.create', ['order_id' => $order->id]) }}"
                    class="text-primary text-sm font-medium hover:underline">
                    Contact Support
                </a>
            @else
                <a href="{{ route('contact') }}"
                    class="text-primary text-sm font-medium hover:underline">
                    Contact Support
                </a>
            @endif
        </div>
    </div>
</div>
</div>
</div>
</div>

<style>
.warning-icon {
width: 80px;
height: 80px;
margin: 0 auto;
}

.animate-pulse {
animation: pulse 2s infinite;
}

.warning-icon circle {
stroke-dasharray: 240;
stroke-dashoffset: 0;
}

.warning-icon path {
stroke-dasharray: 50;
stroke-dashoffset: 0;
}

@keyframes pulse {
0% {
transform: scale(0.95);
opacity: 0.7;
}
50% {
transform: scale(1);
opacity: 1;
}
100% {
transform: scale(0.95);
opacity: 0.7;
}
}

.shadow-soft {
box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}
</style>