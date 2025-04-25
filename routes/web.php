<?php

use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AjaxCartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group.
|
*/

// Home and Static Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');

// Policies and Legal Pages
Route::prefix('policies')->group(function () {
    Route::view('/privacy-policy', 'policies.privacy-policy')->name('privacy.policy');
    Route::view('/terms-and-conditions', 'policies.terms')->name('terms');
    Route::view('/shipping-policy', 'policies.shipping-policy')->name('shipping.policy');
    Route::view('/return-policy', 'policies.return-policy')->name('return.policy');
    Route::view('/refund-policy', 'policies.refund-policy')->name('refund.policy');
    Route::view('/cookie-policy', 'policies.cookie-policy')->name('cookie.policy');
});

// Shop Routes
Route::prefix('shop')->group(function () {
    Route::get('/', [ShopController::class, 'index'])->name('shop.index');
    Route::get('/filter', [ShopController::class, 'filter'])->name('shop.filter');
    Route::get('/categories', [ShopController::class, 'categories'])->name('shop.categories');
    Route::get('/category/{slug}', [ShopController::class, 'category'])->name('shop.category');
    Route::get('/quick-view', [ShopController::class, 'quickView'])->name('shop.quick-view');
});

// Product Routes
Route::get('/product/{slug}', [ShopController::class, 'product'])->name('products.show');

// Search Routes
Route::prefix('search')->group(function () {
    Route::get('/', [SearchController::class, 'index'])->name('search');
    Route::post('/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');
});

// Cart Routes
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::post('/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('cart.checkout');
    
    // AJAX Cart Routes
    Route::prefix('ajax')->group(function () {
        Route::get('/get', [AjaxCartController::class, 'getCart']);
        Route::post('/add', [AjaxCartController::class, 'addToCart'])->name('cart.ajax.add');
        Route::post('/update/{id}', [AjaxCartController::class, 'updateCartItem']);
        Route::post('/remove/{id}', [AjaxCartController::class, 'removeCartItem']);
        Route::post('/clear', [AjaxCartController::class, 'clearCart']);
        Route::post('/apply-coupon', [AjaxCartController::class, 'applyCoupon']);
        Route::post('/remove-coupon', [AjaxCartController::class, 'removeCoupon'])->name('cart.ajax.remove-coupon');
    });
});

// Checkout Routes
Route::prefix('checkout')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::post('/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.applyCoupon');
    Route::post('/remove-coupon', [CheckoutController::class, 'removeCoupon'])->name('checkout.removeCoupon');
    Route::get('/complete/{order}', [CheckoutController::class, 'complete'])->name('checkout.complete');
});

