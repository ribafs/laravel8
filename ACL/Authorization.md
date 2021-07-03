# Authorization no Laravel 8

## Autenticação e Autorização

A autenticação é o processo que ferifica se um usuário que está tentando efetuar login está realmente cadastrado no banco de dados. Caso o usuário não esteja a autenticação o redireciona de volta para o login. Caso ele seja autenticado, então o processo fica a cargo da autorização. A autorização irá verificar se o usuário tem direito de acesso a cada parte do aplicativo. Se pode acessar qualquer das views e mais ainda se tem acesso a específicas partes de uma certa view.

## Autorização

### Tradução livre

Da documentação oficial do Laravel 8 em

https://laravel.com/docs/8.x/authorization

Com a ajuda do Google Tranlator

Além de fornecer serviços de autenticação prontos para uso, o Laravel também fornece uma maneira simples de autorizar ações do usuário contra um determinado recurso. 

Assim como a autenticação, a abordagem do Laravel para autorização é simples, e existem duas formas principais de autorizar ações: gates/portas e policies/políticas.

Pense em portas e políticas como rotas e controladores. Os Gates fornecem uma abordagem simples e baseada em Closure para autorização, enquanto as políticas, como os controladores, agrupam sua lógica em torno de um modelo ou recurso específico. Exploraremos as portas/gates primeiro e, em seguida, examinaremos as políticas.

Você não precisa escolher entre usar exclusivamente portas ou usar exclusivamente políticas ao criar um aplicativo. A maioria dos aplicativos provavelmente conterá uma mistura de portas e políticas, e isso é perfeitamente normal! 

## Gates

Os gates são mais aplicáveis ​​a ações que não estão relacionadas a nenhum modelo ou recurso, como a visualização de um painel do administrador. 

## Policies

Em contraste, as políticas devem ser usadas quando você deseja autorizar uma ação para um determinado modelo ou recurso.

## Criando Gates

Gates são closures que determinam se um usuário está autorizado a executar uma determinada ação e são normalmente definidos na classe App\Providers\AuthServiceProvider usando a facade Gate. Gates sempre recebe uma instância de usuário como seu primeiro argumento e pode, opcionalmente, receber argumentos adicionais, como um model relevante do Eloquent:
```php
/**
 * Register any authentication / authorization services.
 *
 * @return void
 */
public function boot()
{
    $this->registerPolicies();

    Gate::define('edit-settings', function ($user) {
        return $user->isAdmin;
    });

    Gate::define('update-post', function ($user, $post) {
        return $user->id === $post->user_id;
    });
}
```
Gates também podem ser definidos usando um array de retorno de classe, como controllers:
```php
use App\Policies\PostPolicy;

/**
 * Register any authentication / authorization services.
 *
 * @return void
 */
public function boot()
{
    $this->registerPolicies();

    Gate::define('update-post', [PostPolicy::class, 'update']);
}
```
## Autorizando Ações

Para autorizar uma ação usando gates, você deve usar os métodos allow ou denies. Observe que você não é obrigado a passar o usuário autenticado no momento para esses métodos. O Laravel cuidará automaticamente de passar o usuário para o gate Closure:
```php
if (Gate::allows('edit-settings')) {
    // The current user can edit settings
}

if (Gate::allows('update-post', $post)) {
    // The current user can update the post...
}

if (Gate::denies('update-post', $post)) {
    // The current user can't update the post...
}
```
Se você gostaria de determinar se um determinado usuário está autorizado a executar uma ação, você pode usar o método forUser na facade do Gate:
```php
if (Gate::forUser($user)->allows('update-post', $post)) {
    // The user can update the post...
}

if (Gate::forUser($user)->denies('update-post', $post)) {
    // The user can't update the post...
}
```
Você pode autorizar várias ações ao mesmo tempo com os métodos any ou none:
```php
if (Gate::any(['update-post', 'delete-post'], $post)) {
    // The user can update or delete the post
}

if (Gate::none(['update-post', 'delete-post'], $post)) {
    // The user cannot update or delete the post
}
```
## Autorizando ou lançando exceções

Se você quiser tentar autorizar uma ação e lançar automaticamente um Illuminate\Auth\Access\AuthorizationException se o usuário não tiver permissão para executar a ação dada, você pode usar o método Gate::authorize. Instâncias de AuthorizationException são convertidas automaticamente em uma resposta HTTP 403:
```php
Gate::authorize('update-post', $post);
```
// The action is authorized...

