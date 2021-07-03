# Fazendo upgrade de uma aplicação com a versão 7 para a versão 8

https://laravel.com/docs/8.x/upgrade

https://laravel-zero.com/docs/upgrade/

## Requisitos

PHP 7.3

## Atualização do composer.json
```php
require e require-dev do composer.json
```
    • guzzlehttp/guzzle to ^7.0.1 
    • facade/ignition to ^2.3.6 
    • laravel/framework to ^8.0 
    • nunomaduro/collision to ^5.0 
    • phpunit/phpunit to ^9.0 

## Opcionais
    • Horizon v5.0 
    • Passport v10.0 
    • Socialite v5.0 
    • Telescope v4.0 

## O instalador global agora tem a versão 4^

## Código
```php
$collection = collect([null]);

// Laravel 7.x - true
isset($collection[0]);

// Laravel 8.x - false
isset($collection[0]);
```
## Factories e seeders agora são classes e usam namespace
```php
namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
```
Foram adicionados ao composer.json
```php
"autoload": {
    "psr-4": {
        "App\\": "app/",
        "Database\\Factories\\": "database/factories/",
        "Database\\Seeders\\": "database/seeders/"
    }
},
```
Para facilitar o processo de upgrade e continuar usando as factories do 7 foi criado
```php
composer require laravel/legacy-factories
```
## Paginação

Agora usa o Tailwind CSS framework (https://tailwindcss.com/) como default

Para continuar usando o Bootstrap na paginação faça
```php
AppServiceProvider:
use Illuminate\Pagination\Paginator;
```
No método boot()
```php
Paginator::useBootstrap();
```
## Validação

As rules unique e exists agora respeitarão o nome de conexão especificado (acessado por meio do método getConnectionName do modelo) dos modelos Eloquent ao executar consultas.

## Executando
```php
composer install
composer update
```
Criar banco e conf. no .env
```php
php artisan migrate --seed
php artisan serve
localhost:8000
```

