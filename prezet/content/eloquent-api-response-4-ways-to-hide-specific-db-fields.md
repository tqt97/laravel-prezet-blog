---
title: "Eloquent API Response: 4 Ways to Hide Specific DB Fields"
excerpt: "When creating API applications, you often don't want to return ALL the data via API, especially sensitive fields like passwords. In this short tutorial, I will show 4 methods to return only the fields which you need."
date: "2025-09-14"
author: "tuantq"
category: "Laravel"
tags: [eloquent]
image: "/prezet/img/laravel.jpg"
draft: false
---
When creating API applications, you often don't want to return ALL the data via API, especially sensitive fields like passwords. In this short tutorial, I will show 4 methods to return only the fields which you need.

We will use the same example for all the options: API endpoint /api/users which will return all users.

```php
// routes/api.php
Route::get('users', [UserController::class, 'index']);

// controller
class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }
}
```

Now, our task is to NOT return the id field.

The desired result should look similar to this:

```json
[
    {
        "name": "Manley Reichel",
        "email": "okuneva.jeanie@example.net",
        "created_at": "2023-02-10T11:51:33.000000Z"
    },
    {
        "name": "Elouise Mitchell",
        "email": "kreiger.jaeden@example.net",
        "created_at": "2023-02-10T11:51:33.000000Z"
    },
    {
        "name": "Norris Schoen",
        "email": "taylor73@example.org",
        "created_at": "2023-02-10T11:51:33.000000Z"
    },
]
```

## Option 1. Manually Select Fields

The first method would be just to select fields that we only need.

```php
class UserController extends Controller
{
    public function index()
    {
        return User::select('name', 'email', 'created_at')->get();
    }
}
```

## Option 2. API Resources

The second method is to use [API Resources](https://laravel.com/docs/master/eloquent-resources) and return fields only that are needed.

```php artisan make:resource UserResource```

Then in the UserResource, add the return fields which you need.

**app/Http/Resources/UserResource.php:**

```php
class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'name'       => $this->name,
            'email'      => $this->email,
            'created_at' => $this->created_at,
        ];
    }
}
```

And then return the resource in your controller:

```php
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    public function index()
    {
        return UserResource::collection(User::all());
    }
}
```

Keep in mind that API Resources [wrap the result](https://laravel.com/docs/9.x/eloquent-resources#data-wrapping) into another layer of `data`. To disable that, use `JsonResource::withoutWrapping()`; in the `AppServiceProvider`.

## Option 3. $hidden Model Property

You can use Laravel `$hidden` property on your Model. All attributes that are added to the `$hidden` property are hidden in all API requests.

Default Laravel User model already hides two fields: `password` and `remember_token`. So, we add the `id` there.

**app/Models/User.php:**

```php
class User extends Authenticatable
{
    // ...
    protected $hidden = [
        'id', // add here
        'password',
        'remember_token',
    ];
    // ...
}
```

And in the controller, you can just return your data.

```php
class UserController extends Controller
{
    public function index()
    {
        return User::all();
    }
}
```

## Option 4. setHidden() Collection Method

You can use the `setHidden()` method on the Eloquent Collection to hide fields.

This method overwrites everything which is set in your Model `$hidden` property, so you need to add fields with sensitive data like passwords to the list also.

```php
class UserController extends Controller
{
    public function index()
    {
        return User::all()->setHidden([
            'id', 'email_verified_at', 'password', 'remember_token', 'updated_at'
        ]);
    }
}
```
