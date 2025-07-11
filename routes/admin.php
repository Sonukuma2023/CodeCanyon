<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\backend\AdminController;

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        Route::get('/adduser', [AdminController::class, 'adduser'])->name('user');
        Route::post('/adduser', [AdminController::class, 'storeuser'])->name('storeUser');
        Route::get('/viewuser', [AdminController::class, 'viewusers'])->name('viewusers');
        Route::get('/edituser/{id}', [AdminController::class, 'edituser'])->name('edituser');
        Route::post('/updateuser/{id}', [AdminController::class, 'updateuser'])->name('updateuser');
        Route::delete('/deleteuser/{id}', [AdminController::class, 'deleteuser'])->name('deleteuser');
        Route::get('/chat/{id}', [AdminController::class, 'messagePage'])->name('messagePage');
        Route::post('/chat/{id}', [AdminController::class, 'messageSave'])->name('messageSave');
        Route::get('/fetch/chats/{id}', [AdminController::class, 'fetchMessages'])->name('fetchMessages');
        Route::get('/fetch/notifications', [AdminController::class, 'fetchNotifications'])->name('fetchNotifications');
        Route::get('/notifications', [AdminController::class, 'allNotificationPage'])->name('notifications');
        Route::get('/all/notifications', [AdminController::class, 'fetchAllNotifications'])->name('allNotifications');
        Route::post('/mark/read/notifications', [AdminController::class, 'markReadAsNotifications'])->name('markReadNotifications');

        Route::get('/addproduct', [AdminController::class, 'addproduct'])->name('product');
        Route::post('/addproduct', [AdminController::class, 'storeproduct'])->name('storeproduct');
        Route::get('/viewproduct', [AdminController::class, 'viewproduct'])->name('viewproduct');
        Route::get('/editproduct/{id}', [AdminController::class, 'editproduct'])->name('editproduct');
        Route::post('/updateproduct/{id}', [AdminController::class, 'updateproduct'])->name('updateproduct');
        Route::delete('/deleteproduct/{id}', [AdminController::class, 'deleteproduct'])->name('deleteproduct');


        Route::get('/addcategory', [AdminController::class, 'addcategory'])->name('category');
        Route::post('/addcategory', [AdminController::class, 'storecategory'])->name('store');
        Route::get('/viewcategory', [AdminController::class, 'viewcategory'])->name('viewcategory');
        Route::get('/editcategory/{id}', [AdminController::class, 'editcategory'])->name('editcategory');
        Route::post('/updatecategory/{id}', [AdminController::class, 'updatecategory'])->name('updatecategory');
        Route::delete('/deletecategory/{id}', [AdminController::class, 'deletecategory'])->name('deletecategory');

        Route::post('/logout', function () {
            Auth::logout();
            return redirect('/login');
        })->name('logout');

		Route::get('/profile',[AdminController::class, 'adminProfile'])->name('profile');
		Route::post('/profile',[AdminController::class, 'updateProfile'])->name('updateProfile');

		Route::get('/view/communities',[AdminController::class, 'viewCommunities'])->name('viewCommunities');
		Route::get('/fetch/communities',[AdminController::class, 'fetchCommunities'])->name('fetchCommunities');
		Route::get('/reply/community/{id}',[AdminController::class, 'replyCommunityForm'])->name('replyCommunityForm');
		Route::post('/reply/community/{id}',[AdminController::class, 'replyCommunity'])->name('replyCommunity');


        Route::prefix('order')->group(function () {
            Route::get('/list', [AdminController::class, 'ordersPage'])->name('ordersPage');
            Route::get('/fetch', [AdminController::class, 'fetchOrders'])->name('fetchOrders');
            Route::get('/details/{order}', [AdminController::class, 'singleOrderDetails'])->name('singleOrderDetails');
        });

        Route::prefix('user/whislist')->group(function () {
            Route::get('/list', [AdminController::class, 'whislistPage'])->name('whislistPage');
            Route::get('/ajax/list', [AdminController::class, 'fetchWishlist'])->name('fetchWishlist');
            Route::get('/details/{id}', [AdminController::class, 'showWishlistDetails'])->name('showWishlistDetails');
        });

        Route::prefix('coupons')->group(function () {
            Route::get('/add', [AdminController::class, 'couponAddPage'])->name('couponAddPage');
            Route::post('/add', [AdminController::class, 'storeCoupon'])->name('storeCoupon');
            Route::get('/ajax/list', [AdminController::class, 'fetchCoupons'])->name('fetchCoupons');
            Route::get('/list', [AdminController::class, 'couponsPage'])->name('couponsPage');
            Route::get('/used',[AdminController::class, 'showUsedCoupons'])->name('showUsedCoupons');
            Route::get('/used/data',[AdminController::class, 'fetchUsedCoupons'])->name('fetchUsedCoupons');
            Route::delete('/{id}', [AdminController::class, 'deleteCoupons'])->name('deleteCoupons');
            Route::get('/edit/{id}', [AdminController::class, 'editCoupon'])->name('editCoupon');
            Route::put('/edit/{id}', [AdminController::class, 'updateCoupon'])->name('updateCoupon');
        }); 
        
        Route::prefix('user/cart')->group(function () {
            Route::get('/data', [AdminController::class, 'fetchUserCarts'])->name('fetchUserCarts');
            Route::get('/list', [AdminController::class, 'usersCartPage'])->name('usersCartPage');
            Route::get('/show/{id}', [AdminController::class, 'showUserCarts'])->name('showUserCarts');
            Route::get('/edit/{id}', [AdminController::class, 'editUserCarts'])->name('editUserCarts');
            Route::put('/edit/{id}', [AdminController::class, 'updateUserCarts'])->name('updateUserCarts');
            Route::delete('/delete/{id}', [AdminController::class, 'deleteUserCarts'])->name('deleteUserCarts');
        });

        Route::prefix('user/collection')->group(function () {
            Route::get('/data', [AdminController::class, 'fetchUserCollections'])->name('fetchUserCollections');
            Route::get('/list', [AdminController::class, 'userCollectionsPage'])->name('userCollectionsPage');
            Route::delete('/delete/{id}', [AdminController::class, 'deleteUserCollections'])->name('deleteUserCollections');
            Route::get('/show/{id}', [AdminController::class, 'showAllUserCollections'])->name('showAllUserCollections');
        });
        // Route::get('single/{name}/{slug}', [AdminController::class, 'single_categories_details'])->name('single.category');

    });
