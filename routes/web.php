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



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
require __DIR__.'/auth.php';

// Route::get('/', [RoutingController::class, 'index'])->name('root');
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/home', [HomeController::class, 'index']);


    // Cart routes (Updated to use correct method names)
    Route::middleware('auth')->group(function () {
        // Category Setup routes
        Route::get('/categorysetup', [CategoryController::class, 'index'])->name('categorysetup');
        Route::get('/category/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
        Route::post('/category', [CategoryController::class, 'store'])->name('category.store');
        Route::post('/category/{id}', [CategoryController::class, 'update'])->name('category.update');
        Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

        // Product store, update & delete endpoints
        Route::post('/products/store', [AppController::class, 'storeMenuItem'])->name('products.store');
        Route::post('/products-save', [MenuSectionController::class, 'saveProduct'])->name('saveProduct');
        Route::post('/products/update/{id}', [AppController::class, 'updateMenuItem'])->name('products.update');
        Route::delete('/products/delete/{id}', [AppController::class, 'deleteMenuItem'])->name('products.delete');
        Route::post('/admin/delivery-settings/save', [DeliverySettingsController::class, 'saveDeliverySettings'])->name('delivery-settings.save');

        // Cart routes - MUST come before generic routing

        // Rating routes - MUST come before generic routing
        Route::post('/ratings', [RatingController::class, 'store'])->name('ratings.store');
        Route::get('/ratings/{menuItemId}', [RatingController::class, 'getItemRatings'])->name('ratings.getItemRatings');
        Route::get('/ratings/{menuItemId}/user', [RatingController::class, 'getUserRating'])->name('ratings.getUserRating');


        // Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
        // Route::put('/cart/{id}/quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
        // Route::delete('/cart/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
        // Route::delete('/cart', [CartController::class, 'clearCart'])->name('cart.clear');
        // Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
    });
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/category/{id}/products', [CategoryController::class, 'showProducts'])->name('category.products');
    Route::get('/category/{id}/products', function ($id) {
        $category = MenuSection::findOrFail($id);
        $products = $category->menuItems; // Assuming a relationship exists
        return view('category.products', compact('category', 'products'));
    })->name('category.products');

    Route::get('/client/cart', [CartController::class, 'index'])->name('client.cart');
    Route::get('/cart/side', [CartController::class, 'side'])->name('cart.side');
    Route::get('/client/wishlist', [WishlistController::class, 'index'])->name('client.wishlist');
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/{id}/quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    Route::delete('/cart/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::delete('/cart', [CartController::class, 'clearCart'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
    Route::get('/cart/debug', [CartController::class, 'debug'])->name('cart.debug'); // Temporary debug route


    // Checkout routes
    Route::middleware('auth')->group(function () {
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
        Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
        Route::get('/checkout/payment/{order}', [CheckoutController::class, 'payment'])->name('checkout.payment');
        Route::post('/checkout/payment/{order}/complete', [CheckoutController::class, 'completePayment'])->name('checkout.payment.complete');
        Route::get('/checkout/payment/{order}/cancel', [CheckoutController::class, 'cancelPayment'])->name('checkout.payment.cancel');
        Route::get('/checkout/confirmation/{order}', [CheckoutController::class, 'confirmation'])->name('order.confirmation');
        Route::post('/checkout/calculate-delivery-fee', [CheckoutController::class, 'calculateDeliveryFeeAjax'])->name('checkout.calculate-delivery-fee');
    });


Route::get('/my-orders', [OrderController::class, 'myOrders'])
    ->middleware('auth') // optional but recommended
    ->name('orders.index');

Route::get('/my-orders/{order}', [OrderController::class, 'show'])
    ->middleware('auth')
    ->name('orders.show');

Route::middleware('auth')->get('/client/MyOrders', [OrderController::class, 'myOrders']);
    // Order tracking routes
    Route::middleware('auth')->group(function () {
            Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.index');
    Route::get('/my-orders/{order}', [OrderController::class, 'show'])->name('orders.show');
       Route::patch('/my-orders/{order}/cancel', [OrderController::class, 'cancel'])
        ->name('orders.cancel');

        Route::get('/orders/{order}/track', [CheckoutController::class, 'trackOrder'])->name('orders.track');
        Route::get('/orders/history', [CheckoutController::class, 'orderHistory'])->name('orders.history');
    });
Route::middleware('auth')->group(function () {
    Route::get('/my-orders', [OrderController::class, 'myOrders'])
        ->name('orders.index');

    Route::get('/my-orders/{order}', [OrderController::class, 'show'])
        ->name('orders.show');
});
    // Admin Order Management routes
  Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/export', [App\Http\Controllers\Admin\OrderController::class, 'export'])->name('orders.export');
        Route::get('/orders/data', [App\Http\Controllers\Admin\OrderController::class, 'getData'])->name('orders.data');
        Route::get('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('/orders/{order}/payment-status', [App\Http\Controllers\Admin\OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');
        Route::delete('/orders/{order}', [App\Http\Controllers\Admin\OrderController::class, 'destroy'])->name('orders.destroy');
    });


    Route::get('{first}/{second}/{third}', [RoutingController::class, 'thirdLevel'])->name('third');
    Route::get('{first}/{second}', [RoutingController::class, 'secondLevel'])->name('second');
    Route::get('{any}', [RoutingController::class, 'root'])->name('any');

