@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="container mx-auto" id="dashboard-container">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
                <p class="mt-1 text-sm text-gray-500">Overview of your store's performance and activity</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <button type="button" id="refresh-dashboard" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <i class="ri-refresh-line mr-2"></i>
                    Refresh Data
                </button>
                <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <i class="ri-download-line mr-2"></i>
                    Export
                </button>
                <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <i class="ri-add-line mr-2"></i>
                    Add product
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6" id="stats-container">
            <!-- Total Products -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-soft p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Total Products</h3>
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium py-1 px-2 rounded-full">Products</span>
                </div>
                <div class="flex items-baseline">
                    <span class="text-2xl font-semibold text-gray-900" id="total-products">{{ $totalProducts }}</span>
                    <span class="ml-2 text-xs {{ $productGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} flex items-center">
                        <i class="{{ $productGrowth >= 0 ? 'ri-arrow-up-s-line' : 'ri-arrow-down-s-line' }}"></i> 
                        {{ abs($productGrowth) }}% <span class="text-gray-500 ml-1">from last month</span>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="bg-gray-100 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-blue-500 h-full rounded-full" style="width: {{ min(100, max(0, $totalProducts / ($totalProducts + $lowStockProducts) * 100)) }}%"></div>
                    </div>
                </div>
                <div class="mt-4 text-xs text-gray-500">
                    <span class="text-red-500 font-medium" id="low-stock-products">{{ $lowStockProducts }}</span> products with low stock
                </div>
            </div>

            <!-- Total Orders -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-soft p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Total Orders</h3>
                    <span class="bg-purple-100 text-purple-800 text-xs font-medium py-1 px-2 rounded-full">Orders</span>
                </div>
                <div class="flex items-baseline">
                    <span class="text-2xl font-semibold text-gray-900" id="total-orders">{{ $totalOrders }}</span>
                    <span class="ml-2 text-xs {{ $orderGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} flex items-center">
                        <i class="{{ $orderGrowth >= 0 ? 'ri-arrow-up-s-line' : 'ri-arrow-down-s-line' }}"></i> 
                        {{ abs($orderGrowth) }}% <span class="text-gray-500 ml-1">from last month</span>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="bg-gray-100 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-purple-500 h-full rounded-full" style="width: {{ min(100, max(0, ($totalOrders - $pendingOrders) / $totalOrders * 100)) }}%"></div>
                    </div>
                </div>
                <div class="mt-4 text-xs text-gray-500">
                    <span class="text-amber-500 font-medium" id="pending-orders">{{ $pendingOrders }}</span> orders pending
                </div>
            </div>

            <!-- Total Customers -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-soft p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Total Customers</h3>
                    <span class="bg-green-100 text-green-800 text-xs font-medium py-1 px-2 rounded-full">Customers</span>
                </div>
                <div class="flex items-baseline">
                    <span class="text-2xl font-semibold text-gray-900" id="total-customers">{{ $totalCustomers }}</span>
                    <span class="ml-2 text-xs {{ $customerGrowth >= 0 ? 'text-green-600' : 'text-red-600' }} flex items-center">
                        <i class="{{ $customerGrowth >= 0 ? 'ri-arrow-up-s-line' : 'ri-arrow-down-s-line' }}"></i> 
                        {{ abs($customerGrowth) }}% <span class="text-gray-500 ml-1">from last month</span>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="bg-gray-100 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-green-500 h-full rounded-full" style="width: {{ min(100, max(0, $newCustomersThisWeek / max(1, $totalCustomers) * 100 * 5)) }}%"></div>
                    </div>
                </div>
                <div class="mt-4 text-xs text-gray-500">
                    <span class="text-green-500 font-medium">{{ $newCustomersThisWeek }}</span> new customers this week
                </div>
            </div>

            <!-- Support Tickets -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-soft p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Support Tickets</h3>
                    <span class="bg-red-100 text-red-800 text-xs font-medium py-1 px-2 rounded-full">Support</span>
                </div>
                <div class="flex items-baseline">
                    <span class="text-2xl font-semibold text-gray-900" id="open-tickets">{{ $openTickets }}</span>
                    <span class="ml-2 text-xs {{ $ticketGrowth >= 0 ? 'text-red-600' : 'text-green-600' }} flex items-center">
                        <i class="{{ $ticketGrowth >= 0 ? 'ri-arrow-up-s-line' : 'ri-arrow-down-s-line' }}"></i> 
                        {{ abs($ticketGrowth) }}% <span class="text-gray-500 ml-1">from last week</span>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="bg-gray-100 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-red-500 h-full rounded-full" style="width: {{ min(100, max(0, $openTickets / max(1, $openTickets + 10) * 100)) }}%"></div>
                    </div>
                </div>
                <div class="mt-4 text-xs text-gray-500">
                    <span class="text-red-500 font-medium">{{ $openTickets }}</span> tickets require attention
                </div>
            </div>
        </div>

        <!-- Charts and Tables Row -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Sales Chart - 2/3 width -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-soft p-6 lg:col-span-2">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-base font-medium text-gray-900">Sales Overview</h3>
                    <div class="flex space-x-2">
                        <button type="button" data-period="week" class="chart-period-btn px-3 py-1.5 text-xs font-medium bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                            Week
                        </button>
                        <button type="button" data-period="month" class="chart-period-btn px-3 py-1.5 text-xs font-medium bg-primary text-white rounded-md hover:bg-primary-dark">
                            Month
                        </button>
                        <button type="button" data-period="year" class="chart-period-btn px-3 py-1.5 text-xs font-medium bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                            Year
                        </button>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="salesChart" data-labels="{{ json_encode($salesData['labels']) }}" data-values="{{ json_encode($salesData['data']) }}"></canvas>
                </div>
            </div>

            <!-- Top Products - 1/3 width -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-soft p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-base font-medium text-gray-900">Top Products</h3>
                    <a href="{{ route('admin.products') }}" class="text-sm text-primary hover:text-primary-dark">View All</a>
                </div>
                <div class="space-y-4" id="top-products-list">
                    @forelse($topProducts as $product)
                        <div class="flex items-center space-x-3 p-3 border border-gray-100 rounded-md hover:bg-gray-50">
                            <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-md overflow-hidden">
                                @if($product->image)
                                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                                @else
                                    <div class="flex items-center justify-center h-full w-full text-gray-400">
                                        <i class="ri-image-line"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $product->name }}</p>
                                <p class="text-xs text-gray-500">Sales: {{ $product->total_quantity }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">€{{ number_format($product->total_sales, 2) }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <p class="text-sm text-gray-500">No products found</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Orders - 2/3 width -->
        <!-- Recent Orders - 2/3 width -->
<div class="bg-white rounded-lg border border-gray-200 shadow-soft p-6 lg:col-span-2">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-base font-medium text-gray-900">Recent Orders</h3>
        <a href="{{ route('admin.orders') }}" class="text-sm text-primary hover:text-primary-dark">View All</a>
    </div>
    <div class="overflow-x-auto">
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-50">
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Order ID</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Date</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Status</th>
                            <th scope="col" class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Total</th>
                            <th scope="col" class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100" id="recent-orders-list">
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <span class="text-sm font-medium text-primary">#{{ $order->id }}</span>
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
                                    <span class="text-sm font-medium text-gray-900">€{{ number_format($order->total_amount, 2) }}</span>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right">
                                    <div class="flex flex-col sm:flex-row gap-2 justify-end">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                            <span>View</span>
                                            <i class="ri-arrow-right-line ml-2"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-5 text-center text-sm text-gray-500">
                                    No recent orders found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if(empty($recentOrders) || count($recentOrders) === 0)
                <div class="text-center py-16">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                        <i class="ri-shopping-bag-line text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-medium text-primary mb-3">No orders found</h3>
                    <p class="text-gray-500 mb-8 max-w-md mx-auto">There are no recent orders to display.</p>
                    <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center px-6 py-3 border border-primary bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                        <i class="ri-refresh-line mr-2"></i> View All Orders
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

            <!-- Recent Support Tickets - 1/3 width -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-soft p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-base font-medium text-gray-900">Recent Support Tickets</h3>
                    <a href="{{ route('admin.tickets') }}" class="text-sm text-primary hover:text-primary-dark">View All</a>
                </div>
                <div class="space-y-4" id="recent-tickets-list">
                    @forelse($recentTickets as $ticket)
                        <div class="border border-gray-200 rounded-md p-4 hover:bg-gray-50">
                            <div class="flex justify-between">
                                <span class="text-xs font-medium text-gray-500">#{{ $ticket->id }}</span>
                                @if($ticket->status == 'open')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Open
                                    </span>
                                @elseif($ticket->status == 'in_progress')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        In Progress
                                    </span>
                                @elseif($ticket->status == 'resolved')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Resolved
                                    </span>
                                @elseif($ticket->status == 'closed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Closed
                                    </span>
                                @endif
                            </div>
                            <h4 class="mt-2 text-sm font-medium text-gray-900">{{ $ticket->subject }}</h4>
                            <p class="mt-1 text-xs text-gray-500 line-clamp-2">{{ Str::limit($ticket->description, 100) }}</p>
                            <div class="mt-2 flex justify-between items-center">
                                <span class="text-xs text-gray-500">{{ $ticket->created_at->format('M d, Y') }}</span>
                                <a href="{{ route('admin.tickets.show', $ticket) }}" class="text-xs text-primary hover:text-primary-dark">
                                    View details <i class="ri-arrow-right-s-line align-middle ml-1"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <p class="text-sm text-gray-500">No recent tickets found</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    // Make sales data available to the chart
    window.salesChartData = @json($salesData ?? ['labels' => [], 'data' => []]);
</script>



    <script src="{{ asset('js/admin.js') }}"></script>


