@extends('layouts.admin')

@section('title', 'Support Tickets')

@section('content')
<div class="container mx-auto" x-data="ticketsData()">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">Support Tickets</h1>
            <p class="mt-1 text-sm text-gray-500">Manage and respond to customer support tickets</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <a href="{{ route('admin.tickets.create') }}"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                <i class="ri-add-line mr-2"></i>
                New Ticket
            </a>
            <button type="button" @click="showFilters = !showFilters"
                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                <i class="ri-filter-3-line mr-2"></i>
                Filters
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
        <!-- Open Tickets -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Open Tickets</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.open || 0">0</h3>
                </div>
                <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center">
                    <i class="ri-error-warning-line text-xl text-red-600"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center text-sm">
                <span class="text-gray-500">Require immediate attention</span>
            </div>
        </div>

        <!-- In Progress Tickets -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">In Progress</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.in_progress || 0">0</h3>
                </div>
                <div class="w-12 h-12 bg-yellow-50 rounded-full flex items-center justify-center">
                    <i class="ri-time-line text-xl text-yellow-600"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center text-sm">
                <span class="text-gray-500">Currently being handled</span>
            </div>
        </div>

        <!-- Resolved Tickets -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Resolved</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.resolved || 0">0</h3>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center">
                    <i class="ri-check-line text-xl text-green-600"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center text-sm">
                <span class="text-gray-500">Successfully addressed</span>
            </div>
        </div>

        <!-- Closed Tickets -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Closed</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-1" x-text="stats.closed || 0">0</h3>
                </div>
                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                    <i class="ri-archive-line text-xl text-gray-600"></i>
                </div>
            </div>
            <div class="mt-3 flex items-center text-sm">
                <span class="text-gray-500">Completed and archived</span>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6 transition-all duration-300 hover:shadow-md"
        x-show="showFilters" x-transition>
        <div class="p-5 border-b border-gray-100 flex justify-between items-center">
            <div class="flex items-center">
                <i class="ri-filter-3-line text-lg text-primary mr-3"></i>
                <h3 class="text-lg font-medium text-gray-900">Filter Tickets</h3>
            </div>
            <button @click="showFilters = false" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="ri-close-line text-lg"></i>
            </button>
        </div>

        <div class="p-5">
            <form id="ticketFilterForm" @submit.prevent="applyFilters()">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                    <!-- Status Filter -->
                    <div class="relative">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                        <div class="relative rounded-lg border border-gray-200">
                            <select id="status" name="status" x-model="filters.status"
                                class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                <option value="">All Statuses</option>
                                <option value="open">Open</option>
                                <option value="in_progress">In Progress</option>
                                <option value="resolved">Resolved</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>
                    </div>

                    <!-- Priority Filter -->
                    <div class="relative">
                        <label for="priority" class="block text-sm font-medium text-gray-700 mb-1.5">Priority</label>
                        <div class="relative rounded-lg border border-gray-200">
                            <select id="priority" name="priority" x-model="filters.priority"
                                class="block w-full pl-4 pr-10 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                                <option value="">All Priorities</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>

                    <!-- From Date Filter -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-1.5">From
                            Date</label>
                        <div class="relative rounded-lg border border-gray-200">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ri-calendar-line text-gray-400"></i>
                            </div>
                            <input type="date" id="date_from" name="date_from" x-model="filters.date_from"
                                class="block w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm">
                        </div>
                    </div>

                    <!-- Search Filter -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1.5">Search
                            Tickets</label>
                        <div class="relative rounded-lg border border-gray-200">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ri-search-line text-gray-400"></i>
                            </div>
                            <input type="text" id="search" name="search" x-model="filters.search"
                                class="block w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-200 bg-gray-50 focus:border-primary focus:ring focus:ring-primary/20 focus:ring-opacity-50 text-sm transition-all shadow-sm"
                                placeholder="Ticket #, Subject...">
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-5 flex justify-end gap-3">
                    <button type="button" @click="resetFilters()"
                        class="inline-flex items-center px-4 py-2.5 border border-gray-200 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        <i class="ri-refresh-line mr-2"></i>
                        Reset
                    </button>
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        <i class="ri-filter-line mr-2"></i>
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden">
        <div class="flex justify-between items-center p-5 border-b border-gray-100">
            <h3 class="text-lg font-medium text-gray-900">Support Tickets</h3>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Showing <span x-text="ticketsCount"></span> of <span
                        x-text="totalTickets"></span> tickets</span>
            </div>
        </div>

        <div class="overflow-x-auto" x-show="!isLoading">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200 cursor-pointer"
                            @click="sortBy('ticket_number')">
                            <div class="flex items-center">
                                <span>Ticket #</span>
                                <i class="ri-arrow-up-s-line ml-1" x-show="sortField === 'ticket_number' && sortDirection === 'asc'"></i>
                                <i class="ri-arrow-down-s-line ml-1" x-show="sortField === 'ticket_number' && sortDirection === 'desc'"></i>
                            </div>
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200 cursor-pointer"
                            @click="sortBy('subject')">
                            <div class="flex items-center">
                                <span>Subject</span>
                                <i class="ri-arrow-up-s-line ml-1" x-show="sortField === 'subject' && sortDirection === 'asc'"></i>
                                <i class="ri-arrow-down-s-line ml-1" x-show="sortField === 'subject' && sortDirection === 'desc'"></i>
                            </div>
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                            Customer</th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200 cursor-pointer"
                            @click="sortBy('status')">
                            <div class="flex items-center">
                                <span>Status</span>
                                <i class="ri-arrow-up-s-line ml-1" x-show="sortField === 'status' && sortDirection === 'asc'"></i>
                                <i class="ri-arrow-down-s-line ml-1" x-show="sortField === 'status' && sortDirection === 'desc'"></i>
                            </div>
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200 cursor-pointer"
                            @click="sortBy('priority')">
                            <div class="flex items-center">
                                <span>Priority</span>
                                <i class="ri-arrow-up-s-line ml-1" x-show="sortField === 'priority' && sortDirection === 'asc'"></i>
                                <i class="ri-arrow-down-s-line ml-1" x-show="sortField === 'priority' && sortDirection === 'desc'"></i>
                            </div>
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200 cursor-pointer"
                            @click="sortBy('created_at')">
                            <div class="flex items-center">
                                <span>Created</span>
                                <i class="ri-arrow-up-s-line ml-1" x-show="sortField === 'created_at' && sortDirection === 'asc'"></i>
                                <i class="ri-arrow-down-s-line ml-1" x-show="sortField === 'created_at' && sortDirection === 'desc'"></i>
                            </div>
                        </th>
                        <th scope="col"
                            class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider border-b border-gray-200">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <template x-for="ticket in tickets" :key="ticket.id">
                        <tr class="hover:bg-gray-50 transition-colors" :class="{'bg-blue-50': selectedTicket && selectedTicket.id === ticket.id}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900" x-text="ticket.ticket_number"></div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900" x-text="ticket.subject"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                                        <i class="ri-user-line"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900" x-text="ticket.user ? ticket.user.name : 'Unknown'"></div>
                                        <div class="text-xs text-gray-500" x-text="ticket.user ? ticket.user.email : ''"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    :class="{
                                        'bg-red-100 text-red-800': ticket.status === 'open',
                                        'bg-yellow-100 text-yellow-800': ticket.status === 'in_progress',
                                        'bg-green-100 text-green-800': ticket.status === 'resolved',
                                        'bg-gray-100 text-gray-800': ticket.status === 'closed'
                                    }">
                                    <i :class="{
                                        'ri-error-warning-line mr-1': ticket.status === 'open',
                                        'ri-time-line mr-1': ticket.status === 'in_progress',
                                        'ri-check-line mr-1': ticket.status === 'resolved',
                                        'ri-archive-line mr-1': ticket.status === 'closed'
                                    }"></i>
                                    <span x-text="capitalizeFirst(ticket.status)"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                    :class="{
                                        'bg-green-100 text-green-800': ticket.priority === 'low',
                                        'bg-yellow-100 text-yellow-800': ticket.priority === 'medium',
                                        'bg-red-100 text-red-800': ticket.priority === 'high'
                                    }">
                                    <i :class="{
                                        'ri-arrow-down-line mr-1': ticket.priority === 'low',
                                        'ri-arrow-right-line mr-1': ticket.priority === 'medium',
                                        'ri-arrow-up-line mr-1': ticket.priority === 'high'
                                    }"></i>
                                    <span x-text="capitalizeFirst(ticket.priority)"></span>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="formatDate(ticket.created_at)">
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    <a :href="`/admin/tickets/${ticket.id}`"
                                        class="inline-flex items-center px-3 py-1.5 border border-black rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-gray-50 hover:text-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                        <i class="ri-eye-line mr-1"></i>
                                        View
                                    </a>
                                    <a :href="`/admin/tickets/${ticket.id}/edit`"
                                        class="inline-flex items-center px-3 py-1.5 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                                        <i class="ri-edit-line mr-1"></i>
                                        Edit
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Loading Indicator -->
        <div class="p-10 flex justify-center items-center" x-show="isLoading">
            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-primary"></div>
        </div>

        <!-- Empty State -->
        <div class="text-center py-16" x-show="!isLoading && tickets.length === 0">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 rounded-full mb-6">
                <i class="ri-customer-service-line text-3xl text-gray-400"></i>
            </div>
            <h3 class="text-xl font-medium text-primary mb-3">No tickets found</h3>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">There are no support tickets matching your criteria.</p>
            <button @click="resetFilters()"
                class="inline-flex items-center px-6 py-3 border border-gray-300 bg-white text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                <i class="ri-refresh-line mr-2"></i> Reset Filters
            </button>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between"
            x-show="!isLoading && tickets.length > 0">
            <div class="flex-1 flex justify-between sm:hidden">
                <button @click="prevPage()" :disabled="currentPage === 1"
                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                    :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }">
                    Previous
                </button>
                <button @click="nextPage()" :disabled="currentPage === lastPage"
                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                    :class="{ 'opacity-50 cursor-not-allowed': currentPage === lastPage }">
                    Next
                </button>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing
                        <span class="font-medium" x-text="(currentPage - 1) * perPage + 1"></span>
                        to
                        <span class="font-medium" x-text="Math.min(currentPage * perPage, totalTickets)"></span>
                        of
                        <span class="font-medium" x-text="totalTickets"></span>
                        results
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <button @click="prevPage()" :disabled="currentPage === 1"
                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                            :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }">
                            <span class="sr-only">Previous</span>
                            <i class="ri-arrow-left-s-line"></i>
                        </button>
                        <!-- Pagination Numbers -->
                        <template x-for="page in paginationPages" :key="page">
                            <button @click="goToPage(page)"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium hover:bg-gray-50"
                                :class="page === currentPage ?
                                    'z-10 bg-primary text-black hover:text-white border-primary hover:bg-primary/90' :
                                    'text-gray-500'">
                                <span x-text="page"></span>
                            </button>
                        </template>
                        <button @click="nextPage()" :disabled="currentPage === lastPage"
                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                            :class="{ 'opacity-50 cursor-not-allowed': currentPage === lastPage }">
                            <span class="sr-only">Next</span>
                            <i class="ri-arrow-right-s-line"></i>
                        </button>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
