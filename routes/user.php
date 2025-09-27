<?php

use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ProfileController;
use Illuminate\Support\Facades\Route;

Route::middleware(['role:user', 'auth', 'verified'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::controller(OrderController::class)->group(function () {
        Route::prefix('upload')->name('upload.')->group(function () {
            Route::get('/{slug}', 'handleView')->name('view');
            Route::post('/{slug}', 'handleService')->name('service');
        });

        // Detail and Destroy Order
        Route::prefix('order')->name('order.')->group(function () {
            Route::get('/{order}', 'show')->whereNumber('order')->name('show');
            Route::delete('{order}', 'destroy')->whereNumber('order')->name('destroy');
            Route::delete('/files/{file}', 'destroyFile')->name('file.remove');
        });
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::middleware('password.confirmed')->group(function () {
            Route::get('/', [ProfileController::class, 'index'])->name('view');
            Route::put('/update', [ProfileController::class, 'update'])->name('update');
            Route::delete('/jj/{type}', [ProfileController::class, 'destroyVideoJJ'])->name('jj.destroy');
        });

        Route::post('/picture/{slot}', [ProfileController::class, 'updatePicture'])->name('updatePicture');
    });
});
