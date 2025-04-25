@extends('layouts.app')
@section('title', 'Checkout')
@section('content')
    <script src="{{ asset('js/checkout.js') }}"></script>

    <div class="checkout-container min-h-screen bg-gray-50 py-8 md:px-4">
        <div class="container mx-auto px-4">
            <!-- Breadcrumb -->
            <div class="flex items-center text-sm mb-8">
                <a href="/" class="text-gray-500 hover:text-primary">Home</a>
                <span class="mx-2 text-gray-400">/</span>
                <a href="{{ route('cart.index') }}" class="text-gray-500 hover:text-primary">Cart</a>
                <span class="mx-2 text-gray-400">/</span>
                <span class="font-medium">Checkout</span>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Left Column - Checkout Form -->
                <div class="lg:w-3/5">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6">
                            <!-- Steps Progress -->
                            <div class="checkout-steps mb-8">
                                <div class="flex justify-between items-center">
                                    <div class="step active" data-step="1">
                                        <div class="step-icon">
                                            <i class="ri-user-line"></i>
                                        </div>
                                        <span class="step-title">Information</span>
                                    </div>
                                    <div class="step" data-step="2">
                                        <div class="step-icon">
                                            <i class="ri-truck-line"></i>
                                        </div>
                                        <span class="step-title">Shipping</span>
                                    </div>
                                    <div class="step" data-step="3">
                                        <div class="step-icon">
                                            <i class="ri-bank-card-line"></i>
                                        </div>
                                        <span class="step-title">Payment</span>
                                    </div>
                                </div>
                                <div class="progress-bar mt-4">
                                    <div class="progress" style="width: 33.33%"></div>
                                </div>
                            </div>

                            <!-- Checkout Form -->
                            <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST">
                                @csrf

                                <!-- Step 1: Information -->
                                <div class="step-content active" data-step="1">
                                    <h2 class="text-xl font-semibold mb-6">Contact Information</h2>

                                    @guest
                                        <div class="mb-6">
                                            <p class="text-sm text-gray-600 mb-4">
                                                Already have an account?
                                                <a href="{{ route('login') }}" class="text-primary hover:underline">Log in</a>
                                            </p>
                                        </div>
                                    @endguest

                                    <div class="grid gap-6">
                                        <div>
                                            <label for="email"
                                                class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                            <input type="email" id="email" name="email" required
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                                value="{{ auth()->check() ? auth()->user()->email : '' }}">
                                        </div>
                                    </div>

                                    <h3 class="text-lg font-semibold mt-8 mb-4">Shipping Address</h3>

                                    @if (auth()->check() && $shippingAddresses->count() > 0)
                                        <div class="mb-6">
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Saved
                                                Addresses</label>
                                            <div class="grid gap-4">
                                                <div class="flex items-center">
                                                    <input type="radio" name="shipping_address_type" value="new"
                                                        checked
                                                        class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                                    <label class="ml-2 text-sm text-gray-700">Use a new address</label>
                                                </div>
                                                @foreach ($shippingAddresses as $address)
                                                    <div class="flex items-center">
                                                        <input type="radio" name="shipping_address_type" value="existing"
                                                            id="shipping_{{ $address->id }}"
                                                            data-address="{{ json_encode($address) }}"
                                                            class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                                        <label for="shipping_{{ $address->id }}"
                                                            class="ml-2 text-sm text-gray-700">
                                                            {{ $address->address_line_1 }}, {{ $address->city }}
                                                            {{ $address->postal_code }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <input type="hidden" name="shipping_address_type" value="new">
                                    @endif

                                    <div id="shipping-address-form" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                                            <input type="text" name="shipping_first_name" required
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                                            <input type="text" name="shipping_last_name" required
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Company
                                                (Optional)</label>
                                            <input type="text" name="shipping_company"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                            <input type="text" name="shipping_address_line_1" required
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                                placeholder="Street address">
                                        </div>
                                        <div class="md:col-span-2">
                                            <input type="text" name="shipping_address_line_2"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                                placeholder="Apartment, suite, etc. (optional)">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                            <input type="text" name="shipping_city" required
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                        </div>
                                        <div>
                                            <label
                                                class="block text-sm font-medium text-gray-700 mb-1">State/Province</label>
                                            <input type="text" name="shipping_state" required
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Postal Code</label>
                                            <input type="text" name="shipping_postal_code" required
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                            <select name="shipping_country" required
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                                <option value="FR" selected>France</option>
                                                <option value="BE">Belgium</option>
                                                <option value="DE">Germany</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                            <input type="tel" name="shipping_phone" required
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                            <input type="email" name="shipping_email" required
                                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                                value="{{ auth()->check() ? auth()->user()->email : '' }}">
                                        </div>
                                    </div>

                                    @if (auth()->check())
                                        <div class="mt-6">
                                            <label class="inline-flex items-center">
                                                <input type="checkbox" name="shipping_save_address" value="1"
                                                    checked
                                                    class="rounded border-gray-300 text-primary focus:ring-primary">
                                                <span class="ml-2 text-sm text-gray-600">Save address for future
                                                    orders</span>
                                            </label>
                                        </div>
                                    @endif

                                    <div class="mt-8 flex justify-between">
                                        <a href="{{ route('cart.index') }}"
                                            class="text-primary hover:underline flex items-center">
                                            <i class="ri-arrow-left-line mr-2"></i> Return to cart
                                        </a>
                                        <button type="button"
                                            class="step-next bg-primary text-white px-8 py-3 rounded-lg hover:bg-primary-dark transition">
                                            Continue to shipping
                                        </button>
                                    </div>
                                </div>

                                <!-- Step 2: Shipping -->
                                <div class="step-content" data-step="2">
                                    <h2 class="text-xl font-semibold mb-6">Shipping Method</h2>

                                    <div class="shipping-methods space-y-4">
                                        <div class="shipping-method border border-gray-300 rounded-lg p-4 cursor-pointer">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <input type="radio" name="shipping_method" value="standard" checked
                                                        class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                                    <div class="ml-3">
                                                        <p class="font-medium">Standard Shipping</p>
                                                        <p class="text-sm text-gray-500">3-5 business days</p>
                                                    </div>
                                                </div>
                                                <span class="font-medium">{{ number_format($shipping, 2) }} €</span>
                                            </div>
                                        </div>

                                        <!-- Add express shipping option later if needed -->
                                    </div>

                                    <h3 class="text-lg font-semibold mt-8 mb-4">Billing Address</h3>

                                    <div class="mb-6">
                                        <div class="flex items-center mb-4">
                                            <input type="radio" name="billing_address_type" value="same_as_shipping"
                                                checked class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                            <label class="ml-2 text-sm text-gray-700">Same as shipping address</label>
                                        </div>

                                        @if (auth()->check() && $billingAddresses->count() > 0)
                                            <div class="flex items-center mb-4">
                                                <input type="radio" name="billing_address_type" value="existing"
                                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                                <label class="ml-2 text-sm text-gray-700">Use a saved address</label>
                                            </div>
                                        @endif

                                        <div class="flex items-center">
                                            <input type="radio" name="billing_address_type" value="new"
                                                class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                            <label class="ml-2 text-sm text-gray-700">Use a different billing
                                                address</label>
                                        </div>
                                    </div>

                                    <div id="billing-address-form" class="hidden">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">First
                                                    Name</label>
                                                <input type="text" name="billing_first_name" required
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Last
                                                    Name</label>
                                                <input type="text" name="billing_last_name" required
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Company
                                                    (Optional)</label>
                                                <input type="text" name="billing_company"
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                            </div>
                                            <div class="md:col-span-2">
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                                                <input type="text" name="billing_address_line_1" required
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                                    placeholder="Street address">
                                            </div>
                                            <div class="md:col-span-2">
                                                <input type="text" name="billing_address_line_2"
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                                    placeholder="Apartment, suite, etc. (optional)">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                                <input type="text" name="billing_city" required
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                            </div>
                                            <div>
                                                <label
                                                    class="block text-sm font-medium text-gray-700 mb-1">State/Province</label>
                                                <input type="text" name="billing_state" required
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Postal
                                                    Code</label>
                                                <input type="text" name="billing_postal_code" required
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                                <select name="billing_country" required
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                                    <option value="FR" selected>France</option>
                                                    <option value="BE">Belgium</option>
                                                    <option value="DE">Germany</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                                <input type="tel" name="billing_phone" required
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                                <input type="email" name="billing_email" required
                                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                                    value="{{ auth()->check() ? auth()->user()->email : '' }}">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-8 flex justify-between">
                                        <button type="button"
                                            class="step-prev text-primary hover:underline flex items-center">
                                            <i class="ri-arrow-left-line mr-2"></i> Back to information
                                        </button>
                                        <button type="button"
                                            class="step-next bg-primary text-white px-8 py-3 rounded-lg hover:bg-primary-dark transition">
                                            Continue to payment
                                        </button>
                                    </div>
                                </div>

                                <!-- Step 3: Payment -->
                                <div class="step-content" data-step="3">
                                    <h2 class="text-xl font-semibold mb-6">Payment Method</h2>

                                    <div class="payment-methods space-y-4">
                                        <div class="payment-method border border-gray-300 rounded-lg p-4 cursor-pointer">
                                            <div class="flex items-center">
                                                <input type="radio" name="payment_method" value="card" checked
                                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                                <div class="ml-3 flex items-center">
                                                    <i class="ri-bank-card-line text-xl mr-2"></i>
                                                    <p class="font-medium">Credit Card</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="payment-method border border-gray-300 rounded-lg p-4 cursor-pointer">
                                            <div class="flex items-center">
                                                <input type="radio" name="payment_method" value="payment_on_delivery"
                                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                                <div class="ml-3 flex items-center">
                                                    <i class="ri-money-euro-box-line text-xl mr-2"></i>
                                                    <p class="font-medium">Payment on Delivery</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="payment-method border border-gray-300 rounded-lg p-4 cursor-pointer">
                                            <div class="flex items-center">
                                                <input type="radio" name="payment_method" value="bank_transfer"
                                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                                <div class="ml-3 flex items-center">
                                                    <i class="ri-bank-line text-xl mr-2"></i>
                                                    <p class="font-medium">Bank Transfer</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Order notes
                                            (optional)</label>
                                        <textarea name="notes" rows="3"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"
                                            placeholder="Notes about your order, e.g. special notes for delivery"></textarea>
                                    </div>

                                    <div class="mt-6">
                                        <label class="inline-flex items-center">
                                            <input type="checkbox" name="terms_accepted" required
                                                class="rounded border-gray-300 text-primary focus:ring-primary">
                                            <span class="ml-2 text-sm text-gray-600">
                                                I agree to the <a href="{{ route('terms') }}"
                                                    target="_blank"
                                                    class="text-primary hover:underline">terms and conditions</a>
                                            </span>
                                        </label>
                                    </div>

                                    <div class="mt-8 flex justify-between">
                                        <button type="button"
                                            class="step-prev text-primary hover:underline flex items-center">
                                            <i class="ri-arrow-left-line mr-2"></i> Back to shipping
                                        </button>
                                        <button type="submit" id="place-order-btn"
                                            class="bg-primary text-white px-8 py-3 rounded-lg hover:bg-primary-dark transition">
                                            <span class="btn-text">Place Order • {{ number_format($total, 2) }} €</span>
                                            <span class="btn-loading hidden">
                                                <i class="ri-loader-4-line animate-spin mr-2"></i> Processing...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Order Summary -->
                <div class="lg:w-2/5">
                    <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6 sticky top-4">
                        <h2 class="text-xl font-semibold mb-6">Order Summary</h2>

                        <div class="space-y-4 mb-6">
                            @foreach ($cartItems as $item)
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        @if ($item->product->images->count() > 0)
                                            <img src="{{ asset('storage/' . $item->product->images->first()->path) }}"
                                                alt="{{ $item->product->name }}"
                                                class="w-16 h-16 object-cover rounded-lg">
                                        @else
                                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                                <i class="ri-image-line text-gray-400"></i>
                                            </div>
                                        @endif
                                        <span
                                            class="absolute -top-2 -right-2 bg-gray-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">
                                            {{ $item->quantity }}
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-medium">{{ $item->product->name }}</h3>
                                        @if ($item->productVariation)
                                            <p class="text-sm text-gray-500">{{ $item->productVariation->name }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        @php
                                            $price = $item->productVariation
                                                ? $item->productVariation->getCurrentPriceAttribute()
                                                : $item->product->getCurrentPriceAttribute();
                                        @endphp
                                        <p class="font-medium">{{ number_format($price * $item->quantity, 2) }} €</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Summary Details -->
                        <div class="border-t border-gray-100 pt-6 space-y-3">
                            <div class="flex justify-between text-gray-600">
                                <span>Subtotal</span>
                                <span class="subtotal-amount">€{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Tax</span>
                                <span class="tax-amount">€{{ number_format($tax, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Shipping</span>
                                <span
                                    class="shipping-amount">{{ $shipping == 0 ? 'Free' : '€' . number_format($shipping, 2) }}</span>
                            </div>
                            <div
                                class="flex justify-between text-green-600 discount-row {{ $discount > 0 ? '' : 'hidden' }}">
                                <span>Discount</span>
                                <span class="discount-amount">-€{{ number_format($discount, 2) }}</span>
                            </div>
                            <div class="border-t border-gray-100 pt-3">
                                <div class="flex justify-between font-semibold text-lg">
                                    <span>Total</span>
                                    <span class="total-amount">€{{ number_format($total, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .checkout-steps {
        position: relative;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 1;
    }

    .step-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
        transition: all 0.3s ease;
        color: #6b7280;
    }

    .step.active .step-icon {
        background: #000000;
        color: white;
    }

    .step.completed .step-icon {
        background: #10b981;
        color: white;
    }

    .step-title {
        font-size: 14px;
        color: #6b7280;
        font-weight: 500;
    }

    .step.active .step-title {
        color: #000000;
    }

    .step.completed .step-title {
        color: #10b981;
    }

    .progress-bar {
        height: 2px;
        background: #e5e7eb;
        border-radius: 1px;
        overflow: hidden;
    }

    .progress {
        height: 100%;
        background: #000000;
        transition: width 0.3s ease;
    }

    .step-content {
        display: none;
    }

    .step-content.active {
        display: block;
    }

    .shipping-method,
    .payment-method {
        transition: all 0.2s ease;
    }

    .shipping-method:hover,
    .payment-method:hover {
        border-color: #000000;
    }

    .shipping-method.selected,
    .payment-method.selected {
        border-color: #000000;
        box-shadow: 0 0 0 1px #000000;
    }
</style>