## Suprindo contexto adicional

Os métodos do gate para autorizar habilidades (permite, nega, verifica, qualquer, nenhum, autoriza, pode, não pode) e as diretivas do Blade de autorização (@can, @cannot, @canany) podem receber uma matriz como o segundo argumento. Esses elementos da matriz são passados como parâmetros para o gate e podem ser usados para contexto adicional ao tomar decisões de autorização:
```php
Gate::define('create-post', function ($user, $category, $extraFlag) {
    return $category->group > 3 && $extraFlag === true;
});

if (Gate::check('create-post', [$category, $extraFlag])) {
    // The user can create the post...
}
```
## Respostas do Gate

Até agora, examinamos apenas Gates que retornam valores booleanos simples. No entanto, às vezes você pode querer retornar uma resposta mais detalhada, incluindo uma mensagem de erro. Para fazer isso, você pode retornar um Illuminate\Auth\Access\Response do seu gate:
```php
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;

Gate::define('edit-settings', function ($user) {
    return $user->isAdmin
                ? Response::allow()
                : Response::deny('You must be a super administrator.');
});
```
Ao retornar uma resposta de autorização de seu gate, o método Gate::allows ainda retornará um valor booleano simples; no entanto, você pode usar o método Gate::inspect para obter a resposta de autorização completa retornada pelo gate:
```php
$response = Gate::inspect('edit-settings', $post);

if ($response->allowed()) {
    // The action is authorized...
} else {
    echo $response->message();
}
```
Claro, ao usar o método Gate::authorize para lançar um AuthorizationException se a ação não for autorizada, a mensagem de erro fornecida pela resposta de autorização será propagada para a resposta HTTP:
```php
Gate::authorize('edit-settings', $post);

// The action is authorized...
```
## Inspecionando a checagem do Gate

Às vezes, você pode desejar conceder todas as habilidades a um usuário específico. Você pode usar o método before para definir um retorno de chamada que é executado antes de todas as outras verificações de autorização:
```php
Gate::before(function ($user, $ability) {
    if ($user->isSuperAdmin()) {
        return true;
    }
});
```
Se o retorno de chamada anterior retornar um resultado não nulo, esse resultado será considerado o resultado da verificação.
Você pode usar o método after para definir um retorno de chamada a ser executado após todas as outras verificações de autorização:
```php
Gate::after(function ($user, $ability, $result, $arguments) {
    if ($user->isSuperAdmin()) {
        return true;
    }
});
```
Semelhante à verificação com before, se o retorno de chamada after retornar um resultado não nulo, esse resultado será considerado o resultado da verificação.

## Criando Policies

Policies são classes que organizam a lógica de autorização em torno de um modelo ou recurso específico. Por exemplo, se seu aplicativo for um blog, você pode ter um modelo Post e uma PostPolicy correspondente para autorizar ações do usuário, como criar ou atualizar postagens no blog.

Você pode criar uma política usando o comando make:policy do artisan. A política gerada será colocada no diretório app/Policies. Se este diretório não existir em seu aplicativo, o Laravel irá criá-lo para você:

php artisan make:policy PostPolicy

O comando make:policy irá criar uma classe de política vazia. Se desejar gerar uma classe com os métodos básicos de política, tipo um "CRUD" já incluído na classe, você pode especificar um --model ao executar o comando:

php artisan make:policy PostPolicy --model=Post

Todas as políticas são resolvidas através do contêiner de serviço Laravel, permitindo que você digite qualquer dependência necessária no construtor da política para que sejam injetadas automaticamente.

## Registrando Policies

Uma vez que a política exista, ela precisa ser registrada. O AuthServiceProvider incluído nos novos aplicativos do Laravel contém uma propriedade de políticas que mapeia seus modelos do Eloquent para suas políticas correspondentes. O registro de uma política instruirá o Laravel sobre qual política utilizar ao autorizar ações contra um determinado modelo:
```php
<?php
namespace App\Providers;

use App\Models\Post;
use App\Policies\PostPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Post::class => PostPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
```
## Auto-descoberta de política

Em vez de registrar manualmente as políticas do modelo, o Laravel pode descobrir as políticas automaticamente, desde que o modelo e a política sigam as convenções de nomenclatura padrão do Laravel. Especificamente, as políticas devem estar em um diretório Policies ou acima do diretório que contém seus modelos. Assim, por exemplo, os modelos podem ser colocados no diretório app/Models enquanto as políticas podem ser colocadas no diretório app/Policies. Nesta situação, o Laravel irá verificar as políticas em app/Models/Policies e depois app/Policies. Além disso, o nome da política deve corresponder ao nome do modelo e ter um sufixo de Policy. Portanto, um modelo de usuário corresponderia a uma classe UserPolicy.

Se desejar fornecer sua própria lógica de descoberta de política, você pode registrar um retorno de chamada personalizado usando o método Gate::guessPolicyNamesUsing. Normalmente, esse método deve ser chamado a partir do método de inicialização do AuthServiceProvider do seu aplicativo:
```php
use Illuminate\Support\Facades\Gate;

Gate::guessPolicyNamesUsing(function ($modelClass) {
    // return policy class name...
});
```
Todas as políticas mapeadas explicitamente em seu AuthServiceProvider terão precedência sobre quaisquer políticas potencialmente descobertas automaticamente.

## Escrevendo Policies

### Métodos de política

Assim que a política for registrada, você pode adicionar métodos para cada ação que ela autorizar. Por exemplo, vamos definir um método de atualização em nossa PostPolicy que determina se um determinado usuário pode atualizar uma determinada instância de Post.

O método de atualização receberá uma instância de Usuário e uma de Post como seus argumentos e deve retornar verdadeiro ou falso indicando se o usuário está autorizado a atualizar o Post fornecido. Portanto, para este exemplo, vamos verificar se o id do usuário corresponde ao user_id na postagem:
```php
<?php
namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return bool
     */
    public function update(User $user, Post $post)
    {
        return $user->id === $post->user_id;
    }
}
```
Você pode continuar a definir métodos adicionais na política conforme necessário para as várias ações que ela autorizar. Por exemplo, você pode definir métodos de visualização ou exclusão para autorizar várias ações de postagem, mas lembre-se de que é livre para dar aos métodos de política qualquer nome que desejar.

Se você usou a opção --model ao gerar sua política por meio do console Artisan, ela já conterá métodos para as ações viewAny, view, create, update, delete, restore e forceDelete.

## Respostas de política

Até agora, examinamos apenas métodos de política que retornam valores booleanos simples. No entanto, às vezes você pode querer retornar uma resposta mais detalhada, incluindo uma mensagem de erro. Para fazer isso, você pode retornar um Illuminate\Auth\Access\Response do seu método de política:
```php
use Illuminate\Auth\Access\Response;

/**
 * Determine if the given post can be updated by the user.
 *
 * @param  \App\Models\User  $user
 * @param  \App\Models\Post  $post
 * @return \Illuminate\Auth\Access\Response
 */
public function update(User $user, Post $post)
{
    return $user->id === $post->user_id
                ? Response::allow()
                : Response::deny('You do not own this post.');
}
```
Ao retornar uma resposta de autorização de sua política, o método Gate::allow ainda retornará um valor booleano simples; no entanto, você pode usar o método Gate::inspect para obter a resposta de autorização completa retornada pelo gate:
```php
$response = Gate::inspect('update', $post);

if ($response->allowed()) {
    // The action is authorized...
} else {
    echo $response->message();
}
```
Ao usar o método Gate::authorize para lançar um AuthorizationException se a ação não for autorizada, a mensagem de erro fornecida pela resposta de autorização será propagada para a resposta HTTP:
```php
Gate::authorize('update', $post);

// The action is authorized...
```

## Métodos sem modelos

Alguns métodos de política recebem apenas o usuário autenticado no momento e não uma instância do modelo que eles autorizam. Essa situação é mais comum ao autorizar ações create. Por exemplo, se você estiver criando um blog, pode desejar verificar se um usuário está autorizado a criar alguma postagem.

Ao definir métodos de política que não receberão uma instância de modelo, como um método create, ele não receberá uma instância do modelo. Em vez disso, você deve definir o método como apenas esperando o usuário autenticado:
```php
/**
 * Determine if the given user can create posts.
 *
 * @param  \App\Models\User  $user
 * @return bool
 */
public function create(User $user)
{
    //
}
```
## Usuários convidados