// Authentication Routes
Route::prefix('auth')->group(function () {
    Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
    Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
    
    Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
    
    Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
});

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    // Profile Routes
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
        Route::post('/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    });
    
    // Stripe Routes
    Route::prefix('stripe')->group(function () {
        Route::get('/checkout/{order}', [StripeController::class, 'checkout'])->name('stripe.checkout');
        Route::get('/success/{order}', [StripeController::class, 'success'])->name('stripe.success');
        Route::get('/cancel/{order}', [StripeController::class, 'cancel'])->name('stripe.cancel');
    });
    
    // Customer-only Routes
    Route::middleware('role:customer')->group(function () {
        // Account Routes
        Route::prefix('account')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('account.index');
            Route::get('/edit', [ProfileController::class, 'edit'])->name('account.edit');
            Route::put('/', [ProfileController::class, 'update'])->name('account.update');
            
            // Addresses
            Route::prefix('addresses')->group(function () {
                Route::get('/', [ProfileController::class, 'addresses'])->name('account.addresses');
                Route::get('/create', [ProfileController::class, 'createAddress'])->name('account.addresses.create');
                Route::post('/', [ProfileController::class, 'storeAddress'])->name('account.addresses.store');
                Route::get('/{address}/edit', [ProfileController::class, 'editAddress'])->name('account.addresses.edit');
                Route::put('/{address}', [ProfileController::class, 'updateAddress'])->name('account.addresses.update');
                Route::delete('/{address}', [ProfileController::class, 'destroyAddress'])->name('account.addresses.destroy');
            });
            
            // Orders
            Route::prefix('orders')->group(function () {
                Route::get('/', [ProfileController::class, 'orders'])->name('account.orders');
                Route::get('/{order}', [ProfileController::class, 'showOrder'])->name('account.orders.show');
            });
            
            // Support Tickets
            Route::prefix('tickets')->group(function () {
                Route::get('/', [SupportTicketController::class, 'customerIndex'])->name('account.tickets');
                Route::get('/create', [SupportTicketController::class, 'customerCreate'])->name('account.tickets.create');
                Route::post('/', [SupportTicketController::class, 'customerStore'])->name('account.tickets.store');
                Route::get('/{ticket}', [SupportTicketController::class, 'customerShow'])->name('account.tickets.show');
                Route::post('/{ticket}/reply', [SupportTicketController::class, 'customerReply'])->name('account.tickets.reply');
                Route::post('/{ticket}/close', [SupportTicketController::class, 'customerClose'])->name('account.tickets.close');
                Route::post('/{ticket}/reopen', [SupportTicketController::class, 'customerReopen'])->name('account.tickets.reopen');
            });
        });
        
        // Wishlist Routes
        Route::prefix('wishlist')->group(function () {
            Route::get('/', [WishlistController::class, 'index'])->name('wishlist.index');
            Route::post('/add', [WishlistController::class, 'add'])->name('wishlist.add');
            Route::delete('/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
            Route::post('/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');
            Route::post('/{id}/move-to-cart', [WishlistController::class, 'moveToCart'])->name('wishlist.moveToCart');
            Route::post('/{id}/update-notes', [WishlistController::class, 'updateNotes'])->name('wishlist.updateNotes');
            Route::post('/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
            Route::post('/check-products', [WishlistController::class, 'checkProducts'])->name('wishlist.checkProducts');
            Route::get('/count', [WishlistController::class, 'getCount'])->name('wishlist.count');
        });
    });
});

// Stripe Webhook (no auth middleware)
Route::post('/stripe/webhook', [StripeController::class, 'handleWebhook'])->name('stripe.webhook');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register routes for your admin area.
|
*/

Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard/top-products', [DashboardController::class, 'getTopProducts']);
    Route::get('/dashboard/summary', [DashboardController::class, 'getDashboardSummary']);
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('admin.analytics');
    
    // Products
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('admin.products');
        Route::get('/create', [ProductController::class, 'create'])->name('admin.products.create');
        Route::post('/', [ProductController::class, 'store'])->name('admin.products.store');
        Route::get('/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
        Route::put('/{product}', [ProductController::class, 'update'])->name('admin.products.update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
        Route::patch('/{product}/status', [ProductController::class, 'updateStatus']);
    });
    
    // Categories
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('admin.categories');
        Route::get('/create', [CategoryController::class, 'create'])->name('admin.categories.create');
        Route::post('/', [CategoryController::class, 'store'])->name('admin.categories.store');
        Route::get('/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
        Route::put('/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
        Route::delete('/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    });
    
    // Orders
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('admin.orders');
        Route::get('/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
        Route::patch('/{order}/status', [OrderController::class, 'updateStatus']);
    });
    
    // Customers
    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('admin.customers');
        Route::get('/{customer}', [CustomerController::class, 'show'])->name('admin.customers.show');
        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('admin.customers.edit');
        Route::put('/{customer}', [CustomerController::class, 'update'])->name('admin.customers.update');
    });
    
    // Support Tickets
    Route::prefix('tickets')->group(function () {
        Route::get('/', [TicketController::class, 'index'])->name('admin.tickets');
        Route::get('/{ticket}', [TicketController::class, 'show'])->name('admin.tickets.show');
        Route::post('/{ticket}/reply', [TicketController::class, 'reply'])->name('admin.tickets.reply');
        Route::patch('/{ticket}/status', [TicketController::class, 'updateStatus']);
    });
    
    // Content Management
    Route::get('/content', [ContentController::class, 'index'])->name('admin.content');
    
    // Marketing
    Route::get('/marketing', [MarketingController::class, 'index'])->name('admin.marketing');
    
    // Discounts
    Route::get('/discounts', [DiscountController::class, 'index'])->name('admin.discounts');
    
    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
});

// Error Handling
Route::fallback(function () {
    return view('errors.404');
});

Route::get('/403', function () {
    return view('errors.403');
})->name('forbidden');