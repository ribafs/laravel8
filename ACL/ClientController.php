<?php
// Exemplo de controle do laravel-acl
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');        
    }

    public function index(Request $request)
    {
        $auth = Auth::user()->name;
        if(($auth != 'Super') && ($auth != 'Manager') && ($auth != 'User')){
            return view('home');
        }else{
            $keyword = $request->get('search');
            $perPage = 6;

            if (!empty($keyword)) {
                $clients = Client::where('name', 'LIKE', "%$keyword%")
                    ->orWhere('email', 'LIKE', "%$keyword%")
                    ->orderBy('name', 'asc')
                    ->latest()->paginate($perPage);               
            } else {
//                $clients = Client::latest()->orderBy('name', 'asc')->paginate($perPage);
                $clients = Client::orderBy('name', 'asc')->paginate($perPage);
            }
            return view('clients.index', ['clients' => $clients]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $auth = Auth::user()->name;
        if(($auth != 'Super') && ($auth != 'Manager')){
            return view('home');
        }else{        
            return view('clients.create');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $auth = Auth::user()->name;
        if(($auth != 'Super') && ($auth != 'Manager')){
            return view('home');
        }else{        
            $requestData = $request->all();
            
            Client::create($requestData);

            return redirect('clients')->with('flash_message', 'Client added!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $auth = Auth::user()->name;
        if(($auth != 'Super') && ($auth != 'Manager') && ($auth != 'User')){
            return view('home');
        }else{        
            $client = Client::findOrFail($id);

            return view('clients.show', ['client' => $client]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $auth = Auth::user()->name;
        if(($auth != 'Super') && ($auth != 'Manager')){
            return view('home');
        }else{        
            $client = Client::findOrFail($id);

            return view('clients.edit', ['client' => $client]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $auth = Auth::user()->name;
        if(($auth != 'Super') && ($auth != 'Manager')){
            return view('home');
        }else{        
            $requestData = $request->all();
            
            $client = Client::findOrFail($id);
            $client->update($requestData);

            return redirect('clients')->with('flash_message', 'Client updated!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        $auth = Auth::user()->name;
        if(($auth != 'Super') && ($auth != 'Manager')){
            return view('home');
        }else{        
            Client::destroy($id);

            return redirect('clients')->with('flash_message', 'Client deleted!');
        }
    }
}