Por padrão, todas os Gates e Policies retornam automaticamente falso se a solicitação HTTP de entrada não foi iniciada por um usuário autenticado. No entanto, você pode permitir que essas verificações de autorização passem por seus gates e políticas declarando uma dica de tipo "opcional" ou fornecendo um valor padrão nulo para a definição do argumento do usuário:
```php
<?php
namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Post  $post
     * @return bool
     */
    public function update(?User $user, Post $post)
    {
        return optional($user)->id === $post->user_id;
    }
}
```
## Filtrando Políticas

Para certos usuários, você pode desejar autorizar todas as ações dentro de uma determinada política. Para fazer isso, defina um método antes na política. O método before será executado antes de qualquer outro método na política, dando a você a oportunidade de autorizar a ação antes que o método de política pretendido seja realmente chamado. Este recurso é mais comumente usado para autorizar administradores de aplicativos a executar qualquer ação:
```php
public function before($user, $ability)
{
    if ($user->isSuperAdmin()) {
        return true;
    }
}
```
Se você deseja negar todas as autorizações para um usuário, você deve retornar false do método anterior. Se nulo for retornado, a autorização irá cair para o método de política.

O método before de uma classe de política não será chamado se a classe não contiver um método com um nome que corresponda ao nome da habilidade que está sendo verificada.

## Autorizando ações usando policies

### Via model User

O model User que está incluso em sua aplicação Laravel inclui dois métodos úteis para autorizar ações: can e cant. O método can recebe a ação que você deseja autorizar e o modelo relevante. Por exemplo, vamos determinar se um usuário está autorizado a atualizar um determinado modelo Post:
```php
if ($user->can('update', $post)) {
    //
}
```
Se uma política for registrada para o modelo fornecido, o método can chamará automaticamente a política apropriada e retornará o resultado boleano. Se nenhuma política for registrada para o modelo, o método can tentará chamar o Gate baseado na Closure correspondente ao nome da ação fornecida.

## Ações que não requerem model

Lembre-se de que algumas ações como create podem não exigir uma instância de model. Nessas situações, você pode passar um nome da classe para o método can. O nome da classe será usado para determinar qual política usar ao autorizar a ação:
```php
use App\Models\Post;

if ($user->can('create', Post::class)) {
    // Executes the "create" method on the relevant policy...
}
```
## Via middleware

O Laravel inclui um middleware que pode autorizar ações antes mesmo que a solicitação de entrada alcance suas rotas ou controladores. Por padrão, o middleware Illuminate\Auth\Middleware\Authorize é atribuído à chave can em sua classe App\Http\Kernel. Vamos explorar um exemplo de uso do middleware can para autorizar um usuário para atualizar uma postagem de blog:
```php
use App\Models\Post;

Route::put('/post/{post}', function (Post $post) {
    // The current user may update the post...
})->middleware('can:update,post');
```
Neste exemplo, estamos passando dois argumentos ao middleware can. O primeiro é o nome da ação que desejamos autorizar e o segundo é o parâmetro de rota que desejamos passar para o método de política. Nesse caso, como estamos usando a vinculação de modelo implícita, um modelo Post será passado para o método de política. Se o usuário não estiver autorizado a executar a ação fornecida, uma resposta HTTP com um código de status 403 será gerada pelo middleware.

## Ações que não requerem model

Novamente, algumas ações como create podem não exigir uma instância de modelo. Nessas situações, você pode passar um nome de classe para o middleware. O nome da classe será usado para determinar qual política usar ao autorizar a ação:
```php
Route::post('/post', function () {
    // The current user may create posts...
})->middleware('can:create,App\Models\Post');
```
## Através de helpers de controllers

Além de métodos úteis fornecidos para o modelo de usuário, o Laravel fornece um método de autorização útil para qualquer um de seus controladores que estendem a classe base App\Http\Controllers\Controller. Como o método can, este método aceita o nome da ação que você deseja autorizar e o modelo relevante. Se a ação não for autorizada, o método de autorização lançará um Illuminate\Auth\Access\AuthorizationException, que o manipulador de exceção padrão do Laravel converterá em uma resposta HTTP com um código de status 403:
```php
<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Update the given blog post.
     *
     * @param  Request  $request
     * @param  Post  $post
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        // The current user can update the blog post...
    }
}
```
## Ações que não requerem model

