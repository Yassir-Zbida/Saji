@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-5xl mx-auto">
        <!-- Profile Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-jakarta font-semibold mb-2">Edit Profile</h1>
            <p class="text-gray-600 font-jost">Update your account information</p>
        </div>

        <!-- Profile Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Sidebar Navigation -->
            <div class="md:col-span-1">
                <div class="bg-white rounded-md shadow-soft p-4">
                    <h2 class="font-jakarta font-medium text-lg mb-4 pb-2 border-b">Account Navigation</h2>
                    <nav class="space-y-1">
                        <a href="{{ route('profile.index') }}" class="block py-2 px-3 rounded-md bg-black text-white font-jost">
                            <i class="ri-user-line mr-2"></i> Account Details
                        </a>
                        <a href="{{ route('profile.addresses') }}" class="block py-2 px-3 rounded-md text-gray-700 hover:bg-gray-100 transition font-jost">
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
                <div class="bg-white rounded-md shadow-soft p-6">
                    <h2 class="font-jakarta font-medium text-xl mb-6">Update Account Information</h2>

                    <form action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Name Field -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-1 font-jost">Name</label>
                                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-md focus:outline-none focus:border-gray-900 bg-transparent transition-all text-sm font-jost @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 font-jost">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email Field -->
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1 font-jost">Email Address</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                                    class="w-full px-4 py-3 border border-gray-200 rounded-md focus:outline-none focus:border-gray-900 bg-transparent transition-all text-sm font-jost @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 font-jost">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Password Change Section -->
                            <div class="pt-4 border-t">
                                <h3 class="font-medium font-jakarta mb-4">Change Password (optional)</h3>

                                <!-- Current Password -->
                                <div class="mb-4">
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1 font-jost">Current Password</label>
                                    <input type="password" name="current_password" id="current_password" 
                                        class="w-full px-4 py-3 border border-gray-200 rounded-md focus:outline-none focus:border-gray-900 bg-transparent transition-all text-sm font-jost @error('current_password') border-red-500 @enderror">
                                    @error('current_password')
                                        <p class="mt-1 text-sm text-red-600 font-jost">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- New Password -->
                                <div class="mb-4">
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1 font-jost">New Password</label>
                                    <input type="password" name="password" id="password" 
                                        class="w-full px-4 py-3 border border-gray-200 rounded-md focus:outline-none focus:border-gray-900 bg-transparent transition-all text-sm font-jost @error('password') border-red-500 @enderror">
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600 font-jost">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1 font-jost">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" 
                                        class="w-full px-4 py-3 border border-gray-200 rounded-md focus:outline-none focus:border-gray-900 bg-transparent transition-all text-sm font-jost">
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-4">
                                <a href="{{ route('profile.index') }}" class="font-jost text-gray-600 hover:text-black transition">
                                    Cancel
                                </a>
                                <button type="submit" class="bg-black text-white px-6 py-2 rounded-md hover:opacity-90 transition font-jost">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection