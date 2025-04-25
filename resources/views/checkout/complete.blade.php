@extends('layouts.app')

@section('title', 'Order Confirmed')

@section('content')
    <div class="container py-12 mx-auto bg-secondary md:px-8 mb-12">
        <div class="mx-auto">
            <!-- Success message -->
            <div class="text-center mb-8">
                <div class="success-check">
                    <svg class="animate-draw" width="80" height="80" viewBox="0 0 80 80" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <circle cx="40" cy="40" r="38" stroke="#10B981" stroke-width="4" />
                        <path d="M25 40L35 50L55 30" stroke="#10B981" stroke-width="4" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </div>

                <h1 class="text-2xl sm:text-3xl font-semibold text-primary mt-6 mb-3">
                    Thank You for Your Order
                </h1>
                <p class="text-gray-600">
                    Your order <span class="font-medium text-primary">{{ $order->order_number }}</span> has been confirmed
                </p>
            </div>

            <!-- Order content -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-6">
                    <!-- Order Status Card -->
                    <div class="bg-white rounded-lg shadow-soft p-6 sm:p-8 border border-gray-200">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-primary">Order Details</h2>
                            <div class="px-3 py-1.5 bg-green-50 text-green-600 rounded-full text-sm font-medium">
                                <i class="ri-check-line mr-1"></i>
                                {{ ucfirst($order->status) }}
                            </div>
                        </div>

                        <!-- Timeline Progress -->
                        <div class="mb-14">
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

                        <!-- Order Information -->
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Order Date</p>
                                <p class="font-medium">{{ $order->created_at->format('F d, Y') }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Estimated Delivery</p>
                                <p class="font-medium">
                                    @if (isset($order->estimated_delivery_date))
                                        {{ $order->estimated_delivery_date->format('F d, Y') }}
                                    @else
                                        {{ $order->created_at->addDays(7)->format('F d, Y') }}
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Payment Method</p>
                                <p class="font-medium">{{ ucfirst($order->payment_method) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Payment Status</p>
                                <p
                                    class="font-medium {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600' }} flex items-center">
                                    @if ($order->payment_status === 'paid')
                                        <i class="ri-check-line mr-1"></i>
                                    @else
                                        <i class="ri-time-line mr-1"></i>
                                    @endif
                                    {{ ucfirst($order->payment_status) }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white rounded-lg shadow-soft p-6 sm:p-8 border border-gray-200">
                        <h3 class="text-lg font-semibold text-primary mb-6">Order Items</h3>

                        <div class="space-y-5">
                            @if ($order->items && count($order->items) > 0)
                                @foreach ($order->items as $item)
                                    <div
                                        class="flex items-center gap-4 hover:bg-gray-50 p-2 rounded-lg transition-colors duration-200">
                                        <div class="relative">
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                                                @if ($item->product && isset($item->product->image) && $item->product->image)
                                                    <img src="{{ $item->product->image }}" alt="{{ $item->product->name ?? 'Product' }}" class="w-full h-full object-cover">
                                                @else
                                                <img src="{{ url('/images/placeholder.jpg') }}" alt="Placeholder" class="w-full h-full object-cover">
                                                @endif
                                            </div>
                                            <div
                                                class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-primary text-white text-xs flex items-center justify-center rounded-full">
                                                {{ $item->quantity }}
                                            </div>
                                        </div>

                                        <div class="flex-1">
                                            <h4 class="font-medium text-gray-900">
                                                @if ($item->product)
                                                    {{ $item->product->name }}
                                                @else
                                                    {{ $item->name ?? 'Product' }}
                                                @endif
                                            </h4>
                                            @if (isset($item->options) && !empty($item->options))
                                                <p class="text-sm text-gray-500">
                                                    @if (is_array($item->options))
                                                        {{ implode(', ', $item->options) }}
                                                    @else
                                                        {{ $item->options }}
                                                    @endif
                                                </p>
                                            @endif
                                        </div>

                                        <div class="text-right">
                                            <p class="font-medium">{{ number_format($item->price, 2) }} €</p>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4 text-gray-500">
                                    No items in this order
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Shipping & Billing Addresses -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white rounded-lg shadow-soft p-6 border border-gray-200">
                            <h4 class="font-medium mb-4 flex items-center">
                                <i class="ri-truck-line mr-2 text-gray-500"></i>
                                Shipping Address
                            </h4>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                {{ $order->shipping_name }}<br>
                                {{ $order->shipping_address }}<br>
                                {{ $order->shipping_city }}, {{ $order->shipping_zip_code }}<br>
                                {{ $order->shipping_state }}<br>
                                {{ $order->shipping_country }}<br>
                                {{ $order->shipping_phone }}
                            </p>
                        </div>

                        <div class="bg-white rounded-lg shadow-soft p-6 border border-gray-200">
                            <h4 class="font-medium mb-4 flex items-center">
                                <i class="ri-file-list-line mr-2 text-gray-500"></i>
                                Billing Address
                            </h4>
                            <p class="text-gray-600 text-sm leading-relaxed">
                                {{ $order->billing_name }}<br>
                                {{ $order->billing_address }}<br>
                                {{ $order->billing_city }}, {{ $order->billing_zip_code }}<br>
                                {{ $order->billing_state }}<br>
                                {{ $order->billing_country }}<br>
                                {{ $order->billing_phone }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right column - Order summary -->
                <div class="lg:col-span-1 ">
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
                            <a href="{{ route('account.orders') }}"
                                class="flex items-center justify-center w-full bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary/90 transition-colors duration-200">
                                <i class="ri-file-list-3-line mr-2"></i>
                                View All Orders
                            </a>

                            <a href="{{ route('shop.index') }}"
                                class="flex items-center justify-center w-full bg-white border border-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                <i class="ri-shopping-bag-line mr-2"></i>
                                Continue Shopping
                            </a>

                            <a href="/acount/orders/{{ $order->id }}/print"
                                class="flex items-center justify-center w-full bg-white border border-gray-200 text-gray-800 px-6 py-3 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                <i class="ri-printer-line mr-2"></i>
                                Print Order
                            </a>
                        </div>

                        <!-- Support Information -->
                        <div class="mt-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <h4 class="font-medium mb-2 flex items-center">
                                <i class="ri-customer-service-line mr-2"></i>
                                Need Help?
                            </h4>
                            <p class="text-sm text-gray-600 mb-3">Our customer support team is here to assist you.</p>
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
        .success-check {
            width: 80px;
            height: 80px;
            margin: 0 auto;
        }

        .animate-draw {
            animation: appear 0.6s ease forwards;
        }

        .animate-draw circle {
            stroke-dasharray: 240;
            stroke-dashoffset: 240;
            animation: draw-circle 0.8s ease forwards;
        }

        .animate-draw path {
            stroke-dasharray: 50;
            stroke-dashoffset: 50;
            animation: draw-check 0.6s ease forwards 0.8s;
        }

        @keyframes draw-circle {
            to {
                stroke-dashoffset: 0;
            }
        }

        @keyframes draw-check {
            to {
                stroke-dashoffset: 0;
            }
        }

        @keyframes appear {
            0% {
                opacity: 0;
                transform: scale(0.8);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .shadow-soft {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            createConfetti();
        });

        function createConfetti() {
            const colors = ['#10B981', '#3B82F6', '#F59E0B', '#8B5CF6'];
            const confettiCount = 100;

            for (let i = 0; i < confettiCount; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti-piece';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.animationDelay = Math.random() * 3 + 's';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                document.body.appendChild(confetti);

                setTimeout(() => {
                    confetti.remove();
                }, 6000);
            }
        }
    </script>
@endsection
