<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChatbotController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Api\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Api\Admin\StatisticsController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
// Admin public routes
Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);
});

// Products public routes
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/filter-options', [ProductController::class, 'filterOptions']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/products/{id}/accessories', [ProductController::class, 'accessories']);
Route::get('/products/{id}/combos', [ProductController::class, 'combos']);
Route::get('/products/{id}/reviews', [ReviewController::class, 'index']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/products/{id}/review-eligibility', [ReviewController::class, 'eligibility']);
    Route::post('/products/{id}/reviews', [ReviewController::class, 'store']);
});

// Categories public routes
Route::get('/categories', [AdminCategoryController::class, 'index']);

// Chatbot public route
Route::post('/chat', [ChatbotController::class, 'reply']);

// Protected user routes
Route::middleware(['auth:sanctum', 'check.status'])->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/user/change-password', [AuthController::class, 'changePassword']);

    // Favorites
    Route::get('/favorites', [ProductController::class, 'favorites']);
    Route::post('/favorites/{product_id}', [ProductController::class, 'addToFavorites']);
    Route::delete('/favorites/{product_id}', [ProductController::class, 'removeFromFavorites']);

    // Cart
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart/items', [CartController::class, 'addItem']);
    Route::post('/cart/add', [CartController::class, 'addItem']); // Alias cho frontend
    Route::put('/cart/items/{id}', [CartController::class, 'updateItem']);
    Route::put('/cart/{id}', [CartController::class, 'updateItem']); // Alias
    Route::delete('/cart/items/{id}', [CartController::class, 'removeItem']);
    Route::delete('/cart/{id}', [CartController::class, 'removeItem']); // Alias
    Route::delete('/cart', [CartController::class, 'clear']);

    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::post('/checkout', [OrderController::class, 'checkout']);
    Route::put('/orders/{id}/cancel', [OrderController::class, 'cancelOrder']);

    // Recommendations
    Route::get('/recommendations', [\App\Http\Controllers\Api\RecommendationController::class, 'index']);
});

// Protected admin routes
Route::middleware(['auth:sanctum', 'check.status', 'admin'])->prefix('admin')->group(function () {
    // Auth
    Route::post('/logout', [AdminAuthController::class, 'logout']);

    // Dashboard routes (for dashboard.blade.php)
    Route::get('/dashboard/stats', [StatisticsController::class, 'dashboardStats']);
    Route::get('/dashboard/revenue', [StatisticsController::class, 'revenueByMonth']);
    Route::get('/dashboard/orders-status', [StatisticsController::class, 'ordersStatus']);
    Route::get('/dashboard/recent-orders', [AdminOrderController::class, 'recentOrders']);
    Route::get('/dashboard/top-products', [AdminProductController::class, 'topProducts']);

    // Products
    Route::get('/products', [AdminProductController::class, 'index']);
    Route::get('/products/{id}', [AdminProductController::class, 'show']);
    Route::post('/products', [AdminProductController::class, 'store']);
    Route::put('/products/{id}', [AdminProductController::class, 'update']);
    Route::post('/products/{id}', [AdminProductController::class, 'update']); // Hỗ trợ _method=PUT từ FormData
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy']);

    // Orders
    Route::get('/orders/counts', [AdminOrderController::class, 'counts']);
    Route::get('/orders', [AdminOrderController::class, 'index']);
    Route::get('/orders/{id}', [AdminOrderController::class, 'show']);
    Route::put('/orders/{id}', [AdminOrderController::class, 'updateStatus']);
    Route::put('/orders/{id}/confirm-payment', [AdminOrderController::class, 'confirmPayment']);
    Route::delete('/orders/{id}', [AdminOrderController::class, 'destroy']);
    Route::put('/orders/{id}/cancel', [AdminOrderController::class, 'cancel']);

    // Users
    Route::get('/users/stats', [AdminUserController::class, 'stats']);
    Route::get('/users', [AdminUserController::class, 'index']);
    Route::post('/users', [AdminUserController::class, 'store']);
    Route::get('/users/{id}', [AdminUserController::class, 'show']);
    Route::put('/users/{id}', [AdminUserController::class, 'update']);
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy']);
    Route::put('/users/{id}/status', [AdminUserController::class, 'updateStatus']);

    // Categories
    Route::get('/categories', [AdminCategoryController::class, 'index']);
    Route::get('/categories/{id}', [AdminCategoryController::class, 'show']);
    Route::post('/categories', [AdminCategoryController::class, 'store']);
    Route::put('/categories/{id}', [AdminCategoryController::class, 'update']);
    Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy']);

    // Statistics
    Route::get('/statistics/dashboard', [StatisticsController::class, 'dashboard']);
    Route::get('/statistics/revenue', [StatisticsController::class, 'revenueReport']);
    Route::get('/statistics/products', [StatisticsController::class, 'productReport']);
    Route::get('/statistics/categories', [StatisticsController::class, 'categoryReport']);

    
});
