Laravel 8 Ajax CRUD Tutorial with Bootstrap 4 Modal and Pagination Example
    • Author: Techiediaries Team
    • 
    • 
In this tutorial, we'll learn to build a CRUD example with Laravel 8, Bootstrap 4, jQuery, and Ajax.
We'll see by example how to perform Ajax CRUD operations in Laravel 8 with a bootstrap modal, datatable and pagination.
Using Ajax with Laravel 8 for CRUD Operations
We'll be using the jQuery ajax() method for sending Ajax requests.
We'll be using yajra datatable for creating a datatable.
    • Step 1 - Installing Laravel 8
    • Step 2 - Installing Yajra Datatable
    • Step 3 - Configuring a MySQL Database
    • Step 4 - Creating a Laravel 8 Migration
    • Step 5 - Adding a Laravel 8 Route
    • Step 6 - Adding a Laravel 8 Controller and Model
    • Step 7 - Adding a Blade Template View
    • Step 8 - Serving the Laravel 8 Application
Step 1 - Installing Laravel 8
Let's get started by installing Laravel 8 in our development machine.
Head to a new command-line interface and run the following command:
$ composer create-project --prefer-dist laravel/laravel ajax-crud-example
Step 2 - Installing Yajra Datatable
Next, let's install the yajra datatable package in our Laravel 8 project using following command:
$ composer require yajra/laravel-datatables-oracle
Next, you need to add it to the providers and aliases arrays:

'providers'  =>  [
    Yajra\DataTables\DataTablesServiceProvider::class,
]

'aliases'  =>  [

    'DataTables'  =>  Yajra\DataTables\Facades\DataTables::class,

]
Step 3 - Configuring a MySQL Database
Next, let's configure a MySQL database for our Laravel 7 project. Make sure you have created a database then go to the .env file and add the information for connecting to your database:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mydb
DB_USERNAME= root
DB_PASSWORD= root
Step 4 - Creating a Laravel 8 Migration
Let's now create a migration file for a customers table. Head back to your terminal and run the following command:
$ php artisan make:migration create_customers_table --create=customers
Next, open the migration file in the database/migrations folder update it as follows to create a customers database table:
<?php

use  Illuminate\Support\Facades\Schema;
use  Illuminate\Database\Schema\Blueprint;
use  Illuminate\Database\Migrations\Migration;

class  CreateCustomersTable  extends  Migration{

/**

* Run the migrations.

*

* @return void

*/

public  function up(){

    Schema::create('customers',  function  (Blueprint $table)  {
        $table->bigIncrements('id');
        $table->string('firstName');
        $table->string('lastName');
        $table->text('info');
        $table->timestamps();
    });

}

/**

* Reverse the migrations.

*

* @return void

*/

public  function down(){
    Schema::dropIfExists('customers');

}
}
Next, you can create the table in the database by running the following command:
$ php artisan migrate
Step 5 - Adding a Laravel 8 Route
Let's now create a Laravel 8 route for accessing the view.
Go to the routes/web.php file and add following resource route:
Route::resource('customers','CustomerController');
Step 6 - Adding a Laravel 8 Controller and Model
Head back to your terminal and run the following command to generate a controller:
$ php artisan controller:make CustomerController
Next, open the app/Http/Controllers/CustomerController.php file and update it as follows:
<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use DataTables;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $data = Customer::latest()->get();
            return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row){

                           $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editCustomer">Edit</a>';

                           $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteCustomer">Delete</a>';

                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }

        return view('CustomerAjax');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Customer::updateOrCreate(['id' => $request->Customer_id],
                ['firstName' => $request->firstName, 'info' => $request->info]);        

        return response()->json(['success'=>'Customer saved successfully!']);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $Customer
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Customer = Customer::find($id);
        return response()->json($Customer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $Customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Customer::find($id)->delete();

        return response()->json(['success'=>'Customer deleted!']);
    }
}
Generating a Laravel 8 Database Model
Next, let's generate a Customer database model using the following command:
$ php artisan make:model Customer
Next, open the app/Customer.php file and update it as follows:
<?php

namespace  App;

use  Illuminate\Database\Eloquent\Model;

class  Customer  extends  Model{

    protected $fillable =  [

        'firstName', 'lastName', 'info'

    ];

}
Step 7 - Adding a Blade Template View
Next, inside the resources/views/ folder, create customer.blade.php file and update it as follows:
<!DOCTYPE html>
<html>
<head>
    <title>Laravel 6 Ajax CRUD Example</title>
    <meta name="csrf-token" content="">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
</head>
<body>

<div class="container">
    <h1>Laravel 6 Ajax CRUD </h1>
    <a class="btn btn-success" href="javascript:void(0)" id="createNewCustomer"> Create New Customer</a>
    <table class="table table-bordered data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Name</th>
                <th>Details</th>
                <th width="280px">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>

<div class="modal fade" id="ajaxModel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modelHeading"></h4>
            </div>
            <div class="modal-body">
                <form id="CustomerForm" name="CustomerForm" class="form-horizontal">
                   <input type="hidden" name="Customer_id" id="Customer_id">
                    <div class="form-group">
                        <label for="name" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name" value="" maxlength="50" required="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label">Details</label>
                        <div class="col-sm-12">
                            <textarea id="detail" name="detail" required="" placeholder="Enter Details" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="col-sm-offset-2 col-sm-10">
                     <button type="submit" class="btn btn-primary" id="saveBtn" value="create">Save changes
                     </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>

<script type="text/javascript">
  $(function () {

      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });

    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'firstName', name: 'firstName'},
            {data: 'lastName', name: 'lastName'},
            {data: 'info', name: 'info'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
    });

    $('#createNewCustomer').click(function () {
        $('#saveBtn').val("create-Customer");
        $('#Customer_id').val('');
        $('#CustomerForm').trigger("reset");
        $('#modelHeading').html("Create New Customer");
        $('#ajaxModel').modal('show');
    });

    $('body').on('click', '.editCustomer', function () {
      var Customer_id = $(this).data('id');
      $.get("" +'/' + Customer_id +'/edit', function (data) {
          $('#modelHeading').html("Edit Customer");
          $('#saveBtn').val("edit-user");
          $('#ajaxModel').modal('show');
          $('#Customer_id').val(data.id);
          $('#name').val(data.name);
          $('#detail').val(data.detail);
      })
   });

    $('#saveBtn').click(function (e) {
        e.preventDefault();
        $(this).html('Sending..');

        $.ajax({
          data: $('#CustomerForm').serialize(),
          url: "",
          type: "POST",
          dataType: 'json',
          success: function (data) {

              $('#CustomerForm').trigger("reset");
              $('#ajaxModel').modal('hide');
              table.draw();

          },
          error: function (data) {
              console.log('Error:', data);
              $('#saveBtn').html('Save Changes');
          }
      });
    });

    $('body').on('click', '.deleteCustomer', function () {

        var Customer_id = $(this).data("id");
        confirm("Are You sure want to delete !");

        $.ajax({
            type: "DELETE",
            url: ""+'/'+Customer_id,
            success: function (data) {
                table.draw();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

  });
</script>
</html>
Step 8 - Serving the Laravel 8 Application
Head back to your terminal and run the following command:
$ php artisan serve
Next open your web browser and navigate to the http://localhost:8000/customers file.
That's it, we have finished our Laravel 8 Ajax CRUD app where we have seen how to use jQuery to send Ajax requests and implement a CRUD interface for working with a MySQL database.
https://www.techiediaries.com/laravel-8-ajax-crud-modal-pagination/ 
