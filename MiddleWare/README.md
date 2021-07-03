# Middleware no Laravel 8

## Conteúdo

### Teoria
### Exemplo simples
### Referências

![](middleware.jpeg)

O middleware fornece um mecanismo conveniente para filtrar solicitações/requests HTTP que entram em seu aplicativo. Por exemplo, o Laravel inclui um middleware que verifica se o usuário do seu aplicativo está autenticado. Se o usuário não estiver autenticado, o middleware redirecionará o usuário para a tela de login. No entanto, se o usuário estiver autenticado, o middleware permitirá que a solicitação prossiga no aplicativo.

Middleware adicional, ou seja, criados pelo programador, pode ser escrito para executar uma variedade de tarefas além da autenticação. Um middleware CORS pode ser responsável por adicionar os cabeçalhos apropriados a todas as respostas que saem de seu aplicativo. Um middleware de registro pode registrar todas as solicitações recebidas em seu aplicativo.

Existem vários middleware incluídos no framework Laravel, nativos, incluindo middleware para autenticação e proteção CSRF. Todos esses middleware estão localizados no diretório app/Http/Middleware.

Um middleware age como uma ponte entre um request e um response. É um tipo de mecanismo de filtro do que entra/requests no aplicativo.

O Laravel já traz nativamente alguns middlwares, que ficam adeaudaamente na pasta app/Http/Middleware. A versão 8.15 do Laravel traz estes:

- Authenticate.php
- EncryptCookies.php
- PreventRequestsDuringMaintenance.php
- RedirectIfAuthenticated.php
- TrimStrings.php
- TrustHosts.php
- TrustProxies.php
- VerifyCsrfToken.php

O primeiro verifica se um usuário está ou não autenticado. Caso o usuário esteja autenticado será redirecionado para a rota /home (caso o JetStream não esteja instalado, se sim irá para a dashboard), caso não esteja autenticado será redirecionado para a rota /login. O middleware auth irá proteger o URL do seu aplicativo, permitir acesso apenas de usuários registrados no banco de dados.

## Criando um middleware

php artisan make:middleware CheckUser

Criar o arquivo

app/Http/Middleware/CheckUser.php

## Registrando um middleware

Antes de usar um middleware precisamos registrá-lo. Para isso adicione ao app/Http/Kernel.php no array $routeMiddleware:
```php
        'ge' => \App\Http\Middleware\CheckAge::class,
```
## Tipos de middleware

Existem dois tipos de middleware:

- Global Middleware - tem ação global
- Route Middleware - a ser atribuido para uma rota

## Before e after middleware

A execução de um middleware antes ou após uma solicitação/request depende do próprio middleware. Por exemplo, o middleware a seguir executaria alguma tarefa antes que a solicitação fosse tratada pelo aplicativo:
```php
<?php
namespace App\Http\Middleware;

use Closure;

class BeforeMiddleware
{
    public function handle($request, Closure $next)
    {
        // Perform action
        return $next($request);
    }
}
```
No entanto, esse middleware executaria sua tarefa após a solicitação ser tratada pelo aplicativo:
```php
<?php
namespace App\Http\Middleware;

use Closure;

class AfterMiddleware
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        // Perform action

        return $response;
    }
}
```

## Exemplo simples

## Criar novo aplicativo
```php
laravel new blog --jet --stack=livewire
[no]
npm install && npm run dev
```
## Adicionar a rota
```php
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('usuario', function () {
    //$id = Auth::id();
    $name = Auth::user()->name;
    if($name){
        // Somente entrará aqui se o user estiver logado, caso não esteja será redirecionado para o raiz/welcome
        echo 'O nome do user é '.$name;
    }
})->middleware('checkUser')->name('usuario');
```
## Criar o middlwware

php artisan make:middleware CheckUser

## Editar app/Http/Middleware/CheckUser.php
```php
<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return redirect('/');
        }

        return $next($request);
    }
}
```
## Registrar no app/Http/Kernel.php array $routeMiddleware
```php
        'checkUser' => \App\Http\Middleware\CheckUser::class,

php artisan serve
```
## Experimente chamar

localhost:8000/usuario

Veja que é redirecionado para o raiz

## Criar um user para testar

Efetuar login e Chamar

localhost:8000/usuario


## Referências

- https://laravel.com/docs/8.x/middleware
- https://www.digitalocean.com/community/tutorials/get-laravel-route-parameters-in-middleware
- https://www.tutorialspoint.com/laravel/laravel_middleware.htm
- https://programmingpot.com/laravel/controllers-and-middleware-in-laravel/
- https://tony-stark.medium.com/laravel-5-8-middleware-tutorial-with-example-2019-9f5069b8c3cc
- https://www.codechief.org/article/laravel-6-middleware-tutorial-with-example
- https://blog.especializati.com.br/middleware-no-laravel-filtros/
- https://www.nicesnippets.com/blog/laravel-8-middleware-tutorial-example
- https://www.itsolutionstuff.com/post/laravel-57-middleware-tutorial-with-exampleexample.html

