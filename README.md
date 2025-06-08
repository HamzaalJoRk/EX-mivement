# Spatie Permissions and Roles - Laravel 10 dashboard 
Ok so things to know befor getting started - 

This is a laravel10 / breeze / spatie permissions starter.

I am a novice with Livewire as you will see but there are a few humble components.

Please be aware that although you can create permissions and assign them to roles and eventually users in the dashboard,
they have no meaning until this middleware is applied either in the routes (web.php routes) file or in the controllers constructor

The roles and permissions created in the initial setup via the seeder and listed below - but remember they are useless until they are applied!!!!



### Whilst this is exciting the only restrictions at the moment are on the crud for users
as can be seen in the UserController methods eg

```php
 public function create()
    {
        if (!auth()->user()->can('create-users')) {
            abort(403, 'Unauthorized');
        }
        $allRoles = Role::pluck('name','name')->all();
        return view('users.create',compact('allRoles'));
    }
```
### The reason is it is a starter dash - so if we want a blog we then use the ->can('whatever-we-need-permission') in the relevant controller
    
    Coupled with this should be the actual route protection using middleware for example : 
    
    Suppose you have a middleware called CheckPermission that checks if a user has a specific permission to access a route. You want to apply this middleware to a specific route.

Define the middleware in app/Http/Middleware/CheckPermission.php:
```php

<?php

namespace App\Http\Middleware;

use Closure;

class CheckPermission
{
    public function handle($request, Closure $next, $permissionName)
    {
        // Check if the authenticated user has the specified permission
        if (!auth()->user()->hasPermissionTo($permissionName)) {
            abort(403, 'Unauthorized'); // You can customize the error message
        }

        return $next($request);
    }
}

```

### Register the middleware in app/Http/Kernel.php by adding it to the $routeMiddleware array:
  
 ```php
 protected $routeMiddleware = [
    // ... other middleware entries
    'checkPermission' => \App\Http\Middleware\CheckPermission::class,
];

```

### Now, you can use the checkPermission middleware in your routes. For example, in routes/web.php:

```php

Route::get('/dashboard', function () {
    return 'Dashboard Page';
})->middleware('checkPermission:create-users'); // Apply the middleware with the specified permission

Route::get('/profile', function () {
    return 'Profile Page';
})->middleware('checkPermission:edit-profile'); // Apply the middleware with a different permission
```

### In this example, we've applied the checkPermission middleware to specific routes (/dashboard and /profile) and provided the required permission name as a parameter to the middleware. This allows you to check if the authenticated user has the necessary permission to access each route. If the user doesn't have the permission, they will receive a 403 (Unauthorized) error.

## In short - add permissions,roles,and users in dashboard and then apply them by

## Apply Middleware for Access Control:

In Routes: You can specify middleware directly in your routes to protect them based on roles or permissions. 
For example, to protect a route that should only be accessible by users with a specific permission, you might do:

```php

Route::get('/sensitive-data', 'SensitiveDataController@index')->middleware('permission:see-sensitive-data');

```

In Controller 

Constructors: Alternatively, you can apply middleware in the constructor of a controller to protect all actions within that controller, or you can specify it for individual actions. For example:

```php

public function __construct()
{
$this->middleware('role:admin')->only('index');
$this->middleware('permission:view-reports,guard:web')->only('reports');
}
```

Middleware Groups: 
If certain groups of routes require the same set of middleware checks, you can group those routes and apply the middleware
to the entire group, simplifying your route definitions.

   
## Trouble seeding DB

I was writing tests and munted the DB, a migrate fresh and db --seed kept failing.
   
    
```php
/var/www/basesite$ php artisan db:seed

   INFO  Seeding database.  

  Database\Seeders\RoleAndPermissionSeeder ........................... RUNNING  

   Spatie\Permission\Exceptions\PermissionAlreadyExists 

  A `create-users` permission already exists for guard `web`.
```
### This was caused by caching and to clear it just ran : 

`php artisan cache:forget spatie.permission.cache `

`php artisan cache:clear`

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
