# Componentes

Ao construir uma stack Jetstream Livewire, uma variedade de componentes Blade (botões, painéis, inputs, modais) foram criados para ajudar na criação de consistência e facilidade de uso da IU. Você é livre para usar ou não esses componentes. No entanto, se desejar usá-los, você deve publicá-los usando o comando:
```php
php artisan vendor:publish --tag=jetstream-views
```
Você pode ter uma ideia de como usar esses componentes revisando seu uso nas views existentes da Jetstream localizadas no diretório 

resources/views/vendor/jetstream/components

Modais

A maioria dos componentes da stack Jetstream Livewire não tem comunicação com seu back-end. No entanto, os componentes modais Livewire incluídos no Jetstream interagem com o back-end Livewire para determinar seu estado open/close. Além disso, o Jetstream inclui dois tipos de modais: modal de diálogo e modal de confirmação. O modal de confirmação pode ser usado ao confirmar ações destrutivas, como exclusões, enquanto o modal de diálogo é uma janela modal mais genérica que pode ser usada a qualquer momento.

Para ilustrar o uso de modais, considere o seguinte modal que confirma que um usuário deseja excluir sua conta:
```php
<x-jet-confirmation-modal wire:model="confirmingUserDeletion">
    <x-slot name="title">
        Delete Account
    </x-slot>

    <x-slot name="content">
        Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted.
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
            Nevermind
        </x-jet-secondary-button>

        <x-jet-danger-button class="ml-2" wire:click="deleteUser" wire:loading.attr="disabled">
            Delete Account
        </x-jet-danger-button>
    </x-slot>
</x-jet-confirmation-modal>
```
Como você pode ver, o estado de open/close do modal é determinado por uma propriedade wire:model que é declarada no componente. Este nome de propriedade deve corresponder a uma propriedade booleana na classe PHP correspondente do componente Livewire. O conteúdo do modal pode ser especificado hidratando três espaços: título, conteúdo e rodapé.

## Componentes de inertia

Componentes

Durante a construção da stack Jetstream Inertia, uma variedade de componentes Vue (botões, painéis, entradas, modais) foram criados para ajudar na criação de consistência e facilidade de uso da UI. Você é livre para usar ou não esses componentes. Todos esses componentes estão localizados no diretório resources/js/jetstream do seu aplicativo.

Você pode ter uma ideia de como usar esses componentes analisando seu uso nas páginas existentes da Jetstream localizadas no diretório resources/js/Pages.

## Form/Validation Helpers

A fim de tornar o trabalho com formulários e erros de validação mais conveniente, um pacote laravel-jetstream NPM foi criado. Este pacote é instalado automaticamente ao usar a pilha Jetstream Inertia.

Este pacote adiciona um novo método de formulário ao objeto $inertia que pode ser acessado por meio de seus componentes Vue. O método de formulário é usado para criar um novo objeto de formulário que fornecerá acesso fácil a mensagens de erro, bem como conveniências, como redefinir o estado do formulário em um envio de formulário bem-sucedido:
```php
data() {
    return {
        form: this.$inertia.form({
            name: this.name,
            email: this.email,
        }, {
            bag: 'updateProfileInformation',
            resetOnSuccess: true,
        }),
    }
}
```
Um formulário pode ser enviado usando os métodos de postagem, colocação ou exclusão. Todos os dados especificados durante a criação do formulário serão incluídos automaticamente na solicitação. Além disso, opções de solicitação de inércia também pode ser especificado:
```php
this.form.post('/user/profile-information', {
    preserveScroll: true
})
```
As mensagens de erro do formulário podem ser acessadas usando o método form.error. Este método retornará a primeira mensagem de erro disponível para o campo fornecido:
```php
<jet-input-error :message="form.error('email')" class="mt-2" />
```
Uma lista simplificada de todos os erros de validação pode ser acessada usando o método errors(). Este método pode ser útil ao tentar exibir a mensagem de erro em uma lista simples:
```php
<li v-for="error in form.errors()">
    {{ error }}
</li>
```
Informações adicionais sobre o estado atual do formulário estão disponíveis por meio dos métodos recentSuccessful e de métodosde processamento. Esses métodos são úteis para ditar estados de IU desativados ou "em andamento":
```php
<jet-action-message :on="form.recentlySuccessful" class="mr-3">
    Saved.
</jet-action-message>

<jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
    Save
</jet-button>
```
Para saber mais sobre como usar os ajudantes do formulário Inertia da Jetstream, você está livre para revisar as páginas Inertia criadas durante a instalação da Jetstream. Essas páginas estão localizadas no diretório resources/js/Pages do seu aplicativo.

