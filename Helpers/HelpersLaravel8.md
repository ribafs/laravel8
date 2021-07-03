# How to create Helpers function in Laravel 8 (Global function) 

Good day, today, we are going to create a helper function in laravel 8, this is the same method in creating a helper function in laravel 7. Helper function helps us to create a function that can be called anywhere in our app. That is a global function that can be called both in the views and also in the controller.
Laravel and PHP also provide some basic functions that can be called anywhere, however, there are some times we might need to write our own custom functions that we will need both in the controller and in the views or other parts of our app.

Today, we are going to write a helper function to get the email address of the user that is login into our app. This is a very simple function, just for illustration. 
But the logic can be applied to any other function, provided the function is correct.

## Step 1: create a helper file 

Go to app/ directory and create a file called helpers.php

## Step 2: Write the helper function 

## Step 3: Include it in our composer.json 

we need to include the helpers.php file in our composer.json, so that when we autoload, it will load the file.
in the value of "autoload" key, add "files" as a key, with an array as the value, the array will contain "app/helpers.php".

## Step 4: Regenerate the list of all classes in the app 
```php
composer dump-autoload
```
That is all, we can now call our function anywhere in our app

## In view
```php
 <div>
     @php
        $email = user_email();
     @endphp

        {{ $email }}
 </div>
```
## In controller
```php
  $userEmail = user_email();
```

You can follow me, leave a comment below, suggestion, reaction, or email, WhatsApp or call me, my contacts are on the screenshot. Visit my other posts

https://dev.to/kingsconsult/how-to-create-laravel-8-helpers-function-global-function-d8n?utm_source=digest_mailer&utm_medium=email&utm_campaign=digest_email

