@extends('layouts.auth')
@section('title', 'Sign in | Saji Home')


@section('content')
<div class="flex min-h-screen items-center justify-center bg-white py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-5xl">
        <div class="overflow-hidden bg-white rounded-lg shadow-medium flex">
            <div class="w-full md:w-1/2 p-8">
                <div class="mb-10">
                    <h2 class="text-2xl font-jakarta font-semibold text-primary mb-2">Create an account</h2>
                    <p class="text-sm text-gray-500 font-jakarta">Join SAJI HOME and discover beautiful furniture</p>
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
                
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ri-user-line text-gray-400"></i>
                            </div>
                            <input id="name" name="name" type="text" value="{{ old('name') }}" required 
                                class="block w-full pl-10 appearance-none rounded-lg border border-gray-200 px-3 py-2.5 placeholder-gray-400 focus:border-primary focus:outline-none sm:text-sm"
                                placeholder="John Doe">
                        </div>
                    </div>
                    
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
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
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
                        <div id="password-strength-meter" class="mt-1">
                            <div class="flex items-center">
                                <div class="flex-1 h-1 bg-gray-200 rounded-full overflow-hidden">
                                    <div id="password-strength-bar" class="h-full w-0 transition-all duration-300"></div>
                                </div>
                                <span id="password-strength-text" class="ml-2 text-xs"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ri-lock-line text-gray-400"></i>
                            </div>
                            <input id="password_confirmation" name="password_confirmation" type="password" required 
                                class="block w-full pl-10 pr-10 appearance-none rounded-lg border border-gray-200 px-3 py-2.5 placeholder-gray-400 focus:border-primary focus:outline-none sm:text-sm"
                                placeholder="••••••••">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" id="toggleConfirmPassword" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <i class="ri-eye-off-line"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <button type="submit" 
                                class="w-full bg-primary text-white rounded-lg py-2.5 font-medium transition hover:bg-opacity-90">
                            Create Account
                        </button>
                    </div>
                </form>
                
                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-600">
                        Already have an account? 
                        <a href="{{ route('login') }}" class="font-medium text-primary hover:underline">
                            Sign in
                        </a>
                    </p>
                </div>
                
                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-xs font-medium">
                            <span class="bg-white px-4 text-gray-500">Or register with</span>
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
                        By creating an account, you agree to our 
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
    const nameInput = document.getElementById('name');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const strengthBar = document.getElementById('password-strength-bar');
    const strengthText = document.getElementById('password-strength-text');
    const togglePassword = document.getElementById('togglePassword');
    const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
    
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('ri-eye-line');
        this.querySelector('i').classList.toggle('ri-eye-off-line');
    });
    
    toggleConfirmPassword.addEventListener('click', function() {
        const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordConfirmInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('ri-eye-line');
        this.querySelector('i').classList.toggle('ri-eye-off-line');
    });
    
    const namePattern = /^[a-zA-Z\s]{2,}$/;
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    
    const calculatePasswordStrength = (password) => {
        const hasMinLength = password.length >= 8;
        const hasUppercase = /[A-Z]/.test(password);
        const hasLowercase = /[a-z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        const hasSpecial = /[^A-Za-z0-9]/.test(password);
        
        let score = 0;
        if (hasMinLength) score++;
        if (hasUppercase && hasLowercase) score++;
        if (hasNumber) score++;
        if (hasSpecial) score++;
        
        const strengthLabels = ['Very weak', 'Weak', 'Medium', 'Strong', 'Very strong'];
        const strengthColors = ['bg-red-500', 'bg-orange-500', 'bg-yellow-500', 'bg-green-400', 'bg-green-600'];
        const textColors = ['text-red-500', 'text-orange-500', 'text-yellow-500', 'text-green-500', 'text-green-600'];
        
        return {
            score,
            label: strengthLabels[score],
            color: strengthColors[score],
            textColor: textColors[score],
            percentage: (score + 1) * 20
        };
    };
    
    const updateStrengthMeter = (strength) => {
        if (!strength) {
            strengthBar.style.width = '0%';
            strengthBar.className = 'h-full';
            strengthText.textContent = '';
            strengthText.className = 'ml-2 text-xs';
            return;
        }
        
        strengthBar.style.width = `${strength.percentage}%`;
        strengthBar.className = `h-full ${strength.color}`;
        strengthText.textContent = strength.label;
        strengthText.className = `ml-2 text-xs ${strength.textColor}`;
    };
    
    // Real-time validation
    nameInput.addEventListener('input', function() {
        const name = this.value.trim();
        
        if (name === '') {
            this.className = 'block w-full pl-10 appearance-none rounded-lg border border-gray-200 px-3 py-2.5 placeholder-gray-400 focus:border-primary focus:outline-none sm:text-sm';
        } else if (name.length < 2 || !namePattern.test(name)) {
            this.className = 'block w-full pl-10 appearance-none rounded-lg border border-red-300 px-3 py-2.5 placeholder-gray-400 focus:border-red-500 focus:outline-none sm:text-sm';
        } else {
            this.className = 'block w-full pl-10 appearance-none rounded-lg border border-green-300 px-3 py-2.5 placeholder-gray-400 focus:border-green-500 focus:outline-none sm:text-sm';
        }
    });
    
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
            updateStrengthMeter(null);
        } else {
            const strength = calculatePasswordStrength(password);
            updateStrengthMeter(strength);
            
            if (strength.score < 2) {
                this.className = 'block w-full pl-10 pr-10 appearance-none rounded-lg border border-red-300 px-3 py-2.5 placeholder-gray-400 focus:border-red-500 focus:outline-none sm:text-sm';
            } else if (strength.score < 3) {
                this.className = 'block w-full pl-10 pr-10 appearance-none rounded-lg border border-yellow-300 px-3 py-2.5 placeholder-gray-400 focus:border-yellow-500 focus:outline-none sm:text-sm';
            } else {
                this.className = 'block w-full pl-10 pr-10 appearance-none rounded-lg border border-green-300 px-3 py-2.5 placeholder-gray-400 focus:border-green-500 focus:outline-none sm:text-sm';
            }
        }
        
        if (passwordConfirmInput.value !== '') {
            passwordConfirmInput.dispatchEvent(new Event('input'));
        }
    });
    
    passwordConfirmInput.addEventListener('input', function() {
        const passwordConfirm = this.value;
        
        if (passwordConfirm === '') {
            this.className = 'block w-full pl-10 pr-10 appearance-none rounded-lg border border-gray-200 px-3 py-2.5 placeholder-gray-400 focus:border-primary focus:outline-none sm:text-sm';
        } else if (passwordConfirm !== passwordInput.value) {
            this.className = 'block w-full pl-10 pr-10 appearance-none rounded-lg border border-red-300 px-3 py-2.5 placeholder-gray-400 focus:border-red-500 focus:outline-none sm:text-sm';
        } else {
            this.className = 'block w-full pl-10 pr-10 appearance-none rounded-lg border border-green-300 px-3 py-2.5 placeholder-gray-400 focus:border-green-500 focus:outline-none sm:text-sm';
        }
    });
});
</script>
@endsection