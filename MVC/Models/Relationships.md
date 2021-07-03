# Relacionamentos entrs Models no Laravel 8

Criar um aplicativo e executar estes exemplos.

### Tradução livre

Da documentação oficial do Laravel 8 em

https://laravel.com/docs/8.x/eloquent-relationships

Com a ajuda do Google Tranlator


- One to One
- One to Many
- Many to Many

## One to One

É o caso de um model se relacionar com um único outro model.

Supondo um user com um único phone

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public function phone()
    {
        return $this->hasOne('App\Models\Phone');
    }
}

O primeiro argumneto passado para o método hasOne é o model relacionado.

Uma vez que o relacionamento foi definido, podemos agora recuperar o registro relacionado usando as propriedades dinâmicas do Eloquent. As propriedades dinâmicas permitem que você acesse métodos de relacionamento como se fossem propriedades definidas no modelo:

$phone = User::find(1)->phone;

O Eloquent entende deduz o nome da Foreign Key baseado no nome do Model. Neste caso, assume-se automaticamente que o model de phone possui uma chave estrangeira chamada user_id. Se você deseja substituir essa convenção, precisa passar um segundo argumento para o método hasOne, que é o nome da FK:

return $this->hasOne('App\Models\Phone', 'foreign_key');

Além disso, o Eloquent assume que a chave estrangeira deve ter um valor que corresponda à coluna id (ou a coluna $primaryKey personalizada) do pai. Em outras palavras, o Eloquent irá procurar o valor da coluna id do usuário na coluna user_id do registro Phone. Se desejar que o relacionamento use um valor diferente de id para a PK, você precisa passar um terceiro argumento para o método hasOne especificando sua chave PK personalizada:

return $this->hasOne('App\Models\Phone', 'foreign_key', 'local_key');

## Definindo o relacionamento inverso

Agora é um phone com um user

Podemos acessar o model de phone do nosso user. Agora, vamos definir uma relação no model de phone que nos permitirá acessar o user que possui o phone. Podemos definir o inverso de um relacionamento hasOne usando o método belongsTo:

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Phone extends Model
{
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}

No exemplo acima o Eloquent irá entender que em phones temos uma FK chamada user_id e uma PK chamada id. Caso sejam diferentes veja acima como procedor.

## One to Many

Um relacionamento um-para-muitos é usado para definir relacionamentos em que um único modelo possui qualquer quantidade de outros modelos. Por exemplo, uma postagem de blog pode ter um número indefinido de comentários. Como todos os outros relacionamentos do Eloquent, os relacionamentos de um para muitos são definidos pela colocação de uma função em seu modelo do Eloquent:

Posts com comments

Um post pode ter vários comentários.

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }
}

Como está acima o Eloquent irá deduzir que a tabela comments tem uma FK chamada user_id e uma PK chamada id.

Agora podemos proceder assim:
```php
$comments = App\Models\Post::find(1)->comments;

foreach ($comments as $comment) {
    //
}
```
ou
```php
$comment = App\Models\Post::find(1)->comments()->where('title', 'foo')->first();
```
ou
Receber todos os posts que têm pelo menos um comentário:
```php
$posts =Post::has('comments')->get();

$posts =Post::has('comments.votes')->get();

$posts =Post::has('comments', '>', 3)->get();
```
## Relacionamento inverso do one to many

Agora que podemos acessar todos os comentários de uma postagem, vamos definir um relacionamento para permitir que um comentário acesse sua postagem pai. Para definir o inverso de um relacionamento hasMany, defina uma função de relacionamento no modelo filho que chama o método belongsTo:

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /**
     * Get the post that owns the comment.
     */
    public function post()
    {
        return $this->belongsTo('App\Models\Post');
    }
}

Uma vez que o relacionamento foi definido, podemos recuperar o modelo Post para um Comentário acessando a "propriedade dinâmica" post:

$comment = App\Models\Comment::find(1);

echo $comment->post->title;

No exemplo acima, o Eloquent tentará combinar o post_id do modelo Comment com um id no modelo Post. O Eloquent determina o nome da chave estrangeira padrão examinando o nome do método de relacionamento e sufixando o nome do método com um _ seguido pelo nome da coluna da chave primária. No entanto, se a chave estrangeira no modelo de comentário não for post_id, você pode passar um nome de chave personalizado como o segundo argumento para o método belongsTo:

/**
 * Get the post that owns the comment.
 */
public function post()
{
    return $this->belongsTo('App\Models\Post', 'foreign_key');
}

Se o seu modelo pai não usa id como sua chave primária, ou você deseja unir o modelo filho a uma coluna diferente, você pode passar um terceiro argumento para o método belongsTo especificando a chave personalizada PK de sua tabela pai:

/**
 * Get the post that owns the comment.
 */
