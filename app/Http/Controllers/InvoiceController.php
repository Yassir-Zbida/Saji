<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Invoice::with('order.user');
        
        // Apply filters
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('order', function($q) use ($search) {
                      $q->where('order_number', 'like', "%{$search}%")
                        ->orWhereHas('user', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                              ->orWhere('email', 'like', "%{$search}%");
                        });
                  });
            });
        }
        
        // Sort invoices
        $sortField = $request->input('sort_field', 'invoice_date');
        $sortDirection = $request->input('sort_direction', 'desc');
        
        $query->orderBy($sortField, $sortDirection);
        
        $invoices = $query->paginate(10);
        
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $orders = Order::whereDoesntHave('invoice')->with('user')->get();
        return view('invoices.create', compact('orders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'status' => 'required|in:paid,unpaid,cancelled',
        ]);

        $order = Order::findOrFail($request->order_id);

        // Check if invoice already exists
        if ($order->invoice) {
            return back()->withErrors(['order_id' => 'This order already has an invoice.']);
        }

        $invoice = Invoice::create([
            'order_id' => $request->order_id,
            'invoice_number' => 'INV-' . strtoupper(Str::random(8)),
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'total_amount' => $order->total_amount,
            'status' => $request->status,
        ]);

        // Update order payment status if invoice is paid
        if ($request->status === 'paid' && $order->payment_status !== 'paid') {
            $order->update(['payment_status' => 'paid']);
        }

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load('order.user', 'order.items.product');
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        return view('invoices.edit', compact('invoice'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'status' => 'required|in:paid,unpaid,cancelled',
        ]);

        $oldStatus = $invoice->status;

        $invoice->update([
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'status' => $request->status,
        ]);

        // Update order payment status if invoice status changed
        if ($request->status !== $oldStatus) {
            if ($request->status === 'paid' && $invoice->order->payment_status !== 'paid') {
                $invoice->order->update(['payment_status' => 'paid']);
            } elseif ($request->status === 'unpaid' && $invoice->order->payment_status === 'paid') {
                $invoice->order->update(['payment_status' => 'pending']);
            } elseif ($request->status === 'cancelled' && $invoice->order->payment_status !== 'failed') {
                $invoice->order->update(['payment_status' => 'failed']);
            }
        }

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        // Delete PDF if exists
        if ($invoice->pdf_path) {
            Storage::disk('public')->delete($invoice->pdf_path);
        }

        $invoice->delete();

        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }
    
    /**
     * Generate PDF for the invoice.
     */
    public function generatePdf(Invoice $invoice)
    {
        $invoice->load('order.user', 'order.items.product');
        
        // Generate PDF (implementation depends on your PDF library)
        // This is just a placeholder - implement your actual PDF generation
        
        // Save PDF path to invoice
        $pdfPath = 'invoices/' . $invoice->invoice_number . '.pdf';
        $invoice->update(['pdf_path' => $pdfPath]);
        
        return back()->with('success', 'Invoice PDF generated successfully.');
    }
    
    /**
     * Send invoice to customer.
     */
    public function sendToCustomer(Invoice $invoice)
    {
        $invoice->load('order.user');
        
        // Send email with invoice (implementation depends on your email setup)
        // This is just a placeholder - implement your actual email sending
        
        return back()->with('success', 'Invoice sent to customer successfully.');
    }
    
    /**
     * Mark invoice as paid.
     */
    public function markAsPaid(Invoice $invoice)
    {
        $invoice->update(['status' => 'paid']);
        $invoice->order->update(['payment_status' => 'paid']);
        
        return back()->with('success', 'Invoice marked as paid successfully.');
    }
}