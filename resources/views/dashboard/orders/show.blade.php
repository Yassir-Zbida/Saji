@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
<div class="order-details py-12 bg-gray-50 md:px-4 md:pb-28">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="text-sm text-gray-500 mb-2 flex items-center">
                <a href="/" class="hover:text-primary">Home</a>
                <span class="mx-2">/</span>
                <a href="/admin/orders" class="hover:text-primary">Orders</a>
                <span class="mx-2">/</span>
                <span>Order #{{ $order->order_number }}</span>
            </div>
            
            <div class="flex flex-wrap items-center justify-between gap-4">
                <h1 class="text-3xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
                
                <!-- Order Status Badge -->
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @if($order->status == 'completed') bg-green-100 text-green-800
                    @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                    @elseif($order->status == 'shipped') bg-indigo-100 text-indigo-800
                    @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                    @elseif($order->status == 'pending') bg-yellow-100 text-yellow-800
                    @elseif($order->status == 'delivered') bg-emerald-100 text-emerald-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    @if($order->status == 'completed')<i class="ri-check-line mr-1"></i>
                    @elseif($order->status == 'processing')<i class="ri-loader-2-line mr-1"></i>
                    @elseif($order->status == 'shipped')<i class="ri-truck-line mr-1"></i>
                    @elseif($order->status == 'cancelled')<i class="ri-close-line mr-1"></i>
                    @elseif($order->status == 'pending')<i class="ri-time-line mr-1"></i>
                    @elseif($order->status == 'delivered')<i class="ri-checkbox-circle-line mr-1"></i>
                    @else<i class="ri-information-line mr-1"></i>
                    @endif
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            
            <p class="text-gray-600 mt-2">Placed on {{ isset($order->created_at) && $order->created_at ? $order->created_at->format('F d, Y \a\t h:i A') : 'N/A' }}</p>
        </div>

        <!-- Action Buttons Bar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-8 flex flex-wrap gap-3" data-aos="fade-up" data-aos-delay="100">
            <!-- Status Update Button -->
            <div class="relative inline-block" x-data="{ statusOpen: false }">
                <button @click="statusOpen = !statusOpen" class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-all duration-300 text-sm font-medium">
                    <i class="ri-edit-line mr-2"></i> Update Status <i class="ri-arrow-down-s-line ml-2"></i>
                </button>
                
                <div x-show="statusOpen" @click.away="statusOpen = false" class="absolute left-0 mt-2 w-56 bg-white border border-gray-100 rounded-lg shadow-lg z-10" style="display: none;">
                    <form id="update-status-form" action="/orders/{{ $order->id }}/status" method="POST" class="p-3">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label for="status" class="block text-xs font-medium text-gray-700 mb-1">Order Status</label>
                            <select name="status" id="status" class="block w-full rounded-md border border-gray-200 px-1 py-2 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        
                        {{-- <div class="mb-3">
                            <label for="status_comment" class="block text-xs font-medium text-gray-700 mb-1">Comment (optional)</label>
                            <textarea name="status_comment" id="status_comment" rows="2" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm" placeholder="Add a note about this status change"></textarea>
                        </div> --}}
                        
                        <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-all duration-300 text-sm font-medium">
                            <i class="ri-save-line mr-2"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Payment Status Update -->
            <div class="relative inline-block" x-data="{ paymentOpen: false }">
                <button @click="paymentOpen = !paymentOpen" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-all duration-300 text-sm font-medium">
                    <i class="ri-bank-card-line mr-2"></i> Update Payment <i class="ri-arrow-down-s-line ml-2"></i>
                </button>
                
                <div x-show="paymentOpen" @click.away="paymentOpen = false" class="absolute left-0 mt-2 w-56 bg-white border border-gray-100 rounded-lg shadow-lg z-10" style="display: none;">
                    <form id="update-payment-form" action="/orders/{{ $order->id }}/payment" method="POST" class="p-3">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label for="payment_status" class="block text-xs font-medium text-gray-700 mb-1">Payment Status</label>
                            <select name="payment_status" id="payment_status" class="block w-full rounded-md border border-gray-200 px-1 py-2 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-sm">
                                <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                        
                        {{-- <div class="mb-3">
                            <label for="transaction_id" class="block text-xs font-medium text-gray-700 mb-1">Transaction ID</label>
                            <input type="text" name="transaction_id" id="transaction_id" value="{{ $order->transaction_id }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50 text-sm" placeholder="Enter transaction ID">
                        </div> --}}
                        
                        <button type="submit" class="w-full inline-flex justify-center items-center px-3 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-all duration-300 text-sm font-medium">
                            <i class="ri-save-line mr-2"></i> Update Payment
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Delete Button -->
            <a href="#" onclick="event.preventDefault(); if(confirm('Are you sure you want to delete this order? This action cannot be undone.')) document.getElementById('delete-order-form').submit();" class="inline-flex items-center px-4 py-2 bg-white border border-red-500 text-red-500 rounded-lg hover:bg-red-50 transition-all duration-300 text-sm font-medium">
                <i class="ri-delete-bin-line mr-2"></i> Delete Order
            </a>
            
            <!-- Print Invoice -->
            <a href="/admin/orders/{{ $order->id }}/invoice" target="_blank" class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-lg hover:bg-gray-700 transition-all duration-300 text-sm font-medium">
                <i class="ri-printer-line mr-2"></i> Print Invoice
            </a>
        </div>
        
        <!-- Order Details Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
            <!-- Order Summary and Timeline Column -->
            <div class="md:col-span-2">
                <!-- Order Summary -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8" data-aos="fade-up" data-aos-delay="200">
                    <div class="p-5 border-b border-gray-100 flex items-center">
                        <i class="ri-shopping-bag-line text-xl text-primary mr-3"></i>
                        <h3 class="text-lg font-medium text-gray-900">Order Summary</h3>
                    </div>
                    
                    <div class="p-5">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($order->items as $item)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-12 w-12 bg-gray-100 rounded-lg overflow-hidden">
                                                    @if($item->product && $item->product->image)
                                                        <img src="{{ $item->product->image }}" alt="{{ $item->product->name }}" class="h-full w-full object-cover">
                                                    @else
                                                        <img src="{{ asset('images/placeholder.jpg') }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                                    @if($item->options)
                                                        <div class="text-xs text-gray-500 mt-1">
                                                            @foreach($item->options as $option => $value)
                                                                <span>{{ ucfirst($option) }}: {{ $value }}</span>
                                                                @if(!$loop->last) / @endif
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">{{ $item->quantity }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500">{{ $item->formatted_price }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">{{ $item->formatted_total }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <!-- Order Totals -->
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="2" class="px-6 py-3"></td>
                                        <td class="px-6 py-3 text-right text-sm font-medium text-gray-500">Subtotal:</td>
                                        <td class="px-6 py-3 text-right text-sm font-medium text-gray-900">{{ $order->formatted_subtotal }}</td>
                                    </tr>
                                    @if($order->discount_amount > 0)
                                    <tr>
                                        <td colspan="2" class="px-6 py-3"></td>
                                        <td class="px-6 py-3 text-right text-sm font-medium text-gray-500">Discount:</td>
                                        <td class="px-6 py-3 text-right text-sm font-medium text-red-600">-{{ $order->formatted_discount }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td colspan="2" class="px-6 py-3"></td>
                                        <td class="px-6 py-3 text-right text-sm font-medium text-gray-500">Shipping:</td>
                                        <td class="px-6 py-3 text-right text-sm font-medium text-gray-900">{{ $order->formatted_shipping }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2" class="px-6 py-3"></td>
                                        <td class="px-6 py-3 text-right text-sm font-medium text-gray-500">Tax:</td>
                                        <td class="px-6 py-3 text-right text-sm font-medium text-gray-900">{{ $order->formatted_tax }}</td>
                                    </tr>
                                    <tr class="border-t-2 border-gray-200">
                                        <td colspan="2" class="px-6 py-4"></td>
                                        <td class="px-6 py-4 text-right text-base font-semibold text-gray-900">Total:</td>
                                        <td class="px-6 py-4 text-right text-base font-semibold text-primary">{{ $order->formatted_total }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Order Timeline (Moved to be after Order Summary) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                    <div class="p-5 border-b border-gray-100 flex items-center">
                        <i class="ri-history-line text-xl text-primary mr-3"></i>
                        <h3 class="text-lg font-medium text-gray-900">Order Timeline</h3>
                    </div>
                    <div class="p-5">
                        @if(count($order->history ?? []) > 0)
                            <ol class="relative border-l border-gray-200 ml-3">
                                @foreach($order->history as $history)
                                <li class="mb-6 ml-6">
                                    <span class="absolute flex items-center justify-center w-6 h-6 rounded-full -left-3 
                                        @if($history->type == 'status_change' && in_array($history->new_status, ['completed', 'delivered'])) bg-green-100
                                        @elseif($history->type == 'status_change' && $history->new_status == 'processing') bg-blue-100
                                        @elseif($history->type == 'status_change' && $history->new_status == 'shipped') bg-indigo-100
                                        @elseif($history->type == 'status_change' && $history->new_status == 'cancelled') bg-red-100
                                        @elseif($history->type == 'payment') bg-purple-100
                                        @else bg-gray-100 @endif
                                    ">
                                        @if($history->type == 'status_change' && in_array($history->new_status, ['completed', 'delivered']))
                                            <i class="ri-check-line text-green-500"></i>
                                        @elseif($history->type == 'status_change' && $history->new_status == 'processing')
                                            <i class="ri-loader-2-line text-blue-500"></i>
                                        @elseif($history->type == 'status_change' && $history->new_status == 'shipped')
                                            <i class="ri-truck-line text-indigo-500"></i>
                                        @elseif($history->type == 'status_change' && $history->new_status == 'cancelled')
                                            <i class="ri-close-line text-red-500"></i>
                                        @elseif($history->type == 'payment')
                                            <i class="ri-bank-card-line text-purple-500"></i>
                                        @else
                                            <i class="ri-information-line text-gray-500"></i>
                                        @endif
                                    </span>
                                    <h3 class="flex items-center text-lg font-semibold text-gray-900">
                                        @if($history->type == 'status_change')
                                            Order {{ ucfirst($history->new_status) }}
                                        @elseif($history->type == 'payment')
                                            Payment {{ ucfirst($history->payment_status) }}
                                        @elseif($history->type == 'note')
                                            Note Added
                                        @else
                                            {{ ucfirst($history->type) }}
                                        @endif
                                    </h3>
                                    <time class="block text-sm font-normal leading-none text-gray-500 mt-1">{{ $history->created_at->format('F d, Y \a\t h:i A') }}</time>
                                    @if($history->comment)
                                        <p class="text-sm text-gray-500 mt-2">{{ $history->comment }}</p>
                                    @endif
                                </li>
                                @endforeach
                            </ol>
                        @else
                            <div class="flex flex-col items-center justify-center py-6">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                    <i class="ri-file-list-3-line text-2xl text-gray-400"></i>
                                </div>
                                <p class="text-gray-500 text-center">No timeline events found for this order.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Order Info Sidebar -->
            <div class="md:col-span-1">
                <!-- Customer Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="p-5 border-b border-gray-200 flex items-center">
                        <i class="ri-user-line text-xl text-primary mr-3"></i>
                        <h3 class="text-lg font-medium text-gray-900">Customer</h3>
                    </div>
                    <div class="p-5">
                        <p class="text-gray-900 font-medium">{{ $order->customer_name }}</p>
                        <p class="text-gray-500 text-sm mt-1">{{ $order->customer_email }}</p>
                        @if($order->customer_phone)
                            <p class="text-gray-500 text-sm mt-1">{{ $order->customer_phone }}</p>
                        @endif
                        
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-sm text-gray-500">Customer since {{ $order->customer_created_at ? $order->customer_created_at->format('M d, Y') : 'N/A' }}</p>
                            <p class="text-sm text-gray-500 mt-1">Orders: {{ $order->customer_order_count ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6" data-aos="fade-up" data-aos-delay="500">
                    <div class="p-5 border-b border-gray-100 flex items-center">
                        <i class="ri-bank-card-line text-xl text-primary mr-3"></i>
                        <h3 class="text-lg font-medium text-gray-900">Payment</h3>
                    </div>
                    <div class="p-5">
                        <p class="flex justify-between text-sm">
                            <span class="text-gray-500">Method:</span>
                            <span class="font-medium text-gray-900">{{ ucfirst($order->payment_method) }}</span>
                        </p>
                        <p class="flex justify-between text-sm mt-2">
                            <span class="text-gray-500">Status:</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($order->payment_status == 'paid') bg-green-100 text-green-800
                                @elseif($order->payment_status == 'pending') bg-yellow-100 text-yellow-800
                                @elseif($order->payment_status == 'failed') bg-red-100 text-red-800
                                @elseif($order->payment_status == 'refunded') bg-purple-100 text-purple-800
                                @else bg-gray-100 text-gray-800 @endif">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </p>
                        @if($order->transaction_id)
                        <p class="flex justify-between text-sm mt-2">
                            <span class="text-gray-500">Transaction:</span>
                            <span class="font-medium text-gray-900">{{ $order->transaction_id }}</span>
                        </p>
                        @endif
                        <p class="flex justify-between text-sm mt-2">
                            <span class="text-gray-500">Date:</span>
                            <span class="font-medium text-gray-900">{{ isset($order->payment_date) && $order->payment_date ? $order->payment_date->format('M d, Y') : 'N/A' }}</span>
                        </p>
                    </div>
                </div>
                
                <!-- Shipping Info -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6" data-aos="fade-up" data-aos-delay="600">
                    <div class="p-5 border-b border-gray-100 flex items-center">
                        <i class="ri-truck-line text-xl text-primary mr-3"></i>
                        <h3 class="text-lg font-medium text-gray-900">Shipping</h3>
                    </div>
                    <div class="p-5">
                        <p class="flex justify-between text-sm">
                            <span class="text-gray-500">Method:</span>
                            <span class="font-medium text-gray-900">{{ $order->shipping_method }}</span>
                        </p>
                        
                        <div class="mt-4">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Shipping Address</h4>
                            <address class="text-sm text-gray-500 not-italic">
                                {{ $order->shipping_name }}<br>
                                {{ $order->shipping_address_line_1 }}<br>
                                @if($order->shipping_address_line_2)
                                    {{ $order->shipping_address_line_2 }}<br>
                                @endif
                                {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}<br>
                                {{ $order->shipping_country }}
                            </address>
                        </div>
                        
                        @if($order->tracking_number)
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <h4 class="text-sm font-medium text-gray-900 mb-2">Tracking</h4>
                            <p class="text-sm text-gray-500">{{ $order->tracking_number }}</p>
                            @if($order->tracking_url)
                                <a href="{{ $order->tracking_url }}" target="_blank" class="inline-flex items-center text-sm text-primary hover:text-primary/80 mt-1">
                                    Track Order <i class="ri-external-link-line ml-1"></i>
                                </a>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Order Notes -->
                @if($order->notes)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100" data-aos="fade-up" data-aos-delay="700">
                    <div class="p-5 border-b border-gray-100 flex items-center">
                        <i class="ri-sticky-note-line text-xl text-primary mr-3"></i>
                        <h3 class="text-lg font-medium text-gray-900">Order Notes</h3>
                    </div>
                    <div class="p-5">
                        <p class="text-sm text-gray-500">{{ $order->notes }}</p>
                    </div>
                </div>
                @endif

                <!-- Add Note Form -->
                <div class="bg-white hidden rounded-xl shadow-sm border border-gray-200 mt-6" data-aos="fade-up" data-aos-delay="800">
                    <div class="p-5 border-b border-gray-100 flex items-center">
                        <i class="ri-add-line text-xl text-primary mr-3"></i>
                        <h3 class="text-lg font-medium text-gray-900">Add Note</h3>
                    </div>
                    <div class="p-5">
                        <form action="/admin/orders/{{ $order->id }}/notes" method="POST">
                            @csrf
                            <textarea name="note" rows="3" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50 text-sm mb-3" placeholder="Add a note about this order..."></textarea>
                            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-all duration-300 text-sm font-medium">
                                <i class="ri-save-line mr-2"></i> Save Note
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

    <script>

    document.addEventListener('DOMContentLoaded', function() {
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 800,
                easing: 'ease-out-cubic',
                once: true
            });
        }
    });
</script>
<script src="{{ asset('js/order-show.js') }}"></script>