public function post()
{
    return $this->belongsTo('App\Models\Post', 'foreign_key', 'owner_key');
}

## Relacionamentos Many to Many

As relações Many-to-Many/muitos-para-muitos são ligeiramente mais complicadas do que as relações hasOne e hasMany. Um exemplo de tal relacionamento é um usuário com muitas roles, onde as roles também são compartilhadas por outros usuários. Por exemplo, muitos usuários podem ter a função "Admin".

### Estrutura da Tabela

Para definir esse relacionamento, três tabelas de banco de dados são necessárias: users, roles e role_user. A tabela role_user é derivada da ordem alfabética dos nomes de modelos relacionados e contém somente os campos user_id e role_id:

users
    id - integer
    name - string

roles
    id - integer
    name - string

role_user
    user_id - integer
    role_id - integer

## Estrutura do model User

Relacionamentos muitos para muitos são definidos escrevendo um método que retorna o resultado do método belongsToMany. Por exemplo, vamos definir o método de funções em nosso modelo de usuário:

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    /**
     * The roles that belong to the user.
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role');
    }
}

Depois que o relacionamento foi definido, você pode acessar as roles do usuário usando a propriedade dinâmica roles:

$user = App\Models\User::find(1);

foreach ($user->roles as $role) {
    //
}

Como todos os outros tipos de relacionamento, você pode chamar o método roles para continuar encadeando as restrições de consulta no relacionamento:

$roles = App\Models\User::find(1)->roles()->orderBy('name')->get();

Como mencionado anteriormente, para determinar o nome da tabela da tabela de junção do relacionamento, o Eloquent junta os dois nomes de modelo relacionados em "ordem alfabética". No entanto, você está livre para substituir essa convenção. Você pode fazer isso passando um segundo argumento para o método belongsToMany:

return $this->belongsToMany('App\Models\Role', 'role_user');

Além de personalizar o nome da tabela de junção/pivot, você também pode personalizar os nomes das colunas das chaves na tabela, passando argumentos adicionais para o método belongsToMany. O terceiro argumento é o nome da chave estrangeira do modelo no qual você está definindo o relacionamento, enquanto o quarto argumento é o nome da chave estrangeira do modelo ao qual você está se unindo:

return $this->belongsToMany('App\Models\Role', 'role_user', 'user_id', 'role_id');

$user = User::find(1);
$user->roles()->attach($roleId);

Remover

$user->roles()->detach($roleId);

$user->roles()->detach();

Sincronizar

$user->roles()->sync([1, 2, 3]);

Após a operação acima somente os registros 1,2 e 3 devem existir na tabela. 

## Definindo o relacionamento inverso

Para definir o inverso de um relacionamento muitos para muitos, você coloca outra chamada para belongsToMany em seu modelo relacionado. Para continuar nosso exemplo de roles de usuários, vamos definir o método de usuários no modelo Role:

<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public function users()
    {
        return $this->belongsToMany('App\Models\User');
    }
}

Como você pode ver, o relacionamento é definido exatamente da mesma forma que sua contraparte de Usuário, com exceção de referenciar o modelo App\Models\User. Uma vez que estamos reutilizando o método belongsToMany, todas as tabelas usuais e opções de customização de chave estão disponíveis ao definir o inverso dos relacionamentos muitos para muitos.

## Recuperando Colunas da Tabela Intermediária/Pivot

Como você já aprendeu, trabalhar com relações muitos para muitos requer a presença de uma tabela intermediária/pivot. O Eloquent fornece algumas maneiras muito úteis de interagir com esta tabela. Por exemplo, vamos supor que nosso objeto User tenha muitos objetos Role aos quais está relacionado. Após acessar esta relação, podemos acessar a tabela intermediária usando o atributo pivot nos modelos:

$user = App\Models\User::find(1);

foreach ($user->roles as $role) {
    echo $role->pivot->created_at;
}

Observe que cada modelo Role que recuperamos recebe automaticamente um atributo pivô. Este atributo contém um modelo que representa a tabela intermediária e pode ser usado como qualquer outro modelo do Eloquent.

Por padrão, apenas as chaves do modelo estarão presentes no objeto pivô. Se sua tabela dinâmica contiver atributos extras, você deve especificá-los ao definir o relacionamento:

return $this->belongsToMany('App\Models\Role')->withPivot('column1', 'column2');

Se você deseja que sua tabela dinâmica tenha mantido automaticamente os timestamps created_at e updated_at, use o método withTimestamps na definição de relacionamento:

return $this->belongsToMany('App\Models\Role')->withTimestamps();

Ao usar timestamps de data/hora em tabelas dinâmicas/pivot, a tabela deve ter as colunas timestamps de data/hora created_at e updated_at

## Mais detalhes em

https://laravel.com/docs/8.x/eloquent-relationships
