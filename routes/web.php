<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoutingController;
use App\Http\Controllers\CategorySetupController;
use App\Http\Controllers\Controller as AppController;
use App\Models\MenuSection;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MenuSectionController;
use App\Helper\RestaurantHelper;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\DeliverySettingsController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\VendorSetupController;

require __DIR__.'/auth.php';

// ✅ HOME ROUTES
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index']);

// ✅ AUTH ROUTES
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');

// ✅ CART ROUTES
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::get('/client/cart', [CartController::class, 'index'])->name('client.cart');
Route::get('/cart/side', [CartController::class, 'side'])->name('cart.side');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::put('/cart/{id}/quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
Route::delete('/cart/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::delete('/cart', [CartController::class, 'clearCart'])->name('cart.clear');
Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
Route::get('/cart/debug', [CartController::class, 'debug'])->name('cart.debug');

// ✅ CATEGORY ROUTES
Route::get('/category/{id}/products', [CategoryController::class, 'showProducts'])->name('category.products');
Route::get('/category/{id}/products', [CategoryController::class, 'getProducts'])->name('category.products');

// ✅ AUTHENTICATED ROUTES
Route::middleware('auth')->group(function () {
    // Category Setup routes
    Route::get('/categorysetup', [CategoryController::class, 'index'])->name('categorysetup');
    Route::get('/category/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
    Route::post('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

    // Product routes
    Route::post('/products/store', [AppController::class, 'storeMenuItem'])->name('products.store');
    Route::post('/products-save', [MenuSectionController::class, 'saveProduct'])->name('saveProduct');
    Route::post('/products/update/{id}', [AppController::class, 'updateMenuItem'])->name('products.update');
    Route::delete('/products/delete/{id}', [AppController::class, 'deleteMenuItem'])->name('products.delete');
    Route::post('/admin/delivery-settings/save', [DeliverySettingsController::class, 'saveDeliverySettings'])->name('delivery-settings.save');

    // Rating routes
    Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');
    Route::get('/ratings/{menuItemId}', [RatingController::class, 'getItemRatings'])->name('ratings.getItemRatings');
    Route::get('/ratings/{menuItemId}/user', [RatingController::class, 'getUserRating'])->name('ratings.getUserRating');

    // Checkout routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/payment/{order}', [CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::post('/checkout/payment/{order}/complete', [CheckoutController::class, 'completePayment'])->name('checkout.payment.complete');
    Route::get('/checkout/payment/{order}/cancel', [CheckoutController::class, 'cancelPayment'])->name('checkout.payment.cancel');
    Route::get('/checkout/confirmation/{order}', [CheckoutController::class, 'confirmation'])->name('order.confirmation');
    Route::post('/checkout/calculate-delivery-fee', [CheckoutController::class, 'calculateDeliveryFeeAjax'])->name('checkout.calculate-delivery-fee');

    // Order routes
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.index');
    Route::get('/my-orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::patch('/my-orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/client/MyOrders', [OrderController::class, 'myOrders']);
    Route::get('/orders/{order}/track', [CheckoutController::class, 'trackOrder'])->name('orders.track');
    Route::get('/orders/history', [CheckoutController::class, 'orderHistory'])->name('orders.history');
});

// ✅ ADMIN ROUTES - MUST COME BEFORE CATCH-ALL ROUTES
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Delivery settings
    Route::get('/delivery-settings', [AdminController::class, 'deliverySettings'])->name('delivery-settings');
    Route::post('/delivery-settings', [AdminController::class, 'saveDeliverySettings'])->name('delivery-settings.save');

    // Currency settings
    Route::get('currency-settings', [CurrencyController::class, 'index'])->name('currency-settings.index');
    Route::post('currency-settings', [CurrencyController::class, 'store'])->name('currency-settings.store');
    Route::delete('currency-settings/{id}', [CurrencyController::class, 'destroy'])->name('currency-settings.destroy');
    Route::patch('currency-settings/{id}/default', [CurrencyController::class, 'setDefault'])->name('currency-settings.default');

    // Orders
    Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/export', [App\Http\Controllers\Admin\OrderController::class, 'export'])->name('orders.export');
    Route::get('/orders/data', [App\Http\Controllers\Admin\OrderController::class, 'getData'])->name('orders.data');
    Route::get('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/payment-status', [App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');
    Route::delete('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('orders.destroy');

    Route::resource('vendors', \App\Http\Controllers\Admin\VendorSetupController::class);
});


Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
Route::post('/cart/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');
Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
Route::get('/client/wishlist', [WishlistController::class, 'index'])->name('client.wishlist');

Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
Route::get('{any}', [RoutingController::class, 'root'])->name('any');
Route::get('/cart/data', [CartController::class, 'getCartData'])->name('cart.data');
Route::get('/checkout/data', [CheckoutController::class, 'getCheckoutData'])->name('checkout.data');
Route::get('/checkout/order-summary', [CheckoutController::class, 'getOrderSummary'])->name('checkout.order-summary');
Route::get('/checkout/cart-items', [CheckoutController::class, 'getCartItems'])->name('checkout.cart-items');
