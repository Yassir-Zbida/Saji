@extends('layouts.app')

@section('title', 'Ticket Details')

@section('content')
<div class="account-dashboard py-12 bg-gray-100 md:px-4 md:pb-28">
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <div class="text-sm text-gray-500 mb-2 flex items-center">
                <a href="/" class="hover:text-primary">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('profile.index') }}" class="hover:text-primary">My Account</a>
                <span class="mx-2">/</span>
                <a href="{{ route('account.tickets') }}" class="hover:text-primary">Support Tickets</a>
                <span class="mx-2">/</span>
                <span>{{ $ticket->ticket_number }}</span>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-primary">Ticket Details</h1>
            <p class="text-gray-600 mt-2 font-jost">View and manage your support ticket.</p>
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Ticket Info -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 transition-all duration-300 hover:shadow-md mb-8" data-aos="fade-up" data-aos-delay="100">
                    <div class="card-header border-b border-gray-200 px-6 py-5 flex items-center">
                        <i class="ri-ticket-line text-xl text-gray-500 mr-3"></i>
                        <h3 class="text-lg font-medium text-primary">Ticket Information</h3>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm font-semibold text-gray-700">Ticket Number</span>
                            <span class="text-sm text-gray-600">{{ $ticket->ticket_number }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm font-semibold text-gray-700">Status</span>
                            <span class="inline-flex px-2 py-1 text-xs rounded-full
                                @if($ticket->status == 'open') bg-blue-100 text-blue-800
                                @elseif($ticket->status == 'in_progress') bg-indigo-100 text-indigo-800
                                @elseif($ticket->status == 'resolved') bg-green-100 text-green-800
                                @elseif($ticket->status == 'closed') bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $ticket->status)) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm font-semibold text-gray-700">Priority</span>
                            <span class="inline-flex px-2 py-1 text-xs rounded-full
                                @if($ticket->priority == 'low') bg-green-100 text-green-800
                                @elseif($ticket->priority == 'medium') bg-yellow-100 text-yellow-800
                                @elseif($ticket->priority == 'high') bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($ticket->priority) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm font-semibold text-gray-700">Created</span>
                            <span class="text-sm text-gray-600">{{ $ticket->created_at->format('M d, Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-sm font-semibold text-gray-700">Last Updated</span>
                            <span class="text-sm text-gray-600">{{ $ticket->updated_at->format('M d, Y H:i') }}</span>
                        </div>
                        
                        <div class="border-t border-gray-200 pt-4 mt-4">
                            <div class="flex space-x-2">
                                <a href="{{ route('account.tickets') }}" class="inline-flex items-center justify-center px-4 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none w-full">
                                    <i class="ri-arrow-left-line mr-1.5"></i> Back to Tickets
                                </a>
                            </div>
                            
                            <div class="flex space-x-2 mt-3">
                                @if($ticket->status == 'closed')
                                    <form action="{{ route('account.tickets.reopen', $ticket) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-xs font-medium text-blue-700 bg-white border border-blue-300 rounded-md shadow-sm hover:bg-blue-50 focus:outline-none w-full">
                                            <i class="ri-refresh-line mr-1.5"></i> Reopen Ticket
                                        </button>
                                    </form>
                                @elseif($ticket->status != 'closed')
                                    <form action="{{ route('account.tickets.close', $ticket) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none w-full">
                                            <i class="ri-close-circle-line mr-1.5"></i> Close Ticket
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Right Column - Messages -->
            <div class="lg:col-span-2">
                <!-- Ticket Subject -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 transition-all duration-300 hover:shadow-md mb-8" data-aos="fade-up" data-aos-delay="200">
                    <div class="card-header border-b border-gray-200 px-6 py-5 flex items-center">
                        <i class="ri-chat-1-line text-xl text-gray-500 mr-3"></i>
                        <h3 class="text-lg font-medium text-primary">{{ $ticket->subject }}</h3>
                    </div>
                    <div class="p-6">
                        <!-- Original Message -->
                        <div class="border-b border-gray-200 pb-6 mb-6">
                            <div class="flex items-start mb-4">
                                <div class="bg-gray-100 rounded-full w-10 h-10 flex items-center justify-center mr-3 flex-shrink-0">
                                    <i class="ri-user-line text-lg text-gray-500"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">{{ $ticket->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $ticket->created_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="bg-gray-50 rounded-lg p-4 ml-12">
                                <p class="text-sm text-gray-700 whitespace-pre-line">{{ $ticket->message }}</p>
                            </div>
                        </div>
                        
                        <!-- Responses -->
                        @if(isset($ticket->responses) && $ticket->responses && $ticket->responses->count() > 0)
                            @foreach($ticket->responses as $response)
                                <div class="border-b border-gray-200 pb-6 mb-6 last:border-0 last:mb-0 last:pb-0">
                                    <div class="flex items-start mb-4">
                                        <div class="bg-gray-100 rounded-full w-10 h-10 flex items-center justify-center mr-3 flex-shrink-0">
                                            <i class="ri-user-line text-lg text-gray-500"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-800">
                                                {{ $response->user->name }}
                                                @if($response->user->id != $ticket->user_id)
                                                    <span class="inline-flex ml-2 px-2 py-0.5 text-xs rounded bg-primary/10 text-primary">
                                                        Support Team
                                                    </span>
                                                @endif
                                            </p>
                                            <p class="text-xs text-gray-500">{{ $response->created_at->format('M d, Y H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-4 ml-12">
                                        <p class="text-sm text-gray-700 whitespace-pre-line">{{ $response->message }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="ri-information-line text-blue-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-700">
                                            Waiting for staff response. Our team will respond to your ticket as soon as possible.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                        
                        <!-- Reply Form (only if ticket is not closed) -->
                        @if($ticket->status != 'closed')
                            <div class="mt-8">
                                <h4 class="text-base font-medium text-gray-700 mb-4">Add Reply</h4>
                                <form action="{{ route('account.tickets.reply', $ticket) }}" method="POST">
                                    @csrf
                                    <div class="mb-4">
                                        <textarea name="message" rows="4" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary" placeholder="Type your reply here..." required></textarea>
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-primary text-white rounded-md hover:bg-primary/90 transition-all duration-300">
                                            <i class="ri-send-plane-line mr-2"></i> Send Reply
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mt-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <i class="ri-information-line text-yellow-500"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            This ticket is closed. If you need further assistance, please reopen the ticket or create a new one.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
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