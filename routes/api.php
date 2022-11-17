<?php

use DeveloperUnijaya\RmsSpid\Controllers\AuthController;
use DeveloperUnijaya\RmsSpid\Controllers\ConfigController;
use DeveloperUnijaya\RmsSpid\Controllers\UserController;
use DeveloperUnijaya\RmsSpid\Controllers\UserSpidController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {

    Route::prefix('spid')->group(function () {

        Route::post('test', [AuthController::class, 'test'])->name('api.spid.test');
        Route::resource('user-spid', UserSpidController::class);

        // * Auth API
        Route::prefix('auth')->middleware('verifyspidkey')->group(function () {

            // * Login System account and return auth_token
            Route::post('login', [AuthController::class, 'login'])->name('spid.auth.login');

            Route::middleware('auth:sanctum')->group(function () {

                // * Return logged-in user data
                Route::post('me', [AuthController::class, 'me'])->name('spid.auth.me');

                // * Destroy auth_token and logout user
                Route::post('logout', [AuthController::class, 'logout'])->name('spid.auth.logout');
            });
        });

        // User API
        Route::prefix('user')->middleware(['auth:sanctum', 'verifyspidkey'])->group(function () {

            Route::post('register', [UserController::class, 'register'])->name('spid.user.register');
            Route::post('profile', [UserController::class, 'profile'])->name('spid.user.profile');
            Route::post('update-spid_id', [UserController::class, 'updateSpidId'])->name('spid.user.updateSpidId');

            // * Bind account
            Route::post('check', [UserController::class, 'check'])->name('spid.user.check');

            // * Generate and return redirect_token
            Route::post('redirect', [UserController::class, 'redirect'])->name('spid.user.redirect');
        });

        // ! Config API
        Route::prefix('config')->middleware(['auth:sanctum', 'verifyspidkey'])->group(function () {

            // ! Get Sub-system config data
            Route::post('get-config', [ConfigController::class, 'getConfig'])->name('spid.config.getConfig');
        });
    });
});
