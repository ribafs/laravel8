# Laravel 7.x Livewire Form Submit Example Tutorial

Posted By Mahedi Hasan Category Framework Sub-category Laravel 7.x 
August 6, 2020 

Hello Artisan 

In this tutorial i am going to discuss about laravel livewire form submit example. I will create a simple contact form and then i will submit it and save it into database. To submit contact form i will use laravel livewire package.

This tutorial i am going to show you laravel livewire form example. I’m going to show you about how to create form with laravel livewire. let’s discuss about laravel livewire tutorial example. you will learn how to install laravel livewire to laravel.

In this laravel 7 livewire example, we will create a simple form using laravel livewire. you can use laravel livewire with laravel 6 and laravel 7 version. We will save data without page refreshing using Laravel livewire.

Livewire is a full-stack framework for Laravel that makes building dynamic interfaces simple, without leaving the comfort of Laravel. It is a frontend framework. 
After learning it you will just say "Say hello to Livewire".

In this laravel livewire form example, i will give you very simple example to create a contact form with name and email and i will store that data to database without refresh page and too many lines of code in blade file. we will use only livewire/livewire package to do it.
 
1. Download Laravel App
2. Create Migration and Model
3. Install Livewire package
4. Create livewire component
5. Create route
6. Create Blade View
7. Run Project
 
Preview Form of Livewire Form Submit Example

## Step 1 : Install Laravel 7

Now we have to download Laravel 7 version application using bellow command, So open your terminal OR command prompt and run bellow command:
```html
composer create-project --prefer-dist laravel/laravel blog
```
 
## Step 2 : Create Model

Since we have to submit a form, so we need to create a form. Let's we need a model. Let's make a model for laravel livewire form submit example.
```html
php artisan make:model Contact -m
 
contact_table
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->text('body');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contacts');
    }
}
```
 
Now open Contact model and paste this below code.
```html
App/Contact.php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name', 
        'email', 
        'body',
    ];
}
```
 
Read also: Laravel Livewire | Laravel Livewire CRUD Tutorial
 
## Step 3: Install Livewire

After doing above step now in this step, we will simply install livewire to our laravel 7 application using bellow command:
```html
composer require livewire/livewire
```
 
## Step 4: Create Component

Now in this step we need to create livewire component using their command. so run bellow command to create contact us livewire form component.
```html
php artisan make:livewire contact-form
```
 
Now they created fies on both path:
```html
app/Http/Livewire/ContactForm.php
resources/views/livewire/contact-form.blade.php
``` 
Now update ContactForm as stated below
```html
app/Http/Livewire/ContactForm.php
namespace App\Http\Livewire;

use App\Contact;
use Livewire\Component;

class ContactForm extends Component
{   
        public $name;
    public $email;
    public $body;
  
    public function submit()
    {
        $data = $this->validate([
            'name' => 'required|min:6',
            'email' => 'required|email',
            'body' => 'required',
        ]);
  
        Contact::create($data);
  
        return redirect()->to('/form');
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
```
 
Now update contact-form as stated below
```html
resources/views/livewire/contact-form.blade.php

<div>
   <form wire:submit.prevent="submit">
    <div class="form-group">
        <label for="exampleInputName">Name</label>
        <input type="text" class="form-control" id="exampleInputName" placeholder="Enter name" wire:model="name">
        @error('name') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
  
    <div class="form-group">
        <label for="exampleInputEmail">Email</label>
        <input type="text" class="form-control" id="exampleInputEmail" placeholder="Enter name" wire:model="email">
        @error('email') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
  
    <div class="form-group">
        <label for="exampleInputbody">Body</label>
        <textarea class="form-control" id="exampleInputbody" placeholder="Enter Body" wire:model="body"></textarea>
        @error('body') <span class="text-danger">{{ $message }}</span> @enderror
    </div>
  
    <button type="submit" class="btn btn-primary">Save Contact</button>

</div>
```
 
## Step 5: Create Route

In this step we will create one route for calling our example, so let's add new route to web.php file as bellow:
```php
routes/web.php
Route::get('/form', function () {
    return view('form');
});
```
 
## Step 6: Create View 
In the final step, we will create blade file for call form route. in this file we will use @livewireStyles, @livewireScripts and @livewire('contact-form'). so let's add it.
```php
resources/views/form.blade.php

<!DOCTYPE html>
<html>
<head>
    <title></title>
    @livewireStyles
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
  
<div class="container">
  
   <div class="col-md-6">
      <div class="card" style="margin-top: 100px;">
      <div class="card-header">
        Laravel Livewire Form Example - codechief.org
      </div>
      <div class="card-body">
        @livewire('contact-form')
      </div>
    </div>
   </div>
      
</div>
  
</body>
<script src="{{ asset('js/app.js') }}"></script>
@livewireScripts
</html> 
```
 
Now all are set to go, just open the below url to check laravel livewire form submit example.
 
http://localhost:8000/form
 
Hope it can help you to submit your livewire form.

https://www.codechief.org/article/laravel-7x-livewire-form-submit-example-tutorial

