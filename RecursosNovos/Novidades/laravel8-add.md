# Novidades da versão 8.10

## Add is() Method to Model Relations

Sébastien Nikolaou contributed the is() and isNot() methods to Eloquent relations without an extra query:
```php
// Before: performs extra query to fetch the user model from the author relation
$post->author->is($user);
$post->author->isNot($user);

// After
$post->author()->is($user);
$post->author()->isNot($user);
```

## A newline method for console IO

Scott Carpenter contributed a newLine() method you can use to output a blank line in Artisan console output:
```php
// You could already do the following
$this->getOutput()->newLine();

// This feature adds a shortcut
$this->newLine();

// You can specify the number of blank lines
$this->newLine(5);
```

## A canAny() Method for Authorizable Models

Denis Duliçi contributed a canAny method to the Authorizable trait that the User model supports. This allows the following (similar to the blade feature with the same name):
```php
$user->canAny(['permission-one', 'permission-two'])
```
https://laravel-news.com/laravel-8-10-0
