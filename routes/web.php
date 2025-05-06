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
// use App\Http\Controllers\AccountController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ContentController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\AdminSupportTicketController;



// Home and Static Pages
Route::get('/', [HomeController::class, 'index'])->name('ù');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('contact.submit');
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



// Customer Account Routes
Route::middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/account', [ProfileController::class, 'index'])->name('account.index');
    Route::get('/account/edit', [ProfileController::class, 'edit'])->name('account.edit');
    Route::put('/account', [ProfileController::class, 'update'])->name('account.update');

    Route::get('/account/addresses', [ProfileController::class, 'addresses'])->name('account.addresses');
    Route::get('/account/addresses/create', [ProfileController::class, 'createAddress'])->name('account.addresses.create');
    Route::post('/account/addresses', [ProfileController::class, 'storeAddress'])->name('account.addresses.store');
    Route::get('/account/addresses/{address}/edit', [ProfileController::class, 'editAddress'])->name('account.addresses.edit');
    Route::put('/account/addresses/{address}', [ProfileController::class, 'updateAddress'])->name('account.addresses.update');
    Route::delete('/account/addresses/{address}', [ProfileController::class, 'destroyAddress'])->name('account.addresses.destroy');

    Route::get('/account/orders', [ProfileController::class, 'orders'])->name('account.orders');
    Route::get('/account/orders/{order}', [ProfileController::class, 'showOrder'])->name('account.orders.show');

    Route::get('/account/tickets', [SupportTicketController::class, 'customerIndex'])->name('account.tickets');
    Route::get('/account/tickets/create', [SupportTicketController::class, 'customerCreate'])->name('account.tickets.create');
    Route::post('/account/tickets', [SupportTicketController::class, 'customerStore'])->name('account.tickets.store');
    Route::get('/account/tickets/{ticket}', [SupportTicketController::class, 'customerShow'])->name('account.tickets.show');
    Route::post('/account/tickets/{ticket}/reply', [SupportTicketController::class, 'customerReply'])->name('account.tickets.reply');
    Route::post('/account/tickets/{ticket}/close', [SupportTicketController::class, 'customerClose'])->name('account.tickets.close');
    Route::post('/account/tickets/{ticket}/reopen', [SupportTicketController::class, 'customerReopen'])->name('account.tickets.reopen');

    // Route::get('/product/{id}', [ShopController::class, 'product'])->name('products.show');

    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
    Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
    Route::post('/wishlist/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');

    Route::post('/wishlist/{id}/move-to-cart', [WishlistController::class, 'moveToCart'])->name('wishlist.moveToCart');
    Route::post('/wishlist/{id}/update-notes', [WishlistController::class, 'updateNotes'])->name('wishlist.updateNotes');

    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/wishlist/check-products', [WishlistController::class, 'checkProducts'])->name('wishlist.checkProducts');
    Route::get('/wishlist/count', [WishlistController::class, 'getCount'])->name('wishlist.count');

});

// AJAX Cart Routes
Route::prefix('cart/ajax')->group(function () {
    Route::get('/get', [App\Http\Controllers\AjaxCartController::class, 'getCart']);
    Route::post('/add', [App\Http\Controllers\AjaxCartController::class, 'addToCart']);
    Route::post('/update/{id}', [App\Http\Controllers\AjaxCartController::class, 'updateCartItem']);
    Route::post('/remove/{id}', [App\Http\Controllers\AjaxCartController::class, 'removeCartItem']);
});

// Cart Routes
Route::post('/cart/ajax/add', [App\Http\Controllers\AjaxCartController::class, 'addToCart'])->name('cart.ajax.add');
Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
// Route::post('/cart/update/{id}', [App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
// Route::post('/cart/remove/{id}', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
// Route::post('/cart/clear', [App\Http\Controllers\CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/apply-coupon', [App\Http\Controllers\CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
Route::post('/cart/ajax/clear', [App\Http\Controllers\AjaxCartController::class, 'clearCart']);
Route::post('/cart/ajax/apply-coupon', [App\Http\Controllers\AjaxCartController::class, 'applyCoupon']);
Route::post('/cart/ajax/remove-coupon', [App\Http\Controllers\AjaxCartController::class, 'removeCoupon'])->name('cart.ajax.remove-coupon');

