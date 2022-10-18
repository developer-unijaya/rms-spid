<?php

return [

    // Set NULL to disable VerifySpidKey Middleware
    // Set to any UUID Value to enable
    'spid_key' => null,

    // Users ID that allowed to access Login API
    // Set to Empty to enable All User Access (Not Secure)
    'spid_users_id' => [],

    // Redirect route name after Successful SSO
    'redirect_sso_success' => 'home',

    // Redirect route name after Failed SSO
    'redirect_sso_failed' => 'spid.sso.auth.failed',

    // When set to true, redirect_token can only be used once.
    // The redirect_token will be deleted after successful redirect
    // Note: Previously generated token will not be affected
    'redirect_token_once' => true,

    // Set redirect_token validity in minutes
    // Set 0 to never expire
    // Note: Previously generated token will not be affected
    'redirect_token_validity' => 5,

    // User Model Eloquent Relationship to be included in Profile API
    'user_profile_relationship' => [],
];
