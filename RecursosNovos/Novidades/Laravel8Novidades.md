# Laravel 8 – O lançamento da nova versão cheia de novidades!

    • DialHost Internet 

Neste artigo, você aprenderá sobre as novidades do lançamento do Framework Laravel 8. Com melhorias de segurança e autenticação além uma variedade de outras correções de bugs e melhorias de usabilidade.

Setembro de 2020 marcou a chegada de uma das mais aguardadas atualizações dentro da comunidade de desenvolvimento: o Laravel 8 o lançamento da nova versão cheia de novidades!

O Laravel é um framework de aplicação web e sua base é o PHP. Como principal função buscar facilitar as tarefas comuns usadas na maioria dos projetos web, como autenticação, roteamento, sessões e cache.

Tornar o processo de desenvolvimento agradável para o desenvolvedor sem sacrificar a funcionalidade do aplicativo é música aos ouvidos, tanto para quem está começando como para quem já domina as tarefas do lado backend da Web.

O Laravel 8 continua com as melhorias feitas no Laravel 7.x, introduzindo Laravel Jetstream, classes de model factory, migration squashing para seus arquivos de migração, uma nova funcionalidade de Job Batching além de uma variedade de outras correções de bugs e melhorias de usabilidade.

## Tópicos
    • Laravel JetStream
    • Models agora organizados dentro de pastas
    • Classes de Model Factory
    • Migration Squashing
    • Job Batching
    • Modo de manutenção
    • Pré renderização do Modo de manutenção
    • Ajudantes de teste de tempo
    • Melhorias no Comando Artisan Serve
    • isualizações de paginação do Tailwind CSS
    • Roteamento de atualizações de namespace
    • Instalação
    • Composer

## Laravel JetStream

O Jetstream fornece o ponto de partida perfeito para seu próximo projeto incluindo login, registro, verificação de e-mail, autenticação de dois fatores, gerenciamento de sessão, suporte de API via Laravel Sanctum e gerenciamento de usuários.

Este pacote irá aprimorar e substituir a estrutura de template base de autenticação do usuário das versões anteriores do Laravel, entregando mais segurança e facilidade. O Jetstream é projetado utilizando o Tailwind CSS.

Models agora organizados dentro de pastas

É isso mesmo o que você leu! Agora todos os desenvolvedores que sofriam com a organização das Models tiveram seus problemas encerrados. Se antes as models ficavam por padrão na pasta raiz de app, agora elas possuem uma pasta para chamar de sua: app/Models.

Todos os comandos relevantes do gerador foram atualizados para assumir que os modelos existem no diretório app/Models. O desenvolvedor ainda tem a liberdade de colocar suas models dentro do diretório raiz da pasta app sem maiores problemas. O que vale são as possibilidades para a organização de sua aplicação.

Classes de Model Factory

As model factories foram inteiramente reescritas e aprimoradas para serem utilizadas como classes. Por exemplo, a UserFactory incluída no Laravel é escrita da seguinte forma.
```php
<?php
namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ];
    }
}
```
Graças ao novo trait HasFactory pode ser verificada a existência do factory disponível nos modelos gerados, sendo utilizada como descrito a seguir.
use App\Models\User;

User::factory()->count(50)->create();

Como as Model Factory agora são classes PHP simples, as transformações de estado podem ser escritas como métodos de classe, o que traz facilidade para adicionar outras classes auxiliares dentro dos seus Modelos. Exemplos completos dessa funcionalidade estão na documentação oficial do Laravel.

Migration Squashing

Sua aplicação já passou por várias versões, podendo ter acumulado mais e mais arquivos de migrations ao longo do tempo. Isso pode fazer com que seu diretório de migração fique um tanto quanto inchado. Pensando nisso, se sua aplicação estiver usando MySQL ou PostgreSQL, agora você pode “esmagar” suas migrações em um único arquivo SQL. Para começar, execute o comando.

schema: dump:

Quando você executa este comando, o Laravel escreverá um arquivo “schema” na sua pasta database/schema . Agora, quando você tentar migrar seu banco de dados e nenhuma outra migração for executada, o Laravel executará o SQL do arquivo de schema primeiro. Após rodar os comandos do arquivo de schema, o Laravel executará todas as migrations restantes que não fizeram parte do arquivo de schema criado.

Job Batching

O recurso de Job Batching do Laravel permite que você execute facilmente um lote de Jobs (trabalhos) e em seguida, execute alguma ação quando o lote tiver concluído sua operação.

O novo método Batch do facade bus pode ser usado para despachar um lote de trabalhos. O envio em lote é útil principalmente quando combinado com retornos de chamada de conclusão. Portanto, você pode usar os métodos then, catch e finally para definir retornos de conclusão para o lote.

Cada um desses retornos de chamada receberá uma instância Illuminate\Bus\Batch quando forem chamados.
```php
use App\Jobs\ProcessPodcast;
use App\Podcast;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

$batch = Bus::batch([
    new ProcessPodcast(Podcast::find(1)),
    new ProcessPodcast(Podcast::find(2)),
    new ProcessPodcast(Podcast::find(3)),
    new ProcessPodcast(Podcast::find(4)),
    new ProcessPodcast(Podcast::find(5)),
])->then(function (Batch $batch) {
    // All jobs completed successfully...
})->catch(function (Batch $batch, Throwable $e) {
    // First batch job failure detected...
})->finally(function (Batch $batch) {
    // The batch has finished executing...
})->dispatch();

return $batch->id;
```
Executar os trabalhos e ter o retorno das ações ajuda muito na tarefa de debug. Para saber mais sobre Job Batching, acesse a documentação completa, pois ela possui uma série de exemplos.

Modo de manutenção

Em versões anteriores do Laravel, o recurso do modo de manutenção do php artisan podia ser contornado usando uma “lista de permissão” de endereços IP que tinham autorização para acessar o aplicativo. Este recurso foi removido em favor de uma solução mais simples – secret – em que uma senha é criada para se ter acesso a aplicação.

Confira esse exemplo abaixo.
```php
php artisan down --secret="1630542a-246b-4b66-afa1-dd72a4c43515"
```
Após esse comando ser executado, a aplicação ou site em “segredo” poderá ser acessada por meio de uma combinação de endereço + senha criada, desta forma.
https://example.com/1630542a-246b-4b66-afa1-dd72a4c43515

Isso acontece porque o Laravel emitirá um cookie de desvio de modo de manutenção para o seu navegador web, podendo então navegar normalmente como se aplicativo ou site não estivesse em modo de manutenção. Pense em como esse recurso poderá ajudar a exibir seu projeto nas reuniões!

Pré renderização do Modo de manutenção

Se você utilizar o comando php artisan down durante a implantação, seus usuários ainda podem encontrar erros se acessarem o aplicativo enquanto as dependências do Composer ou outros componentes de infraestrutura estão sendo atualizados.

Isso ocorre porque uma parte significativa do framework Laravel deve inicializar para determinar se sua aplicação está em modo de manutenção e renderizar a visualização do modo de manutenção usando o motor de templates.

Por esta razão, o Laravel agora permite que você pré-renderize uma visualização do modo de manutenção que será retornada no início do ciclo de solicitação. Esta visualização é renderizada antes que qualquer uma das dependências do seu aplicativo seja carregada.

Você pode pré-renderizar um modelo de sua escolha usando a opção de renderização do comando abaixo.
```php
php artisan down --render="errors::503"
```
Ajudantes de teste de tempo

Ao realizar os testes em sua aplicação, você pode ocasionalmente precisar modificar o tempo retornado por ajudantes como now ou Illuminate\Support\ Carbon :: now ().

A classe de teste de recursos básicos do Laravel agora inclui ajudantes que permitem a você manipular o tempo atual.
```php
public function testTimeCanBeManipulated()
{
    // Travel into the future...
    $this->travel(5)->milliseconds();
    $this->travel(5)->seconds();
    $this->travel(5)->minutes();
    $this->travel(5)->hours();
    $this->travel(5)->days();
    $this->travel(5)->weeks();
    $this->travel(5)->years();

    // Travel into the past...
    $this->travel(-5)->hours();

    // Travel to an explicit time...
    $this->travelTo(now()->subHours(6));

    // Return back to the present time...
    $this->travelBack();
}
```

Melhorias no Comando Artisan Serve

