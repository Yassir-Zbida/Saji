@extends('layouts.app')

@section('title', 'Secure Payment')

@section('content')
    <div class="container py-12 mx-auto bg-secondary md:px-8 mb-12">
        <div class="max-w-3xl mx-auto">
            <!-- Simple header -->
            <div class="text-center mb-8">
                <div class="mb-6">
                    <svg class="w-16 h-16 mx-auto" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 10V6C9 4.34315 10.3431 3 12 3C13.6569 3 15 4.34315 15 6V10" stroke="#111827" stroke-width="1.5" stroke-linecap="round"/>
                        <rect x="5" y="10" width="14" height="11" rx="2" stroke="#111827" stroke-width="1.5"/>
                        <circle cx="12" cy="16" r="1" fill="#111827"/>
                    </svg>
                </div>

                <h1 class="text-2xl font-semibold text-primary mb-2">
                    Complete Your Payment
                </h1>
                <p class="text-gray-600 max-w-md mx-auto">
                    Your order is almost ready. Please complete the payment to confirm.
                </p>
            </div>

            <!-- Main payment card -->
            <div class="bg-white rounded-lg shadow-soft p-6 sm:p-8 border border-gray-200">
                @if(session('error'))
                    <div class="bg-red-50 border border-red-100 rounded-lg p-4 mb-6">
                        <p class="text-red-700 text-sm">{{ session('error') }}</p>
                    </div>
                @endif

                <!-- Order total -->
                <div class="flex justify-between items-center mb-8 pb-6 border-b border-gray-100">
                    <span class="text-gray-600">Total amount</span>
                    <span class="text-2xl font-semibold text-primary">{{ number_format($order->total_amount, 2) }} €</span>
                </div>

                <!-- Payment button -->
                <div class="mb-8">
                    <a href="{{ route('stripe.checkout', ['order' => $order->id]) }}"
                        class="flex items-center justify-center w-full bg-primary text-white px-6 py-4 rounded-lg hover:bg-primary/90 transition-colors duration-200 text-lg font-medium">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 12V8C21 6.89543 20.1046 6 19 6H5C3.89543 6 3 6.89543 3 8V16C3 17.1046 3.89543 18 5 18H13" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7 15C7 15 8.5 14 10 14C11.5 14 13 15 13 15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M17 10.01L17.01 9.99889" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M3 10H21" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="18" cy="17" r="3" stroke="currentColor" stroke-width="2"/>
                            <path d="M16.5 17L17.5 17.8L19.5 16" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Pay with Stripe
                    </a>
                    <p class="text-sm text-center text-gray-500 mt-3">
                        You'll be redirected to Stripe's secure payment platform
                    </p>
                </div>

                <!-- Simple security indicators -->
                <div class="flex items-center justify-center space-x-6 pt-2">
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-1.5" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.3333 7.33333V5.33333C13.3333 2.75 11.25 0.666667 8.66667 0.666667C6.08333 0.666667 4 2.75 4 5.33333V7.33333M4.66667 15.3333H12.6667C13.4 15.3333 14 14.7333 14 14V8.66667C14 7.93333 13.4 7.33333 12.6667 7.33333H4.66667C3.93333 7.33333 3.33333 7.93333 3.33333 8.66667V14C3.33333 14.7333 3.93333 15.3333 4.66667 15.3333Z" stroke="#6B7280" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Secure payment
                    </div>
                    <div class="flex items-center text-sm text-gray-500">
                        <svg class="w-4 h-4 mr-1.5" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.00001 14.6667C11.6819 14.6667 14.6667 11.6819 14.6667 8.00001C14.6667 4.31811 11.6819 1.33334 8.00001 1.33334C4.31811 1.33334 1.33334 4.31811 1.33334 8.00001C1.33334 11.6819 4.31811 14.6667 8.00001 14.6667Z" stroke="#6B7280" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M5.33334 8.00001L7.33334 10L10.6667 6.66667" stroke="#6B7280" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Protected data
                    </div>
                </div>
            </div>

            <!-- Back link -->
            <div class="mt-6 text-center">
                <a href="{{ route('cart.index') }}" class="text-primary hover:underline text-sm inline-flex items-center">
                    <svg class="w-3 h-3 mr-1" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M7.5 9.5L4 6L7.5 2.5" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Return to cart
                </a>
            </div>
        </div>
    </div>

    <style>
        .shadow-soft {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
    </style>
@endsection