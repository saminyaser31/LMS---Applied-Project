<?php

use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Teacher\CourseContentController;
use App\Http\Controllers\Teacher\CourseController;
use App\Http\Controllers\Teacher\CourseMaterialController;
use App\Http\Controllers\Teacher\DashboardController;
use App\Http\Controllers\Teacher\MyStudentsController;
use App\Http\Controllers\Teacher\MyContentsController;
use App\Http\Controllers\Teacher\ProfileController;
use App\Http\Controllers\Teacher\ReviewController;
use App\Http\Controllers\Web\TeacherAuthController;
use Illuminate\Support\Facades\Route;


Route::middleware('checkIfLoggedIn')->group(function () {
    Route::get('/teacher/login', [TeacherAuthController::class, 'loginPage'])->name('teacher.login-page');
    // Route::post('/teacher/login', [TeacherAuthController::class, 'login'])->name('teacher.login');
    Route::get('/teacher/register', [TeacherAuthController::class, 'registerPage'])->name('teacher.register-page');
    Route::post('/teacher/sign-up', [TeacherAuthController::class, 'register'])->name('teacher.register');
    Route::get('/teacher/account/verify/{token}', [TeacherAuthController::class, 'verifyAccount'])->name('teacher.verify');
});

Route::group(['prefix' => 'teacher', 'as' => 'teacher.', 'middleware' => ['auth']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['prefix' => 'courses', 'as' => 'courses.', 'middleware' => ['auth']], function () {
        Route::get('/', [CourseController::class, 'index'])->name('index');
        Route::get('/show/{id}', [CourseController::class, 'show'])->name('show');
        Route::get('/create', [CourseController::class, 'create'])->name('create');
        Route::post('/store', [CourseController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CourseController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [CourseController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [CourseController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'course-contents', 'as' => 'course-contents.', 'middleware' => ['auth']], function () {
        Route::get('/', [CourseContentController::class, 'index'])->name('index');
        Route::get('/create', [CourseContentController::class, 'create'])->name('create');
        Route::post('/store', [CourseContentController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CourseContentController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [CourseContentController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [CourseContentController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'course-materials', 'as' => 'course-materials.', 'middleware' => ['auth']], function () {
        Route::get('/', [CourseMaterialController::class, 'index'])->name('index');
        Route::get('/create', [CourseMaterialController::class, 'create'])->name('create');
        Route::post('/store', [CourseMaterialController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [CourseMaterialController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [CourseMaterialController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [CourseMaterialController::class, 'delete'])->name('delete');
    });

    Route::group(['prefix' => 'my-students', 'as' => 'my-students.', 'middleware' => ['auth']], function () {
        Route::get('/', [MyStudentsController::class, 'index'])->name('index');
    });

    Route::group(['prefix' => 'my-contents', 'as' => 'my-contents.', 'middleware' => ['auth']], function () {
        Route::get('/', [MyContentsController::class, 'index'])->name('index');
        Route::get('/create', [MyContentsController::class, 'create'])->name('create');
        Route::post('/store', [MyContentsController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [MyContentsController::class, 'edit'])->name('edit');
        Route::post('/update/{id}', [MyContentsController::class, 'update'])->name('update');
        Route::get('/delete/{id}', [MyContentsController::class, 'delete'])->name('delete');
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
