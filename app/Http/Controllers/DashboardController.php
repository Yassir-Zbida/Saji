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
use App\Models\Category;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Response;

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
        $lowStockProducts = Product::where('quantity', '>', 0)->where('quantity', '<=', 5)->count();
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
            ->take(7)
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
            ->leftJoin('product_images', function ($join) {
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
        $topProducts = $topProducts->map(function ($product) {
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
        $lowStockProducts = Product::where('quantity', '>', 0)->where('quantity', '<=', 5)->count();
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




    /**
     * Display a comprehensive listing of all orders for admin.
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function adminAllOrders(Request $request)
    {
        // This route should be protected with admin middleware in routes file

        $query = Order::with(['user', 'items.product', 'invoice']);

        // Admin-specific filters
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('payment_status') && $request->payment_status != '') {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Admin-specific search (more comprehensive)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('total_amount', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    })
                    ->orWhereHas('invoice', function ($q) use ($search) {
                        $q->where('invoice_number', 'like', "%{$search}%");
                    });
            });
        }

        // Sort orders (default: newest first)
        $sortField = $request->input('sort_field', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Get all statistics for admin dashboard
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();

        $totalRevenue = Order::where('payment_status', 'paid')->sum('total_amount');
        $pendingRevenue = Order::where('payment_status', 'pending')->sum('total_amount');

        // Option 1: Paginate with more items per page for admin
        $orders = $query->paginate(25);

        // Option 2: Get all orders without pagination
        // $orders = $query->get();

        // Get users for filter dropdown
        $users = User::all();

        return view('dashboard.orders.index', compact(
            'orders',
            'users',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'cancelledOrders',
            'totalRevenue',
            'pendingRevenue'
        ));
    }



    /**
     * Display a comprehensive listing of all products for admin.
     * 
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function adminAllProducts(Request $request)
    {
        try {
            // Check if this is an AJAX request
            if ($request->ajax() || $request->wantsJson()) {
                return $this->getProductsData($request);
            }

            // For initial page load, just return the view with minimal data
            // The actual product data will be loaded via AJAX
            $categories = Category::all();

            // Get basic stats for initial page load
            $totalProducts = Product::count();
            $lowStockProducts = Product::where('quantity', '<=', 5)->count();
            $activeProducts = Product::where('is_active', true)->count();

            return view('dashboard.products.index', compact('categories', 'totalProducts', 'lowStockProducts', 'activeProducts'));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error in adminAllProducts: ' . $e->getMessage());

            // If AJAX request, return error response
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => true,
                    'message' => 'An error occurred while loading products. Please try again.',
                    'details' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }

            // For regular request, flash error message and return view
            return view('dashboard.products.index')->withErrors(['error' => 'An error occurred while loading products. Please try again.']);
        }
    }

    /**
 * Get products data for AJAX request in admin dashboard
 * 
 * @param Request $request
 * @return \Illuminate\Http\JsonResponse
 */
public function getProductsData(Request $request)
{
    try {
        // Start with a base query
        $query = DB::table('products')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'products.id',
                'products.name',
                'products.description',
                'products.price',
                'products.quantity',
                'products.sku',
                'products.is_active',
                'products.created_at',
                'products.image',
                'categories.name as category_name',
                'categories.id as category_id'
            );
            
        // Apply filters
        if ($request->has('category') && $request->category != '') {
            $query->where('products.category_id', $request->category);
        }

        if ($request->has('status') && $request->status != '') {
            if ($request->status === 'active') {
                $query->where('products.is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('products.is_active', false);
            }
        }

        if ($request->has('stock_status') && $request->stock_status != '') {
            if ($request->stock_status === 'in_stock') {
                $query->where('products.quantity', '>', 0);
            } elseif ($request->stock_status === 'out_of_stock') {
                $query->where('products.quantity', 0);
            } elseif ($request->stock_status === 'low_stock') {
                // Use a fixed threshold of 5
                $query->where('products.quantity', '>', 0)
                      ->where('products.quantity', '<=', 5);
            }
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('products.name', 'like', "%{$search}%")
                  ->orWhere('products.description', 'like', "%{$search}%")
                  ->orWhere('products.sku', 'like', "%{$search}%");
            });
        }

        // Sort products
        $sortField = $request->input('sort', 'products.created_at');
        if ($sortField === 'name') $sortField = 'products.name';
        if ($sortField === 'price') $sortField = 'products.price';
        if ($sortField === 'quantity') $sortField = 'products.quantity';
        if ($sortField === 'created_at') $sortField = 'products.created_at';
        
        $sortDirection = $request->input('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Paginate the results
        $perPage = $request->input('per_page', 10);
        $products = $query->paginate($perPage);

        // Get product images separately - ONLY using image_path column
        $productImages = [];
        if (Schema::hasTable('product_images')) {
            $productIds = collect($products->items())->pluck('id')->toArray();
            
            // Only select image_path, not path
            $productImages = DB::table('product_images')
                ->whereIn('product_id', $productIds)
                ->select('product_id', 'image_path')
                ->get()
                ->groupBy('product_id');
        }

        // Transform the data
        $transformedProducts = collect($products->items())->map(function ($product) use ($productImages) {
            // Convert to array for easier manipulation
            $productArray = (array) $product;
            
            // Add image URL - only using image_path
            if (isset($productImages[$product->id]) && count($productImages[$product->id]) > 0) {
                $imagePath = $productImages[$product->id][0]->image_path;
                // Dans la méthode getProductsData du DashboardController
$productArray['image_url'] = $imagePath ? asset('storage/' . $imagePath) : asset('images/placeholder.jpg');
            } else {
                $productArray['image_url'] = $product->image ? asset('storage/' . $product->image) : asset('images/placeholder.jpg');
            }
            
            // Format price for display
            $productArray['formatted_price'] = number_format($product->price, 2);
            
            // Add stock status label
            if ($product->quantity <= 0) {
                $productArray['stock_status_label'] = 'Out of Stock';
                $productArray['stock_status_class'] = 'bg-red-100 text-red-800';
            } elseif ($product->quantity <= 5) { // Using fixed threshold of 5
                $productArray['stock_status_label'] = 'Low Stock';
                $productArray['stock_status_class'] = 'bg-yellow-100 text-yellow-800';
            } else {
                $productArray['stock_status_label'] = 'In Stock';
                $productArray['stock_status_class'] = 'bg-green-100 text-green-800';
            }
            
            // Add category object for compatibility
            $productArray['category'] = (object) [
                'id' => $product->category_id,
                'name' => $product->category_name
            ];
            
            return (object) $productArray;
        });

        // Replace the items in the paginator
        $products->setCollection($transformedProducts);

        // Get statistics
        $totalProducts = DB::table('products')->count();
        $lowStockProducts = DB::table('products')
            ->where('quantity', '>', 0)
            ->where('quantity', '<=', 5)
            ->count();
        $activeProducts = DB::table('products')->where('is_active', true)->count();
        
        // Get category count
        $categoryCount = DB::table('categories')->count();
        
        // Get top category
        $topCategory = DB::table('categories')
            ->leftJoin('products', 'categories.id', '=', 'products.category_id')
            ->select('categories.name', DB::raw('count(products.id) as product_count'))
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('product_count', 'desc')
            ->first();
            
        $topCategoryName = $topCategory ? $topCategory->name : 'None';
        
        // Calculate product growth (simplified)
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        $currentMonthStart = Carbon::now()->startOfMonth();

        $lastMonthProducts = DB::table('products')
            ->where('created_at', '>=', $lastMonthStart)
            ->where('created_at', '<=', $lastMonthEnd)
            ->count();
        $currentMonthProducts = DB::table('products')
            ->where('created_at', '>=', $currentMonthStart)
            ->count();
        $productGrowth = $lastMonthProducts > 0
            ? round(($currentMonthProducts - $lastMonthProducts) / $lastMonthProducts * 100)
            : 0;

        return response()->json([
            'products' => $products,
            'stats' => [
                'totalProducts' => $totalProducts,
                'lowStockProducts' => $lowStockProducts,
                'activeProducts' => $activeProducts,
                'categoryCount' => $categoryCount,
                'topCategory' => $topCategoryName,
                'productGrowth' => $productGrowth
            ]
        ]);
    } catch (\Exception $e) {
        \Log::error('Error in getProductsData: ' . $e->getMessage());
        \Log::error($e->getTraceAsString());
        
        return response()->json([
            'error' => true,
            'message' => 'An error occurred while loading products. Please try again.',
            'details' => config('app.debug') ? $e->getMessage() : null,
            'trace' => config('app.debug') ? $e->getTraceAsString() : null
        ], 500);
    }
}
    public function getCategoriesData()
    {
        try {
            // Direct database query
            $categories = DB::table('categories')
                ->select('id', 'name')
                ->orderBy('name')
                ->get();

            return response()->json($categories);
        } catch (\Exception $e) {
            \Log::error('Error in getCategoriesData: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());

            return response()->json([
                'error' => true,
                'message' => 'An error occurred while loading categories.',
                'details' => config('app.debug') ? $e->getMessage() : null,
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }

    /**
     * Export products to CSV
     * 
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportProducts(Request $request)
    {
        try {
            // Start with a base query
            $query = DB::table('products')
                ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
                ->select(
                    'products.id',
                    'products.name',
                    'products.sku',
                    'products.description',
                    'products.price',
                    'products.quantity',
                    'products.is_active',
                    'products.created_at',
                    'categories.name as category_name'
                );
            
            // Apply the same filters as in the getProductsData method
            if ($request->has('category') && $request->category != '') {
                $query->where('products.category_id', $request->category);
            }

            if ($request->has('status') && $request->status != '') {
                if ($request->status === 'active') {
                    $query->where('products.is_active', true);
                } elseif ($request->status === 'inactive') {
                    $query->where('products.is_active', false);
                }
            }

            if ($request->has('stock_status') && $request->stock_status != '') {
                if ($request->stock_status === 'in_stock') {
                    $query->where('products.quantity', '>', 0);
                } elseif ($request->stock_status === 'out_of_stock') {
                    $query->where('products.quantity', 0);
                } elseif ($request->stock_status === 'low_stock') {
                    $query->where('products.quantity', '>', 0)
                          ->where('products.quantity', '<=', 5);
                }
            }

            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('products.name', 'like', "%{$search}%")
                      ->orWhere('products.description', 'like', "%{$search}%")
                      ->orWhere('products.sku', 'like', "%{$search}%");
                });
            }

            // Sort products
            $sortField = $request->input('sort', 'products.created_at');
            if ($sortField === 'name') $sortField = 'products.name';
            if ($sortField === 'price') $sortField = 'products.price';
            if ($sortField === 'quantity') $sortField = 'products.quantity';
            if ($sortField === 'created_at') $sortField = 'products.created_at';
            
            $sortDirection = $request->input('direction', 'desc');
            $query->orderBy($sortField, $sortDirection);

            // Get all products (no pagination for export)
            $products = $query->get();

            // Create CSV file
            $filename = 'products_export_' . date('Y-m-d_His') . '.csv';
            $handle = fopen($filename, 'w+');
            
            // Add CSV headers
            fputcsv($handle, [
                'ID', 
                'Name', 
                'SKU', 
                'Category', 
                'Description', 
                'Price', 
                'Quantity', 
                'Status', 
                'Created At'
            ]);
            
            // Add product data
            foreach($products as $product) {
                fputcsv($handle, [
                    $product->id,
                    $product->name,
                    $product->sku,
                    $product->category_name,
                    $product->description,
                    $product->price,
                    $product->quantity,
                    $product->is_active ? 'Active' : 'Inactive',
                    $product->created_at
                ]);
            }
            
            fclose($handle);
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            
            return Response::download($filename, $filename, $headers)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            \Log::error('Error in exportProducts: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            return back()->withErrors(['error' => 'An error occurred while exporting products. Please try again.']);
        }
    }
}
