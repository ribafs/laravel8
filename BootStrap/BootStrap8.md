# BootStrap no Laravel 8

## Usando o Bootstrap no front do Laravel 8
```bash
laravel new blog8 --jet --teams --stack=livewire
cd blog8
composer require laravel/ui
php artisan ui bootstrap --auth
    Sobrescrever todos os existentes
npm install && npm run dev
```
## Paginação

Para continuar usando bootstrap adicione para o método boot do AppServiceProvider

app/Providers/AppServiceProvider.php

use Illuminate\Pagination\Paginator;

Adicionar ao método boot

Paginator::useBootstrap();

Para que fique assim:

```php
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
    }
}
```

https://laravel.com/docs/8.x/pagination#using-bootstrap

