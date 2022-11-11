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

You can add _HasUserSpid_ Trait to your Auth Provider User Model

```php
use DeveloperUnijaya\RmsSpid\Traits\HasUserSpid;

class User extends Authenticatable
{
    use HasUserSpid;
}
```

Add _VerifySpidKey_ Middleware in _$routeMiddleware_ at _App\Http\Kernel.php_
You can optionally Enable or Disable the middleware on the published Config File in *spid_key* property
```php
protected $routeMiddleware = [
    // ...
    'verifyspidkey' => \DeveloperUnijaya\RmsSpid\Middleware\VerifySpidKey::class,
];
```

## Usage

Register new User to SPID:
Add following code to _app\Http\Controllers\Auth\RegisterController.php_ 
```php
use Illuminate\Http\Request;
use DeveloperUnijaya\RmsSpid\Helpers\SpidHelper;

public function registered(Request $request, $user)
{
    SpidHelper::regUserSpid($user->id);
}
```

Update user Registration Status:
```php
use DeveloperUnijaya\RmsSpid\Helpers\SpidHelper;

// Using SpidHelper
// Approve
SpidHelper::updateRegStatus($user_id, true);

// Reject
SpidHelper::updateRegStatus($user_id, false);

// Using Trait
// Approve
$user->approveSpidReg();

// Reject
$user->rejectSpidReg();
```

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
