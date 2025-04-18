@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <!-- Profile Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-jakarta font-semibold mb-2">My Addresses</h1>
            <p class="text-gray-600 font-jost">Manage your shipping and billing addresses</p>
        </div>

        <!-- Success Message -->
        @if (session('success'))
        <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-md mb-6 font-jost">
            {{ session('success') }}
        </div>
        @endif

        <!-- Error Message -->
        @if (session('error'))
        <div class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded-md mb-6 font-jost">
            {{ session('error') }}
        </div>
        @endif

        <!-- Profile Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Sidebar Navigation -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-md shadow-soft p-4">
                    <h2 class="font-jakarta font-medium text-lg mb-4 pb-2 border-b">Account Navigation</h2>
                    <nav class="space-y-1">
                        <a href="{{ route('profile.index') }}" class="block py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100 transition font-jost">
                            <i class="ri-user-line mr-2"></i> Account Details
                        </a>
                        <a href="{{ route('profile.addresses') }}" class="block py-2 px-3 rounded-md bg-black text-white font-jost">
                            <i class="ri-map-pin-line mr-2"></i> My Addresses
                        </a>
                        <a href="{{ route('profile.orders') }}" class="block py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100 transition font-jost">
                            <i class="ri-shopping-bag-line mr-2"></i> Order History
                        </a>
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100 transition font-jost">
                            <i class="ri-logout-box-line mr-2"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </nav>
                </div>
            </div>

            <!-- Main Content -->
            <div class="md:col-span-2">
                <!-- Add New Address Button -->
                <div class="flex justify-end mb-4">
                    <a href="{{ route('profile.addresses.create') }}" class="flex items-center bg-black text-white px-4 py-2 rounded-md hover:opacity-90 transition font-jost">
                        <i class="ri-add-line mr-1"></i> Add New Address
                    </a>
                </div>

                <!-- Shipping Addresses -->
                <div class="bg-white rounded-md shadow-soft p-6 mb-6">
                    <h2 class="font-jakarta font-medium text-xl mb-6">Shipping Addresses</h2>

                    @if($shippingAddresses->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($shippingAddresses as $address)
                                <div class="border rounded-md p-4 relative hover:border-black transition-all">
                                    @if($address->is_default)
                                        <span class="absolute top-2 right-2 bg-black text-white text-xs px-2 py-1 rounded-full font-jost">
                                            Default
                                        </span>
                                    @endif
                                    
                                    <div class="mb-4">
                                        <h3 class="font-medium font-jost">{{ $address->first_name }} {{ $address->last_name }}</h3>
                                        @if($address->company)
                                            <p class="text-gray-600 text-sm font-jost">{{ $address->company }}</p>
                                        @endif
                                    </div>
                                    
                                    <div class="text-sm text-gray-700 font-jost mb-4">
                                        <p>{{ $address->address_line_1 }}</p>
                                        @if($address->address_line_2)
                                            <p>{{ $address->address_line_2 }}</p>
                                        @endif
                                        <p>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                        <p>{{ $address->country }}</p>
                                        <p class="mt-1">{{ $address->phone }}</p>
                                        <p>{{ $address->email }}</p>
                                    </div>
                                    
                                    <div class="flex space-x-2 mt-2">
                                        <a href="{{ route('profile.addresses.edit', $address) }}" class="text-sm text-gray-700 hover:text-black transition font-jost">
                                            <i class="ri-edit-line"></i> Edit
                                        </a>
                                        
                                        <form action="{{ route('profile.addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this address?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-800 transition font-jost">
                                                <i class="ri-delete-bin-line"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 border rounded-md">
                            <p class="text-gray-500 font-jost mb-4">You haven't added any shipping addresses yet.</p>
                            <a href="{{ route('profile.addresses.create') }}?address_type=shipping" class="inline-block bg-black text-white px-4 py-2 rounded-md hover:opacity-90 transition font-jost">
                                Add Shipping Address
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Billing Addresses -->
                <div class="bg-white rounded-md shadow-soft p-6">
                    <h2 class="font-jakarta font-medium text-xl mb-6">Billing Addresses</h2>

                    @if($billingAddresses->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($billingAddresses as $address)
                                <div class="border rounded-md p-4 relative hover:border-black transition-all">
                                    @if($address->is_default)
                                        <span class="absolute top-2 right-2 bg-black text-white text-xs px-2 py-1 rounded-full font-jost">
                                            Default
                                        </span>
                                    @endif
                                    
                                    <div class="mb-4">
                                        <h3 class="font-medium font-jost">{{ $address->first_name }} {{ $address->last_name }}</h3>
                                        @if($address->company)
                                            <p class="text-gray-600 text-sm font-jost">{{ $address->company }}</p>
                                        @endif
                                    </div>
                                    
                                    <div class="text-sm text-gray-700 font-jost mb-4">
                                        <p>{{ $address->address_line_1 }}</p>
                                        @if($address->address_line_2)
                                            <p>{{ $address->address_line_2 }}</p>
                                        @endif
                                        <p>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                        <p>{{ $address->country }}</p>
                                        <p class="mt-1">{{ $address->phone }}</p>
                                        <p>{{ $address->email }}</p>
                                    </div>
                                    
                                    <div class="flex space-x-2 mt-2">
                                        <a href="{{ route('profile.addresses.edit', $address) }}" class="text-sm text-gray-700 hover:text-black transition font-jost">
                                            <i class="ri-edit-line"></i> Edit
                                        </a>
                                        
                                        <form action="{{ route('profile.addresses.destroy', $address) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this address?');" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-800 transition font-jost">
                                                <i class="ri-delete-bin-line"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6 border rounded-md">
                            <p class="text-gray-500 font-jost mb-4">You haven't added any billing addresses yet.</p>
                            <a href="{{ route('profile.addresses.create') }}?address_type=billing" class="inline-block bg-black text-white px-4 py-2 rounded-md hover:opacity-90 transition font-jost">
                                Add Billing Address
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection