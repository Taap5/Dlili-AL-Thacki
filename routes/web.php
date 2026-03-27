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
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\MyReviewController;
use App\Http\Controllers\AdminController;

// الصفحة الرئيسية
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/categories/{category}', [GovernmentCategoryController::class, 'show'])
    ->name('categories.show');

Route::view('/about', 'pages.navbar-footer-pages.about')->name('about');
Route::view('/team', 'pages.navbar-footer-pages.team')->name('team');
// ===== مسارات المصادقة =====
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');

    // مسارات التحقق من البريد
    Route::post('/verification/send', [VerificationController::class, 'sendCode'])->name('verification.send');
    Route::get('/verify-code', [VerificationController::class, 'showCodeForm'])->name('verify.code.form');
    Route::post('/verify-code', [VerificationController::class, 'verifyCode'])->name('verify.code');
    Route::post('/verification/resend', [VerificationController::class, 'resendCode'])->name('verification.resend');

    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===== مسارات المستخدم المسجل =====
Route::middleware('auth')->group(function () {
    // لوحة التحكم (للمستخدم العادي)
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    // مسارات المفضلة
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites');
    Route::post('/favorite/government/toggle', [FavoriteController::class, 'toggleGovernment'])->name('favorite.government.toggle');
    Route::post('/favorite/service/toggle', [FavoriteController::class, 'toggleService'])->name('favorite.service.toggle');

    // ===== مسارات التقييمات =====
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{id}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // ===== مسار تقييماتي =====
    Route::get('/my-reviews', [MyReviewController::class, 'index'])->name('my.reviews');
    Route::delete('/my-reviews/{id}', [MyReviewController::class, 'destroy'])->name('my.reviews.destroy');

    // ===== مسارات المسؤول =====
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/governments', [AdminController::class, 'governments'])->name('admin.governments');
    Route::get('/admin/services', [AdminController::class, 'services'])->name('admin.services');
    Route::get('/admin/users', [AdminController::class, 'users'])->name('admin.users');

    // ===== مسارات إدارة المسؤول (POST, PUT, DELETE) =====
    Route::post('/admin/governments', [AdminController::class, 'storeGovernment'])->name('admin.governments.store');
    Route::put('/admin/governments/{id}', [AdminController::class, 'updateGovernment'])->name('admin.governments.update');
    Route::delete('/admin/governments/{id}', [AdminController::class, 'destroyGovernment'])->name('admin.governments.destroy');

    Route::post('/admin/services', [AdminController::class, 'storeService'])->name('admin.services.store');
    Route::put('/admin/services/{id}', [AdminController::class, 'updateService'])->name('admin.services.update');
    Route::delete('/admin/services/{id}', [AdminController::class, 'destroyService'])->name('admin.services.destroy');

    Route::put('/admin/users/{id}/role', [AdminController::class, 'updateUserRole'])->name('admin.users.role');
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::delete('/profile/remove-photo', [AuthController::class, 'removePhoto'])->name('profile.remove-photo');
});

// مسارات البحث
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

// مسارات الجهات والخدمات
Route::get('/governments/{id}', [GovernmentController::class, 'show'])->name('governments.show');
Route::get('/services/{id}', [OfferServiceController::class, 'show'])->name('services.show');

// ===== مسارات التقييمات (للجميع - لعرض التقييمات) =====
Route::get('/governments/{id}/reviews', [ReviewController::class, 'getReviews'])->name('reviews.get');

// صفحات جميع الجهات وجميع الخدمات
Route::get('/governments', [GovernmentController::class, 'index'])->name('governments.index');
Route::get('/services', [OfferServiceController::class, 'index'])->name('services.index');

// صفحة المساعدة
Route::view('/help', 'pages.navbar-footer-pages.help')->name('help');
