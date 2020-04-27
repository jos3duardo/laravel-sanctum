<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About [Laravel Sanctum](https://laravel.com/docs/7.x/sanctum)
Laravel Sanctum provides a featherweight authentication system for SPAs (single page applications), mobile applications, and simple, token based APIs. Sanctum allows each user of your application to generate multiple API tokens for their account. These tokens may be granted abilities / scopes which specify which actions the tokens are allowed to perform. 

### Installation
You may install Laravel Sanctum via Composer:
```bash
composer require laravel/sanctum
```

Next, you should publish the Sanctum configuration and migration files using the vendor:publish Artisan command. The sanctum configuration file will be placed in your config directory:

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"

```

Finally, you should run your database migrations. Sanctum will create one database table in which to store API tokens:

```bash
php artisan migrate
```
Next, if you plan to utilize Sanctum to authenticate an SPA, you should add Sanctum's middleware to your api middleware group within your app/Http/Kernel.php file:
```bash
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

'api' => [
    EnsureFrontendRequestsAreStateful::class,
    'throttle:60,1',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```

### CORS & Cookies
In addition, you should enable the withCredentials option on your global axios instance. Typically, this should be performed in your resources/js/bootstrap.js file:
```bash
axios.defaults.withCredentials = true;
```

### Authenticating

To authenticate your SPA, your SPA's login page should first make a request to the /sanctum/csrf-cookie route to initialize CSRF protection for the application:  

Once CSRF protection has been initialized, you should make a POST request to the typical Laravel /login route. This /login route may be provided by the laravel/ui authentication scaffolding package.

### Laravel/UI

Laravel's laravel/ui package provides a quick way to scaffold all of the routes and views you need for authentication using a few simple commands:

```bash
composer require laravel/ui

```

 

### Protecting Routes

```bash
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
```

### Create a controller

It's necessary for use a route and make login
```bash
php artisan make:controller UserController
```

### Create a route for controller

this route is make in file routes/api.php
```bash
Route::post('/login', 'UserController@login');
```

now is necessary your create a user for testing the route

we go use the [seeder](https://laravel.com/docs/7.x/seeding#writing-seeders) for it

```bash
php artisan make:seeder UserSeeder
```

edit the file in path **database/seeds/UserSeeder.php**
```bash
<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
           'name' => 'admin',
           'email' => 'admin@gmail.com',
           'password' => Hash::make('password'),
       ]);
    }
}
```

uncomment code in path **database/seeds/DatabaseSeeder.php**

```bash
 public function run()
    {
         $this->call(UserSeeder::class);
    }
```

use the db:seed Artisan command to seed you database.
```bash
php artisan db:seed
```

in UserController:
```bash
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

Route::post('/sanctum/token', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    return $user->createToken($request->device_name)->plainTextToken;
});
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

