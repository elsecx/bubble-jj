<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Replace default Laravel 'login' route
Route::get('/login', function () {
    return redirect()->route('login.user.view');
})->name('login');

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/user.php';