## Modals

A stack inertia do Jetstream também inclui dois componentes modais: DialogModal e ConfirmationModal. O ConfirmationModal pode ser usado para confirmar ações destrutivas, como exclusões, enquanto o DialogModal é uma janela modal mais genérica que pode ser usada a qualquer momento.

Para ilustrar o uso de modais, considere o seguinte modal que confirma que um usuário deseja excluir sua conta:
```php
<jet-confirmation-modal :show="confirmingUserDeletion" @close="confirmingUserDeletion = false">
    <template #title>
        Delete Account
    </template>

    <template #content>
        Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted.
    </template>

    <template #footer>
        <jet-secondary-button @click.native="confirmingUserDeletion = false">
            Nevermind
        </jet-secondary-button>

        <jet-danger-button class="ml-2" @click.native="deleteTeam" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            Delete Account
        </jet-danger-button>
    </template>
</jet-confirmation-modal>
```
Como você pode ver, o estado de open/close do modal é determinado por uma propriedade show que é declarada no componente. O conteúdo do modal pode ser especificado hidratando três espaços: título, conteúdo e rodapé.

https://github.com/laravel/jetstream-docs/edit/master/1.x/stacks/inertia.md


## Componentes

Componentes e slots fornecem benefícios semelhantes para seções e layouts; no entanto, alguns podem achar o modelo mental de componentes e slots mais fácil de entender. Existem duas abordagens para escrever componentes: componentes baseados em classe e componentes anônimos.

Para criar um componente baseado em classe, você pode usar o comando make:component do Artisan. Para ilustrar como usar componentes, criaremos um componente de Alerta simples. O comando make:component colocará o componente no diretório App\View\Components:
```php
php artisan make:component Alert
```
O comando make:component também criará um template para a view para o componente. A view será colocada no diretório resources/views/components.

Registro manual de componentes do pacote

Ao escrever componentes para seu próprio aplicativo, os componentes são descobertos automaticamente no diretório app/View/Components e no diretório resources/views/components.

No entanto, se estiver construindo um pacote que utiliza componentes Blade, você precisará registrar manualmente sua classe de componente e seu alias de tag HTML. 
Normalmente, você deve registrar seus componentes no método boot() do provedor de serviços do seu pacote:
```php
use Illuminate\Support\Facades\Blade;

/**
 * Bootstrap your package's services.
 */
public function boot()
{
    Blade::component('package-alert', AlertComponent::class);
}
```
Depois que seu componente for registrado, ele pode ser renderizado usando seu alias de tag:
```php
<x-package-alert/>
```
## Exibindo Componentes

Para exibir um componente, você pode usar uma tag de componente Blade dentro de um de seus modelos Blade. As tags do componente blade começam com a string x- seguida pelo nome do caso kebab da classe do componente:
```php
<x-alert/>

<x-user-profile/>
```
Se a classe do componente estiver mais aninhada no diretório App\View\Components, você pode usar o caractere . para indicar aninhamento de diretório. Por exemplo, se assumirmos que um componente está localizado em App\View\Components\Inputs\Button.php, podemos renderizá-lo assim:
```php
<x-inputs.button/>
```
## Passando informações para componentes

Você pode passar dados para componentes Blade usando atributos HTML. Valores primitivos embutidos em código podem ser passados para o componente usando atributos HTML simples. Expressões e variáveis PHP devem ser passadas ao componente por meio de atributos que usam o caractere : como prefixo
```php
<x-alert type="error" :message="$message"/>
```
Você deve definir os dados necessários do componente em seu construtor de classe. Todas as propriedades públicas em um componente serão disponibilizadas automaticamente para a visualização do componente. Não é necessário passar os dados para a visualização do método de renderização do componente:
```php
<?php
namespace App\View\Components;

use Illuminate\View\Component;

class Alert extends Component
{
    /**
     * The alert type.
     *
     * @var string
     */
    public $type;

    /**
     * The alert message.
     *
     * @var string
     */
    public $message;

    /**
     * Create the component instance.
     *
     * @param  string  $type
     * @param  string  $message
     * @return void
     */
    public function __construct($type, $message)
    {
        $this->type = $type;
        $this->message = $message;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.alert');
    }
}
```
Quando seu componente é renderizado, você pode exibir o conteúdo das variáveis públicas de seu componente, ecoando as variáveis por nome:
```php
<div class="alert alert-{{ $type }}">
    {{ $message }}
</div>
```
Casing/Invólucro

