<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        // Get counts for dashboard cards
        $totalProducts = Product::count();
        $lowStockProducts = Product::whereRaw('stock_quantity <= stock_alert_threshold')->count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $openTickets = SupportTicket::whereIn('status', ['open', 'in_progress'])->count();
        
        // Get recent orders
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Get recent tickets
        $recentTickets = SupportTicket::with('user')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Get sales data for chart
        $salesData = $this->getSalesData();
        
        return view('dashboard.index', compact(
            'totalProducts', 
            'lowStockProducts', 
            'totalOrders', 
            'pendingOrders', 
            'totalCustomers', 
            'openTickets', 
            'recentOrders', 
            'recentTickets',
            'salesData'
        ));
    }
    
    /**
     * Get sales data for the chart.
     */
    private function getSalesData()
    {
        // Get sales for the last 30 days
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();
        
        $dailySales = Order::where('created_at', '>=', $startDate)
            ->where('status', '!=', 'cancelled')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Format data for chart
        $labels = [];
        $data = [];
        
        // Create array with all dates in range
        $period = new \DatePeriod(
            new \DateTime($startDate->format('Y-m-d')),
            new \DateInterval('P1D'),
            new \DateTime($endDate->format('Y-m-d'))
        );
        
        foreach ($period as $date) {
            $dateString = $date->format('Y-m-d');
            $labels[] = $date->format('M d');
            
            // Find sales for this date
            $sale = $dailySales->firstWhere('date', $dateString);
            $data[] = $sale ? $sale->total : 0;
        }
        
        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }
    
    /**
     * Display the analytics page.
     */
    public function analytics()
    {
        // Get sales data by month for the current year
        $currentYear = Carbon::now()->year;
        
        $monthlySales = Order::whereYear('created_at', $currentYear)
            ->where('status', '!=', 'cancelled')
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        // Format data for chart
        $monthlyLabels = [];
        $monthlyData = [];
        
        for ($month = 1; $month <= 12; $month++) {
            $monthlyLabels[] = Carbon::create($currentYear, $month, 1)->format('F');
            
            // Find sales for this month
            $sale = $monthlySales->firstWhere('month', $month);
            $monthlyData[] = $sale ? $sale->total : 0;
        }
        
        // Get top selling products
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_sales')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->take(10)
            ->get();
            
        // Get sales by category
        $salesByCategory = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.id',
                'categories.name',
                DB::raw('SUM(order_items.quantity * order_items.price) as total_sales')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_sales', 'desc')
            ->get();
            
        return view('dashboard.analytics', compact(
            'monthlyLabels',
            'monthlyData',
            'topProducts',
            'salesByCategory'
        ));
    }
}