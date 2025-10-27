<?php

use App\Http\Controllers\Web\AboutUsController;
use App\Http\Controllers\Web\CartController;
use App\Http\Controllers\Web\CheckoutController;
use App\Http\Controllers\Web\ContactUsController;
use App\Http\Controllers\Web\CourseController;
use App\Http\Controllers\Web\FaqsController;
use App\Http\Controllers\Web\HomeController;
use App\Http\Controllers\Web\PrivacyPolicyController;
use App\Http\Controllers\Web\StudentAuthController;
use App\Http\Controllers\Web\TeacherController;
use App\Http\Controllers\Web\WishlistController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ForgotPasswordController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::controller(CourseController::class)->group(function () {
    Route::prefix('courses')->group(function () {
        Route::get('/', 'index')->name('courses');
        Route::get('/detail/{id}', 'courseDetails')->name('course-details');
    });
});

Route::controller(TeacherController::class)->group(function () {
    Route::prefix('instructors')->group(function () {
        Route::get('/', 'index')->name('instructors');
        Route::get('/{id}/profile', 'profile')->name('instructor-profile');
        Route::get('/{id}/contents', 'contents')->name('instructor-contents');
    });
});

Route::get('/about-us', [AboutUsController::class, 'index'])->name('about-us');

Route::get('/contact-us', [ContactUsController::class, 'index'])->name('contact-us');
Route::post('/contact-message/store', [ContactUsController::class, 'store'])->name('contact-message.store');

Route::get('/faqs', [FaqsController::class, 'index'])->name('faqs');

Route::get('/privacy-policy', [PrivacyPolicyController::class, 'index'])->name('privacy-policy');

Route::group(['middleware' => ['web.auth']], function () {
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
    Route::get('/wishlist/store/{id}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::get('/wishlist/delete/{id}', [WishlistController::class, 'delete'])->name('wishlist.delete');

    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::get('/cart/store/{id}', [CartController::class, 'store'])->name('cart.store');
    Route::get('/cart/delete/{id}', [CartController::class, 'delete'])->name('cart.delete');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
});

Route::group(['prefix' => 'forgot-password', 'as' => 'forgot-password.', 'middleware' => ['checkIfLoggedIn']], function () {
    Route::get('/', [ForgotPasswordController::class, 'index'])->name('index');
    Route::post('/generate-token', [ForgotPasswordController::class, 'generateToken'])->name('generate-token');
    Route::get('/reset', [ForgotPasswordController::class, 'resetPassword'])->name('reset');
    Route::post('/reset-password/update', [ForgotPasswordController::class, 'updatePassword'])->name('update');
});
