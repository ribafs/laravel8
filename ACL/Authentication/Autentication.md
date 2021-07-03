# Autenticação no Laravel 8

## Tradução livre

Da documentação oficial do Laravel 8 em

https://laravel.com/docs/8.x/authentication

Com a ajuda do Google Tranlator

A autenticação no Laravel é algo simples. Muita coisa já vem out of the box, pronta, fora da caixa. O arquivo de configuração da autenticação é o config/auth.php, que contém algumas opções bem documentadas para configurar o comportamento da autenticação.

A instalação da autenticação do Laravel sé composta de "guards" e "providers". 

Guards definem como os usuários são autenticados para cada request. Por exemplo, o Laravel vem com um guard de session que mantém o estado usando armazenamento de session e cookies.

Os providers definem como os usuários são recuperados de seu armazenamento persistente. O Laravel vem com suporte para recuperação de usuários usando Eloquent e o construtor de consultas de banco de dados. No entanto, você é livre para definir providers adicionais conforme necessário para sua aplicação.

## Início rápido

Para ter um início r ápido instale o JetStream em uma nova instalação. O JetStream fornecerá um scaffolding compelto da autenticação com login e registro.

## Considerações sobre banco de dados

Por default o Laravel inclui um model User em app/Models. Quando usando o model Users garanta que o campo password na tabela tenha no mínimo 60 caracteres de comprimento. Manter o default 255 caracteres é uma boa escolha. Mas se usando o collation uft8mb4_unicode_ci use no máximo 191 catactetres.

## Como funciona a autenticação

O user deve usar seu username e password para acessar o aplicativo através do login. Caso as credenciais estejam corretasm elas serão g ravadas na sessão do usuário. Também será gravado um cookie no navegador com o ID da sessão para que requests subsequentes para a aplicação possam associar o user com a sessão.

## Criando sua própria camada de autenticação

