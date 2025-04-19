@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
<div class="account-dashboard py-12 bg-gray-100 md:px-4 md:pb-28">
    <div class="container mx-auto px-4">
        <div class="mb-8">
            <div class="text-sm text-gray-500 mb-2 flex items-center">
                <a href="/" class="hover:text-primary">Home</a>
                <span class="mx-2">/</span>
                <a href="{{ route('profile.index') }}" class="hover:text-primary">My Account</a>
                <span class="mx-2">/</span>
                <span>Edit Profile</span>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-primary">Edit Your Profile</h1>
            <p class="text-gray-600 mt-2 font-jost">Update your personal information and change your password.</p>
        </div>

        <!-- Tab Navigation -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8 overflow-hidden">
            <div class="flex flex-wrap overflow-x-auto">
                <a href="{{ route('profile.index') }}" class="inline-flex items-center px-6 py-4 text-primary font-medium bg-gray-50 border-b-2 border-primary">
                    <i class="ri-dashboard-line mr-3"></i>
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
                <a href="{{ route('account.tickets') }}" class="inline-flex items-center px-6 py-4 text-gray-700 hover:bg-gray-50 transition-colors">
                    <i class="ri-customer-service-2-line mr-3 text-gray-500"></i>
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

        <!-- User Info Summary -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 transition-all duration-300 hover:shadow-md mb-8" data-aos="fade-up" data-aos-delay="100">
            <div class="p-6 md:p-8">
                <div class="flex flex-wrap md:flex-nowrap items-center gap-6">
                    <div class="user-icon bg-gray-100 rounded-full w-20 h-20 flex items-center justify-center">
                        <i class="ri-user-line text-3xl text-gray-500"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-primary">{{ $user->name }}</h2>
                        <p class="text-gray-500 mt-1 mb-1"><i class="ri-mail-line mr-2"></i>{{ $user->email }}</p>
                        <p class="text-gray-500"><i class="ri-calendar-line mr-2"></i>Member since {{ $user->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
            
        <!-- Profile Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 transition-all duration-300 hover:shadow-md mb-8" data-aos="fade-up" data-aos-delay="200">
            <div class="card-header border-b border-gray-200 px-6 py-5 flex items-center">
                <i class="ri-profile-line text-xl text-gray-500 mr-3"></i>
                <h3 class="text-lg font-medium text-primary">Personal Information</h3>
            </div>
            
            <div class="p-6 md:p-8">
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="ri-error-warning-line text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                                <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('account.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                            <input type="text" name="name" id="name" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary" value="{{ old('name', $user->name) }}" required>
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address <span class="text-red-500">*</span></label>
                            <input type="email" name="email" id="email" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary" value="{{ old('email', $user->email) }}" required>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-6 mt-6">
                        <h4 class="text-base font-medium text-gray-700 mb-4">Change Password (Optional)</h4>
                        <p class="text-sm text-gray-500 mb-4">Leave these fields empty if you don't want to change your password.</p>
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
                                <input type="password" name="current_password" id="current_password" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary">
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
                                    <input type="password" name="password" id="password" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary">
                                </div>
                                
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full rounded-lg border border-gray-300 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex justify-end pt-4">
                        <div class="flex space-x-3">
                            <a href="{{ route('profile.index') }}" class="inline-flex items-center px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-all duration-300">
                                <i class="ri-arrow-left-line mr-2"></i> Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-primary text-white rounded-lg hover:bg-primary/90 transition-all duration-300">
                                <i class="ri-save-line mr-2"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Password Security Tips -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 transition-all duration-300 hover:shadow-md" data-aos="fade-up" data-aos-delay="300">
            <div class="card-header border-b border-gray-200 px-6 py-5 flex items-center">
                <i class="ri-shield-keyhole-line text-xl text-gray-500 mr-3"></i>
                <h3 class="text-lg font-medium text-primary">Password Security Tips</h3>
            </div>
            
            <div class="p-6 md:p-8">
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-lg p-5 border-gray-200 border">
                        <h4 class="text-primary font-medium mb-3 flex items-center">
                            <i class="ri-information-line mr-2"></i>
                            Password Recommendations
                        </h4>
                        <ul class="text-gray-600 space-y-2">
                            <li class="flex items-start">
                                <i class="ri-check-line text-green-500 mt-1 mr-2"></i>
                                <span>Use at least 8 characters</span>
                            </li>
                            <li class="flex items-start">
                                <i class="ri-check-line text-green-500 mt-1 mr-2"></i>
                                <span>Include a mix of uppercase and lowercase letters</span>
                            </li>
                            <li class="flex items-start">
                                <i class="ri-check-line text-green-500 mt-1 mr-2"></i>
                                <span>Include at least one number and one special character</span>
                            </li>
                            <li class="flex items-start">
                                <i class="ri-check-line text-green-500 mt-1 mr-2"></i>
                                <span>Avoid using personal information or common words</span>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="bg-gray-50 rounded-lg p-5 border-gray-200 border">
                        <h4 class="text-primary font-medium mb-3 flex items-center">
                            <i class="ri-error-warning-line mr-2"></i>
                            Security Best Practices
                        </h4>
                        <ul class="text-gray-600 space-y-2">
                            <li class="flex items-start">
                                <i class="ri-check-line text-green-500 mt-1 mr-2"></i>
                                <span>Use a unique password for each of your accounts</span>
                            </li>
                            <li class="flex items-start">
                                <i class="ri-check-line text-green-500 mt-1 mr-2"></i>
                                <span>Change your passwords regularly</span>
                            </li>
                            <li class="flex items-start">
                                <i class="ri-check-line text-green-500 mt-1 mr-2"></i>
                                <span>Never share your password with anyone</span>
                            </li>
                            <li class="flex items-start">
                                <i class="ri-check-line text-green-500 mt-1 mr-2"></i>
                                <span>Consider using a password manager for better security</span>
                            </li>
                        </ul>
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