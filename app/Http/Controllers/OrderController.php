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

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with('user');
        
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
        
        $orders = $query->paginate(10);
        
        // Get users for filter
        $users = User::all();
        
        return view('orders.index', compact('orders', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::where('is_active', true)->where('stock_quantity', '>', 0)->get();
        return view('orders.create', compact('customers', 'products'));
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

            return redirect()->route('orders.index')
                ->with('success', 'Order created successfully.');
                
        } catch (\Exception $e) {
            // Rollback transaction on error
            DB::rollBack();
            
            return back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        $order->load('user', 'items.product', 'invoice');
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $order->load('user', 'items.product');
        return view('orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
            'notes' => 'nullable|string',
        ]);

        $oldStatus = $order->status;
        $oldPaymentStatus = $order->payment_status;

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
            }
        }

        // Notify customer if status changed
        if ($request->status !== $oldStatus || $request->payment_status !== $oldPaymentStatus) {
            $order->user->notify(new OrderStatusUpdatedNotification($order));
        }

        return redirect()->route('orders.index')
            ->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        // If order is not cancelled, restore stock
        if ($order->status !== 'cancelled') {
            foreach ($order->items as $item) {
                $product = $item->product;
                $product->stock_quantity += $item->quantity;
                $product->save();
            }
        }

        $order->delete();

        return redirect()->route('orders.index')
            ->with('success', 'Order deleted successfully.');
    }
    
    /**
     * Generate invoice PDF.
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
        // Ajout de logs pour déboguer
        Log::info('Export orders method called', ['request' => $request->all()]);
        
        try {
            $query = Order::with('user');

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
                $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                });
            }

            $orders = $query->get();
            
            Log::info('Orders fetched for export', ['count' => $orders->count()]);

            $filename = 'orders_export_' . date('Y-m-d_His') . '.csv';

            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            $callback = function () use ($orders) {
                $handle = fopen('php://output', 'w');

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

                foreach ($orders as $order) {
                    fputcsv($handle, [
                        $order->order_number ?? 'N/A',
                        $order->created_at ? $order->created_at->format('Y-m-d H:i:s') : 'N/A',
                        $order->user ? $order->user->name : 'Guest',
                        $order->user ? $order->user->email : 'N/A',
                        ucfirst($order->status ?? 'N/A'),
                        ucfirst($order->payment_status ?? 'N/A'),
                        $order->items ? $order->items->sum('quantity') : 0,
                        $order->total_amount ?? 0,
                    ]);
                }

                fclose($handle);
            };

            return response()->stream($callback, 200, $headers);
            
        } catch (\Exception $e) {
            Log::error('Error in export orders', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withErrors(['error' => 'Une erreur est survenue lors de l\'export: ' . $e->getMessage()]);
        }
    }


    
}