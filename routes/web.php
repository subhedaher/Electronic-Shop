<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\auth\AuthController;
use App\Http\Controllers\auth\AuthUserController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\FavoriteProductController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LocalController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductDetailsController;
use App\Http\Controllers\ProductShopController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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



Route::prefix('')->middleware(['auth:user', 'verified'])->group(function () {
    Route::get('/profile/edit', [AuthUserController::class, 'editProfile'])->name('editProfile');
    Route::put('/profile/edit', [AuthUserController::class, 'updateProfile'])->name('updateProfile');
    Route::get('/password/edit', [AuthUserController::class, 'editPassword'])->name('editPassword');
    Route::put('/password/edit', [AuthUserController::class, 'updatePassword'])->name('updatePassword');
    Route::get('/logout', [AuthUserController::class, 'logout'])->name('logout');
    Route::get('products/favorite', [FavoriteProductController::class, 'index'])->name('favoriteProducts');
    Route::post('products/favorite', [FavoriteProductController::class, 'store'])->name('createFavoriteProducts');
    Route::delete('products/favorite/{product}', [FavoriteProductController::class, 'destroy'])->name('deleteFavoriteProducts');
    Route::post('products/cart/{product}', [CartController::class, 'store'])->name('createCartProducts');
    Route::get('carts', [CartController::class, 'index'])->name('cart');
    Route::delete('cart/{cart}', [CartController::class, 'destroy'])->name('deleteCart');
    Route::put('cart/{cart}', [CartController::class, 'update'])->name('updateCart');
    Route::get('cart/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('order', [CheckoutController::class, 'order'])->name('order');
    Route::get('orders', [CheckoutController::class, 'orders'])->name('orders');
    Route::post('/products/{product}/rating', [ProductDetailsController::class, 'rating'])->name('rating');
    Route::post('/coupon', [CheckoutController::class, 'coupon'])->name('coupon');
});

Route::prefix('')->group(function () {
    Route::get('', [IndexController::class, 'index'])->name('index');
    Route::get('/products/{product}', [ProductDetailsController::class, 'index'])->name('product');
    Route::get('/products', [ProductShopController::class, 'index'])->name('products');
    Route::get('/about', [AboutController::class, 'index'])->name('about');
    Route::post('/contact', [MessageController::class, 'message'])->name('message');
    Route::get('/contact', [MessageController::class, 'index'])->name('contact');
    Route::get('/register', [UserController::class, 'create'])->name('showRegister');
    Route::post('/register', [UserController::class, 'store'])->name('register');
    Route::get('/login', [AuthUserController::class, 'showLogin'])->name('showLogin');
    Route::post('/login', [AuthUserController::class, 'login'])->name('login');
    Route::post('/search', [IndexController::class, 'search'])->name('search');
    Route::get('/forgot', [AuthUserController::class, 'forgotPassword'])->name('forgotPassword');
    Route::post('password/forgot', [AuthUserController::class, 'requestResetPassword'])->name('sendEmail');
    Route::get('password/forgot/{token}', [AuthUserController::class, 'resetPassword'])->name('password.reset');
    Route::put('password/forgot', [AuthUserController::class, 'changePassword'])->name('auth.changePassword');
});


Route::prefix('cms')->middleware('guest:admin')->group(function () {
    Route::get('{guard}/login', [AuthController::class, 'showLogin'])->name('auth.showLogin');
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::get('password/forgot', [AuthController::class, 'forgotPassword'])->name('auth.forgotPassword');
    Route::post('password/forgot', [AuthController::class, 'requestResetPassword'])->name('auth.requestResetPassword');
    Route::get('password/forgot/{token}', [AuthController::class, 'resetPassword'])->name('password.reset');
    Route::put('password/forgot', [AuthController::class, 'changePassword'])->name('auth.changePassword');
});

Route::prefix('cms/admin')->middleware(['auth:admin', 'verified'])->group(function () {
    Route::get('', [HomeController::class, 'index'])->name('home');
    Route::resource('admins', AdminController::class);
    Route::get('/categories/search', [CategoryController::class, 'search'])->name('categories.search');
    Route::get('/categories/trash', [CategoryController::class, 'trash'])->name('categories.trash');
    Route::put('/categories/trash/{id}', [CategoryController::class, 'restore'])->name('categories.restore');
    Route::delete('/categories/trash/{id}', [CategoryController::class, 'forceDelete'])->name('categories.forceDelete');
    Route::resource('categories', CategoryController::class);
    Route::get('/products/trash', [ProductController::class, 'trash'])->name('products.trash');
    Route::put('/products/trash/{id}', [ProductController::class, 'restore'])->name('products.restore');
    Route::delete('/products/trash/{id}', [ProductController::class, 'forceDelete'])->name('products.forceDelete');
    Route::resource('products', ProductController::class);
    Route::resource('offers', OfferController::class);
    Route::get('profile/edit', [AuthController::class, 'editProfile'])->name('auth.editProfile');
    Route::put('profile/edit', [AuthController::class, 'updateProfile'])->name('auth.updateProfile');
    Route::get('password/edit', [AuthController::class, 'editPassword'])->name('auth.editPassword');
    Route::put('password/edit', [AuthController::class, 'updatePassword'])->name('auth.updatePassword');
    Route::get('messages', [MessageController::class, 'messages'])->name('messages');
    Route::delete('messages/{id}', [MessageController::class, 'delete'])->name('deleteMessage');
    Route::resource('coupons', CouponController::class);
    Route::get('orders-pdf', [PDFController::class, 'orders'])->name('pdf.orders');
    Route::get('orders-pdf/{order}', [PDFController::class, 'order'])->name('pdf.order');
    Route::get('orders', [CheckoutController::class, 'ordersForAdmin'])->name('ordersForAdmin');
    Route::get('orders/{order}', [CheckoutController::class, 'orderForAdmin'])->name('orderForAdmin');
    Route::put('orders/{order}/status/change', [CheckoutController::class, 'changeStatus'])->name('changeStatus');
    Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
});

Route::prefix('cms/admin')->middleware(['auth:admin', 'verified'])->group(function () {
    Route::resource('roles', RoleController::class);
    Route::put('permission', [RoleController::class, 'updateRolePermission'])->name('updateRolePermission');
});

Route::prefix('cms/email')->middleware(['auth:admin'])->group(function () {
    Route::get('verification', [AuthController::class, 'showEmailVerification'])->name('verification.notice');
    Route::post('verification', [AuthController::class, 'emailVerification'])->middleware('throttle:3,3')->name('auth.emailVerification');
    Route::get('verification/{id}/{hash}', [AuthController::class, 'verify'])->middleware('signed')->name('verification.verify');
});

Route::prefix('/')->middleware(['auth:user'])->group(function () {
    Route::get('verification', [AuthUserController::class, 'showEmailVerification'])->middleware('throttle:3,3')->name('verificationUser.notice');
    Route::get('verification/{id}/{hash}', [AuthUserController::class, 'verify'])->middleware('signed')->name('verificationUser.verify');
});

Route::get('local/{lang}', [LocalController::class, 'setLocal'])->name('local');