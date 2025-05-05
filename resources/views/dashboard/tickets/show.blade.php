@extends('layouts.admin')

@section('title', 'Ticket Details')

@section('content')
<div class="container mx-auto" x-data="ticketDetailsData({{ $ticket->id }})">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">
                Ticket #{{ $ticket->ticket_number }}
            </h1>
            <p class="mt-1 text-sm text-gray-500">{{ $ticket->subject }}</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <a href="{{ route('admin.tickets.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                <i class="ri-arrow-left-line mr-2"></i>
                Back to Tickets
            </a>
            <a href="{{ route('admin.tickets.edit', $ticket->id) }}"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                <i class="ri-edit-line mr-2"></i>
                Edit Ticket
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <h3 class="text-lg font-medium text-gray-900">Ticket Details</h3>
                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($ticket->status == 'open') bg-red-100 text-red-800 @endif
                        @if($ticket->status == 'in_progress') bg-yellow-100 text-yellow-800 @endif
                        @if($ticket->status == 'resolved') bg-green-100 text-green-800 @endif
                        @if($ticket->status == 'closed') bg-gray-100 text-gray-800 @endif
                    ">
                        <i class="
                            @if($ticket->status == 'open') ri-error-warning-line @endif
                            @if($ticket->status == 'in_progress') ri-time-line @endif
                            @if($ticket->status == 'resolved') ri-check-line @endif
                            @if($ticket->status == 'closed') ri-archive-line @endif
                            mr-1
                        "></i>
                        {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                    </span>
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($ticket->priority == 'low') bg-green-100 text-green-800 @endif
                        @if($ticket->priority == 'medium') bg-yellow-100 text-yellow-800 @endif
                        @if($ticket->priority == 'high') bg-red-100 text-red-800 @endif
                    ">
                        <i class="
                            @if($ticket->priority == 'low') ri-arrow-down-line @endif
                            @if($ticket->priority == 'medium') ri-arrow-right-line @endif
                            @if($ticket->priority == 'high') ri-arrow-up-line @endif
                            mr-1
                        "></i>
                        {{ ucfirst($ticket->priority) }} Priority
                    </span>
                </div>
                <div class="text-sm text-gray-500">
                    Created: {{ $ticket->created_at->format('M d, Y h:i A') }}
                </div>
            </div>
        </div>

        <div class="p-5">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Ticket Info Sidebar -->
                <div class="md:col-span-1 space-y-6">
                    <!-- Customer Info -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Customer Information</h4>
                        <div class="flex items-center mb-3">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                                <i class="ri-user-line"></i>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">{{ $ticket->user->name }}</div>
                                <div class="text-xs text-gray-500">{{ $ticket->user->email }}</div>
                            </div>
                        </div>
                        <div class="text-xs text-gray-500 space-y-1">
                            <div class="flex items-center">
                                <i class="ri-phone-line w-4 mr-1"></i>
                                <span>{{ $ticket->user->phone ?? 'No phone provided' }}</span>
                            </div>
                            <div class="flex items-start">
                                <i class="ri-map-pin-line w-4 mr-1 mt-1"></i>
                                <span>
                                    @if(isset($ticket->user->address))
                                        {{ $ticket->user->address }}, 
                                        {{ $ticket->user->city ?? '' }} 
                                        {{ $ticket->user->state ?? '' }} 
                                        {{ $ticket->user->zip_code ?? '' }}
                                    @else
                                        No address provided
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Ticket Details -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Ticket Details</h4>
                        <div class="text-xs text-gray-500 space-y-2">
                            <div class="flex justify-between">
                                <span class="font-medium">Status:</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    @if($ticket->status == 'open') bg-red-100 text-red-800 @endif
                                    @if($ticket->status == 'in_progress') bg-yellow-100 text-yellow-800 @endif
                                    @if($ticket->status == 'resolved') bg-green-100 text-green-800 @endif
                                    @if($ticket->status == 'closed') bg-gray-100 text-gray-800 @endif
                                ">
                                    {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Priority:</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    @if($ticket->priority == 'low') bg-green-100 text-green-800 @endif
                                    @if($ticket->priority == 'medium') bg-yellow-100 text-yellow-800 @endif
                                    @if($ticket->priority == 'high') bg-red-100 text-red-800 @endif
                                ">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Created:</span>
                                <span>{{ $ticket->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="font-medium">Last Updated:</span>
                                <span>{{ $ticket->updated_at->format('M d, Y h:i A') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Ticket Actions -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Actions</h4>
                        <div class="space-y-2">
                            <div>
                                <label for="status_update" class="block text-xs font-medium text-gray-700 mb-1">Update Status</label>
                                <select id="status_update" x-model="ticketStatus" @change="updateTicketStatus()"
                                    class="block w-full pl-3 pr-10 py-2 text-xs rounded-md border-gray-200 bg-white focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 transition-all shadow-sm">
                                    <option value="open">Open</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="resolved">Resolved</option>
                                    <option value="closed">Closed</option>
                                </select>
                            </div>
                            <div>
                                <label for="priority_update" class="block text-xs font-medium text-gray-700 mb-1">Update Priority</label>
                                <select id="priority_update" x-model="ticketPriority" @change="updateTicketPriority()"
                                    class="block w-full pl-3 pr-10 py-2 text-xs rounded-md border-gray-200 bg-white focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 transition-all shadow-sm">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                </select>
                            </div>
                            <div class="pt-2">
                                @if($ticket->status !== 'closed')
                                <button @click="closeTicket()" 
                                    class="w-full inline-flex justify-center items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-xs font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                    <i class="ri-close-circle-line mr-1"></i>
                                    Close Ticket
                                </button>
                                @else
                                <button @click="reopenTicket()"
                                    class="w-full inline-flex justify-center items-center px-3 py-2 border border-transparent rounded-md shadow-sm text-xs font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                    <i class="ri-refresh-line mr-1"></i>
                                    Reopen Ticket
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Conversation Thread -->
                <div class="md:col-span-3">
                    <!-- Original Message -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <div class="flex justify-between items-center mb-2">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                                    <i class="ri-user-line"></i>
                                </div>
                                <div class="ml-2">
                                    <span class="text-sm font-medium text-gray-900">{{ $ticket->user->name }}</span>
                                    <span class="text-xs text-gray-500 ml-2">{{ $ticket->created_at->format('M d, Y h:i A') }}</span>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="ri-chat-new-line mr-1"></i>
                                Original Message
                            </span>
                        </div>
                        <div class="text-sm text-gray-700 whitespace-pre-line">{{ $ticket->message }}</div>
                    </div>

                    <!-- Responses -->
                    <div class="space-y-4 mb-6">
                        @foreach($ticket->responses as $response)
                        <div class="p-4 rounded-lg {{ $response->user->role === 'customer' ? 'bg-gray-50' : 'bg-blue-50' }}">
                            <div class="flex justify-between items-center mb-2">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full flex items-center justify-center
                                        {{ $response->user->role === 'customer' ? 'bg-primary/10 text-primary' : 'bg-blue-100 text-blue-800' }}">
                                        <i class="{{ $response->user->role === 'customer' ? 'ri-user-line' : 'ri-customer-service-line' }}"></i>
                                    </div>
                                    <div class="ml-2">
                                        <span class="text-sm font-medium text-gray-900">{{ $response->user->name }}</span>
                                        <span class="text-xs text-gray-500 ml-2">{{ $response->created_at->format('M d, Y h:i A') }}</span>
                                    </div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $response->user->role === 'customer' ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800' }}">
                                    <i class="{{ $response->user->role === 'customer' ? 'ri-user-line mr-1' : 'ri-customer-service-line mr-1' }}"></i>
                                    {{ ucfirst($response->user->role) }}
                                </span>
                            </div>
                            <div class="text-sm text-gray-700 whitespace-pre-line">{{ $response->message }}</div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Reply Form -->
                    @if($ticket->status !== 'closed')
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-2">Reply to Ticket</h4>
                        <form action="{{ route('admin.tickets.response', $ticket->id) }}" method="POST" id="replyForm">
                            @csrf
                            <div class="mb-3">
                                <textarea name="message" id="message" rows="4" x-model="replyMessage"
                                    class="block w-full px-4 py-3 rounded-lg border border-gray-200 bg-white focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="Type your response here..."></textarea>
                            </div>
                            <div class="flex justify-between items-center">
                                <div class="flex items-center">
                                    <input type="checkbox" id="update_status" name="update_status" value="1" x-model="updateStatusOnReply"
                                        class="h-4 w-4 text-primary border-gray-300 rounded focus:ring-primary">
                                    <label for="update_status" class="ml-2 text-sm text-gray-700">
                                        Update status to "In Progress" when replying
                                    </label>
                                </div>
                                <button type="button" @click="submitReply()" :disabled="isSubmitting || !replyMessage.trim()"
                                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                    <i class="ri-send-plane-line mr-2"></i>
                                    <span x-text="isSubmitting ? 'Sending...' : 'Send Reply'"></span>
                                </button>
                            </div>
                        </form>
                    </div>
                    @else
                    <!-- Closed Ticket Message -->
                    <div class="bg-gray-100 p-4 rounded-lg text-center">
                        <p class="text-gray-700 mb-3">This ticket is closed. Reopen it to add more responses.</p>
                        <button @click="reopenTicket()"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                            <i class="ri-refresh-line mr-2"></i>
                            Reopen Ticket
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
function ticketDetailsData(ticketId) {
    return {
        ticketId: ticketId,
        ticketStatus: '{{ $ticket->status }}',
        ticketPriority: '{{ $ticket->priority }}',
        replyMessage: '',
        updateStatusOnReply: true,
        isSubmitting: false,
        
        init() {
            // Initialize data
        },
        
        async updateTicketStatus() {
            try {
                const response = await fetch(`/admin/tickets/${this.ticketId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        status: this.ticketStatus
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showToast('Ticket status updated', 'success');
                    // Reload page to show updated status
                    window.location.reload();
                } else {
                    this.showToast(data.message || 'Error updating status', 'error');
                }
            } catch (error) {
                console.error('Error updating status:', error);
                this.showToast('Error updating status', 'error');
            }
        },
        
        async updateTicketPriority() {
            try {
                const response = await fetch(`/admin/tickets/${this.ticketId}/priority`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        priority: this.ticketPriority
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showToast('Ticket priority updated', 'success');
                    // Reload page to show updated priority
                    window.location.reload();
                } else {
                    this.showToast(data.message || 'Error updating priority', 'error');
                }
            } catch (error) {
                console.error('Error updating priority:', error);
                this.showToast('Error updating priority', 'error');
            }
        },
        
        async submitReply() {
            if (!this.replyMessage.trim()) return;
            
            this.isSubmitting = true;
            
            try {
                const response = await fetch(`/admin/tickets/${this.ticketId}/response`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        message: this.replyMessage,
                        update_status: this.updateStatusOnReply
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showToast('Reply sent successfully', 'success');
                    this.replyMessage = '';
                    
                    // Reload page to show new response
                    window.location.reload();
                } else {
                    this.showToast(data.message || 'Error sending reply', 'error');
                }
            } catch (error) {
                console.error('Error sending reply:', error);
                this.showToast('Error sending reply', 'error');
            } finally {
                this.isSubmitting = false;
            }
        },
        
        async closeTicket() {
            if (!confirm('Are you sure you want to close this ticket?')) return;
            
            try {
                const response = await fetch(`/admin/tickets/${this.ticketId}/close`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showToast('Ticket closed successfully', 'success');
                    // Reload page to show closed status
                    window.location.reload();
                } else {
                    this.showToast(data.message || 'Error closing ticket', 'error');
                }
            } catch (error) {
                console.error('Error closing ticket:', error);
                this.showToast('Error closing ticket', 'error');
            }
        },
        
        async reopenTicket() {
            try {
                const response = await fetch(`/admin/tickets/${this.ticketId}/reopen`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showToast('Ticket reopened successfully', 'success');
                    // Reload page to show reopened status
                    window.location.reload();
                } else {
                    this.showToast(data.message || 'Error reopening ticket', 'error');
                }
            } catch (error) {
                console.error('Error reopening ticket:', error);
                this.showToast('Error reopening ticket', 'error');
            }
        },
        
        showToast(message, type = 'success') {
            if (typeof Toast === 'function') {
                Toast(message, type);
            } else {
                alert(message);
            }
        }
    };
}
</script>