@extends('layouts.auth')
@section('title', 'Login | Saji Home')

@section('content')
<div class="flex min-h-screen items-center justify-center bg-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-5xl">
        <div class="overflow-hidden bg-white rounded-lg shadow-medium flex">
            <div class="w-full md:w-1/2 p-8">
                <div class="mb-10">
                    <h2 class="text-2xl font-jakarta font-semibold text-primary mb-2">Welcome back</h2>
                    <p class="text-sm text-gray-500 font-jakarta">Login to your SAJI HOME account</p>
                </div>
                
                @if ($errors->any())
                <div class="mt-4 text-center">
                    <div class="text-red-500 text-sm py-2 px-4 bg-red-50 rounded-lg inline-block">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
                @endif
                
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
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
                        <div class="flex items-center justify-between mb-1">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <a href="{{ route('password.request') }}" class="text-xs font-medium text-primary hover:underline">
                                Forgot your password?
                            </a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ri-lock-line text-gray-400"></i>
                            </div>
                            <input id="password" name="password" type="password" required 
                                class="block w-full pl-10 pr-10 appearance-none rounded-lg border border-gray-200 px-3 py-2.5 placeholder-gray-400 focus:border-primary focus:outline-none sm:text-sm"
                                placeholder="••••••••">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" id="togglePassword" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <i class="ri-eye-off-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div>
                    
                    <div>
                        <button type="submit" 
                                class="w-full bg-primary text-white rounded-lg py-2.5 font-medium transition hover:bg-opacity-90">
                            Sign in
                        </button>
                    </div>
                </form>
                
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="font-medium text-primary hover:underline">
                            Register now
                        </a>
                    </p>
                </div>
                
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs font-medium">
                            <span class="bg-white px-4 text-gray-500">Or continue with</span>
                        </div>
                    </div>
                    
                    <div class="mt-6 grid grid-cols-3 gap-3">
                        <a href="#" class="flex items-center justify-center rounded-lg border border-gray-200 px-4 py-2 hover:bg-gray-50">
                            <i class="ri-apple-fill text-xl"></i>
                        </a>
                        <a href="#" class="flex items-center justify-center rounded-lg border border-gray-200 px-4 py-2 hover:bg-gray-50">
                            <i class="ri-google-fill text-xl"></i>
                        </a>
                        <a href="#" class="flex items-center justify-center rounded-lg border border-gray-200 px-4 py-2 hover:bg-gray-50">
                            <i class="ri-facebook-fill text-xl"></i>
                        </a>
                    </div>
                </div>
                
                <div class="mt-10 text-center">
                    <p class="text-xs text-gray-500">
                        By signing in, you agree to our 
                        <a href="#" class="text-primary hover:underline">Terms of Service</a> and 
                        <a href="#" class="text-primary hover:underline">Privacy Policy</a>
                    </p>
                </div>
            </div>
            
            <div class="hidden md:block md:w-1/2 bg-gray-100">
                <img src="{{ asset('images/login.webp') }}" alt="SAJI HOME Furniture" class="w-full h-full object-cover">
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('ri-eye-line');
        this.querySelector('i').classList.toggle('ri-eye-off-line');
    });
    
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    
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
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        
        if (password === '') {
            this.className = 'block w-full pl-10 pr-10 appearance-none rounded-lg border border-gray-200 px-3 py-2.5 placeholder-gray-400 focus:border-primary focus:outline-none sm:text-sm';
        } else if (password.length < 8) {
            this.className = 'block w-full pl-10 pr-10 appearance-none rounded-lg border border-red-300 px-3 py-2.5 placeholder-gray-400 focus:border-red-500 focus:outline-none sm:text-sm';
        } else {
            this.className = 'block w-full pl-10 pr-10 appearance-none rounded-lg border border-green-300 px-3 py-2.5 placeholder-gray-400 focus:border-green-500 focus:outline-none sm:text-sm';
        }
    });
});
</script>
@endsection