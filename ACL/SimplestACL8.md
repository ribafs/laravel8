# Laravel 8 - Simplest ACL

Um projeto para implementar ACL em aplicativos com o laravel 8 da forma mais simples que conheço. Praticamente usando apenas a autenticação do laravel para controlar autenticação e autorização. Sem usar roles, permissions, middlewares, traits, gates nem policies.

O aplicativo inicial conta com um menu que pode levar ao login, register e aos CRUDs: users, clients e products dependendo do privilégio do usuário
Aqui iniciaei com as seguites roles

```php
super - pode tudo em todo o aplicativo
admin - pode tudo apenas em users
manager - pode tudo em clients e products
user - pode apenas logar e acessar index e show de clients, mas sem mesmo visualizar nenhum botão de index, exceto View e em show somente Back
test - apenas login (autenticação) mas sem acesso a nenhum dos CRUDs.
```
As roles acima são identificadas apenas pelo nome do usuário autenticado e pelo código nas regiões do aplicativo: controllers, routes e views.

## Detalhes da criação do Simples ACL

= Instalar o laravel 8

laravel new simplest-acl8 --jet

cd simplest-acl8

= Configurar

.env
```php
APP_NAME='Laravel 8 - Simplest ACL'

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=acl
DB_USERNAME=root
DB_PASSWORD=
```
= Copiar o CRUD products
    
Mude na migration users original a linha
```php
            $table->id();
para
            $table->increments('id');
```
= A paginação agora está a cargo do Tailwind CSS framework (https://tailwindcss.com/) para o estilo default

Para continuar usando bootstrap adicione para o método boot do AppServiceProvider

use Illuminate\Pagination\Paginator;

Paginator::useBootstrap();

= Adicionar Seeders
```php
php artisan make:seed UsersSeeder
php artisan make:seed ProductsSeeder

<?php

use Illuminate\Database\Seeder;
use App\User;

class UsersSeeder extends Seeder
{
    public function run()
    {
        // Super Users - all in all
        $super = new User();
        $super->name = 'Super';
        $super->email = 'super@gmail.com';
        $super->password = bcrypt('123456');
        $super->save();

        // Super Admin - all in users
        $admin = new User();
        $admin->name = 'Admin';
        $admin->email = 'admin@gmail.com';
        $admin->password = bcrypt('123456');
        $admin->save();

        // Super Manager - all in clients and products
        $manager = new User();
        $manager->name = 'Manager';
        $manager->email = 'manager@gmail.com';
        $manager->password = bcrypt('123456');
        $manager->save();

        // User - only index and show from clients
        $user = new User();
        $user->name = 'User';
        $user->email = 'user@gmail.com';
        $user->password = bcrypt('123456');
        $user->save();

        // Test - only login
        $user = new User();
        $user->name = 'Test';
        $user->email = 'test@gmail.com';
        $user->password = bcrypt('123456');
        $user->save();
    }
}

class ProductsSeeder extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();

        for($i=0; $i<=100; $i++):
            DB::table('products')
                ->insert([
                'name'      => $faker->userName,
                'price'      => $faker->numberBetween(5,50),
                ]);
        endfor;
    }
}

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersSeeder::class);
        $this->call(ClientsSeeder::class);
        $this->call(ProductsSeeder::class);
    }
}
```
= Popular banco
```php
php artisan migrate
php artisan db:seed
```
= Implementar customizações

Remover o link de registro da view welcome.blade.php
Deixando assim:
```php
    <body>
        <div class="flex-center position-ref full-height">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>
                    @endauth
                </div>
            @endif
```
- Feito

Alternativa

- Para tabelas com muitos campos podemos remover o sidebar da view para deixar uma área maior para os mesmos e mostrar o include top_menu, descomentando em app.blade

- Remover views/admin - Mover sidebar e dashboard de admin para includes e ajustar os links nas views de cada crud - Feito
- Melhorar welcome, home e demais views (claro que ainda pode ser bem melhoradas)- Feito

Trocar o código do logout em app.blade.php pelo abaixo para evitar problemas de não funcionar
```php
<li class="nav-item">
    <form id="logout-form" action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">
            {{ __('Logout') }}
        </button>
    </form>
</li>
```
- Feito

= Testar
```php
php artisan serve
localhost:8000/clientes

Testar com cada um dos users para ver a diferença
- Super - super@gmail.com - 123456 - pode tudo em todas os CRUDs
- Admin - admin@gmail.com - 123456 - pode tudo em users
- Manager - managegr@gmail.com - 123456 - pode tudo em clients e products
- User - user@gmail.com - 123456 - pode somente list e show em clients e não deve ver os botões do index, exceto o View e no show ver somente o Back
- Test - test@gmail.com - 123456 - pode somente logar e mais nada
```