Conforme discutido anteriormente, algumas ações como create podem não exigir uma instância de modelo. Nessas situações, você deve passar um nome da classe para o método de autorização. O nome da classe será usado para determinar qual política usar ao autorizar a ação:
```php
/**
 * Create a new blog post.
 *
 * @param  Request  $request
 * @return Response
 * @throws \Illuminate\Auth\Access\AuthorizationException
 */
public function create(Request $request)
{
    $this->authorize('create', Post::class);

    // The current user can create blog posts...
}
```
## Autorizando controladores de recursos

Se você estiver utilizando controllers de resources, poderá usar o método authorizeResource no construtor do controlador. Este método anexará as definições de middleware can apropriadas aos métodos do controlador de recursos.

O método authorizeResource aceita o nome da classe do modelo como seu primeiro argumento e o nome do parâmetro de rota/request que conterá o ID do modelo como seu segundo argumento. Você deve garantir que seu controlador de recursos seja criado com a sinalização --model para ter as assinaturas de método necessárias e dicas de tipo:
```php
<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Post::class, 'post');
    }
}
```
Os seguintes métodos de controller serão mapeados para seu método de política correspondente:
```php
Controller Method 	Policy Method

index 	            viewAny
show 	            view
create 	            create
store 	            create
edit 	            update
update 	            update
destroy 	        delete
```
## Através do Blade

Ao criar views usando o Blade, você pode desejar exibir uma parte da página apenas se o usuário estiver autorizado a executar uma determinada ação. Por exemplo, você pode querer mostrar um formulário de atualização para uma postagem de blog apenas se o usuário puder realmente atualizar a postagem. Nesta situação, você pode usar a família de diretivas @can e @cannot:
```php
@can('update', $post)
    <!-- The Current User Can Update The Post -->
@elsecan('create', App\Models\Post::class)
    <!-- The Current User Can Create New Post -->
@endcan

@cannot('update', $post)
    <!-- The Current User Cannot Update The Post -->
@elsecannot('create', App\Models\Post::class)
    <!-- The Current User Cannot Create A New Post -->
@endcannot
```
Essas diretivas são atalhos convenientes para escrever instruções @if e @unless. As declarações @can e @cannot acima se traduzem, respectivamente, nas seguintes declarações:
```php
@if (Auth::user()->can('update', $post))
    <!-- The Current User Can Update The Post -->
@endif

@unless (Auth::user()->can('update', $post))
    <!-- The Current User Cannot Update The Post -->
@endunless
```
Mas de forma mais simples.

Você também pode determinar se um usuário tem algum privilégio de autorização de uma determinada lista de privilégios. Para fazer isso, use a diretiva @canany:
```php
@canany(['update', 'view', 'delete'], $post)
    // The current user can update, view, or delete the post
@elsecanany(['create'], \App\Models\Post::class)
    // The current user can create a post
@endcanany
```
## Ações que não requerem model

Como a maioria dos outros métodos de autorização, você pode passar um nome de classe para as diretivas @can e @cannot se a ação não exigir uma instância de modelo:
```php
@can('create', App\Models\Post::class)
    <!-- The Current User Can Create Posts -->
@endcan

@cannot('create', App\Models\Post::class)
    <!-- The Current User Can't Create Posts -->
@endcannot
```
## Suprindo contexto adicional

Ao autorizar ações usando políticas, você pode passar uma matriz/array como o segundo argumento para as várias funções de autorização e auxiliares. O primeiro elemento na matriz/array será usado para determinar qual política deve ser chamada, enquanto o restante dos elementos da matriz/array são passados como parâmetros para o método de política e podem ser usados para contexto adicional ao tomar decisões de autorização. Por exemplo, considere a seguinte definição de método PostPolicy que contém um parâmetro $category adicional:
```php
/**
 * Determine if the given post can be updated by the user.
 *
 * @param  \App\Models\User  $user
 * @param  \App\Models\  $post
 * @param  int  $category
 * @return bool
 */
public function update(User $user, Post $post, int $category)
{
    return $user->id === $post->user_id &&
           $category > 3;
}
```
Ao tentar determinar se o usuário autenticado pode atualizar uma determinada postagem, podemos invocar este método de política assim:
```php
/**
 * Update the given blog post.
 *
 * @param  Request  $request
 * @param  Post  $post
 * @return Response
 * @throws \Illuminate\Auth\Access\AuthorizationException
 */
public function update(Request $request, Post $post)
{
    $this->authorize('update', [$post, $request->input('category')]);

    // The current user can update the blog post...
}
```
## Original em

https://laravel.com/docs/8.x/authorization

