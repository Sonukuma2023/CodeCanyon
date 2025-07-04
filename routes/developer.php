<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\backend\DeveloperController;

Route::middleware(['auth', 'role:developer'])
    ->prefix('developer')
    ->name('developer.')
    ->group(function () {
        Route::get('/dashboard', [DeveloperController::class, 'dashboard'])->name('dashboard');

		
		Route::get('/view/communities',[DeveloperController::class, 'viewCommunities'])->name('viewCommunities');
		Route::get('/fetch/communities',[DeveloperController::class, 'fetchCommunities'])->name('fetchCommunities');
		Route::get('/reply/community/{id}',[DeveloperController::class, 'replyCommunityForm'])->name('replyCommunityForm');
		Route::post('/reply/community/{id}',[DeveloperController::class, 'replyCommunity'])->name('replyCommunity');
    });
