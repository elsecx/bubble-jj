<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OperatorsController;
use App\Http\Controllers\Admin\OrdersController;
use Illuminate\Support\Facades\Route;

Route::middleware(['role:super,operator', 'auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
  Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

  Route::controller(OrdersController::class)->prefix('orders')->name('orders.')->group(function () {
    Route::get('/', 'index')->name('view');
    Route::get('/data', 'data')->name('data');
    Route::get('{order}', 'show')->name('show');

    Route::post('/result/{order}', 'result')->name('result');
    Route::post('/reject/{order}', 'reject')->name('reject');
  });

  Route::middleware('role:super')->group(function () {
    Route::get('/operators/data', [OperatorsController::class, 'data'])->name('operators.data');
    Route::resource('operators', OperatorsController::class);
  });
});
