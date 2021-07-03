# Criação manual de pacotes para o Laravel

## Instalação do laravel

laravel new my_app

cd my_app

## Criar a estrutura de pastas

packages/ribafs/calculator/src

Após publicar nosso pacote então poderemos colocar na pasta vendor

## Criar o composer.json com

cd packages/ribafs/calculator/src

## Executar e responder as perguntas

composer init

Ao final terá algo como:
```json
{
    "name": "ribafs/calculator",
    "description": "Este pacote efeftua soma e subtração",
    "authors": [
        {
            "name": "Ribamar FS",
            "email": "ribafs@gmail.com"
        }
    ],
    "minimum-stability": "dev",
    "require": {}
}
```
Agora nosso pacote foi publicado e poderemos então dizer ao composer.json da aplicação para carregar este.

## Carregar nosso pacote do nosso laravel aplicativo

No raiz do aplicativo existe o composer.json.

Vamos prosseguir e carregar automaticamente nosso pacote recém-criado por meio do bloco de carregamento automático do PSR-4

A seção autoload está assim:
```json
    "autoload": {
        "psr-4": {
            "App\\": "app/"
        },
        "classmap": [
            "database/seeds",
            "database/factories"
        ]
    },
```
Apenas adicione nosso pacote assim:
```json
    "autoload": {
            "App\\": "app/",
            "Database\\Factories\\": "database/factories/",
            "Database\\Seeders\\": "database/seeders/",
            "Ribafsf\\Calculator\\": "packages/ribafs/calculator/src"
    },
```
## Salve e execute no raiz do aplicativo:

composer dump-autoload

O service provider é a principal entrada para nosso pacote. É nele que nosso pacote é carregado ou inicializado. Na raiz do nosso aplicativo, vamos criar nosso 
ServiceProvider com um comando artisan por meio da linha de comando:

php artisan make:provider CalculatorServiceProvider

Este comando irá criar

app/Providers/CalculatorServiceProvider.php

Como você pode ver em nossa nova classe de service provider, temos 2 métodos boot() e register(). O método boot() é usado para inicializar qualquer rota, event listener ou qualquer outra funcionalidade que você deseja adicionar ao seu pacote. O método register() é usado para vincular qualquer classe ou funcionalidade ao contêiner do aplicativo.

## Agora precisamos adiciionar nosso Service Provider ao config/app.php do raiz, dentro do array providers[]

App\Providers\CalculatorServiceProvider::class,

## Adicionar a rota

cd packages/ribafs/calculator/src

Criar

routes.php
```json
<?php
Route::get('calculator', function(){
        echo 'Olá do pacote calculator!';
});
```
Adicionar a rota ao método boot() do nosso ServiceProvider
```json
public function boot()
{
    include __DIR__.'/routes.php';
}
```
## Testando

php artisan serve

http://localhost:8000/calculator

## Original em inglês com mais informações em

https://devdojo.com/devdojo/how-to-create-a-laravel-package 

Agora podemos hospedar nosso pacote no GitHub e registrar no Packagist para que outros possam usar com facilidade.

## Referẽncias

- https://imasters.com.br/desenvolvimento/como-criar-um-pacote-para-o-laravel
- https://medium.com/@mateusgalasso/desenvolvendo-pacote-para-laravel-39b616808cf6
- https://devdojo.com/devdojo/how-to-create-a-laravel-package
- https://medium.com/cafe24-ph-blog/build-your-own-laravel-package-in-10-minutes-using-composer-867e8ef875dd
- https://www.csrhymes.com/2019/08/10/creating-your-first-laravel-package.html
- https://laravel-news.com/developing-laravel-packages-with-local-composer-dependencies
- https://laravel-zero.com/docs/introduction - an unofficial and customized version of Laravel optimized for building command-line applications.
