# Service Provider no Laravel 8

## Tradução livre da documentação oficial e de alguns outros tutoriais
Com algumas alterações e com a ajuda do Google Translator

Os provedores de serviços/Service providers são o local central de todo o bootstrap/inicialização dos aplicativos Laravel. Seu próprio aplicativo, bem como todos os serviços principais do Laravel, são inicializados através de Service providers.

Mas, o que queremos dizer com "bootstrapped"? Em geral, queremos dizer registrar coisas, incluindo registrar ligações de contêineres de serviço, ouvintes de eventos, middleware e até mesmo rotas. Os provedores de serviços são o local central para configurar seu aplicativo.

Se você abrir o arquivo config/app.php incluído no Laravel, você verá um array de provedores. Essas são todas as classes de provedor de serviços que serão carregadas para seu aplicativo. Observe que muitos desses são provedores "adiados/deferred", o que significa que não serão carregados em todas as solicitações, mas apenas quando os serviços que fornecem forem realmente necessários.

Nesta visão geral, você aprenderá como escrever seus próprios provedores de serviço e registrá-los com seu aplicativo Laravel.

O Laravel já vem com alguns service providers nativos e podemos criar nossos service providers customizados. O Artisan ajuda nesta tarefa.

Os serviços principais do laravel e os serviços, classes e dependências do aplicativo são injetados no contêiner de serviço por meio de service providers.

O Laravel possui um mecanismo para definir e executar o processamento inicial de cada serviço. A classe que implementa o processamento inicial é chamada de provedor de serviços.

## Service Container

Em termos mais simples, poderíamos dizer que o service container no Laravel é uma caixa que contém as ligações de vários componentes, e eles são servidos conforme a necessidade em toda a aplicação. Sob o ponto de vista da documentação um service container é uma ferramenta poderosa para gerenciar dependências de classes e executar injeção de dependências.

Exemplo:
```php
Class SomeClass
{
    public function __construct(FooBar $foobarObject)
    {
        // use $foobarObject object
    }
}
```
SomeClass precisa de uma instância de FooBar para ser instanciaada. Então, basicamente, ele tem uma dependência que precisa ser injetada. O Laravel faz isso automaticamente olhando para o container de serviço e injetando a dependência apropriada.

E se você está se perguntando como o Laravel sabe quais componentes ou serviços incluir no container de serviço, a resposta é o provedor de serviço. É o provedor de serviço que diz ao Laravel para vincular vários componentes ao container de serviço. Na verdade, é chamado de ligações de contêiner de serviço e você precisa fazer isso por meio do provedor de serviços.

É o provedor de serviço que registra todas as ligações do contêiner de serviço e isso é feito por meio do método de registro da implementação do provedor de serviço.

Isso deve trazer outra questão: como o Laravel conhece os diversos provedores de serviço? Acabei de ouvir alguém dizer isso, o Laravel também deveria descobrir isso automaticamente! Puxa, isso é pedir muito: Laravel é um framework, não um super-homem, não é? Brincadeira à parte, isso é algo que você precisa informar ao Laravel explicitamente.

Veja o conteúdo do arquivo config/app.php. Você encontrará uma entrada de array que lista todos os provedores de serviço que serão carregados durante a inicialização do aplicativo Laravel.

Se o contêiner de serviço é algo que permite definir ligações e injetar dependências, o provedor de serviço é o lugar onde isso acontece.

## Resumindo

Os service providers eles são a espinha dorsal do framework Laravel e fazem todo o trabalho pesado quando você inicia executa um aplicativo Laravel.

O service provider é a peça principal do bootstraping/inicialização do Laravel. O próprio aplicativo, assim como outros serviços são inicializados pelos service providers. Eles contém os métodos boot() e register(). No método register podemos definir apenas ligações de containers de serviços/service container bindings. Você tem sempre acesso a um service container de um service provider através de $this->app. Para registrar um service provider apenas o adicione para o array providers em config/app.php, onde ficam todas as classes de service providers do aplicativo, assim:
```php
'providers' => [
        App\Providers\ProductServiceProvider::class,
],
```
Os providers também são usados para registrar packages, para que sejam executados na inicialização do aplicativo. Cada pacakge cria uma classe PacoteServiceProvider que extende ServiceProvider. É importante registrar que pacotes ao serem instalados nas versões 5.5 ou superior do laravel não precisam registrar o provider nem no array providers nem no aliases, pois são detectados automaticamente.

