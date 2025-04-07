<?php
 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    /**
     * Afficher la liste des factures
     */
    public function index()
    {
        $invoices = Invoice::with('order.user')->latest()->paginate(10);
        return view('admin.invoices.index', compact('invoices'));
    }

    /**
     * Afficher une facture spécifique
     */
    public function show(Invoice $invoice)
    {
        $invoice->load('order.user', 'order.items.product');
        return view('admin.invoices.show', compact('invoice'));
    }

    /**
     * Mettre à jour le statut d'une facture
     */
    public function updateStatus(Request $request, Invoice $invoice)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,overdue,cancelled',
        ]);

        $invoice->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Statut de la facture mis à jour avec succès.');
    }

    /**
     * Télécharger une facture
     */
    public function download(Invoice $invoice)
    {
        if (!$invoice->pdf_path || !Storage::disk('public')->exists($invoice->pdf_path)) {
            return back()->withErrors(['invoice' => 'Le fichier PDF de la facture est introuvable.']);
        }

        return Storage::disk('public')->download($invoice->pdf_path, $invoice->invoice_number . '.pdf');
    }
}
