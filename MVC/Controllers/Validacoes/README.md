# Validações no Laravel 8

Este arquivo basicamente é uma tradução livre da documentação oficial do Laravel 8. Novamente contei com a ajuda do Google Translator.

O Laravel oferece várias abordagens diferentes para validar os dados de entrada de sua aplicação. Por padrão, a classe controller base do Laravel usa um trait ValidatesRequests que fornece um método conveniente para validar solicitações/requests HTTP de entrada com uma variedade de regras de validação poderosas.

## Exemplo rápido

Um exemplo completo de validação de um form e mostrar as mensagens de erro de volta para o usuário.

## Criar o controller
```php
<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Show the form to create a new blog post.
     *
     * @return Response
     */
    public function create()
    {
        return view('post.create');
    }

    /**
     * Store a new blog post.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        // Validate and store the blog post...
    }
}
```
## Definindo as rotas

routes/web.php:
```php
use App\Http\Controllers\PostController;

Route::get('post/create', [PostController::class, 'create']);
Route::post('post', [PostController::class, 'store']);
```
A rota GET irá mostrar o form create. Enquanto que a rota POST armazenará o post no banco.

## Escrevendo a validação

Agora estamos prontos para preencher nosso método store() com a lógica para validar a nova postagem do blog. Para fazer isso, usaremos o método de validação fornecido pelo objeto Illuminate\Http\Request. Se as regras de validação forem aprovadas, seu código continuará executando normalmente; no entanto, se a validação falhar, uma exceção será lançada e a resposta de erro adequada será enviada automaticamente de volta ao usuário. No caso de uma solicitação HTTP tradicional, uma resposta de redirecionamento será gerada, enquanto uma resposta JSON será enviada para solicitações AJAX.
Para entender melhor o método de validação, vamos voltar ao método store():
```php
/**
 * Store a new blog post.
 *
 * @param  Request  $request
 * @return Response
 */
public function store(Request $request)
{
    $validatedData = $request->validate([
        'title' => 'required|unique:posts|max:255',
        'body' => 'required',
    ]);

    // The blog post is valid...
}
```
Como você pode ver, passamos as regras de validação desejadas para o método de validação. Novamente, se a validação falhar, a resposta adequada será gerada automaticamente. Se a validação passar, nosso controlador continuará executando normalmente.
Como alternativa, as regras de validação podem ser especificadas como arrays de regras/rules em vez de uma única | string delimitada:
```php
$validatedData = $request->validate([
    'title' => ['required', 'unique:posts', 'max:255'],
    'body' => ['required'],
]);
```
Você pode usar o método validateWithBag para validar uma solicitação/request e armazenar qualquer mensagem de erro em um pacote de erro nomeado:
```php
$validatedData = $request->validateWithBag('post', [
    'title' => ['required', 'unique:posts', 'max:255'],
    'body' => ['required'],
]);
```
## Parando na primeira falha de validação

Às vezes, você pode desejar interromper a execução de regras de validação em um atributo após a primeira falha de validação. Para fazer isso, atribua a regra de fiança/bail ao atributo:
```php
$request->validate([
    'title' => 'bail|required|unique:posts|max:255',
    'body' => 'required',
]);
```
Neste exemplo, se a regra unique no atributo title falhar, a regra max não será verificada. As regras serão validadas na ordem em que são atribuídas.

## Uma nota sobre atributos aninhados/nested

Se sua solicitação HTTP contém parâmetros "aninhados", você pode especificá-los em suas regras de validação usando a sintaxe de "ponto/dot":
```php
$request->validate([
    'title' => 'required|unique:posts|max:255',
    'author.name' => 'required',
    'author.description' => 'required',
]);
```
Por outro lado, se o nome do seu campo contiver um ponto final literal, você pode impedir explicitamente que isso seja interpretado como sintaxe de "ponto/dot" escapando o ponto com uma barra invertida:
```php
$request->validate([
    'title' => 'required|unique:posts|max:255',
    'v1\.0' => 'required',
]);
```
## Exibindo os erros de validação