Os argumentos do construtor do componente devem ser especificados usando camelCase, enquanto kebab-case deve ser usado ao fazer referência aos nomes dos argumentos em seus atributos HTML. Por exemplo, dado o seguinte construtor de componente:
```php
/**
 * Create the component instance.
 *
 * @param  string  $alertType
 * @return void
 */
public function __construct($alertType)
{
    $this->alertType = $alertType;
}
```
The $alertType argument may be provided like so:
```php
<x-alert alert-type="danger" />
```
## Métodos de Componente

Além das variáveis públicas disponíveis para o seu template de componente, quaisquer métodos públicos no componente também podem ser executados. Por exemplo, imagine um componente que tem um método isSelected:
```php
/**
 * Determine if the given option is the current selected option.
 *
 * @param  string  $option
 * @return bool
 */
public function isSelected($option)
{
    return $option === $this->selected;
}
```
Você pode executar este método a partir do seu template de componente, invocando a variável que corresponde ao nome do método:
```php
<option {{ $isSelected($value) ? 'selected="selected"' : '' }} value="{{ $value }}">
    {{ $label }}
</option>
```
Usando Atributos e Slots dentro da Classe

Os componentes de blade também permitem que você acesse o nome do componente, os atributos e o slot dentro do método de renderização da classe. No entanto, para acessar esses dados, você deve retornar um Closure do método de renderização do seu componente. O Closure receberá um array $data como seu único argumento:
```php
/**
 * Get the view / contents that represent the component.
 *
 * @return \Illuminate\View\View|\Closure|string
 */
public function render()
{
    return function (array $data) {
        // $data['componentName'];
        // $data['attributes'];
        // $data['slot'];

        return '<div>Component content</div>';
    };
}
```
O componentName é igual ao nome usado na tag HTML após o prefixo x. Portanto, o componentName de <x-alert /> deve ser alert. O elemento de atributos conterá todos os atributos que estavam presentes na tag HTML. O elemento slot é uma instância Illuminate\Support\HtmlString com o conteúdo do slot do componente.

## Dependências Adicionais

Se o seu componente requer dependências do container de serviço do Laravel, você pode listá-los antes de qualquer um dos atributos de dados do componente e eles serão automaticamente injetados pelo container:
```php
use App\Services\AlertCreator

/**
 * Create the component instance.
 *
 * @param  \App\Services\AlertCreator  $creator
 * @param  string  $type
 * @param  string  $message
 * @return void
 */
public function __construct(AlertCreator $creator, $type, $message)
{
    $this->creator = $creator;
    $this->type = $type;
    $this->message = $message;
}
```
## Gerenciando Atributos

Já examinamos como passar atributos de dados para um componente; entretanto, às vezes você pode precisar especificar atributos HTML adicionais, como classe, que não fazem parte dos dados necessários para o funcionamento de um componente. Normalmente, você deseja passar esses atributos adicionais para o elemento raiz do template de componente. Por exemplo, imagine que queremos renderizar um componente de alerta assim:
```php
<x-alert type="error" :message="$message" class="mt-4"/>
```
Todos os atributos que não fazem parte do construtor do componente serão automaticamente adicionados ao "pacote de atributos" do componente. Este pacote de atributos é automaticamente disponibilizado para o componente por meio da variável $attribute. Todos os atributos podem ser renderizados dentro do componente ecoando esta variável:
```php
<div {{ $attributes }}>
    <!-- Component Content -->
</div>
```
O uso de diretivas como @env diretamente em um componente não é suportado no momento.

## Atributos padrão / mesclados

Às vezes, você pode precisar especificar valores padrão para atributos ou mesclar valores adicionais em alguns dos atributos do componente. Para fazer isso, você pode usar o método merge do atributo bag:
```php
<div {{ $attributes->merge(['class' => 'alert alert-'.$type]) }}>
    {{ $message }}
</div>
```
Se assumimos que este componente é utilizado assim:
```php
<x-alert type="error" :message="$message" class="mb-4"/>
```
Finalmente o HTML renderizado do componente irá aparecer como o seguinte:
```php
<div class="alert alert-error mb-4">
    <!-- Contents of the $message variable -->
</div>
```
## Filtragem de Atributos

Você pode filtrar atributos usando o método filter. Este método aceita um Closure que deve retornar verdadeiro se você quiser manter o atributo no pacote de atributos:
```php
{{ $attributes->filter(fn ($value, $key) => $key == 'foo') }}
```
Por conveniência, você pode usar o método whereStartsWith para recuperar todos os atributos cujas chaves começam com uma determinada string:
```php
{{ $attributes->whereStartsWith('wire:model') }}
```
Usando o primeiro método, você pode renderizar o primeiro atributo em um determinado pacote de atributos:
```php
{{ $attributes->whereStartsWith('wire:model')->first() }}
```
## Slots

