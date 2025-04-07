<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\TicketReply;
use App\Models\User;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    /**
     * Afficher la liste des tickets de support
     */
    public function index()
    {
        $tickets = SupportTicket::with('user', 'assignedTo')
            ->latest()
            ->paginate(10);
        return view('admin.tickets.index', compact('tickets'));
    }

    /**
     * Afficher un ticket spécifique
     */
    public function show(SupportTicket $ticket)
    {
        $ticket->load('user', 'assignedTo', 'replies.user', 'order');
        $agents = User::whereIn('role', ['admin', 'gerant'])->get();
        return view('admin.tickets.show', compact('ticket', 'agents'));
    }

    /**
     * Répondre à un ticket
     */
    public function reply(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'message' => 'required',
        ]);

        // Créer la réponse
        TicketReply::create([
            'message' => $request->message,
            'is_admin' => true,
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
        ]);

        // Mettre à jour le statut du ticket si nécessaire
        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        return back()->with('success', 'Réponse envoyée avec succès.');
    }

    /**
     * Assigner un ticket à un agent
     */
    public function assign(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $ticket->update([
            'assigned_to' => $request->assigned_to,
        ]);

        return back()->with('success', 'Ticket assigné avec succès.');
    }

    /**
     * Mettre à jour le statut d'un ticket
     */
    public function updateStatus(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $ticket->update([
            'status' => $request->status,
        ]);

        return back()->with('success', 'Statut du ticket mis à jour avec succès.');
    }

    /**
     * Mettre à jour la priorité d'un ticket
     */
    public function updatePriority(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'priority' => 'required|in:low,medium,high,urgent',
        ]);

        $ticket->update([
            'priority' => $request->priority,
        ]);

        return back()->with('success', 'Priorité du ticket mise à jour avec succès.');
    }
}