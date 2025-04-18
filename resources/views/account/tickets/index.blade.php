@extends('layouts.app')

@section('title', 'Support Tickets')

@section('content')
<div class="account-dashboard py-12 bg-gray-100 md:px-4 md:pb-28">
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <div class="text-sm text-gray-500 mb-2 flex items-center">
                <a href="/" class="hover:text-primary">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('profile.index') }}" class="hover:text-primary">My Account</a>
                <span class="mx-2">/</span>
                <span>Support Tickets</span>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-primary">Support Tickets</h1>
            <p class="text-gray-600 mt-2 font-jost">View and manage your support requests.</p>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8 overflow-hidden">
            <div class="flex flex-wrap overflow-x-auto">
                <a href="{{ route('profile.index') }}" class="inline-flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="ri-dashboard-line mr-3 text-gray-500"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('account.orders') }}" class="inline-flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="ri-shopping-bag-line mr-3 text-gray-500"></i>
                    <span>My Orders</span>
                </a>
                <a href="{{ route('account.addresses') }}" class="inline-flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="ri-map-pin-line mr-3 text-gray-500"></i>
                    <span>My Addresses</span>
                </a>
                <a href="{{ route('account.tickets') }}" class="inline-flex items-center px-6 py-4 text-primary font-medium bg-gray-50 border-b-2 border-primary">
                    <i class="ri-customer-service-2-line mr-3"></i>
                    <span>Support Tickets</span>
                </a>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="inline-flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="ri-logout-box-line mr-3 text-gray-500"></i>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>

        <!-- Tickets Overview Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 transition-all duration-300 hover:shadow-md mb-8" data-aos="fade-up" data-aos-delay="100">
            <div class="p-6 md:p-8">
                <div class="flex flex-wrap md:flex-nowrap items-center justify-between gap-6">
                    <div class="flex items-center">
                        <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mr-6">
                            <i class="ri-customer-service-2-line text-2xl text-gray-500"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-primary">Support Tickets</h2>
                            <p class="text-gray-500 mt-1 mb-1">
                                <i class="ri-ticket-line mr-2"></i>
                                @if($tickets->isEmpty())
                                    No active tickets
                                @else
                                    {{ $tickets->where('status', 'open')->count() }} open
                                    {{ $tickets->where('status', 'in_progress')->count() > 0 ? ', ' . $tickets->where('status', 'in_progress')->count() . ' in progress' : '' }}
                                @endif
                            </p>
                            <p class="text-gray-500">
                                <i class="ri-question-answer-line mr-2"></i>
                                Get help with your orders and account issues
                            </p>
                        </div>
                    </div>
                    <div>
                        <a href="{{ route('account.tickets.create') }}" class="inline-flex items-center px-6 py-3 bg-primary text-white rounded-md hover:bg-primary/90 transition-all duration-300">
                            <i class="ri-add-line mr-2"></i> Create New Ticket
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tickets List -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 transition-all duration-300 hover:shadow-md" data-aos="fade-up" data-aos-delay="200">
            <div class="card-header border-b border-gray-200 px-6 py-5 flex items-center">
                <i class="ri-list-check text-xl text-gray-500 mr-3"></i>
                <h3 class="text-lg font-medium text-primary">All Tickets</h3>
            </div>
            <div class="p-6">
                @if($tickets->isEmpty())
                    <div class="text-center py-10">
                        <div class="bg-gray-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                            <i class="ri-customer-service-2-line text-2xl text-gray-400"></i>
                        </div>
                        <h4 class="text-lg font-medium text-primary mb-2">No support tickets yet</h4>
                        <p class="text-gray-500 mb-4">You haven't created any support tickets. Create a ticket if you need assistance.</p>
                        <a href="{{ route('account.tickets.create') }}" class="inline-flex items-center px-5 py-2 border border-primary bg-primary text-white rounded-md hover:bg-primary/90 transition-all duration-300">
                            <i class="ri-add-line mr-2"></i> Create Your First Ticket
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($tickets as $ticket)
                            <div class="border border-gray-200 rounded-lg bg-white hover:border-gray-300 transition-all duration-300">
                                <div class="flex items-center py-5 px-6">
                                    <div class="w-1/4">
                                        <div class="text-sm font-medium text-gray-800">{{ $ticket->ticket_number }}</div>
                                        <div class="text-xs text-gray-500 mt-1">{{ $ticket->created_at->format('M d, Y') }}</div>
                                    </div>
                                    <div class="w-2/4">
                                        <div class="text-sm text-gray-800">{{ $ticket->subject }}</div>
                                        <div class="flex items-center space-x-2 mt-2">
                                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-full
                                                @if($ticket->priority == 'low') bg-green-100 text-green-800
                                                @elseif($ticket->priority == 'medium') bg-yellow-100 text-yellow-800
                                                @elseif($ticket->priority == 'high') bg-red-100 text-red-800
                                                @endif">
                                                {{ ucfirst($ticket->priority) }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-full
                                                @if($ticket->status == 'open') bg-blue-100 text-blue-800
                                                @elseif($ticket->status == 'in_progress') bg-indigo-100 text-indigo-800
                                                @elseif($ticket->status == 'resolved') bg-green-100 text-green-800
                                                @elseif($ticket->status == 'closed') bg-gray-100 text-gray-800
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="w-1/4 flex justify-end space-x-2">
                                        <a href="{{ route('account.tickets.show', $ticket) }}" class="inline-flex items-center justify-center px-4 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none">
                                            <i class="ri-eye-line mr-1.5"></i> View Details
                                        </a>
                                        
                                        @if($ticket->status == 'closed')
                                            <form action="{{ route('account.tickets.reopen', $ticket) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-xs font-medium text-blue-700 bg-white border border-blue-300 rounded-md shadow-sm hover:bg-blue-50 focus:outline-none">
                                                    <i class="ri-refresh-line mr-1.5"></i> Reopen
                                                </button>
                                            </form>
                                        @elseif($ticket->status != 'closed')
                                            <form action="{{ route('account.tickets.close', $ticket) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none">
                                                    <i class="ri-close-circle-line mr-1.5"></i> Close
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if(method_exists($tickets, 'links'))
                        <div class="mt-6">
                            {{ $tickets->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.bg-white.rounded-lg');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        card.style.transitionDelay = `${index * 0.1}s`;
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100);
    });
    
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true
        });
    }
});
</script>
@endsection