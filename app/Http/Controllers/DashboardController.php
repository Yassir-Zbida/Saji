<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
     * Display the dashboard index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Données de base du tableau de bord - toutes dynamiques
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('quantity', '<=', 5)->count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $openTickets = SupportTicket::whereIn('status', ['open', 'in_progress'])->count();
        
        // Calcul des pourcentages de croissance pour les statistiques
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        $currentMonthStart = Carbon::now()->startOfMonth();
        
        // Croissance des produits
        $lastMonthProducts = Product::where('created_at', '>=', $lastMonthStart)
            ->where('created_at', '<=', $lastMonthEnd)
            ->count();
        $currentMonthProducts = Product::where('created_at', '>=', $currentMonthStart)->count();
        $productGrowth = $lastMonthProducts > 0 
            ? round(($currentMonthProducts - $lastMonthProducts) / $lastMonthProducts * 100) 
            : 0;
        
        // Croissance des commandes
        $lastMonthOrders = Order::where('created_at', '>=', $lastMonthStart)
            ->where('created_at', '<=', $lastMonthEnd)
            ->count();
        $currentMonthOrders = Order::where('created_at', '>=', $currentMonthStart)->count();
        $orderGrowth = $lastMonthOrders > 0 
            ? round(($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders * 100) 
            : 0;
        
        // Croissance des clients
        $lastMonthCustomers = User::where('role', 'customer')
            ->where('created_at', '>=', $lastMonthStart)
            ->where('created_at', '<=', $lastMonthEnd)
            ->count();
        $currentMonthCustomers = User::where('role', 'customer')
            ->where('created_at', '>=', $currentMonthStart)
            ->count();
        $customerGrowth = $lastMonthCustomers > 0 
            ? round(($currentMonthCustomers - $lastMonthCustomers) / $lastMonthCustomers * 100) 
            : 0;
        
        // Croissance des tickets
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();
        $currentWeekStart = Carbon::now()->startOfWeek();
        
        $lastWeekTickets = SupportTicket::where('created_at', '>=', $lastWeekStart)
            ->where('created_at', '<=', $lastWeekEnd)
            ->count();
        $currentWeekTickets = SupportTicket::where('created_at', '>=', $currentWeekStart)->count();
        $ticketGrowth = $lastWeekTickets > 0 
            ? round(($currentWeekTickets - $lastWeekTickets) / $lastWeekTickets * 100) 
            : 0;
        
        // Nouveaux clients cette semaine
        $newCustomersThisWeek = User::where('role', 'customer')
            ->where('created_at', '>=', $currentWeekStart)
            ->count();

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
        $salesData = $this->getSalesDataForChart();

        // Get top products
        $topProducts = $this->getTopProducts();

        return view('dashboard.index', compact(
            'totalProducts',
            'lowStockProducts',
            'totalOrders',
            'pendingOrders',
            'totalCustomers',
            'openTickets',
            'recentOrders',
            'recentTickets',
            'salesData',
            'topProducts',
            'productGrowth',
            'orderGrowth',
            'customerGrowth',
            'ticketGrowth',
            'newCustomersThisWeek'
        ));
    }

    /**
     * Get top selling products
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTopProducts()
    {
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('product_images', function($join) {
                $join->on('products.id', '=', 'product_images.product_id')
                    ->whereRaw('product_images.id = (SELECT MIN(id) FROM product_images WHERE product_id = products.id)');
            })
            ->select(
                'products.id',
                'products.name',
                'product_images.image_path as image',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_sales')
            )
            ->groupBy('products.id', 'products.name', 'product_images.image_path')
            ->orderBy('total_quantity', 'desc')
            ->take(5)
            ->get();

        // Format image URLs
        $topProducts = $topProducts->map(function($product) {
            if ($product->image) {
                $product->image = asset('storage/' . $product->image);
            }
            return $product;
        });

        return $topProducts;
    }

    /**
     * Get top selling products API endpoint
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTopProductsApi()
    {
        return response()->json($this->getTopProducts());
    }

    /**
     * Get dashboard summary data for AJAX refresh
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDashboardSummary()
    {
        // Données de base
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('quantity', '<=', 5)->count();
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $totalCustomers = User::where('role', 'customer')->count();
        $openTickets = SupportTicket::whereIn('status', ['open', 'in_progress'])->count();
        
        // Calcul des pourcentages de croissance
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        $currentMonthStart = Carbon::now()->startOfMonth();
        
        // Croissance des produits
        $lastMonthProducts = Product::where('created_at', '>=', $lastMonthStart)
            ->where('created_at', '<=', $lastMonthEnd)
            ->count();
        $currentMonthProducts = Product::where('created_at', '>=', $currentMonthStart)->count();
        $productGrowth = $lastMonthProducts > 0 
            ? round(($currentMonthProducts - $lastMonthProducts) / $lastMonthProducts * 100) 
            : 0;
        
        // Croissance des commandes
        $lastMonthOrders = Order::where('created_at', '>=', $lastMonthStart)
            ->where('created_at', '<=', $lastMonthEnd)
            ->count();
        $currentMonthOrders = Order::where('created_at', '>=', $currentMonthStart)->count();
        $orderGrowth = $lastMonthOrders > 0 
            ? round(($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders * 100) 
            : 0;
        
        // Croissance des clients
        $lastMonthCustomers = User::where('role', 'customer')
            ->where('created_at', '>=', $lastMonthStart)
            ->where('created_at', '<=', $lastMonthEnd)
            ->count();
        $currentMonthCustomers = User::where('role', 'customer')
            ->where('created_at', '>=', $currentMonthStart)
            ->count();
        $customerGrowth = $lastMonthCustomers > 0 
            ? round(($currentMonthCustomers - $lastMonthCustomers) / $lastMonthCustomers * 100) 
            : 0;
        
        // Croissance des tickets
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();
        $currentWeekStart = Carbon::now()->startOfWeek();
        
        $lastWeekTickets = SupportTicket::where('created_at', '>=', $lastWeekStart)
            ->where('created_at', '<=', $lastWeekEnd)
            ->count();
        $currentWeekTickets = SupportTicket::where('created_at', '>=', $currentWeekStart)->count();
        $ticketGrowth = $lastWeekTickets > 0 
            ? round(($currentWeekTickets - $lastWeekTickets) / $lastWeekTickets * 100) 
            : 0;
        
        // Nouveaux clients cette semaine
        $newCustomersThisWeek = User::where('role', 'customer')
            ->where('created_at', '>=', $currentWeekStart)
            ->count();

        return response()->json([
            'totalProducts' => $totalProducts,
            'lowStockProducts' => $lowStockProducts,
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'totalCustomers' => $totalCustomers,
            'openTickets' => $openTickets,
            'productGrowth' => $productGrowth,
            'orderGrowth' => $orderGrowth,
            'customerGrowth' => $customerGrowth,
            'ticketGrowth' => $ticketGrowth,
            'newCustomersThisWeek' => $newCustomersThisWeek
        ]);
    }

    /**
     * Get sales data for the chart based on period.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSalesData(Request $request)
    {
        $period = $request->input('period', 'month');
        
        switch ($period) {
            case 'week':
                $startDate = Carbon::now()->subDays(7);
                break;
            case 'year':
                $startDate = Carbon::now()->startOfYear();
                break;
            case 'month':
            default:
                $startDate = Carbon::now()->subDays(30);
                break;
        }
        
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

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }

    /**
     * Get sales data for the chart (for initial load).
     *
     * @return array
     */
    private function getSalesDataForChart()
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
}