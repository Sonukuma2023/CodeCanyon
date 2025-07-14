<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentController;
 use App\Http\Controllers\ReviewController;
 
// use App\Http\Controllers\ProfileController;


Route::get('/', [UserController::class, 'dashboard'])->name('dashboard');


// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/dashboard', function () {
//     return view('user.dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });

Route::get('paypal', [PaymentController::class, 'index'])->name('paypal');
Route::POST('paypal/payment', [PaymentController::class, 'payment'])->name('paypal.payment');
Route::get('paypal/payment/success', [PaymentController::class, 'paymentSuccess'])->name('paypal.payment.success');
Route::get('paypal/payment/cancel', [PaymentController::class, 'paymentCancel'])->name('paypal.payment/cancel');
  
Route::post('/submit-review', [ReviewController::class, 'store'])->name('submit.review');
Route::post('/ajax/rating/init', [ReviewController::class, 'init'])->name('ajax.rating.init');




require __DIR__.'/auth.php';
