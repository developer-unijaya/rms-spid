<?php

use DeveloperUnijaya\RmsSpid\Controllers\RmsSpidController;
use Illuminate\Support\Facades\Route;

Route::get('test-spid', [RmsSpidController::class, 'testSpid']);

Route::prefix('spid')->group(function () {
    Route::post('sso/auth', [RmsSpidController::class, 'ssoAuth'])->name('spid.sso.auth');
    Route::get('sso/login/{user_spid_id}/{redirect_token}', [RmsSpidController::class, 'ssoLogin'])->name('spid.sso.login');
});
