# Novidades do Laravel 8

O Laravel 8 foi lançado em 8 de setembro de 2020. Lembrando que a cada 6 meses é lançado um novo release. Laravel 8 não é uma versão LTS. Terá suporte e coreções até 8 de setembro de 2021.

O novo sistema de scaffolding do laravel 8 é o JetStream. Um sistema completo de autenticação e que se integra com os modernos front-ends como Vue e Tailwind.

laravel 8 introduce jetstream for login, registration, email verification, two-factor authentication, session management, API support and team management.

Laravel 8 continua os avanços feitos no Laravel 7.x introduzindo Laravel Jetstream, model factory classes, migration squashing, job batching, improved rate limiting, queue improvements, dynamic Blade components, Tailwind pagination views, time testing helpers, improvements to artisan serve, event listener improvements, and a variety de outros bug fixes e usability improvements.

## Instalação do laravel 8

Comece fazendo upgrade do instalador do laravel

composer upgrade laravel/installer

ou

composer global require "laravel/installer:^4.0"

laravel -v


## Instalação Simples

laravel new blog


## Instalação com o JetStream

laravel new blog --jet

Será perguntado pela stack

0 - livewire

1 - inertia

Depois se deseja team

yes - para que seja instalado o suporte


## Instalação com o JetStream e o LiveWire

laravel new blog --jet --stack=livewire

Será perguntado se deseja Team

## Instalar com o JetStream e o Inertia

laravel new blog --jet --stack=inertia

## Instalar os assets

cd blog

npm install && npm run dev


## Versão do instalador

Após instalar pode verificar a versão do instalador do laravel com

laravel --version

ou

laravel -V


## Novos métodos das migrations

Eles vieram ainda na versão 7 do laravel


## Novidades

- two_factor
- personal_access_token
- teams
- team_user
- sessions

A view default, do raiz '/' é a welcome

Após o login somos redirecionados para a view dashboard

## Models

Agora por default na pasta app/Models

Por default o model User está agora assim:
```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use HasTeams;
    use Notifiable;
    use TwoFactorAuthenticatable;
```

Quando criamos um model com a versão 8 usando o artisan ele vem:

php artisan make:model Teste

app/Models/Teste.php
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teste extends Model
{
    use HasFactory;
}
```

## Factories

Agora factories são classes e tem namespace

    namespace Database\Factories;

## Seeders

Seeders também

    namespace Database\Seeders;

### Fique atento:

- na versão 7 o diretório chamava-se database/seeds e agora é database/seeders.

## Agora a pasta app vem também com:
```php
    Actions
    Events
    View
```
## Migrations

A tabela session, que agora vem nas migrations, é obrigatória para rodar o aplicativo, ou seja, precisamos executar antes de rodar:

php artisan migrate

Antes de executar o

php artisan serve

## Avatar

Clicando no avatar acima e à direita temos:
- Profile
- API Token
- Team Settings
- Create new team
- Logout

Após o login podemos configurar o profile do usuário

Podemos adicionar uma imagem local, trocar a senha, implementar o 2-factor e até excluir a conta.

A imagem do avatar fica em storage/app/livewire-tmp


## Exportar banco de dados
```php
php artisan schema:dump
```
// Dump the current database schema and prune all existing migrations...
```php
php artisan schema:dump --prune
```
When you execute this command, Laravel will write a "schema" file to your database/schema directory.

SQLite support for the new schema:dump artisan command

## Gerador de commands

Na versão 8 todo gerador de comandos irá detectar se temos uma pasta Models. Se não tivermos ele assumirá a pasta "app".

## Modo de manutenção
```php
php artisan down --secret="1630542a-246b-4b66-afa1-dd72a4c43515"
```
https://example.com/1630542a-246b-4b66-afa1-dd72a4c43515
```php
php artisan down --render="errors::503"
php artisan down --render="errors::503" --status=200 --secret=YOUR_SECRET_HERE

php artisan down --render="errors::back-soon"
```
Laravel will pre-render the view (in this case errors/back-soon.blade.php) and then put the site into maintenance mode.

you no longer have to manually restart "php artisan serve" if you update your ".env" file

## Componente dinãmico para blade
```php
<x-dynamic-component :component="$componentName" class="mt-4" />
```

## Tailwind Pagination Views

The Laravel paginator has been updated to use the Tailwind CSS framework by default. Tailwind CSS is a highly customizable, low-level CSS framework that gives you all of the building blocks you need to build bespoke designs without any annoying opinionated styles you have to fight to override. Of course, Bootstrap 3 and 4 views remain available as well.


## Cleaner syntax for closure based event listeners

In previous Laravel versions, when registering a closure based event listener, you had to first define the event class, then register the closure, and probably type hint the event class for the closure e.g.:
```php
Event::listen(OrderShipped::class, function(OrderShipped $event) { 
    // Do something
});
```
In Laravel 8, you'll be able to skip the first definition of the event class as the framework will be able to infer it from the type hinted argument - e.g.:
```php
Event::listen(function(OrderShipped $event) { 
    // Do something
});
```

Laravel 8 Jetstream provides new all feature are configurable. you can see there is a configuration file fortify.php and jetstream.php file where you can enable and disable option for that feature:
```php
config/fortify.php
....
  
'features' => [
        Features::registration(),
        Features::resetPasswords(),
        Features::emailVerification(),
        Features::updateProfileInformation(),
        Features::updatePasswords(),
        Features::twoFactorAuthentication(),
    ],
...
config/jetstream.php
....
  
'features' => [
        Features::profilePhotos(),
        Features::api(),
        Features::teams(),
    ],
...
```

Please note that the Laravel team recommends developers to use Jetstream for new Laravel 8 projects but they have also updated the laravel/ui package to version 3 for using with Laravel 8, especially if you are updating your previous Laravel 7 app to the latest version.


