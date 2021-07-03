# Laravel 8.7 Released

September 30, 2020 / Paul Redmond

The Laravel team released 8.7 this week with new rate-limiting constructors and an onError() HTTP client method, along with the latest new features, fixes, and changes in the 8.x branch.

Add Per Hour and Per Day Constructors

User @ali-alharthi contributed perHour and perDay rate limiting for Laravel 8’s new RateLimiter functionality:
```php
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

RateLimiter::for('global', function (Request $request) {
    return Limit::perHour(1000);
});
```

Laravel 8 introduces some nice rate limiting improvements you should check out if you haven’t had a chance yet.
HTTP Client onError

Tim MacDonald contributed an onError() method to the HTTP client that accepts a callback:
```php
return $client->withHeaders($headers)
    ->post($url, $payload)
    ->onError(fn ($response) =>
        Log::error('Twitter API failed posting Tweet', [
            'url' => $url,
            'payload' => $payload,
            'headers' => $headers,
            'response' => $response->body(),
        ])
    )->json();
```
As suggested in the pull request, you can now pass a closure to the throw() method which will call the closure before throwing an exception:
```php
return $client->withHeaders($headers)
    ->post($url, $payload)
    ->throw(fn($response) => Log::error('Twitter API failed posting Tweet', [
        'url' => $url,
        'payload' => $payload,
        'headers' => $headers,
        'response' => $response->body(),
    ]))->json();
```
Artisan Serve “no-reload” Option

Taylor Otwell contributed a --no-reload flag to the Artisan serve command, which does not reload the development server on .env file changes:
```php
php artisan serve --no-reload
```
Release Notes

You can see the full list of new features and updates below and the diff between 8.6.0 and 8.7.0 on GitHub. The following release notes are directly from the changelog:

v8.7.0

Added
```php
    Added tg:// protocol in “url” validation rule (#34464)
    Allow dynamic factory methods to obey newFactory method on model (#34492, 4708e9e)
    Added no-reload option to serve command (9cc2622)
    Added perHour and perDay methods to Illuminate\Cache\RateLimiting\Limit (#34530)
    Added Illuminate\Http\Client\Response::onError() (#34558, d034e2c)
    Added X-Message-ID to Mailgun and Ses Transport (#34567)
```
Fixed
```php
    Fixed incompatibility with Lumen route function in Illuminate\Session\Middleware\StartSession (#34491)
    Fixed: Eager loading MorphTo relationship does not honor each models $keyType (#34531, c3f44c7)
    Fixed translation label (“Pagination Navigation”) for the Tailwind blade (#34568)
    Fixed save keys on increment / decrement in Model (77db028)
```
Changed
```php
    Allow modifiers in date format in Model (#34507)
    Allow for dynamic calls of anonymous component with varied attributes (#34498)
    Cast Expression as string so it can be encoded (#34569)
```
