@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="account-order-detail py-12 bg-secondary">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="mb-8">
            <div class="text-sm text-gray-500 mb-2 flex items-center">
                <a href="{{ route('/') }}" class="hover:text-primary">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('account.index') }}" class="hover:text-primary">My Account</a>
                <span class="mx-2">/</span>
                <a href="{{ route('account.orders') }}" class="hover:text-primary">My Orders</a>
                <span class="mx-2">/</span>
                <span>Order #{{ $order->order_number }}</span>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h1 class="text-3xl md:text-4xl font-bold text-primary">Order #{{ $order->order_number }}</h1>
                <a href="{{ route('account.orders') }}" class="inline-flex items-center text-sm text-primary hover:text-primary/70 transition">
                    <i class="ri-arrow-left-line mr-1"></i> Back to My Orders
                </a>
            </div>
            <p class="text-gray-600 mt-2 font-jost">Placed on {{ $order->created_at->format('F d, Y \a\t H:i') }}</p>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-8 rounded-md" role="alert">
            <p>{{ session('success') }}</p>
        </div>
        @endif

        <!-- Order Status -->
        <div class="bg-white rounded-xl shadow-soft overflow-hidden border border-accent/20 transition-all duration-300 hover:shadow-medium mb-8" data-aos="fade-up" data-aos-delay="100">
            <div class="card-header border-b border-accent/10 px-6 py-5 flex items-center justify-between">
                <div class="flex items-center">
                    <i class="ri-information-line text-xl text-primary/70 mr-3"></i>
                    <h2 class="text-lg font-medium text-primary">Order Status</h2>
                </div>
                <span class="inline-flex px-4 py-1.5 text-sm rounded-full font-medium
                    @if($order->status == 'completed') bg-green-100 text-green-800
                    @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                    @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                    @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <div class="p-6">
                <div class="flex flex-wrap md:flex-nowrap gap-8">
                    <div class="w-full md:w-1/2">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Order Details</h3>
                        <ul class="space-y-2">
                            <li class="flex justify-between">
                                <span class="text-gray-600">Order Number:</span>
                                <span class="font-medium">#{{ $order->order_number }}</span>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-gray-600">Date Placed:</span>
                                <span>{{ $order->created_at->format('M d, Y') }}</span>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-gray-600">Payment Method:</span>
                                <span>{{ $order->payment_method ?? 'Not specified' }}</span>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-gray-600">Payment Status:</span>
                                <span class="inline-flex px-2 py-0.5 text-xs rounded-full
                                    @if($order->payment_status == 'paid') bg-green-100 text-green-800
                                    @elseif($order->payment_status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($order->payment_status == 'failed') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($order->payment_status ?? 'Unknown') }}
                                </span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="w-full md:w-1/2">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-2">Shipping Details</h3>
                        <ul class="space-y-2">
                            <li class="flex justify-between">
                                <span class="text-gray-600">Shipping Method:</span>
                                <span>{{ $order->shipping_method ?? 'Standard Shipping' }}</span>
                            </li>
                            <li class="flex justify-between">
                                <span class="text-gray-600">Shipping Status:</span>
                                <span class="inline-flex px-2 py-0.5 text-xs rounded-full
                                    @if($order->status == 'shipped') bg-green-100 text-green-800
                                    @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                    @elseif($order->status == 'completed') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </li>
                            @if($order->tracking_number)
                            <li class="flex justify-between">
                                <span class="text-gray-600">Tracking Number:</span>
                                <span class="font-medium">{{ $order->tracking_number }}</span>
                            </li>
                            @endif
                            @if($order->estimated_delivery)
                            <li class="flex justify-between">
                                <span class="text-gray-600">Estimated Delivery:</span>
                                <span>{{ \Carbon\Carbon::parse($order->estimated_delivery)->format('M d, Y') }}</span>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Shipping Address -->
            <div class="bg-white rounded-xl shadow-soft overflow-hidden border border-accent/20 transition-all duration-300 hover:shadow-medium" data-aos="fade-up" data-aos-delay="200">
                <div class="card-header border-b border-accent/10 px-6 py-5 flex items-center">
                    <i class="ri-truck-line text-xl text-primary/70 mr-3"></i>
                    <h2 class="text-lg font-medium text-primary">Shipping Address</h2>
                </div>
                <div class="p-6">
                    @if($order->shippingAddress)
                    <div class="text-gray-600">
                        <p class="font-medium text-primary mb-2">
                            {{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}
                        </p>
                        @if($order->shippingAddress->company)
                            <p>{{ $order->shippingAddress->company }}</p>
                        @endif
                        <p>{{ $order->shippingAddress->address_line_1 }}</p>
                        @if($order->shippingAddress->address_line_2)
                            <p>{{ $order->shippingAddress->address_line_2 }}</p>
                        @endif
                        <p>
                            {{ $order->shippingAddress->city }}, 
                            {{ $order->shippingAddress->state }} 
                            {{ $order->shippingAddress->postal_code }}
                        </p>
                        <p>{{ $order->shippingAddress->country }}</p>
                        <p class="mt-2">
                            <i class="ri-phone-line mr-1"></i> {{ $order->shippingAddress->phone }}
                        </p>
                        <p>
                            <i class="ri-mail-line mr-1"></i> {{ $order->shippingAddress->email }}
                        </p>
                    </div>
                    @else
                    <p class="text-gray-500">No shipping address information available.</p>
                    @endif
                </div>
            </div>

            <!-- Billing Address -->
            <div class="bg-white rounded-xl shadow-soft overflow-hidden border border-accent/20 transition-all duration-300 hover:shadow-medium" data-aos="fade-up" data-aos-delay="300">
                <div class="card-header border-b border-accent/10 px-6 py-5 flex items-center">
                    <i class="ri-bill-line text-xl text-primary/70 mr-3"></i>
                    <h2 class="text-lg font-medium text-primary">Billing Address</h2>
                </div>
                <div class="p-6">
                    @if($order->billingAddress)
                    <div class="text-gray-600">
                        <p class="font-medium text-primary mb-2">
                            {{ $order->billingAddress->first_name }} {{ $order->billingAddress->last_name }}
                        </p>
                        @if($order->billingAddress->company)
                            <p>{{ $order->billingAddress->company }}</p>
                        @endif
                        <p>{{ $order->billingAddress->address_line_1 }}</p>
                        @if($order->billingAddress->address_line_2)
                            <p>{{ $order->billingAddress->address_line_2 }}</p>
                        @endif
                        <p>
                            {{ $order->billingAddress->city }}, 
                            {{ $order->billingAddress->state }} 
                            {{ $order->billingAddress->postal_code }}
                        </p>
                        <p>{{ $order->billingAddress->country }}</p>
                        <p class="mt-2">
                            <i class="ri-phone-line mr-1"></i> {{ $order->billingAddress->phone }}
                        </p>
                        <p>
                            <i class="ri-mail-line mr-1"></i> {{ $order->billingAddress->email }}
                        </p>
                    </div>
                    @else
                    <p class="text-gray-500">No billing address information available.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-xl shadow-soft overflow-hidden border border-accent/20 transition-all duration-300 hover:shadow-medium mb-8" data-aos="fade-up" data-aos-delay="400">
            <div class="card-header border-b border-accent/10 px-6 py-5 flex items-center">
                <i class="ri-shopping-basket-line text-xl text-primary/70 mr-3"></i>
                <h2 class="text-lg font-medium text-primary">Order Items</h2>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto -mx-6">
                    <table class="min-w-full divide-y divide-accent/10">
                        <thead class="bg-secondary/70">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-accent/10">
                            @foreach($order->items as $item)
                                <tr class="hover:bg-secondary/30 transition-colors duration-150">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            @if($item->product && isset($item->product->featured_image))
                                                <div class="flex-shrink-0 h-10 w-10 mr-4">
                                                    <img class="h-10 w-10 rounded object-cover" src="{{ asset($item->product->featured_image) }}" alt="{{ $item->product_name }}">
                                                </div>
                                            @else
                                                <div class="flex-shrink-0 h-10 w-10 bg-secondary rounded mr-4 flex items-center justify-center">
                                                    <i class="ri-box-3-line text-primary/50"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="text-sm font-medium text-primary">
                                                    {{ $item->product_name }}
                                                </div>
                                                @if($item->productVariation)
                                                    <div class="text-xs text-gray-500">
                                                        @foreach(json_decode($item->productVariation->options) as $key => $value)
                                                            <span>{{ ucfirst($key) }}: {{ $value }}</span>
                                                            @if(!$loop->last), @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                                @if($item->product)
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        <a href="{{ route('shop.product', $item->product->slug) }}" class="text-primary hover:text-primary/70">
                                                            View Product
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->formatted_unit_price ?? $item->unit_price }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary">
                                        {{ $item->formatted_subtotal ?? $item->subtotal }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-xl shadow-soft overflow-hidden border border-accent/20 transition-all duration-300 hover:shadow-medium" data-aos="fade-up" data-aos-delay="500">
            <div class="card-header border-b border-accent/10 px-6 py-5 flex items-center">
                <i class="ri-file-list-3-line text-xl text-primary/70 mr-3"></i>
                <h2 class="text-lg font-medium text-primary">Order Summary</h2>
            </div>
            <div class="p-6">
                <div class="md:w-1/2 md:ml-auto">
                    <div class="space-y-2">
                        <div class="flex justify-between py-2 border-b border-accent/10">
                            <span class="text-gray-600">Subtotal:</span>
                            <span>{{ $order->formatted_subtotal ?? $order->subtotal }}</span>
                        </div>
                        
                        <div class="flex justify-between py-2 border-b border-accent/10">
                            <span class="text-gray-600">Shipping:</span>
                            <span>{{ $order->formatted_shipping_cost ?? $order->shipping_cost ?? 'Free' }}</span>
                        </div>
                        
                        @if($order->tax)
                        <div class="flex justify-between py-2 border-b border-accent/10">
                            <span class="text-gray-600">Tax:</span>
                            <span>{{ $order->formatted_tax ?? $order->tax }}</span>
                        </div>
                        @endif
                        
                        @if($order->discount)
                        <div class="flex justify-between py-2 border-b border-accent/10">
                            <span class="text-gray-600">Discount:</span>
                            <span>-{{ $order->formatted_discount ?? $order->discount }}</span>
                        </div>
                        @endif
                        
                        <div class="flex justify-between py-3 font-medium text-primary">
                            <span>Total:</span>
                            <span class="text-lg">{{ $order->formatted_total ?? $order->total }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-between items-center mt-8">
            <a href="{{ route('account.orders') }}" class="inline-flex items-center px-5 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300">
                <i class="ri-arrow-left-line mr-2"></i> Back to Orders
            </a>
            
            @if($order->status !== 'cancelled' && $order->status !== 'completed')
                <a href="#" class="inline-flex items-center px-5 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300">
                    <i class="ri-customer-service-2-line mr-2"></i> Contact Support
                </a>
            @endif
        </div>
    </div>
</div>

<style>
/* Animations & Transitions */
.bg-white {
    transition: all 0.3s ease;
}

@media (max-width: 768px) {
    .md\:w-1\/2 {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS if available
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true
        });
    }
});
</script