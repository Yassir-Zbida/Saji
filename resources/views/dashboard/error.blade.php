@extends('layouts.admin')

@section('title', 'Dashboard Error')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white rounded-lg border border-red-200 shadow-sm p-6 max-w-2xl mx-auto">
            <div class="flex items-center mb-4">
                <div class="bg-red-100 rounded-full p-3 mr-4">
                    <i class="ri-error-warning-line text-red-500 text-xl"></i>
                </div>
                <h1 class="text-xl font-semibold text-gray-900">Dashboard Error</h1>
            </div>
            
            <div class="bg-red-50 border border-red-100 rounded-md p-4 mb-6">
                <p class="text-red-800">{{ $error }}</p>
            </div>
            
            <p class="text-gray-600 mb-6">There was an error loading the dashboard data. This could be due to:</p>
            
            <ul class="list-disc pl-5 mb-6 text-gray-600 space-y-2">
                <li>Database connection issues</li>
                <li>Missing tables or columns</li>
                <li>Permission problems</li>
                <li>Server configuration issues</li>
            </ul>
            
            <p class="text-gray-600 mb-6">Please check the Laravel error logs for more details.</p>
            
            <div class="flex justify-between">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <i class="ri-refresh-line mr-2"></i>
                    Retry Loading Dashboard
                </a>
                
                <button onclick="window.location.href='{{ url()->previous() }}'" class="inline-flex items-center px-4 py-2 bg-primary border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <i class="ri-arrow-left-line mr-2"></i>
                    Go Back
                </button>
            </div>
        </div>
    </div>
@endsection