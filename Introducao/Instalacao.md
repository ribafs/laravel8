# Instalação do Laravel 8

## Requisitos
Veja aqui

https://laravel.com/docs/8.x

## Instalar ou atualizar o laravel installer no Ubuntu e derivadas

composer global require laravel/installer

export PATH="~/.config/composer/vendor/bin:$PATH"

laravel -v

Versão 4 ou superior

## Instalar o laravel limpo

laravel new blog

## Instalar o scaffold do laravel/ui com bootstrap

composer require laravel/ui

php artisan ui bootstrap --auth

npm install && npm run dev

## Instalar o scaffold com JetStream

composer require laravel/jetstream

// Install Jetstream with the Livewire stack...
php artisan jetstream:install livewire

ou

// Install Jetstream with the Inertia stack...
php artisan jetstream:install inertia

ou

## Instalar com JetStream e cia

laravel new blog --jet --teams --stack=livewire

A minha preferência até o momento é com laravel/ui.
