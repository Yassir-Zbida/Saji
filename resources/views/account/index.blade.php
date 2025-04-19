@extends('layouts.app')

@section('title', 'My Account')

@section('content')
<div class="account-dashboard py-12 bg-gray-100 md:px-4 md:pb-28">
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <div class="text-sm text-gray-500 mb-2 flex items-center">
                <a href="/" class="hover:text-primary">Home</a>
                <span class="mx-2">/</span>
                <span>My Account</span>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-primary">Your Personal Dashboard</h1>
            <p class="text-gray-600 mt-2 font-jost">Manage your orders, addresses, and support tickets all in one place.</p>
        </div>

        <!-- Success message -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="ri-checkbox-circle-line text-green-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8 overflow-hidden">
            <div class="flex flex-wrap overflow-x-auto">
                <a href="{{ route('profile.index') }}" class="inline-flex items-center px-6 py-4 text-primary font-medium bg-gray-50 border-b-2 border-primary">
                    <i class="ri-dashboard-line mr-3"></i>
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

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 transition-all duration-300 hover:shadow-md mb-8" data-aos="fade-up" data-aos-delay="100">
            <div class="p-6 md:p-8">
                <div class="flex flex-wrap md:flex-nowrap items-center justify-between gap-6">
                    <div class="flex items-center">
                        <div class="user-icon bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mr-6">
                            <i class="ri-user-line text-2xl text-gray-500"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-primary">{{ $user->name }}</h2>
                            <p class="text-gray-500 mt-1 mb-1"><i class="ri-mail-line mr-2"></i>{{ $user->email }}</p>
                            <p class="text-gray-500"><i class="ri-calendar-line mr-2"></i>customer since {{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('account.edit') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-lg hover:bg-primary/90 transition-all duration-300">
                            <i class="ri-edit-line mr-2"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 transition-all duration-300 hover:shadow-md h-full" data-aos="fade-up" data-aos-delay="200">
                <div class="card-header border-b border-gray-200 px-6 py-5 flex justify-between items-center">
                    <div class="flex items-center">
                        <i class="ri-shopping-bag-line text-xl text-gray-500 mr-3"></i>
                        <h3 class="text-lg font-medium text-primary">Recent Orders</h3>
                    </div>
                    @if($user->orders && !$user->orders->isEmpty())
                        <a href="{{ route('account.orders') }}" class="text-sm text-primary hover:text-primary/70 transition-colors duration-300">View all</a>
                    @endif
                </div>
                <div class="p-6">
                    @if($user->orders && $user->orders->isEmpty())
                        <div class="text-center py-10">
                            <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <i class="ri-shopping-bag-line text-2xl text-gray-400"></i>
                            </div>
                            <h4 class="text-lg font-medium text-primary mb-2">No orders yet</h4>
                            <p class="text-gray-500 mb-4">You haven't placed any orders yet.</p>
                            <a href="/shop" class="inline-flex items-center px-5 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300">
                                <i class="ri-store-2-line mr-2"></i> Start Shopping
                            </a>
                        </div>
                    @elseif($user->orders)
                        <div class="space-y-5">
                            @foreach($user->orders->take(3) as $order)
                                <div class="bg-gray-50 rounded-lg p-5 transition-all duration-300 hover:bg-gray-100 hover:shadow-sm">
                                    <div class="flex flex-wrap md:flex-nowrap justify-between items-center gap-4">
                                        <div>
                                            <p class="text-sm font-medium text-primary mb-1">Order #{{ $order->order_number }}</p>
                                            <p class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y') }}</p>
                                        </div>
                                        <div>
                                            <span class="inline-flex px-3 py-1 text-xs rounded-full
                                                @if($order->status == 'completed') bg-green-100 text-green-800
                                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                                @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                                @else bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </div>
                                        <div class="font-medium">
                                            {{ $order->formatted_total ?? $order->total }}
                                        </div>
                                        <div>
                                            <a href="{{ route('account.orders.show', $order) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white text-primary border border-gray-200 hover:bg-primary hover:text-white transition-all duration-300">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="text-center mt-6">
                                <a href="{{ route('account.orders') }}" class="inline-flex items-center px-5 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300">
                                    <i class="ri-shopping-bag-line mr-2"></i> View All Orders
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <i class="ri-shopping-bag-line text-2xl text-gray-400"></i>
                            </div>
                            <h4 class="text-lg font-medium text-primary mb-2">No orders yet</h4>
                            <p class="text-gray-500 mb-4">You haven't placed any orders yet.</p>
                            <a href="{{ route('shop.index') }}" class="inline-flex items-center px-5 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300">
                                <i class="ri-store-2-line mr-2"></i> Start Shopping
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 transition-all duration-300 hover:shadow-md h-full" data-aos="fade-up" data-aos-delay="300">
                <div class="card-header border-b border-gray-200 px-6 py-5 flex justify-between items-center">
                    <div class="flex items-center">
                        <i class="ri-map-pin-line text-xl text-gray-500 mr-3"></i>
                        <h3 class="text-lg font-medium text-primary">Address Book</h3>
                    </div>
                    @if(isset($user->addresses) && !$user->addresses->isEmpty())
                        <a href="{{ route('account.addresses') }}" class="text-sm text-primary hover:text-primary/70 transition-colors duration-300">Manage all</a>
                    @endif
                </div>
                <div class="p-6">
                    @if(!isset($user->addresses) || $user->addresses->isEmpty())
                        <div class="text-center py-10">
                            <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                <i class="ri-map-pin-line text-2xl text-gray-400"></i>
                            </div>
                            <h4 class="text-lg font-medium text-primary mb-2">No addresses yet</h4>
                            <p class="text-gray-500 mb-4">You haven't added any addresses yet.</p>
                            <a href="{{ route('account.addresses.create') }}" class="inline-flex items-center px-5 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300">
                                <i class="ri-add-line mr-2"></i> Add Address
                            </a>
                        </div>
                    @else
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Default Shipping Address -->
                            <div>
                                <h4 class="text-sm font-medium text-primary mb-3 flex items-center">
                                    <i class="ri-truck-line mr-2"></i>
                                    Default Shipping Address
                                </h4>
                                @php
                                    $defaultShipping = $user->addresses->where('address_type', 'shipping')->where('is_default', true)->first();
                                @endphp
                                
                                @if($defaultShipping)
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 transition-all duration-300 hover:border-gray-300 hover:shadow-sm">
                                        <p class="text-sm font-medium mb-1">{{ $defaultShipping->first_name }} {{ $defaultShipping->last_name }}</p>
                                        <p class="text-sm text-gray-500">{{ $defaultShipping->address_line_1 }}</p>
                                        @if($defaultShipping->address_line_2)
                                            <p class="text-sm text-gray-500">{{ $defaultShipping->address_line_2 }}</p>
                                        @endif
                                        <p class="text-sm text-gray-500">{{ $defaultShipping->city }}, {{ $defaultShipping->state }} {{ $defaultShipping->postal_code }}</p>
                                        <p class="text-sm text-gray-500">{{ $defaultShipping->country }}</p>
                                        <div class="mt-3">
                                            <a href="{{ route('account.addresses.edit', $defaultShipping) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white text-primary border border-gray-200 hover:bg-primary hover:text-white transition-all duration-300">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 text-center">
                                        <p class="text-sm text-gray-500 mb-2">No default shipping address set.</p>
                                        <a href="{{ route('account.addresses.create') }}" class="text-sm text-primary hover:text-primary/70 transition">
                                            <i class="ri-add-line mr-1"></i> Add address
                                        </a>
                                    </div>
                                @endif
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-primary mb-3 flex items-center">
                                    <i class="ri-bill-line mr-2"></i>
                                    Default Billing Address
                                </h4>
                                @php
                                    $defaultBilling = $user->addresses->where('address_type', 'billing')->where('is_default', true)->first();
                                @endphp
                                
                                @if($defaultBilling)
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 transition-all duration-300 hover:border-gray-300 hover:shadow-sm">
                                        <p class="text-sm font-medium mb-1">{{ $defaultBilling->first_name }} {{ $defaultBilling->last_name }}</p>
                                        <p class="text-sm text-gray-500">{{ $defaultBilling->address_line_1 }}</p>
                                        @if($defaultBilling->address_line_2)
                                            <p class="text-sm text-gray-500">{{ $defaultBilling->address_line_2 }}</p>
                                        @endif
                                        <p class="text-sm text-gray-500">{{ $defaultBilling->city }}, {{ $defaultBilling->state }} {{ $defaultBilling->postal_code }}</p>
                                        <p class="text-sm text-gray-500">{{ $defaultBilling->country }}</p>
                                        <div class="mt-3">
                                            <a href="{{ route('account.addresses.edit', $defaultBilling) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white text-primary border border-gray-200 hover:bg-primary hover:text-white transition-all duration-300">
                                                <i class="ri-edit-line"></i>
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 text-center">
                                        <p class="text-sm text-gray-500 mb-2">No default billing address set.</p>
                                        <a href="{{ route('account.addresses.create') }}" class="text-sm text-primary hover:text-primary/70 transition">
                                            <i class="ri-add-line mr-1"></i> Add address
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="text-center mt-6">
                            <a href="{{ route('account.addresses') }}" class="inline-flex items-center px-5 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300">
                                <i class="ri-map-pin-line mr-2"></i> Manage All Addresses
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 transition-all duration-300 hover:shadow-md" data-aos="fade-up" data-aos-delay="400">
            <div class="card-header border-b border-gray-200 px-6 py-5 flex justify-between items-center">
                <div class="flex items-center">
                    <i class="ri-customer-service-2-line text-xl text-gray-500 mr-3"></i>
                    <h3 class="text-lg font-medium text-primary">Support Tickets</h3>
                </div>
                @if(isset($user->tickets) && !$user->tickets->isEmpty())
                    <a href="{{ route('account.tickets') }}" class="text-sm text-primary hover:text-primary/70 transition-colors duration-300">View all</a>
                @endif
            </div>
            <div class="p-6">
                <!-- Debug info to check if tickets are available -->
                @if(isset($user->tickets))
                    <!-- Hidden debug info -->
                    <div class="hidden">
                        <p>Tickets count: {{ $user->tickets->count() }}</p>
                        <p>Tickets empty: {{ $user->tickets->isEmpty() ? 'Yes' : 'No' }}</p>
                        <p>User ID: {{ $user->id }}</p>
                    </div>
                @endif

                @if(!isset($user->tickets) || $user->tickets->isEmpty())
                    <div class="text-center py-10">
                        <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <i class="ri-customer-service-2-line text-2xl text-gray-400"></i>
                        </div>
                        <h4 class="text-lg font-medium text-primary mb-2">No support tickets</h4>
                        <p class="text-gray-500 mb-4">You haven't created any support tickets yet.</p>
                        <a href="{{ route('account.tickets.create') }}" class="inline-flex items-center px-5 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300">
                            <i class="ri-add-line mr-2"></i> Create New Ticket
                        </a>
                    </div>
                @else
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach($user->tickets->take(3) as $ticket)
                            <div class="bg-gray-50 rounded-lg p-5 border border-gray-200 transition-all duration-300 hover:border-gray-300 hover:shadow-sm">
                                <div class="flex justify-between items-start mb-3">
                                    <span class="text-sm font-medium text-primary">#{{ $ticket->ticket_number }}</span>
                                    <span class="inline-flex px-2 py-1 text-xs rounded-full
                                        @if($ticket->status == 'resolved') bg-green-100 text-green-800
                                        @elseif($ticket->status == 'open') bg-blue-100 text-blue-800
                                        @elseif($ticket->status == 'closed') bg-gray-100 text-gray-800
                                        @else bg-yellow-100 text-yellow-800
                                        @endif">
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </div>
                                <h4 class="text-sm font-medium text-primary mb-2 line-clamp-1">{{ $ticket->subject }}</h4>
                                <p class="text-xs text-gray-500 mb-3">
                                    <i class="ri-time-line mr-1"></i> Updated {{ $ticket->updated_at->format('M d, Y') }}
                                </p>
                                <a href="{{ route('account.tickets.show', $ticket) }}" class="text-sm text-primary hover:text-primary/70 transition-colors duration-300 inline-flex items-center">
                                    View Details <i class="ri-arrow-right-line ml-1"></i>
                                </a>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex flex-wrap justify-center gap-4 mt-8">
                        <a href="{{ route('account.tickets') }}" class="inline-flex items-center px-5 py-2 border border-primary text-primary rounded-lg hover:bg-primary hover:text-white transition-all duration-300">
                            <i class="ri-eye-line mr-2"></i> View All Tickets
                        </a>
                        <a href="{{ route('account.tickets.create') }}" class="inline-flex items-center px-5 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-all duration-300">
                            <i class="ri-add-line mr-2"></i> Create New Ticket
                        </a>
                    </div>
                @endif
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