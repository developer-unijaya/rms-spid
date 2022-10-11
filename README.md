# eRMS-SPID Packages

[![Latest Version on Packagist](https://img.shields.io/packagist/v/developer-unijaya/rms-spid.svg?style=flat-square)](https://packagist.org/packages/developer-unijaya/rms-spid)
[![Total Downloads](https://img.shields.io/packagist/dt/developer-unijaya/rms-spid.svg?style=flat-square)](https://packagist.org/packages/developer-unijaya/rms-spid)

## Installation

You can install the package via composer:

```bash
composer require developer-unijaya/rms-spid
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="rms-spid-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="rms-spid-config"
```

This is the contents of the published config file:

```php
return [
    // Redirect route name after Successful SSO
    'redirect_sso' => 'spid.sso.auth',

    // Redirect route name after Failed SSO
    'redirect_sso_failed' => 'spid.sso.auth.failed',

    // When set to true, redirect_token can only be use once.
    // Delete redirect_token after successful redirect
    'redirect_token_once' => true,

    // Set redirect_token validity in minutes
    // Set to 0 for never expire
    'redirect_token_validity' => 5,

    // User Model
    // 'user_model' => App\Models\User::class,

    // User Model Eloquent Relationship to be included in Profile API
    'user_profile_relationship' => [],
];
```

## Usage

```php
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Syafiq Unijaya](https://github.com/syafiq-unijaya)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
