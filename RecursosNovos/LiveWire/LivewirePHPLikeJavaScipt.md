# Laravel 7.x Livewire Example - Run PHP Code Like JavaScipt

Posted By Mahedi Hasan Category Framework Sub-category Laravel 6
February 5, 2020

Hey Artisan

In this tutorial, i am going to discuss about laravel livewire. In this laravel liveware tutorial, i will show you that how can run php laravel code without page refreshing.

laravel livewire framework is an amazing package. Livewire is a full-stack framework for Laravel that makes building dynamic front-ends as simple as writing vanilla PHP (literally).

It's not like anything you've seen before, the best way to understand it is just to look at the code. To see the example, let's start our tutorial.

laravel-livewire-tutorial

    Set up Livewire (docs)

To setup livewire in your project, run below command
```php
composer require calebporzio/livewire
```

Include it on all pages you’d like to use, before the closing body tag:

```php
@livewireAssets 

</body> 
</html>
```

Let's consider the following "counter" component written in VueJs:
```php
<script>
    export default {
        data: {
            count: 0
        },
        methods: {
            increment() {
                this.count++
            },
            decrement() {
                this.count--
            },
        },
    }
</script>

<template>
    <div>
        <button @click="increment">+</button>
        <button @click="decrement">-</button>

        <span>{{ count }}</span>
    </div>
</template> 
```

Now, let's see how we would accomplish the exact same thing with a Livewire component.
```php
app/Http/Livewire/Counter.php

use Livewire\Component;

class Counter extends Component
{
    public $count = 0;

    public function increment()
    {
        $this->count++;
    }

    public function decrement()
    {
        $this->count--;
    }

    public function render()
    {
        return view('livewire.counter');
    }
}
```

resources/views/livewire/counter.blade.php

```php
<div>
    <button wire:click="increment">+</button>
    <button wire:click="decrement">-</button>

    <span>{{ $this->count }}</span>
</div> 
```
 

Check it now, you will see it works same like vue component works before. 

How livewire works ? 

Livewire works nearly the same like vue js, but with 2 extra steps behind the scenes.
```php
    Livewire hears the click because of wire:click="increment"
    Livewire sends an ajax request to PHP
    PHP calls the increment method, which updates $count
    PHP re-renders the Blade template and sends back the HTML
    Livewire receives the response, and updates the DOM
```
Cool huh? 
Can I replace Vue components with Livewire components now?

Not exactly. A good rule of thumb is: any JavaScript components that rely on ajax for server communication, will be better off as Livewire components.

There's lots of other good use cases, but this gives you a basic idea of where to start.

https://www.codechief.org/article/laravel-livewire-run-your-php-code-like-javascipt

