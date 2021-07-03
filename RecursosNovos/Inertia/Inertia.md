# Inertia

The Inertia.js stack provided by Jetstream uses Vue.js as its templating language. Building an Inertia application is a lot like building a typical Vue application; however, you will use Laravel's router instead of Vue router. Inertia is a small library that allows you to render single-file Vue components from your Laravel backend by providing the name of the component and the data that should be hydrated into that component's "props".

Inertia.js + Vue

The Inertia.js stack provided by Jetstream uses Vue.js

As its templating language. Building an Inertia application is a lot like building a typical Vue application; however, you will use Laravel's router instead of Vue router. Inertia is a small library that allows you to render single-file Vue components from your Laravel backend by providing the name of the component and the data that should be hydrated into that component's "props".

In other words, this stack gives you the full power of Vue.js without the complexity of client-side routing. You get to use the standard Laravel router that you are used to.

The Inertia stack is a great choice if you are comfortable with and enjoy using Vue.js as your templating language.

Instalando o inertia com teams

composer require inertiajs/inertia-laravel

php artisan jetstream:install inertia --teams (mudará em config/jetstream.php para inertia como default)

npm install && npm run dev

Inertia.js is a stack provided by Jetstream that uses Vue.js as its templating language,

You can create auth scaffolding with Jetstream Inertia.js using the following command(s):

$ php artisan jetstream:install inertia

$ php artisan jetstream:install inertia --teams

Next, install and build your frontend dependencies as follows:

$ npm install

$ npm run dev