Então, e se os parâmetros de solicitação/request de entrada não passarem nas regras de validação fornecidas? Como mencionado anteriormente, o Laravel irá redirecionar automaticamente o usuário de volta ao local anterior. Além disso, todos os erros de validação serão automaticamente transferidos para a sessão.
Novamente, observe que não tivemos que vincular explicitamente as mensagens de erro à visualização em nossa rota GET. Isso ocorre porque o Laravel irá verificar se há erros nos dados da sessão, e automaticamente vinculá-los à visualização se estiverem disponíveis. A variável $errors será uma instância de Illuminate\Support\MessageBag. Para mais informações sobre como trabalhar com este objeto, verifique sua documentação (https://laravel.com/docs/8.x/validation#working-with-error-messages).

A variável $errors é vinculada à visualização pelo middleware Illuminate\View\Middleware\ShareErrorsFromSession, que é fornecido pelo grupo de middleware da web. Quando este middleware é aplicado, uma variável $errors estará sempre disponível em suas views, permitindo que você convenientemente presuma que a variável $errors está sempre definida e pode ser usada com segurança.

Assim, em nosso exemplo, o usuário será redirecionado para o método create de nosso controlador quando a validação falhar, permitindo-nos exibir as mensagens de erro na view:
```php
<!-- /resources/views/post/create.blade.php -->

<h1>Create Post</h1>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Create Post Form -->
```
## A diretiva @error

Você também pode usar a diretiva @error do Blade para verificar rapidamente se existem mensagens de erro de validação para um determinado atributo. Dentro de uma diretiva @error, você pode mostrar/echo a variável $message para exibir a mensagem de erro:
```php
<!-- /resources/views/post/create.blade.php -->

<label for="title">Post Title</label>

<input id="title" type="text" class="@error('title') is-invalid @enderror">

@error('title')
    <div class="alert alert-danger">{{ $message }}</div>
@enderror
```
## Uma nota sobre campos opcionais

Por padrão, o Laravel inclui o middleware TrimStrings e ConvertEmptyStringsToNull na pilha de middlewares global do seu aplicativo. Esses middlewares são listados na pilha pela classe App\Http\Kernel. Por causa disso, você frequentemente precisará marcar seus campos de solicitação/request "opcionais" como anuláveis se não quiser que o validador considere valores nulos como inválidos. Por exemplo:
```php
$request->validate([
    'title' => 'required|unique:posts|max:255',
    'body' => 'required',
    'publish_at' => 'nullable|date',
]);
```
Neste exemplo, estamos especificando que o campo publish_at pode ser nulo ou uma representação de data válida. Se o modificador nullable não for adicionado à definição de regra, o validador consideraria nula uma data inválida.

## Solicitações e validação AJAX

Neste exemplo, usamos um formulário tradicional para enviar dados ao aplicativo. No entanto, muitos aplicativos usam solicitações AJAX. Ao usar o método validate durante uma requisição AJAX, o Laravel não irá gerar uma resposta de redirecionamento. Em vez disso, o Laravel gera uma resposta JSON contendo todos os erros de validação. Esta resposta JSON será enviada com um código de status HTTP 422.

## Validação de solicitação de formulário

Criação de solicitações de formulário

Para cenários de validação mais complexos, você pode desejar criar uma "form request". Form request são classes de solicitação personalizadas que contêm lógica de validação. Para criar uma classe form request, use o comando make: request Artisan da CLI:

php artisan make:request StoreBlogPost

A classe gerada será colocada no diretório app/Http/Requests. Se este diretório não existir, ele será criado quando você executar o comando make:request. Vamos adicionar algumas regras de validação ao método rules:
```php
/**
 * Get the validation rules that apply to the request.
 *
 * @return array
 */
public function rules()
{
    return [
        'title' => 'required|unique:posts|max:255',
        'body' => 'required',
    ];
}
```
Então, como as regras de validação são avaliadas? Tudo o que você precisa fazer é digitar uma dica da solicitação no método do seu controlador. O form request de entrada é validado antes que o método do controlador seja chamado, o que significa que você não precisa sobrecarregar seu controlador com nenhuma lógica de validação:
```php
/**
 * Store the incoming blog post.
 *
 * @param  StoreBlogPost  $request
 * @return Response
 */
public function store(StoreBlogPost $request)
{
    // The incoming request is valid...

    // Retrieve the validated input data...
    $validated = $request->validated();
}
```
Se a validação falhar, uma resposta de redirecionamento será gerada para enviar o usuário de volta ao local anterior. Os erros também serão exibidos na sessão para que estejam disponíveis para exibição. Se a solicitação for AJAX, uma resposta HTTP com um código de status 422 será retornada ao usuário, incluindo uma representação JSON dos erros de validação.

Adicionando ganchos/hooks posteriores a solicitações de formulário

Se desejar adicionar um gancho "depois" a uma solicitação de formulário, você pode usar o método withValidator. Este método recebe o validador totalmente construído, permitindo que você chame qualquer um de seus métodos antes que as regras de validação sejam realmente avaliadas:
```php
/**
 * Configure the validator instance.
 *
 * @param  \Illuminate\Validation\Validator  $validator
 * @return void
 */
public function withValidator($validator)
{
    $validator->after(function ($validator) {
        if ($this->somethingElseIsInvalid()) {
            $validator->errors()->add('field', 'Something is wrong with this field!');
        }
    });
}
```
## Autorização request de form

A classe de form request também contém um método de autorização. Nesse método, você pode verificar se o usuário autenticado realmente tem autoridade para atualizar um determinado recurso. Por exemplo, você pode determinar se um usuário realmente possui um comentário de blog que está tentando atualizar:
```php
/**
 * Determine if the user is authorized to make this request.
 *
 * @return bool
 */
public function authorize()
{
    $comment = Comment::find($this->route('comment'));

    return $comment && $this->user()->can('update', $comment);
}
```
Uma vez que todas as form request estendem a classe de solicitação/request base do Laravel, podemos usar o método do usuário para acessar o usuário autenticado no momento. Observe também a chamada ao método de rota no exemplo acima. Este método concede acesso aos parâmetros de URI definidos na rota que está sendo chamada, como o parâmetro {comment} no exemplo abaixo:

Route :: post ('comentário/{comentário}');

Se o método de autorização retornar falso, uma resposta HTTP com um código de status 403 será automaticamente retornada e seu método de controlador não será executado.

Se você planeja ter lógica de autorização em outra parte do seu aplicativo, retorne true do método de autorização:
```php
/**
 * Determine if the user is authorized to make this request.
 *
 * @return bool
 */
public function authorize()
{
    return true;
}
```
## Personalizando as Mensagens de Erro

Você pode personalizar as mensagens de erro usadas pela solicitação do formulário, substituindo o método de mensagens. Este método deve retornar um array de pares de atributo/rule e suas mensagens de erro correspondentes:
```php
/**
 * Get the error messages for the defined validation rules.
 *
 * @return array
 */
public function messages()
{
    return [
        'title.required' => 'A title is required',
        'body.required' => 'A message is required',
    ];
}
```
## Personalizando os atributos de validação

Se você quiser que a parte: attribute de sua mensagem de validação seja substituída por um nome de atributo personalizado, você pode especificar os nomes personalizados substituindo o método de atributos. Este método deve retornar um array de pares de atributo/nome:
```php
/**
 * Get custom attributes for validator errors.
 *
 * @return array
 */
public function attributes()
{
    return [
        'email' => 'email address',
    ];
}
```
## Prepare input for validation

Se precisar sanitizar quaisquer dados do request antes de aplicar suas regras de validação, você pode usar o método prepareForValidation:
```php
use Illuminate\Support\Str;

/**
 * Prepare the data for validation.
 *
 * @return void
 */
protected function prepareForValidation()
{
    $this->merge([
        'slug' => Str::slug($this->slug),
    ]);
}
```
## Criando manualmente valodadores

Se você não quiser usar o método de validação no request, você pode criar uma instância do validador manualmente usando a facade do validador. O método make na facade gera uma nova instância do validador:
```php
<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Store a new blog post.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts|max:255',
            'body' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('post/create')
                        ->withErrors($validator)
                        ->withInput();
        }

        // Store the blog post...
    }
}
```
O primeiro argumento passado para o método make são os dados sob validação. O segundo argumento é uma matriz/array das regras de validação que devem ser aplicadas aos dados.

Depois de verificar se a validação da solicitação falhou, você pode usar o método withErrors para enviar as mensagens de erro para a sessão. Ao usar este método, a variável $errors será compartilhada automaticamente com suas views após o redirecionamento, permitindo que você as exiba facilmente de volta para o usuário. O método withErrors aceita um validador, um MessageBag ou um array PHP.

## Redirecionamento Automático

Se desejar criar uma instância do validador manualmente, mas ainda aproveitar o redirecionamento automático oferecido pelo método de validação da solicitação, você pode chamar o método de validação em uma instância do validador existente. Se a validação falhar, o usuário será redirecionado automaticamente ou, no caso de uma solicitação AJAX, uma resposta JSON será retornada:
```php
Validator::make($request->all(), [
    'title' => 'required|unique:posts|max:255',
    'body' => 'required',
])->validate();
```
Você pode usar o método validateWithBag para armazenar as mensagens de erro em um pacote de erro nomeado se a validação falhar:
```php
Validator::make($request->all(), [
    'title' => 'required|unique:posts|max:255',
    'body' => 'required',
])->validateWithBag('post');
```
Sacos de erro nomeados

Se você tiver vários formulários em uma única página, pode desejar nomear o MessageBag de erros, permitindo que você recupere as mensagens de erro de um formulário específico. Passe um nome como o segundo argumento para withErrors:
```php
return redirect('register')
            ->withErrors($validator, 'login');
```
Você pode então acessar a instância MessageBag nomeada a partir da variável $errors:
```php
{{ $errors->login->first('email') }}
```
## Após Gancho/Hook de Validação

O validador também permite que você anexe callbacks a serem executados após a validação ser concluída. Isso permite que você execute mais validações facilmente e até mesmo adicione mais mensagens de erro à coleção de mensagens. Para começar, use o método after em uma instância do validador:
```php
$validator = Validator::make(...);

$validator->after(function ($validator) {
    if ($this->somethingElseIsInvalid()) {
        $validator->errors()->add('field', 'Something is wrong with this field!');
    }
});

if ($validator->fails()) {
    //
}
```
## Trabalho com mensagens de erro

Depois de chamar o método de erros em uma instância do Validator, você receberá uma instância Illuminate\Support\MessageBag, que tem uma variedade de métodos convenientes para trabalhar com mensagens de erro. A variável $errors, automaticamente disponibilizada para todas as visualizações, também é uma instância da classe MessageBag.

## Recuperando a primeira mensagem de erro para um campo

Para recuperar a primeira mensagem de erro para um determinado campo, use o método first:
```php
$errors = $validator->errors();

echo $errors->first('email');
```
## Recuperando todas as mensagens de erro para um campo

Se você precisar recuperar uma matriz de todas as mensagens de um determinado campo, use o método get:
```php
foreach ($errors->get('email') as $message) {
    //
}
```
Se estiver validando um campo de formulário de array, você pode recuperar todas as mensagens para cada um dos elementos da matriz usando o caractere *:
```php
foreach ($errors->get('attachments.*') as $message) {
    //
}
```
## Recuperando todas as mensagens de erro para todos os campos

Para recuperar uma matriz de todas as mensagens para todos os campos, use o método all:
```php
foreach ($errors->all() as $message) {
    //
}
```
## Determinando se as mensagens existem para um campo

O método has pode ser usado para determinar se existe alguma mensagem de erro para um determinado campo:
```php
if ($errors->has('email')) {
    //
}
```
## Mensagens de erro personalizadas

Se necessário, você pode usar mensagens de erro personalizadas para validação, em vez dos padrões. Existem várias maneiras de especificar mensagens personalizadas. Primeiro, você pode passar as mensagens personalizadas como o terceiro argumento para o método Validator::make:
```php
$messages = [
    'required' => 'The :attribute field is required.',
];

$validator = Validator::make($input, $rules, $messages);
```
Neste exemplo, o :attribute placeholder será substituído pelo nome real do campo em validação. Você também pode utilizar outros marcadores de posição nas mensagens de validação. Por exemplo:
```php
$messages = [
    'same' => 'The :attribute and :other must match.',
    'size' => 'The :attribute must be exactly :size.',
    'between' => 'The :attribute value :input is not between :min - :max.',
    'in' => 'The :attribute must be one of the following types: :values',
];
```
## Especificando uma mensagem personalizada para um determinado atributo

Às vezes, você pode desejar especificar uma mensagem de erro personalizada apenas para um campo específico. Você pode fazer isso usando a notação de "ponto/dot". Especifique o nome do atributo primeiro, seguido pela regra:
```php
$messages = [
    'email.required' => 'We need to know your e-mail address!',
];
```
## Especificando mensagens personalizadas em arquivos de idioma

Na maioria dos casos, você provavelmente especificará suas mensagens personalizadas em um arquivo de idioma em vez de passá-las diretamente para o Validador. Para fazer isso, adicione suas mensagens ao array personalizado no arquivo de idioma resources/lang/xx/validation.php.
```php
'custom' => [
    'email' => [
        'required' => 'We need to know your e-mail address!',
    ],
],
```
## Especificando Valores de Atributos Personalizados

Se você deseja que a parte :attribute de sua mensagem de validação seja substituída por um nome de atributo personalizado, você pode especificar o nome personalizado na matriz de atributos de seu arquivo de idioma resources/lang/xx/validation.php:
```php
'attributes' => [
    'email' => 'email address',
],
```
Você também pode passar os atributos personalizados como o quarto argumento para o método Validator::make:
```php
$customAttributes = [
    'email' => 'email address',
];

$validator = Validator::make($input, $rules, $messages, $customAttributes);
```
## Especificando valores personalizados em arquivos de idioma

Às vezes, você pode precisar que a parte :value de sua mensagem de validação seja substituída por uma representação personalizada do valor. Por exemplo, considere a seguinte regra que especifica que um número de cartão de crédito é necessário se o payment_type tiver um valor cc:
```php
$request->validate([
    'credit_card_number' => 'required_if:payment_type,cc'
]);
```
Se essa regra de validação falhar, ela produzirá a seguinte mensagem de erro:

The credit card number field is required when payment type is cc.

Em vez de exibir cc como o valor do tipo de pagamento, você pode especificar uma representação de valor personalizado em seu arquivo de linguagem de validação, definindo uma matriz de valores:
```php
'values' => [
    'payment_type' => [
        'cc' => 'credit card'
    ],
],
```
Agora, se a regra de validação falhar, ela produzirá a seguinte mensagem:

The credit card number field is required when payment type is credit card.

## Regras de validação disponíveis
```php
Accepted
Active URL
After (Date)
After Or Equal (Date)
Alpha
Alpha Dash
Alpha Numeric
Array
Bail
Before (Date)
Before Or Equal (Date)
Between
Boolean
Confirmed
Date
Date Equals
Date Format
Different
Digits
Digits Between
Dimensions (Image Files)
Distinct
Email
Ends With
Exclude If
Exclude Unless
Exists (Database)
File
Filled
Greater Than
Greater Than Or Equal
Image (File)
In
In Array
Integer
IP Address
JSON
Less Than
Less Than Or Equal
Max
MIME Types
MIME Type By File Extension
Min
Multiple Of
Not In
Not Regex
Nullable
Numeric
Password
Present
Regular Expression
Required
Required If
Required Unless
Required With
Required With All
Required Without
Required Without All
Same
Size
Sometimes
Starts With
String
Timezone
Unique (Database)
URL
UUID
```

## Adicionando regras condicionalmente

Ignorando validação quando os campos têm certos valores

Ocasionalmente, você pode desejar não validar um determinado campo se outro campo tiver um determinado valor. Você pode fazer isso usando a regra de validação exclude_if. Neste exemplo, os campos appointment_date e doctor_name não serão validados se o campo has_appointment tiver um valor falso:
```php
$v = Validator::make($data, [
    'has_appointment' => 'required|bool',
    'appointment_date' => 'exclude_if:has_appointment,false|required|date',
    'doctor_name' => 'exclude_if:has_appointment,false|required|string',
]);
```
Como alternativa, você pode usar a regra exclude_unless para não validar um determinado campo, a menos que outro campo tenha um determinado valor:
```php
$v = Validator::make($data, [
    'has_appointment' => 'required|bool',
    'appointment_date' => 'exclude_unless:has_appointment,true|required|date',
    'doctor_name' => 'exclude_unless:has_appointment,true|required|string',
]);
```
## Validando quando presente

Em algumas situações, você pode desejar executar verificações de validação em um campo apenas se esse campo estiver presente na matriz de entrada. Para fazer isso rapidamente, adicione a regra às vezes à sua lista de regras:
```php
$v = Validator::make($data, [
    'email' => 'sometimes|required|email',
]);
```
No exemplo acima, o campo email só será validado se estiver presente no array $data.


## Exemplo simples de validação usando formRequest

[Exemplo](Exemplo.md)

## Referências

- https://laravel.com/docs/8.x/validation
- https://medium.com/@rosselli00/validating-in-laravel-7e88bbe1b627
- https://medium.com/@kamerk22/the-smart-way-to-handle-request-validation-in-laravel-5e8886279271
- https://laravel-news.com/laravel-validation-101-controllers-form-requests-and-rules
- https://www.sitepoint.com/data-validation-laravel-right-way-custom-validators/
