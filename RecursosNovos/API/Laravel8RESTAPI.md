# Laravel 8 REST API Authentication with JWT Tutorial by Example

https://shabang.dev/laravel-8-rest-api-authentication-with-jwt-tutorial-by-example/

laravel new api-auth --jet

npm run dev && npm run dev

Config

.env

Instalar jwt-laravel

composer require tymon/jwt-auth

Editar config/app.php
```php
'providers' => [
….
'TymonJWTAuthProvidersJWTAuthServiceProvider',
],
'aliases' => [
….
'JWTAuth' => 'TymonJWTAuthFacadesJWTAuth',
'JWTFactory' => 'TymonJWTAuthFacadesJWTFactory',
],
```
Publicar

php artisan vendor:publish --provider="TymonJWTAuthProvidersJWTAuthServiceProvider"

Gerar a chave secreta

php artisan jwt:generate

Editar

vendor/tymon/src/Commands/JWTGenerateCommand.php

E atualizar
```php
public function handle() {$this->fire();}
```
Abrir

App/User.php
```php
<?php 
 
namespace App;
 
use IlluminateNotificationsNotifiable;
use IlluminateFoundationAuthUser as Authenticatable;
 
class User extends Authenticatable
{
    use Notifiable;
 
    protected $fillable = [
        'name', 'email', 'password',
    ];
 
    protected $hidden = [
        'password', 'remember_token',
    ];
 
}
```
Atualizar para o 8

Implementar o REST API Controller para JWT Authentication

Criar o controller

php artisan make:controller JwtAuthController

Atualizar
```php
<?php 
namespace AppHttpControllers;
 
use JWTAuth;
use Validator;
use AppUser;
use IlluminateHttpRequest;
use AppHttpRequestsRegisterAuthRequest;
use TymonJWTAuthExceptionsJWTException;
use SymfonyComponentHttpFoundationResponse;
 
class JwtAuthController extends Controller
{
    public $token = true;
  
    public function register(Request $request)
    {
 
         $validator = Validator::make($request->all(), 
                      [ 
                      'name' => 'required',
                      'email' => 'required|email',
                      'password' => 'required',  
                      'c_password' => 'required|same:password', 
                     ]);  
 
         if ($validator->fails()) {  
 
               return response()->json(['error'=>$validator->errors()], 401); 
 
            }   
 
 
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
  
        if ($this->token) {
            return $this->login($request);
        }
  
        return response()->json([
            'success' => true,
            'data' => $user
        ], Response::HTTP_OK);
    }
  
    public function login(Request $request)
    {
        $input = $request->only('email', 'password');
        $jwt_token = null;
  
        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], Response::HTTP_UNAUTHORIZED);
        }
  
        return response()->json([
            'success' => true,
            'token' => $jwt_token,
        ]);
    }
  
    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
  
        try {
            JWTAuth::invalidate($request->token);
  
            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
  
    public function getUser(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);
  
        $user = JWTAuth::authenticate($request->token);
  
        return response()->json(['user' => $user]);
    }
}
```
Adicionar em routes/api.php
```php
Route::post('login', 'JwtAuthController@login');
Route::post('register', 'JwtAuthController@register');
  
Route::group(['middleware' => 'auth.jwt'], function () {
 
    Route::get('logout', 'JwtAuthController@logout');
    Route::get('user-info', 'JwtAuthController@getUser');
});

php artisan serve
```
Testar com o Postman

https://shabang.dev/laravel-8-rest-api-authentication-with-jwt-tutorial-by-example/


