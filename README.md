## eRMS-SPID SSO API

### System Requirement:
<ol>
    <li> PHP ^7.0 </li>
    <li> Laravel Sanctum </li>
</ol>


### Instructions:


```
composer require developer-unijaya/rms-spid
``` 

```
php artisan migrate
``` 

```
php artisan vendor:publish --provider="DeveloperUnijaya\RmsSpid\Providers\RmsSpidProvider" --tag="config"
``` 


### Add Exception *'spid/*'* in app\Http\Middleware\VerifyCsrfToken.php
```php
class VerifyCsrfToken extends Middleware
{
    protected $except = [
        'spid/*'
    ];
}
```