O comando Artisan serve foi aprimorado. Agora ele conta com o recurso automático de recarregar quando mudanças de variáveis são detectadas em seu arquivo .env local. 
Anteriormente, as alterações seriam carregadas somente reiniciando manualmente o servidor.
isualizações de paginação do Tailwind CSS

O paginador do Laravel foi atualizado, agora por padrão ele utilizará o framework CSS do Tailwind. O Tailwind CSS é uma estrutura CSS de baixo nível altamente personalizável que fornece todos os blocos de construção de que você precisa para criar designs sob medida, sem nenhum estilo opinativo irritante. Claro, as visualizações do Bootstrap 3 e 4 também permanecem disponíveis.

Roteamento de atualizações de namespace

Em versões anteriores do Laravel, o RouteServiceProvider continha uma propriedade $namespace. O valor desta propriedade seria automaticamente prefixado nas definições de rota do controlador e chamadas para o action helper / URL :: action method.

No Laravel 8.x, esta propriedade é nula por padrão. Isso significa que nenhum prefixo de namespace automático será feito pelo Laravel. Portanto, em novos aplicativos 
Laravel 8.x, as definições de rota do controlador devem ser definidas usando a sintaxe padrão de chamada do PHP.
```php
use App\Http\Controllers\UserController;

Route::get('/users', [UserController::class, 'index']);
```
As chamadas para os métodos relacionados à ação devem usar a mesma sintaxe que pode ser chamada.
```php
action([UserController::class, 'index']);

return Redirect::action([UserController::class, 'index']);
```
Se você preferir o prefixo de rota do controlador no estilo Laravel 7.x, pode simplesmente adicionar a propriedade $namespace no RouteServiceProvider de sua aplicação. É importante ressaltar que esta mudança afeta apenas os novos aplicativos do Laravel 8.x. Os aplicativos atualizados do Laravel 7.x ainda terão a propriedade $ namespace em seu RouteServiceProvider.

## Instalação

Se você já utiliza um servidor remoto, seja numa nuvem, VPS ou mesmo em um plano de hospedagem compartilhada, é seu dever checar os requisitos de sistema para a instalação.

    • PHP na versão 7.3 ou superior
    • BCMath PHP Extensão ativa
    • Ctype PHP Extensão ativa
    • Fileinfo PHP Extensão ativa
    • JSON PHP Extensão ativa
    • Mbstring PHP Extensão ativa
    • OpenSSL PHP Extensão ativa
    • PDO PHP Extensão ativa
    • Tokenizer PHP Extensão ativa
    • XML PHP Extensão ativa

Composer

O Laravel utiliza o Composer para gerenciar suas dependências. Portanto, antes de usar o Laravel, certifique-se de ter o Composer instalado em sua máquina.

Caso você não tenha, primeiro, baixe o instalador do Laravel usando o Composer.
composer global require laravel/installer

Certifique-se de colocar o diretório bin do Composer em seu $PATH para que o executável Laravel possa ser localizado por seu sistema. Este diretório existe em diferentes locais com base em seu sistema operacional. No entanto, alguns locais comuns incluem os diretórios a seguir.
    • macOS: $HOME/.composer/vendor/bin
    • Windows: %USERPROFILE%\AppData\Roaming\Composer\vendor\bin
    • GNU / Linux Distributions: $HOME/.config/composer/vendor/bin or $HOME/.composer/vendor/bin

Uma vez instalado, o comando laravel new criará uma nova instalação do Laravel no diretório que você especificar. Por exemplo, “laravel new blog” criará um diretório chamado “blog” contendo uma nova instalação do Laravel com todas as dependências do já instaladas.
laravel new blog

O Laravel 8 agora tambem serve como marco, pois partindo do seu lançamento ele agora segue oficialmente a nomenclatura de versionamento semântico de versões. A cada 6 meses é lançado uma major version. A versão 8 atual terá suporte oficial até Setembro de 2021. Existe muito mais sobre o Laravel 8 e que podem ser acessadas pela página oficial do lançamento. Vale conferir também o GitHub do excelentíssimo Taylor Otwell, criador do Laravel.

Você já utiliza o Laravel? Conte-nos um pouco mais sobre os seus projetos e como esta nova versão poderá otimizar sua produção!

https://www.dialhost.com.br/blog/laravel-8-melhorias-novidades-lancamento/

