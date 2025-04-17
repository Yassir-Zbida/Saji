<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerOrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AddressController;



// Home and Static Pages
Route::get('/', [HomeController::class, 'index'])->name('ù');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');

// Policies and Legal Pages

Route::view('/privacy-policy', 'policies.privacy-policy')->name('privacy.policy');
Route::view('/terms-and-conditions', 'policies.terms')->name('terms');
Route::view('/shipping-policy', 'policies.shipping-policy')->name('shipping.policy');
Route::view('/return-policy', 'policies.return-policy')->name('return.policy');
Route::view('/refund-policy', 'policies.refund-policy')->name('refund.policy');
Route::view('/cookie-policy', 'policies.cookie-policy')->name('cookie.policy');

// Authentication Routes


// Authentication Routes
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

// Social Login Routes (if needed)
Route::get('/login/google', [App\Http\Controllers\Auth\LoginController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/login/google/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleGoogleCallback']);
Route::get('/login/facebook', [App\Http\Controllers\Auth\LoginController::class, 'redirectToFacebook'])->name('login.facebook');
Route::get('/login/facebook/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleFacebookCallback']);



// Shop Routes (Public)
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/filter', [ShopController::class, 'filter'])->name('shop.filter');
Route::get('/categories', [ShopController::class, 'categories'])->name('categories');
// Route::get('/category/{slug}', [ShopController::class, 'category'])->name('category.show');
Route::get('/product/{slug}', [ShopController::class, 'product'])->name('product.show');
Route::post('/product/quick-view', [ShopController::class, 'quickView'])->name('product.quick-view');

// Search
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::post('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

// Cart Routes (Public)
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/promo', [CartController::class, 'applyPromo'])->name('cart.promo');

// Admin and manager routes
Route::middleware(['auth', 'role:admin,manager'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Products Management
    Route::resource('products', ProductController::class);
    Route::post('/products/upload-image', [ProductController::class, 'uploadImage'])->name('products.upload-image');
    Route::delete('/products/remove-image/{id}', [ProductController::class, 'removeImage'])->name('products.remove-image');
    Route::post('/products/update-stock', [ProductController::class, 'updateStock'])->name('products.update-stock');
    
    // Categories Management
    // Route::resource('categories', CategoryController::class);
    
    // Orders Management
    Route::resource('orders', OrderController::class);
    Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');
    
    // Invoices Management
    Route::resource('invoices', InvoiceController::class);
    Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
    Route::post('/invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
    
    // Support Tickets
    Route::resource('support-tickets', SupportTicketController::class);
    Route::post('/support-tickets/{supportTicket}/add-response', [SupportTicketController::class, 'addResponse'])->name('support-tickets.add-response');
    Route::post('/support-tickets/{supportTicket}/close', [SupportTicketController::class, 'close'])->name('support-tickets.close');
    
    // Users Management
    Route::resource('users', UserController::class);
    
    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    
    // Reports
    Route::get('/reports/sales', [DashboardController::class, 'salesReport'])->name('reports.sales');
    Route::get('/reports/products', [DashboardController::class, 'productsReport'])->name('reports.products');
    Route::get('/reports/customers', [DashboardController::class, 'customersReport'])->name('reports.customers');
    Route::get('/reports/export/{type}', [DashboardController::class, 'exportReport'])->name('reports.export');
});

// Admin-only routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Coupons Management
    Route::resource('coupons', App\Http\Controllers\CouponController::class);
    
    // Tax Settings
    Route::get('/tax-settings', [SettingController::class, 'taxSettings'])->name('tax-settings.index');
    Route::post('/tax-settings', [SettingController::class, 'updateTaxSettings'])->name('tax-settings.update');
    
    // Shipping Settings
    Route::get('/shipping-settings', [SettingController::class, 'shippingSettings'])->name('shipping-settings.index');
    Route::post('/shipping-settings', [SettingController::class, 'updateShippingSettings'])->name('shipping-settings.update');
    
    // System Maintenance
    Route::get('/maintenance', [SettingController::class, 'maintenance'])->name('maintenance.index');
    Route::post('/maintenance/toggle', [SettingController::class, 'toggleMaintenance'])->name('maintenance.toggle');
    Route::post('/maintenance/clear-cache', [SettingController::class, 'clearCache'])->name('maintenance.clear-cache');
});



// Customer routes
Route::middleware(['auth', 'role:customer'])->group(function () {
    // Account Dashboard
    Route::get('/account', [AccountController::class, 'index'])->name('account');
    
    // Profile Management
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    
    // Customer Orders
    Route::get('/account/orders', [CustomerOrderController::class, 'index'])->name('account.orders');
    Route::get('/account/orders/{order}', [CustomerOrderController::class, 'show'])->name('account.orders.show');
    Route::post('/account/orders/{order}/cancel', [CustomerOrderController::class, 'cancel'])->name('account.orders.cancel');
    Route::get('/account/orders/{order}/invoice', [CustomerOrderController::class, 'invoice'])->name('account.orders.invoice');
    
    // Addresses
    Route::get('/account/addresses', [AddressController::class, 'index'])->name('account.addresses');
    Route::get('/account/addresses/create', [AddressController::class, 'create'])->name('account.addresses.create');
    Route::post('/account/addresses', [AddressController::class, 'store'])->name('account.addresses.store');
    Route::get('/account/addresses/{address}/edit', [AddressController::class, 'edit'])->name('account.addresses.edit');
    Route::put('/account/addresses/{address}', [AddressController::class, 'update'])->name('account.addresses.update');
    Route::delete('/account/addresses/{address}', [AddressController::class, 'destroy'])->name('account.addresses.destroy');
    Route::post('/account/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('account.addresses.default');
    
    // Wishlist
    // Route::get('/account/wishlist', [WishlistController::class, 'index'])->name('account.wishlist');
    // Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    // Route::delete('/wishlist/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    // Route::post('/wishlist/move-to-cart/{id}', [WishlistController::class, 'moveToCart'])->name('wishlist.move-to-cart');
    // Add this to your routes file
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::get('/cart', [cartController::class, 'index'])->name('wishlist');

    // Cart Routes
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/promo', [CartController::class, 'applyPromo'])->name('cart.promo');
    
    // Reviews
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
    
    // Support Tickets for Customers
    Route::middleware(['auth'])->group(function () {
        // Support Tickets for Customers
        Route::get('/account/support-tickets', [SupportTicketController::class, 'customerIndex'])->name('account.support-tickets');
        Route::get('/account/support-tickets/create', [SupportTicketController::class, 'customerCreate'])->name('account.support-tickets.create');
        Route::post('/account/support-tickets', [SupportTicketController::class, 'customerStore'])->name('account.support-tickets.store');
        Route::get('/account/support-tickets/{ticket}', [SupportTicketController::class, 'customerShow'])->name('account.support-tickets.show');
        Route::post('/account/support-tickets/{ticket}/reply', [SupportTicketController::class, 'customerReply'])->name('account.support-tickets.reply');
    });
    
});


// support agents dashbord
Route::middleware(['auth', 'role:support_agent'])->group(function () {
    Route::get('/dashboard/tickets', [SupportTicketController::class, 'index'])->name('dashboard.tickets.index');
});



// route for 404 error view 
Route::fallback(function () {
    return view('errors.404');
});