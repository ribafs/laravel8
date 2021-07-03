# Assign Expiration Dates to Eloquent Models

October 01, 2020 / Paul Redmond

Laravel Model Expires is a package by Mark van den Broek that assigns expiration dates to models and gives you some conveniences for working with them. The package provides a convenient trait to make your models "expirable" in a snap:
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mvdnbrk\EloquentExpirable\Expirable;

class Subscription extends Model
{
    use Expirable;
}
```
You’ll also need to set up an expires_at field to the model’s database table, which is easy using the package’s helper in your migrations:
```php
Schema::create('subscriptions', function (Blueprint $table) {
    $table->expires();
});
```
The expires_at column is cast to a Carbon instance, and you can also customize the name of the column if you desire in your model.

You’ll want to refer to the package’s documentation for full details of configuration, setting expiration dates, and working with models. Here are a few examples of methods provided to “expirable” models:
```php
// Already has expired?
$subscription->expired()

// Will expire in the future?
$subscription->willExpire()

// Only get expired models
$subscriptions = Subscription::onlyExpired()->get();

// Models expiring in the future
$subscriptions = Subscription::expiring()->get();
```
You can learn more about this package, get full installation instructions, and view the source code on GitHub at mvdnbrk/laravel-model-expires.

This package was submitted to our Laravel News Links section. Links is a place the community can post packages and tutorials around the Laravel ecosystem. Follow along on Twitter @LaravelLinks

https://laravel-news.com/laravel-eloquent-expiration

https://github.com/mvdnbrk/laravel-model-expires

