@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <!-- Profile Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-jakarta font-semibold mb-2">My Orders</h1>
            <p class="text-gray-600 font-jost">View and track your order history</p>
        </div>

        <!-- Profile Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Sidebar Navigation -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-md shadow-soft p-4">
                    <h2 class="font-jakarta font-medium text-lg mb-4 pb-2 border-b">Account Navigation</h2>
                    <nav class="space-y-1">
                        <a href="{{ route('profile.index') }}" class="block py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100 transition font-jost">
                            <i class="ri-user-line mr-2"></i> Account Details
                        </a>
                        <a href="{{ route('profile.addresses') }}" class="block py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100 transition font-jost">
                            <i class="ri-map-pin-line mr-2"></i> My Addresses
                        </a>
                        <a href="{{ route('profile.orders') }}" class="block py-2 px-3 rounded-md bg-black text-white font-jost">
                            <i class="ri-shopping-bag-line mr-2"></i> Order History
                        </a>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100 transition font-jost">
                            <i class="ri-logout-box-line mr-2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-md shadow-soft p-6">
                    <h2 class="font-jakarta font-medium text-xl mb-6">Order History</h2>

                    @if($orders->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b">
                                        <th class="text-left py-3 px-2 font-jost font-medium text-sm text-gray-600">Order #</th>
                                        <th class="text-left py-3 px-2 font-jost font-medium text-sm text-gray-600">Date</th>
                                        <th class="text-left py-3 px-2 font-jost font-medium text-sm text-gray-600">Status</th>
                                        <th class="text-right py-3 px-2 font-jost font-medium text-sm text-gray-600">Total</th>
                                        <th class="text-right py-3 px-2 font-jost font-medium text-sm text-gray-600">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="py-4 px-2 font-jost">
                                            <a href="{{ route('profile.orders.show', $order) }}" class="text-black hover:underline">
                                                #{{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td class="py-4 px-2 font-jost text-gray-600">{{ $order->created_at->format('M d, Y') }}</td>
                                        <td class="py-4 px-2 font-jost">
                                            <span class="inline-block px-2 py-1 text-xs rounded 
                                                @if($order->status == 'completed') bg-green-100 text-green-800 
                                                @elseif($order->status == 'processing') bg-blue-100 text-blue-800 
                                                @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                                                @elseif($order->status == 'cancelled') bg-red-100 text-red-800 
                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="py-4 px-2 text-right font-jost font-medium">
                                            {{ number_format($order->total, 2) }} €
                                        </td>
                                        <td class="py-4 px-2 text-right">
                                            <a href="{{ route('profile.orders.show', $order) }}" class="font-jost text-sm text-black hover:underline">
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="py-12 text-center">
                            <div class="mb-4">
                                <i class="ri-shopping-bag-line text-5xl text-gray-300"></i>
                            </div>
                            <h3 class="font-jost font-medium mb-2">No Orders Found</h3>
                            <p class="text-gray-500 font-jost mb-6">You haven't placed any orders yet.</p>
                            <a href="/" class="inline-block bg-black text-white px-6 py-3 rounded-md hover:opacity-90 transition font-jost">
                                Continue Shopping
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection