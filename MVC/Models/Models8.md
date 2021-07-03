# Models no Laravel 8

No laravel 8 os models agora tem seu próprio diretório e namespace:

app/Models

App\Models

Se criarmos um novo model:

php artisan make:model Clients

Ele será criado na pasta app/Models

Mas se você não quizer isso e quizer trabalhar como no 7, na pasta app, então mova o User.php para app.

Agora, se criar o model Clients, e o laravel nao encontrar a pasta app/Models, ele criará e gerenciará os models na pasta app

php artisan make:model Clients

Será criado em app.


