<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    
    public function index()
    {
        $totalUsers = User::where('role', 'client')->count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalSales = Order::where('status', 'completed')->sum('total_amount');
        
        $lowStockProducts = Product::where('quantity', '<=', 'stock_alert_threshold')->take(5)->get();
        
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        
        $pendingTickets = SupportTicket::whereIn('status', ['open', 'in_progress'])->count();
        
        $monthlySales = Order::where('status', 'completed')
            ->whereBetween('created_at', [now()->startOfYear(), now()->endOfYear()])
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->groupBy('month')
            ->get();
            
        return view('admin.dashboard', compact(
            'totalUsers', 
            'totalProducts', 
            'totalOrders', 
            'totalSales', 
            'lowStockProducts', 
            'recentOrders', 
            'pendingTickets', 
            'monthlySales'
        ));
    }
}
