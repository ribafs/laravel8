# Tailwind CSS

Agora, na versão 8 do laravel, as views usam, por default, o CSS do Tailwind

A utility-first CSS framework for rapidly building custom designs.

Tailwind CSS is a highly customizable, low-level CSS framework that gives you all of the building blocks you need to build bespoke designs without any annoying opinionated styles you have to fight to override.

Instead of opinionated predesigned components, Tailwind provides low-level utility classes that let you build completely custom designs without ever leaving your HTML.

Responsive

<div class="justify-start sm:justify-center md:justify-end lg:justify-between xl:justify-around"></div>


Ao invés de criar classes com algumas propriedades, o Tailwind cria classes com uma única propriedade. Ex:

.w-100 {
    width: 100px;
}

.h-100 {
    height: 100px;
}

<div class="w-100 h-100">

Por isiso chamado de Utility-First.

Mas não somos obrigados a ter todas as classes com apenas uma propriedade.

Upgrade tailwindcss

npm install tailwindcss@^1.0 --save-dev

## Paginação

A paginação agora está a cargo do Tailwind CSS framework (https://tailwindcss.com/) para o estilo default

https://blog.codecasts.com.br/conhecendo-css-utility-first-com-tailwind-css-55f81b65f9e4


## Steps to install TailWindCSS

    1. Install TailWind using Npm
    2. Import TailWind CSS to Laravel CSS
    3. Generate TailWind Config File
    4. Add TailWind into Laravel Mix Build Process 
    5. Build Laravel Mix
    6. Examples of Using TailWind CSS

## 1. Install TailWind using npm
I assume that you already have created a Laravel application if not you cal create with laravel install or composer. 
okay so now open you terminal and navigate into your application root directory and execute following command, if you are windows use then open command prompt:
npm install tailwindcss

## Import TailWind CSS to Laravel CSS
Now the tailwindcss has been added to our node_modules and we are ready to use it in our project. if you looked the app.scss file from resource folder you will see that laravel already comes with Bootstrap front end framework configured. 

We can replace bootstrap with tailwind. 

Go ahead and remove following lines from /resources/sass/app.scss file
/// Fonts/ /@import/ url('https://fonts.googleapis.com/css?family=Nunito'); /// Variables/ /@import/ 'variables'; /// Bootstrap/ /@import/ '~bootstrap/scss/bootstrap';

And Replace with following tailwind imports:
```php
@tailwind base; @tailwind components; @tailwind utilities;
```
## Generate TailWind Config File

Now the next step is to generate tailwind config file into root of our Laravel application.

This file is going to be used by laravel mix when we will build scss into css. 

Execute following command to generate tailwind config file:
```php
npx tailwind init
```
After running above running above command if you check into root of your project you will see new file created called tailwind.config.js if you open into your code editor it should have following JS configuration written:
```php
module.exports = { theme: { extend: {} }, variants: {}, plugins: [] }
```
## Add TailWind into Laravel Mix Build Process

Now will have to add tailwind into Laravel mix build process basically will have to tell laravel mix to compile tailwind sass using the tailwind configuration file.

Laravel mix configuration can be located at webpack.mix.js, so go ahead and open webpack.mix.js file:

You should see following default configuration:
```php
const mix = require('laravel-mix'); mix.js('resources/js/app.js', 'public/js') .sass('resources/sass/app.scss', 'public/css');
```
Replace it with following configuration:
```php
const mix = require('laravel-mix'); const tailwindcss = require('tailwindcss'); mix.js('resources/js/app.js', 'public/js') .sass('resources/sass/app.scss', 'public/css') .options({ processCssUrls: false, postCss: [tailwindcss('./tailwind.config.js')], });
```
## Build Laravel Mix

Now that we have are ready to build and compile our application assets go ahead and run following command from your terminal or command prompt:
```php
npm install npm run dev
```
## Examples of Using TailWind CSS

Now we can use tailwind utility class into our project ok to do so I am going to demonstrate example uses of tailwind css on welcome.blade.php

First Let’s import app.css file into welcome.blade.php file:
```php
<link href="{{ asset('css/app.css') }}" rel="stylesheet">
```
Here is the code for login form which I have copied from tailwind components section. 
Add this into body of welcome.blade.php page:
```php
<div class="w-full max-w-xs">
            <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" /for/="username">
                    Username
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" type="text" placeholder="Username">
                </div>
                <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" /for/="password">
                    Password
                </label>
                <input class="shadow appearance-none border border-red-500 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" placeholder="******************">
                <p class="text-red-500 text-xs italic">Please choose a password.</p>
                </div>
                <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="button">
                    Sign In
                </button>
                <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="#">
                    Forgot Password?
                </a>
                </div>
            </form>
            <p class="text-center text-gray-500 text-xs">
                ©2019 iTech Empires. All rights reserved.
            </p>
            </div>
```

If you get any question on tutorial feel free to comment using comment section below. I hope this tutorial helped you on installing tailwind.

Git Repository of Laravel Application with Tailwind Installed: 

https://github.com/yogeshkoli/laraveltailwindcss

https://www.itechempires.com/2019/07/how-to-use-tailwindcss-with-laravel-framework/

