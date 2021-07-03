# Liviwire Events

Os eventos livewire do Laravel. Você já sabe que os componentes do Livewire podem se comunicar uns com os outros por meio de um sistema de eventos global. Contanto que dois componentes Livewire estejam na mesma página, eles podem se comunicar usando eventos e ouvintes.

O Livewire facilita o envio de dados do cliente para o servidor e do servidor de volta para o cliente. Neste tutorial de exemplo rápido, mostrarei como é fácil enviar e receber dados entre cliente e servidor.

## Complete Beginners Guide on Laravel Livewire Events

Posted By Mahedi Hasan Category Framework Sub-category Laravel 8.x 
October 24, 2020 

Hello devs in this tutorial i am going to discuss about Laravel livewire events. You already know that Livewire components can communicate with each other through a global event system. As long as two Livewire components are living on the same page, they can communicate using events and listeners.
If you already worked with vue events then livewire events is going to be almost same thing i think. If you use larave livewire already then you know about that Laravel Livewire simplifies so many aspects of building out your Laravel application.

Livewire makes it easy to send data from the client to the server and from the server back to the client.In this quick example tutorial, I'll show you how easy it is to send data back and forth from the client and the server.

## Client To The Server with Livewire

In laravel livewire sending events from the client to the server can easily be accomplished with a wire:click event like

 <button wire:click="functionName">Click Me</button> 
 
This is covered in the Livewire Actions Documentation, but what if we wanted to call a PHP function from vanilla javascript? Simple enough, we can utilize Livewire 
Events to do that like so:
```html
<script>
    window.livewire.emit('say-hello');
</script> 
```
 
After doing that steps, inside of our PHP code, you'll need to register an event listener that maps to a function:
```html
protected $listeners = ['say-hello' => 'sayHello'];

public function sayHello()
{
    // your code here
}
```
 
That's the simplest and very easy way for your front-end to talk to your back-end. Next, we'll see how we can send an event from the back-end to the front-end in this tutorial with laravel livewire events.

## Server to the Client with Livewire

Sending events from the server to the client can be done by utilizing the dispatchBrowserEvent function in laravel livewire. See the example like so:
```html
public function sayGoodbye()
{
    $this->dispatchBrowserEvent('say-goodbye', []);
}
```
 
Then, we can register an even listener in javascript to catch this event like below
```html
<script>
window.addEventListener('say-goodbye', event => {
    alert('Radical!');
});
</script> 
```
 
And that's it 🙌 Finally, you may like to know how to send data between the sever to clinet and client to server.

## Sending Data to the Server

In our previous code example, we can easily pass data to the server with the following javascript code:
```html
<script>
    window.livewire.emit('say-hello', { name : 'John' );
</script> 
```
 
Read also : Laravel Livewire Dynamically Add More Input Fields Example
 
And we can access that data from the first argument in our function:
```html
protected $listeners = ['say-hello' => 'sayHello'];

public function sayHello($payload)
{
    $name = $payload['name'];
    // your code here
}
```
 
That's great, right? Next step, we'll also need a way to pass data from our server to our client.

## Sending Data to the Client

We already seen before that we can easily send data from our PHP code to our front-end by sending it in the array of our dispatchBrowserEvent function:
```html
public function sayGoodbye()
{
    $this->dispatchBrowserEvent('say-goodbye', ['name' => 'John']);
}
```
 
And we can capture that data in javascript by fetching the event.detail variable.
```html
<script>
window.addEventListener('say-goodbye', event => {
    alert( event.detail.name );
});
</script> 
```
 
Read also : Laravel 8.x Livewire CRUD Tutorial Step by Step
 
Hope it can help you to pass data from client to server and server to client. Now you know how to pass data client to server and server to clinet using Laravel livewire.

https://www.codechief.org/article/complete-beginners-guide-on-laravel-livewire-events?ref=laravelnews

