<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\StoresController;
use App\Http\Controllers\ProductCategoriesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\OrdersController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('public.welcome');
});

Route::middleware(['auth','admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('users', [UsersController::class, 'admin_index'])->name('users.index');
    Route::put('users/{id}/toggle-admin', [UsersController::class, 'admin_toggle_admin'])->name('users.toggle_admin');
    Route::get('stores', [StoresController::class, 'admin_index'])->name('stores.index');
    Route::get('stores/deactivated', [StoresController::class, 'admin_deactivated_index'])->name('stores.deactivated.index');
    Route::delete('stores/{id}', [StoresController::class, 'admin_destroy'])->name('stores.destroy');
    Route::get('stores/{id}/restore', [StoresController::class, 'admin_restore'])->name('stores.restore');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::resource('users', UsersController::class);
    Route::resource('product-categories', ProductCategoriesController::class);
    Route::put('product-categories/{id}/toggle-hide', [ProductCategoriesController::class,'toggle_hide'])->name('product-categories.toggle');
    Route::resource('products', ProductsController::class);
    Route::get('orders/my-orders', [OrdersController::class, 'my_orders'])->name('orders.my_orders');
    Route::get('orders/my-orders/{id}', [OrdersController::class, 'show_my_order'])->name('orders.show_my_order');
    Route::resource('orders', OrdersController::class);
    Route::put('orders/{id}/complete', [OrdersController::class, 'complete'])->name('orders.complete');
    Route::put('orders/{id}/cancel', [OrdersController::class, 'cancel'])->name('orders.cancel');
});

Auth::routes();

Route::middleware('store.status')->group(function () {
    Route::get('{client_id}', [StoresController::class, 'index'])->name('store.index');
    Route::get('store/{client_id}/cart', [StoresController::class, 'cart'])->name('store.cart');
    Route::get('store/{client_id}/cart/delete/{cart_id}', [StoresController::class, 'delete_cart'])->name('store.cart.delete');
    Route::put('store/{client_id}/cart/update', [StoresController::class, 'update_cart'])->name('store.cart.update');
    Route::get('store/{client_id}/checkout', [StoresController::class, 'checkout'])->name('store.checkout');
    Route::post('store/{client_id}/order', [StoresController::class, 'place_order'])->name('store.order.place');
    Route::get('store/{client_id}/{category_id}', [StoresController::class, 'get_by_category'])->name('store.by_category');
    Route::get('store/{client_id}/product/{product_id}', [StoresController::class, 'show_product'])->name('store.products.show');
    Route::get('store/{client_id}/product/{category_id}/{product_id}', [StoresController::class, 'show_product_with_category'])->name('store.products.show_with_category');
    Route::post('store/{client_id}/product/{product_id}/add-to-cart', [StoresController::class, 'add_to_cart'])->name('store.product.add_to_cart');
});