<?php

return [
    // Redirect route name after Successful SSO
    'route_home' => 'home',

    // When set to true, redirect_token can only be used once
    'strict_redirect_token' => false,

    // User Model
    'user_model' => App\Models\User::class,
];
