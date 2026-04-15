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
use Illuminate\Support\Facades\DB;
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

    // الملف الشخصي
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');
    Route::delete('/profile/remove-photo', [AuthController::class, 'removePhoto'])->name('profile.remove-photo');

    // تغيير البريد الإلكتروني
    Route::post('/profile/request-email-change', [AuthController::class, 'requestEmailChange'])->name('profile.request-email-change');
    Route::get('/profile/confirm-email-change', [AuthController::class, 'showConfirmEmailChange'])->name('profile.confirm-email-change');
    Route::post('/profile/confirm-email-change', [AuthController::class, 'confirmEmailChange'])->name('profile.confirm-email-change.submit');

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

    // ===== مسارات المسؤول (مجموعة واحدة) =====
    Route::prefix('admin')->name('admin.')->group(function () {
        // الصفحات الرئيسية
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/governments', [AdminController::class, 'governments'])->name('governments');
        Route::get('/services', [AdminController::class, 'services'])->name('services');
        Route::get('/users', [AdminController::class, 'users'])->name('users');

        // إدارة التقييمات
        Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews');
        Route::delete('/reviews/{id}', [AdminController::class, 'destroyReview'])->name('reviews.destroy');

        // إدارة الجهات (POST, PUT, DELETE)
        Route::post('/governments', [AdminController::class, 'storeGovernment'])->name('governments.store');
        Route::put('/governments/{id}', [AdminController::class, 'updateGovernment'])->name('governments.update');
        Route::delete('/governments/{id}', [AdminController::class, 'destroyGovernment'])->name('governments.destroy');

        // إدارة الخدمات (POST, PUT, DELETE)
        Route::post('/services', [AdminController::class, 'storeService'])->name('services.store');
        Route::put('/services/{id}', [AdminController::class, 'updateService'])->name('services.update');
        Route::delete('/services/{id}', [AdminController::class, 'destroyService'])->name('services.destroy');

        // إدارة المستخدمين
        Route::put('/users/{id}/role', [AdminController::class, 'updateUserRole'])->name('users.role');
        Route::delete('/users/{id}', [AdminController::class, 'destroyUser'])->name('users.destroy');
        // ========== أضف Routes العروض هنا ==========
        Route::get('/governments/{governmentId}/offers', [App\Http\Controllers\Admin\GovernmentOfferController::class, 'index'])
            ->name('government-offers.index');
        Route::post('/governments/{governmentId}/offers', [App\Http\Controllers\Admin\GovernmentOfferController::class, 'store'])
            ->name('government-offers.store');
        Route::put('/governments/{governmentId}/offers/{offerId}', [App\Http\Controllers\Admin\GovernmentOfferController::class, 'update'])
            ->name('government-offers.update');
        Route::delete('/governments/{governmentId}/offers/{offerId}', [App\Http\Controllers\Admin\GovernmentOfferController::class, 'destroy'])
            ->name('government-offers.destroy');
        Route::post('/governments/{governmentId}/offers/{offerId}/toggle', [App\Http\Controllers\Admin\GovernmentOfferController::class, 'toggleActive'])
            ->name('government-offers.toggle');
    });
    Route::get('/api/get-location', [AdminController::class, 'getLocationApi']);
});

// ===== مسارات البحث =====
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');
Route::get('/search/advanced', [SearchController::class, 'advanced'])->name('search.advanced');
Route::get('/search/ajax', [SearchController::class, 'ajaxSearch'])->name('search.ajax');

// ===== مسارات الجهات والخدمات =====
Route::get('/governments/{id}', [GovernmentController::class, 'show'])->name('governments.show');
Route::get('/services/{id}', [OfferServiceController::class, 'show'])->name('services.show');
Route::get('/governments', [GovernmentController::class, 'index'])->name('governments.index');
Route::get('/services', [OfferServiceController::class, 'index'])->name('services.index');

// ===== مسارات التقييمات (للجميع - لعرض التقييمات) =====
Route::get('/governments/{id}/reviews', [ReviewController::class, 'getReviews'])->name('reviews.get');

// ===== صفحات إضافية =====
Route::view('/help', 'pages.navbar-footer-pages.help')->name('help');
Route::get('/emergency/nearest', [App\Http\Controllers\EmergencyController::class, 'nearest'])->name('emergency.nearest');
// ===== مسارات العروض الخاصة =====
Route::get('/offers', [App\Http\Controllers\OffersController::class, 'index'])->name('offers.index');
Route::get('/offers/{id}', [App\Http\Controllers\OffersController::class, 'show'])->name('offers.show');


Route::get('/fix-db', function () {
    try {
        // هذا الأمر يعيد ضبط عداد الـ ID في PostgreSQL ليصبح بعد أكبر ID موجود حالياً
        DB::statement("SELECT setval('users_id_seq', (SELECT MAX(id) FROM users))");
        return "تم إصلاح عداد قاعدة البيانات بنجاح! يمكنك التسجيل الآن.";
    } catch (\Exception $e) {
        return "حدث خطأ أو ربما العداد منضبط بالفعل: " . $e->getMessage();
    }
});
