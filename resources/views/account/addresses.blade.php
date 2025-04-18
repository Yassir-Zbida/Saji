<!-- resources/views/account/addresses.blade.php -->
@extends('layouts.app')

@section('title', 'My Addresses')

@section('content')
<div class="bg-gray-100 py-12 md:px-4 md:pb-28">
    <div class="container mx-auto px-4">
        <div class="mb-6">
            <nav class="text-sm text-gray-500">
                <ol class="flex items-center">
                    <li><a href="/" class="hover:text-primary">Home</a></li>
                    <li class="mx-2">/</li>
                    <li><a href="{{ route('profile.index') }}" class="hover:text-primary list-none">My Account</a></li>
                    <li class="mx-2">/</li>
                    <li class="text-primary">My Addresses</li>
                </ol>
            </nav>
        </div>

        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-primary">My Addresses</h1>
                <p class="text-gray-600 mt-2 font-jost">Manage your shipping and billing addresses.</p>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8 overflow-hidden">
            <div class="inline-flex bg-white p-1 w-full overflow-x-auto">
                <a href="{{ route('account.index') }}" class="inline-flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="ri-dashboard-line mr-3 text-gray-500"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('account.orders') }}" class="inline-flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="ri-shopping-bag-line mr-3"></i>
                    <span>My Orders</span>
                </a>
                <a href="{{ route('account.addresses') }}" class="inline-flex items-center px-6 py-4 text-primary font-medium border-b-2 border-primary transition-colors">
                    <i class="ri-map-pin-line mr-3 text-gray-500"></i>
                    <span>My Addresses</span>
                </a>
                <a href="{{ route('account.tickets') }}" class="inline-flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="ri-customer-service-2-line mr-3 text-gray-500"></i>
                    <span>Support Tickets</span>
                </a>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="inline-flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="ri-logout-box-line mr-3 text-gray-500"></i>
                    <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md" role="alert">
            <p>{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md" role="alert">
            <p>{{ session('error') }}</p>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
            <!-- Shipping Addresses -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-medium text-primary flex items-center">
                        <i class="ri-truck-line mr-2 text-gray-500"></i> Shipping Addresses
                    </h2>
                    <a href="{{ route('account.addresses.create') }}?address_type=shipping" class="text-primary hover:text-primary/70 text-sm">
                        <i class="ri-add-line mr-1"></i> Add
                    </a>
                </div>
                <div class="p-6">
                    @if(isset($shippingAddresses) && count($shippingAddresses) > 0)
                        <div class="space-y-4">
                            @foreach($shippingAddresses as $address)
                                <div class="bg-gray-50 rounded-lg p-5 border border-gray-200 relative transition-all duration-300 hover:border-gray-300 hover:shadow-sm">
                                    @if($address->is_default)
                                        <div class="absolute top-2 right-2">
                                            <span class="inline-flex px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">Default</span>
                                        </div>
                                    @endif
                                    <p class="text-sm font-medium mb-1">{{ $address->first_name }} {{ $address->last_name }}</p>
                                    @if($address->company)
                                        <p class="text-sm text-gray-500">{{ $address->company }}</p>
                                    @endif
                                    <p class="text-sm text-gray-500">{{ $address->address_line_1 }}</p>
                                    @if($address->address_line_2)
                                        <p class="text-sm text-gray-500">{{ $address->address_line_2 }}</p>
                                    @endif
                                    <p class="text-sm text-gray-500">{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                    <p class="text-sm text-gray-500">{{ $address->country }}</p>
                                    <p class="text-sm text-gray-500">{{ $address->phone }}</p>
                                    <div class="mt-4 flex space-x-2">
                                        <a href="{{ route('account.addresses.edit', $address) }}" class="inline-flex items-center justify-center px-3 py-1 text-sm border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                            <i class="ri-edit-line mr-1"></i> Edit
                                        </a>
                                        @if(!$address->is_default)
                                            <form action="{{ route('account.addresses.update', $address) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="address_type" value="{{ $address->address_type }}">
                                                <input type="hidden" name="first_name" value="{{ $address->first_name }}">
                                                <input type="hidden" name="last_name" value="{{ $address->last_name }}">
                                                <input type="hidden" name="company" value="{{ $address->company }}">
                                                <input type="hidden" name="address_line_1" value="{{ $address->address_line_1 }}">
                                                <input type="hidden" name="address_line_2" value="{{ $address->address_line_2 }}">
                                                <input type="hidden" name="city" value="{{ $address->city }}">
                                                <input type="hidden" name="state" value="{{ $address->state }}">
                                                <input type="hidden" name="postal_code" value="{{ $address->postal_code }}">
                                                <input type="hidden" name="country" value="{{ $address->country }}">
                                                <input type="hidden" name="phone" value="{{ $address->phone }}">
                                                <input type="hidden" name="email" value="{{ $address->email }}">
                                                <input type="hidden" name="is_default" value="1">
                                                <button type="submit" class="inline-flex items-center justify-center px-3 py-1 text-sm border border-primary rounded-md text-primary bg-white hover:bg-primary/5 transition-colors">
                                                    <i class="ri-check-line mr-1"></i> Set Default
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('account.addresses.destroy', $address) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this address?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center px-3 py-1 text-sm border border-red-300 rounded-md text-red-700 bg-white hover:bg-red-50 transition-colors">
                                                <i class="ri-delete-bin-line mr-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 rounded-full mb-3">
                                <i class="ri-map-pin-line text-xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-500 mb-4">You don't have any shipping addresses yet.</p>
                            <a href="{{ route('account.addresses.create') }}?address_type=shipping" class="inline-flex items-center px-4 py-2 border border-primary text-primary rounded-md hover:bg-primary hover:text-white transition-colors">
                                <i class="ri-add-line mr-2"></i> Add Shipping Address
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Billing Addresses -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                    <h2 class="text-lg font-medium text-primary flex items-center">
                        <i class="ri-bill-line mr-2 text-gray-500"></i> Billing Addresses
                    </h2>
                    <a href="{{ route('account.addresses.create') }}?address_type=billing" class="text-primary hover:text-primary/70 text-sm">
                        <i class="ri-add-line mr-1"></i> Add
                    </a>
                </div>
                <div class="p-6">
                    @if(isset($billingAddresses) && count($billingAddresses) > 0)
                        <div class="space-y-4">
                            @foreach($billingAddresses as $address)
                                <div class="bg-gray-50 rounded-lg p-5 border border-gray-200 relative transition-all duration-300 hover:border-gray-300 hover:shadow-sm">
                                    @if($address->is_default)
                                        <div class="absolute top-2 right-2">
                                            <span class="inline-flex px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded-full">Default</span>
                                        </div>
                                    @endif
                                    <p class="text-sm font-medium mb-1">{{ $address->first_name }} {{ $address->last_name }}</p>
                                    @if($address->company)
                                        <p class="text-sm text-gray-500">{{ $address->company }}</p>
                                    @endif
                                    <p class="text-sm text-gray-500">{{ $address->address_line_1 }}</p>
                                    @if($address->address_line_2)
                                        <p class="text-sm text-gray-500">{{ $address->address_line_2 }}</p>
                                    @endif
                                    <p class="text-sm text-gray-500">{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                    <p class="text-sm text-gray-500">{{ $address->country }}</p>
                                    <p class="text-sm text-gray-500">{{ $address->phone }}</p>
                                    <div class="mt-4 flex space-x-2">
                                        <a href="{{ route('account.addresses.edit', $address) }}" class="inline-flex items-center justify-center px-3 py-1 text-sm border border-gray-300 rounded-md text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                                            <i class="ri-edit-line mr-1"></i> Edit
                                        </a>
                                        @if(!$address->is_default)
                                            <form action="{{ route('account.addresses.update', $address) }}" method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="address_type" value="{{ $address->address_type }}">
                                                <input type="hidden" name="first_name" value="{{ $address->first_name }}">
                                                <input type="hidden" name="last_name" value="{{ $address->last_name }}">
                                                <input type="hidden" name="company" value="{{ $address->company }}">
                                                <input type="hidden" name="address_line_1" value="{{ $address->address_line_1 }}">
                                                <input type="hidden" name="address_line_2" value="{{ $address->address_line_2 }}">
                                                <input type="hidden" name="city" value="{{ $address->city }}">
                                                <input type="hidden" name="state" value="{{ $address->state }}">
                                                <input type="hidden" name="postal_code" value="{{ $address->postal_code }}">
                                                <input type="hidden" name="country" value="{{ $address->country }}">
                                                <input type="hidden" name="phone" value="{{ $address->phone }}">
                                                <input type="hidden" name="email" value="{{ $address->email }}">
                                                <input type="hidden" name="is_default" value="1">
                                                <button type="submit" class="inline-flex items-center justify-center px-3 py-1 text-sm border border-primary rounded-md text-primary bg-white hover:bg-primary/5 transition-colors">
                                                    <i class="ri-check-line mr-1"></i> Set Default
                                                </button>
                                            </form>
                                        @endif
                                        <form action="{{ route('account.addresses.destroy', $address) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this address?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center px-3 py-1 text-sm border border-red-300 rounded-md text-red-700 bg-white hover:bg-red-50 transition-colors">
                                                <i class="ri-delete-bin-line mr-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="inline-flex items-center justify-center w-12 h-12 bg-gray-100 rounded-full mb-3">
                                <i class="ri-bill-line text-xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-500 mb-4">You don't have any billing addresses yet.</p>
                            <a href="{{ route('account.addresses.create') }}?address_type=billing" class="inline-flex items-center px-4 py-2 border border-primary text-primary rounded-md hover:bg-primary hover:text-white transition-colors">
                                <i class="ri-add-line mr-2"></i> Add Billing Address
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection