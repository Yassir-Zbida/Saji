<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index()
    {
        // Get featured products
        $featuredProducts = Product::where('is_active', 1)
            ->where('featured', 1) // Make sure you have this column in your products table
            ->limit(8)
            ->get();
            
        // Get new arrivals
        $newArrivals = Product::where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->with('category', 'images')
            ->take(8)
            ->get();
            
        // Get on sale products
        $onSaleProducts = Product::where('is_active', true)
            ->whereNotNull('sale_price')
            ->whereRaw('sale_price < price')
            ->with('category', 'images')
            ->take(8)
            ->get();
            
        // Get main categories
        $categories = Category::where('is_active', true)
            ->whereNull('parent_id')
            ->withCount('products')
            ->orderBy('position')
            ->take(6)
            ->get();
            
        return view('home', compact('featuredProducts', 'newArrivals', 'onSaleProducts', 'categories'));
    }
    
    /**
     * Display the about page.
     */
    public function about()
    {
        return view('about');
    }
    
    /**
     * Display the contact page.
     */
    public function contact()
    {
        return view('contact');
    }
    
    /**
     * Process the contact form.
     */
    public function contactSubmit(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        
        // Process contact form (send email, save to database, etc.)
        // This is just a placeholder - implement your actual contact form processing
        
        return back()->with('success', 'Your message has been sent successfully. We will get back to you soon.');
    }
    
    /**
     * Display the FAQ page.
     */
    public function faq()
    {
        return view('faq');
    }
    
    /**
     * Display the terms and conditions page.
     */
    public function terms()
    {
        return view('terms');
    }
    
    /**
     * Display the privacy policy page.
     */
    public function privacy()
    {
        return view('privacy');
    }
}