<?php

use DeveloperUnijaya\RmsSpid\Controllers\RmsSpidController;
use Illuminate\Support\Facades\Route;

Route::get('spid-test', [RmsSpidController::class, 'testSpid'])->name('spid.test');

Route::prefix('spid')->middleware(['web'])->group(function () {
    Route::post('sso/auth', [RmsSpidController::class, 'ssoAuth'])->name('spid.sso.auth');
    Route::get('sso/login/{user_spid_id}/{redirect_token}', [RmsSpidController::class, 'ssoLogin'])->name('spid.sso.login');
});
