# Novidades do Laravel 8

## Principais ferramentas novas:

- JetStream - https://jetstream.laravel.com/1.x/introduction.html
- TailWind - https://tailwindcss.com/ e https://jetstream.laravel.com/1.x/installation.html#tailwind
- Fortify - https://github.com/laravel/fortify e https://jetstream.laravel.com/1.x/features/authentication.html#laravel-fortify
- Livewire - https://laravel-livewire.com/ e https://jetstream.laravel.com/1.x/installation.html#livewire
- Inertia - https://inertiajs.com/ e https://github.com/inertiajs/inertia-laravel e https://jetstream.laravel.com/1.x/stacks/inertia.html

## Onde mudou

- factories e seeders agora precisam usar namespace.
- A pasta database/seeds agora é database/seeders
- models agora vem, por padrão em app/Models, mas em "app" também ainda são aceitos
- models usa factories, como abaixo
- Desde a versão 7 já podemos usar os novos métodos das migrations
- Rotas não chamam mais os controllers diretamente, mas passando seu namespace ou usando "use App\Http\Controllers\NomeController"


## Models:

- Agora ficam em app/Models e usam o namespace App\Models
- Agora usam:
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ola extends Model
{
    use HasFactory;

    public $msgOla = 'Olá do model';
}
```
A pasta default dos models pode ser alterada em

config/auth.php
```php
...
    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
...
```

