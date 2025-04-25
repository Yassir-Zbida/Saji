@extends('layouts.app')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="py-12 bg-secondary">
    <div class="container mx-auto md:px-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="text-sm text-gray-500 mb-4">
                <ol class="flex items-center flex-wrap">
                    <li><a href="/" class="hover:text-primary">Home</a></li>
                    <li class="mx-2">/</li>
                    <li><a href="{{ route('account.index') }}" class="hover:text-primary">My Account</a></li>
                    <li class="mx-2">/</li>
                    <li><a href="{{ route('account.orders') }}" class="hover:text-primary">My Orders</a></li>
                    <li class="mx-2">/</li>
                    <li class="text-primary">Order #{{ $order->order_number }}</li>
                </ol>
            </nav>
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h1 class="text-3xl md:text-4xl font-bold text-primary">Order #{{ $order->order_number }}</h1>
                <a href="{{ route('account.orders') }}" class="inline-flex items-center text-sm font-medium px-4 py-2 rounded-lg border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="ri-arrow-left-line mr-2"></i> Back to Orders
                </a>
            </div>
            <p class="text-gray-600 mt-2 font-jost">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
            <p>{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
            <p>{{ session('error') }}</p>
        </div>
        @endif

        <!-- Order Status -->
        <div class="bg-white rounded-xl shadow-soft overflow-hidden border border-gray-200 transition-all duration-300 hover:shadow-sm mb-8">
            <div class="card-header border border-gray-200 border-b border-accent/10 px-6 py-5 flex items-center justify-between">
                <div class="flex items-center">
                    <i class="ri-information-line text-xl text-primary/70 mr-3"></i>
                    <h2 class="text-lg font-medium text-primary">Order Status</h2>
                </div>
                @php
                    $statusClasses = [
                        'completed' => [
                            'bg' => 'bg-green-50', 
                            'text' => 'text-green-700',
                            'border' => 'border border-green-200',
                            'icon' => 'ri-check-line'
                        ],
                        'processing' => [
                            'bg' => 'bg-blue-50', 
                            'text' => 'text-blue-700',
                            'border' => 'border border-blue-200',
                            'icon' => 'ri-loader-2-line'
                        ],
                        'shipped' => [
                            'bg' => 'bg-indigo-50', 
                            'text' => 'text-indigo-700',
                            'border' => 'border border-indigo-200',
                            'icon' => 'ri-truck-line'
                        ],
                        'cancelled' => [
                            'bg' => 'bg-red-50', 
                            'text' => 'text-red-700',
                            'border' => 'border border-red-200',
                            'icon' => 'ri-close-line'
                        ],
                        'pending' => [
                            'bg' => 'bg-yellow-50', 
                            'text' => 'text-yellow-700',
                            'border' => 'border border-yellow-200',
                            'icon' => 'ri-time-line'
                        ],
                        'delivered' => [
                            'bg' => 'bg-emerald-50', 
                            'text' => 'text-emerald-700',
                            'border' => 'border border-emerald-200',
                            'icon' => 'ri-checkbox-circle-line'
                        ],
                        'default' => [
                            'bg' => 'bg-gray-50', 
                            'text' => 'text-gray-700',
                            'border' => 'border border-gray-200',
                            'icon' => 'ri-information-line'
                        ]
                    ];
                    
                    $status = strtolower($order->status);
                    $statusClass = $statusClasses[$status] ?? $statusClasses['default'];
                @endphp
                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium {{ $statusClass['bg'] }} {{ $statusClass['text'] }} {{ $statusClass['border'] }}">
                    <i class="{{ $statusClass['icon'] }} mr-2"></i>
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <div class="p-6">
                <!-- Timeline Progress -->
                <div class="mb-12">
                    <div class="relative h-20 w-[94%] mx-auto">
                        <!-- Base Line -->
                        <div class="absolute top-6 left-0 right-0 h-[2px] bg-gray-200 z-0"></div>

                        <!-- Progress Line (filled based on order status) -->
                        @php
                            $statusStep = 1;
                            $progressPercentage = 25;

                            if ($order->status == 'processing') {
                                $statusStep = 2;
                                $progressPercentage = 50;
                            } elseif ($order->status == 'shipped') {
                                $statusStep = 3;
                                $progressPercentage = 75;
                            } elseif ($order->status == 'completed' || $order->status == 'delivered') {
                                $statusStep = 4;
                                $progressPercentage = 100;
                            }
                        @endphp
                        <div class="absolute top-6 left-0 h-[2px] bg-primary z-1"
                            style="width: {{ $progressPercentage }}%;"></div>

                        <!-- Steps Container -->
                        <div class="flex justify-between w-full relative">
                            <!-- Step 1: Order Placed -->
                            <div class="flex flex-col relative z-10 pt-[0px]">
                                <div
                                    class="w-12 h-12 rounded-full flex items-center justify-center {{ $statusStep >= 1 ? 'bg-black text-white shadow-md' : 'bg-white text-gray-400 border border-gray-200 shadow-sm' }}">
                                    <i class="ri-shopping-cart-line text-lg"></i>
                                </div>
                                <span
                                    class="text-sm {{ $statusStep >= 1 ? 'font-medium text-primary' : 'text-gray-500' }} mt-2 -ml-6">Order
                                    Placed</span>
                            </div>

                            <!-- Step 2: Processing -->
                            <div class="flex flex-col items-center relative z-10 pt-[0px]">
                                <div
                                    class="w-12 h-12 rounded-full flex items-center justify-center {{ $statusStep >= 2 ? 'bg-black text-white shadow-md' : 'bg-white text-gray-400 border border-gray-200 shadow-sm' }}">
                                    <i class="ri-loader-line text-lg"></i>
                                </div>
                                <span
                                    class="text-sm {{ $statusStep >= 2 ? 'font-medium text-primary' : 'text-gray-500' }} mt-2">Processing</span>
                            </div>

                            <!-- Step 3: Shipped -->
                            <div class="flex flex-col items-center relative z-10 pt-[0px]">
                                <div
                                    class="w-12 h-12 rounded-full flex items-center justify-center {{ $statusStep >= 3 ? 'bg-black text-white shadow-md' : 'bg-white text-gray-400 border border-gray-200 shadow-sm' }}">
                                    <i class="ri-truck-line text-lg"></i>
                                </div>
                                <span
                                    class="text-sm {{ $statusStep >= 3 ? 'font-medium text-primary' : 'text-gray-500' }} mt-2">Shipped</span>
                            </div>

                            <!-- Step 4: Delivered -->
                            <div class="flex flex-col items-end relative z-10 pt-[0px]">
                                <div
                                    class="w-12 h-12 rounded-full flex items-center justify-center {{ $statusStep >= 4 ? 'bg-black text-white shadow-md' : 'bg-white text-gray-400 border border-gray-200 shadow-sm' }}">
                                    <i class="ri-checkbox-circle-line text-lg"></i>
                                </div>
                                <span
                                    class="text-sm {{ $statusStep >= 4 ? 'font-medium text-primary' : 'text-gray-500' }} mt-2 -mr-2">Delivered</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap md:flex-nowrap gap-8">
                    <div class="w-full md:w-1/2">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Order Details</h3>
                        <ul class="space-y-3">
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
                                <span>{{ ucfirst($order->payment_method ?? 'Not specified') }}</span>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="text-gray-600">Payment Status:</span>
                                @php
                                    $paymentStatusClass = [
                                        'paid' => 'bg-green-50 text-green-700 border border-green-200',
                                        'pending' => 'bg-yellow-50 text-yellow-700 border border-yellow-200',
                                        'failed' => 'bg-red-50 text-red-700 border border-red-200',
                                        'default' => 'bg-gray-50 text-gray-700 border border-gray-200'
                                    ];
                                    $paymentStatus = strtolower($order->payment_status ?? 'unknown');
                                    $paymentClass = $paymentStatusClass[$paymentStatus] ?? $paymentStatusClass['default'];
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 text-xs rounded-full font-medium {{ $paymentClass }}">
                                    @if($paymentStatus == 'paid')
                                        <i class="ri-check-line mr-1"></i>
                                    @elseif($paymentStatus == 'pending')
                                        <i class="ri-time-line mr-1"></i>
                                    @elseif($paymentStatus == 'failed')
                                        <i class="ri-close-line mr-1"></i>
                                    @else
                                        <i class="ri-question-line mr-1"></i>
                                    @endif
                                    {{ ucfirst($paymentStatus) }}
                                </span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="w-full md:w-1/2">
                        <h3 class="text-sm font-semibold text-gray-500 uppercase mb-3">Shipping Details</h3>
                        <ul class="space-y-3">
                            <li class="flex justify-between">
                                <span class="text-gray-600">Shipping Method:</span>
                                <span>{{ $order->shipping_method ?? 'Standard Shipping' }}</span>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="text-gray-600">Shipping Status:</span>
                                <span class="inline-flex items-center px-2 py-1 text-xs rounded-full font-medium {{ $statusClass['bg'] }} {{ $statusClass['text'] }} {{ $statusClass['border'] }}">
                                    <i class="{{ $statusClass['icon'] }} mr-1"></i>
                                    {{ ucfirst($order->status) }}
                                </span>
                            </li>
                            @if(isset($order->tracking_number) && $order->tracking_number)
                            <li class="flex justify-between">
                                <span class="text-gray-600">Tracking Number:</span>
                                <span class="font-medium">{{ $order->tracking_number }}</span>
                            </li>
                            @endif
                            @if(isset($order->estimated_delivery))
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
            <div class="bg-white rounded-xl shadow-soft overflow-hidden border border-gray-200 transition-all duration-300 hover:shadow-medium border border-gray-200">
                <div class="card-header border-b border-accent/10 px-6 py-5 flex items-center">
                    <i class="ri-truck-line text-xl text-primary/70 mr-3"></i>
                    <h2 class="text-lg font-medium text-primary">Shipping Address</h2>
                </div>
                <div class="p-6">
                    @if(isset($order->shippingAddress) && $order->shippingAddress)
                    <div class="text-gray-600 leading-relaxed">
                        <p class="font-medium text-primary mb-2">
                            {{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}
                        </p>
                        @if(isset($order->shippingAddress->company) && $order->shippingAddress->company)
                            <p>{{ $order->shippingAddress->company }}</p>
                        @endif
                        <p>{{ $order->shippingAddress->address_line_1 }}</p>
                        @if(isset($order->shippingAddress->address_line_2) && $order->shippingAddress->address_line_2)
                            <p>{{ $order->shippingAddress->address_line_2 }}</p>
                        @endif
                        <p>
                            {{ $order->shippingAddress->city }}, 
                            {{ $order->shippingAddress->state }} 
                            {{ $order->shippingAddress->postal_code }}
                        </p>
                        <p>{{ $order->shippingAddress->country }}</p>
                        <p class="mt-2 flex items-center">
                            <i class="ri-phone-line mr-2 text-primary/70"></i> {{ $order->shippingAddress->phone }}
                        </p>
                        <p class="flex items-center">
                            <i class="ri-mail-line mr-2 text-primary/70"></i> {{ $order->shippingAddress->email }}
                        </p>
                    </div>
                    @elseif(isset($order->shipping_name) && $order->shipping_name)
                    <div class="text-gray-600 leading-relaxed">
                        <p class="font-medium text-primary mb-2">{{ $order->shipping_name }}</p>
                        <p>{{ $order->shipping_address }}</p>
                        <p>
                            {{ $order->shipping_city }}, 
                            {{ $order->shipping_state }} 
                            {{ $order->shipping_zip_code }}
                        </p>
                        <p>{{ $order->shipping_country }}</p>
                        @if(isset($order->shipping_phone) && $order->shipping_phone)
                        <p class="mt-2 flex items-center">
                            <i class="ri-phone-line mr-2 text-primary/70"></i> {{ $order->shipping_phone }}
                        </p>
                        @endif
                    </div>
                    @else
                    <p class="text-gray-500">No shipping address information available.</p>
                    @endif
                </div>
            </div>

            <!-- Billing Address -->
            <div class="bg-white rounded-xl shadow-soft overflow-hidden border border-gray-200 transition-all duration-300 hover:shadow-medium">
                <div class="card-header border-b border-accent/10 px-6 py-5 flex items-center">
                    <i class="ri-bill-line text-xl text-primary/70 mr-3"></i>
                    <h2 class="text-lg font-medium text-primary">Billing Address</h2>
                </div>
                <div class="p-6">
                    @if(isset($order->billingAddress) && $order->billingAddress)
                    <div class="text-gray-600 leading-relaxed">
                        <p class="font-medium text-primary mb-2">
                            {{ $order->billingAddress->first_name }} {{ $order->billingAddress->last_name }}
                        </p>
                        @if(isset($order->billingAddress->company) && $order->billingAddress->company)
                            <p>{{ $order->billingAddress->company }}</p>
                        @endif
                        <p>{{ $order->billingAddress->address_line_1 }}</p>
                        @if(isset($order->billingAddress->address_line_2) && $order->billingAddress->address_line_2)
                            <p>{{ $order->billingAddress->address_line_2 }}</p>
                        @endif
                        <p>
                            {{ $order->billingAddress->city }}, 
                            {{ $order->billingAddress->state }} 
                            {{ $order->billingAddress->postal_code }}
                        </p>
                        <p>{{ $order->billingAddress->country }}</p>
                        <p class="mt-2 flex items-center">
                            <i class="ri-phone-line mr-2 text-primary/70"></i> {{ $order->billingAddress->phone }}
                        </p>
                        <p class="flex items-center">
                            <i class="ri-mail-line mr-2 text-primary/70"></i> {{ $order->billingAddress->email }}
                        </p>
                    </div>
                    @elseif(isset($order->billing_name) && $order->billing_name)
                    <div class="text-gray-600 leading-relaxed">
                        <p class="font-medium text-primary mb-2">{{ $order->billing_name }}</p>
                        <p>{{ $order->billing_address }}</p>
                        <p>
                            {{ $order->billing_city }}, 
                            {{ $order->billing_state }} 
                            {{ $order->billing_zip_code }}
                        </p>
                        <p>{{ $order->billing_country }}</p>
                        @if(isset($order->billing_phone) && $order->billing_phone)
                        <p class="mt-2 flex items-center">
                            <i class="ri-phone-line mr-2 text-primary/70"></i> {{ $order->billing_phone }}
                        </p>
                        @endif
                    </div>
                    @else
                    <p class="text-gray-500">No billing address information available.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-xl shadow-soft overflow-hidden border border-gray-200 transition-all duration-300 hover:shadow-medium mb-8">
            <div class="card-header border-b border-accent/10 px-6 py-5 flex items-center justify-between">
                <div class="flex items-center">
                    <i class="ri-shopping-basket-line text-xl text-primary/70 mr-3"></i>
                    <h2 class="text-lg font-medium text-primary">Order Items</h2>
                </div>
                @if(isset($order->items_count) && $order->items_count)
                <span class="bg-primary/10 text-primary text-sm font-medium px-3 py-1 rounded-full">
                    {{ $order->items_count }} {{ Str::plural('item', $order->items_count) }}
                </span>
                @elseif(isset($order->items) && count($order->items) > 0)
                <span class="bg-primary/10 text-primary text-sm font-medium px-3 py-1 rounded-full">
                    {{ count($order->items) }} {{ Str::plural('item', count($order->items)) }}
                </span>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-accent/10">
                    <thead class="bg-secondary/70">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-accent/10">
                        @foreach($order->items as $item)
                            <tr class="hover:bg-secondary/30 transition-colors duration-150">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-16 w-16 mr-4 rounded overflow-hidden shadow-sm border border-gray-200">
                                            @if(isset($item->product) && isset($item->product->featured_image))
                                                <img class="h-16 w-16 object-cover" src="{{ asset($item->product->featured_image) }}" alt="{{ $item->product_name ?? $item->name ?? 'Product' }}">
                                            @elseif(isset($item->product) && isset($item->product->image))
                                                <img class="h-16 w-16 object-cover" src="{{ asset($item->product->image) }}" alt="{{ $item->product_name ?? $item->name ?? 'Product' }}">
                                            @else
                                                <div class="h-16 w-16 bg-secondary flex items-center justify-center">
                                                    <i class="ri-box-3-line text-2xl text-primary/50"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-primary">
                                                {{ $item->product_name ?? $item->name ?? ($item->product ? $item->product->name : 'Product') }}
                                            </div>
                                            @if(isset($item->productVariation) && $item->productVariation && isset($item->productVariation->options))
                                                <div class="text-xs text-gray-500 mt-1">
                                                    @foreach(json_decode($item->productVariation->options) as $key => $value)
                                                        <span>{{ ucfirst($key) }}: {{ $value }}</span>
                                                        @if(!$loop->last), @endif
                                                    @endforeach
                                                </div>
                                            @elseif(isset($item->options) && $item->options)
                                                <div class="text-xs text-gray-500 mt-1">
                                                    @if(is_array($item->options))
                                                        {{ implode(', ', $item->options) }}
                                                    @else
                                                        {{ $item->options }}
                                                    @endif
                                                </div>
                                            @endif
                                            @if(isset($item->product) && $item->product && isset($item->product->slug))
                                                <div class="text-xs text-gray-500 mt-2">
                                                    <a href="{{ route('shop.product', $item->product->slug) }}" class="text-primary hover:text-primary/70 flex items-center">
                                                        <span>View Product</span>
                                                        <i class="ri-external-link-line ml-1"></i>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->formatted_unit_price ?? $item->formatted_price ?? number_format($item->unit_price ?? $item->price, 2) }} €
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                    <span class="inline-flex items-center justify-center bg-gray-100 text-gray-800 w-8 h-8 rounded">
                                        {{ $item->quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary text-right">
                                    {{ $item->formatted_subtotal ?? $item->formatted_total ?? number_format(($item->unit_price ?? $item->price) * $item->quantity, 2) }} €
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Only include tracking info if order is shipped and has tracking number -->
            @if($order->status == 'shipped' && isset($order->tracking_number) && $order->tracking_number)
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-soft overflow-hidden border border-gray-200 transition-all duration-300 hover:shadow-medium mb-8">
                    <div class="card-header border-b border-accent/10 px-6 py-5 flex items-center">
                        <i class="ri-map-pin-line text-xl text-primary/70 mr-3"></i>
                        <h2 class="text-lg font-medium text-primary">Tracking Information</h2>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-secondary/40 rounded-lg">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Tracking Number</p>
                                <p class="font-medium">{{ $order->tracking_number }}</p>
                            </div>
                            <div class="mt-3 sm:mt-0">
                                <p class="text-sm text-gray-500 mb-1">Carrier</p>
                                <p class="font-medium">{{ $order->shipping_carrier ?? 'Standard Shipping' }}</p>
                            </div>
                            <div class="mt-4 sm:mt-0">
                                <a href="{{ $order->tracking_url ?? '#' }}" target="_blank" 
                                class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                                    <i class="ri-roadmap-line mr-2"></i>
                                    Track Package
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-1">
            @else
            <div class="lg:col-span-3">
            @endif
                <div class="bg-white rounded-xl shadow-soft overflow-hidden border border-gray-200 transition-all duration-300 hover:shadow-medium">
                    <div class="card-header border-b border-accent/10 px-6 py-5 flex items-center">
                        <i class="ri-file-list-3-line text-xl text-primary/70 mr-3"></i>
                        <h2 class="text-lg font-medium text-primary">Order Summary</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b border-accent/10">
                                <span class="text-gray-600">Subtotal:</span>
                                <span>{{ $order->formatted_subtotal ?? $order->subtotal ?? number_format(($order->total_amount ?? 0) - ($order->tax_amount ?? 0) - ($order->shipping_amount ?? 0), 2) }} €</span>
                            </div>
                            
                            <div class="flex justify-between py-2 border-b border-accent/10">
                                <span class="text-gray-600">Shipping:</span>
                                <span>{{ $order->formatted_shipping_cost ?? ($order->shipping_amount ? number_format($order->shipping_amount, 2) . ' €' : 'Free') }}</span>
                            </div>
                            
                            @if(isset($order->tax_amount) && $order->tax_amount > 0)
                            <div class="flex justify-between py-2 border-b border-accent/10">
                                <span class="text-gray-600">Tax:</span>
                                <span>{{ $order->formatted_tax ?? number_format($order->tax_amount, 2) }} €</span>
                            </div>
                            @endif
                            
                            @if(isset($order->discount_amount) && $order->discount_amount > 0)
                            <div class="flex justify-between py-2 border-b border-accent/10">
                                <span class="text-gray-600">Discount:</span>
                                <span class="text-green-600">-{{ $order->formatted_discount ?? number_format($order->discount_amount, 2) }} €</span>
                            </div>
                            @endif
                            
                            <div class="flex justify-between py-3 font-medium text-primary">
                                <span>Total:</span>
                                <span class="text-lg">{{ $order->formatted_total ?? number_format($order->total_amount, 2) }} €</span>
                            </div>
                        </div>
                        
                        <div class="mt-8 space-y-3 hidden">
                            @if($order->status !== 'cancelled')
                                @if($order->status !== 'delivered' && $order->status !== 'completed')
                                    <form action="/" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit"
                                            class="flex items-center justify-center w-full bg-white border border-red-300 text-red-600 px-4 py-3 rounded-lg hover:bg-red-50 transition-colors duration-200">
                                            <i class="ri-close-circle-line mr-2"></i>
                                            Cancel Order
                                        </button>
                                    </form>
                                @endif
                            
                                @if($order->status == 'delivered' || $order->status == 'completed')
                                    <a href="{{ route('account.reviews.create', ['order_id' => $order->id]) ?? '#' }}"
                                        class="flex items-center justify-center w-full bg-white border border-primary text-primary px-4 py-3 rounded-lg hover:bg-primary hover:text-white transition-colors duration-200">
                                        <i class="ri-star-line mr-2"></i>
                                        Review Products
                                    </a>
                                @endif
                                
                                @if(Route::has('account.orders.reorder'))
                                    <a href="{{ route('account.orders.reorder', $order->id) }}"
                                        class="flex items-center justify-center w-full bg-primary text-white px-4 py-3 rounded-lg hover:bg-primary/90 transition-colors duration-200">
                                        <i class="ri-refresh-line mr-2"></i>
                                        Reorder
                                    </a>
                                @endif
                            @endif
                            
                            <a href="{{ route('account.orders') }}"
                                class="flex items-center justify-center w-full bg-white border border-gray-200 text-gray-800 px-4 py-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                <i class="ri-arrow-left-line mr-2"></i>
                                Back to Orders
                            </a>
                        </div>
                        
                        <!-- Support Information -->
                        <div class="mt-8 p-4 bg-secondary/40 rounded-lg border border-gray-200">
                            <h4 class="font-medium mb-2 flex items-center">
                                <i class="ri-customer-service-line mr-2"></i>
                                Need Help?
                            </h4>
                            <p class="text-sm text-gray-600 mb-3">Our customer support team is here to assist you.</p>
                            @if (Route::has('account.tickets.create'))
                                <a href="{{ route('account.tickets.create', ['order_id' => $order->id]) }}"
                                    class="text-primary text-sm font-medium hover:underline inline-flex items-center">
                                    Contact Support <i class="ri-arrow-right-line ml-1"></i>
                                </a>
                            @else
                                <a href="{{ route('contact') }}"
                                    class="text-primary text-sm font-medium hover:underline inline-flex items-center">
                                    Contact Support <i class="ri-arrow-right-line ml-1"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Animations & Transitions */
.bg-white {
    transition: all 0.3s ease;
}

.shadow-soft {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.shadow-medium {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

@media (max-width: 768px) {
    .md\:w-1\/2 {
        width: 100%;
    }
    
    table {
        display: block;
    }
    
    thead {
        display: none;
    }
    
    tbody {
        display: block;
    }
    
    tr {
        display: flex;
        flex-direction: column;
        padding: 1rem;
        border-bottom: 1px solid #f3f4f6;
    }
    
    tr:last-child {
        border-bottom: none;
    }
    
    td {
        display: flex;
        padding: 0.5rem 0;
        border: none;
        text-align: right;
        justify-content: space-between;
        align-items: center;
    }
    
    td:before {
        content: attr(data-label);
        font-weight: 500;
        color: #6b7280;
        text-align: left;
    }
    
    td:first-child {
        padding-top: 1rem;
    }
    
    td:last-child {
        padding-bottom: 1rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize AOS if available
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 600,
            easing: 'ease-out-cubic',
            once: true,
            startEvent: 'DOMContentLoaded',
            disable: 'mobile',
            delay: 0
        });
    }
    
    // Add data-label attributes to table cells for responsive view
    const tables = document.querySelectorAll('table');
    tables.forEach(table => {
        const headerCells = table.querySelectorAll('thead th');
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            cells.forEach((cell, index) => {
                if(headerCells[index]) {
                    const headerText = headerCells[index].textContent.trim();
                    cell.setAttribute('data-label', headerText);
                }
            });
        });
    });
});
</script>
@endsection