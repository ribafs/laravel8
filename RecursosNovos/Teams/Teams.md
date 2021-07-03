# Teams no Laravel 8

## Permissões e roles

No app/Providers/JetStreamServiceProvider.php estão as configurações para Team
```php
    protected function configurePermissions()
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::role('admin', 'Administrator', [
            'create',
            'read',
            'update',
            'delete',
        ])->description('Administrator users can perform any action.');

        Jetstream::role('editor', 'Editor', [
            'read',
            'create',
            'update',
        ])->description('Editor users have the ability to read, create, and update.');
    }

Podemos alterar, aumentar ou redizir as permissões. Exemplo:

        Jetstream::role('author', 'Author', [
            'read',
            'create',
            'update',
        ])->description('Author users have the ability to read, create, and update posts.');
```

## Adicionar a rota
```php
Route::middleware('auth:sanctum')->get('/test', function(Request $request)){
    if($request->user()->tokenCan('read')){
        return response('heyyy');
    }else{
        return 'unauthorized';
    }
};
```
## Testar no Postman
```php
GET localhost:8000/api/test

Headers
Content-Type    application/json
Accept          application/json
Authorization   Bearer b(colar aqui o token gerado)
```
A permissão é de somente read. Se mudar para outra receberá

unauthorized

## Team

Avatar
```php
    Team Settings. Onde podemos adicionar usuários registrados ao Team atual. Entrar com o e-mail e selecionar a role
    Create a new team
    Usuário Team
```
Editar a view dashboard e

Dashboard ({{auth()->user()->currentTeam->name}})

Ver para detalhes

https://jetstream.laravel.com/1.x/introduction.html

Autorização

Laravel\Jetstream\HasTeams
```php
if($request->user()->hasTeamPermission($team, 'read')){
    //liberado
}
```

