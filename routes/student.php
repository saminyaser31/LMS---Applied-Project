<?php

use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Student\CourseController;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\OrderController;
use App\Http\Controllers\Student\ProfileController;
use App\Http\Controllers\Student\ReviewController;
use App\Http\Controllers\Web\StudentAuthController;
use Illuminate\Support\Facades\Route;


Route::middleware('checkIfLoggedIn')->group(function () {
    Route::get('/student/login', [StudentAuthController::class, 'loginPage'])->name('student.login-page');
    // Route::post('/student/login', [StudentAuthController::class, 'login'])->name('student.login');
    Route::get('/student/register', [StudentAuthController::class, 'registerPage'])->name('student.register-page');
    Route::post('/student/sign-up', [StudentAuthController::class, 'register'])->name('student.register');
    Route::get('/student/account/verify/{token}', [StudentAuthController::class, 'verifyAccount'])->name('student.verify');
});

Route::group(['prefix' => 'student', 'as' => 'student.', 'middleware' => ['auth']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['prefix' => 'orders', 'as' => 'orders.', 'middleware' => ['auth']], function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
    });

    Route::group(['prefix' => 'courses', 'as' => 'courses.', 'middleware' => ['auth']], function () {
        Route::get('/', [CourseController::class, 'index'])->name('index');
        Route::get('/show/{id}', [CourseController::class, 'show'])->name('show');
    });

    Route::group(['prefix' => 'profile', 'as' => 'profile.', 'middleware' => ['auth']], function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::post('/update/{id}', [ProfileController::class, 'update'])->name('update');
    });

    Route::group(['prefix' => 'change-password', 'as' => 'change-password.', 'middleware' => ['auth']], function () {
        Route::get('/', [ChangePasswordController::class, 'index'])->name('index');
        Route::post('/update/{id}', [ChangePasswordController::class, 'update'])->name('update');
    });

    Route::group(['prefix' => 'reviews', 'as' => 'reviews.', 'middleware' => ['auth']], function () {
        Route::get('/', [ReviewController::class, 'index'])->name('index');
    });
});
