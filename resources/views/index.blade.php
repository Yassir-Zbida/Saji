<!-- resources/views/account/index.blade.php -->
@extends('layouts.app')

@section('title', 'My Account')

@section('styles')
<style>
    .account-header {
        padding: 3rem 0;
        background-color: var(--gray-100);
        text-align: center;
    }
    
    .account-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    .account-container {
        padding: 3rem 0;
    }
    
    .account-content {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 2rem;
    }
    
    @media (max-width: 768px) {
        .account-content {
            grid-template-columns: 1fr;
        }
    }
    
    .account-sidebar {
        background: var(--white);
        border: 1px solid var(--gray-200);
    }
    
    .account-nav {
        list-style: none;
    }
    
    .account-nav-item {
        border-bottom: 1px solid var(--gray-200);
    }
    
    .account-nav-link {
        display: block;
        padding: 1rem 1.5rem;
        font-size: 0.875rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .account-nav-link:hover {
        background: var(--gray-100);
    }
    
    .account-nav-link.active {
        background: var(--gray-800);
        color: var(--white);
    }
    
    .account-nav-icon {
        margin-right: 0.5rem;
    }
    
    .account-main {
        background: var(--white);
        border: 1px solid var(--gray-200);
        padding: 2rem;
    }
    
    .account-section-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .account-welcome {
        margin-bottom: 2rem;
    }
    
    .account-welcome-name {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }
    
    .account-welcome-text {
        color: var(--gray-600);
    }
    
    .account-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    @media (max-width: 576px) {
        .account-stats {
            grid-template-columns: 1fr;
        }
    }
    
    .account-stat {
        background: var(--gray-100);
        padding: 1.5rem;
        text-align: center;
    }
    
    .account-stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .account-stat-label {
        font-size: 0.875rem;
        color: var(--gray-600);
    }
    
    .recent-orders {
        margin-bottom: 2rem;
    }
    
    .order-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .order-table th,
    .order-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--gray-200);
    }
    
    .order-table th {
        font-weight: 600;
        color: var(--gray-700);
    }
    
    .order-status {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 0.25rem;
    }
    
    .order-status-pending {
        background: var(--gray-200);
        color: var(--gray-800);
    }
    
    .order-status-processing {
        background: #FEF3C7;
        color: #92400E;
    }
    
    .order-status-completed {
        background: #D1FAE5;
        color: #065F46;
    }
    
    .order-status-cancelled {
        background: #FEE2E2;
        color: #B91C1C;
    }
    
    .account-form {
        max-width: 600px;
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
    
    .address-card {
        border: 1px solid var(--gray-200);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .address-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .address-card-title {
        font-size: 1rem;
        font-weight: 600;
    }
    
    .address-card-actions {
        display: flex;
        gap: 0.5rem;
    }
    
    .address-card-action {
        background: transparent;
        border: none;
        color: var(--gray-600);
        cursor: pointer;
        transition: color 0.3s ease;
    }
    
    .address-card-action:hover {
        color: var(--black);
    }
    
    .address-card-content {
        font-size: 0.875rem;
        color: var(--gray-600);
    }
    
    .address-card-default {
        display: inline-block;
        margin-top: 0.5rem;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        background: var(--gray-200);
        color: var(--gray-800);
    }
</style>
@endsection

@section('content')
<div class="account-header">
    <div class="container">
        <h1 class="account-title">My Account</h1>
    </div>
</div>

<div class="container">
    <div class="account-container">
        <div class="account-content">
            <aside class="account-sidebar">
                <ul class="account-nav">
                    <li class="account-nav-item">
                        <a href="{{ route('account') }}" class="account-nav-link active">
                            <i class="ri-dashboard-line account-nav-icon"></i> Dashboard
                        </a>
                    </li>
                    <li class="account-nav-item">
                        <a href="{{ route('account.orders') }}" class="account-nav-link">
                            <i class="ri-shopping-bag-line account-nav-icon"></i> Orders
                        </a>
                    </li>
                    <li class="account-nav-item">
                        <a href="{{ route('account.addresses') }}" class="account-nav-link">
                            <i class="ri-map-pin-line account-nav-icon"></i> Addresses
                        </a>
                    </li>
                    <li class="account-nav-item">
                        <a href="{{ route('account.wishlist') }}" class="account-nav-link">
                            <i class="ri-heart-line account-nav-icon"></i> Wishlist
                        </a>
                    </li>
                    <li class="account-nav-item">
                        <a href="{{ route('account.profile') }}" class="account-nav-link">
                            <i class="ri-user-line account-nav-icon"></i> Account Details
                        </a>
                    </li>
                    <li class="account-nav-item">
                        <a href="{{ route('logout') }}" class="account-nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="ri-logout-box-line account-nav-icon"></i> Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </aside>
            
            <div class="account-main">
                <div class="account-welcome">
                    <h2 class="account-welcome-name">Hello, {{ auth()->user()->name }}!</h2>
                    <p class="account-welcome-text">From your account dashboard you can view your recent orders, manage your shipping and billing addresses, and edit your password and account details.</p>
                </div>
                
                <div class="account-stats">
                    <div class="account-stat">
                        <div class="account-stat-value">{{ $orderCount }}</div>
                        <div class="account-stat-label">Orders</div>
                    </div>
                    
                    <div class="account-stat">
                        <div class="account-stat-value">${{ number_format($totalSpent, 2) }}</div>
                        <div class="account-stat-label">Total Spent</div>
                    </div>
                    
                    <div class="account-stat">
                        <div class="account-stat-value">{{ $wishlistCount }}</div>
                        <div class="account-stat-label">Wishlist Items</div>
                    </div>
                </div>
                
                <div class="recent-orders">
                    <h3 class="account-section-title">Recent Orders</h3>
                    
                    @if(count($recentOrders) > 0)
                        <table class="order-table">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentOrders as $order)
                                <tr>
                                    <td>#{{ $order->order_number }}</td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="order-status order-status-{{ $order->status }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                    <td>${{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <a href="{{ route('account.orders.show', $order->id) }}" class="btn">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                        <div style="margin-top: 1.5rem; text-align: right;">
                            <a href="{{ route('account.orders') }}" class="btn">View All Orders</a>
                        </div>
                    @else
                        <p>You haven't placed any orders yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection