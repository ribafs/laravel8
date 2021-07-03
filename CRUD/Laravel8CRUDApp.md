# Laravel 8 CRUD App, A simple guide 

Laravel is a PHP-based web framework with an expressive, elegant syntax. It is termed the framework for web artisan. The foundation has already laid the foundation, freeing the web artisan to create without sweating the small things. Laravel is model after the MVC (Model View Controller) architecture and revolves around CRUD (Create, Retrieve, Update, Delete) operations.

Laravel has a large and growing community which makes it easy for new developers to find solutions and help to challenges. Laravel releases a new version twice in a year, and the latest version which is Laravel 8 was released on the 8th Sept 2020. 

This article will work you through on creating your first CRUD app with Laravel 8. So if you are new to Laravel, this article will help you to create, insert, update and delete a model from the database.

Click on my profile to follow me to get more updates.

There some couple of things you need to have on your system before installing laravel 8
    • PHP 
    • Composer 
    • Server (WAMP, LAMP, XAMPP etc) I am using windows, so I use WAMP, we are going to be using MySQL as our database for storing our data. Make sure the above-listed packages are installed on your system. 

## Step 1: Install Laravel 8 

To install Laravel 8, we will need a composer and we make sure we specify the version of Laravel we need, in our case Laravel 8.

Composer create-project laravel/laravel=8.0 projectapp --prefer-dist

You can choose to use Laravel options for installing Laravel, but I prefer the first method because it will install all the packages and I also have the liberty of choosing the version I want

Laravel new projectapp

With Laravel 8, some couple of things have been set up, we don’t need to copy and rename the env.example file, Laravel 8 does that automatically for us

Another important thing about Laravel 8, you don’t need to generate APP_KEY, this new version will also generate it for us.

With that all set up, our app is ready.

## Step 2: Database setup 
    • Open your server, in my case WAMP server, and open phpmyadmin, thereafter, sign into MySQL database, create an empty database by clicking new on the left pane  
    • Open the .env file on your IDE or text editor  Change the DB_DATABASE to the name of your database and if you have set a Username and password for your phpmyadmin, specify it, otherwise, leave the username as root and password blank. 

## Step 3: Create Migration 

we are going to create crud application for projects, in the long run, this will be a project management app, I will be writing more articles on Laravel 8, this will be a series, for the main time, lets just stop at creating a crud for projects.

First cd into the project app cd projectapp/
```php
php artisan make:migration create_projects_table --create=projects
```
A migration file will be created in the database/migrations folder, and we need to create our schema, I added name (string), introduction (string), location (string), cost of the project (float), date created, and date updated.

Before we run our migration command, we need to specify the default string length, else, we are going to run into errors

So go to app/Providers/AppServiceProvider.php and add 

## Schema::defaultstringLength(191);

to the boot function, also add 

## use Illuminate\Support\Facades\Schema;

to the top

Finally, we run our migration command
```php
php artisan migrate
```
## Step 4: Add Resource Route 

We need to add routes for our CRUD operations, Laravel provides a resource route for us that will take care of the CRUD, that is a route to Create, another route to Retrieve, a separate route to Update, and finally a route to Delete.
So head up to routes\web.php and add our resource route
```php
Route::resource(‘projects’, ProjectController::class);
```
Also, add the ProjectController class at the top, this was introduced in this version, Laravel 8 does not know where to call the function from

use App\Http\Controllers\ProjectController;

## Step 5: Add Controller and Model 

Previously, in step 4, the second parameter for the resource is ProjectController, so we need to create the controller and we need to specify the resource model by add --model
```php
php artisan make:controller ProjectController --resource --model=Project
```
It will ask a question if you want to create the model because it does not exist. Type yes and it will create the model and controller

A controller for project has been created for us with the following methods in the controller folder app/Http/Controllers/ProjectController.php
    1. index() 
    2. create() 
    3. store(Request, $request) 
    4. show(Project, $project) 
    5. edit(Project, $project) 
    6. update(Request, $request, Project, $project) 
    7. destroy( Project, $project) 

I wrote the codes for the different methods, just copy and paste them
```php
<?php
namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $projects = Project::latest()->paginate(5);

        return view('projects.index', compact('projects'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'introduction' => 'required',
            'location' => 'required',
            'cost' => 'required'
        ]);

        Project::create($request->all());

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required',
            'introduction' => 'required',
            'location' => 'required',
            'cost' => 'required'
        ]);
        $project->update($request->all());

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully');
    }
}
```
Laravel 8 has created a folder called Models, which is not available in the previous version, so our project model is found in the app/Models/Project.php, add the following functions and the fillable, the fillable are the fields in the database that a user can fill.
```php
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';
    public $timestamps = true;

    protected $casts = [
        'cost' => 'float'
    ];

    protected $fillable = [
        'name',
        'introduction',
        'created_at',
        'location',
        'cost'
    ];
}
```
## Step 6: Add your views 

Laravel view files are is called the blade files, we are going to add those blade files, so a user can be able to interact with our app

I like arranging my views according to the models, so I am going to create two folders in the resources/views folder
    1. Layouts 
        ◦ app.blade.php 
    2. Projects 
        ◦ Index.blade.php 
        ◦ Create.blade.php 
        ◦ Edit.blade.php 
        ◦ show.blade.php 

## app.blade.php
```php
<html>

<head>
    <title>App Name - @yield('title')</title>

    <!-- Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/css/bootstrap.css" rel="stylesheet">

    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js"
        integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous">
    </script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js"integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous">
    </script>

    <style>
        .footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            background-color: #9C27B0;
            color: white;
            text-align: center;
        }

    </style>

</head>

<body>
    @section('sidebar')

    @show

    <div class="container">
        @yield('content')
    </div>
    <div class="text-center footer">

        <h4>The writer needs a job</h4>
        <h4>+234 806 605 6233</h4>
        <h4>kingsconsult001@gmail.com</h4>

    </div>
</body>

</html>
```

index.blade.php

```php
@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Laravel 8 CRUD </h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('projects.create') }}" title="Create a project"> <i class="fas fa-plus-circle"></i>
                    </a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered table-responsive-lg">
        <tr>
            <th>No</th>
            <th>Name</th>
            <th>Introduction</th>
            <th>Location</th>
            <th>Cost</th>
            <th>Date Created</th>
            <th width="280px">Action</th>
        </tr>
        @foreach ($projects as $project)
            <tr>
                <td>{{ ++$i }}</td>
                <td>{{ $project->name }}</td>
                <td>{{ $project->introduction }}</td>
                <td>{{ $project->location }}</td>
                <td>{{ $project->cost }}</td>
                <td>{{ date_format($project->created_at, 'jS M Y') }}</td>
                <td>
                    <form action="{{ route('projects.destroy', $project->id) }}" method="POST">

                        <a href="{{ route('projects.show', $project->id) }}" title="show">
                            <i class="fas fa-eye text-success  fa-lg"></i>
                        </a>

                        <a href="{{ route('projects.edit', $project->id) }}">
                            <i class="fas fa-edit  fa-lg"></i>

                        </a>

                        @csrf
                        @method('DELETE')

                        <button type="submit" title="delete" style="border: none; background-color:transparent;">
                            <i class="fas fa-trash fa-lg text-danger"></i>

                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>

    {!! $projects->links() !!}

@endsection
```

create.blade.php
```php
@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Add New Product</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('projects.index') }}" title="Go back"> <i class="fas fa-backward "></i> </a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form action="{{ route('projects.store') }}" method="POST" >
        @csrf

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" class="form-control" placeholder="Name">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Introduction:</strong>
                    <textarea class="form-control" style="height:50px" name="introduction"
                        placeholder="Introduction"></textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Location:</strong>
                    <input type="text" name="location" class="form-control" placeholder="Location">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Cost:</strong>
                    <input type="number" name="cost" class="form-control" placeholder="Cost">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

    </form>
@endsection
```

edit.blade.php
```php
@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Product</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('projects.index') }}" title="Go back"> <i class="fas fa-backward "></i> </a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('projects.update', $project->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" value="{{ $project->name }}" class="form-control" placeholder="Name">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Introduction:</strong>
                    <textarea class="form-control" style="height:50px" name="introduction"
                        placeholder="Introduction">{{ $project->introduction }}</textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Location:</strong>
                    <input type="text" name="location" class="form-control" placeholder="{{ $project->location }}"
                        value="{{ $project->location }}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Cost:</strong>
                    <input type="number" name="cost" class="form-control" placeholder="{{ $project->cost }}"
                        value="{{ $project->location }}">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

    </form>
@endsection
```


show.blade.php
```php
@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>  {{ $project->name }}</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('projects.index') }}" title="Go back"> <i class="fas fa-backward "></i> </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Name:</strong>
                {{ $project->name }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Introduction:</strong>
                {{ $project->introduction }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Location:</strong>
                {{ $project->location }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Cost:</strong>
                {{ $project->cost }}
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="form-group">
                <strong>Date Created:</strong>
                {{ date_format($project->created_at, 'jS M Y') }}
            </div>
        </div>
    </div>
@endsection
```

## Everything is now set
```php
php artisan serve

http://127.0.0.1:8000/projects/

http://127.0.0.1:8000/projects/create

http://127.0.0.1:8000/projects/1/edit

http://127.0.0.1:8000/projects/1
```

Get the full code at

https://github.com/Kingsconsult/laravel_8_crud

https://dev.to/kingsconsult/laravel-8-crud-bi9

