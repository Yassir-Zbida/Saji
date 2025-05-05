<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CustomersController extends Controller
{
    /**
     * Display a listing of users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Apply filters
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Sort users
        $sortField = $request->input('sort_field', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');

        $query->orderBy($sortField, $sortDirection);

        $users = $query->paginate(10);

        return view('dashboard.customers.index', compact('users'));
    }

    /**
     * Get users for AJAX datatable.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomers(Request $request)
    {
        $query = User::query();

        // Apply filters
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        if ($request->has('verified') && $request->verified != '') {
            if ($request->verified == 'verified') {
                $query->whereNotNull('email_verified_at');
            } else if ($request->verified == 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Add relationship counts if relationships exist
        if (method_exists(User::class, 'orders')) {
            $query->withCount('orders');
        }

        if (method_exists(User::class, 'supportTickets')) {
            $query->withCount('supportTickets');
        }

        // Sort users
        $sortField = $request->input('sort_field', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // Paginate results
        $perPage = $request->input('per_page', 10);
        $users = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $users->items(),
            'pagination' => [
                'total' => $users->total(),
                'per_page' => $users->perPage(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
            ]
        ]);
    }

    /**
     * Get user details for modal.
     *
     * @param  \App\Models\User  $customer
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomerDetails(User $customer)
    {
        // Load relationships if they exist
        if (method_exists($customer, 'orders')) {
            $customer->load('orders');
        }

        if (method_exists($customer, 'supportTickets')) {
            $customer->load('supportTickets');
        }

        // Convert to array for manipulation
        $userData = $customer->toArray();
        
        // Ensure orders is an array
        if (!isset($userData['orders'])) {
            $userData['orders'] = [];
        }
        
        // Map supportTickets to tickets for the frontend
        if (isset($userData['support_tickets'])) {
            $userData['tickets'] = $userData['support_tickets'];
        } elseif (isset($userData['supportTickets'])) {
            $userData['tickets'] = $userData['supportTickets'];
        } else {
            $userData['tickets'] = [];
        }

        return response()->json([
            'success' => true,
            'user' => $userData
        ]);
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.customers.create');
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,customer,support_agent',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'email_verified' => 'nullable|boolean',
        ]);

        // Create user with basic data
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
        ];

        // Set email_verified_at if checkbox is checked
        if ($request->has('email_verified') && $request->email_verified == '1') {
            $userData['email_verified_at'] = now();
        }

        $user = User::create($userData);

        // Check if request is AJAX
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User created successfully',
                'name' => $user->name,
                'verified' => !is_null($user->email_verified_at)
            ]);
        }

        // Regular form submission
        return redirect()->route('admin.customers')
            ->with('success', 'User created successfully.');
    }

    /**
     * Store a new user via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAjax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,manager,customer,support_agent',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User created successfully',
            'user' => $user
        ]);
    }

    /**
     * Display the specified user.
     *
     * @param  \App\Models\User  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(User $customer)
    {
        // Load relationships if they exist
        if (method_exists($customer, 'orders')) {
            $customer->load('orders');
        }

        if (method_exists($customer, 'supportTickets')) {
            $customer->load('supportTickets');
        }

        return view('dashboard.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\User  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(User $customer)
    {
        return view('dashboard.customers.edit', compact('customer'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($customer->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,manager,customer,support_agent',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $customer->update($userData);

        return redirect()->route('admin.customers')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Update a user via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $customer
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAjax(Request $request, User $customer)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($customer->id),
            ],
            'password' => 'nullable|string|min:8',
            'role' => 'required|in:admin,manager,customer,support_agent',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'country' => $request->country,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $customer->update($userData);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'user' => $customer
        ]);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $customer)
    {
        // Prevent deleting yourself
        if ($customer->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        // Check if user has related records
        $hasRelatedRecords = false;

        if (method_exists($customer, 'orders') && $customer->orders()->count() > 0) {
            $hasRelatedRecords = true;
        }

        if (method_exists($customer, 'supportTickets') && $customer->supportTickets()->count() > 0) {
            $hasRelatedRecords = true;
        }

        if ($hasRelatedRecords) {
            return back()->with('error', 'Cannot delete user with related records.');
        }

        $customer->delete();

        return redirect()->route('admin.customers')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Delete a user via AJAX.
     *
     * @param  \App\Models\User  $customer
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyAjax(User $customer)
    {
        // Prevent deleting yourself
        if ($customer->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account.'
            ], 403);
        }

        // Check if user has related records
        $hasRelatedRecords = false;
        $relatedRecords = [];

        if (method_exists($customer, 'orders') && $customer->orders()->count() > 0) {
            $hasRelatedRecords = true;
            $relatedRecords['orders'] = $customer->orders()->count();
        }

        if (method_exists($customer, 'supportTickets') && $customer->supportTickets()->count() > 0) {
            $hasRelatedRecords = true;
            $relatedRecords['tickets'] = $customer->supportTickets()->count();
        }

        if ($hasRelatedRecords) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete user with related records.',
                'related_records' => $relatedRecords
            ], 422);
        }

        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    /**
     * Export users to CSV.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(Request $request)
    {
        $query = User::query();

        // Apply filters if provided
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        if ($request->has('verified') && $request->verified != '') {
            if ($request->verified == 'verified') {
                $query->whereNotNull('email_verified_at');
            } else if ($request->verified == 'unverified') {
                $query->whereNull('email_verified_at');
            }
        }

        if ($request->has('date_from') && $request->date_from != '') {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $users = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="users-export-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, [
                'ID',
                'Name',
                'Email',
                'Role',
                'Phone',
                'Address',
                'City',
                'State',
                'ZIP Code',
                'Country',
                'Email Verified',
                'Created At'
            ]);

            // Add data
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->role,
                    $user->phone,
                    $user->address,
                    $user->city,
                    $user->state,
                    $user->zip_code,
                    $user->country,
                    $user->email_verified_at ? 'Yes' : 'No',
                    $user->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Verify user email.
     *
     * @param  \App\Models\User  $customer
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyEmail(User $customer)
    {
        if ($customer->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Email already verified.'
            ]);
        }

        $customer->email_verified_at = now();
        $customer->save();

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully.',
            'verified_at' => $customer->email_verified_at
        ]);
    }

    /**
     * Reset user password and send notification.
     *
     * @param  \App\Models\User  $customer
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(User $customer)
    {
        // Generate random password
        $password = \Str::random(10);

        $customer->password = Hash::make($password);
        $customer->save();

        // Here you would typically send an email with the new password
        // Mail::to($customer->email)->send(new PasswordResetByAdmin($customer, $password));

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully. A temporary password has been generated.',
            'password' => $password // Only for demonstration, in production you should send this via email only
        ]);
    }

    /**
     * Impersonate a user (admin only).
     *
     * @param  \App\Models\User  $customer
     * @return \Illuminate\Http\Response
     */
    public function impersonate(User $customer)
    {
        // Store current user ID in session
        session()->put('impersonator_id', auth()->id());

        // Login as the target user
        Auth::login($customer);

        return redirect()->route('admin.dashboard')
            ->with('success', 'You are now impersonating ' . $customer->name);
    }

    /**
     * Stop impersonating.
     *
     * @return \Illuminate\Http\Response
     */
    public function stopImpersonating()
    {
        // Get the original user ID
        $impersonatorId = session()->get('impersonator_id');

        if ($impersonatorId) {
            // Login as the original user
            $originalUser = User::find($impersonatorId);
            Auth::login($originalUser);

            // Remove the impersonator ID from session
            session()->forget('impersonator_id');

            return redirect()->route('admin.customers')
                ->with('success', 'You are no longer impersonating.');
        }

        return redirect()->route('dashboard');
    }

    /**
     * Get user statistics.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getStatistics()
    {
        // Get current month
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $endOfMonth = $now->copy()->endOfMonth();

        // Get previous month
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // Count total customers
        $totalCustomers = User::where('role', 'customer')->count();

        // Count verified customers
        $verifiedCustomers = User::where('role', 'customer')
            ->whereNotNull('email_verified_at')
            ->count();

        // Count unverified customers
        $unverifiedCustomers = User::where('role', 'customer')
            ->whereNull('email_verified_at')
            ->count();

        // Count new customers this month
        $newCustomersThisMonth = User::where('role', 'customer')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->count();

        // Count new customers last month
        $newCustomersLastMonth = User::where('role', 'customer')
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->count();

        // Calculate growth percentage
        $growth = 0;
        if ($newCustomersLastMonth > 0) {
            $growth = round((($newCustomersThisMonth - $newCustomersLastMonth) / $newCustomersLastMonth) * 100);
        } elseif ($newCustomersThisMonth > 0) {
            $growth = 100; // If there were no customers last month but there are this month
        }

        return response()->json([
            'success' => true,
            'stats' => [
                'total' => $totalCustomers,
                'verified' => $verifiedCustomers,
                'unverified' => $unverifiedCustomers,
                'newCustomers' => $newCustomersThisMonth,
                'growth' => $growth
            ]
        ]);
    }

    /**
     * Calculate growth rate from previous month.
     *
     * @return float
     */
    private function calculateGrowthRate()
    {
        $currentMonth = now()->month;
        $previousMonth = now()->subMonth()->month;

        $currentMonthCount = User::whereMonth('created_at', $currentMonth)->count();
        $previousMonthCount = User::whereMonth('created_at', $previousMonth)->count();

        if ($previousMonthCount == 0) {
            return 100; // If no users in previous month, growth is 100%
        }

        return round((($currentMonthCount - $previousMonthCount) / $previousMonthCount) * 100, 1);
    }

    /**
     * Get monthly user registrations for the past year.
     *
     * @return array
     */
    private function getMonthlyRegistrations()
    {
        $results = DB::table('users')
            ->select(DB::raw('YEAR(created_at) as year'), DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subYear())
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        $data = [];

        foreach ($results as $result) {
            $monthName = date('F', mktime(0, 0, 0, $result->month, 1));
            $data[] = [
                'month' => $monthName,
                'year' => $result->year,
                'count' => $result->count
            ];
        }

        return $data;
    }
}