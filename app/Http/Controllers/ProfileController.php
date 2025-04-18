<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function index()
    {
        $user = Auth::user();
        return view('account.index', compact('user'));
    }
    
    /**
     * Show the form for editing the user's profile.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('account.edit', compact('user'));
    }
    
    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'current_password' => 'nullable|required_with:password|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        // Verify current password if changing password
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.'])->withInput();
            }
        }
        
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }
        
        return redirect()->route('account.index')
            ->with('success', 'Profile updated successfully.');
    }
    
    /**
     * Display the user's addresses.
     */
    public function addresses()
    {
        $user = Auth::user();
        $shippingAddresses = $user->addresses()->where('address_type', 'shipping')->get();
        $billingAddresses = $user->addresses()->where('address_type', 'billing')->get();
        
        return view('account.addresses', compact('shippingAddresses', 'billingAddresses'));
    }
    
    /**
     * Show the form for creating a new address.
     */
    public function createAddress()
    {
        return view('account.addresses.create');
    }
    
    /**
     * Store a new address.
     */
    public function storeAddress(Request $request)
    {
        $request->validate([
            'address_type' => 'required|in:shipping,billing',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:2',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'is_default' => 'boolean',
        ]);
        
        $user = Auth::user();
        
        // If setting as default, unset any existing default address of this type
        if ($request->is_default) {
            $user->addresses()
                ->where('address_type', $request->address_type)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }
        
        $user->addresses()->create($request->all());
        
        return redirect()->route('account.addresses')
            ->with('success', 'Address added successfully.');
    }
    
    /**
     * Show the form for editing an address.
     */
    public function editAddress(Address $address)
    {
        // Ensure user owns the address
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('account.addresses.edit', compact('address'));
    }
    
    /**
     * Update an address.
     */
    public function updateAddress(Request $request, Address $address)
    {
        // Ensure user owns the address
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'address_type' => 'required|in:shipping,billing',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:2',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'is_default' => 'boolean',
        ]);
        
        $user = Auth::user();
        
        // If setting as default, unset any existing default address of this type
        if ($request->is_default && !$address->is_default) {
            $user->addresses()
                ->where('address_type', $request->address_type)
                ->where('is_default', true)
                ->update(['is_default' => false]);
        }
        
        $address->update($request->all());
        
        return redirect()->route('account.addresses')
            ->with('success', 'Address updated successfully.');
    }
    
    /**
     * Delete an address.
     */
    public function destroyAddress(Address $address)
    {
        // Ensure user owns the address
        if ($address->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Check if the orders table has the shipping/billing address columns
        $hasAddressColumns = false;
        
        try {
            // Safely check if these columns exist in the orders table
            $columnsExist = DB::select("
                SELECT COLUMN_NAME 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'orders' 
                AND COLUMN_NAME IN ('shipping_address_id', 'billing_address_id')
            ");
            
            $hasAddressColumns = count($columnsExist) > 0;
        } catch (\Exception $e) {
            // If there's any error, assume columns don't exist
            $hasAddressColumns = false;
        }
        
        $isUsedInOrders = false;
        
        if ($hasAddressColumns) {
            // If the columns exist, check if address is used in any orders
            $isUsedInOrders = Order::where(function($query) use ($address) {
                $query->where('shipping_address_id', $address->id)
                      ->orWhere('billing_address_id', $address->id);
            })->exists();
        } else {
            // Alternative check - look for any relationships in your order system
            // For example, if you have an order_addresses table:
            $isUsedInOrders = false;
            
            // Uncomment and modify this if you have a different relationship structure
            // $isUsedInOrders = DB::table('order_addresses')
            //     ->where('address_id', $address->id)
            //     ->exists();
        }
            
        if ($isUsedInOrders) {
            return back()->with('error', 'This address cannot be deleted because it is used in orders.');
        }
        
        $address->delete();
        
        return redirect()->route('account.addresses')
            ->with('success', 'Address deleted successfully.');
    }
    
    /**
     * Display the user's orders.
     */
    public function orders()
    {
        $user = Auth::user();
        $orders = $user->orders()->with('items')->latest()->paginate(10);
        
        return view('account.orders', compact('orders'));
    }
    
    /**
     * Display a specific order.
     */
    public function showOrder(Order $order)
    {
        // Ensure user owns the order
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        $order->load('items.product', 'items.productVariation', 'shippingAddress', 'billingAddress', 'transactions');
        
        return view('account.orders.show', compact('order'));
    }
}