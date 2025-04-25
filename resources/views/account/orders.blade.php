<!-- resources/views/account/orders.blade.php -->
@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="py-12 md:px-4 md:pb-28">
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

        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            @if(isset($orders) && count($orders) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Order #</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Date</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Status</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Total</th>
                                <th scope="col" class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($orders as $order)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <span class="text-sm font-medium text-primary">#{{ $order->order_number }}</span>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-medium text-gray-900">{{ $order->created_at->format('M d, Y') }}</span>
                                            <span class="text-xs text-gray-500">{{ $order->created_at->format('h:i A') }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
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
                                        
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium {{ $statusClass['bg'] }} {{ $statusClass['text'] }} {{ $statusClass['border'] }}">
                                            <i class="{{ $statusClass['icon'] }} mr-1"></i>
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">{{ $order->formatted_total ?? $order->total_amount }}</span>
                                    </td>
                                    <td class="px-6 py-5 whitespace-nowrap text-right">
                                        <a href="{{ route('account.orders.show', $order) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                            <span>View Order</span>
                                            <i class="ri-arrow-right-line ml-2"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-16">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                        <i class="ri-shopping-bag-line text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-medium text-primary mb-3">No orders yet</h3>
                    <p class="text-gray-500 mb-8 max-w-md mx-auto">You haven't placed any orders yet. Start shopping to see your orders here.</p>
                    <a href="/shop" class="inline-flex items-center px-6 py-3 border border-primary bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                        <i class="ri-store-2-line mr-2"></i> Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    @media (max-width: 640px) {
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
            position: relative;
        }
        
        tr:last-child {
            border-bottom: none;
        }
        
        td {
            display: flex;
            padding: 0.5rem 0;
            border: none;
            align-items: center;
        }
        
        td:before {
            content: attr(data-label);
            font-weight: 500;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
            margin-right: 1rem;
            min-width: 100px;
        }
        
        td:last-child {
            margin-top: 1rem;
            justify-content: flex-end;
            width: 100%;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add data-label attributes to cells for responsive view
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