// Shop Routes
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/filter', [ShopController::class, 'filter'])->name('shop.filter');
Route::get('/shop/categories', [ShopController::class, 'categories'])->name('shop.categories');
Route::get('/shop/category/{slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/product/{slug}', [ShopController::class, 'product'])->name('products.show');
Route::get('/shop/quick-view', [ShopController::class, 'quickView'])->name('shop.quick-view');


// Checkout routes
// Route::prefix('checkout')->group(function () {
//     // Display checkout page
//     Route::get('/', [CheckoutController::class, 'index'])
//         ->name('checkout.index');

//     // Process checkout
//     Route::post('/process', [CheckoutController::class, 'process'])
//         ->name('checkout.process');

//     // Apply coupon
//     Route::post('/apply-coupon', [CheckoutController::class, 'applyCoupon'])
//         ->name('checkout.applyCoupon');

//     // Remove coupon
//     Route::post('/remove-coupon', [CheckoutController::class, 'removeCoupon'])
//         ->name('checkout.removeCoupon');

//     // Order complete page
//     Route::get('/complete/{order}', [CheckoutController::class, 'complete'])
//         ->name('checkout.complete');
// });

// Route::get('/cart/checkout', [CheckoutController::class, 'index'])
//     ->name('cart.checkout');

// Route::prefix('checkout')->group(function () {

//     Route::get('/payment/{order}', [CheckoutController::class, 'payment'])
//         ->name('checkout.payment');

//     Route::post('/create-payment-intent', [CheckoutController::class, 'createPaymentIntent'])
//         ->name('checkout.createPaymentIntent');

//     Route::post('/webhook', [StripeController::class, 'handleWebhook'])
//         ->name('checkout.webhook');
// });    



// Ajouter ces routes dans la section des routes de checkout existantes
// Route::prefix('checkout')->group(function () {
//     // Routes existantes...

//     // Afficher la page de paiement Stripe
//     Route::get('/payment/{order}', [CheckoutController::class, 'payment'])
//         ->name('checkout.payment');

//     // Créer une intention de paiement Stripe
//     Route::post('/create-payment-intent', [CheckoutController::class, 'createPaymentIntent'])
//         ->name('checkout.createPaymentIntent');

//     // Webhook Stripe
//     Route::post('/webhook', [StripeController::class, 'handleWebhook'])
//         ->name('checkout.webhook');
// });



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
// Checkout routes
Route::prefix('checkout')->group(function () {
    // Display checkout page
    Route::get('/', [CheckoutController::class, 'index'])
        ->name('checkout.index');

    // Process checkout
    Route::post('/process', [CheckoutController::class, 'process'])
        ->name('checkout.process');

    // Apply coupon
    Route::post('/apply-coupon', [CheckoutController::class, 'applyCoupon'])
        ->name('checkout.applyCoupon');

    // Remove coupon
    Route::post('/remove-coupon', [CheckoutController::class, 'removeCoupon'])
        ->name('checkout.removeCoupon');

    // Order complete page
    Route::get('/complete/{order}', [CheckoutController::class, 'complete'])
        ->name('checkout.complete');
});

Route::get('/cart/checkout', [CheckoutController::class, 'index'])
    ->name('cart.checkout');

// Routes Stripe

