<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\TicketResponse;
use App\Models\User;
use App\Notifications\NewSupportTicketNotification;
use App\Notifications\TicketResponseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SupportTicket::with('user');
        
        // For regular users, only show their tickets
        if (!Auth::user()->isAdmin() && !Auth::user()->isManager()) {
            $query->where('user_id', Auth::id());
        }
        
        // Apply filters
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('priority') && $request->priority != '') {
            $query->where('priority', $request->priority);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Sort tickets
        $sortField = $request->input('sort_field', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        
        $query->orderBy($sortField, $sortDirection);
        
        $tickets = $query->paginate(10);
        
        return view('support-tickets.index', compact('tickets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // For admin/manager, show user selection
        $customers = null;
        if (Auth::user()->isAdmin() || Auth::user()->isManager()) {
            $customers = User::where('role', 'customer')->get();
        }
        
        return view('support-tickets.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'priority' => 'required|in:low,medium,high',
        ];
        
        // For admin/manager, require user_id
        if (Auth::user()->isAdmin() || Auth::user()->isManager()) {
            $rules['user_id'] = 'required|exists:users,id';
        }
        
        $request->validate($rules);
        
        $userId = Auth::user()->isAdmin() || Auth::user()->isManager() ? $request->user_id : Auth::id();
        
        $ticket = SupportTicket::create([
            'user_id' => $userId,
            'ticket_number' => 'TIC-' . strtoupper(Str::random(8)),
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'open',
            'priority' => $request->priority,
        ]);
        
        // Notify admins about new ticket
        if (!Auth::user()->isAdmin()) {
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewSupportTicketNotification($ticket));
            }
        }
        
        return redirect()->route('support-tickets.index')
            ->with('success', 'Support ticket created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SupportTicket $supportTicket)
    {
        // Check if user can view this ticket
        if (!Auth::user()->isAdmin() && !Auth::user()->isManager() && $supportTicket->user_id !== Auth::id()) {
            abort(403);
        }
        
        $supportTicket->load('user', 'responses.user');
        
        return view('support-tickets.show', compact('supportTicket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SupportTicket $supportTicket)
    {
        // Check if user can edit this ticket
        if (!Auth::user()->isAdmin() && !Auth::user()->isManager() && $supportTicket->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('support-tickets.edit', compact('supportTicket'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SupportTicket $supportTicket)
    {
        // Check if user can update this ticket
        if (!Auth::user()->isAdmin() && !Auth::user()->isManager() && $supportTicket->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'subject' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high',
        ]);
        
        // Only admin/manager can change status
        if (Auth::user()->isAdmin() || Auth::user()->isManager()) {
            $request->validate([
                'status' => 'required|in:open,in_progress,resolved,closed',
            ]);
            
            $supportTicket->status = $request->status;
        }
        
        $supportTicket->subject = $request->subject;
        $supportTicket->priority = $request->priority;
        $supportTicket->save();
        
        return redirect()->route('support-tickets.index')
            ->with('success', 'Support ticket updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SupportTicket $supportTicket)
    {
        // Only admin can delete tickets
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $supportTicket->delete();
        
        return redirect()->route('support-tickets.index')
            ->with('success', 'Support ticket deleted successfully.');
    }
    
    /**
     * Add a response to a ticket.
     */
    public function addResponse(Request $request, SupportTicket $supportTicket)
    {
        // Check if user can respond to this ticket
        if (!Auth::user()->isAdmin() && !Auth::user()->isManager() && $supportTicket->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'message' => 'required|string',
        ]);
        
        $response = TicketResponse::create([
            'support_ticket_id' => $supportTicket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);
        
        // Update ticket status if needed
        if ($supportTicket->status === 'open' && (Auth::user()->isAdmin() || Auth::user()->isManager())) {
            $supportTicket->update(['status' => 'in_progress']);
        }
        
        // Notify the appropriate users
        if (Auth::id() === $supportTicket->user_id) {
            // Customer responded, notify admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new TicketResponseNotification($supportTicket, $response));
            }
        } else {
            // Admin/manager responded, notify customer
            $supportTicket->user->notify(new TicketResponseNotification($supportTicket, $response));
        }
        
        return redirect()->route('support-tickets.show', $supportTicket->id)
            ->with('success', 'Response added successfully.');
    }
    
    /**
     * Close a ticket.
     */
    public function close(SupportTicket $supportTicket)
    {
        // Check if user can close this ticket
        if (!Auth::user()->isAdmin() && !Auth::user()->isManager() && $supportTicket->user_id !== Auth::id()) {
            abort(403);
        }
        
        $supportTicket->update(['status' => 'closed']);
        
        return redirect()->route('support-tickets.index')
            ->with('success', 'Ticket closed successfully.');
    }
    
    /**
     * Reopen a ticket.
     */
    public function reopen(SupportTicket $supportTicket)
    {
        // Check if user can reopen this ticket
        if (!Auth::user()->isAdmin() && !Auth::user()->isManager() && $supportTicket->user_id !== Auth::id()) {
            abort(403);
        }
        
        $supportTicket->update(['status' => 'open']);
        
        return redirect()->route('support-tickets.index')
            ->with('success', 'Ticket reopened successfully.');
    }
}