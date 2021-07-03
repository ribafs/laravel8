use Illuminate\Support\Facades\Auth;

```php
Auth::logout();

Auth::login($user);

// Login and "remember" the given user...

Auth::login($user, true);

Route::get('/settings', function () {
    // ...
})->middleware(['password.confirm']);

Route::post('/settings', function () {
    // ...
})->middleware(['password.confirm']);
```

When a user is successfully authenticated, they will typically be redirected to the /home URI. You can customize the post-authentication redirect path using the HOME constant defined in your RouteServiceProvider:
```php
public const HOME = '/home';
```

