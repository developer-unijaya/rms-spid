# eRMS-SPID Packages

[![Latest Version on Packagist](https://img.shields.io/packagist/v/developer-unijaya/rms-spid.svg?style=flat-square)](https://packagist.org/packages/developer-unijaya/rms-spid)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/developer-unijaya/rms-spid/run-tests?label=tests)](https://github.com/developer-unijaya/rms-spid/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/developer-unijaya/rms-spid/Fix%20PHP%20code%20style%20issues?label=code%20style)](https://github.com/developer-unijaya/rms-spid/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/developer-unijaya/rms-spid.svg?style=flat-square)](https://packagist.org/packages/developer-unijaya/rms-spid)

<!--/delete-->
This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require developer-unijaya/rms-spid
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="developer-unijaya/rms-spid-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="developer-unijaya/rms-spid-config"
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
