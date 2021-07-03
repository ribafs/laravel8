# Laravel Routing – 8 Advanced Tips: Languages, APIs, Groups, Validation

We all use simple Route::get() and Route::post() syntax, but in bigger projects, it gets much more complicated. This article will compile various tips for different situations.

## Tip 1. Route::get() BEFORE Route::resource()

For Resource Controllers, this is one of the most common mistakes, see this example:

Route::resource('photos', 'PhotoController');

Route::get('photos/popular', 'PhotoController@method');

The second route won’t be accurate, you know why? Because it will match show() method of Route::resource() which is /photos/{id}, which will assign “popular” as $id parameter.

So if you want to add any get/post route, in addition to Route::resource(), you need to put them BEFORE the resource. Like this:

Route::get('photos/popular', 'PhotoController@method');

Route::resource('photos', 'PhotoController');

## Tip 2. Group in Another Group

We probably all know that we can group routes with Route::group() and assign different middlewares/prefixes and other parameters, like public routes and logged-in routes.

But what if you need a certain set of rules for sub-groups of those groups?

A typical example: you need public routes and authenticated routes, but within the authenticated group, you need to separate administrators from simple users.

So you can do this:
```php
// public routes
Route::get('/', 'HomeController@index');

// Logged-in users - with "auth" middleware
Route::group(['middleware' => ['auth']], function () {

    // /user/XXX: In addition to "auth", this group will have middleware "simple_users"
    Route::group(['middleware' => ['simple_users'], 'prefix' => 'user'], function () {
        Route::resource('tasks', 'TaskController');
    });

    // /admin/XXX: This group won't have "simple_users", but will have "auth" and "admins"
    Route::group(['middleware' => ['admins'], 'prefix' => 'admin'], function () {
        Route::resource('users', 'UserController');
    });
});
```
## Tip 3. Route Parameter Validation – Multi-Language Example

A pretty typical case is to prefix your routes by language locale, like fr/blog and en/article/333. How do we ensure that those two first letters are not used for some other than language?

We can validate it directly in the route, with “where” parameter:
```php
Route::group(['prefix' => '{locale}', 'where' => ['locale' => '[a-zA-Z]{2}']], function () {
    Route::get('/', 'HomeController@index');
    Route::get('article/{id}', 'ArticleController@show');
});
```
The main part here is ‘where’ => [‘locale’ => ‘[a-zA-Z]{2}’] where we use a regular expression to match only two-letter combinations.

## Tip 4. Dynamic Subdomain Routing

This comes directly from the official Laravel documentation, but rarely used so I think to mention it. 

If you have a dynamic subdomain, like a different subdomain for every user, it needs to become a variable, right? Laravel has done that automatically for you. See example:
```php
Route::domain('{account}.myapp.com')->group(function () {
    Route::get('user/{id}', function ($account, $id) {
        //
    });
});
```
Note that {account} automatically is passed as $account parameter in all inside controllers methods, so you need to accept that in all of them, don’t forget.

## Tip 5. Be Careful with Non-English Route Model Binding

Sometimes, URLs should contain non-English words. For example, you have a Spanish portal for books and you want to have URL like /libros for the book list, and single book would have /libros/1, like a normal Resource Controller.

But in the database, all names should all be in English, so Laravel “magic” could work between singular and plural, right?

So if you generate a model Book with migration and Controller, you may have this command:
```php
php artisan make:model Book -mcr
```
That -mcr key would generate a model and a resource controller, read more in this article. So, in that Controller, you would have this:
```php
/**
 * Display the specified resource.
 *
 * @param  \App\Book  $book
 * @return \Illuminate\Http\Response
 */
public function show(Book $book)
{
    // ...
}
```
But in your routes/web.php, you would have this:

Route::resource('libros', 'BookController');

The problem is that it wouldn’t work. An even bigger problem is that it wouldn’t throw any error, just $book would be empty, and you wouldn’t understand why.

According to the official Resource Controller description, the name of the variable should be the same as a parameter in singular:
```php
// So instead of
public function show(Book $book)
{
    // ...
}

// You should have
public function show(Book $libro)
{
    // ...
}
```
But, to be very honest, in projects with non-English projects, I would suggest to not use Route::resource and Route Model Binding at all. Too much “magic” is unpredictable, like how Laravel would “guess” that singular of “libros” is “libro”? 

## Tip 6. API Routes – from V1 to V2

Imagine you’re working with API-based project and you need to release a new version of this API. So older endpoints will stay at api/[something], and for new version you would use api/V2/[something].

The whole logic is in app/Providers/RouteServiceProvider.php:
```php
public function map()
{
    $this->mapApiRoutes();

    $this->mapWebRoutes();

    // ...
}

protected function mapWebRoutes()
{
    Route::middleware('web')
        ->namespace($this->namespace)
        ->group(base_path('routes/web.php'));
}

protected function mapApiRoutes()
{
    Route::prefix('api')
        ->middleware('api')
        ->namespace($this->namespace)
        ->group(base_path('routes/api.php'));
}
```
As you can see, API routes are registered in a separate function with prefix api/.

