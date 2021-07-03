# Laravel 8 Form Validation Example

This article goes in detailed on laravel 8 form validation tutorial. We will look at example of laravel 8 form validation with error message. if you have question about form validation laravel 8 then i will give simple example with solution.

You can also define custom error messages in laravel 8 form validation. we will display error message with each field. we will use has() for checking is error message in laravel 8.

Here, i am going to show you very simple example of form validation so, you can simply use in your laravel 8 project.

## Create Routes:

Here we are learning simple and easy example of validation in laravel 8 so just add following both route in your web.php file.
```php
routes/web.php

<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

Route::get('user/create', [ HomeController::class, 'create' ]);

Route::post('user/create', [ HomeController::class, 'store' ]);
```

## Create Controller:

Now we will add two controller method, one will just display blade file with get request, and another for post request, i write validation for that, so simply add both following method on it.
```php
app/Http/Controllers/HomeController.php

<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('createUser');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
                'name' => 'required',
                'password' => 'required|min:5',
                'email' => 'required|email|unique:users'
            ], [
                'name.required' => 'Name is required',
                'password.required' => 'Password is required'
            ]);

        $validatedData['password'] = bcrypt($validatedData['password']);
        $user = User::create($validatedData);

        return back()->with('success', 'User created successfully.');
    }
}
```
## Create Blade File:

now here we will create createUser.blade.php file and here we will create bootstrap simple form with error validation message. So, let's create following file:

resources/views/createUser.blade.php

Read Also: Laravel 8 CRUD Application Tutorial for Beginners
```php
<!DOCTYPE html>
<html>
<head>
    <title>Laravel 8 form validation example - ItSolutionStuff.com</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Laravel 8 Form Validation Example - ItSolutionStuff.com</h1>

        @if(Session::has('success'))
        <div class="alert alert-success">
            {{ Session::get('success') }}
            @php
                Session::forget('success');
            @endphp
        </div>
        @endif
        <form method="POST" action="{{ url('user/create') }}">
            {{ csrf_field() }}
            <div class="form-group">
                <label>Name:</label>
                <input type="text" name="name" class="form-control" placeholder="Name">
                @if ($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
            </div>
            <div class="form-group">
                <label>Password:</label>
                <input type="password" name="password" class="form-control" placeholder="Password">
                @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
            </div>
            <div class="form-group">
                <strong>Email:</strong>
                <input type="text" name="email" class="form-control" placeholder="Email">
                @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
            </div>

            <div class="form-group">
                <button class="btn btn-success btn-submit">Submit</button>
            </div>
        </form>
    </div>
</body>
</html>
```
Now we can run and check full example.

I hope it can help you...

https://www.itsolutionstuff.com/post/laravel-8-form-validation-exampleexample.html

