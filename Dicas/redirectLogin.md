# Redirecionar após o login

What I did, make a HomeController with only index method, then:

## Changed RouteServiceProvider line to
```php
public const HOME = 'redirects';
```
Made a route:
```php
Route::get('redirects', 'App\Http\Controllers\HomeController@index');
```
And in HomeController it redirects depending on role:
```php
    public function index()
    {
        $role = Auth::user()->role;
        $checkrole = explode(',', $role);
        if (in_array('admin', $checkrole)) {
            return redirect('dog/indexadmin');
        } else {
            return redirect('pet/index');
        }

    }
```
Note

Most will need a leading slash (/) like:
```php
return redirect('/dog/indexadmin');
```
I don't because I set htacces to skip leading slash.

It works. But if you could a short example of a LoginResponse class, it would also help others.


I don't know why everyone's binding the Login Response Class in Jetstreams Service Provider...

Here's what I did. Works for both TwoFactorLogin and vanilla login:

## Create "LoginResponse.php" in "app/Http/Responses"
```php
<?php
namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $role = \Auth::user()->role;

        if ($request->wantsJson()) {
            return response()->json(['two_factor' => false]);
        }

        switch ($role) {
            case 'admin':
                return redirect()->intended(config('fortify.home'));
            case 'host':
                return redirect()->intended('/host/dashboard');
            default:
                return redirect('/');
        }
    }
}
```

## Create "TwoFactorLoginResponse.php" in "app/Http/Responses"
```php
<?php
namespace App\Http\Responses;

use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;

class TwoFactorLoginResponse implements TwoFactorLoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        $role = \Auth::user()->role;

        if ($request->wantsJson()) {
            return response('', 204);
        }

        switch ($role) {
            case 'admin':
                return redirect()->intended(config('fortify.home'));
            case 'host':
                return redirect()->intended('/host/dashboard');
            default:
                return redirect('/');
        }
    }
}
```

## Now register the response bindings in 'app\Providers\FortifyServiceProvider.php'
```php
<?php
namespace App\Providers;

use Illuminate\Support\Facades\Route;
use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Laravel\Fortify\Contracts\TwoFactorLoginResponse as TwoFactorLoginResponseContract;
use App\Http\Responses\LoginResponse;
use App\Http\Responses\TwoFactorLoginResponse;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerResponseBindings();
    }

    /**
     * Register the response bindings.
     *
     * @return void
     */
    protected function registerResponseBindings()
    {
        $this->app->singleton(LoginResponseContract::class, LoginResponse::class);
        $this->app->singleton(TwoFactorLoginResponseContract::class, TwoFactorLoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {        
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
    }
}
```

## Laravel 8 Conditional Login Redirect

How to provide different redirects at login when using Laravel Fortify

Laravel 8 introduces Fortify, a new back-end package for providing user authentication services. This represents a big departure from the controller with traits approach used in previous versions and has caused some concern that the authentication process is no longer customisable.

Of course, the maturity of the framework, and previous experiences would be unlikely to produce a framework where you could not override default behaviour.

Suppose we need to redirect the user as they are logging in based on some user attribute. With Fortify, how might this be possible?

The hooks that we require are bound into the container during the booting of the Laravel\Fortify\FortifyServiceProvider. Within our own code we can re-bind a different class where we will place our business logic.

## Create our own Login Response Class

    Create a folder under app\Http called Responses

    Create a file LoginResponse.php
```php
<?php
namespace App\Http\Responses;
​
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
​
class LoginResponse implements LoginResponseContract
{
​
    public function toResponse($request)
    {
        
        // below is the existing response
        // replace this with your own code
        // the user can be located with Auth facade
        
        return $request->wantsJson()
                    ? response()->json(['two_factor' => false])
                    : redirect()->intended(config('fortify.home'));
    }
​
}
```
## Make Laravel use our new Response Class

This new class now replaces the Singleton previously registered by Fortify.

Edit the JetstreamServiceProvider in your app\Providers folder;

In the boot method, add reference to your new response class. When login completes (and the user is actually Authenticated) then your new response will be called.
```php
    public function boot()
    {
        $this->configurePermissions();
​
        Jetstream::deleteUsersUsing(DeleteUser::class);
​
        // register new LoginResponse
        $this->app->singleton(
            \Laravel\Fortify\Contracts\LoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );
    }
```
## Two Factor Authentication

If you use 2FA with Jetstream, you will also need to catch the TwoFactorLoginResponse.  Use the same approach;

​```php
        // register new TwofactorLoginResponse
        $this->app->singleton(
            \Laravel\Fortify\Contracts\TwoFactorLoginResponse::class,
            \App\Http\Responses\LoginResponse::class
        );
```
You can return the same response, or create an additional response if you want different behaviour for users that login using 2FA.

https://talltips.novate.co.uk/laravel/laravel-8-conditional-login-redirects


