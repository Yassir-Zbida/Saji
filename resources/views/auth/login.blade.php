@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-8">
            <h2 class="text-2xl font-bold text-center mb-6">Login</h2>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}
                               class="form-checkbox h-4 w-4 text-blue-600">
                        <span class="ml-2 text-sm text-gray-600">Remember Me</span>
                    </label>
                </div>
                
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Login
                    </button>
                    
                    @if (Route::has('password.request'))
                        <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="{{ route('password.request') }}">
                            Forgot Password?
                        </a>
                    @endif
                </div>
            </form>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="text-center">
                <p class="text-sm text-gray-600">Don't have an account? <a href="{{ route('register') }}" class="text-blue-500 hover:text-blue-700 font-bold">Register</a></p>
            </div>
        </div>
    </div>
</div>
@endsection