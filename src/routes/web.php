<?php

use DeveloperUnijaya\RmsSpid\Controllers\RmsSpidController;
use Illuminate\Support\Facades\Route;

// Route::get('test-spid', RmsSpidController::class);

Route::get('test-spid', [RmsSpidController::class, 'testSpid']);