So, if you want to create V2 route group, you can create a separate routes/api_v2.php and do this:
```php
public function map()
{
    // ... older functions

    $this->mapApiV2Routes();
}

// And new function
protected function mapApiV2Routes()
{
    Route::prefix('api/V2')
        ->middleware('api')
        ->namespace($this->namespace)
        ->group(base_path('routes/api_v2.php'));
}
```
This way, old routes wouldn’t break, and you would just create a new set of routes.

## Tip 7. Rate Limiting – Global and for Guests/Users

This also comes from official documentation, but with less-known details.

First, you can limit some URL to be called a maximum of 60 times per minute, with throttle:60,1.
```php
Route::middleware('auth:api', 'throttle:60,1')->group(function () {
    Route::get('/user', function () {
        //
    });
});
```
But did you know you can do it separately for public and for logged-in users?
```php
// maximum of 10 requests per minute for guests 60 for authenticated users
Route::middleware('throttle:10|60,1')->group(function () {
    //
});
Also, you can have a DB field users.rate_limit and limit the amount for specific user:
Route::middleware('auth:api', 'throttle:rate_limit,1')->group(function () {
    Route::get('/user', function () {
        //
    });
});
```
## Tip 8. Route List and Route Caching

Final tip – how to check existing routes.

Not all of you know what routes exactly are hidden under Route::resource(), or under some more complex Route::group statement.

But at any point, you can check your actual route with Artisan command:
php artisan route:list

Keep in mind, that you are using route caching, after every change of your routes, you need to launch command:
```php
php artisan route:clear
```
https://quickadminpanel.com/blog/laravel-routing-8-advanced-tips-languages-apis-groups-validation/

## Laravel 8: Routing Change 

 Sholley O. ・2 min read 

With the release of Laravel 8, there has been a change to the routing syntax which might catch you by surprise, at least it did to me. It wasn't under the high or low impact changes so I skipped reading to the end and it came back to haunt me.

In a fresh laravel 8 install, I have this in my web.php file:
```php
Route::get('/', 'HomeController@index');
```
and this in the index method of the HomeController.php
```php
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('welcome');
    }
```
This should be very familiar and we'd be expecting a view when we visit the / route. Rather what we see is an error page

You might start thinking you messed thing up, but no you didn't. Rather, in laravel 8 this syntax doesn't work by default. In the upgrade guide, it was mentioned but the likelihood of impact was optional 🤨. Though this was mentioned:

In most cases, this won't impact applications that are being upgraded because your RouteServiceProvider will still contain the $namespace property with its previous value. However, if you upgrade your application by creating a brand new Laravel project, you may encounter this as a breaking change.

The way to define your routes in laravel 8 is either
```php
use App\Http\Controllers\HomeController;

// Using PHP callable syntax...
Route::get('/', [HomeController::class, 'index']);
```
OR
```php
// Using string syntax...
Route::get('/', 'App\Http\Controllers\HomeController@index');
```
A resource route becomes
```php
Route::resource('/', HomeController::class);
```
This means that in laravel 8, there is no automatic controller declaration prefixing by default.

If you want to stick to the old way, then you need to add a namespace property in the RouteServiceProvider.php (This property was present in previous versions) and activate in the routes method.
```php
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    // Edit boot method  
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('web')
                ->namespace($this->namespace)
                ->group(base_path('routes/web.php'));

            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->namespace)
                ->group(base_path('routes/api.php'));
    });
```
To recap, in laravel 8 you can use the new syntax to define your routes or stick to the old way.

https://dev.to/mr_steelze/laravel-8-routing-change-25co


## Outras Dicas sobre rotas no laravel 8
```php
// Generating Redirects...
return redirect()->route('profile');

public function handle($request, Closure $next)
{
    if ($request->route()->named('profile')) {
        //
    }

    return $next($request);
}

Route::middleware(['first', 'second'])->group(function () {
    Route::get('/users', function () {
        // Uses first & second middleware...
    });

    Route::get('clients', function () {
        // Uses first & second middleware...
    });
});

Route::prefix('admin')->group(function () {
    Route::get('users', function () {
        // Matches The "/admin/users" URL
    });
});

Route::name('admin.')->group(function () {
    Route::get('users', function () {
        // Route assigned name "admin.users"...
    })->name('users');
});
```
## Fallback Routes

Using the Route::fallback method, you may define a route that will be executed when no other route matches the incoming request. Typically, unhandled requests will automatically render a "404" page via your application's exception handler. However, since you may define the fallback route within your routes/web.php file, all middleware in the web middleware group will apply to the route. You are free to add additional middleware to this route as needed:
```php
Route::fallback(function () {
    //
});
```
Accessing The Current Route

You may use the current, currentRouteName, and currentRouteAction methods on the Route facade to access information about the route handling the incoming request:
```php
$route = Route::current();

$name = Route::currentRouteName();

$action = Route::currentRouteAction();


Route::get('/', function () {
    return view('login');
})->name('login');

Route::get('login', [userLogin::class, 'showLoginForm'])->name('login');

```


