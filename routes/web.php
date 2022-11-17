<?php

use DeveloperUnijaya\RmsSpid\Controllers\SsoController;
use Illuminate\Support\Facades\Route;

Route::prefix('spid')->middleware(['web'])->group(function () {

    Route::get('test', [SsoController::class, 'spidTest'])->name('spid.test');

    Route::prefix('sso')->group(function () {

        Route::post('auth', [SsoController::class, 'ssoAuth'])->name('spid.sso.auth');
        Route::get('auth/login/{user_spid_id}/{redirect_token}', [SsoController::class, 'ssoLogin'])->name('spid.sso.auth.login');
        Route::get('auth/failed/{failed_msg?}', [SsoController::class, 'ssoFailed'])->name('spid.sso.auth.failed');

    });
});
