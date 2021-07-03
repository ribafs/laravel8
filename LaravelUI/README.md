# Exemplo de uso do Laravel UI no Laravel 8 com bootstrap

## Instalar um novo aplicativo laravel 8

laravel new blog

cd blog

## Instalar o laravel/ui atual com bootstrap 4 e os assets

composer require laravel/ui

php artisan ui bootstrap --auth
    
npm install && npm run dev

## Criar o banco e configurar no .env

## Testar

php artisan migrate

php artisan serve

http://localhost:8000/register

## Criar HomeController

php artisan make:controller HomeController

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

	public function dashboard(){
		if(auth()->check()){
            // http://localhost:8000/dashboard
            //echo "Você está autenticado";
            // Receber o usuário atual autenticado
            $user = auth()->user();
            echo $user->name;
        }
	}
}

## Protegendo as rotas

//Auth::routes();
Auth::routes(['register' => false]);

Route::get('dashboard', 'App\Http\Controllers\HomeController@dashboard')->middleware('auth');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

## Se estiver usando o JetStream com Fortfy

Neste caso precisa editar as configurações do fortify para desabilitar o register

config/fortyfy.php

## Customizações

### Redirecionar user logado

Após o login com sucesso, caso não esteja usando JetStream, o user é redirecionado para /home. Caso deseje mudar isso, edite o
app/Providers/RouteServiceProvider.php

E mude a constante HOME. Exemplo

public const HOME = '/dashboard';

### Customização do username

Por default a autenticação do Laravel checa o email. Para mudar isso edite

app/Http/Controllers/Auth/LoginController.php

public function username()
{
    return 'username';
}

## Alternativa para Paginação

Por padrão o laravel 8 usa o Tailwind CSS na paginação. Mas o Laravel 8 também inclui views para paginação usando o CSS do Bootstrap 4. Para usar o bootstrap na paginação de um aplicativo com Laravel 8 instalado com JetStream e cia veja:

https://laravel.com/docs/8.x/pagination#using-bootstrap

Para isso edite o app/Providers/AppServiceProvider.php e mude assim:

use Illuminate\Pagination\Paginator;

public function boot()
{
    Paginator::useBootstrap();
}

## Mesmo comportamento do 7 no Laravel 8

Para isso editamos o 

app/Providers/RouteServiceProvider.php

E descomentamos a linha com:

    protected $namespace = 'App\\Http\\Controllers';

Após salvar já podemos usar as rotas assim:

Route::get('/test', 'TestController@index');

E sem adicionar ao início o use...

Obs: A meu ver devemos deixar como está, pois fica mais seguro e mais leve que os controllers nãos ejam globais. Mas a decisão final é de cada um.

## Referências

- https://github.com/laravel/ui (versão 3 em diante no Laravel 8)
- https://laravelarticle.com/laravel-8-authentication-tutorial
- https://www.techiediaries.com/laravel-8-bootstrap-4-tailwind/
- https://www.positronx.io/how-to-properly-install-and-use-bootstrap-in-laravel/
- https://morioh.com/p/8edaa37ea5a9
- https://www.itsolutionstuff.com/post/laravel-8-install-bootstrap-example-tutorialexample.html
- https://dev.to/jasminetracey/laravel-8-with-bootstrap-livewire-and-fortify-5d33
- https://hdtuto.com/article/laravel-8-bootstrap-auth-example-step-by-step
- https://morioh.com/p/5fade441c2cd
    
## Para refletir

- https://laracasts.com/discuss/channels/laravel/makeauth-3
- https://twitter.com/laravelphp/status/1304445936043798528
- https://github.com/laravel/framework/discussions/34214
- https://laracasts.com/discuss/channels/general-discussion/laravel-7-vs-laravel-8-dilemma

## Custom Pagination

- https://www.positronx.io/laravel-pagination-example-with-bootstrap-tutorial/

## Alguns demoimentos

Colhidos daqui:

https://laracasts.com/discuss/channels/general-discussion/laravel-7-vs-laravel-8-dilemma

E traduzidos para maior conforto dos que ainda não leem inglês com facilidade.

### wilk_randall no Laracasts citado acima

Eu uso Bootstrap e SASS há anos

É melhor apenas aprender e usar o Tailwind em vez disso?

Não há nada que o impeça de usar o Bootstrap no Laravel 8 (ou qualquer outra versão). As pilhas Tailwind e Livewire / Inertia são apenas preferências e são completamente opcionais, como a maioria das outras coisas com o Laravel.

Eu recomendo usar qualquer tipo de front-end com o qual você se sinta confortável. Eu também recomendo fazer projetos na v8 só porque você não terá que se preocupar em atualizar seus projetos mais tarde, e também quanto mais você esperar para atualizar, mais versões você terá que atualizar e provavelmente será mais difícil façam.

### artcore

Laravel 6 é o LTS atual, que geralmente sigo, mas o L7 tem um router mais rápido, então é este que estou usando para todos os meus projetos.

### jlrdw

O guia de atualização cobre o uso de bootstrap em vez do Tailwind. Mas, na verdade, você pode usar qualquer CSS que queira usar. Desejo que a IU permaneça padrão e o Jetstream opcional.

### akc4

Este jetstream/livewire é repugnantemente confuso para mim e vem com novas tags. 

### wilk_randall

Então akc4 não use ele. Tudo isso é totalmente opcional. Eu pessoalmente não planejo usar o Jetstream em meus próprios projetos.

### Snapey

Jetstream é opcional e laravel/ui é instalável em L8

## Muito mais abaixo

https://laracasts.com/discuss/channels/general-discussion/laravel-7-vs-laravel-8-dilemma

## Minha opinião

Acredito que cada um deve decidir o que usar e como usar. Se usa o Laravel 8, se usa o Laravel 8 com laravel/ui, se usa de forma mixta. Acho que cada um tem um perfil de conhecimentos e deve decidir com tranquilidade sobre o que usará.