Route::get('/category/{id}/products', [CategoryController::class, 'showProducts'])->name('category.products');
Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');

Route::post('/cart/update/{id}', [CartController::class, 'updateQuantity'])->name('cart.update');




Route::get('/category/{id}/products', [CategoryController::class, 'getProducts'])->name('category.products');
// Route::get('/products/filter', [ProductController::class, 'filterByOrderType'])->name('products.filter');

Route::get('/admin/delivery-settings', [AdminController::class, 'deliverySettings'])->name('admin.delivery-settings');
Route::post('/admin/delivery-settings', [AdminController::class, 'saveDeliverySettings']);

Route::get('/admin/currency-settings', [CurrencyController::class, 'index'])
    ->name('admin.currency-settings');

// Route::get('/admin/currency-settings', [\App\Http\Controllers\Admin\CurrencyController::class, 'index'])->name('admin.currency-settings');
// Route::post('/admin/currency-settings', [\App\Http\Controllers\Admin\CurrencyController::class, 'store'])->name('admin.currency-settings.store');
// Route::delete('/admin/currency-settings/{id}', [\App\Http\Controllers\Admin\CurrencyController::class, 'destroy'])->name('admin.currency-settings.destroy');

    // Route::get('/categorydelete',[CategorySetupController::class,'categorydelete']);
    // Route::get('/itemcategorysetup',[CategorySetupController::class,'itemcategorysetup']);
    // Route::get('/categorygetdata',[CategorySetupController::class,'categorygetdata'])->name('categorygetdata');
    // Route::get('/categorysearch',[CategorySetupController::class,'categorysearch'])->name('categorysearch');


Route::middleware(['auth','admin']) // optional middleware
    ->prefix('admin')
    ->name('admin.')
    ->group(function() {
        Route::get('currency-settings', [CurrencyController::class, 'index'])
             ->name('currency-settings.index');

        Route::post('currency-settings', [CurrencyController::class, 'store'])
             ->name('currency-settings.store');

        Route::put('currency-settings/{id}', [CurrencyController::class, 'update'])
        ->name('currency-settings.update');// ADD THIS LINE

        Route::delete('currency-settings/{id}', [CurrencyController::class, 'destroy'])
             ->name('currency-settings.destroy');
             Route::patch('currency-settings/{id}/default', [CurrencyController::class, 'setDefault'])
            ->name('currency-settings.default');
            Route::get('currency-settings/{id}/edit', [CurrencyController::class, 'edit'])->name('currency-settings.edit');
    });
// Add these routes
Route::get('/cart/data', [CartController::class, 'getCartData'])->name('cart.data');
Route::get('/checkout/data', [CheckoutController::class, 'getCheckoutData'])->name('checkout.data');
Route::get('/checkout/order-summary', [CheckoutController::class, 'getOrderSummary'])->name('checkout.order-summary');
Route::get('/checkout/cart-items', [CheckoutController::class, 'getCartItems'])->name('checkout.cart-items');