Freqüentemente, você precisará passar conteúdo adicional para seu componente por meio de "slots". Vamos imaginar que um componente de alerta que criamos tem a seguinte marcação:
```php
<!-- /resources/views/components/alert.blade.php -->

<div class="alert alert-danger">
    {{ $slot }}
</div>
```
Podemos passar conteúdo para o slot injetando conteúdo no componente:
```php
<x-alert>
    <strong>Whoops!</strong> Something went wrong!
</x-alert>
```
Às vezes, um componente pode precisar renderizar vários slots diferentes em locais diferentes dentro do componente. Vamos modificar nosso componente de alerta para permitir a injeção de um "título":
```php
<!-- /resources/views/components/alert.blade.php -->

<span class="alert-title">{{ $title }}</span>

<div class="alert alert-danger">
    {{ $slot }}
</div>
```
Você pode definir o conteúdo do slot nomeado usando a tag x-slot. Qualquer conteúdo que não esteja dentro de uma tag x-slot será passado para o componente na variável $slot:
```php
<x-alert>
    <x-slot name="title">
        Server Error
    </x-slot>

    <strong>Whoops!</strong> Something went wrong!
</x-alert>
```
## Slots com escopo

Se você usou uma estrutura JavaScript como o Vue, pode estar familiarizado com "slots com escopo", que permitem acessar dados ou métodos do componente dentro de seu slot. Você pode obter um comportamento semelhante no Laravel definindo métodos públicos ou propriedades em seu componente e acessando o componente dentro de seu slot através da variável $component:
```php
<x-alert>
    <x-slot name="title">
        {{ $component->formatAlert('Server Error') }}
    </x-slot>

    <strong>Whoops!</strong> Something went wrong!
</x-alert>
```
Views de componentes embutidos

Para componentes muito pequenos, pode parecer complicado gerenciar a classe do componente e o template de view do componente. Por esse motivo, você pode retornar a marcação do componente diretamente do método de renderização:
```php
/**
 * Get the view / contents that represent the component.
 *
 * @return \Illuminate\View\View|\Closure|string
 */
public function render()
{
    return <<<'blade'
        <div class="alert alert-danger">
            {{ $slot }}
        </div>
    blade;
}
```
## Gerando Componentes de View Inline

Para criar um componente que renderiza uma view embutida, você pode usar a opção embutida ao executar o make:component command:
php artisan make:component Alert --inline

## Componentes Anônimos

Semelhante aos componentes embutidos, os componentes anônimos fornecem um mecanismo para gerenciar um componente por meio de um único arquivo. No entanto, os componentes anônimos utilizam um único arquivo de view e não possuem classe associada. Para definir um componente anônimo, você só precisa colocar um template Blade em seu diretório resources/views/components. Por exemplo, supondo que você tenha definido um componente em resources/views/components/alert.blade.php:
```php
<x-alert/>
```
Você pode usar o caractere . para indicar se um componente está aninhado mais profundamente dentro do diretório de componentes. Por exemplo, supondo que o componente seja definido em resources/views/components/inputs/button.blade.php, você pode renderizar ele assim:
```php
<x-inputs.button/>
```
## Propriedades/atributos de dados

Visto que os componentes anônimos não têm nenhuma classe associada, você pode se perguntar como pode diferenciar quais dados devem ser passados para o componente como variáveis e quais atributos devem ser colocados no pacote de atributos do componente.

Você pode especificar quais atributos devem ser considerados variáveis de dados usando a diretiva @props no topo do template Blade do seu componente. Todos os outros atributos do componente estarão disponíveis por meio do pacote de atributos do componente. Se você deseja dar a uma variável de dados um valor padrão, você pode especificar o nome da variável como a chave da matriz/array e o valor padrão como o valor da matriz:
```php
<!-- /resources/views/components/alert.blade.php -->

@props(['type' => 'info', 'message'])

<div {{ $attributes->merge(['class' => 'alert alert-'.$type]) }}>
    {{ $message }}
</div>
```
## Componentes Dinâmicos

Às vezes, você pode precisar renderizar um componente, mas não saber qual componente deve ser renderizado até o tempo de execução. Nesta situação, você pode usar o componente dinâmico embutido no Laravel para renderizar o componente baseado em um valor ou variável de tempo de execução:
```php
<x-dynamic-component :component="$componentName" class="mt-4" />
```
https://laravel.com/docs/8.x/blade#components