// Checkout routes
Route::middleware(['auth'])->group(function () {
    // Cart and checkout process
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::post('/cart/update/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

    // Checkout process
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/payment/{order}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::get('/checkout/cancel/{order}', [CheckoutController::class, 'cancel'])->name('checkout.cancel');
    Route::get('/checkout/complete/{order}', [CheckoutController::class, 'complete'])->name('checkout.complete');

    // Stripe routes
    Route::get('/stripe/checkout/{order}', [StripeController::class, 'checkout'])->name('stripe.checkout');
    Route::get('/stripe/success/{order}', [StripeController::class, 'success'])->name('stripe.success');
    Route::get('/stripe/cancel/{order}', [StripeController::class, 'cancel'])->name('stripe.cancel');
});

// Stripe webhook (no auth middleware)
Route::post('/stripe/webhook', [StripeController::class, 'handleWebhook'])->name('stripe.webhook');

// // User account routes
// Route::middleware(['auth'])->prefix('account')->group(function () {
//     Route::get('/orders', [AccountController::class, 'orders'])->name('account.orders');
//     Route::get('/orders/{order}', [AccountController::class, 'orderDetail'])->name('account.orders.detail');
// });



// Error Handling

// 404 error view 
// Route::fallback(function () {
//     return view('errors.404');
// });

// 403 Forbidden error view
Route::get('/403', function () {
    return view('errors.403');
})->name('forbidden');

























/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register admin routes for your application.
|
*/

// Admin group with auth middleware
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Dashboard AJAX endpoints
    Route::get('/dashboard/top-products', [DashboardController::class, 'getTopProducts']);
    Route::get('/dashboard/summary', [DashboardController::class, 'getDashboardSummary']);
    Route::get('/dashboard/sales-data', [DashboardController::class, 'getSalesData'])->name('admin.dashboard.sales-data');

    // Analytics
    Route::get('/analytics', [DashboardController::class, 'analytics'])->name('admin.analytics');

    // Products
    Route::get('/products', [DashboardController::class, 'adminAllProducts'])->name('admin.products');
    Route::get('/products/create', [ProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::delete('/products/images/{id}', [ProductController::class, 'removeImage'])->name('admin.products.images.destroy');
    Route::patch('/products/{product}/status', [ProductController::class, 'updateStatus']);
    Route::get('/products/data', [DashboardController::class, 'getProductsData'])->name('admin.products.data');
    Route::get('/categories/data', [DashboardController::class, 'getCategoriesData'])->name('admin.categories.data');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::get('/products/export', [App\Http\Controllers\DashboardController::class, 'exportProducts'])->name('admin.products.export');


    // Categories management
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories');
    Route::get('/categories/data', [CategoryController::class, 'getCategoriesData'])->name('admin.categories.data');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('admin.categories.destroy');
    Route::patch('/categories/{category}/position', [CategoryController::class, 'updatePosition'])->name('admin.categories.update-position');
    Route::get('/categories/export', [CategoryController::class, 'export'])->name('admin.categories.export');


    Route::get('/tags', [TagController::class, 'index'])->name('admin.tags');
    Route::get('/tags/create', [TagController::class, 'create'])->name('tags.create');
    Route::post('/tags', [TagController::class, 'store'])->name('tags.store');
    Route::get('/tags/{tag}/edit', [TagController::class, 'edit'])->name('tags.edit');
    Route::put('/tags/{tag}', [TagController::class, 'update'])->name('tags.update');
    Route::delete('/tags/{tag}', [TagController::class, 'destroy'])->name('tags.destroy');
    Route::get('/tags/data', [TagController::class, 'data'])->name('tags.data');
    Route::get('/tags/export', [TagController::class, 'export'])->name('tags.export');

    // Orders
    Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
    Route::get('/orders/data', [OrdersController::class, 'getOrdersData'])->name('orders.data');
    Route::get('/orders/{order}', [OrdersController::class, 'show'])->name('orders.show');
    Route::get('/orders/{id}/invoice', [OrdersController::class, 'invoice'])->name('orders.invoice');
    Route::patch('/orders/{id}/status', [OrdersController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('/orders/{id}/payment', [OrdersController::class, 'updatePaymentStatus'])->name('orders.update-payment');
    Route::delete('/orders/{order}', [OrdersController::class, 'destroy'])->name('orders.destroy');

    Route::get('/customers/export', [CustomersController::class, 'export'])->name('admin.customers.export');
    Route::get('/customers/statistics', [CustomersController::class, 'getStatistics'])->name('admin.customers.statistics');
    Route::get('/customers', [CustomersController::class, 'index'])->name('admin.customers');
    Route::get('/customers/create', [CustomersController::class, 'create'])->name('admin.customers.create');
    Route::post('/customers', [CustomersController::class, 'store'])->name('admin.customers.store');
    Route::get('/customers/{customer}', [CustomersController::class, 'show'])->name('admin.customers.show');
    Route::get('/customers/{customer}/edit', [CustomersController::class, 'edit'])->name('admin.customers.edit');
    Route::put('/customers/{customer}', [CustomersController::class, 'update'])->name('admin.customers.update');
    Route::delete('/customers/{customer}', [CustomersController::class, 'destroy'])->name('admin.customers.destroy');
    Route::get('/customers-data', [CustomersController::class, 'getCustomers'])->name('admin.customers.data');
    Route::get('/customers/{customer}/details', [CustomersController::class, 'getCustomerDetails'])->name('admin.customers.details');
    // Route::post('/customers/bulk-action', [CustomersController::class, 'bulkActionAjax'])->name('admin.customers.bulk-action');
    Route::post('/customers/{customer}/verify-email', [CustomersController::class, 'verifyEmail'])->name('admin.customers.verify-email');
    Route::post('/customers/{customer}/reset-password', [CustomersController::class, 'resetPassword'])->name('admin.customers.reset-password');
    Route::get('/impersonate/{customer}', [CustomersController::class, 'impersonate'])->name('admin.impersonate');
    Route::get('/stop-impersonating', [CustomersController::class, 'stopImpersonating'])->name('admin.stop-impersonating');
    
    // Admin Ticket views
    Route::get('/tickets', [AdminSupportTicketController::class, 'index'])->name('admin.tickets.index');
    Route::get('/tickets/create', [AdminSupportTicketController::class, 'create'])->name('admin.tickets.create');
    
    // Admin Ticket API endpoints for AJAX
    Route::get('/tickets/data', [AdminSupportTicketController::class, 'getTicketsData'])->name('admin.tickets.data');
    Route::get('/tickets/stats', [AdminSupportTicketController::class, 'getStats'])->name('admin.tickets.stats');
    Route::post('/tickets', [AdminSupportTicketController::class, 'store'])->name('admin.tickets.store');

    
    // These routes must come after the specific routes above
    Route::get('/tickets/{id}/edit', [AdminSupportTicketController::class, 'edit'])->name('admin.tickets.edit');
    Route::get('/tickets/{id}', [AdminSupportTicketController::class, 'show'])->name('admin.tickets.show');
    Route::put('/tickets/{id}', [AdminSupportTicketController::class, 'update'])->name('admin.tickets.update');
    Route::delete('/tickets/{id}', [AdminSupportTicketController::class, 'destroy'])->name('admin.tickets.destroy');
    Route::post('/tickets/{id}/response', [AdminSupportTicketController::class, 'addResponse'])->name('admin.tickets.response');
    Route::patch('/tickets/{id}/status', [AdminSupportTicketController::class, 'updateStatus'])->name('admin.tickets.status');
    Route::patch('/tickets/{id}/priority', [AdminSupportTicketController::class, 'updatePriority'])->name('admin.tickets.priority');
    Route::post('/tickets/{id}/close', [AdminSupportTicketController::class, 'close'])->name('admin.tickets.close');
    Route::post('/tickets/{id}/reopen', [AdminSupportTicketController::class, 'reopen'])->name('admin.tickets.reopen');

    Route::get('/content', [ContentController::class, 'index'])->name('admin.content');
    Route::get('/marketing', [MarketingController::class, 'index'])->name('admin.marketing');

    // Coupons
    Route::get('/coupons', [CouponController::class, 'index'])->name('admin.coupons');
    Route::get('/coupons-data', [CouponController::class, 'getCouponsData'])->name('admin.coupons.data');
    Route::get('/coupons/statistics', [CouponController::class, 'getStatistics'])->name('admin.coupons.statistics');
    Route::get('/coupons/create', [CouponController::class, 'create'])->name('admin.coupons.create');
    Route::post('/coupons', [CouponController::class, 'store'])->name('admin.coupons.store');
    Route::get('/coupons/{id}/details', [CouponController::class, 'details'])->name('admin.coupons.details');
    Route::get('/coupons/{id}/edit', [CouponController::class, 'edit'])->name('admin.coupons.edit');
    Route::put('/coupons/{id}', [CouponController::class, 'update'])->name('admin.coupons.update');
    Route::delete('/coupons/{id}', [CouponController::class, 'destroy'])->name('admin.coupons.destroy');
    Route::post('/coupons/{id}/toggle-status', [CouponController::class, 'toggleStatus'])->name('admin.coupons.toggle-status');
    Route::get('/coupons/export', [CouponController::class, 'export'])->name('admin.coupons.export');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('admin.settings');
});