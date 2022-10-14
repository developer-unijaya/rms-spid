<?php

use DeveloperUnijaya\RmsSpid\Controllers\RmsSpidAuthController;
use DeveloperUnijaya\RmsSpid\Controllers\RmsSpidConfigController;
use DeveloperUnijaya\RmsSpid\Controllers\RmsSpidUserController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {

    Route::prefix('spid')->group(function () {

        Route::post('test', [RmsSpidAuthController::class, 'test'])->name('api.spid.test');

        // * Auth API
        Route::prefix('auth')->group(function () {

            // * Login System account and return auth_token
            Route::post('login', [RmsSpidAuthController::class, 'login'])->name('spid.auth.login');

            Route::middleware('auth:sanctum')->group(function () {

                // * Return logged-in user data
                Route::post('me', [RmsSpidAuthController::class, 'me'])->middleware('auth:sanctum')->name('spid.auth.me');

                // * Destroy auth_token and logout user
                Route::post('logout', [RmsSpidAuthController::class, 'logout'])->middleware('auth:sanctum')->name('spid.auth.logout');
            });
        });

        // User API
        Route::prefix('user')->middleware('auth:sanctum')->group(function () {
            Route::post('register', [RmsSpidUserController::class, 'register'])->name('spid.user.register');
            Route::post('profile', [RmsSpidUserController::class, 'profile'])->name('spid.user.profile');
            Route::post('update-spid_id', [RmsSpidUserController::class, 'updateSpidId'])->name('spid.user.updateSpidId');

            // * Bind account
            Route::post('check', [RmsSpidUserController::class, 'check'])->name('spid.user.check');

            // * Generate and return redirect_token
            Route::post('redirect', [RmsSpidUserController::class, 'redirect'])->name('spid.user.redirect');
        });

        // ! Config API
        Route::prefix('config')->middleware('auth:sanctum')->group(function () {

            // ! Get Sub-system config data
            Route::post('get-config', [RmsSpidConfigController::class, 'getConfig'])->name('spid.config.getConfig');
        });
    });
});