Podemos também criar pacotes contendo service providers customizados. Veja exemplos nas referências ao final.

Um service provider é simplesmente um apelido/alias para uma classe. Provedores de serviços são uma forma de vincular uma classe específica e geralmente são usados em conjunto com uma facade. Um alias é simplesmente uma maneira conveniente de usar uma classe sem ter que importar toda a classe com espaço de nomes todas as vezes.


## Pequeno exemplo de uso de um Service Provider

O collation padrão usado nas migrations do laravel é o utf8mb4_unicode_ci. Ele tem a limitação de 191 caracteres para o tipo de campo string. Então para evitar error de estouro desta limitação, quando se usa, exemplo, email com 255, edita-se o app/Providers/AppServiceProvider.php, assim:
```php
use Illuminate\Database\Schema\Builder; // Import Builder where defaultStringLength method is defined

function boot()
{
    Builder::defaultStringLength(191); // Update defaultStringLength
}
```
Assim o AppServiceProvider se encarregará de limitar o tamanho das strings a 191, antes das migrations serem executadas.

Após executar as migrations poderá verificar na estrutura da tabela users, que os campos tipo string, name, email e password foram limitados a 191 caracteres. Obra do nosso método boot() do AppServiceProvider.

É imteressane notar que as migrations não foram alteradas, mas somente a criação das tabelas foi feita de forma diferente pela interferência do provider.

Para testar o funcionamento, irei remover as tabelas do banco e comentar a linha do método boot no AppServiceProvider, então executarei novamente as migrations.
Não ocorreu nenhum erro no Laravel 8, mas o mais importante foi feito, que foi alterar o tamanho default dos campos strings para 191 quando usamos no método boot() do AppServiceProvider: Builder::defaultStringLength(191);

## Criando Service Providers

Todos os service providers extendem a classe Illuminate\Support\ServiceProvider. A maioria dos provedores de serviço contém um método registro/register e um método de inicialização/boot. No método de registro, você só deve vincular coisas ao contêiner de serviço. Você nunca deve tentar registrar quaisquer ouvintes de eventos, rotas ou qualquer outra parte da funcionalidade dentro do método de registro.

Para criar um novo service provider com o Artisan:
```php
php artisan make:provider RiakServiceProvider
```
## O Método register()

Conforme mencionado anteriormente, dentro do método register, você só deve vincular coisas ao contêiner de serviço. Você nunca deve tentar registrar quaisquer ouvintes de eventos, rotas ou qualquer outra parte da funcionalidade dentro do método de registro. Caso contrário, você pode acidentalmente usar um serviço fornecido por um provedor de serviços que ainda não foi carregado.