function ticketsData() {
    return {
        // State variables
        tickets: [],
        stats: {
            open: 0,
            in_progress: 0,
            resolved: 0,
            closed: 0
        },
        isLoading: true,
        isSubmitting: false,
        showFilters: false,
        
        selectedTicket: null,
        ticketResponses: [],
        replyMessage: '',
        updateStatusOnReply: true,
        ticketStatus: '',
        ticketPriority: '',
        
        currentPage: 1,
        lastPage: 1,
        totalTickets: 0,
        perPage: 10,
        
        sortField: 'created_at',
        sortDirection: 'desc',
        
        filters: {
            status: '',
            priority: '',
            date_from: '',
            search: ''
        },
        
        get ticketsCount() {
            return this.tickets.length;
        },
        
        get paginationPages() {
            const pages = [];
            const maxPages = 5;
            
            if (this.lastPage <= maxPages) {
                for (let i = 1; i <= this.lastPage; i++) {
                    pages.push(i);
                }
            } else {
                let startPage = Math.max(1, this.currentPage - 2);
                let endPage = Math.min(this.lastPage, startPage + maxPages - 1);
                
                if (endPage - startPage < maxPages - 1) {
                    startPage = Math.max(1, endPage - maxPages + 1);
                }
                
                for (let i = startPage; i <= endPage; i++) {
                    pages.push(i);
                }
            }
            
            return pages;
        },
        
        init() {
            this.loadTickets();
            this.loadStats();
            
            this.currentPage = 1;
            this.lastPage = 1;
        },
        
        async loadTickets() {
            this.isLoading = true;
            
            try {
                const params = new URLSearchParams({
                    page: this.currentPage,
                    per_page: this.perPage,
                    sort_field: this.sortField,
                    sort_direction: this.sortDirection,
                    status: this.filters.status,
                    priority: this.filters.priority,
                    date_from: this.filters.date_from,
                    search: this.filters.search
                });
                
                const response = await fetch(`/admin/tickets/data?${params.toString()}`);
                const data = await response.json();
                
                if (data.success) {
                    this.tickets = data.data;
                    this.currentPage = data.pagination.current_page;
                    this.lastPage = data.pagination.last_page;
                    this.totalTickets = data.pagination.total;
                } else {
                    console.error('Error loading tickets:', data.message);
                    this.showToast('Error loading tickets', 'error');
                }
            } catch (error) {
                console.error('Error loading tickets:', error);
                this.showToast('Error loading tickets', 'error');
            } finally {
                this.isLoading = false;
            }
        },
        
        async loadStats() {
            try {
                const response = await fetch('/admin/tickets/stats');
                const data = await response.json();
                
                if (data.success) {
                    this.stats = data.stats;
                } else {
                    console.error('Error loading statistics:', data.message);
                }
            } catch (error) {
                console.error('Error loading statistics:', error);
            }
        },
        
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.loadTickets();
            }
        },
        
        nextPage() {
            if (this.currentPage < this.lastPage) {
                this.currentPage++;
                this.loadTickets();
            }
        },
        
        goToPage(page) {
            if (page >= 1 && page <= this.lastPage) {
                this.currentPage = page;
                this.loadTickets();
            }
        },
        
        sortBy(field) {
            if (this.sortField === field) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortField = field;
                this.sortDirection = 'desc';
            }
            
            this.loadTickets();
        },
        
        applyFilters() {
            this.currentPage = 1; 
            this.loadTickets();
        },
        
        resetFilters() {
            this.filters = {
                status: '',
                priority: '',
                date_from: '',
                search: ''
            };
            
            this.currentPage = 1;
            this.loadTickets();
        },
        
        formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
        },
        
        capitalizeFirst(string) {
            if (!string) return '';
            return string.charAt(0).toUpperCase() + string.slice(1).replace('_', ' ');
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