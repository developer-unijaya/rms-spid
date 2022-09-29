<?php

return [

    // Redirect route name after Successful SSO
    'redirect_sso' => 'spid.sso.auth',

    // When set to true, redirect_token can only be used once
    'strict_redirect_token' => false,

    // User Model
    'user_model' => App\Models\User::class,

    // User Eloquent Relationship to be included in Profile API
    'user_profile_relationship' => [],
];
