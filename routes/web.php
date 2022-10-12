<?php

use DeveloperUnijaya\RmsSpid\Controllers\RmsSpidSsoController;
use Illuminate\Support\Facades\Route;

Route::get('spid-test', [RmsSpidSsoController::class, 'testSpid'])->name('spid.test');

Route::prefix('spid')->middleware(['web'])->group(function () {
    Route::post('sso/auth', [RmsSpidSsoController::class, 'ssoAuth'])->name('spid.sso.auth');
    Route::get('sso/auth/failed/{failed_msg?}', [RmsSpidSsoController::class, 'ssoAuthFailed'])->name('spid.sso.auth.failed');

    Route::get('sso/login/{user_spid_id}/{redirect_token}', [RmsSpidSsoController::class, 'ssoLogin'])->name('spid.sso.login');
});
