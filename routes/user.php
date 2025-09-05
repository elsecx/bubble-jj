<?php

use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['role:user', 'auth', 'verified'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::controller(OrderController::class)->prefix('order')->name('order.')->group(function () {
        Route::get('/{slug}', 'handleView')->name('view');
        Route::post('/{slug}', 'handleService')->middleware('password.confirmed')->name('service');
    });

    Route::middleware('password.confirmed')->prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('view');
        Route::put('/update', [ProfileController::class, 'update'])->name('update');
    });
});