Podemos criar nossa própria autenticação. Os pacotes nativos da autenticação do Laravel são o JetStream (https://jetstream.laravel.com/) e o Fortfy (https://github.com/laravel/fortify).

## Fortify

Laravel Fortify é um lado backend de autenticação para Laravel 8, que implementa muitos dos recursos encontrados na documentação, incluindo autenticação baseada em cookies, bem como outros recursos como autenticação de dois fatores e verificação de email.

## JetStream

Laravel Jetstream é uma IU (interface do usuário) que consome e expõe os serviços de autenticação do Fortify com uma IU bonita e moderna usando Tailwind CSS, Laravel Livewire e/ou Inertia.js. Laravel Jetstream, além de oferecer autenticação de cookie baseada em navegador, inclui integração embutida com Laravel Sanctum para oferecer autenticação de token em API.

## API

O Laravel fornece dois pacotes opcionais para ajudá-lo a gerenciar tokens de API e autenticar requisições/requests feitas com tokens de API: Passport e Sanctum. Observe que essas bibliotecas e as bibliotecas de autenticação baseadas em cookies embutidas no Laravel não são mutuamente exclusivas. Essas bibliotecas se concentram principalmente na autenticação de token API, enquanto os serviços de autenticação integrados se concentram na autenticação do navegador baseada em cookies. Muitos aplicativos usarão os serviços de autenticação baseados em cookies embutidos do Laravel e um dos pacotes de autenticação de API do Laravel.

## Passport

O Passport é um provider de autenticação OAuth2, oferecendo uma variedade de "tipos de concessão" OAuth2 que permitem a emissão de vários tipos de tokens. Em geral, este é um pacote robusto e complexo para autenticação de API. No entanto, a maioria dos aplicativos não requer os recursos complexos oferecidos pela especificação OAuth2, o que pode ser confuso para usuários e desenvolvedores. Além disso, os desenvolvedores têm se confundido historicamente sobre como autenticar aplicativos SPA ou aplicativos móveis usando provedores de autenticação OAuth2 como o Passport.

## Sanctum

Em resposta à complexidade do OAuth2 e à confusão do desenvolvedor, decidimos construir um pacote de autenticação mais simples e otimizado que pudesse lidar com solicitações da web de um navegador da web e requisições de API por meio de tokens. Esse objetivo foi alcançado com o lançamento do Laravel Sanctum, que deve ser considerado o pacote de autenticação preferido e recomendado para aplicativos que oferecerão uma interface de usuário da web de primeira parte além de uma API, ou será alimentado por um aplicativo de página única que existe separadamente do aplicativo backend Laravel, ou aplicativos que oferecem um cliente móvel.

Laravel Sanctum é um pacote híbrido de autenticação web/API que pode gerenciar todo o processo de autenticação de sua aplicação. Isso é possível porque quando os aplicativos baseados no Sanctum recebem um request/solicitação, o Sanctum primeiro determina se a solicitação inclui um cookie de sessão que faz referência a uma sessão autenticada. O Sanctum faz isso chamando os serviços de autenticação embutidos do Laravel que discutimos anteriormente. Se a solicitação não estiver sendo autenticada por meio de um cookie de sessão, o Sanctum inspecionará a solicitação de um token de API. Se um token de API estiver presente, o Sanctum autenticará a solicitação usando esse token. Para saber mais sobre esse processo, consulte a documentação "como funciona" do Sanctum.

Laravel Sanctum é o pacote de API que escolhemos incluir no scaffolding de autenticação do Laravel Jetstream porque acreditamos que ele é o mais adequado para a maioria das necessidades de autenticação de aplicativos da web.

## Rotas

O pacote laravel/jetstream do Laravel fornece uma maneira rápida de organizar todas as rotas, views e outras lógicas de backend necessárias para autenticação usando alguns comandos simples:

## Instalando em uma nova e existente aplicação

Em uma instalação nova e limpa, sem o scaffonding da auenteicação, ao chamar no navegador não veremos os botões de login e registro

composer require laravel/jetstream

### Install Jetstream with the Livewire stack...

php artisan jetstream:install livewire

### Install Jetstream with the Inertia stack...

php artisan jetstream:install inertia

npm install && npm run de

Agora, após instalar a autenticação, chamando pelo navegador veremos os botões de login e autenticação.

### Criar aplicativo já com auteicação

laravel new blog --jet

Assim o aplicativo já virá com o JetStream e o Fortify.

### Instalar já com jetstream, teams e stack

laravel new blog --jet --teams --stack=livewire

ou

laravel new blog --jet --teams --stack=inertia

## Views

O JetStream cria todas as views necessárias a autenticação na pasta: 

resources/views/auth

Também cria

resources/views/layouts contendo um layout base para a aplicação.

Todas estas views atualmente, na versão 8 do Laravel, usam o framework TailWind CSS. Mas estamos livres para usar o framework que desejarmos, como o BootStrap.

## Customizando path

Por default o usuário  autenticado, após efetuar login, será redirecionado para

    public const HOME = '/dashboard';

Isso está definido no app/Providers/RouteServiceProvider.php e podemos alterar esta URL de pós autenticação. Caso não estejs usando o JetStream esta constante será:

    public const HOME = '/home';

## Recebendo o usuário autenticado

use Illuminate\Support\Facades\Auth;

// Get the currently authenticated user...
$user = Auth::user();

// Get the currently authenticated user's ID...
$id = Auth::id();

Como alternativa, depois que um usuário é autenticado, você pode acessar o usuário autenticado por meio de uma instância Illuminate\Http\Request. Lembre-se de que as classes com dicas de tipo serão injetadas automaticamente em seus métodos do controlador. Ao inserir dicas de tipo no objeto Illuminate\Http\Request, você pode obter acesso conveniente ao usuário autenticado a partir de qualquer método do controlador em seu aplicativo:

<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function update(Request $request)
    {
        // $request->user() returns an instance of the authenticated user...
    }
}

## Verificar se o user atual está autenticado

Verificar se um usuário está logado no aplicativo:

use Illuminate\Support\Facades\Auth;

if (Auth::check()) {
    // The user is logged in...
}

Também podemos usar o middleware 'auth' em rotas ou controllers para garantir que somente usuários autenticados acessem o aplicativo.

https://laravel.com/docs/8.x/authentication#protecting-routes

## Protegendo rotas

O middleware de rota pode ser usado para permitir que apenas usuários autenticados acessem uma determinada rota. O Laravel vem com um middleware 'auth', que faz referência à classe Illuminate\Auth\Middleware\Authenticate. Uma vez que este middleware já está registrado em seu kernel HTTP, tudo que você precisa fazer é anexar o middleware a uma definição de rota:

Route::get('flights', function () {
    // Only authenticated users may enter...
})->middleware('auth');

## Protegendo um controller

<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');        
    }
...

## Redirecionando usuários não autenticados

Quando o middleware auth detecta um usuário não autenticado ele o redirecionará para a rota noeada de login. Você pode momdificar este comportamento alterando o método redirectTo() no middleware app/Http/Middleware/Authenticate.php:

