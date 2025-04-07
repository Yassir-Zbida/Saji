<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Invoice;
use Illuminate\Http\Request;
use PDF;

class OrderController extends Controller
{
    /**
     * Afficher la liste des commandes
     */
    public function index()
    {
        $orders = Order::with('user')->latest()->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Afficher une commande spécifique
     */
    public function show(Order $order)
    {
        $order->load('user', 'items.product', 'invoice');
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Mettre à jour le statut d'une commande
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled,refunded',
        ]);

        $order->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Statut de la commande mis à jour avec succès.');
    }

    /**
     * Mettre à jour le statut de paiement
     */
    public function updatePaymentStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed',
        ]);

        $order->update([
            'payment_status' => $request->payment_status,
        ]);

        return back()->with('success', 'Statut de paiement mis à jour avec succès.');
    }

    /**
     * Générer une facture pour une commande
     */
    public function generateInvoice(Order $order)
    {
        // Vérifier si une facture existe déjà
        if ($order->invoice) {
            return back()->withErrors(['invoice' => 'Une facture existe déjà pour cette commande.']);
        }

        // Créer la facture
        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'invoice_date' => now(),
            'due_date' => now()->addDays(15),
            'status' => $order->payment_status === 'paid' ? 'paid' : 'pending',
            'total_amount' => $order->total_amount,
            'order_id' => $order->id,
        ]);

        // Générer le PDF
        $order->load('user', 'items.product');
        $pdf = PDF::loadView('admin.invoices.pdf', compact('order', 'invoice'));
        
        // Enregistrer le PDF
        $pdfPath = 'invoices/' . $invoice->invoice_number . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());

        // Mettre à jour le chemin du PDF dans la facture
        $invoice->update([
            'pdf_path' => $pdfPath,
        ]);

        return back()->with('success', 'Facture générée avec succès.');
    }

    /**
     * Télécharger une facture
     */
    public function downloadInvoice(Invoice $invoice)
    {
        if (!$invoice->pdf_path || !Storage::disk('public')->exists($invoice->pdf_path)) {
            return back()->withErrors(['invoice' => 'Le fichier PDF de la facture est introuvable.']);
        }

        return Storage::disk('public')->download($invoice->pdf_path, $invoice->invoice_number . '.pdf');
    }
}