Vamos dar uma olhada em um provedor de serviços básico. **Em qualquer um dos métodos do seu provedor de serviços, você sempre tem acesso à propriedade $app, que fornece acesso ao contêiner de serviço**:
```php
<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Riak\Connection;

class RiakServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Connection::class, function ($app) {
            return new Connection(config('riak'));
        });
    }
}
```
Este provedor de serviços define apenas um método register e usa esse método para definir uma implementação de Riak\Connection no contêiner de serviço. Se você não entende como o contêiner de serviço funciona, verifique sua documentação (- https://laravel.com/docs/8.x/container).

As propriedades de ligações e singletons

Se o seu provedor de serviços registrar muitas ligações simples, você pode desejar usar as propriedades de ligações e singletons em vez de registrar manualmente cada ligação de contêiner.

## O Método boot()

Nste método você pode usar para estender a funcionalidade principal ou criar uma funcionalidade personalizada. Neste método, você pode acessar todos os serviços que foram registrados usando o método register do provedor de serviços. Então, e se precisarmos registrar um composer de view em nosso provedor de serviços? Isso deve ser feito dentro do método de inicialização/boot. **Este método é chamado depois que todos os outros provedores de serviço foram registrados**, o que significa que você tem acesso a todos os outros serviços que foram registrados pela estrutura.

Voc:ê pode usar o método boot() para adicionar uma validação customizada:
```php
public function boot()
{
    Validator::extend('custom_validator', function ($attribute, $value, $parameters, $validator) {
        // validation logic goes here...
    });
}
```
Ou o composer().
```php
<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        view()->composer('view', function () {
            //
        });
    }
}
```
## Injeção de dependência no método boot

Você pode injetar dependências no método boot() do seu provedor de serviços. O contêiner de serviço injetará automaticamente todas as dependências de que você precisa:
```php
use Illuminate\Contracts\Routing\ResponseFactory;

public function boot(ResponseFactory $response)
{
    $response->macro('caps', function ($value) {
        //
    });
}
```
## Registrando providers

Todos os provedores de serviço são registrados no arquivo de configuração config/app.php. Este arquivo contém um array providers onde são listadas as classes de seus provedores de serviço. Por padrão, um conjunto de provedores de serviços principais do Laravel, nativos, estão listados neste array. Esses provedores inicializam os componentes principais do Laravel, como o mailer, queue/fila, cache e outros.

Para registrar seu provider, adicione-o para o array:
```php
'providers' => [
    // Other Service Providers

    App\Providers\ComposerServiceProvider::class,
],
```
## Provedores deferidos/adiados

Se o seu provedor estiver apenas registrando ligações no container de serviço, você pode optar por adiar seu registro até que uma das ligações registradas seja realmente necessária. Adiar o carregamento de tal provedor melhorará o desempenho de seu aplicativo, uma vez que ele não é carregado no sistema de arquivos em todas as solicitações.

O Laravel compila e armazena uma lista de todos os serviços fornecidos por provedores de serviços diferidos, junto com o nome de sua classe de provedor de serviço. Então, somente quando você tenta resolver um desses serviços, o Laravel carrega o respectivo provedor de serviço.

Para adiar o carregamento de um provedor, implemente a interface \Illuminate\Contracts\Support\DeferrableProvider e defina um provider método. O método provider deve retornar as ligações do contêiner de serviço registradas pelo provedor:
```php
<?php

namespace App\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Riak\Connection;

class RiakServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Connection::class, function ($app) {
            return new Connection($app['config']['riak']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Connection::class];
    }
}
```

## Exemplos de uso dos Service Providers no Laravel 8

Um service provider é um apelido/alias para sua classe. Provedores de serviços são uma forma de vincular uma classe específica e muitas vezes são usados em conjunto com um Facade.

Um alias é simplesmente uma maneira conveniente de usar uma classe sem ter que importar toda a classe com namespaces todas as vezes.

Por exemplo, se você tiver uma classe \My\Very\Long\Class\Adapter, pode criar um alias para isso em config/app.php:
```php
<?php
'aliases' => [
    // a bunch of aliases
    'MyAdapter' => My\Very\Long\Class\Adapter::class,
]
```
E agora você pode fazer:
```php
<?php
new MyAdapter();
... 
```
Ao invés de:
```php
<?php
use My\Very\Long\Class\Adapter;
...
new Adapter();
...
```
Um service providers é freqüentemente usado quando você deseja resolver uma dependência, mais comumente por meio de injeção. Isso pode ser útil quando a classe que você deseja resolver exige que os parâmetros sejam passados para o construtor ou sempre tem uma configuração comum. Você pode realizar toda essa configuração no Provedor.

Eis um cenário:

Você tem uma API com a qual deseja interagir. Vamos chamá-lo de SuperApi. Os documentos para SuperAPI dizem que para criar uma instância da classe SuperApi, você deve fazer algo como:
```php
<?php
// Some method (a controller or something)
public function index()
{
    $superApi = new \SuperApi\Connector($key, $secret);
    return $superApi->getCustomers();
}
```
Agora, toda vez que você quiser criar uma instância desta classe, você terá que fazer a mesma configuração (ou abstraí-la para alguma classe, mas o fato é que você precisa passar uma $key e $secret para o construtor).

Se você fosse criar um alias para esta classe de conector, talvez fosse assim:
```php
// config/app.php
<?php
'aliases' => [
    // a bunch of aliases
    'SuperApi' => SuperApi\Connector::class,
]
```
Então, com esse alias, agora você pode fazer isso:
```php
<?php

// Some method (a controller or something)
public function index()
{
    $superApi = new SuperApi($key, $secret);
    return $superApi->getCustomers();
}
```
Mesmo com o alias, você ainda precisa passar a $key e $secret.

É aqui que um provedor de serviços pode ajudar.
```php
// app/Providers/SuperApiProvider.php
<?php

namespace App\Providers;

use SuperApi\Connector;
use Illuminate\Support\ServiceProvider;

class SuperApiProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('superApiConnector', function ($app) {
            return new ApiConnector($app['config']->get('super-api.key'), $app['config']->get('super-api.secret'));
        });
    }
}
```

app/Providers/SuperApi.php (the Facade)
```php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Facade;

class SuperApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'superApiConnector';
    }
}
```

config/super-api.config

```php
<?php

return [
    'key' => env('SUPER_API_KEY'),
    'secret' => env('SUPER_API_SECRET'),
];

// config/app.php
<?php
'providers' => [
    // a bunch of providers
    App\Providers\SuperApiProvider::class,
]
```
Veja que a string que você vincula no provedor ('superApiConnector') é a mesma que você retorna da facade e o nome da classe da facade é como você realmente chamará a classe vinculada, neste caso SuperApi.

Agora, quando quiser usar a classe SuperApi\Connector, você pode fazer o seguinte:
```php
<?php
// Some method (a controller or something)
public function index()
{
    return SuperApi::getCustomers();
}
```
Onde um provedor realmente é útil é quando você quer injetá-lo e fazer com que o contêiner IoC do Laravel resolva automaticamente a classe injetada:
```php
<?php
// Some method (a controller or something)
public function index(SuperApi $api)
{
    return $api->getCustomers();
}
```
Para ser claro, você NÃO precisa de um provedor de serviços para aproveitar as vantagens da injeção de dependência. Contanto que a classe possa ser resolvida pelo aplicativo, ela pode ser injetada. Isso significa que todos os argumentos usados pelo construtor da classe que você está injetando também precisam ser resolvidos automaticamente.

https://stackoverflow.com/questions/46330374/laravel-do-i-need-a-service-provider-for-each-of-my-service-containers-custo

## Outro exemplo

Criar service provider
```php
php artisan make:provider EnvatoCustomServiceProvider
```
Registrar em config/app.php array Providers
```php
        App\Providers\EnvatoCustomServiceProvider::class,
```
Editar método register()
```php
    public function register()
    {
        $this->app->bind('App\Library\Services\DemoOne', function ($app) {
          return new DemoOne();
        });
    }
```
Criar a classe
```php
app/Library/Services/DemoOne.php

<?php
namespace App\Library\Services;
  
class DemoOne
{
    public function doSomethingUseful()
    {
      return 'Output from DemoOne';
    }
}
```
Adicionar o código ao controller, onde a dependência deve ser injetada
```php
<?php
namespace App\Http\Controllers;
  
use App\Http\Controllers\Controller;
use App\Library\Services\DemoOne;
  
class TestController extends Controller
{
    public function index(DemoOne $customServiceInstance)
    {
        echo $customServiceInstance->doSomethingUseful();
    }
}
```
Criar uma interface simples
```php
app/Library/Services/Contracts/CustomServiceInterface.php

<?php
// app/Library/Services/Contracts/CustomServiceInterface.php
namespace App\Library\Services\Contracts;
  
Interface CustomServiceInterface
{
    public function doSomethingUseful();
}
```
Criar duas implementações desta interface

Criar a classe app/Library/Services/DemoOne.php
```php
<?php
// app/Library/Services/DemoOne.php
namespace App\Library\Services;
  
use App\Library\Services\Contracts\CustomServiceInterface;
  
class DemoOne implements CustomServiceInterface
{
    public function doSomethingUseful()
    {
      return 'Output from DemoOne';
    }
}
```
E também
```php
app/Library/Services/DemoTwo.php

<?php
// app/Library/Services/DemoTwo.php
namespace App\Library\Services;
  
use App\Library\Services\Contracts\CustomServiceInterface;
  
class DemoTwo implements CustomServiceInterface
{
    public function doSomethingUseful()
    {
      return 'Output from DemoTwo';
    }
}
```
Alterar EnvatoCustomServiceProvider.php
```php
<?php
namespace App\Providers;
  
use Illuminate\Support\ServiceProvider;
use App\Library\Services\DemoOne;
  
class EnvatoCustomServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }
  
    public function register()
    {
        $this->app->bind('App\Library\Services\Contracts\CustomServiceInterface', function ($app) {
          return new DemoOne();
        });
    }
}
```
Alterar o controller
```php
<?php
namespace App\Http\Controllers;
  
use App\Http\Controllers\Controller;
use App\Library\Services\Contracts\CustomServiceInterface;
  
class TestController extends Controller
{
    public function index(CustomServiceInterface $customServiceInstance)
    {
        echo $customServiceInstance->doSomethingUseful();
    }
}
```
Em EnvatoCustomServiceProvider.php, 

Troque use App\Library\Services\DemoOne; por
```php
use App\Library\Services\DemoTwo;
```
E troque return new DemoOne(); por
```php
return new DemoTwo();
```
Adicione ao método boot()
```php
public function boot()
{
    Validator::extend('my_custom_validator', function ($attribute, $value, $parameters, $validator) {
        // validation logic goes here...
    });
}
```
Ou
```php
public function boot()
{
    View::share('key', 'value');
}
```
ou ainda
```php
public function boot()
{
    parent::boot();
  
    Route::model('user', App\User::class);
}
```
https://code.tutsplus.com/tutorials/how-to-register-use-laravel-service-providers--cms-28966


## Outro exemplo
```php
app/MyCustomLogic.php

<?php

namespace App;

class MyCustomLogic
{
  protected $baseNumber;

  public function __construct($baseNumber)
  {
    $this->baseNumber = $baseNumber;
  }

  public function add($num)
  {
    return $this->baseNumber + $num;
  }
}

php artisan make:provider MyCustomProvider

public function register()
{
  $this->app->singleton('my-custom-logic', function () {
    $baseNumber = 123;

    return new \App\MyCustomLogic($baseNumber);
  });
}

$this->app->singleton('my-custom-logic', function(){});
```
Registrar
```php
<?php

return [
  ...
  'providers' => [
    ...
    App\Providers\MyCustomProvider::class,
    ...
  ],
  ...
];

php artisan tinker

>>> app('my-custom-logic');

>>> app('my-custom-logic')->add(4);

app/Facades/MyCustomFacade.php.
<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class MyCustomFacade extends Facade
{
  protected static function getFacadeAccessor()
  {
    return 'my-custom-logic';
  }
}
```
Criar um alias
```php
<?php

return [
  ...
  'aliases' => [
    ...
    'CustomLogic' => App\Facades\MyCustomFacade::class,
    ...
  ],
  ...
];
```
Usar num controller app/Http/Controllers/PageController.php
```php
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
  protected $customLogic;
    
  public function __construct(\CustomLogic $customLogic)
  {
    $this->customLogic = $customLogic;
  }
    
  public function index()
  {
    return $this->customLogic->add(4);
  }
}
```
Testando
```php
<?php
CustomLogic::shouldReceive('add')
  ->with(4)
  ->once()
  ->andReturn(127);
```
Criar
```php
config/my-custom-logic.php com:

<?php

return [
  'base-number' => 123
];

<?php

public function register()
{
  $this->app->singleton('my-custom-logic', function () {
    $baseNumber = config('my-custom-logic.base-number');

    return new \App\MyCustomLogic($baseNumber);
  });
}

<?php

return [
  'base-number' => env('MY_CUSTOM_LOGIC_BASE_NUMBER')
];
```        
Abra o .env e adicione a linha seguinte:
```php
MY_CUSTOM_LOGIC_BASE_NUMBER=123
```
https://clickds.co.uk/news/how-why-to-use-facades-service-providers-in-laravel


## Outro exemplo

Laravel 5.1
```php
app/Http/routes.php:

Route::resource('demo', 'DemoController');

php artisan make:controller DemoController
```
Editar e adicionar:
```php
public function index()
{
    return view('demo.index');
}

app/Resources/views/index.blade.php

@extends('layouts.master')

@section('content')
<h1>Demo Page</h1>
@endsection
```
Criar um helepr
```php
<?php
namespace App\Helpers\Contracts;

Interface RocketShipContract
{
    public function blastOff();
}
```
Criar em app/Helpers
```php
<?php
namespace app\Helpers;

use App\Helpers\Contracts\RocketShipContract;

class RocketShip implements RocketShipContract
{
    public function blastOff()
    {
        return 'Houston, we have ignition';
    }
}

php artisan make:provider RocketShipServiceProvider
```
Importante: No laravel 5.1 um service provider tinha 3 métodos: boot, register e provides. A partir da 5.8 ficou com apenas 2: boot e register.

Editar app/Providers/RocketShipServiceProvider.php e ...
```php
    public function register()
    {
        $this->app->bind('App\Helpers\Contracts\RocketShipContract', function(){

            return new RocketShip();

        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['App\Helpers\Contracts\RocketShipContract'];
    }

protected $defer = true;
```
Definir a propriedade $defer como true significa que essa classe só será carregada quando necessário, para que o aplicativo seja executado com mais eficiência.
```php
public function register()
{
    $this->app->bind('App\Helpers\Contracts\RocketShipContract', function(){

        return new RocketShip();

    });
}

/**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['App\Helpers\Contracts\RocketShipContract'];
    }

}
```
Registrando o provider
```php
config/app.php.
         * Application Service Providers...
...
        App\Providers\RocketShipServiceProvider::class,
...
```
Editar o index() do controller
```php
public function index(RocketShipContract $rocketship)
{
        $boom = $rocketship->blastOff();

        return view('demo.index', compact('boom'));
}
```
Editar a view
```php
@extends('layouts.master')

@section('content')

    {{ $boom }}

@endsection

<?php

namespace app\Helpers;

use App\Helpers\Contracts\RocketShipContract;

class RocketLauncher implements RocketShipContract
{
    public function blastOff()
    {
        return 'Houston, we have launched!';
    }
}

use App\Helpers\RocketLauncher;

public function register()
    {
        $this->app->bind('App\Helpers\Contracts\RocketShipContract', function(){

            return new RocketLauncher();

        });
    }
```
https://laraveltips.wordpress.com/2015/06/11/how-to-create-a-service-provider-in-laravel-5-1/

## Referências

- https://laravel.com/docs/8.x/providers
- https://dev.to/patelparixit07/laravel-service-providers-bja
- https://code.tutsplus.com/tutorials/how-to-register-use-laravel-service-providers--cms-28966
- https://barryvanveen.nl/blog/34-laravel-service-provider-examples
- https://programmingpot.com/laravel/laravel-service-provider/
- https://www.codementor.io/@decodeweb/laravel-service-providers-explained-in-depth-12uu86s2pq
- https://sodocumentation.net/laravel/topic/1908/services
- https://medium.com/grevo-techblog/service-provider-in-laravel-3b7267b0576e
- http://dev.rdybarra.com/2016/07/27/Laravel-Service-Provider-Example/
- https://clickds.co.uk/news/how-why-to-use-facades-service-providers-in-laravel
- https://programmer.group/laravel-practice-service-providers.html
- https://laraveltips.wordpress.com/2015/06/11/how-to-create-a-service-provider-in-laravel-5-1/

### Pacotes com Service Providers de Exemplo:

- https://github.com/willvincent/feeds - inclui a SimplePie
- https://github.com/wesleywillians/laravel-pagseguro
- https://github.com/ixianming/laravel-route-service-provider
- https://github.com/artisanry/service-provider
- https://github.com/theofidry/laravel-yaml
- https://gist.github.com/rosswintle/428ae8a3e0c215a7b3ecbfd186b5520c - stats service provider
