# Criar um CRUD no frontend do Laravel 8 com Livewire

## Instalar o laravel 8 limpo
```bash
laravel new livewire

cd livewire
```
## Instalar o livewire
```bash
composer require livewire/livewire
```

### Editar a view welcome.blade.php, limpar deixando assim:
```blade
<!DOCTYPE html>
<html lang="pt-br">
    <head>

        @livewireStyles

    </head>'
    <body>


        @livewireScripts
    </body>
</html>
```

## Criar um componente chamado crud
```bash
php artisan make:livewire crud
```
Criou o componente em
```bash
app/Http/Livewire/Crud.php
```
Assim:
```php
<?php
namespace App\Http\Livewire;

use Livewire\Component;

class Crud extends Component
{
    // Este é o método construtor do componente
    public function render()
    {
        return view('livewire.crud');
    }
}
```
E também criou a view em
```bash
resources/views/livewire/crud.blade.php

<div>
    {{-- Stop trying to control. --}}
</div>
```

### Incluir o componente na view welcome
```blade
<!DOCTYPE html>
    <head>
        @livewireStyles
    </head>
    <body>
        <livewire:crud />

        @livewireScripts
    </body>
</html>
```

### Alerta

Assim é para quem usa a versão 7 ou acima.

Para quem está usando uma versão 6 ou abaixo, deve usar
```blade
@livewire('crud')

ao invés de:
<livewire:crud />
```
Vamos adicionar propriedades e métodos ao componente que poderão ser vistos na view crud em resources/livewire e a view crud está incluida na welcome.
```blade
<?php
namespace App\Http\Livewire;

use Livewire\Component;

class Crud extends Component
{
    public $item;
    public $list = [];

    // Este é o método construtor do componente
    public function render()
    {
        return view('livewire.crud');
    }
}
```
Podemos passar informações assim:
```blade
        return view('livewire.crud', [ 'name' => 'Ribamar FS']);
```
E receber assim em:
```blade
resources/views/livewire/crud.blade.php

<div>
    <h1>Olá {{ $name }}</h1>
</div>
```
As propriedades públicas do componente são vistas diretamente na view.

Perceba que as informações estão no componente, que são recebidas pela view do livewire e a view no livewire está incluída na welcome.

Faça o download dos dois arquivos, componente e view e copie para os respectivos diretórios para ver o resultado final.


## Crédito e um passo a passo

https://www.youtube.com/watch?v=NrEDKe03vkc&list=PL7ScB28KYHhHVse4Xj4lY_t033WWmlxpI


