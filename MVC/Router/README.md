# Rotas no Laravel 8

No laravel 7 e anteriores o namespace dos controllers era global e podiamos chamar numa rota assim:

Route::get('/test', 'TestController@index');

Agora no laravel 8, isso não mais funciona. Temos as alternativas:

```php
Route::get('/home', 'App\Http\Controllers\HomeController@index');
```
Ou então

```php
Route::get('/home', '[App\Http\Controllers\HomeController], 'index');
```
Ou
```php
Acima adicionar o:
use App\Http\Controllers\UserController;

E na rota:
Route::get('/user', [UserController::class, 'index']);
```

## Mesmo comportamento do 7 no Laravel 8

Para isso editamos o 

app/Providers/RouteServiceProvider.php

E descomentamos a linha com:

    protected $namespace = 'App\\Http\\Controllers';

Após salvar já podemos usar as rotas assim:

Route::get('/test', 'TestController@index');

E sem adicionar ao início o use...

Mas me parece que é bom se adaptar ao estilo default do laravel 8. Se pudermos evitar em nosso aplicativo elementos globais, melhor.

```php
php artisan make:controller PhotoController --resource

use app/Http/Controllers/PhotoController.php

Route::resource('photos', PhotoController::class);

Route::resources([
    'photos' => PhotoController::class,
    'posts' => PostController::class,
]);
```
Outros exemplos
```php
php artisan make:controller PhotoController --resource --model=Photo

Route::resource('photos', PhotoController::class)->only([
    'index', 'show'
]);

Route::resource('photos', PhotoController::class)->except([
    'create', 'store', 'update', 'destroy'
]);
```

## API
```php
Route::apiResource('photos', PhotoController::class);
Route::apiResource('photos', PhotoController::class);

php artisan make:controller API/PhotoController --api
Route::resource('photos.comments', PhotoCommentController::class);
Route::resource('photos.comments', PhotoCommentController::class)->scoped([
    'comment' => 'slug',
]);

Route::resource('photos.comments', CommentController::class)->shallow();
Route::resource('photos', PhotoController::class)->names([
    'create' => 'photos.build'
]);

Route::resource('users', AdminUserController::class)->parameters([
    'users' => 'admin_user'
]);

Route::get('photos/popular', [PhotoController::class, 'popular']);
Route::resource('photos', PhotoController::class);
```
