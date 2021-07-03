# O livewire trabalha com requisições AJAX e abstrai o uso do JavaScript

Livewire + Blade

Laravel Livewire é uma biblioteca que simplifica a construção de interfaces modernas, reativas e dinâmicas usando o Laravel Blade como sua linguagem de templates. 
Esta é uma ótima pilha/stack para escolher se você deseja construir um aplicativo que seja dinâmico e reativo, mas não se sente confortável usando uma estrutura JavaScript completa como Vue.js.

Ao usar o Livewire, você pode escolher quais partes do seu aplicativo serão um componente do Livewire, enquanto o restante do seu aplicativo pode ser renderizado como os modelos Blade tradicionais aos quais você está acostumado.

## Laravel 8 Auth Scaffolding using Livewire Jetstream

Laravel Livewire is a library that makes it simple to build modern, reactive, dynamic interfaces using Laravel Blade, Laravel controller and Laravel validation.

Livewire provide way to write your ajax with laravel blade, validation and all, you can use as javascript framework. so let's see bellow step to create auth using laravel 8 livewire.

Livewire provide way to write your ajax with laravel blade, validation and all, you can use as javascript framework. so let's see bellow step to create auth using laravel 8 livewire.

You can scaffold authentication with basic login, register and email verification and optionally team management using the following command(s):
```php
$ php artisan jetstream:install livewire
$ php artisan jetstream:install livewire --teams
```
## Livewire Components

Jetstream uses a variety of Blade components, such as buttons and modals, to power the Livewire stack. If you are using the Livewire stack and you would like to publish these components after installing Jetstream, you may use the vendor:publish Artisan command:
```php
php artisan vendor:publish --tag=jetstream-views
```
Os arquivos ficarão em

resources/views/vendor/jetstream/components

## Livewire + Blade

Livewire is a full-stack framework for Laravel that makes building dynamic interfaces simple, without leaving the comfort of Laravel. 

Laravel Livewire is a library that makes it simple to build modern, reactive, dynamic interfaces using Laravel Blade as your templating language. This is a great stack to choose if you want to build an application that is dynamic and reactive but don't feel comfortable jumping into a full JavaScript framework like Vue.js.

https://jetstream.laravel.com/1.x/introduction.html#livewire-blade

O Livewire renderiza a saída do componente inicial com a página (como um Blade include). Desta forma, é SEO amigável.
Quando ocorre uma interação, o Livewire faz uma solicitação AJAX ao servidor com os dados atualizados.
O servidor renderiza novamente o componente e responde com o novo HTML.
O Livewire então muda o DOM de forma inteligente de acordo com as coisas que mudaram.

https://laravel-livewire.com/docs/2.x/quickstart

