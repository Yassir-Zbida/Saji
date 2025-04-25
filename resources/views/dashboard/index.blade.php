```php
@extends('layouts.admin')

@section('title', 'Dashboard')
<script src="{{ asset('js/admin.js') }}"></script>


@section('content')
    <div class="container mx-auto">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Dashboard</h1>
                <p class="mt-1 text-sm text-gray-500">Overview of your store's performance and activity</p>
            </div>
            <div class="mt-4 md:mt-0 flex space-x-3">
                <button type="button" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <i class="ri-download-line mr-2"></i>
                    Export
                </button>
                <button type="button" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <i class="ri-add-line mr-2"></i>
                    Add product
                </button>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Products -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-soft p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Total Products</h3>
                    <span class="bg-blue-100 text-blue-800 text-xs font-medium py-1 px-2 rounded-full">Products</span>
                </div>
                <div class="flex items-baseline">
                    <span class="text-2xl font-semibold text-gray-900">{{ $totalProducts }}</span>
                    <span class="ml-2 text-xs text-green-600 flex items-center">
                        <i class="ri-arrow-up-s-line"></i> 12% <span class="text-gray-500 ml-1">from last month</span>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="bg-gray-100 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-blue-500 h-full rounded-full" style="width: 75%"></div>
                    </div>
                </div>
                <div class="mt-4 text-xs text-gray-500">
                    <span class="text-red-500 font-medium">{{ $lowStockProducts }}</span> products with low stock
                </div>
            </div>

            <!-- Total Orders -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-soft p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Total Orders</h3>
                    <span class="bg-purple-100 text-purple-800 text-xs font-medium py-1 px-2 rounded-full">Orders</span>
                </div>
                <div class="flex items-baseline">
                    <span class="text-2xl font-semibold text-gray-900">{{ $totalOrders }}</span>
                    <span class="ml-2 text-xs text-green-600 flex items-center">
                        <i class="ri-arrow-up-s-line"></i> 8% <span class="text-gray-500 ml-1">from last month</span>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="bg-gray-100 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-purple-500 h-full rounded-full" style="width: 65%"></div>
                    </div>
                </div>
                <div class="mt-4 text-xs text-gray-500">
                    <span class="text-amber-500 font-medium">{{ $pendingOrders }}</span> orders pending
                </div>
            </div>

            <!-- Total Customers -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-soft p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Total Customers</h3>
                    <span class="bg-green-100 text-green-800 text-xs font-medium py-1 px-2 rounded-full">Customers</span>
                </div>
                <div class="flex items-baseline">
                    <span class="text-2xl font-semibold text-gray-900">{{ $totalCustomers }}</span>
                    <span class="ml-2 text-xs text-green-600 flex items-center">
                        <i class="ri-arrow-up-s-line"></i> 5% <span class="text-gray-500 ml-1">from last month</span>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="bg-gray-100 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-green-500 h-full rounded-full" style="width: 55%"></div>
                    </div>
                </div>
                <div class="mt-4 text-xs text-gray-500">
                    <span class="text-green-500 font-medium">24</span> new customers this week
                </div>
            </div>

            <!-- Support Tickets -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-soft p-6 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-medium text-gray-500">Support Tickets</h3>
                    <span class="bg-red-100 text-red-800 text-xs font-medium py-1 px-2 rounded-full">Support</span>
                </div>
                <div class="flex items-baseline">
                    <span class="text-2xl font-semibold text-gray-900">{{ $openTickets }}</span>
                    <span class="ml-2 text-xs text-red-600 flex items-center">
                        <i class="ri-arrow-up-s-line"></i> 2% <span class="text-gray-500 ml-1">from last week</span>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="bg-gray-100 h-1.5 rounded-full overflow-hidden">
                        <div class="bg-red-500 h-full rounded-full" style="width: 25%"></div>
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
                        <button type="button" class="px-3 py-1.5 text-xs font-medium bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                            Week
                        </button>
                        <button type="button" class="px-3 py-1.5 text-xs font-medium bg-primary text-white rounded-md hover:bg-primary-dark">
                            Month
                        </button>
                        <button type="button" class="px-3 py-1.5 text-xs font-medium bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200">
                            Year
                        </button>
                    </div>
                </div>
                <div class="h-64">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>

            <!-- Top Products - 1/3 width -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-soft p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-base font-medium text-gray-900">Top Products</h3>
                    <button class="text-sm text-primary hover:text-primary-dark">View All</button>
                </div>
                <div class="space-y-4">
                    <!-- Product items will be populated by AJAX -->
                    <div id="top-products-list">
                        <div class="flex justify-center items-center py-6">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Recent Orders - 2/3 width -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-soft p-6 lg:col-span-2">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-base font-medium text-gray-900">Recent Orders</h3>
                    <a href="{{ route('admin.orders') }}" class="text-sm text-primary hover:text-primary-dark">View All</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Order ID
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                                <th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($recentOrders as $order)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $order->id }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                        {{ $order->user->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                        {{ $order->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        @if($order->status == 'completed')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        @elseif($order->status == 'pending')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                Pending
                                            </span>
                                        @elseif($order->status == 'processing')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                Processing
                                            </span>
                                        @elseif($order->status == 'cancelled')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                Cancelled
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">
                                        €{{ number_format($order->total_amount, 2) }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-primary hover:text-primary-dark">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-3 text-center text-sm text-gray-500">
                                        No recent orders found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Support Tickets - 1/3 width -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-soft p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-base font-medium text-gray-900">Recent Support Tickets</h3>
                    <a href="{{ route('admin.tickets') }}" class="text-sm text-primary hover:text-primary-dark">View All</a>
                </div>
                <div class="space-y-4">
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
    // Initialize Sales Chart
    document.addEventListener('DOMContentLoaded', function() {
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        
        // Chart data from controller
        const salesLabels = @json($salesData['labels']);
        const salesValues = @json($salesData['data']);
        
        // Create the chart
        const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: salesLabels,
                datasets: [{
                    label: 'Sales',
                    data: salesValues,
                    borderColor: '#111111',
                    backgroundColor: 'rgba(17, 17, 17, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: '#111111',
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(17, 17, 17, 0.9)',
                        padding: 10,
                        cornerRadius: 4,
                        titleFont: {
                            size: 12,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 12
                        },
                        callbacks: {
                            label: function(context) {
                                return '€' + context.raw.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            color: '#6B7280'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            font: {
                                size: 10
                            },
                            color: '#6B7280',
                            callback: function(value) {
                                return '€' + value;
                            }
                        }
                    }
                }
            }
        });
        
        // Load top products via AJAX
        fetchTopProducts();
    });
    
    // Fetch top products with AJAX
    function fetchTopProducts() {
        fetch('/admin/dashboard/top-products', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
        })
        .then(response => response.json())
        .then(data => {
            const productsList = document.getElementById('top-products-list');
            
            if (data.length === 0) {
                productsList.innerHTML = '<div class="text-center py-4"><p class="text-sm text-gray-500">No products found</p></div>';
                return;
            }
            
            let html = '';
            data.forEach(product => {
                html += `
                <div class="flex items-center space-x-3 p-3 border border-gray-100 rounded-md hover:bg-gray-50">
                    <div class="flex-shrink-0 w-10 h-10 bg-gray-100 rounded-md overflow-hidden">
                        ${product.image ? `<img src="${product.image}" alt="${product.name}" class="w-full h-full object-cover">` : '<div class="flex items-center justify-center h-full w-full text-gray-400"><i class="ri-image-line"></i></div>'}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">${product.name}</p>
                        <p class="text-xs text-gray-500">Sales: ${product.total_quantity}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-900">€${parseFloat(product.total_sales).toFixed(2)}</p>
                    </div>
                </div>
                `;
            });
            
            productsList.innerHTML = html;
        })
        .catch(error => {
            console.error('Error fetching top products:', error);
            document.getElementById('top-products-list').innerHTML = '<div class="text-center py-4"><p class="text-sm text-gray-500">Failed to load products</p></div>';
        });
    }
</script>
