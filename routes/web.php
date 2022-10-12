<?php

use DeveloperUnijaya\RmsSpid\Controllers\RmsSpidSsoController;
use Illuminate\Support\Facades\Route;

Route::prefix('spid')->middleware(['web'])->group(function () {

    Route::get('test', [RmsSpidSsoController::class, 'spidTest'])->name('spid.test');

    Route::prefix('sso')->group(function () {

        Route::post('auth', [RmsSpidSsoController::class, 'ssoAuth'])->name('spid.sso.auth');
        Route::get('auth/login/{user_spid_id}/{redirect_token}', [RmsSpidSsoController::class, 'ssoLogin'])->name('spid.sso.auth.login');
        Route::get('auth/failed/{failed_msg?}', [RmsSpidSsoController::class, 'ssoFailed'])->name('spid.sso.auth.failed');

    });
});
