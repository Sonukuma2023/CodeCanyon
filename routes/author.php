<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\backend\AuthorController;

Route::middleware(['auth', 'role:author'])
    ->prefix('author')
    ->name('author.')
    ->group(function () {
        Route::get('/dashboard', [AuthorController::class, 'dashboard'])->name('dashboard');

        Route::get('/addproduct', [AuthorController::class, 'addproduct'])->name('product');
        Route::post('/addproduct', [AuthorController::class, 'storeproduct'])->name('storeproduct');
        Route::get('/viewproduct', [AuthorController::class, 'viewproduct'])->name('viewproduct');
        Route::get('/editproduct/{id}', [AuthorController::class, 'editproduct'])->name('editproduct');
        Route::post('/updateproduct/{id}', [AuthorController::class, 'updateproduct'])->name('updateproduct');
        Route::delete('/deleteproduct/{id}', [AuthorController::class, 'deleteproduct'])->name('deleteproduct');

        Route::get('/viewreview', [AuthorController::class, 'viewreview'])->name('viewreview');
        Route::get('/approve-review/{id}', [AuthorController::class, 'approveReview'])->name('approve');
        Route::get('/reject-review/{id}', [AuthorController::class, 'rejectReview'])->name('reject');

        
        Route::post('/logout', function () {
            Auth::logout();
            return redirect('/login');
        })->name('logout');
    });
