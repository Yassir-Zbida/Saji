<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('title', 'Login')

@section('styles')
<style>
    .auth-container {
        padding: 5rem 0;
    }
    
    .auth-box {
        max-width: 500px;
        margin: 0 auto;
        background: var(--white);
        border: 1px solid var(--gray-200);
        padding: 2.5rem;
    }
    
    .auth-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    
    .auth-subtitle {
        text-align: center;
        margin-bottom: 2rem;
        color: var(--gray-600);
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .form-control {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--gray-300);
        transition: border-color 0.3s ease;
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--gray-800);
    }
    
    .form-check {
        display: flex;
        align-items: center;
    }
    
    .form-check-input {
        margin-right: 0.5rem;
    }
    
    .form-check-label {
        font-size: 0.875rem;
    }
    
    .auth-footer {
        margin-top: 2rem;
        text-align: center;
        font-size: 0.875rem;
    }
    
    .auth-footer a {
        color: var(--gray-800);
        text-decoration: underline;
    }
    
    .auth-divider {
        display: flex;
        align-items: center;
        margin: 2rem 0;
    }
    
    .auth-divider::before,
    .auth-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: var(--gray-200);
    }
    
    .auth-divider-text {
        padding: 0 1rem;
        color: var(--gray-500);
        font-size: 0.875rem;
    }
    
    .social-login {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    
    .social-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0.75rem;
        border: 1px solid var(--gray-300);
        background: var(--white);
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .social-btn:hover {
        background: var(--gray-100);
    }
    
    .social-icon {
        margin-right: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="auth-container">
        <div class="auth-box">
            <h1 class="auth-title">Login</h1>
            <p class="auth-subtitle">Welcome back! Please login to your account.</p>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    
                    @error('email')
                        <span class="invalid-feedback" role="alert" style="color: red; font-size: 0.875rem; margin-top: 0.25rem;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                    
                    @error('password')
                        <span class="invalid-feedback" role="alert" style="color: red; font-size: 0.875rem; margin-top: 0.25rem;">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
                
                <div class="form-group" style="display: flex; justify-content: space-between; align-items: center;">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>
                    
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="font-size: 0.875rem;">Forgot Password?</a>
                    @endif
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
            </form>
            
            <div class="auth-divider">
                <span class="auth-divider-text">Or login with</span>
            </div>
            
            <div class="social-login">
                <a href="{{ route('login.google') }}" class="social-btn">
                    <i class="ri-google-fill social-icon"></i> Google
                </a>
                <a href="{{ route('login.facebook') }}" class="social-btn">
                    <i class="ri-facebook-fill social-icon"></i> Facebook
                </a>
            </div>
            
            <div class="auth-footer">
                Don't have an account? <a href="{{ route('register') }}">Register</a>
            </div>
        </div>
    </div>
</div>
@endsection