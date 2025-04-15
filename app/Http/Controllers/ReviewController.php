<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // For admin: show all reviews
        if (Auth::user()->isAdmin()) {
            $reviews = Review::with('product', 'user')->latest()->paginate(20);
            return view('admin.reviews.index', compact('reviews'));
        }
        
        // For regular users: show their reviews
        $reviews = Review::where('user_id', Auth::id())
            ->with('product')
            ->latest()
            ->paginate(10);
            
        return view('reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $productId = $request->query('product_id');
        
        if (!$productId) {
            return redirect()->route('home')->with('error', 'Product not specified.');
        }
        
        $product = Product::findOrFail($productId);
        
        // Check if user has purchased the product
        $hasPurchased = $this->userHasPurchasedProduct($productId);
        
        // Check if user has already reviewed this product
        $existingReview = Review::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();
            
        if ($existingReview) {
            return redirect()->route('reviews.edit', $existingReview->id)
                ->with('info', 'You have already reviewed this product. You can edit your review.');
        }
        
        return view('reviews.create', compact('product', 'hasPurchased'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'comment' => 'required|string',
        ]);
        
        // Check if user has already reviewed this product
        $existingReview = Review::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();
            
        if ($existingReview) {
            return redirect()->route('reviews.edit', $existingReview->id)
                ->with('info', 'You have already reviewed this product. You can edit your review.');
        }
        
        // Check if user has purchased the product
        $hasPurchased = $this->userHasPurchasedProduct($request->product_id);
        
        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'is_approved' => Auth::user()->isAdmin() ? true : false, // Auto-approve admin reviews
            'is_verified_purchase' => $hasPurchased,
        ]);
        
        return redirect()->route('products.show', $request->product_id)
            ->with('success', 'Review submitted successfully' . 
                (Auth::user()->isAdmin() ? '.' : ' and is pending approval.'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        // Check if user can view this review
        if (!Auth::user()->isAdmin() && $review->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        // Check if user can edit this review
        if (!Auth::user()->isAdmin() && $review->user_id !== Auth::id()) {
            abort(403);
        }
        
        $product = $review->product;
        
        return view('reviews.edit', compact('review', 'product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        // Check if user can update this review
        if (!Auth::user()->isAdmin() && $review->user_id !== Auth::id()) {
            abort(403);
        }
        
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'required|string|max:255',
            'comment' => 'required|string',
        ]);
        
        $review->update([
            'rating' => $request->rating,
            'title' => $request->title,
            'comment' => $request->comment,
            'is_approved' => Auth::user()->isAdmin() ? $request->has('is_approved') : $review->is_approved,
        ]);
        
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.reviews.index')
                ->with('success', 'Review updated successfully.');
        }
        
        return redirect()->route('products.show', $review->product_id)
            ->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        // Check if user can delete this review
        if (!Auth::user()->isAdmin() && $review->user_id !== Auth::id()) {
            abort(403);
        }
        
        $productId = $review->product_id;
        
        $review->delete();
        
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.reviews.index')
                ->with('success', 'Review deleted successfully.');
        }
        
        return redirect()->route('products.show', $productId)
            ->with('success', 'Review deleted successfully.');
    }
    
    /**
     * Approve a review (admin only).
     */
    public function approve(Review $review)
    {
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }
        
        $review->update(['is_approved' => true]);
        
        return back()->with('success', 'Review approved successfully.');
    }
    
    /**
     * Check if the current user has purchased the product.
     */
    private function userHasPurchasedProduct($productId)
    {
        return Order::where('user_id', Auth::id())
            ->where('status', 'completed')
            ->whereHas('items', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->exists();
    }
}