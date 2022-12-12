<?php

return [

    // SPID Base URL
    'spid_base_url' => env('SPID_BASE_URL', null),

    // SPID Cred
    'spid_username' => env('SPID_USERNAME', null),
    'spid_password' => env('SPID_PASSWORD', null),

    // Set NULL to disable VerifySpidKey Middleware
    // Set to any UUID value to enable
    'spid_key' => env('SPID_KEY', null),

    // Timezone setting
    'timezone' => env('SPID_TIMEZONE', 'Asia/Kuala_Lumpur'),

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

    // Log options
    'enable_log' => env('SPID_LOG', true),

];
