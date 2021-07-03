# Factories no laravel 8

Exemplo

https://www.youtube.com/watch?v=KVLqFKxT4qM&feature=youtu.be
```php
posts: id, title, content

php artisan make:factory Post

database/factories/PostFactory.php

    public function definition()
    {
        return [
            'title' => $this->faker->text(2),
            'content' => $this->faker->text(5)
        ];
    }
```
Podemos usar o seeder para criar os registros
```php
php artisan make:seeder PostSeeder

database/seeders/PostSeeder.php

    public function run()
    {
        User::factory(10)->create();
        Post::factory(10)->create();
    }
```

## Brand new Model factories!

The model factories feature of Laravel has been totally rewritten for Laravel 8, making them incredibly powerful and easier to work with. They are now class based, like some existing community packages.

For each model, you'll have a factory class, e.g. UserFactory, which contains a definition() method which returns an array of attributes, similar to the factories in Laravel 7 and earlier e.g.:
```php
class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'name'  => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'role'  => 'admin',
        ];
    }
}
```
Your models will make use of a new HasFactory trait, which provides a factory()  method on your model classes - which will return an instance of the factory class, on which you can call methods as before such as create() or make() - e.g:
```php
use App\Models\User;

$fake_user = User::factory()->create();
```
You can of course optionally pass an array of attributes that you want to give a specific value to those methods, as you did in previous Laravel versions.

Because the factories are now class based, there is a fluent interface - for example if you wanted to create 10 users:
```php
$fake_users = User::factory()->times(10)->create();
```
There are also improvements to factory states - rather than being closure based, you can now create your own custom methods right on the factory class itself. Being method based you can of course write whatever logic you wish (maybe passing in parameters), but here is a simple example of overriding an attribute:
```php
class UserFactory extends Factory
{
    public function withActiveStatus()
    {
        return $this->state(fn(array $attributes) => [
            'status' => 'active',
        ]);
    }    
}
```
Relationships with model factories have also been greatly improved in Laravel 8. The example below shows us creating a User model that has an Order:
```php
$user_with_order = User::factory()
    ->has(Order::factory())
    ->create();
```
And because everything is fluent, you chain methods together such as:
```php
$user_with_orders = User::factory()
    ->withActiveStatus()
    ->has(
        Order::factory()
            ->times(5)
            ->withStatus('shipped')
            ->state(function(array $attributes, User $user) {
                return ['customer_name' => $user->name];
            })
    )
    ->create();
```
In the example above, we create a User that has 5 Orders that all have a status of "shipped", with the customer_name attribute of the Order set to the name of our newly created fake User.

There are also magic methods available for working with relationships, meaning you can make your code even simpler. Sticking with the theme of the above example, here's an example of creating a User with 3 Orders using a magic method:
```php
$user_with_orders = User::factory()
    ->hasOrders(3)
    ->create();
```
Those examples only cover one way of a "has many" relationship, but there are a whole range of methods (+ magic methods) available for the various other types of  model relationships.

If you are using factories in an existing Laravel project, you won't have to re-write them to the new format them right away - there is a separate official package to include support for the old format called laravel/legacy-factories. The package will also let you use both the old and new factories at the same time.

