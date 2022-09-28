## eRMS-SPID SSO API Package

System Requirement:
<ol>
    <li> PHP version: <pre>7.0.0 and above</pre></li>
    <li> Laravel Sanctum (Installed and configured) </li>
    <li>  </li>
</ol>

Instructions:
<ol>
    <li> To install, run : <pre> composer require developer-unijaya/rms-spid </pre> </li>
    <li> <pre> php artisan migrate </pre> </li>
</ol>

Add Exception:
<pre>
namespace App\Http\Middleware;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    protected $except = [
        'spid/*'
    ];
}
</pre>
