# RedirectMiddleware

```php
php artisan make middleware:RedirectMiddleware 

<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class RedirectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (Auth::user()->role == 1) {
            return redirect()->route('super/users');
        }

        if (Auth::user()->role == 5) {
            return redirect()->route('academy');
        }

        if (Auth::user()->role == 6) {
            return redirect()->route('scout');
        }

        if (Auth::user()->role == 4) {
            return redirect()->route('team');
        }

        if (Auth::user()->role == 3) {
            return $next($request);
        }

        if (Auth::user()->role == 2) {
            return redirect()->route('admin');
        }
    }

}
```
