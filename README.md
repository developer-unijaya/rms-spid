## eRMS-SPID SSO API

### System Requirement:
<ol>
    <li> PHP ^7.0 </li>
    <li> Laravel Sanctum </li>
</ol>

### Instructions:
<ol>
    <li> <pre> composer require developer-unijaya/rms-spid </pre> </li>
    <li> <pre> php artisan migrate </pre> </li>
    <li> <pre> php artisan vendor:publish --provider="DeveloperUnijaya\RmsSpid\Providers\RmsSpidProvider" --tag="config" </pre> </li>
</ol>

### Add Exception *'spid/*'* in app\Http\Middleware\VerifyCsrfToken.php
<pre>
class VerifyCsrfToken extends Middleware
{
    protected $except = [
        'spid/*'
    ];
}
</pre>
