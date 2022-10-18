# eRMS-SPID Packages (API + SSO)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/developer-unijaya/rms-spid.svg?style=flat-square)](https://packagist.org/packages/developer-unijaya/rms-spid)
[![Total Downloads](https://img.shields.io/packagist/dt/developer-unijaya/rms-spid.svg?style=flat-square)](https://packagist.org/packages/developer-unijaya/rms-spid)

## Requirements

- PHP ^8.0
- Laravel ^8.0
- [Laravel Sanctum](https://laravel.com/docs/8.x/sanctum)

## Installation

You can install the package via composer:
```bash
composer require developer-unijaya/rms-spid
```


Publish and run the migrations with:
```bash
php artisan vendor:publish --tag="rms-spid-migrations"
php artisan migrate
```


Publish the views file with:
```bash
php artisan vendor:publish --tag="RmsSpidView-views"
```


Publish the config file with:
```bash
php artisan vendor:publish --tag="rms-spid-config"
```


This is the contents of the published config file:
```php
return [

    // SPID Key
    'spid_key' => null,

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
```


Add following route to VerifyCsrfToken Exception in _App\Http\Middleware\VerifyCsrfToken.php_
```php
class VerifyCsrfToken extends Middleware
{
    protected $except = [
        "spid/*"
    ];
}
```


Check and Locate your Auth Provider User Model
config\auth.php
```php
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class, // <= Your Auth Provider User Model
    ],
],
```

Add Laravel Sanctum _HasApiTokens_ Trait to your Auth Provider User Model
```php
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
}
```


## Usage


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Syafiq Unijaya](https://github.com/syafiq-unijaya)
- [Developer Unijaya](https://github.com/developer-unijaya)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
