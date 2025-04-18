<!-- resources/views/account/orders.blade.php -->
@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class=" py-12  md:px-4 md:pb-28">
    <div class="container mx-auto px-4">
        <div class="mb-6">
            <nav class="text-sm text-gray-500">
                <ol class="flex items-center">
                    <li><a href="/" class="hover:text-primary">Home</a></li>
                    <li class="mx-2">/</li>
                    <li><a href="{{ route('account.index') }}" class="hover:text-primary">My Account</a></li>
                    <li class="mx-2">/</li>
                    <li class="text-primary">My Orders</li>
                </ol>
            </nav>
        </div>

        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-primary">My Orders</h1>
                <p class="text-gray-600 mt-2 font-jost">View and track all your orders with us.</p>
            </div>
            
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8 overflow-hidden">
            <div class="inline-flex bg-white p-1 w-full overflow-x-auto">
                <a href="{{ route('account.index') }}" class="inline-flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="ri-dashboard-line mr-3 text-gray-500"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('account.orders') }}" class="inline-flex items-center px-6 py-4 text-primary font-medium bg-gray-50 border-b-2 border-primary">
                    <i class="ri-shopping-bag-line mr-3"></i>
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
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="inline-flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="ri-logout-box-line mr-3 text-gray-500"></i>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
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

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-6">
            @if(isset($orders) && count($orders) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order #</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-primary">
                                        #{{ $order->order_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->created_at->format('M d, Y, H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-3 py-1 text-xs rounded-full font-medium
                                            @if($order->status == 'completed') bg-green-100 text-green-800
                                            @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                                            @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                                            @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->formatted_total ?? $order->total }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('account.orders.show', $order) }}" class="text-primary hover:text-primary/70 inline-flex items-center">
                                            <span class="mr-1">View</span>
                                            <i class="ri-arrow-right-line"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
                        <i class="ri-shopping-bag-line text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-primary mb-2">No orders yet</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">You haven't placed any orders yet. Start shopping to see your orders here.</p>
                    <a href="/shop" class="inline-flex items-center px-5 py-2 border border-primary bg-white text-primary rounded-lg hover:bg-primary hover:text-white transition-colors">
                        <i class="ri-store-2-line mr-2"></i> Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection