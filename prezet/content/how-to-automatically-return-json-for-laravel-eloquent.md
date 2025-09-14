---
title: How to Automatically Return JSON for Laravel Eloquent
excerpt: When working with APIs, you often need to return an Eloquent response in JSON format. By default, Laravel transforms the response into JSON if you just return a Model or Eloquent Collection.
date: 2025-09-13
author: tuantq
category: Laravel
tags: [eloquent]
image: /prezet/img/laravel.jpg
draft: false
---
When working with APIs, you often need to return an Eloquent response in JSON format. By default, Laravel transforms the response into JSON if you just return a Model or Eloquent Collection.

## Example with Eloquent Collection

```php
// Controller:
public function index()
{
    return User::all();
}
```

Will return:

```json
[
    {
        "id": 1,
        "name": "Prof. Marcos Ratke",
        "email": "providenci.hane@example.org",
        "email_verified_at": "2023-01-04T12:26:19.000000Z",
        "created_at": "2023-01-04T12:26:20.000000Z",
        "updated_at": "2023-01-04T12:26:20.000000Z"
    },
    {
        "id": 2,
        "name": "Sincere Casper",
        "email": "jaylin33@example.com",
        "email_verified_at": "2023-01-04T12:26:19.000000Z",
        "created_at": "2023-01-04T12:26:20.000000Z",
        "updated_at": "2023-01-04T12:26:20.000000Z"
    },

    // ... other users
]
```

## Example with Pagination

```php
// Controller:
public function index()
{
    return User::paginate();
}
```

Will return:

```json
{
    "current_page": 1,
    "data": [
        {
            "id": 1,
            "name": "Prof. Marcos Ratke",
            "email": "providenci.hane@example.org",
            "email_verified_at": "2023-01-04T12:26:19.000000Z",
            "created_at": "2023-01-04T12:26:20.000000Z",
            "updated_at": "2023-01-04T12:26:20.000000Z"
        },
        {
            "id": 2,
            "name": "Sincere Casper",
            "email": "jaylin33@example.com",
            "email_verified_at": "2023-01-04T12:26:19.000000Z",
            "created_at": "2023-01-04T12:26:20.000000Z",
            "updated_at": "2023-01-04T12:26:20.000000Z"
        },

        // ... other users
    ],
    "first_page_url": "http://laravel.test/api/users?page=1",
    "from": 1,
    "last_page": 2,
    "last_page_url": "http://laravel.test/api/users?page=2",
    "links": [
        {
            "url": null,
            "label": "&laquo; Previous",
            "active": false
        },
        {
            "url": "http://laravel.test/api/users?page=1",
            "label": "1",
            "active": true
        },
        {
            "url": "http://laravel.test/api/users?page=2",
            "label": "2",
            "active": false
        },
        {
            "url": "http://laravel.test/api/users?page=2",
            "label": "Next &raquo;",
            "active": false
        }
    ],
    "next_page_url": "http://laravel.test/api/users?page=2",
    "path": "http://laravel.test/api/users",
    "per_page": 15,
    "prev_page_url": null,
    "to": 15,
    "total": 20
}
```

As you can see, the users are wrapped in data, and the main JSON contains more data about pages.

## Example with a Single Model

```php
// Controller:
public function show(User $user)
{
    return $user;
}
```

Will return:

```json
{
    "id": 1,
    "name": "Prof. Marcos Ratke",
    "email": "providenci.hane@example.org",
    "email_verified_at": "2023-01-04T12:26:19.000000Z",
    "created_at": "2023-01-04T12:26:20.000000Z",
    "updated_at": "2023-01-04T12:26:20.000000Z"
}
```

## How Does It Work?

Quoting the official [Laravel docs](https://laravel.com/docs/eloquent-serialization#serializing-to-json) about Serialization:

>Since models and collections are converted to JSON when cast to a string, you can return Eloquent objects directly from your application's routes or controllers. Laravel will automatically serialize your Eloquent models and collections to JSON when they are returned from routes or controllers:

-----

## Manually Return JSON

If you want to return the JSON from some other non-Eloquent structure, you can specify it with return response()->json(), passing array as a parameter:

```php
// Controller:
public function update(UpdateUserRequest $request, User $user)
{
    $user->update($request->validated());

    return response()->json(['success' => true]);
}
```

Will return:

```json
{
    "success": true
}
```
