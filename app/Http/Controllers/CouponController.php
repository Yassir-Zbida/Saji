<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class CouponController extends Controller
{
    /**
     * Display a listing of the coupons.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('dashboard.coupons.index');
    }

    /**
     * Get coupons data for AJAX requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCouponsData(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $sortField = $request->input('sort_field', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        
        $query = Coupon::query();
        
        // Apply filters
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }
        
        if ($request->filled('status')) {
            $status = $request->input('status');
            $now = Carbon::now();
            
            if ($status === 'active') {
                $query->active()->where(function ($q) use ($now) {
                    $q->whereNull('start_date')
                        ->orWhere('start_date', '<=', $now);
                })->where(function ($q) use ($now) {
                    $q->whereNull('end_date')
                        ->orWhere('end_date', '>=', $now);
                });
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($status === 'expired') {
                $query->where('is_active', true)
                    ->whereNotNull('end_date')
                    ->where('end_date', '<', $now);
            }
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Apply sorting
        $query->orderBy($sortField, $sortDirection);
        
        // Get paginated results
        $coupons = $query->paginate($perPage, ['*'], 'page', $page);
        
        return response()->json([
            'success' => true,
            'data' => $coupons->items(),
            'pagination' => [
                'current_page' => $coupons->currentPage(),
                'last_page' => $coupons->lastPage(),
                'per_page' => $coupons->perPage(),
                'total' => $coupons->total()
            ]
        ]);
    }

    /**
     * Get coupon statistics.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatistics()
    {
        $now = Carbon::now();
        
        $total = Coupon::count();
        
        // Active coupons (using the scope from your model)
        $active = Coupon::valid()->count();
        
        // Expired coupons
        $expired = Coupon::where('is_active', true)
            ->whereNotNull('end_date')
            ->where('end_date', '<', $now)
            ->count();
        
        // Upcoming coupons
        $upcoming = Coupon::where('is_active', true)
            ->whereNotNull('start_date')
            ->where('start_date', '>', $now)
            ->count();
        
        // Calculate usage rate (example calculation)
        $usageRate = 0;
        $totalUsageLimit = Coupon::whereNotNull('usage_limit')->sum('usage_limit');
        $totalUsageCount = Coupon::sum('usage_count');
        
        if ($totalUsageLimit > 0) {
            $usageRate = round(($totalUsageCount / $totalUsageLimit) * 100);
        }
        
        // Calculate growth (example calculation)
        $lastMonth = Carbon::now()->subMonth();
        $lastMonthCount = Coupon::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();
        
        $thisMonth = Carbon::now();
        $thisMonthCount = Coupon::whereMonth('created_at', $thisMonth->month)
            ->whereYear('created_at', $thisMonth->year)
            ->count();
        
        $growth = 0;
        if ($lastMonthCount > 0) {
            $growth = round((($thisMonthCount - $lastMonthCount) / $lastMonthCount) * 100);
        }
        
        return response()->json([
            'success' => true,
            'stats' => [
                'total' => $total,
                'active' => $active,
                'expired' => $expired,
                'upcoming' => $upcoming,
                'usage_rate' => $usageRate,
                'usage_growth' => 5, // Example value
                'growth' => $growth
            ]
        ]);
    }

    /**
     * Show the form for creating a new coupon.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.coupons.create');
    }

    /**
     * Store a newly created coupon in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255|unique:coupons,code',
            'type' => 'required|string|max:255',
            'value' => 'required|numeric|min:0',
            'minimum_spend' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'product_ids' => 'nullable|string',
            'excluded_product_ids' => 'nullable|string',
            'category_ids' => 'nullable|string',
            'excluded_category_ids' => 'nullable|string',
            'email_restrictions' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Additional validation for percentage type
        if ($request->input('type') === 'percentage' && $request->input('value') > 100) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => ['value' => ['Percentage discount cannot exceed 100%']]
                ], 422);
            }
            
            return redirect()->back()
                ->withErrors(['value' => 'Percentage discount cannot exceed 100%'])
                ->withInput();
        }
        
        // Create the coupon
        $coupon = new Coupon();
        $coupon->code = strtoupper($request->input('code'));
        $coupon->type = $request->input('type');
        $coupon->value = $request->input('value');
        $coupon->minimum_spend = $request->input('minimum_spend');
        $coupon->maximum_discount = $request->input('maximum_discount');
        $coupon->usage_limit = $request->input('usage_limit');
        $coupon->usage_count = 0;
        $coupon->individual_use = $request->has('individual_use') ? 1 : 0;
        $coupon->exclude_sale_items = $request->has('exclude_sale_items') ? 1 : 0;
        $coupon->product_ids = $request->input('product_ids');
        $coupon->excluded_product_ids = $request->input('excluded_product_ids');
        $coupon->category_ids = $request->input('category_ids');
        $coupon->excluded_category_ids = $request->input('excluded_category_ids');
        $coupon->email_restrictions = $request->input('email_restrictions');
        $coupon->start_date = $request->input('start_date');
        $coupon->end_date = $request->input('end_date');
        $coupon->is_active = $request->has('is_active') ? 1 : 0;
        $coupon->description = $request->input('description');
        $coupon->save();
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Coupon created successfully',
                'code' => $coupon->code,
                'redirect' => route('admin.coupons')
            ]);
        }
        
        return redirect()->route('admin.coupons')
            ->with('success', 'Coupon created successfully');
    }

    /**
     * Display the specified coupon details.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function details($id)
    {
        $coupon = Coupon::with(['orders' => function($query) {
            $query->latest()->limit(5);
        }])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'coupon' => $coupon
        ]);
    }

    /**
     * Show the form for editing the specified coupon.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);
        return view('dashboard.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified coupon in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255|unique:coupons,code,' . $id,
            'type' => 'required|string|max:255',
            'value' => 'required|numeric|min:0',
            'minimum_spend' => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'description' => 'nullable|string',
            'product_ids' => 'nullable|string',
            'excluded_product_ids' => 'nullable|string',
            'category_ids' => 'nullable|string',
            'excluded_category_ids' => 'nullable|string',
            'email_restrictions' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Additional validation for percentage type
        if ($request->input('type') === 'percentage' && $request->input('value') > 100) {
            return redirect()->back()
                ->withErrors(['value' => 'Percentage discount cannot exceed 100%'])
                ->withInput();
        }
        
        // Update the coupon
        $coupon->code = strtoupper($request->input('code'));
        $coupon->type = $request->input('type');
        $coupon->value = $request->input('value');
        $coupon->minimum_spend = $request->input('minimum_spend');
        $coupon->maximum_discount = $request->input('maximum_discount');
        $coupon->usage_limit = $request->input('usage_limit');
        $coupon->individual_use = $request->has('individual_use') ? 1 : 0;
        $coupon->exclude_sale_items = $request->has('exclude_sale_items') ? 1 : 0;
        $coupon->product_ids = $request->input('product_ids');
        $coupon->excluded_product_ids = $request->input('excluded_product_ids');
        $coupon->category_ids = $request->input('category_ids');
        $coupon->excluded_category_ids = $request->input('excluded_category_ids');
        $coupon->email_restrictions = $request->input('email_restrictions');
        $coupon->start_date = $request->input('start_date');
        $coupon->end_date = $request->input('end_date');
        $coupon->is_active = $request->has('is_active') ? 1 : 0;
        $coupon->description = $request->input('description');
        $coupon->save();
        
        return redirect()->route('admin.coupons')
            ->with('success', 'Coupon updated successfully');
    }

    /**
     * Toggle the status of the specified coupon.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleStatus($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->is_active = !$coupon->is_active;
        $coupon->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Coupon status updated successfully',
            'is_active' => $coupon->is_active
        ]);
    }

    /**
     * Remove the specified coupon from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Coupon deleted successfully'
        ]);
    }

    /**
     * Export coupons data.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        // Build query based on filters
        $query = Coupon::query();
        
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }
        
        if ($request->filled('status')) {
            $status = $request->input('status');
            $now = Carbon::now();
            
            if ($status === 'active') {
                $query->active()->where(function ($q) use ($now) {
                    $q->whereNull('start_date')
                        ->orWhere('start_date', '<=', $now);
                })->where(function ($q) use ($now) {
                    $q->whereNull('end_date')
                        ->orWhere('end_date', '>=', $now);
                });
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($status === 'expired') {
                $query->where('is_active', true)
                    ->whereNotNull('end_date')
                    ->where('end_date', '<', $now);
            }
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->input('date_from'));
        }
        
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        $coupons = $query->get();
        
        // In a real application, you would generate a CSV or Excel file
        // For this example, we'll just return a simple CSV
        $filename = 'coupons_export_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($coupons) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID', 'Code', 'Type', 'Value', 'Minimum Spend', 'Maximum Discount',
                'Usage Count', 'Usage Limit', 'Individual Use', 'Exclude Sale Items',
                'Product IDs', 'Excluded Product IDs', 'Category IDs', 'Excluded Category IDs',
                'Email Restrictions', 'Start Date', 'End Date', 'Active', 'Description', 
                'Created At', 'Updated At'
            ]);
            
            // Add data rows
            foreach ($coupons as $coupon) {
                fputcsv($file, [
                    $coupon->id,
                    $coupon->code,
                    $coupon->type,
                    $coupon->value,
                    $coupon->minimum_spend,
                    $coupon->maximum_discount,
                    $coupon->usage_count,
                    $coupon->usage_limit,
                    $coupon->individual_use ? 'Yes' : 'No',
                    $coupon->exclude_sale_items ? 'Yes' : 'No',
                    $coupon->product_ids,
                    $coupon->excluded_product_ids,
                    $coupon->category_ids,
                    $coupon->excluded_category_ids,
                    $coupon->email_restrictions,
                    $coupon->start_date,
                    $coupon->end_date,
                    $coupon->is_active ? 'Yes' : 'No',
                    $coupon->description,
                    $coupon->created_at,
                    $coupon->updated_at
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}