# JetStream

JetStream e uma das novidades do Laravel 8

É um scaffold para autenticação do Laravel 8 e bem mais. Inclue login, registro, verificação de e-mail, autenticação em dois fatores, gerenciamento de sessão, suporte a API via Laravel Sanctum e opcional gerenciamento de equipes/times.

Laravel 8 jetstream designed by Tailwind CSS and they provide auth using livewire and Inertia.

According to the offcial docs of Jetstream (https://jetstream.laravel.com/1.x/introduction.html):
Laravel Jetstream is a beautifully designed application scaffolding for Laravel. Jetstream provides the perfect starting point for your next Laravel application and includes login, registration, email verification, two-factor authentication, session management, API support via Laravel Sanctum, and optional team management.

Jetstream is not only a scaffolding for Laravel 8 authentication but also other common application requirements such as API and team managment that can help you easily build SaaS applications with Laravel 8.

Jetstream is not based on Bootstrap styles but Tailwind CSS instead and provides you with two stacks - Livewire or Inertia scaffolding. This latter is based on Vue.js.

API support via Laravel Sanctum, and optional team management.

É projetado usando Tailwind CSS com as opções de scaffold com livewire ou inertia.js

## Código do jetstream
```php
resources/views
    api/
    auth/
    layouts/
    profile/
    teams/
    dashboard.blade.php
    welcome.blade.php
app/Actions
    Fortify
        CreateNewUser.php
        PasswordValidationRules.php
        ResetUserPassword.php
        UpdateUserPassword.php
        UpdateUserProfileInformation.php
    JetStream
        Jetstream/AddTeamMember.php
        Jetstream/CreateTeam.php
        Jetstream/DeleteTeam.php
        Jetstream/DeleteUser.php
        Jetstream/UpdateTeamName.php    
app/Providers
    JetstreamServiceProvider.php
app/Providers
    FortifyServiceProvider.php
vendor/laravel/jetstream (muita coisa aqui, mas devemos evitar alterar, apenas ler)
```
## Configurações do jetstream
```php
    config/jetstream.php
```
    Por default apenas:
```php
        'stack' => 'livewire',

        'features' => [
            Features::profilePhotos(),
            Features::api(),
            Features::teams(),
        ],
```
    Podemos desabilitar qualquer das features ou todas apenas comentando
```php
    config/fortify.php
    'features' => [
        Features::registration(),
        Features::resetPasswords(),
        // Features::emailVerification(),
        Features::updateProfileInformation(),
        Features::updatePasswords(),
        Features::twoFactorAuthentication(),
    ],
```
    Também podemos desabilitar as que não desejamos usar

    E outras configurações

Tiago Heineck

Olá, usando o JetStream, ele cria tudo o que tu precisa em App\Actions\Fortify .... foram movidos para este namespace... Ali ficaram as regras básicas para criar novo usuário, regras de validação de senha, reset, atualização de senha, atualização de informações do usuário e exclusão de usuário.... mudou um pouco a abordagem, achei bem interessante, ainda não explorei o suficiente, mas me parece que aumenta o reúso com essas Actions fora de um controller (primeira impressão, ainda não usei de fato).

resources/views/dashboard.blade.php when using Livewire and resources/js/Pages/Dashboard.vue when using Inertia.

## Actions

app/Actions directory. These action classes typically perform a single action and correspond to a single Jetstream feature, such as creating a team or deleting a user. You are free to customize these classes if you would like to tweak the backend behavior of Jetstream.

## Tailwind

During installation, Jetstream will scaffold your application's integration with the Tailwind CSS framework. Specifically, a webpack.mix.js file and tailwind.config.js file will be created. These two files are used to build your compiled application CSS output. You are free to modify these files as needed for your application.

In addition, your tailwind.config.js file has been pre-configured to support PurgeCSS with the relevant directories properly specified depending on your chosen Jetstream stack.

Your application's package.json file is already scaffolded with NPM commands that you may use to compile your assets:
npm run dev

npm run prod

npm run watch

## Livewire Components

Jetstream uses a variety of Blade components, such as buttons and modals, to power the Livewire stack. If you are using the Livewire stack and you would like to publish these components after installing Jetstream, you may use the vendor:publish Artisan command:
```php
php artisan vendor:publish --tag=jetstream-views
```
## Application Logo

As you may have noticed, the Jetstream logo is utilized on Jetstream's authentication pages as well as the top navigation bar. You may easily customize the logo by modifying two Jetstream components.

## Livewire
If you are using the Livewire stack, you should first publish the Livewire stack's Blade components:
php artisan vendor:publish --tag=jetstream-views

Next, you should customize the SVGs located in the resources/views/vendor/jetstream/components/application-logo.blade.php and resources/views/vendor/jetstream/components/application-mark.blade.php components.

## Inertia

If you are using the Inertia stack, you should customize the SVGs located in resources/js/Jetstream/ApplicationLogo.vue and resources/js/Jetstream/
ApplicationMark.vue. After customizing these components, you should rebuild your assets:
npm run dev

## Views

Regardless of the stack chosen for your application, authentication related views are stored in the resources/views/auth directory and are Blade templates. You are free to customize the styling of these templates based on your application's needs.

## Actions

As typical of most Jetstream features, the logic executed to satisfy authentication requests can be found in an action class within your application.
Specifically, the App\Actions\Fortify\UpdateUserProfileInformation class will be invoked when the user updates their profile. This action is responsible for validating the input and updating the user's profile information.

Therefore, any customizations you wish to make to this logic should be made in this class. The action receives the currently authenticated $user and an array of $input that contains all of the input from the incoming request, including the updated profile photo if applicable.

## Password Validation Rules

The App\Actions\Fortify\CreateNewUser, App\Actions\Fortify\ResetUserPassword, and App\Actions\Fortify\UpdateUserPassword actions all utilize the App\Actions\Fortify\PasswordValidationRules trait.

As you may have noticed, the App\Actions\Fortify\PasswordValidationRules trait utilizes a custom Laravel\Fortify\Rules\Password validation rule object. This object allows you to easily customize the password requirements for your application. By default, the rule requires a password that is at least 8 characters in length. However, you may use the following methods to customize the password's requirements:
(new Password)->length(10)
```php
// Require at least one uppercase character...
(new Password)->requireUppercase()

// Require at least one numeric character...
(new Password)->requireNumeric()
```
## Email Verification

Laravel Jetstream includes support for requiring that a newly registered user verify their email address. However, support for this feature is disabled by default. To enable this feature, you should uncomment the relevant entry in the features configuration item of the config/fortify.php configuration file:
```php
'features' => [
    Features::registration(),
    Features::resetPasswords(),
    Features::emailVerification(),
    Features::updateProfileInformation(),
    Features::updatePasswords(),
    Features::twoFactorAuthentication(),
],
```
Next, you should ensure that your App\Models\User class implements the MustVerifyEmail interface. This interface is already imported into this model for you.
Once these two setup steps have been completed, newly registered users will receive an email prompting them to verify their email address ownership.

## Profile Management

Introduction

Laravel Jetstream's profile management features are accessed by the user using the top-right user profile navigation dropdown menu. Jetstream scaffolds views and actions that allow the user to update their name, email address, and, optionally, their profile photo.

## Views / Pages

When using the Livewire stack, this view is displayed using the resources/views/profile/update-profile-information-form.blade.php Blade template. When using the Inertia stack, this view is displayed using the resources/js/Pages/Profile/UpdateProfileInformationForm.vue template.
Each of these templates will receive the entire authenticated user object so that you can add additional fields to these forms as necessary. Any additional inputs added to the forms will be included in the $input array that is passed to your UpdateUserProfileInformation action.

## Actions
As typical of most Jetstream features, the logic executed to satisfy profile update requests can be found in an action class within your application. Specifically, the App\Actions\Fortify\UpdateUserProfileInformation class will be invoked when the user updates their profile. This action is responsible for validating the input and updating the user's profile information.

Therefore, any customizations you wish to make to this logic should be made in this class. The action receives the currently authenticated $user and an array of $input that contains all of the input from the incoming request, including the updated profile photo if applicable.

## Profile Photos

By default, Jetstream allows users to upload custom profile photos. This functionality is supported by the Laravel\Jetstream\HasProfilePhoto trait that is automatically attached to your App\Models\User class during Jetstream's installation.

This trait contains methods such as updateProfilePhoto, getProfilePhotoUrlAttribute, defaultProfilePhotoUrl, and profilePhotoDisk which may all be overwritten by your own App\Models\User class if you need to customize their behavior. You are encouraged to read through the source code of this trait so that you have a full understanding of the features it is providing to your application.

The updateProfilePhoto method is the primary method used to store profile photos and is called by the UpdateUserProfileInformation action.

Laravel Vapor
By default, the s3 disk will be used automatically when your application is running within Laravel Vapor
.
## Disabling Profile Photos

If you do not wish to allow users to upload custom profile photos, you may disable the feature in your config/jetstream.php configuration file. To disable the feature, simply comment out the feature entry from the features configuration item within this file:
```php
'features' => [
    // Features::profilePhotos(),
    Features::api(),
    Features::teams(),
],
```
## Account Deletion

The profile management screen also includes an action panel that allows the user to delete their application account. When the user chooses to delete their account, the App\Actions\Jetstream\DeleteUser action class will be invoked. You are free to customize your application's account deletion logic within this class.

## Security

Introduction

Laravel Jetstream's security features are accessed by the user using the top-right user profile navigation dropdown menu. Jetstream scaffolds views and actions that allow the user to update their password, enable / disable two-factor authentication, and logout their other browser sessions.

## Two-Factor Authentication

Laravel Jetstream automatically scaffolds two factor authentication support for all Jetstream applications. When a user enables two-factor authentication for their account, they should scan the given QR code using a free authenticator application such as Google Authenticator. In addition, they should store the listed recovery codes in a secure password manager such as 1Password.
If the user loses access to their mobile device, the Jetstream login view will allow them to authenticate using one of their recovery codes instead of the temporary token provided by their mobile device's authenticator application.

## Views / Pages
Typically, the views and pages for these features should not require customization, as they are already feature complete. However, their locations are described below.

## Password Update
When using the Livewire stack, the password update view is displayed using the resources/views/profile/update-password-form.blade.php Blade template. When using the Inertia stack, this view is displayed using the resources/js/Pages/Profile/UpdatePasswordForm.vue template.

## Two-Factor Authentication
When using the Livewire stack, the two-factor authentication management view is displayed using the resources/views/profile/two-factor-authentication-form.blade.php Blade template. When using the Inertia stack, this view is displayed using the resources/js/Pages/Profile/TwoFactorAuthenticationForm.vue template.

## Browser Sessions
When using the Livewire stack, the browser session management view is displayed using the resources/views/profile/logout-other-browser-sessions-form.blade.php Blade template. When using the Inertia stack, this view is displayed using the resources/js/Pages/Profile/LogoutOtherBrowserSessionsForm.vue template.
This feature utilizes Laravel's built-in AuthenticateSession middleware to safely logout other browser sessions that are authenticated as the current user.

## Actions
As typical of most Jetstream features, the logic executed to satisfy security related requests can be found within action classes within your application. However, only the password update action is customizable. Generally, the two-factor authentication and browser sessions actions should remain encapsulated within Jetstream and should not require customization.

## Password Update
The App\Actions\Fortify\UpdateUserPassword class will be invoked when the user updates their password. This action is responsible for validating the input and updating the user's password.

This action utilizes the App\Actions\Fortify\PasswordValidationRules trait to determine the validation rules that will be applied to the password. Customizing this trait will uniformly affect the validation rules applied to the password when the user registers, resets their password, or updates their password.

## Password Validation Rules
As you may have noticed, the App\Actions\Fortify\PasswordValidationRules trait utilizes a custom Laravel\Fortify\Rules\Password validation rule object. This object allows you to easily customize the password requirements for your application. By default, the rule requires a password that is at least 8 characters in length. However, you may use the following methods to customize the password's requirements:
```php
(new Password)->length(10)

// Require at least one uppercase character...
(new Password)->requireUppercase()

// Require at least one numeric character...
(new Password)->requireNumeric()
```

## API

Introduction

Jetstream includes first-party integration with Laravel Sanctum

. Laravel Sanctum provides a featherweight authentication system for SPAs (single page applications), mobile applications, and simple, token based APIs. Sanctum allows each user of your application to generate multiple API tokens for their account. These tokens may be granted abilities / permissions which specify which actions the tokens are allowed to perform.

By default, the API token creation panel may be accessed using the "API" link of the top-right user profile dropdown menu. From this screen, users may create Sanctum 
API tokens that have various permissions.

Sanctum Documentation
For more information on Sanctum and to learn how to issue requests to a Sanctum authenticated API, please consult the official Sanctum documentation
.
## Defining Permissions

The permissions available to API tokens are defined using the Jetstream::permissions method within your application's JetstreamServiceProvider. Permissions are just simple strings. Once they have been defined they may be assigned to an API token:

Jetstream::defaultApiTokenPermissions(['read']);
```php
Jetstream::permissions([
    'create',
    'read',
    'update',
    'delete',
]);
```
The defaultApiTokenPermissions method may be used to specify which permissions should be selected by default when creating a new API token. Of course, a user may uncheck a default permission before creating the token.

## Authorizing Incoming Requests

Every request made to your Jetstream application, even to authenticated routes within your routes/web.php file, will be associated with a Sanctum token object. You may determine if the associated token has a given permission using the tokenCan method provided by the Laravel\Sanctum\HasApiTokens trait. This trait is automatically applied to your application's App\Models\User model during Jetstream's installation:
```php
$request->user()->tokenCan('read');
```

## First-Party UI Initiated Requests

When a user makes a request to a route within your routes/web.php file, the request will typically be authenticated by Sanctum through a cookie based web guard. Since the user is making a first-party request through the application UI in this scenario, the tokenCan method will always return true.
At first, this behavior may seem strange; however, it is convenient to be able to always assume an API token is available and can be inspected via the tokenCan method. This means that within your application's authorizations policies you may always call this method without fear that there is no token associated with the request.

## Disabling API Support

If your application will not be offering an API to third-parties, you can disable Jetstream's API feature entirely. To do so, you should comment out the relevant entry in the features configuration option of the config/jetstream.php configuration file:
```php
'features' => [
    Features::profilePhotos(),
    // Features::api(),
    Features::teams(),
],
```

## Teams

Introduction

If you installed Jetstream using the teams option, your application will be scaffolded to support team creation and management:
laravel new project-name --jet --teams

Jetstream's team features allow each registered user to create and belong to multiple teams. By default, every registered user will belong to a "Personal" team. For example, if a user named "Sally Jones" creates a new account, they will be assigned to a team named Sally's Team. After registration, the user may rename this team or create additional teams.

## Team Creation / Deletion

The team creation view is accessed via the top-right user navigation dropdown menu.

### Views / Pages

When using the Livewire stack, the team creation view is displayed using the resources/views/teams/create-team-form.blade.php Blade template. When using the Inertia stack, this view is displayed using the resources/js/Pages/Teams/CreateTeamForm.vue template.

## Actions

Team creation and deletion logic may be customized by modifying the relevant action classes within your app/Actions/Jetstream directory. These actions include 
CreateTeam, UpdateTeamName, and DeleteTeam. Each of these actions is invoked when their corresponding task is performed by the user in the application's UI. You are free to modify these actions as needed based on your application's needs.

## Inspecting User Teams

Information about a user's teams may be accessed via the methods provided by the Laravel\Jetstream\HasTeams trait. This trait is automatically applied to your application's App\Models\User model during Jetstream's installation. This trait provides a variety of helpful methods that allow you to inspect a user's teams:
```php
// Access a user's currently selected team...
$user->currentTeam : Laravel\Jetstream\Team

// Access all of the team's (including owned teams) that a user belongs to...
$user->allTeams() : Illuminate\Support\Collection

// Access all of a user's owned teams...
$user->ownedTeams : Illuminate\Database\Eloquent\Collection

// Access all of the teams that a user belongs to but does not own...
$user->teams : Illuminate\Database\Eloquent\Collection

// Access a user's "personal" team...
$user->personalTeam() : Laravel\Jetstream\Team

// Determine if a user owns a given team...
$user->ownsTeam($team) : bool

// Determine if a user belongs to a given team...
$user->belongsToTeam($team) : bool

// Access an array of all permissions a user has for a given team...
$user->teamPermissions($team) : array

// Determine if a user has a given team permission...
$user->hasTeamPermission($team, 'server:create') : bool
```
## Current Team

Every user within a Jetstream application has a "current team". This is the team that the user is actively viewing resources for. For example, if you are building a calendar application, your application would display the upcoming calendar events for the user's current team.

You may access the user's current team using the $user->currentTeam Eloquent relationship. This team may be used to scope your other Eloquent queries by the team:
```php
return App\Models\Calendar::where(
    'team_id', $request->user()->currentTeam->id
)->get();
```
A user may switch their current team via the user profile dropdown menu available within the Jetstream navigation bar.

## Member Management

Team members may be added and removed via Jetstream's "Team Settings" view. The backend logic that manages these actions may be customized by modifying the relevant actions, such as the App\Actions\Jetstream\AddTeamMember class.

## Member Management Views / Pages
When using the Livewire stack, the team member manager view is displayed using the resources/views/teams/team-member-manager.blade.php Blade template. When using the Inertia stack, this view is displayed using the resources/js/Pages/Teams/TeamMemberManager.vue template. Generally, these templates should not require customization.

## Member Management Actions
Team member addition logic may be customized by modifying the App\Actions\Jetstream\AddTeamMember action class. The class' add method is invoked with the currently authenticated user, the Laravel\Jetstream\Team instance, the email address of the user being added to the team, and the role (if applicable) of the user being added to the team.

This action is responsible for validating that the user can actually be added to the team and then adding the user to the team. You are free to customize this action based on the needs of your particular application.

## Roles / Permissions

Each team member added to a team may be assigned a given role, and each role is assigned a set of permissions. Role permissions are defined in your application's JetstreamServiceProvider using the Jetstream::role method. This method accepts a "slug" for the role, a user-friendly role name, the role permissions, and a description of the role. This information will be used to display the role within the team member management view:
Jetstream::defaultApiTokenPermissions(['read']);
```php
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
```
## Team API Support

When Jetstream is installed with team support, available API permissions are automatically derived by combining all unique permissions available to roles. Therefore, a separate call to the Jetstream::permissions method is unnecessary.

## Authorization
Of course, you will need a way to authorize that incoming requests initiated by a team member may actually be performed by that user. A user's team permissions may be inspected using the hasTeamPermission method available via the Laravel\Jetstream\HasTeams trait. There is never a need to inspect a user's role. You only need to inspect that the user has a given granular permission. Roles are simply a presentational concept used to group granular permissions. Typically, you will execute calls to this method within your application's authorization policies:
```php
if ($request->user()->hasTeamPermission($team, 'read')) {
    // The user's role includes the "read" permission...
}
```
## Combining Team Permissions With API Permissions

When building a Jetstream application that provides both API support and team support, you should verify an incoming request's team permissions and API token permissions within your application's authorization policies. This is important because an API token may have the theoretical ability to perform an action while a user does not actually have that action granted to them via their team permissions:
```php
/**
 * Determine whether the user can view a flight.
 *
 * @param  \App\Models\User  $user
 * @param  \App\Models\Flight  $flight
 * @return bool
 */
public function view(User $user, Flight $flight)
{
    return $user->belongsToTeam($flight->team) &&
           $user->hasTeamPermission($flight->team, 'flight:view') &&
           $user->tokenCan('flight:view');
}
```
## Livewire

Introduction

Laravel Livewire is a library that makes it simple to build modern, reactive, dynamic interfaces using Laravel Blade as your templating language. This is a great stack to choose if you want to build an application that is dynamic and reactive but don't feel comfortable jumping into a full JavaScript framework like Vue.js.

When using Livewire, your application's routes will respond with typical Blade templates. However, within these templates you may render Livewire components as necessary:
```php
<div class="mt-4">
    @livewire('server-list')
</div>
```
When using the Livewire stack, Jetstream has some unique features that you should be aware of. We will discuss each of these features below.

### Livewire Documentation

Before using the Livewire stack, you are strongly encouraged to review the entire Livewire documentation

## Components

While building the Jetstream Livewire stack, a variety of Blade components (buttons, panels, inputs, modals) were created to assist in creating UI consistency and ease of use. You are free to use or not use these components. However, if you would like to use them, you should publish them using the Artisan vendor:publish command:
```php
php artisan vendor:publish --tag=jetstream-views
```
You may gain insight into how to use these components by reviewing their usage within Jetstream's existing views located within your resources/views directory.

## Modals

Most of the the Jetstream Livewire stack's components have no communication with your backend. However, the Livewire modal components included with Jetstream do interact with your Livewire backend to determine their open / closed state. In addition, Jetstream includes two types of modals: dialog-modal and confirmation-modal. 
The confirmation-modal may be used when confirming destructive actions such as deletions, while the dialog-modal is a more generic modal window that may be used at any time.

To illustrate the use of modals, consider the following modal that confirms a user would like to delete their account:
```php
<x-jet-confirmation-modal wire:model="confirmingUserDeletion">
    <x-slot name="title">
        Delete Account
    </x-slot>

    <x-slot name="content">
        Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted.
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
            Nevermind
        </x-jet-secondary-button>

        <x-jet-danger-button class="ml-2" wire:click="deleteUser" wire:loading.attr="disabled">
            Delete Account
        </x-jet-danger-button>
    </x-slot>
</x-jet-confirmation-modal>
```
As you can see, the modal's open / close state is determined by a wire:model property that is declared on the component. This property name should correspond to a boolean property on your Livewire component's corresponding PHP class. The modal's contents may be specified by hydrating three slots: title, content, and footer.

## Inertia

Introduction

The Inertia.js stack provided by Jetstream uses Vue.js
as its templating language. Building an Inertia application is a lot like building a typical Vue application; however, you will use Laravel's router instead of Vue router. Inertia is a small library that allows you to render single-file Vue components from your Laravel backend by providing the name of the component and the data that should be hydrated into that component's "props".

In other words, this stack gives you the full power of Vue.js without the complexity of client-side routing. You get to use the standard Laravel router that you are used to. The Inertia stack is a great choice if you are comfortable with and enjoy using Vue.js as your templating language.

When using Inertia, your application's routes will respond by rendering an Inertia "page". This looks very similar to returning a Laravel Blade view:
```php
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * Show the general profile settings screen.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Inertia\Response
 */
public function show(Request $request)
{
    return Inertia::render('Profile/Show', [
        'sessions' => $this->sessions($request)->all(),
    ]);
}
```
When using the Inertia stack, Jetstream has some unique features that you should be aware of. We will discuss each of these features below.

### Inertia Documentation

Before using the Inertia stack, you are strongly encouraged to review the entire Inertia documentation

## Components

While building the Jetstream Inertia stack, a variety of Vue components (buttons, panels, inputs, modals) were created to assist in creating UI consistency and ease of use. You are free to use or not use these components. All of these components are located within your application's resources/js/Jetstream directory.

You may gain insight into how to use these components by reviewing their usage within Jetstream's existing pages located within your resources/js/Pages directory.

## Form / Validation Helpers

In order to make working with forms and validation errors more convenient, a laravel-jetstream
NPM package has been created. This package is automatically installed when using the Jetstream Inertia stack.

This package adds a new form method to the $inertia object that may be accessed via your Vue components. The form method is used to create a new form object that will provide easy access to error messages, as well as conveniences such as resetting the form state on a successful form submission:
```php
data() {
    return {
        form: this.$inertia.form({
            name: this.name,
            email: this.email,
        }, {
            bag: 'updateProfileInformation',
            resetOnSuccess: true,
        }),
    }
}
```

A form may be submitted using the post, put, or delete methods. All of the data specified during the form's creation will be automatically included in the request. 
In addition, Inertia request options
may also be specified:
```php
this.form.post('/user/profile-information', {
    preserveScroll: true
})
```

Form error messages may be access using the form.error method. This method will return the first available error message for the given field:
```php
<jet-input-error :message="form.error('email')" class="mt-2" />
```
A flattened list of all validation errors may be accessed using the errors method. This method may prove useful when attempting to display the error message in a simple list:
```php
<li v-for="error in form.errors()">
    {{ error }}
</li>
```
Additional information about the form's current state is available via the recentlySuccessful and processing methods. These methods are helpful for dictating disabled or "in progress" UI states:
```php
<jet-action-message :on="form.recentlySuccessful" class="mr-3">
    Saved.
</jet-action-message>

<jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
    Save
</jet-button>
```
To learn more about using Jetstream's Inertia form helpers, you are free to review the Inertia pages created during Jetstream's installation. These pages are located within your application's resources/js/Pages directory.

## Modals

Jetstream's Inertia stack also includes two modal components: DialogModal and ConfirmationModal. The ConfirmationModal may be used when confirming destructive actions such as deletions, while the DialogModal is a more generic modal window that may be used at any time.

To illustrate the use of modals, consider the following modal that confirms a user would like to delete their account:
```php
<jet-confirmation-modal :show="confirmingUserDeletion" @close="confirmingUserDeletion = false">
    <template #title>
        Delete Account
    </template>

    <template #content>
        Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted.
    </template>

    <template #footer>
        <jet-secondary-button @click.native="confirmingUserDeletion = false">
            Nevermind
        </jet-secondary-button>

        <jet-danger-button class="ml-2" @click.native="deleteTeam" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            Delete Account
        </jet-danger-button>
    </template>
</jet-confirmation-modal>
```
As you can see, the modal's open / close state is determined by a show property that is declared on the component. The modal's contents may be specified by hydrating three slots: title, content, and footer.

https://jetstream.laravel.com/1.x/introduction.html 

https://bootstrapped.blog/taking-payment-on-jetstream-register-page/

## Limewire X inertia

    Both aim to leverage the server as much as possible (routing, controllers, database access), while also avoiding full page refreshes. The difference is the views:

    Inertia: JavaScript templates (Vue/React/Svelte)

    Livewire: PHP (Blade) templates (minimal use of JS when needed)

reinink

Both Caleb (LiveWire) and myself (Inertia.js) are trying to make it easier to develop single-page applications. We both find the JavaScript landscape a little complicated these days, and want to find simpler ways to use all this awesome tech.

With LiveWire, you get to write almost no JavaScript, while still creating highly reactive applications in Laravel. Meaning no JavaScript build pains, npm installs, or any of that.

With Inertia.js, you get to build fully client-side rendered apps (Vue.js, React, Svelte), but build those apps in a more classic server-side "monolith" way. With Inertia you don't need an API. Just create controllers and views, with plain old server-side routing, and Inertia turns it into a single-page application.

I hope that helps!

Inertia seems to be less locked in and rigid, giving you more freedom as a developer. Meaning you can write Vue or React views that could easily be used without inertiajs (with minor tweaks and by adding an API that your views interact with) whereas Livewire is really locking you in to the way it generates code from Blade files and its "persistent connections to the server". So you could migrate away from inertia much easier than you could migrate away from livewire.

And since you are writing Vue, React, or any other frontend framework when you use Inertia, you are building your knowledge in that highly popular framework. Livewire is livewire, for better or worse.
4

basicamente inertia é mais js (VUE, React) e o liveware é mais PHP com blade.
Eu vou de liveware, pois gosto de como o blade trata as coisas

O jetstream agora é o novo pacote pra autenticação, gestão de UI, teams e miuito mais.
Ao usares o --jet ele vai adiconar um ficheiro chamado Fortify, na pasta config... Lá você libera as features do app (login, registro, autenticação em 2 fatores e muito mais.) 

Balduino Fernando

