<?php

use DeveloperUnijaya\RmsSpid\Controllers\RmsSpidController;
use Illuminate\Support\Facades\Route;

Route::get('test-spid', [RmsSpidController::class, 'testSpid']);

Route::prefix('spid')->group(function () {
    Route::post('sso/auth', [RmsSpidController::class, 'ssoAuth']);
});
