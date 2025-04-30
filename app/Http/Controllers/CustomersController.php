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
            $query->where(function($q) use ($search) {
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
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Get users for AJAX datatable.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers(Request $request)
    {
        $query = User::query();
        
        // Apply filters
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserDetails(User $user)
    {
        $userData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'phone' => $user->phone,
            'address' => $user->address,
            'city' => $user->city,
            'state' => $user->state,
            'zip_code' => $user->zip_code,
            'country' => $user->country,
            'email_verified_at' => $user->email_verified_at,
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
        
        // Load relationships if they exist
        $relationData = [];
        
        if (method_exists($user, 'orders')) {
            $recentOrders = $user->orders()->latest()->take(5)->get();
            $orderCount = $user->orders()->count();
            $relationData['orders'] = [
                'count' => $orderCount,
                'recent' => $recentOrders
            ];
        }
        
        if (method_exists($user, 'supportTickets')) {
            $recentTickets = $user->supportTickets()->latest()->take(5)->get();
            $ticketCount = $user->supportTickets()->count();
            $relationData['tickets'] = [
                'count' => $ticketCount,
                'recent' => $recentTickets
            ];
        }
        
        return response()->json([
            'success' => true,
            'user' => $userData,
            'relations' => $relationData
        ]);
    }

    /**
     * Show the form for creating a new user.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
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
        ]);

        User::create([
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

        return redirect()->route('users.index')
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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        // Load relationships if they exist
        if (method_exists($user, 'orders')) {
            $user->load('orders');
        }
        
        if (method_exists($user, 'supportTickets')) {
            $user->load('supportTickets');
        }
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
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

        $user->update($userData);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Update a user via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAjax(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
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
        
        $user->update($userData);
        
        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    /**
     * Remove the specified user from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        
        // Check if user has related records
        $hasRelatedRecords = false;
        
        if (method_exists($user, 'orders') && $user->orders()->count() > 0) {
            $hasRelatedRecords = true;
        }
        
        if (method_exists($user, 'supportTickets') && $user->supportTickets()->count() > 0) {
            $hasRelatedRecords = true;
        }
        
        if ($hasRelatedRecords) {
            return back()->with('error', 'Cannot delete user with related records.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Delete a user via AJAX.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroyAjax(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot delete your own account.'
            ], 403);
        }
        
        // Check if user has related records
        $hasRelatedRecords = false;
        $relatedRecords = [];
        
        if (method_exists($user, 'orders') && $user->orders()->count() > 0) {
            $hasRelatedRecords = true;
            $relatedRecords['orders'] = $user->orders()->count();
        }
        
        if (method_exists($user, 'supportTickets') && $user->supportTickets()->count() > 0) {
            $hasRelatedRecords = true;
            $relatedRecords['tickets'] = $user->supportTickets()->count();
        }
        
        if ($hasRelatedRecords) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete user with related records.',
                'related_records' => $relatedRecords
            ], 422);
        }
        
        $user->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }
    
    /**
     * Perform bulk actions on users via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function bulkActionAjax(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action' => 'required|in:delete,change_role',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'new_role' => 'required_if:action,change_role|in:admin,manager,customer,support_agent',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $userIds = $request->user_ids;
        
        // Prevent actions on own account
        if (in_array(auth()->id(), $userIds)) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot perform bulk actions on your own account.'
            ], 403);
        }
        
        $results = [
            'success' => true,
            'processed' => count($userIds),
            'successful' => 0,
            'failed' => 0,
            'message' => ''
        ];
        
        switch ($request->action) {
            case 'delete':
                foreach ($userIds as $userId) {
                    $user = User::find($userId);
                    
                    if (!$user) {
                        $results['failed']++;
                        continue;
                    }
                    
                    // Skip users with related records
                    $hasRelatedRecords = false;
                    
                    if (method_exists($user, 'orders') && $user->orders()->count() > 0) {
                        $hasRelatedRecords = true;
                    }
                    
                    if (method_exists($user, 'supportTickets') && $user->supportTickets()->count() > 0) {
                        $hasRelatedRecords = true;
                    }
                    
                    if ($hasRelatedRecords) {
                        $results['failed']++;
                        continue;
                    }
                    
                    $user->delete();
                    $results['successful']++;
                }
                
                $results['message'] = "{$results['successful']} users deleted successfully. {$results['failed']} users could not be deleted.";
                break;
                
            case 'change_role':
                User::whereIn('id', $userIds)->update(['role' => $request->new_role]);
                $results['successful'] = count($userIds);
                $results['message'] = "{$results['successful']} users updated to role: {$request->new_role}";
                break;
        }
        
        return response()->json($results);
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
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
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
        
        $callback = function() use ($users) {
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
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyEmail(User $user)
    {
        if ($user->email_verified_at) {
            return response()->json([
                'success' => false,
                'message' => 'Email already verified.'
            ]);
        }
        
        $user->email_verified_at = now();
        $user->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully.',
            'verified_at' => $user->email_verified_at
        ]);
    }
    
    /**
     * Reset user password and send notification.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function resetPassword(User $user)
    {
        // Generate random password
        $password = \Str::random(10);
        
        $user->password = Hash::make($password);
        $user->save();
        
        // Here you would typically send an email with the new password
        // Mail::to($user->email)->send(new PasswordResetByAdmin($user, $password));
        
        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully. A temporary password has been generated.',
            'password' => $password // Only for demonstration, in production you should send this via email only
        ]);
    }
    
    /**
     * Impersonate a user (admin only).
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function impersonate(User $user)
    {
        // Store current user ID in session
        session()->put('impersonator_id', auth()->id());
        
        // Login as the target user
        Auth::login($user);
        
        return redirect()->route('dashboard')
            ->with('success', 'You are now impersonating ' . $user->name);
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
            
            return redirect()->route('admin.users.index')
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
        $stats = [
            'total' => User::count(),
            'by_role' => [
                'admin' => User::where('role', 'admin')->count(),
                'manager' => User::where('role', 'manager')->count(),
                'customer' => User::where('role', 'customer')->count(),
                'support_agent' => User::where('role', 'support_agent')->count(),
            ],
            'verified' => User::whereNotNull('email_verified_at')->count(),
            'unverified' => User::whereNull('email_verified_at')->count(),
            'recent' => User::orderBy('created_at', 'desc')->take(5)->get(),
            'monthly_registrations' => $this->getMonthlyRegistrations()
        ];
        
        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
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