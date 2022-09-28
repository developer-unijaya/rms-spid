<?php

use DeveloperUnijaya\RmsSpid\Controllers\RmsSpidAuthController;
use DeveloperUnijaya\RmsSpid\Controllers\RmsSpidUserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('spid')->group(function () {

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

        // To be used by Sub-system
        Route::post('redirect', [RmsSpidUserController::class, 'redirect'])->name('spid.user.redirect');
    });

});
