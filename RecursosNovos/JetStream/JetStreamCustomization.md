# JetStream Customization

All of the authentication view's rendering logic may be customized using the appropriate methods available via the Laravel\Fortify\Fortify class. Typically, you should call this method from the boot method of your JetstreamServiceProvider:
```php
use Laravel\Fortify\Fortify;

Fortify::loginView(function () {
    return view('auth.login');
});

Fortify::registerView(function () {
    return view('auth.register');
});
```
to have full customization over how login credentials are authenticated and users are retrieved. Jetstream allows you to easily accomplish this using the Fortify::authenticateUsing method.

This method accepts a Closure which that receives the incoming HTTP request. The Closure is responsible for validating the login credentials attached to the request and returning the associated user instance. If the credentials are invalid or no user can be found, null or false should be returned by the Closure. Typically, this method should be called from the boot method of your JetstreamServiceProvider:
```php
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Fortify;

Fortify::authenticateUsing(function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if ($user &&
        Hash::check($request->password, $user->password)) {
        return $user;
    }
})
```
If you are using the Livewire stack, your Livewire component that contains the password confirmed action should use the Laravel\Jetstream\ConfirmsPasswords trait. 
Next, you should wrap the action you wish to confirm using the confirms-password Blade component. confirms-password wrapper should contain wire:then directive that specifies which Livewire action should be run once the user's password has been confirmed. Once the user has confirmed their password, they will not be required to re-enter their password for 15 minutes:
```php
<x-jet-confirms-password wire:then="enableAdminMode">
    <x-jet-button type="button" wire:loading.attr="disabled">
        {{ __('Enable') }}
    </x-jet-button>
</x-jet-confirms-password>
```
Next, within your Livewire action that is being confirmed, you should call the ensurePasswordIsConfirmed method. This should be done at the very beginning of the relevant action method:
```php
/**
 * Enable administration mode for user.
 *
 * @return void
 */
public function enableAdminMode()
{
    $this->ensurePasswordIsConfirmed();

    // ...
}
```
Custom Rendered Inertia Authentication Pages

Some of Jetstream's Inertia pages, such as Teams/Show and Profile/Show are rendered from within Jetstream itself. However, you may need to pass additional data to these pages while building your application. Therefore, Jetstream allows you to customize the data / props passed to these pages using the Jetstream::inertia()->renderUsing method.

This method accepts the name of the page you wish to customize and a Closure. The Closure will receive the incoming HTTP request and an array of the default data that would typically be sent to the page. You are welcome to customize or add new array elements to the data as necessary. Typically, you should call this method from within the boot method of your JetstreamServiceProvider:
```php
use Illuminate\Http\Request;
use Laravel\Jetstream\Jetstream;

Jetstream::inertia()->renderUsing(
    'Profile/Show',
    function (Request $request, array $data) {
        return array_merge($data, [
            // Custom data...
        ]);
    }
);
```
By Taylor Otwell 

Creator of Laravel.

https://blog.laravel.com/jetstream-customization-and-password-confirmation