/**
 * Get the path the user should be redirected to.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return string
 */
protected function redirectTo($request)
{
    return route('login');
}

## Especificando um guarda

Ao anexar o middleware auth a uma rota, você também pode especificar qual proteção/guard deve ser usada para autenticar o usuário. O guard especificado deve corresponder a uma das chaves no array guards do seu arquivo de configuração config/auth.php. No caso api ou web:

Route::get('flights', function () {
    // Only authenticated users may enter...
})->middleware('auth:api');

## Limitando o Login

Se você estiver usando o Laravel Jetstream, a limitação de taxa será aplicada automaticamente às tentativas de login. Por padrão, o usuário não conseguirá fazer o login por um minuto se não fornecer as credenciais corretas após várias tentativas. A limitação é exclusiva para o nome de usuário/endereço de e-mail do usuário e seu endereço IP.

Caso queira limitar a taxa de suas próprias rotas, verifique

https://laravel.com/docs/8.x/routing#rate-limiting

## Autenticando usuários manualmente

Você não é obrigado a usar o scaffolding de autenticação incluído no Laravel Jetstream. Se você decidir não usar este scaffolding, você precisará gerenciar a autenticação do usuário usando as classes de autenticação do Laravel diretamente. Não se preocupe, é fácil!

Iremos acessar os serviços de autenticação do Laravel através da facade Auth, então precisamos ter certeza de importar a facade Auth no topo da classe. A seguir, vamos verificar o método de tentativa:

<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            return redirect()->intended('dashboard');
        }
    }
}

O método de tentativa aceita uma matriz de pares de chave/valor como seu primeiro argumento. Os valores na matriz serão usados ​​para localizar o usuário em sua tabela do banco de dados. Portanto, no exemplo acima, o usuário será recuperado pelo valor da coluna email. Se o usuário for encontrado, a senha com hash armazenada no banco de dados será comparada com o valor da senha passada ao método por meio do array. Você não deve fazer o hash da senha especificada como o valor da senha, uma vez que o framework fará o hash automaticamente do valor antes de compará-lo com a senha com hash no banco de dados. Se as duas senhas com hash corresponderem, uma sessão autenticada será iniciada para o usuário.

O método de tentativa retornará verdadeiro se a autenticação for bem-sucedida. Caso contrário, false será retornado.

O método pretendido no redirecionador redirecionará o usuário para a URL que ele estava tentando acessar antes de ser interceptado pelo middleware de autenticação. Um URI de fallback pode ser fornecido a esse método, caso o destino pretendido não esteja disponível.

## Especificando condições adicionais

Se desejar, você também pode adicionar condições extras à consulta de autenticação, além do e-mail e senha do usuário. Por exemplo, podemos verificar se o usuário está marcado como "active":

if (Auth::attempt(['email' => $email, 'password' => $password, 'active' => 1])) {
    // The user is active, not suspended, and exists.
}

Neste exemplo, o e-mail não é uma opção obrigatória, é apenas usado como exemplo. Você deve usar qualquer nome de coluna que corresponda a um "username" em seu banco de dados.

## Acessando específica instância de guard

Você pode especificar qual instância de guard deseja utilizar usando o método guard na facade Auth. Isso permite que você gerencie a autenticação para partes separadas de seu aplicativo usando models autenticáveis ou tabelas de usuário totalmente separados.
O nome do guard passado ao método guard deve corresponder a um dos guards configurados em seu arquivo de configuração config/auth.php:

## Logout

O método abaixo irá limpar as informações de autenticação da sessão do usuário:

Método da facade Auth()

Auth::logout();

## Lembrando usuários

Se desejar fornecer a funcionalidade "Remember me" em seu aplicativo, você pode passar um valor booleano como o segundo argumento para o método de tentativa, que manterá o usuário autenticado indefinidamente ou até que ele efetue o logout manualmente. Sua tabela de usuários deve incluir a coluna de string remember_token, que será usada para armazenar o token "Remember me".

if (Auth::attempt(['email' => $email, 'password' => $password], $remember)) {
    // The user is being remembered...
}

Se você estiver "lembrando" de usuários, poderá usar o método viaRemember para determinar se o usuário foi autenticado usando o cookie "Remember me":

if (Auth::viaRemember()) {
    //
}

## Outros métodos de autenticação

https://laravel.com/docs/8.x/authentication#other-authentication-methods

