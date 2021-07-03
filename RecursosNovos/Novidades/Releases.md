# Releases do Laravel 8

is() and isNot() methods to Eloquent relations without an extra query:
```php
$post->author()->is($user);
$post->author()->isNot($user);
```
A newline method for console IO
Scott Carpenter contributed a newLine() method you can use to output a blank line in Artisan console output:
```php
// What you might do now...
$this->info('');
$this->info('Parsing CSV file');
$this->info('');

// You could already do the following
$this->getOutput()->newLine();

// This feature adds a shortcut
$this->newLine();

// You can specify the number of blank lines
$this->newLine(5);
```
canAny method to the Authorizable trait that the User model supports. This allows the following (similar to the blade feature with the same name):
$user->canAny(['permission-one', 'permission-two'])

## 8.10.0

Added

    • Allow for chains to be added to batches (#34612, 7b4a9ec) 
    • Added is() method to 1-1 relations for model comparison (#34693, 7ba2577) 
    • Added upsert to Eloquent and Base Query Builders (#34698, #34712, 58a0e1b) 
    • Support psql and pg_restore commands in schema load (#34711) 
    • Added Illuminate\Database\Schema\Builder::dropColumns() method on the schema class (#34720) 
    • Added yearlyOn() method to scheduler (#34728) 
    • Added restrictOnDelete method to ForeignKeyDefinition class (#34752) 
    • Added newLine() method to InteractsWithIO trait (#34754) 
    • Added isNotEmpty method to HtmlString (#34774) 
    • Added delay() to PendingChain (#34789) 
    • Added ‘multiple_of’ validation rule (#34788) 
    • Added custom methods proxy support for jobs ::dispatch() (#34781) 
    • Added QueryBuilder::clone() (#34780) 
    • Support bus chain on fake (a952ac24) 
    • Added missing force flag to queue:clear command (#34809) 
    • Added dropConstrainedForeignId to `Blueprint (#34806) 
    • Implement supportsTags() on the Cache Repository (#34820) 
    • Added canAny to user model (#34815) 
    • Added when() and unless() methods to MailMessage (#34814) 

Fixed

    • Fixed collection wrapping in BelongsToManyRelationship (9245807) 
    • Fixed LengthAwarePaginator translations issue (#34714) 

Changed

    • Improve schedule:work command (#34736, bbddba2) 
    • Guard against invalid guard in make:policy (#34792) 
    • Fixed router inconsistency for namespaced route groups (#34793) 

## 8.12: New withColumn() to support Aggregate Functions
Khalil Laleh contributed a withColumn method to support more SQL aggregation functions like min, max, sum, avg, etc. over relationships:
```php
Post::withCount('comments');
Post::withMax('comments', 'created_at');
Post::withMin('comments', 'created_at');
Post::withSum('comments', 'foo');
Post::withAvg('comments', 'foo');
```
8.12: Add explain() to Eloquent/Query Builder
Illia Sakovich contributed an explain() method to the query builder/eloquent builder, which allows you to receive the explanation query from the builder:
Webhook::where('event', 'users.registered')->explain()

Webhook::where('event', 'users.registered')->explain()->dd()

Now you can call explain() to return the explanation or chain a dd() call to die and dump the explanation.

8.12: Full PHP 8 Support

Dries Vints has been working on adding PHP 8 support to the Laravel ecosystem, which involves various libraries (both first- and third-party libraries) and coordination of many efforts. A HUGE thanks to Dries and all those involved in getting Laravel ready for the next major PHP version!

8.12: Route Registration Methods
Gregori Piñeres contributed some route regex registration methods to easily define route params for repetitive, regular expressions you might add to route params:
```php
// Before. This is still a valid, acceptable way of defining routes
Route::get('authors/{author}/{book}')
    ->where([
        'author' => '[0-9]+',
        'book' => '[a-zA-Z]+'
    ]);

// New optional syntax
Route::get('authors/{author}/{book}')
    ->whereNumber('author')
    ->whereAlpha('book');

// New methods support multiple args
Route::get('authors/{author}/{book}')
    ->whereAlpha('author', 'book');
```

## 8.13: Aggregate Load Methods

In 8.12, Khalil Laleh contributed the withColumn() method to support aggregate functions. In 8.13, he contributed load* methods for aggregate functions:
```php
public function loadAggregate($relations, $column, $function = null)
public function loadCount($relations)
public function loadMax($relations, $column)
public function loadMin($relations, $column)
public function loadSum($relations, $column)
public function loadAvg($relations, $column)
```
v8.13.0

Added

    • Added loadMax() | loadMin() | loadSum() | loadAvg() methods to Illuminate\Database\Eloquent\Collection. Added loadMax() | loadMin() | loadSum() | loadAvg() | loadMorphMax() | loadMorphMin() | loadMorphSum() | loadMorphAvg() methods to Illuminate\Database\Eloquent\Model (#35029) 
    • Modify Illuminate\Database\Eloquent\Concerns\QueriesRelationships::has() method to support MorphTo relations (#35050) 
    • Added Illuminate\Support\Stringable::chunk() (#35038) 

Fixed

    • Fixed a few issues in Illuminate\Database\Eloquent\Concerns\QueriesRelationships::withAggregate() (#35061, #35063) 

Changed

    • Set chain queue | connection | delay only when explicitly configured in (#35047) 
Refactoring
    • Remove redundant unreachable return statements in some places (#35053) 

## v8.12.0

Added

    • Added ability to create observers with custom path via make:observer command (#34911) 
    • Added Illuminate\Database\Eloquent\Factories\Factory::lazy() (#34923) 
    • Added ability to make cast with custom stub file via make:cast command (#34930) 
    • ADDED: Custom casts can implement increment/decrement logic (#34964) 
    • Added encrypted Eloquent cast (#34937, #34948) 
    • Added DatabaseRefreshed event to be emitted after database refreshed (#34952, f31bfe2) 
    • Added withMax()|withMin()|withSum()|withAvg() methods to Illuminate/Database/Eloquent/Concerns/QueriesRelationships (#34965, f4e4d95, #35004) 
    • Added explain() to Query\Builder and Eloquent\Builder (#34969) 
    • Make multiple_of validation rule handle non-integer values (#34971) 
    • Added setKeysForSelectQuery method and use it when refreshing model data in Models (#34974) 
    • Full PHP 8.0 Support (#33388) 
    • Added Illuminate\Support\Reflector::isCallable() (#34994, 8c16891, 31917ab, 11cfa4d, #34999) 
    • Added route regex registration methods (#34997, 3d405cc, c2df0d5) 
    • Added dontRelease option to RateLimited and RateLimitedWithRedis job middleware (#35010) 

Fixed

    • Fixed check of file path in Illuminate\Database\Schema\PostgresSchemaState::load() (268237f) 
    • Fixed: PhpRedis (v5.3.2) cluster – set default connection context to null (#34935) 
    • Fixed Eloquent Model loadMorph and loadMorphCount methods (#34972) 
    • Fixed ambigious column on many to many with select load (5007986) 
    • Fixed Postgres Dump (#35018) 

Changed

    • Changed make:factory command (#34947, 4f38176) 
    • Make assertSee, assertSeeText, assertDontSee and assertDontSeeText accept an array (#34982, 2b98bcc) 

https://laravel-news.com/laravel-8-13-0

## Releases do framework

https://github.com/laravel/framework/releases

