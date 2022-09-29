<?php

use DeveloperUnijaya\RmsSpid\Controllers\RmsSpidAuthController;
use DeveloperUnijaya\RmsSpid\Controllers\RmsSpidUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {

    Route::prefix('spid')->group(function () {

        Route::post('test', [RmsSpidAuthController::class, 'test'])->name('api.spid.test');

        Route::prefix('auth')->group(function () {

            Route::post('login', [RmsSpidAuthController::class, 'login'])->name('spid.auth.login');

            Route::middleware('auth:sanctum')->group(function () {
                Route::post('me', [RmsSpidAuthController::class, 'me'])->middleware('auth:sanctum')->name('spid.auth.me');
                Route::post('logout', [RmsSpidAuthController::class, 'logout'])->middleware('auth:sanctum')->name('spid.auth.logout');
            });
        });

        Route::prefix('user')->middleware('auth:sanctum')->group(function () {

            Route::post('register', [RmsSpidUserController::class, 'register'])->name('spid.user.register');
            Route::post('profile', [RmsSpidUserController::class, 'profile'])->name('spid.user.profile');

            Route::post('check', [RmsSpidUserController::class, 'check'])->name('spid.user.check');

            Route::post('redirect', [RmsSpidUserController::class, 'redirect'])->name('spid.user.redirect');
        });
    });
});
