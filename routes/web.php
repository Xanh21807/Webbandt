<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginGoogleController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PaymentController;
/*
|--------------------------------------------------------------------------
| Web Routes - XanhStore
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', function () {
    return view('home');
})->name('home');

// Auth routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
//Login with Google
Route::get('/auth/google', [LoginGoogleController::class, 'redirectToGoogle'])
    ->name('auth.google');
Route::get('/auth/google/callback', [LoginGoogleController::class, 'handleGoogleCallback']);
Route::get('/logout', [LoginGoogleController::class, 'logout'])->name('logout');
// Registration page
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Product routes
Route::get('/products', function () {
    return view('products.index');
})->name('products.index');

Route::get('/products/{id}', function ($id) {
    return view('products.show', ['productId' => $id]);
})->name('products.show');

// Cart & Checkout
Route::get('/cart', function () {
    return view('cart');
})->name('cart');

Route::get('/checkout', function () {
    return view('checkout');
})->name('checkout');

// User profile pages
Route::get('/profile', function () {
    return view('profile');
})->name('profile');

Route::get('/favorites', function () {
    return view('favorites');
})->name('favorites');

Route::get('/orders', function () {
    return view('orders');
})->name('orders');

Route::get('/change-password', function () {
    return view('change-password');
})->name('change-password');

Route::get('/orders/{id}/success', function ($id) {
    return view('orders.success', ['orderId' => $id]);
})->name('orders.success');

Route::get('/orders/{id}', function ($id) {
    return view('orders.show', ['orderId' => $id]);
})->name('orders.show');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', function () {
        return view('admin.login');
    })->name('login');
    
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard.index');
    
    Route::get('/products', function () {
        return view('admin.products.index');
    })->name('products.index');
    
    Route::get('/orders', function () {
        return view('admin.orders.index');
    })->name('orders.index');
    
    Route::get('/users', function () {
        return view('admin.users.index');
    })->name('users.index');
    
    Route::get('/categories', function () {
        return view('admin.categories.index');
    })->name('categories.index');
    
    Route::get('/statistics', function () {
        return view('admin.statistics.index');
    })->name('statistics.index');

});
// Payment routes
Route::post('/payment/create', [PaymentController::class, 'createPayment']);
Route::get('/payment/success', [PaymentController::class, 'success'])
->name('payment.success');

Route::get('/payment/cancel', [PaymentController::class, 'cancel'])
->name('payment.cancel');

