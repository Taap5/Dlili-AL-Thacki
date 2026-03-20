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

// الصفحة الرئيسية

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/categories/{category}', [GovernmentCategoryController::class, 'show'])
    ->name('categories.show');

Route::view('/about', 'pages.navbar-footer-pages.about')->name('about');

Route::view('/login', 'auth.login')->name('login');

Route::view('/register', 'auth.register')->name('register');


Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

Route::get('/governments/{id}', [GovernmentController::class, 'show'])->name('governments.show');
Route::get('/services/{id}', [OfferServiceController::class, 'show'])->name('services.show');
