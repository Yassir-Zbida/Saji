@extends('layouts.app')

@section('title', 'Order Complete - Thank you for your order')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Progress Bar -->
    <div class="bg-white border-b">
        <div class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between relative">
                <div class="absolute left-0 right-0 top-1/2 h-0.5 bg-gray-200 -translate-y-1/2"></div>
                <div class="absolute left-0 right-0 top-1/2 h-0.5 bg-primary transform -translate-y-1/2 origin-left" style="width: 100%;"></div>
                
                <div class="flex w-full justify-between relative">
                    <div class="flex flex-col items-center z-10">
                        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center">
                            <i class="ri-check-line"></i>
                        </div>
                        <span class="text-sm mt-2 font-medium">Cart</span>
                    </div>
                    <div class="flex flex-col items-center z-10">
                        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center">
                            <i class="ri-check-line"></i>
                        </div>
                        <span class="text-sm mt-2 font-medium">Shipping</span>
                    </div>
                    <div class="flex flex-col items-center z-10">
                        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center">
                            <i class="ri-check-line"></i>
                        </div>
                        <span class="text-sm mt-2 font-medium">Payment</span>
                    </div>
                    <div class="flex flex-col items-center z-10">
                        <div class="w-10 h-10 bg-primary text-white rounded-full flex items-center justify-center animate-bounce">
                            <i class="ri-check-line"></i>
                        </div>
                        <span class="text-sm mt-2 font-medium text-primary">Complete</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        <div class="max-w-3xl mx-auto">
            <!-- Thank You Message -->
            <div class="bg-white rounded-xl shadow-sm p-8 text-center mb-8 fade-in">
                <div class="success-animation mb-6">
                    <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                        <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                        <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Thank You For Your Order!</h1>
                <p class="text-gray-600 mb-6">Your order has been successfully placed and is being processed.</p>
                <div class="bg-gray-50 rounded-lg p-4 inline-block">
                    <p class="text-sm text-gray-600">Order Number</p>
                    <p class="text-lg font-semibold">{{ $order->order_number }}</p>
                </div>
            </div>

            <!-- Order Details -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-8 fade-in-delayed">
                <h2 class="text-xl font-semibold mb-6">Order Details</h2>
                
                <!-- Order Items -->
                <div class="divide-y divide-gray-200 mb-6">
                    @foreach($order->items as $item)
                        <div class="py-4 flex">
                            <div class="relative">
                                @if($item->product->images->first())
                                    <img src="{{ asset('storage/' . $item->product->images->first()->path) }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-16 h-16 object-cover rounded-lg">
                                @else
                                    <img src="{{ asset('images/placeholder.jpg') }}" 
                                         alt="{{ $item->product->name }}" 
                                         class="w-16 h-16 object-cover rounded-lg">
                                @endif
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-sm font-medium">{{ $item->product->name }}</h3>
                                @if($item->productVariation)
                                    <p class="text-xs text-gray-500">{{ $item->productVariation->name }}</p>
                                @endif
                                <p class="text-sm text-gray-600">Quantity: {{ $item->quantity }}</p>
                            </div>
                            <div class="text-sm font-medium">
                                {{ number_format($item->total, 2) }} €
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Order Totals -->
                <div class="border-t border-gray-200 pt-4 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Subtotal</span>
                        <span class="font-medium">{{ number_format($order->subtotal, 2) }} €</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Discount</span>
                            <span>-{{ number_format($order->discount_amount, 2) }} €</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Shipping</span>
                        <span class="font-medium">{{ number_format($order->shipping_amount, 2) }} €</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Tax</span>
                        <span class="font-medium">{{ number_format($order->tax_amount, 2) }} €</span>
                    </div>
                    <div class="flex justify-between text-lg font-semibold border-t border-gray-200 pt-3">
                        <span>Total</span>
                        <span>{{ number_format($order->total_amount, 2) }} €</span>
                    </div>
                </div>
            </div>

            <!-- Shipping & Billing Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <!-- Shipping Address -->
                <div class="bg-white rounded-xl shadow-sm p-6 fade-in-delayed-2">
                    <h2 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="ri-truck-line mr-2 text-primary"></i>
                        Shipping Address
                    </h2>
                    <div class="text-sm text-gray-600">
                        <p class="font-medium text-gray-900">{{ $order->shippingAddress->first_name }} {{ $order->shippingAddress->last_name }}</p>
                        <p>{{ $order->shippingAddress->address_line_1 }}</p>
                        @if($order->shippingAddress->address_line_2)
                            <p>{{ $order->shippingAddress->address_line_2 }}</p>
                        @endif
                        <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->postal_code }}</p>
                        <p>{{ $order->shippingAddress->country }}</p>
                        <p class="mt-2">Phone: {{ $order->shippingAddress->phone }}</p>
                        <p>Email: {{ $order->shippingAddress->email }}</p>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="bg-white rounded-xl shadow-sm p-6 fade-in-delayed-3">
                    <h2 class="text-lg font-semibold mb-4 flex items-center">
                        <i class="ri-bank-card-line mr-2 text-primary"></i>
                        Payment Information
                    </h2>
                    <div class="text-sm text-gray-600">
                        <p><span class="font-medium">Payment Method:</span> {{ ucfirst($order->payment_method) }}</p>
                        <p><span class="font-medium">Payment Status:</span> 
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $order->payment_status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </p>
                        @if($order->payment_method === 'bank_transfer' && $order->transactions->first())
                            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                                <p class="font-medium text-gray-900 mb-2">Bank Transfer Details</p>
                                <p>Account Name: {{ $order->transactions->first()->gateway_response['bank_details']['account_name'] }}</p>
                                <p>Account Number: {{ $order->transactions->first()->gateway_response['bank_details']['account_number'] }}</p>
                                <p>Bank Name: {{ $order->transactions->first()->gateway_response['bank_details']['bank_name'] }}</p>
                                <p>Reference: {{ $order->transactions->first()->gateway_response['bank_details']['reference'] }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Next Steps -->
            <div class="bg-white rounded-xl shadow-sm p-6 mb-8 fade-in-delayed-4">
                <h2 class="text-lg font-semibold mb-4">What's Next?</h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center">
                                <i class="ri-mail-line text-primary"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">Confirmation Email</h3>
                            <p class="text-sm text-gray-600">A confirmation email has been sent to your email address with order details.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center">
                                <i class="ri-time-line text-primary"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">Processing Time</h3>
                            <p class="text-sm text-gray-600">Your order will be processed within 1-2 business days.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-primary/10 rounded-full flex items-center justify-center">
                                <i class="ri-truck-line text-primary"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-900">Shipping Updates</h3>
                            <p class="text-sm text-gray-600">You'll receive shipping updates once your order is dispatched.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row justify-center gap-4 fade-in-delayed-5">
                @auth
                    <a href="{{ route('account.orders') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-primary hover:bg-primary-dark transition">
                        <i class="ri-file-list-3-line mr-2"></i>
                        View Orders
                    </a>
                @endauth
                
                <a href="{{ route('shop.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                    <i class="ri-shopping-bag-line mr-2"></i>
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    /* Success Animation */
    .checkmark {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: block;
        stroke-width: 2;
        stroke: #4bb543;
        stroke-miterlimit: 10;
        margin: 10% auto;
        box-shadow: inset 0px 0px 0px #4bb543;
        animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;
    }

    .checkmark__circle {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        stroke-width: 2;
        stroke-miterlimit: 10;
        stroke: #4bb543;
        fill: none;
        animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
    }

    .checkmark__check {
        transform-origin: 50% 50%;
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;
    }

    @keyframes stroke {
        100% {
            stroke-dashoffset: 0;
        }
    }

    @keyframes scale {
        0%, 100% {
            transform: none;
        }
        50% {
            transform: scale3d(1.1, 1.1, 1);
        }
    }

    @keyframes fill {
        100% {
            box-shadow: inset 0px 0px 0px 30px #4bb543;
        }
    }

    /* Fade Animations */
    .fade-in {
        animation: fadeIn 0.5s ease-out;
    }

    .fade-in-delayed {
        animation: fadeIn 0.5s ease-out 0.2s backwards;
    }

    .fade-in-delayed-2 {
        animation: fadeIn 0.5s ease-out 0.4s backwards;
    }

    .fade-in-delayed-3 {
        animation: fadeIn 0.5s ease-out 0.6s backwards;
    }

    .fade-in-delayed-4 {
        animation: fadeIn 0.5s ease-out 0.8s backwards;
    }

    .fade-in-delayed-5 {
        animation: fadeIn 0.5s ease-out 1s backwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection