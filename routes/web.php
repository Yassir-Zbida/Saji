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

Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Route::get('/register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);

Route::get('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

// Goole Login Routes 
// Route::get('/login/google', [App\Http\Controllers\Auth\LoginController::class, 'redirectToGoogle'])->name('login.google');
// Route::get('/login/google/callback', [App\Http\Controllers\Auth\LoginController::class, 'handleGoogleCallback']);



// Customer Account Routes
Route::middleware(['auth', 'role:customer'])->group(function () {
    // Profile routes - this is the main account page
    Route::get('/account', [ProfileController::class, 'index'])->name('account.index');
    Route::get('/account/edit', [ProfileController::class, 'edit'])->name('account.edit');
    Route::put('/account', [ProfileController::class, 'update'])->name('account.update');
    
    // Address routes
    Route::get('/account/addresses', [ProfileController::class, 'addresses'])->name('account.addresses');
    Route::get('/account/addresses/create', [ProfileController::class, 'createAddress'])->name('account.addresses.create');
    Route::post('/account/addresses', [ProfileController::class, 'storeAddress'])->name('account.addresses.store');
    Route::get('/account/addresses/{address}/edit', [ProfileController::class, 'editAddress'])->name('account.addresses.edit');
    Route::put('/account/addresses/{address}', [ProfileController::class, 'updateAddress'])->name('account.addresses.update');
    Route::delete('/account/addresses/{address}', [ProfileController::class, 'destroyAddress'])->name('account.addresses.destroy');
    
    // Order routes
    Route::get('/account/orders', [ProfileController::class, 'orders'])->name('account.orders');
    Route::get('/account/orders/{order}', [ProfileController::class, 'showOrder'])->name('account.orders.show');

    // Support Tickets for customers
    Route::get('/account/tickets', [SupportTicketController::class, 'customerIndex'])->name('account.tickets');
    Route::get('/account/tickets/create', [SupportTicketController::class, 'customerCreate'])->name('account.tickets.create');
    Route::post('/account/tickets', [SupportTicketController::class, 'customerStore'])->name('account.tickets.store');
    Route::get('/account/tickets/{ticket}', [SupportTicketController::class, 'customerShow'])->name('account.tickets.show');
    Route::post('/account/tickets/{ticket}/reply', [SupportTicketController::class, 'customerReply'])->name('account.tickets.reply');
    Route::post('/account/tickets/{ticket}/close', [SupportTicketController::class, 'customerClose'])->name('account.tickets.close');
    Route::post('/account/tickets/{ticket}/reopen', [SupportTicketController::class, 'customerReopen'])->name('account.tickets.reopen');
    
    // Profile additional routes (these should point to the same controller)
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
});


// Shop Routes (Public)
// Route::get('/shop', [ShopController::class, 'index'])->name('shop');
// Route::get('/shop/filter', [ShopController::class, 'filter'])->name('shop.filter');
// Route::get('/categories', [ShopController::class, 'categories'])->name('categories');
// // Route::get('/category/{slug}', [ShopController::class, 'category'])->name('category.show');
// Route::get('/product/{slug}', [ShopController::class, 'product'])->name('product.show');
// Route::post('/product/quick-view', [ShopController::class, 'quickView'])->name('product.quick-view');

// Search
// Route::get('/search', [SearchController::class, 'index'])->name('search');
// Route::post('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

// Cart Routes (Public)
// Route::get('/cart', [CartController::class, 'index'])->name('cart');
// Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
// Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
// Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
// Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
// Route::post('/cart/promo', [CartController::class, 'applyPromo'])->name('cart.promo');

// // Admin and manager routes
// Route::middleware(['auth', 'role:admin,manager'])->group(function () {
//     // Dashboard
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
//     // Products Management
//     Route::resource('products', ProductController::class);
//     Route::post('/products/upload-image', [ProductController::class, 'uploadImage'])->name('products.upload-image');
//     Route::delete('/products/remove-image/{id}', [ProductController::class, 'removeImage'])->name('products.remove-image');
//     Route::post('/products/update-stock', [ProductController::class, 'updateStock'])->name('products.update-stock');
    
//     // Categories Management
//     // Route::resource('categories', CategoryController::class);
    
//     // Orders Management
//     Route::resource('orders', OrderController::class);
//     Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
//     Route::post('/orders/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');
    
//     // Invoices Management
//     Route::resource('invoices', InvoiceController::class);
//     Route::get('/invoices/{invoice}/download', [InvoiceController::class, 'download'])->name('invoices.download');
//     Route::post('/invoices/{invoice}/send', [InvoiceController::class, 'send'])->name('invoices.send');
    
//     // Support Tickets
//     Route::resource('support-tickets', SupportTicketController::class);
//     Route::post('/support-tickets/{supportTicket}/add-response', [SupportTicketController::class, 'addResponse'])->name('support-tickets.add-response');
//     Route::post('/support-tickets/{supportTicket}/close', [SupportTicketController::class, 'close'])->name('support-tickets.close');
    
//     // Users Management
//     Route::resource('users', UserController::class);
    
//     // Settings
//     Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
//     Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    
//     // Reports
//     Route::get('/reports/sales', [DashboardController::class, 'salesReport'])->name('reports.sales');
//     Route::get('/reports/products', [DashboardController::class, 'productsReport'])->name('reports.products');
//     Route::get('/reports/customers', [DashboardController::class, 'customersReport'])->name('reports.customers');
//     Route::get('/reports/export/{type}', [DashboardController::class, 'exportReport'])->name('reports.export');
// });

// Admin-only routes
// Route::middleware(['auth', 'role:admin'])->group(function () {
//     // Coupons Management
//     Route::resource('coupons', App\Http\Controllers\CouponController::class);
    
//     // Tax Settings
//     Route::get('/tax-settings', [SettingController::class, 'taxSettings'])->name('tax-settings.index');
//     Route::post('/tax-settings', [SettingController::class, 'updateTaxSettings'])->name('tax-settings.update');
    
//     // Shipping Settings
//     Route::get('/shipping-settings', [SettingController::class, 'shippingSettings'])->name('shipping-settings.index');
//     Route::post('/shipping-settings', [SettingController::class, 'updateShippingSettings'])->name('shipping-settings.update');
    
//     // System Maintenance
//     Route::get('/maintenance', [SettingController::class, 'maintenance'])->name('maintenance.index');
//     Route::post('/maintenance/toggle', [SettingController::class, 'toggleMaintenance'])->name('maintenance.toggle');
//     Route::post('/maintenance/clear-cache', [SettingController::class, 'clearCache'])->name('maintenance.clear-cache');
// });

// Error Handling

// 404 error view 
Route::fallback(function () {
    return view('errors.404');
});

// 403 Forbidden error view
Route::get('/403', function () {
    return view('errors.403');
})->name('forbidden');