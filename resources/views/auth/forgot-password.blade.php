@extends('layouts.auth')
@section('title', 'Forget password | Saji Home')


@section('content')
<div class="flex min-h-screen items-center justify-center bg-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-5xl">
        <!-- Auth Card -->
        <div class="overflow-hidden bg-white rounded-lg shadow-medium flex">
            <!-- Form Side -->
            <div class="w-full md:w-1/2 p-8">
                <div class="mb-10">
                    <h2 class="text-2xl font-jakarta font-semibold text-primary mb-2">Forgot Password</h2>
                    <p class="text-sm text-gray-500 font-jakarta">Enter your email to receive a password reset link</p>
                </div>
                
                @if (session('status'))
                <div class="mt-4 text-center">
                    <div class="text-green-500 text-sm py-2 px-4 bg-green-50 rounded-lg inline-block">
                        {{ session('status') }}
                    </div>
                </div>
                @endif
                
                @if ($errors->any())
                <div class="mt-4 text-center">
                    <div class="text-red-500 text-sm py-2 px-4 bg-red-50 rounded-lg inline-block">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ri-mail-line text-gray-400"></i>
                            </div>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required 
                                class="block w-full pl-10 appearance-none rounded-lg border border-gray-200 px-3 py-2.5 placeholder-gray-400 focus:border-primary focus:outline-none sm:text-sm"
                                placeholder="name@example.com">
                        </div>
                    </div>
                    
                    <div>
                        <button type="submit" 
                                class="w-full bg-primary text-white rounded-lg py-2.5 font-medium transition hover:bg-opacity-90">
                            Send Reset Link
                        </button>
                    </div>
                </form>
                
                <!-- Back to Login Link -->
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600">
                        Remembered your password? 
                        <a href="{{ route('login') }}" class="font-medium text-primary hover:underline">
                            Back to login
                        </a>
                    </p>
                </div>
                
                <!-- Footer -->
                <div class="mt-10 text-center">
                    <p class="text-xs text-gray-500">
                        Need help? Contact 
                        <a href="/support" class="text-primary hover:underline">SAJI HOME Support</a>
                    </p>
                </div>
            </div>
            
            <!-- Image Side -->
            <div class="hidden md:block md:w-1/2 bg-gray-100">
                <img src="{{ asset('images/login.webp') }}" alt="SAJI HOME Furniture" class="w-full h-full object-cover">
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    
    // Validation patterns
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    
    // Real-time validation
    emailInput.addEventListener('input', function() {
        const email = this.value.trim();
        
        if (email === '') {
            this.className = 'block w-full pl-10 appearance-none rounded-lg border border-gray-200 px-3 py-2.5 placeholder-gray-400 focus:border-primary focus:outline-none sm:text-sm';
        } else if (!emailPattern.test(email)) {
            this.className = 'block w-full pl-10 appearance-none rounded-lg border border-red-300 px-3 py-2.5 placeholder-gray-400 focus:border-red-500 focus:outline-none sm:text-sm';
        } else {
            this.className = 'block w-full pl-10 appearance-none rounded-lg border border-green-300 px-3 py-2.5 placeholder-gray-400 focus:border-green-500 focus:outline-none sm:text-sm';
        }
    });
});
</script>
@endsection