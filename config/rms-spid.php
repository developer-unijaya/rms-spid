<?php

return [

    // Redirect route name after Successful SSO
    'redirect_sso_success' => 'home',

    // Redirect route name after Failed SSO
    'redirect_sso_failed' => 'spid.sso.auth.failed',

    // When set to true, redirect_token can only be use once.
    // Delete redirect_token after successful redirect
    // Note: Previously generated token will not be affected
    'redirect_token_once' => true,

    // Set redirect_token validity in minutes
    // Set to 0 for never expire
    // Note: Previously generated token will not be affected
    'redirect_token_validity' => 5,

    // User Model Eloquent Relationship to be included in Profile API
    'user_profile_relationship' => [],
];
