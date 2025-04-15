@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-8">
            <h2 class="text-2xl font-bold text-center mb-6">Reset Password</h2>
            
            @if (session('status'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline">{{ session('status') }}</span>
                </div>
            @endif
            
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                
                <div class="mb-6">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center justify-center">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                        Send Password Reset Link
                    </button>
                </div>
            </form>
        </div>
        
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="text-center">
                <p class="text-sm text-gray-600">Remember your password? <a href="{{ route('login') }}" class="text-blue-500 hover:text-blue-700 font-bold">Login</a></p>
            </div>
        </div>
    </div>
</div>
@endsection