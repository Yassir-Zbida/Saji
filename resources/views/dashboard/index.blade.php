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
                    <canvas id="salesChart"></canvas>
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
                        <tbody class="bg-white divide-y divide-gray-200" id="recent-orders-list">
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
                                    <td class="px-4 py-3   }}
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
    // Global variables
    let salesChart;
    
    // Initialize Sales Chart
    document.addEventListener('DOMContentLoaded', function() {
        initializeSalesChart();
        
        // Add event listener for refresh button
        document.getElementById('refresh-dashboard').addEventListener('click', function() {
            refreshDashboardData();
        });
        
        // Add event listeners for chart period buttons
        document.querySelectorAll('.chart-period-btn').forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.chart-period-btn').forEach(btn => {
                    btn.classList.remove('bg-primary', 'text-white');
                    btn.classList.add('bg-gray-100', 'text-gray-700');
                });
                
                // Add active class to clicked button
                this.classList.remove('bg-gray-100', 'text-gray-700');
                this.classList.add('bg-primary', 'text-white');
                
                // Update chart based on selected period
                updateChartPeriod(this.dataset.period);
            });
        });
    });
    
    function initializeSalesChart() {
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        
        // Chart data from controller
        const salesLabels = @json($salesData['labels']);
        const salesValues = @json($salesData['data']);
        
        // Create the chart
        salesChart = new Chart(salesCtx, {
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
    }
    
    // Refresh dashboard data
    function refreshDashboardData() {
        // Show loading state
        const refreshBtn = document.getElementById('refresh-dashboard');
        const originalContent = refreshBtn.innerHTML;
        refreshBtn.innerHTML = '<i class="ri-loader-4-line animate-spin mr-2"></i> Refreshing...';
        refreshBtn.disabled = true;
        
        // Fetch updated dashboard data
        fetch('/admin/dashboard/summary', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
        })
        .then(response => response.json())
        .then(data => {
            // Update stats
            document.getElementById('total-products').textContent = data.totalProducts;
            document.getElementById('low-stock-products').textContent = data.lowStockProducts;
            document.getElementById('total-orders').textContent = data.totalOrders;
            document.getElementById('pending-orders').textContent = data.pendingOrders;
            document.getElementById('total-customers').textContent = data.totalCustomers;
            document.getElementById('open-tickets').textContent = data.openTickets;
            
            // Update growth indicators
            updateGrowthIndicator('total-products', data.productGrowth);
            updateGrowthIndicator('total-orders', data.orderGrowth);
            updateGrowthIndicator('total-customers', data.customerGrowth);
            updateGrowthIndicator('open-tickets', data.ticketGrowth, true);
            
            // Refresh other components
            fetchTopProducts();
            updateChartPeriod('month'); // Refresh chart with current period
            
            // Add a subtle highlight effect to show updated data
            document.querySelectorAll('#stats-container > div').forEach(card => {
                card.classList.add('bg-green-50');
                setTimeout(() => {
                    card.classList.remove('bg-green-50');
                }, 1000);
            });
            
            // Reset button
            refreshBtn.innerHTML = originalContent;
            refreshBtn.disabled = false;
        })
        .catch(error => {
            console.error('Error refreshing dashboard data:', error);
            refreshBtn.innerHTML = originalContent;
            refreshBtn.disabled = false;
            
            // Show error notification
            alert('Failed to refresh dashboard data. Please try again.');
        });
    }
    
    // Helper function to update growth indicators
    function updateGrowthIndicator(elementId, growthValue, inverse = false) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        const growthElement = element.nextElementSibling;
        if (!growthElement) return;
        
        // For tickets, growth is inversed (increase is bad, decrease is good)
        const isPositive = inverse ? growthValue < 0 : growthValue >= 0;
        
        // Update icon and color
        growthElement.className = `ml-2 text-xs ${isPositive ? 'text-green-600' : 'text-red-600'} flex items-center`;
        
        // Update icon
        const iconElement = growthElement.querySelector('i');
        if (iconElement) {
            iconElement.className = isPositive ? 'ri-arrow-up-s-line' : 'ri-arrow-down-s-line';
        }
        
        // Update text
        const textNode = growthElement.childNodes[1];
        if (textNode) {
            textNode.nodeValue = ` ${Math.abs(growthValue)}% `;
        }
    }
    
    // Update chart based on selected period
    function updateChartPeriod(period) {
        // Show loading state on chart
        const chartContainer = document.getElementById('salesChart').parentNode;
        chartContainer.classList.add('opacity-50');
        
        // Prepare the request URL based on the period
        const url = `/admin/dashboard/sales-data?period=${period}`;
        
        // Fetch data for the selected period
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
        })
        .then(response => response.json())
        .then(data => {
            // Update chart with new data
            salesChart.data.labels = data.labels;
            salesChart.data.datasets[0].data = data.data;
            salesChart.update();
            
            // Remove loading state
            chartContainer.classList.remove('opacity-50');
        })
        .catch(error => {
            console.error('Error updating chart data:', error);
            chartContainer.classList.remove('opacity-50');
            
            // Show error notification
            alert('Failed to update chart data. Please try again.');
        });
    }
    
    // Fetch top products with AJAX
    function fetchTopProducts() {
        const productsList = document.getElementById('top-products-list');
        productsList.innerHTML = '<div class="flex justify-center items-center py-6"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div></div>';
        
        fetch('/admin/dashboard/top-products', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
        })
        .then(response => response.json())
        .then(data => {
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
            productsList.innerHTML = '<div class="text-center py-4"><p class="text-sm text-gray-500">Failed to load products</p></div>';
        });
    }
</script>
