---
title: "Eloquent: Count Models by Relations - Three Performance Optimizations"
excerpt: When counting the Model records grouped by their type in a relationship, it's tempting to load too many DB queries or too much data into the memory. There are a few ways to optimize it, let's take a look at an example.
date: 2025-09-13
author: tuantq
category: Laravel
tags: [eloquent]
image: /prezet/img/laravel.jpg
draft: false
---
When counting the Model records grouped by their type in a relationship, it's tempting to load too many DB queries or too much data into the memory.
There are a few ways to optimize it, let's take a look at an example.

Let's say, you have a User -> manyToMany -> Role relationship, and you need to return the number of users per role.

The most straightforward (and the worst) way:

```php
use App\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return [
            'administrators' => User::whereHas('roles',
                fn($query) => $query->where('id', 1))->count(),
            'editors' => User::whereHas('roles',
                fn($query) => $query->where('id', 2))->count(),
            'viewers' => User::whereHas('roles',
                fn($query) => $query->where('id', 3))->count(),
        ];
    }
}
```

This would fire THREE queries to the DB, going through the same list of users, just filtering by different criteria.

## Optimization 1. Load all data and filter the Collection

If you load all the users with ONE query to the database, then, whenever you need to count, you won't need to call the database anymore:

```php
$users = User::with('roles')->get();

return [
    'admins' => $users->filter(fn ($user) => $user->roles->contains('id', 1))->count(),
    'editors' => $users->filter(fn ($user) => $user->roles->contains('id', 2))->count(),
    'viewers' => $users->filter(fn ($user) => $user->roles->contains('id', 3))->count(),
];
```

That would fire ONE DB query, instead of THREE queries.

But it has a downside: you load ALL the users into the memory. If you have a lot of users, like 100k+, it may be an even worse performance, even with a lower amount of queries. So I suggest using this method only in case of a smaller amount of data.


## Optimization 2. Inverse what you really need

If you need ONLY those count() with relationships, what you really need is the RELATIONSHIP count, you don't really even need the main Model.

So, instead of loading all the Users with relationship, load Roles with the count of Users.

```php
$roles = Role::withCount('users')->get()->keyBy('id');

return [
    'admins' => $roles[1]->users_count,
    'editors' => $roles[2]->users_count,
    'viewers' => $roles[3]->users_count,
];
```

This will fire only ONE query to the database, will not load all the Users, and return only what you actually need.


## Optimization 3. Raw Query with MySQL CASE-WHEN

For the best performance, you may want to totally skip Eloquent and launch a raw SQL query to the database, calculating what you need.

```php
$roles = DB::select("SELECT
    COUNT(CASE WHEN role_id = 1 THEN 1 END) as administrators,
    COUNT(CASE WHEN role_id = 2 THEN 1 END) as editors,
    COUNT(CASE WHEN role_id = 3 THEN 1 END) as viewers
FROM role_user");
```

Think about it: what you actually need is a pivot table only and its records, so the example above uses exactly that.

>Notice: by removing Eloquent, you lose all its "magic", so the query above would not check for Soft Deletes or other Eloquent features if you use them.

Also keep in mind that this syntax is from MySQL, so if you want to use another DB engine, please check its documentation. Or, if you want to have separate DB engines - one for live DB, one for testing in memory - check the docs for all systems if they support such syntax. If they both don't, it may be a better idea to get back to using Eloquent, which would abstract it for both to work well.
