@extends('layouts.app')

@section('title', isset($address) ? 'Edit Address' : 'Add New Address')

@section('content')
<div class="account-dashboard py-12 bg-gray-100 md:px-4 md:pb-28">
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <div class="text-sm text-gray-500 mb-2 flex items-center">
                <a href="/" class="hover:text-primary">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('profile.index') }}" class="hover:text-primary">My Account</a>
                <span class="mx-2">/</span>
                <a href="{{ route('account.addresses') }}" class="hover:text-primary">My Addresses</a>
                <span class="mx-2">/</span>
                <span>{{ isset($address) ? 'Edit Address' : 'Add New Address' }}</span>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-primary">{{ isset($address) ? 'Edit Address' : 'Add New Address' }}</h1>
            <p class="text-gray-600 mt-2 font-jost">{{ isset($address) ? 'Update your saved address information.' : 'Add a new shipping or billing address to your account.' }}</p>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8 overflow-hidden">
            <div class="flex flex-wrap overflow-x-auto">
                <a href="{{ route('profile.index') }}" class="inline-flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="ri-dashboard-line mr-3 text-gray-500"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('account.orders') }}" class="inline-flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="ri-shopping-bag-line mr-3 text-gray-500"></i>
                    <span>My Orders</span>
                </a>
                <a href="{{ route('account.addresses') }}" class="inline-flex items-center px-6 py-4 text-primary font-medium bg-gray-50 border-b-2 border-primary">
                    <i class="ri-map-pin-line mr-3"></i>
                    <span>My Addresses</span>
                </a>
                <a href="{{ route('account.tickets') }}" class="inline-flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="ri-customer-service-2-line mr-3 text-gray-500"></i>
                    <span>Support Tickets</span>
                </a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="inline-flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="ri-logout-box-line mr-3 text-gray-500"></i>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>

        <!-- Address Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 transition-all duration-300 hover:shadow-md mb-8" data-aos="fade-up" data-aos-delay="100">
            <div class="card-header border-b border-gray-200 px-6 py-5 flex items-center">
                <i class="ri-map-pin-line text-xl text-gray-500 mr-3"></i>
                <h3 class="text-lg font-medium text-primary">{{ isset($address) ? 'Edit Address Details' : 'Address Details' }}</h3>
            </div>
            
            <div class="p-6 md:p-8">
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="ri-error-warning-line text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                                <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ isset($address) ? route('account.addresses.update', $address) : route('account.addresses.store') }}" method="POST" class="space-y-6">
                    @csrf
                    @if(isset($address))
                        @method('PUT')
                    @endif
                    
                    <div class="border-b border-gray-200 pb-6">
                        <h4 class="text-base font-medium text-gray-700 mb-4">Address Type</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="relative flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="radio" id="shipping" name="address_type" value="shipping" class="h-4 w-4 text-primary border-gray-300 rounded" {{ (!isset($address) || $address->address_type == 'shipping') ? 'checked' : '' }} required>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="shipping" class="font-medium text-gray-700">Shipping Address</label>
                                    <p class="text-gray-500">Use for delivery of physical products</p>
                                </div>
                            </div>
                            <div class="relative flex items-start">
                                <div class="flex items-center h-5">
                                    <input type="radio" id="billing" name="address_type" value="billing" class="h-4 w-4 text-primary border-gray-300 rounded" {{ (isset($address) && $address->address_type == 'billing') ? 'checked' : '' }} required>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="billing" class="font-medium text-gray-700">Billing Address</label>
                                    <p class="text-gray-500">Use for payment and invoice information</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-b border-gray-200 pb-6">
                        <h4 class="text-base font-medium text-gray-700 mb-4">Contact Information</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name <span class="text-red-500">*</span></label>
                                <input type="text" name="first_name" id="first_name" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary" value="{{ old('first_name', isset($address) ? $address->first_name : '') }}" required>
                            </div>
                            
                            <div>
                                <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name <span class="text-red-500">*</span></label>
                                <input type="text" name="last_name" id="last_name" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary" value="{{ old('last_name', isset($address) ? $address->last_name : '') }}" required>
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number <span class="text-red-500">*</span></label>
                                <input type="tel" name="phone" id="phone" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary" value="{{ old('phone', isset($address) ? $address->phone : '') }}" required>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                                <input type="email" name="email" id="email" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary" value="{{ old('email', isset($address) ? $address->email : '') }}" required>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="company" class="block text-sm font-medium text-gray-700 mb-2">Company Name <span class="text-gray-400">(Optional)</span></label>
                                <input type="text" name="company" id="company" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary" value="{{ old('company', isset($address) ? $address->company : '') }}">
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="text-base font-medium text-gray-700 mb-4">Address Details</h4>
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="address_line_1" class="block text-sm font-medium text-gray-700 mb-2">Street Address <span class="text-red-500">*</span></label>
                                <input type="text" name="address_line_1" id="address_line_1" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary" value="{{ old('address_line_1', isset($address) ? $address->address_line_1 : '') }}" required>
                            </div>
                            
                            <div>
                                <label for="address_line_2" class="block text-sm font-medium text-gray-700 mb-2">Apartment, Suite, etc. <span class="text-gray-400">(Optional)</span></label>
                                <input type="text" name="address_line_2" id="address_line_2" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary" value="{{ old('address_line_2', isset($address) ? $address->address_line_2 : '') }}">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City <span class="text-red-500">*</span></label>
                                    <input type="text" name="city" id="city" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary" value="{{ old('city', isset($address) ? $address->city : '') }}" required>
                                </div>
                                
                                <div>
                                    <label for="state" class="block text-sm font-medium text-gray-700 mb-2">State / Province <span class="text-red-500">*</span></label>
                                    <input type="text" name="state" id="state" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary" value="{{ old('state', isset($address) ? $address->state : '') }}" required>
                                </div>
                                
                                <div>
                                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Postal Code <span class="text-red-500">*</span></label>
                                    <input type="text" name="postal_code" id="postal_code" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary" value="{{ old('postal_code', isset($address) ? $address->postal_code : '') }}" required>
                                </div>
                            </div>
                            
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country <span class="text-red-500">*</span></label>
                                <select name="country" id="country" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary" required>
                                    <option value="">Select Country</option>
                                    <option value="US" {{ old('country', isset($address) ? $address->country : '') == 'US' ? 'selected' : '' }}>United States</option>
                                    <option value="CA" {{ old('country', isset($address) ? $address->country : '') == 'CA' ? 'selected' : '' }}>Canada</option>
                                    <option value="GB" {{ old('country', isset($address) ? $address->country : '') == 'GB' ? 'selected' : '' }}>United Kingdom</option>
                                    <option value="FR" {{ old('country', isset($address) ? $address->country : '') == 'FR' ? 'selected' : '' }}>France</option>
                                    <option value="DE" {{ old('country', isset($address) ? $address->country : '') == 'DE' ? 'selected' : '' }}>Germany</option>
                                    <option value="IT" {{ old('country', isset($address) ? $address->country : '') == 'IT' ? 'selected' : '' }}>Italy</option>
                                    <option value="ES" {{ old('country', isset($address) ? $address->country : '') == 'ES' ? 'selected' : '' }}>Spain</option>
                                    <!-- Add more countries as needed -->
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-6">
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" id="is_default" name="is_default" value="1" class="h-4 w-4 text-primary border-gray-300 rounded" {{ (isset($address) && $address->is_default) || !isset($address) ? 'checked' : '' }}>
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="is_default" class="font-medium text-gray-700">Set as default address</label>
                                <p class="text-gray-500">Use this address as my default {{ isset($address) ? $address->address_type : 'shipping' }} address</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end pt-4">
                        <div class="flex space-x-3">
                            <a href="{{ route('account.addresses') }}" class="inline-flex items-center px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-300">
                                <i class="ri-arrow-left-line mr-2"></i> Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-primary text-white rounded-lg hover:bg-primary/90 transition-all duration-300">
                                <i class="ri-save-line mr-2"></i> {{ isset($address) ? 'Update Address' : 'Save Address' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.bg-white.rounded-lg');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        card.style.transitionDelay = `${index * 0.1}s`;
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100);
    });
    
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true
        });
    }
});
</script>
@endsection