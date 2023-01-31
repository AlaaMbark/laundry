<?php

use App\Http\Controllers\Api\Auth\AccessTokensController;
use App\Http\Controllers\Api\Auth\CodeCheckController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
use App\Http\Controllers\Api\Auth\ProfileController;
use App\Http\Controllers\Api\Auth\ResetPasswordController;
use App\Http\Controllers\Api\Mobile\OrderController;
use App\Http\Controllers\Api\Mobile\OrderGrilleController;
use App\Http\Controllers\Api\Mobile\OrderUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::middleware(['setlocale'])->group(function () {
    Route::middleware(['guest:sanctum'])->prefix('auth')->group(function () {
        Route::post('register', [AccessTokensController::class, 'createUser']);
        Route::post('login', [AccessTokensController::class, 'store']);

        Route::get('login/google', [AccessTokensController::class, 'redirectToGoogle']);
        Route::get('login/google/callback', [AccessTokensController::class, 'handleGoogleCallback']);

        Route::get('login/facebook', [AccessTokensController::class, 'redirectToFacebook']);
        Route::get('login/facebook/callback', [AccessTokensController::class, 'handleFacebookCallback']);

        Route::post('/password/phone', [ForgotPasswordController::class, 'phone']);
        Route::post('/password/email', [ForgotPasswordController::class, 'email']);
        Route::post('/password/code/check', CodeCheckController::class);
    });


    Route::middleware(['auth:sanctum'])->group(function () {
        Route::delete('auth/login/{token?}', [AccessTokensController::class, 'destroy']);

        Route::post('/password/reset', ResetPasswordController::class);

        Route::get('/profile/show', [ProfileController::class, 'showProfile']);
        Route::post('/profile/update', [ProfileController::class, 'updateProfile']);


        Route::middleware(['role:user'])->group(function () {
            Route::post('/order/create', [OrderUserController::class, 'createOrder']);
            Route::post('/order/canceled', [OrderUserController::class, 'canceledOrder']);

        });

        Route::middleware(['role:grille'])->group(function () {
            Route::get('/new-orders/index', [OrderGrilleController::class, 'indexNewOrder']);
            Route::get('/pending-orders/index', [OrderGrilleController::class, 'indexPendingOrder']);
            Route::get('/started-orders/index', [OrderGrilleController::class, 'indexStartedOrder']);
            Route::get('/finished-orders/index', [OrderGrilleController::class, 'indexFinishedOrder']);

            Route::post('/order/canceled/{id}', [OrderGrilleController::class, 'canceledOrder']);
            Route::post('/order/acceptable/{id}', [OrderGrilleController::class, 'acceptableOrder']);
            Route::post('/order/started/{id}', [OrderGrilleController::class, 'startedOrder']);
            Route::post('/order/finished/{id}', [OrderGrilleController::class, 'finishedOrder']);


            Route::get('/order/details/{id}', [OrderGrilleController::class, 'detailsOrder']);
            Route::get('/order/notification', [OrderGrilleController::class, 'notification']);
        });

    });



});
