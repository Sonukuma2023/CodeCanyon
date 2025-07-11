<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\cart\CartController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ProfileController;
 

Route::middleware(['auth', 'role:user'])
    ->name('user.')
    ->group(function () {
        Route::get('/dashboard', [UserController::class, 'dashboard'])->name('dashboard');

        Route::get('/product/{id}', [UserController::class, 'singleproduct'])->name('singleproduct');

        Route::post('/addreview', [UserController::class, 'submitReview'])->name('submitreview');


        Route::post('/logout', function () {
            Auth::logout();
            return redirect('/');
        })->name('logout');

    });

    Route::get('/profile/edit', [UserController::class, 'edit'])->name('profile.edit');

    Route::middleware(['auth'])->group(function () {
        Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.index');
        Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
        Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

        Route::post('/cart/increase/{id}', [CartController::class, 'increase'])->name('cart.increase');
        Route::post('/cart/decrease/{id}', [CartController::class, 'decrease'])->name('cart.decrease');
        Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('coupon.apply');

        Route::get('/checkout', [CartController::class, 'index1'])->name('checkout');
        Route::post('/checkout', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
        Route::get('/checkout-success/{order}', [OrderController::class, 'checkoutSuccess'])->name('checkout.success');
        Route::get('/orders', [OrderController::class, 'index']) ->name('user.orders')->middleware('auth');
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::get('/orders', [OrderController::class, 'index'])->name('orders');

		Route::get('/chat-with-admin', [UserController::class, 'messagePage'])->name('user.messagePage');
		Route::get('/fetch/messages', [UserController::class, 'fetchMessages'])->name('user.fetchMessages');
		Route::post('/save/chat', [UserController::class, 'messageSave'])->name('user.messageSave');
		Route::post('/mark/asread', [UserController::class, 'markMessagesAsRead'])->name('user.markMessagesAsRead');

		Route::get('/script/runner', [UserController::class, 'scriptRunnerPage'])->name('user.scriptRunnerPage');
		Route::post('/script/runner', [UserController::class, 'runScript'])->name('user.runScript');
		Route::get('/community/page', [UserController::class, 'communityPage'])->name('user.communityPage');
		Route::post('/create/community', [UserController::class, 'createCommunity'])->name('user.createCommunity');
		Route::get('/community/list', [UserController::class, 'communityList'])->name('user.communityList');
		Route::get('/fetch/community/list', [UserController::class, 'fetchCommunityList'])->name('user.fetchCommunityList');

		Route::get('/category/products/{slug}', [UserController::class, 'showCategoryProducts'])->name('user.showCategoryProducts');
		Route::post('/add/whislist', [UserController::class, 'addWhislist'])->name('user.addWhislist');
        Route::get('/category/details/{id}', [UserController::class, 'singleDetailsCategory'])->name('user.singleDetailsCategory');

        Route::post('save/cart/{id}',[CartController::class,'saveCart'])->name('user.saveCart');
        Route::get('user/cart/count',[CartController::class,'userCartCount'])->name('user.userCartCount');

        Route::post('/apply/coupon', [UserController::class, 'applyCoupon'])->name('user.applyCoupon');

        Route::post('/apply/coupon', [UserController::class, 'applyCoupon'])->name('user.applyCoupon');


        // Route::post('/search-items', [SearchController::class, 'search'])->name('search.items');
        Route::get('/search-items', [SearchController::class, 'search'])->name('search.items');
        Route::get('/filter-products', [SearchController::class, 'filterProducts'])->name('filter.products');
        Route::get('/products/search', [SearchController::class, 'product_sale_search'])->name('products.search');
        // Route::get('/onsale/search', [SearchController::class, 'product_on_sale_search'])->name('onsale.search');



        Route::get('/all/products', [SearchController::class, 'allProductPage'])->name('user.allProducts');
        Route::get('/all/products/data', [SearchController::class, 'allProductFilter'])->name('user.allProductFilter');

        Route::get('/order/history', [ProfileController::class, 'userOrderHistory'])->name('user.OrderHistory');
        Route::get('/order/history/data', [ProfileController::class, 'fetchOrdersHistory'])->name('user.fetchOrdersHistory');
        Route::get('/wishlist', [ProfileController::class, 'userWishlist'])->name('user.wishlistPage');
        Route::get('/wishlist/data', [ProfileController::class, 'fetchWishlistItems'])->name('user.fetchWishlistItems');
        Route::get('/user/profile', [ProfileController::class, 'userProfileEdit'])->name('user.ProfileEdit');
        Route::post('/user/profile', [ProfileController::class, 'userProfileUpdate'])->name('user.ProfileUpdate');
    
    });


