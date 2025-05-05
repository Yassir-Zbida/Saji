@extends('layouts.admin')

@section('title', 'Edit Ticket')

@section('content')
<div class="container mx-auto" x-data="editTicketData()">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">
                Edit Ticket #{{ $ticket->ticket_number }}
            </h1>
            <p class="mt-1 text-sm text-gray-500">{{ $ticket->subject }}</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <a href="{{ route('admin.tickets.show', $ticket->id) }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                <i class="ri-eye-line mr-2"></i>
                View Ticket
            </a>
            <a href="{{ route('admin.tickets.index') }}"
                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                <i class="ri-arrow-left-line mr-2"></i>
                Back to Tickets
            </a>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-100">
            <h3 class="text-lg font-medium text-gray-900">Edit Ticket Information</h3>
        </div>

        <div class="p-5">
            <form id="editTicketForm" @submit.prevent="submitForm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Customer Selection -->
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Customer</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <select id="user_id" name="user_id" x-model="formData.user_id"
                                    class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-white focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                    <option value="">Select Customer</option>
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="mt-1 text-xs text-red-600" x-show="errors.user_id" x-text="errors.user_id"></p>
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <input type="text" id="subject" name="subject" x-model="formData.subject"
                                    class="block w-full px-4 py-2.5 rounded-lg border-gray-200 bg-white focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="Enter ticket subject">
                            </div>
                            <p class="mt-1 text-xs text-red-600" x-show="errors.subject" x-text="errors.subject"></p>
                        </div>

                        <!-- Status and Priority -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <div class="relative rounded-lg border border-gray-200">
                                    <select id="status" name="status" x-model="formData.status"
                                        class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-white focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                        <option value="open">Open</option>
                                        <option value="in_progress">In Progress</option>
                                        <option value="resolved">Resolved</option>
                                        <option value="closed">Closed</option>
                                    </select>
                                </div>
                                <p class="mt-1 text-xs text-red-600" x-show="errors.status" x-text="errors.status"></p>
                            </div>
                            <div>
                                <label for="priority" class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                                <div class="relative rounded-lg border border-gray-200">
                                    <select id="priority" name="priority" x-model="formData.priority"
                                        class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-white focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                        <option value="low">Low</option>
                                        <option value="medium">Medium</option>
                                        <option value="high">High</option>
                                    </select>
                                </div>
                                <p class="mt-1 text-xs text-red-600" x-show="errors.priority" x-text="errors.priority"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div>
                        <!-- Message -->
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                            <div class="relative rounded-lg border border-gray-200">
                                <textarea id="message" name="message" x-model="formData.message" rows="10"
                                    class="block w-full px-4 py-2.5 rounded-lg border-gray-200 bg-white focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                    placeholder="Enter ticket message"></textarea>
                            </div>
                            <p class="mt-1 text-xs text-red-600" x-show="errors.message" x-text="errors.message"></p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="mt-6 flex justify-between">
                    <button type="button" @click="confirmDelete" class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md shadow-sm text-sm font-medium text-red-700 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                        <i class="ri-delete-bin-line mr-2"></i>
                        Delete Ticket
                    </button>
                    
                    <div class="flex space-x-3">
                        <button type="button" @click="resetForm"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                            <i class="ri-refresh-line mr-2"></i>
                            Reset
                        </button>
                        <button type="submit" :disabled="isSubmitting"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="ri-save-line mr-2"></i>
                            <span x-text="isSubmitting ? 'Saving...' : 'Save Changes'"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

<script>
function editTicketData() {
    return {
        formData: {
            user_id: '{{ $ticket->user_id }}',
            subject: '{{ $ticket->subject }}',
            message: '{{ addslashes($ticket->message) }}',
            status: '{{ $ticket->status }}',
            priority: '{{ $ticket->priority }}'
        },
        originalData: {
            user_id: '{{ $ticket->user_id }}',
            subject: '{{ $ticket->subject }}',
            message: '{{ addslashes($ticket->message) }}',
            status: '{{ $ticket->status }}',
            priority: '{{ $ticket->priority }}'
        },
        errors: {},
        isSubmitting: false,
        
        resetForm() {
            if (!confirm('Are you sure you want to reset the form? All changes will be lost.')) return;
            
            this.formData = { ...this.originalData };
            this.errors = {};
        },
        
        async submitForm() {
            this.errors = {};
            
            // Basic validation
            if (!this.formData.user_id) this.errors.user_id = 'Please select a customer';
            if (!this.formData.subject) this.errors.subject = 'Subject is required';
            if (!this.formData.message) this.errors.message = 'Message is required';
            
            if (Object.keys(this.errors).length > 0) return;
            
            this.isSubmitting = true;
            
            try {
                const response = await fetch('/admin/tickets/{{ $ticket->id }}', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.formData)
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showToast('Ticket updated successfully', 'success');
                    // Update original data
                    this.originalData = { ...this.formData };
                    // Redirect to the ticket view
                    window.location.href = '/admin/tickets/{{ $ticket->id }}';
                } else {
                    if (data.errors) {
                        this.errors = data.errors;
                    } else {
                        this.showToast(data.message || 'Error updating ticket', 'error');
                    }
                }
            } catch (error) {
                console.error('Error updating ticket:', error);
                this.showToast('Error updating ticket', 'error');
            } finally {
                this.isSubmitting = false;
            }
        },
        
        confirmDelete() {
            if (!confirm('Are you sure you want to delete this ticket? This action cannot be undone.')) return;
            
            this.deleteTicket();
        },
        
        async deleteTicket() {
            try {
                const response = await fetch('/admin/tickets/{{ $ticket->id }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showToast('Ticket deleted successfully', 'success');
                    // Redirect to the ticket list
                    window.location.href = '/admin/tickets';
                } else {
                    this.showToast(data.message || 'Error deleting ticket', 'error');
                }
            } catch (error) {
                console.error('Error deleting ticket:', error);
                this.showToast('Error deleting ticket', 'error');
            }
        },
        
        showToast(message, type = 'success') {
            if (typeof Toast === 'function') {
                Toast(message, type);
            } else {
                alert(message);
            }
          {
                Toast(message, type);
            } else {
                alert(message);
            }
        }
    };
}
</script>