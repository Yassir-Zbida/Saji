@extends('layouts.app')

@section('title', 'Create Support Ticket')

@section('content')
<div class="account-dashboard py-12 bg-gray-100 md:px-4 md:pb-28">
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <div class="text-sm text-gray-500 mb-2 flex items-center">
                <a href="/" class="hover:text-primary">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('profile.index') }}" class="hover:text-primary">My Account</a>
                <span class="mx-2">/</span>
                <a href="{{ route('account.tickets') }}" class="hover:text-primary">Support Tickets</a>
                <span class="mx-2">/</span>
                <span>Create New Ticket</span>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-primary">Create New Support Ticket</h1>
            <p class="text-gray-600 mt-2 font-jost">Submit a new support request for our team to help you.</p>
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
                <a href="{{ route('account.addresses') }}" class="inline-flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="ri-map-pin-line mr-3 text-gray-500"></i>
                    <span>My Addresses</span>
                </a>
                <a href="{{ route('account.tickets') }}" class="inline-flex items-center px-6 py-4 text-primary font-medium bg-gray-50 border-b-2 border-primary">
                    <i class="ri-customer-service-2-line mr-3"></i>
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

        <!-- Create Ticket Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 transition-all duration-300 hover:shadow-md mb-8" data-aos="fade-up" data-aos-delay="100">
            <div class="card-header border-b border-gray-200 px-6 py-5 flex items-center">
                <i class="ri-add-circle-line text-xl text-gray-500 mr-3"></i>
                <h3 class="text-lg font-medium text-primary">Submit a New Support Request</h3>
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

                <form action="{{ route('account.tickets.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Subject -->
                        <div class="col-span-2">
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject <span class="text-red-500">*</span></label>
                            <input type="text" name="subject" id="subject" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="Brief description of your issue" value="{{ old('subject') }}" required>
                        </div>
                        
                        <!-- Priority -->
                        <div>
                            <label for="priority" class="block text-sm font-medium text-gray-700 mb-2">Priority <span class="text-red-500">*</span></label>
                            <select name="priority" id="priority" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary" required>
                                <option value="">Select priority</option>
                                <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                            </select>
                        </div>
                        
                        <!-- Order Number (Optional) -->
                        <div>
                            <label for="order_number" class="block text-sm font-medium text-gray-700 mb-2">Related Order (Optional)</label>
                            <input type="text" name="order_number" id="order_number" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="Order number if applicable" value="{{ old('order_number') }}">
                        </div>
                        
                        <!-- Message -->
                        <div class="col-span-2">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message <span class="text-red-500">*</span></label>
                            <textarea name="message" id="message" rows="8" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="Please describe your issue in detail. Include any relevant information that might help us assist you better." required>{{ old('message') }}</textarea>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end gap-4 pt-4">
                        <a href="{{ route('account.tickets') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-300">
                            <i class="ri-arrow-left-line mr-2"></i> Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary/90 transition-all duration-300">
                            <i class="ri-send-plane-line mr-2"></i> Submit Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Help Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 transition-all duration-300 hover:shadow-md" data-aos="fade-up" data-aos-delay="200">
            <div class="card-header border-b border-gray-200 px-6 py-5 flex items-center">
                <i class="ri-question-line text-xl text-gray-500 mr-3"></i>
                <h3 class="text-lg font-medium text-primary">Help & Guidelines</h3>
            </div>
            <div class="p-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-lg p-5">
                        <h4 class="text-primary font-medium mb-3 flex items-center">
                            <i class="ri-information-line mr-2"></i>
                            Before Submitting a Ticket
                        </h4>
                        <ul class="text-gray-600 space-y-2">
                            <li class="flex items-start">
                                <i class="ri-check-line text-green-500 mt-1 mr-2"></i>
                                <span>Check our <a href="#" class="text-primary hover:underline">FAQ section</a> for quick answers to common questions.</span>
                            </li>
                            <li class="flex items-start">
                                <i class="ri-check-line text-green-500 mt-1 mr-2"></i>
                                <span>For order-related issues, please include your order number for faster assistance.</span>
                            </li>
                            <li class="flex items-start">
                                <i class="ri-check-line text-green-500 mt-1 mr-2"></i>
                                <span>Provide as much detail as possible about your issue for quicker resolution.</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-5">
                        <h4 class="text-primary font-medium mb-3 flex items-center">
                            <i class="ri-time-line mr-2"></i>
                            Response Times
                        </h4>
                        <ul class="text-gray-600 space-y-2">
                            <li class="flex items-start">
                                <i class="ri-check-line text-green-500 mt-1 mr-2"></i>
                                <span>High priority: We aim to respond within 4-8 hours.</span>
                            </li>
                            <li class="flex items-start">
                                <i class="ri-check-line text-green-500 mt-1 mr-2"></i>
                                <span>Medium priority: Responses typically within 24 hours.</span>
                            </li>
                            <li class="flex items-start">
                                <i class="ri-check-line text-green-500 mt-1 mr-2"></i>
                                <span>Low priority: Responses within 48 hours.</span>
                            </li>
                            <li class="flex items-start">
                                <i class="ri-information-line text-blue-500 mt-1 mr-2"></i>
                                <span>Note: Response times may vary during weekends and holidays.</span>
                            </li>
                        </ul>
                    </div>
                </div>
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