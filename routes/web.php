<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\GovernmentCategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\GovernmentController;
use App\Http\Controllers\OfferServiceController;
use App\Http\Controllers\Auth\AuthController;

// الصفحة الرئيسية
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/categories/{category}', [GovernmentCategoryController::class, 'show'])
    ->name('categories.show');

Route::view('/about', 'pages.navbar-footer-pages.about')->name('about');

// ===== مسارات المصادقة =====
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===== مسارات المستخدم المسجل =====
Route::middleware('auth')->group(function () {
    // لوحة التحكم
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    // مسارات المفضلة
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
    Route::post('/favorite/government/toggle', [FavoriteController::class, 'toggleGovernment'])->name('favorite.government.toggle');
    Route::post('/favorite/service/toggle', [FavoriteController::class, 'toggleService'])->name('favorite.service.toggle');

    // مسار خدمات المستخدم (اختياري)
    Route::get('/dashboard/services', function () {
        return view('dashboard.services');
    })->name('dashboard.services');

    // ===== مسارات التقييمات (للمستخدمين المسجلين فقط) =====
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// مسارات البحث
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

// مسارات الجهات والخدمات
Route::get('/governments/{id}', [GovernmentController::class, 'show'])->name('governments.show');
Route::get('/services/{id}', [OfferServiceController::class, 'show'])->name('services.show');

// ===== مسارات التقييمات (للجميع - لعرض التقييمات) =====
Route::get('/governments/{id}/reviews', [ReviewController::class, 'getReviews'])->name('reviews.get');
