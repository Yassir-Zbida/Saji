<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use App\Models\Invoice;
use App\Notifications\NewOrderNotification;
use App\Notifications\OrderStatusUpdatedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get order statistics for initial page load
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        
        // Calculate growth percentages
        $orderGrowth = $this->calculateOrderGrowth();
        
        // For initial page load, get data if not AJAX request
        if (!$request->ajax()) {
            // Get users for filter dropdown
            $users = User::all();
            
            // Initial load with empty orders - will be loaded via AJAX
            return view('dashboard.orders.index', compact(
                'totalOrders',
                'pendingOrders',
                'completedOrders',
                'totalRevenue',
                'orderGrowth',
                'users'
            ));
        } else {
            // Handle AJAX request
            return $this->getOrdersData($request);
        }
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::where('is_active', true)->where('stock_quantity', '>', 0)->get();
        return view('dashboard.orders.create', compact('customers', 'products'));
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'payment_method' => 'required|string',
            'shipping_address' => 'required|string',
            'billing_address' => 'required|string',
            'notes' => 'nullable|string',
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        // Start transaction
        DB::beginTransaction();
        
        try {
            // Calculate total amount
            $totalAmount = 0;
            foreach ($request->products as $item) {
                $product = Product::find($item['id']);
                
                // Check stock
                if ($product->stock_quantity < $item['quantity']) {
                    return back()->withErrors(['products' => "Not enough stock for {$product->name}."]);
                }
                
                $totalAmount += $product->price * $item['quantity'];
            }

            // Create order
            $order = Order::create([
                'user_id' => $request->user_id,
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->billing_address,
                'notes' => $request->notes,
            ]);

            // Add products to order
            foreach ($request->products as $item) {
                $product = Product::find($item['id']);
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ]);

                // Update stock
                $product->stock_quantity -= $item['quantity'];
                $product->save();
            }

            // Create invoice
            Invoice::create([
                'order_id' => $order->id,
                'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
                'invoice_date' => now(),
                'due_date' => now()->addDays(15),
                'total_amount' => $totalAmount,
                'status' => 'unpaid',
            ]);

            // Send notification to customer
            $user = User::find($request->user_id);
            $user->notify(new NewOrderNotification($order));

            // Commit transaction
            DB::commit();

            return redirect()->route('dashboard.orders.index')
                ->with('success', 'Order created successfully.');
                
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            
            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Get orders data for AJAX requests.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrdersData(Request $request)
    {
        // Build query with filters and eager loading
        $query = Order::with(['user', 'items'])
            ->withCount('items');
            
        // Apply filters
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }
        
        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('total_amount', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Sort orders
        $sortField = $request->input('sort_field', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        
        $query->orderBy($sortField, $sortDirection);
        
        // Paginate the results
        $orders = $query->paginate($request->input('per_page', 15));
        
        // Get updated statistics based on filters
        $stats = $this->getFilteredStats($request);
        
        return response()->json([
            'orders' => $orders,
            'stats' => $stats
        ]);
    }
    
    /**
     * Get updated statistics based on applied filters.
     *
     * @param Request $request
     * @return array
     */
    private function getFilteredStats(Request $request)
    {
        // Base query for statistics
        $statsQuery = Order::query();
        
        // Apply the same filters to get relevant stats
        if ($request->has('date_from') && $request->date_from) {
            $statsQuery->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $statsQuery->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $statsQuery->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('total_amount', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Get counts for different statuses
        $totalOrders = (clone $statsQuery)->count();
        $pendingOrders = (clone $statsQuery)->where('status', 'pending')->count();
        $completedOrders = (clone $statsQuery)->where('status', 'completed')->count();
        
        // Get revenue
        $totalRevenue = (clone $statsQuery)->where('payment_status', 'paid')->sum('total_amount');
        
        // Calculate growth
        $orderGrowth = $this->calculateOrderGrowth();
        
        return [
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'completedOrders' => $completedOrders,
            'totalRevenue' => $totalRevenue,
            'orderGrowth' => $orderGrowth
        ];
    }
    
    /**
     * Calculate order growth percentage.
     *
     * @return int
     */
    private function calculateOrderGrowth()
    {
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        $currentMonthStart = Carbon::now()->startOfMonth();
        
        $lastMonthOrders = Order::where('created_at', '>=', $lastMonthStart)
            ->where('created_at', '<=', $lastMonthEnd)
            ->count();
            
        $currentMonthOrders = Order::where('created_at', '>=', $currentMonthStart)->count();
        
        if ($lastMonthOrders > 0) {
            return round(($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders * 100);
        }
        
        return 0;
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load('user', 'items.product', 'invoice');
        return view('dashboard.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $order->load('user', 'items.product');
        return view('dashboard.orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $order->status;
        $oldPaymentStatus = $order->payment_status;

        // Start transaction
        DB::beginTransaction();
        
        try {
            $order->update([
                'status' => $request->status,
                'payment_status' => $request->payment_status,
                'notes' => $request->notes,
            ]);

            // If order is cancelled and wasn't before, restore stock
            if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
                foreach ($order->items as $item) {
                    $product = $item->product;
                    $product->stock_quantity += $item->quantity;
                    $product->save();
                }
            }
            
            // If order was cancelled and now isn't, reduce stock again
            if ($oldStatus === 'cancelled' && $request->status !== 'cancelled') {
                foreach ($order->items as $item) {
                    $product = $item->product;
                    $product->stock_quantity -= $item->quantity;
                    $product->save();
                }
            }

            // Update invoice if payment status changed
            if ($request->payment_status !== $oldPaymentStatus) {
                if ($request->payment_status === 'paid' && $order->invoice) {
                    $order->invoice->update(['status' => 'paid']);
                } elseif ($request->payment_status === 'failed' && $order->invoice) {
                    $order->invoice->update(['status' => 'unpaid']);
                } elseif ($request->payment_status === 'refunded' && $order->invoice) {
                    $order->invoice->update(['status' => 'refunded']);
                }
            }

            // Notify customer if status changed
            if ($request->status !== $oldStatus || $request->payment_status !== $oldPaymentStatus) {
                if ($order->user) {
                    $order->user->notify(new OrderStatusUpdatedNotification($order));
                }
            }
            
            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order updated successfully',
                    'order' => $order->fresh(['user', 'items.product']),
                ]);
            }
            
            return redirect()->route('dashboard.orders.index')
                ->with('success', 'Order updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating order: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        DB::beginTransaction();
        
        try {
            // If order is not cancelled, restore stock
            if ($order->status !== 'cancelled') {
                foreach ($order->items as $item) {
                    $product = $item->product;
                    $product->stock_quantity += $item->quantity;
                    $product->save();
                }
            }

            // Delete related items and invoice
            $order->items()->delete();
            if ($order->invoice) {
                $order->invoice->delete();
            }
            
            $order->delete();
            
            DB::commit();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order deleted successfully'
                ]);
            }

            return redirect()->route('dashboard.orders.index')
                ->with('success', 'Order deleted successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting order: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Update order status via AJAX.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,completed,cancelled'
        ]);
        
        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        
        // Start transaction
        DB::beginTransaction();
        
        try {
            $order->status = $request->status;
            $order->save();
            
            // Handle inventory if status is cancelled or uncancelled
            if ($request->status === 'cancelled' && $oldStatus !== 'cancelled') {
                foreach ($order->items as $item) {
                    $product = $item->product;
                    $product->stock_quantity += $item->quantity;
                    $product->save();
                }
            } elseif ($oldStatus === 'cancelled' && $request->status !== 'cancelled') {
                foreach ($order->items as $item) {
                    $product = $item->product;
                    $product->stock_quantity -= $item->quantity;
                    $product->save();
                }
            }
            
            // Notify customer if enabled
            if ($request->has('notify') && $request->notify && $order->user) {
                $order->user->notify(new OrderStatusUpdatedNotification($order));
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully',
                'order' => $order->fresh()
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating order status: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update payment status via AJAX.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePaymentStatus(Request $request, $id)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed,refunded'
        ]);
        
        $order = Order::findOrFail($id);
        $oldPaymentStatus = $order->payment_status;
        
        DB::beginTransaction();
        
        try {
            $order->payment_status = $request->payment_status;
            $order->save();
            
            // Update invoice if payment status changed
            if ($request->payment_status !== $oldPaymentStatus && $order->invoice) {
                if ($request->payment_status === 'paid') {
                    $order->invoice->update(['status' => 'paid']);
                } elseif (in_array($request->payment_status, ['pending', 'failed'])) {
                    $order->invoice->update(['status' => 'unpaid']);
                } elseif ($request->payment_status === 'refunded') {
                    $order->invoice->update(['status' => 'refunded']);
                }
            }
            
            // Notify customer if enabled
            if ($request->has('notify') && $request->notify && $order->user) {
                $order->user->notify(new OrderStatusUpdatedNotification($order));
            }
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Payment status updated successfully',
                'order' => $order->fresh()
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Error updating payment status: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Display invoice for an order.
     */
    public function invoice(Order $order)
    {
        $order->load('user', 'items.product', 'invoice');
        return view('dashboard.orders.invoice', compact('order'));
    }
    
    /**
     * Generate and download invoice PDF.
     */
    public function generateInvoice(Order $order)
    {
        $order->load('user', 'items.product', 'invoice');
        
        // Generate PDF (implementation depends on your PDF library)
        // This is just a placeholder - implement your actual PDF generation
        
        return back()->with('success', 'Invoice generated successfully.');
    }
    
    /**
     * Export orders to CSV.
     */
    public function export(Request $request)
    {
        $query = Order::with('user');
        
        // Apply the same filters as in getOrdersData method
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }
        
        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        $orders = $query->get();
        
        // Generate CSV filename
        $filename = 'orders_export_' . date('Y-m-d_His') . '.csv';
        
        // Set headers for CSV download
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];
        
        // Create CSV file
        $handle = fopen('php://temp', 'r+');
        
        // Add CSV headers
        fputcsv($handle, [
            'Order #',
            'Date',
            'Customer',
            'Email',
            'Status',
            'Payment Status',
            'Items',
            'Total Amount',
        ]);
        
        // Add order data
        foreach ($orders as $order) {
            fputcsv($handle, [
                $order->order_number,
                $order->created_at->format('Y-m-d H:i:s'),
                $order->user ? $order->user->name : 'Guest',
                $order->user ? $order->user->email : 'N/A',
                ucfirst($order->status),
                ucfirst($order->payment_status),
                $order->items->sum('quantity'),
                $order->total_amount,
            ]);
        }
        
        // Reset file pointer
        rewind($handle);
        
        // Get content
        $content = stream_get_contents($handle);
        fclose($handle);
        
        return response($content, 200, $headers);
    }
}