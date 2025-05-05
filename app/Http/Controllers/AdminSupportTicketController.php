<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\TicketResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AdminSupportTicketController extends Controller
{
    /**
     * Display a listing of the tickets.
     */
    public function index()
    {
        return view('dashboard.tickets.index');
    }

    /**
     * Get tickets data for AJAX requests.
     */
    public function getTicketsData(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $sortField = $request->input('sort_field', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $status = $request->input('status');
        $priority = $request->input('priority');
        $dateFrom = $request->input('date_from');
        $search = $request->input('search');
        
        $query = SupportTicket::with('user');
        
        // Apply filters
        if ($status) {
            $query->where('status', $status);
        }
        
        if ($priority) {
            $query->where('priority', $priority);
        }
        
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Apply sorting
        $query->orderBy($sortField, $sortDirection);
        
        // Paginate results
        $tickets = $query->paginate($perPage);
        
        return response()->json([
            'success' => true,
            'data' => $tickets->items(),
            'pagination' => [
                'current_page' => $tickets->currentPage(),
                'last_page' => $tickets->lastPage(),
                'per_page' => $tickets->perPage(),
                'total' => $tickets->total()
            ]
        ]);
    }

    /**
     * Get ticket statistics.
     */
    public function getStats()
    {
        $stats = [
            'open' => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in_progress')->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
            'closed' => SupportTicket::where('status', 'closed')->count(),
        ];
        
        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // For admin/manager, show user selection
        $users = User::where('role', 'customer')->get();
        
        return view('dashboard.tickets.create', compact('users'));
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
            'user_id' => 'required|exists:users,id',
        ];

        $validator = validator($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $ticket = new SupportTicket();
        $ticket->user_id = $request->user_id;
        $ticket->ticket_number = 'TKT-' . strtoupper(Str::random(8));
        $ticket->subject = $request->subject;
        $ticket->message = $request->message;
        $ticket->status = 'open';
        $ticket->priority = $request->priority;
        $ticket->save();

        return response()->json([
            'success' => true,
            'message' => 'Ticket created successfully',
            'ticket' => $ticket
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $ticket = SupportTicket::with(['responses.user', 'user'])->findOrFail($id);

        return view('dashboard.tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $users = User::where('role', 'customer')->get();
        
        return view('dashboard.tickets.edit', compact('ticket', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $rules = [
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:open,in_progress,resolved,closed',
        ];

        $validator = validator($request->all(), $rules);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $ticket->subject = $request->subject;
        $ticket->message = $request->message;
        $ticket->user_id = $request->user_id;
        $ticket->priority = $request->priority;
        $ticket->status = $request->status;
        $ticket->save();

        return response()->json([
            'success' => true,
            'message' => 'Ticket updated successfully',
            'ticket' => $ticket
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ticket deleted successfully'
        ]);
    }

    /**
     * Add a response to a ticket.
     */
    public function addResponse(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $validator = validator($request->all(), [
            'message' => 'required|string',
            'update_status' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        // Create the response
        $response = new TicketResponse();
        $response->message = $request->message;
        $response->user_id = Auth::id();
        $response->support_ticket_id = $ticket->id;
        $response->save();
        
        // Update ticket status if requested
        if ($request->update_status && $ticket->status !== 'in_progress') {
            $ticket->status = 'in_progress';
            $ticket->save();
        }
        
        // Load the updated ticket with responses
        $ticket->load(['responses.user', 'user']);
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'ticket' => $ticket,
                'message' => 'Response added successfully'
            ]);
        }
        
        return redirect()->route('admin.tickets.show', $ticket->id)
            ->with('success', 'Response added successfully');
    }

    /**
     * Update the status of a ticket.
     */
    public function updateStatus(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $validator = validator($request->all(), [
            'status' => 'required|in:open,in_progress,resolved,closed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $ticket->status = $request->status;
        $ticket->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Ticket status updated successfully'
        ]);
    }

    /**
     * Update the priority of a ticket.
     */
    public function updatePriority(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);

        $validator = validator($request->all(), [
            'priority' => 'required|in:low,medium,high'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $ticket->priority = $request->priority;
        $ticket->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Ticket priority updated successfully'
        ]);
    }

    /**
     * Close a ticket.
     */
    public function close($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->status = 'closed';
        $ticket->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Ticket closed successfully'
        ]);
    }

    /**
     * Reopen a ticket.
     */
    public function reopen($id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->status = 'open';
        $ticket->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Ticket reopened successfully'
        ]);
    }
}