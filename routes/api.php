<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\PaypalController;
use App\Http\Controllers\API\EmailVerificationController;
use App\Http\Controllers\API\NewPasswordController;
use App\Http\Controllers\API\SocialAuthController;
use App\Http\Controllers\API\OrderStatusController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\UserStatusController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Protected Routes
 */
Route::group([
    'middleware' => ['auth:sanctum', 'verified']
], function () {
    Route::put('product/{product}/restore', [ProductController::class, 'restore'])->name('product.restore');
    Route::resource('product', ProductController::class)->except([
        'index', 'show', 'edit', 'create'
    ]);
    Route::put('category/{category}/restore', [CategoryController::class, 'restore'])->name('category.restore');
    Route::resource('category', CategoryController::class)->except([
        'index', 'show', 'edit', 'create'
    ]);
    Route::put('user/{user}/reset-password', [UserController::class, 'reset'])->name('user.reset.password');
    Route::put('user/{user}/status', [UserController::class, 'updateStatus'])->name('update.user.status');
    Route::resource('user', UserController::class)->only([
        'index', 'store'
    ]);
    Route::resource('order', OrderController::class)->only([
        'index'
    ]);
    Route::put('order/{order}/status', [OrderController::class, 'updateStatus'])->name('update.order.status');
    Route::put('order/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel.order');
    Route::get('logged-user', [AuthController::class, 'loggedUser'])->name('logged.user');
    Route::put('update-profile', [AuthController::class, 'updateProfile'])->name('update.profile');
    Route::post('add-favorite/{product}', [AuthController::class, 'addFavorite'])->name('add.favorite');
    Route::delete('remove-favorite/{product}', [AuthController::class, 'removeFavorite'])->name('remove.favorite');
    Route::post('add-review/{product}', [AuthController::class, 'addReview'])->name('add.review');
    Route::put('update-review/{review}', [AuthController::class, 'updateReview'])->name('update.review');
    Route::delete('delete-review/{review}', [AuthController::class, 'deleteReview'])->name('delete.review');

    Route::group(['prefix' => 'paypal'], function () {
        Route::post('checkout', [PaypalController::class, 'create'])->name('paypal.checkout');
        Route::post('approve', [PaypalController::class, 'approve'])->name('paypal.approve');
    });

    Route::delete('logout', [AuthController::class, 'logout']);
    Route::put('change-password', [NewPasswordController::class, 'change'])->name('change.password');
    Route::post('confirm-password', [AuthController::class, 'confirmPassword'])->name('confirm.password');

    Route::get('dashboard-data', [DashboardController::class, 'index']);
    Route::resource('order-status', OrderStatusController::class)->only([
        'index'
    ]);
    Route::resource('user-status', UserStatusController::class)->only([
        'index'
    ]);
});

/**
 * Public routes
 */
Route::resource('product', ProductController::class)->only([
    'index', 'show'
]);
Route::get('product/{product}/reviews', [ProductController::class, 'reviews'])->name('product.reviewws');
Route::resource('category', CategoryController::class)->only([
    'index', 'show'
]);
Route::get('check-unique/{field}', [UserController::class, 'checkUniqueField'])->name('check.unique');
Route::post('send-inquiry ', [ContactController::class, 'sendInquiry'])->name('send.inquiry');

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('forgot-password', [NewPasswordController::class, 'forgot'])->name('forgot.password');
Route::put('reset-password', [NewPasswordController::class, 'reset'])->name('reset.password');

Route::post('email/verification-notification', [EmailVerificationController::class, 'send'])->middleware('auth:sanctum')->name('verification.send');
Route::get('email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->name('verification.verify');

Route::group([
    'prefix' => 'sign-in',
], function () {
    Route::get('{provider}', [SocialAuthController::class, 'redirectToProvider'])->name('social.redirect');
    Route::get('{provider}/callback', [SocialAuthController::class, 'handleProviderCallback'])->name('social.callback');
});
